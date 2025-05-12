<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class PasswordSetupController extends Controller
{
    /**
     * 顯示密碼設置頁面
     *
     * @return \Illuminate\View\View
     */
    public function showSetupForm()
    {
        // 檢查 session 中是否有用戶 ID
        if (!session()->has('setup_password_user_id')) {
            return view('auth.passwords.error', ['message' => '無效的請求，請重新登入']);
        }

        $user = User::where('id', session('setup_password_user_id'))->first();
        
        // 檢查用戶是否存在
        if (!$user) {
            session()->forget('setup_password_user_id');
            return view('auth.passwords.error', ['message' => '找不到使用者資料，請重新登入']);
        }

        // 如果用戶沒有設置email，預設使用學號@gapps.uch.edu.tw
        $defaultEmail = $user->email ?? ($user->student_id . '@gapps.uch.edu.tw');

        return view('auth.passwords.setup', ['email' => $defaultEmail]);
    }

    /**
     * 處理密碼設置請求
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setup(Request $request)
    {
        // 檢查 session 中是否有用戶 ID
        if (!session()->has('setup_password_user_id')) {
            return redirect()->route('login')
                ->with('error', '無效的請求，請重新登入');
        }

        $user = User::find(session('setup_password_user_id'));
        
        // 檢查用戶是否存在
        if (!$user) {
            session()->forget('setup_password_user_id');
            return redirect()->route('login')
                ->with('error', '找不到使用者資料，請重新登入');
        }
        
        // 驗證輸入
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.required' => '請輸入電子郵件',
            'email.email' => '請輸入有效的電子郵件地址',
            'email.max' => '電子郵件不能超過 255 個字元',
            'email.unique' => '此電子郵件已被使用',
            'password.required' => '請輸入密碼',
            'password.confirmed' => '兩次輸入的密碼不相符',
            'password.min' => '密碼至少需要 8 個字元'
        ]);

        // 使用 Hash 加密密碼
        $hashedPassword = Hash::make($request->password);
        
        // 直接使用 Query Builder 更新密碼與電子郵件
        $updated = DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => $hashedPassword, 
                'email' => $request->email,
                'updated_at' => now()
            ]);
        
        // 清除 session 中的用戶 ID
        session()->forget('setup_password_user_id');

        // 如果更新失敗，返回錯誤
        if (!$updated) {
            return redirect()->route('login')
                ->with('error', '設置失敗，請聯絡系統管理員');
        }

        // 重新查詢用戶以獲取最新資料
        $refreshedUser = User::where('id', $user->id)->first();
        
        // 自動登入用戶
        Auth::login($refreshedUser);

        return redirect()->route('home')
            ->with('success', '個人資料設置成功！您已成功登入系統。');
    }
}
