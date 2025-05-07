<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * 處理登入請求
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        try {
            // 修改驗證規則
            $request->validate([
                'email' => ['required'],
                'password' => ['required', 'string']
            ], [
                'email.required' => '請輸入電子郵件',
                'password.required' => '請輸入密碼'
            ]);

            // 檢查用戶是否存在
            $user = null;
            if ($request->email === 'admin') {
                $user = User::where('email', 'admin')
                         ->where('role', 'admin')
                         ->first();
                $credentials = [
                    'email' => $request->email,
                    'password' => $request->password,
                    'role' => 'admin'
                ];
            } else {
                // 一般用戶驗證 email 格式
                if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                    throw ValidationException::withMessages([
                        'email' => ['請輸入有效的電子郵件地址']
                    ]);
                }
                $user = User::where('email', $request->email)->first();
                $credentials = $request->only('email', 'password');
            }

            // 檢查用戶是否存在
            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => ['找不到此電子郵件的用戶']
                ]);
            }

            // 檢查用戶密碼是否為 null
            if ($user->password === null) {
                // 將用戶 ID 存入 session 以便設置密碼頁面使用
                session(['setup_password_user_id' => $user->id]);
                return redirect()->route('password.setup');
            }

            // 檢查登入次數限制
            $maxAttempts = 5;
            if ($this->limiter()->tooManyAttempts($this->throttleKey($request), $maxAttempts)) {
                $seconds = $this->limiter()->availableIn($this->throttleKey($request));
                throw ValidationException::withMessages([
                    'email' => ['登入嘗試次數太多，請在' . ceil($seconds / 60) . '分鐘後再試']
                ]);
            }

            // 嘗試登入
            if (!Auth::attempt($credentials, $request->boolean('remember'))) {
                $this->limiter()->hit($this->throttleKey($request));
                throw ValidationException::withMessages([
                    'email' => ['帳號或密碼錯誤']
                ]);
            }

            // 登入成功
            $this->limiter()->clear($this->throttleKey($request));
            return $this->sendLoginResponse($request);
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors($e->errors());
        }
    }

    /**
     * 登出後重導向的位置
     */
    protected function loggedOut(Request $request)
    {
        return redirect('/');
    }
}
