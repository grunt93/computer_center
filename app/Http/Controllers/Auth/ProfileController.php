<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function __construct()
    {
        // 所有用戶方法需要登入
        $this->middleware('auth');
        
        // 用戶管理功能只有超級管理員可以訪問
        $this->middleware(function ($request, $next) {
            if (in_array($request->route()->getName(), [
                'profile.users.index', 'profile.users.show', 'profile.users.edit',
                'profile.users.update', 'profile.users.update.email', 'profile.users.update.password',
                'profile.users.delete', 'profile.users.create', 'profile.users.store'
            ])) {
                if (Auth::user()->role !== User::ROLE_SUPER_ADMIN) {
                    abort(403, '只有超級管理員可以訪問用戶管理功能');
                }
            }
            
            return $next($request);
        });
    }

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
    public function index(Request $request)
    {
        $query = User::query();
        
        // 如果有姓名搜尋關鍵字，則進行過濾
        if ($request->has('name') && !empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        
        // 如果有學號搜尋關鍵字，則進行過濾
        if ($request->has('student_id') && !empty($request->student_id)) {
            $query->where('student_id', 'like', '%' . $request->student_id . '%');
        }
        
        $users = $query->paginate(10)->withQueryString();
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

    // 在 updateUser 方法中修改檢查邏輯
    public function updateUser(Request $request, User $user)
    {
        // 修正邏輯錯誤：正確檢查是否為超級管理員
        if (Auth::user()->role !== User::ROLE_SUPER_ADMIN) {
            abort(403, '只有超級管理員可以修改用戶資料');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:staff,admin,super_admin']  
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

    // 在 updateUserEmail 方法中更新檢查邏輯
    public function updateUserEmail(Request $request, User $user)
    {
        // 改為檢查是否為超級管理員
        if (Auth::user()->role !== User::ROLE_SUPER_ADMIN) {
            abort(403, '只有超級管理員可以修改用戶電子郵件');
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

    // 在 updateUserPassword 方法中更新檢查邏輯
    public function updateUserPassword(Request $request, User $user)
    {
        // 改為檢查是否為超級管理員
        if (Auth::user()->role !== User::ROLE_SUPER_ADMIN) {
            abort(403, '只有超級管理員可以重設用戶密碼');
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

    // 在 deleteUser 方法中更新檢查邏輯
    public function deleteUser(User $user)
    {
        // 改為檢查是否為超級管理員
        if (Auth::user()->role !== User::ROLE_SUPER_ADMIN) {
            abort(403, '只有超級管理員可以刪除用戶帳號');
        }

        // 防止刪除自己的帳號
        if ($user->id === Auth::id()) {
            abort(403, '無法刪除自己的帳號');
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
            'role' => ['required', 'string', 'in:admin,staff,super_admin'],
        ];
        
        $messages = [
            'name.required' => '請輸入姓名',
            'name.max' => '姓名不能超過 255 個字元',
            'student_id.required' => '請輸入學號',
            'student_id.max' => '學號不能超過 10 個字元',
            'student_id.unique' => '此學號已被使用',
            'role.required' => '請選擇角色',
            'role.in' => '無效的角色選擇'
        ];
        
        $request->validate($validation, $messages);
        
        // 學號轉為大寫
        $studentId = strtoupper($request->student_id);
        
        // 預設email為 '學號' + '@gapps.uch.edu.tw'
        $defaultEmail = $studentId . '@gapps.uch.edu.tw';
        
        // 使用 DB 原始方法插入資料，預設email為學號@gapps.uch.edu.tw
        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $defaultEmail, 
            'student_id' => $studentId,
            'role' => $request->role,
            'password' => '',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('profile.users.index')
            ->with('status', '新用戶已成功建立！用戶首次登入時需要設置密碼。預設電子郵件為：' . $defaultEmail);
    }
}