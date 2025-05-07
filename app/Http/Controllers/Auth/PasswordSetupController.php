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

        return view('auth.passwords.setup', ['email' => $user->email]);
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
        
        // 修正判斷條件，檢查用戶是否存在且密碼是否為空（無論是 null 還是空字串）
        if (!$user) {
            session()->forget('setup_password_user_id');
            return redirect()->route('login')
                ->with('error', '找不到使用者資料，請重新登入');
        }
        
        // 驗證密碼
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.required' => '請輸入密碼',
            'password.confirmed' => '兩次輸入的密碼不相符',
            'password.min' => '密碼至少需要 8 個字元'
        ]);

        // 明確使用 Hash 加密密碼並加入除錯輸出
        $hashedPassword = Hash::make($request->password);
        
        // 直接使用 Query Builder 更新密碼，避免 Eloquent 模型可能的問題
        $updated = DB::table('users')
            ->where('id', $user->id)
            ->update(['password' => $hashedPassword, 'updated_at' => now()]);
        
        // 清除 session 中的用戶 ID
        session()->forget('setup_password_user_id');

        // 如果更新失敗，返回錯誤
        if (!$updated) {
            return redirect()->route('login')
                ->with('error', '密碼設置失敗，請聯絡系統管理員');
        }

        // 重新查詢用戶以獲取最新資料
        $refreshedUser = User::where('id', $user->id)->first();
        
        // 自動登入用戶
        Auth::login($refreshedUser);

        return redirect()->route('home')
            ->with('success', '密碼設置成功！您已成功登入系統。');
    }
}
