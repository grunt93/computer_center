<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        return view('auth.profile.show', [
            'user' => Auth::user()
        ]);
    }

    public function edit()
    {
        return view('auth.profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:255']
        ], [
            'name.required' => '請輸入姓名',
            'name.max' => '姓名不能超過 255 個字元',
            'student_id.required' => '請輸入學號',
            'student_id.max' => '學號不能超過 255 個字元',
        ]);

        /**
         * @var User $user
         */
        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('status', '個人資料已更新成功！');
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'email_password' => ['required', 'current_password'],
        ], [
            'email.required' => '請輸入電子郵件',
            'email.email' => '請輸入有效的電子郵件地址',
            'email.max' => '電子郵件不能超過 255 個字元',
            'email.unique' => '此電子郵件已被使用',
            'email_password.required' => '請輸入密碼',
            'email_password.current_password' => '密碼不正確',
        ]);

        /**
         * @var User $user
         */
        $user->update([
            'email' => $validated['email'],
        ]);

        return redirect()->route('profile.show')
            ->with('status', '電子郵件已更新成功！');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'current_password.required' => '請輸入目前密碼',
            'current_password.current_password' => '目前密碼不正確',
            'password.required' => '請輸入新密碼',
            'password.confirmed' => '兩次輸入的密碼不相符',
            'password.min' => '密碼至少需要 :min 個字元',
        ]);

        /**
         * @var User $user
         */
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.show')
            ->with('status', '密碼已更新成功！');
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'delete_confirmation' => ['required', 'current_password'],
        ], [
            'delete_confirmation.required' => '請輸入密碼',
            'delete_confirmation.current_password' => '密碼不正確',
        ]);

        Auth::logout();
        /**
         * @var User $user
         */
        $user->delete();

        return redirect()->route('home')
            ->with('status', '帳號已成功刪除！');
    }

    // 管理員方法
    public function index()
    {
        $users = User::paginate(10);
        return view('auth.profile.admins.index', compact('users'));
    }

    public function showUser(User $user)
    {
        return view('auth.profile.admins.show', compact('user'));
    }

    public function editUser(User $user)
    {
        return view('auth.profile.admins.edit', compact('user'));
    }

    // 在 updateUser 方法中加入檢查
    public function updateUser(Request $request, User $user)
    {
        if ($user->email === 'admin') {
            abort(403, '無法修改admin管理員資料');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:staff,admin']  
        ], [
            'name.required' => '請輸入姓名',
            'name.max' => '姓名不能超過 255 個字元',
            'student_id.required' => '請輸入學號',
            'student_id.max' => '學號不能超過 255 個字元',
            'role.required' => '請選擇角色',
            'role.in' => '無效的角色選擇'
        ]);

        $validated['role'] = strval($validated['role']);
        
        $user->update($validated);

        return redirect()->route('profile.users.show', $user)
            ->with('status', '用戶資料已更新成功！');
    }

    // 在 updateUserEmail 方法中加入檢查
    public function updateUserEmail(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            abort(403, '無法修改管理員資料');
        }

        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ], [
            'email.required' => '請輸入電子郵件',
            'email.email' => '請輸入有效的電子郵件地址',
            'email.max' => '電子郵件不能超過 255 個字元',
            'email.unique' => '此電子郵件已被使用',
        ]);

        $user->update([
            'email' => $validated['email'],
        ]);

        return redirect()->route('profile.users.show', $user)
            ->with('status', '用戶電子郵件已更新成功！');
    }

    // 在 updateUserPassword 方法中加入檢查
    public function updateUserPassword(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            abort(403, '無法修改管理員資料');
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.required' => '請輸入新密碼',
            'password.confirmed' => '兩次輸入的密碼不相符',
            'password.min' => '密碼至少需要 :min 個字元',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('profile.users.show', $user)
            ->with('status', '用戶密碼已更新成功！');
    }

    // 在 deleteUser 方法中加入檢查
    public function deleteUser(User $user)
    {
        if ($user->role === 'admin') {
            abort(403, '無法刪除管理員帳號');
        }

        $user->delete();

        return redirect()->route('profile.users.index')
            ->with('status', '用戶帳號已成功刪除！');
    }

    /**
     * 顯示創建用戶的表單
     */
    public function createUser()
    {
        return view('auth.profile.admins.create');
    }

    /**
     * 儲存新用戶
     */
    public function storeUser(Request $request)
    {
        $validation = [
            'name' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:10', 'unique:users,student_id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'in:admin,staff'],
        ];
        
        $messages = [
            'name.required' => '請輸入姓名',
            'name.max' => '姓名不能超過 255 個字元',
            'student_id.required' => '請輸入學號',
            'student_id.max' => '學號不能超過 10 個字元',
            'student_id.unique' => '此學號已被使用',
            'email.required' => '請輸入電子郵件',
            'email.email' => '請輸入有效的電子郵件地址',
            'email.max' => '電子郵件不能超過 255 個字元',
            'email.unique' => '此電子郵件已被使用',
            'role.required' => '請選擇角色',
            'role.in' => '無效的角色選擇'
        ];
        
        // 如果不是使用「首次登入設置密碼」功能，則檢查密碼
        if (!$request->has('skip_password') || !$request->skip_password) {
            $validation['password'] = ['required', 'string', 'min:8', 'confirmed'];
            $messages['password.required'] = '請輸入密碼';
            $messages['password.min'] = '密碼至少需要 8 個字元';
            $messages['password.confirmed'] = '兩次輸入的密碼不相符';
        }
        
        $request->validate($validation, $messages);
        
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'student_id' => strtoupper($request->student_id),
            'role' => $request->role,
        ];
        
        // 如果不是使用「首次登入設置密碼」功能，則設置密碼
        if (!$request->has('skip_password') || !$request->skip_password) {
            $userData['password'] = Hash::make($request->password);
        } else {
            // 密碼為空值
            $userData['password'] = null;
        }
        
        $user = User::create($userData);

        return redirect()->route('profile.users.index')
            ->with('status', '新用戶已成功建立！' . ($request->skip_password ? '用戶首次登入時需要設置密碼。' : ''));
    }
}