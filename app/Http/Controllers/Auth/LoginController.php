<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
            // 驗證請求資料
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string']
            ], [
                'email.required' => '請輸入電子郵件',
                'email.email' => '請輸入有效的電子郵件地址',
                'password.required' => '請輸入密碼'
            ]);

            // 檢查登入次數限制
            $maxAttempts = 5;
            if ($this->limiter()->tooManyAttempts($this->throttleKey($request), $maxAttempts)) {
                $seconds = $this->limiter()->availableIn($this->throttleKey($request));
                throw ValidationException::withMessages([
                    'email' => ['登入嘗試次數太多，請在' . ceil($seconds / 60) . '分鐘後再試']
                ]);
            }

            // 嘗試登入
            if (!$this->attemptLogin($request)) {
                $this->limiter()->hit($this->throttleKey($request));
                throw ValidationException::withMessages([
                    'email' => ['電子郵件或密碼錯誤']
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
