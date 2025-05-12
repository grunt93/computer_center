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
            // 驗證輸入
            $request->validate([
                'email' => ['required', 'string'],
                // 不再要求密碼必填
                'password' => ['nullable', 'string']
            ], [
                'email.required' => '請輸入電子郵件或學號',
            ]);

            $user = null;
            
            // 判斷輸入是電子郵件還是學號
            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                // 是電子郵件格式
                $user = User::where('email', $request->email)->first();
            } else {
                // 不是電子郵件格式，視為學號
                $user = User::where('student_id', $request->email)->first();
            }

            // 檢查用戶是否存在
            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => ['找不到此帳號的用戶']
                ]);
            }

            // 檢查用戶是否需要設置密碼（只檢查密碼是否為空）
            if ($user->password === null || $user->password === '' || strlen(trim($user->password)) === 0) {
                // 將用戶 ID 存入 session 以便設置密碼頁面使用
                session(['setup_password_user_id' => $user->id]);
                return redirect()->route('password.setup');
            }

            // 密碼不能為空（對於已經設置過密碼的用戶）
            if (empty($request->password)) {
                throw ValidationException::withMessages([
                    'password' => ['請輸入密碼']
                ]);
            }

            // 檢查登入次數限制
            $maxAttempts = 5;
            if ($this->limiter()->tooManyAttempts($this->throttleKey($request), $maxAttempts)) {
                $seconds = $this->limiter()->availableIn($this->throttleKey($request));
                throw ValidationException::withMessages([
                    'email' => ['登入嘗試次數太多，請在' . ceil($seconds / 60) . '分鐘後再試']
                ]);
            }

            // 嘗試登入（使用電子郵件或學號）
            $credentials = [];
            
            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                $credentials = ['email' => $request->email, 'password' => $request->password];
            } else {
                $credentials = ['student_id' => $request->email, 'password' => $request->password];
            }
            
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