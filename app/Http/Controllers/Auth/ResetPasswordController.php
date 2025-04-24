<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
/*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * 重設密碼後重新導向的位置
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * 重設密碼成功的回應
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->redirectPath())
            ->with('status', '密碼已成功重設！');
    }

    /**
     * 重設密碼失敗的回應
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        $error = trans($response);
        
        // 自訂錯誤訊息
        switch ($response) {
            case Password::INVALID_TOKEN:
                $error = '密碼重設連結已過期或無效。';
                break;
            case Password::INVALID_USER:
                $error = '找不到使用該電子郵件地址的使用者。';
                break;
            case Password::PASSWORD_RESET:
                $error = '密碼重設成功。';
                break;
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => $error]);
    }

    /**
     * 取得密碼驗證規則
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ];
    }

    /**
     * 取得密碼驗證錯誤訊息
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'password.required' => '請輸入新密碼',
            'password.confirmed' => '兩次輸入的密碼不相符',
            'password.min' => '密碼至少需要 8 個字元',
            'email.required' => '請輸入電子郵件',
            'email.email' => '請輸入有效的電子郵件地址',
        ];
    }
}
