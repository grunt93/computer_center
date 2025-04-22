<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * 驗證器
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'regex:/^[A-Za-z]\d{8}$/', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => '請輸入姓名',
            'name.string' => '姓名必須是字串',
            'name.max' => '姓名不能超過 :max 個字',
            
            'student_id.required' => '請輸入學號',
            'student_id.string' => '學號必須是字串',
            'student_id.regex' => '學號格式不正確（須為一個英文字母加上8個數字）',
            'student_id.unique' => '此學號已被註冊',
            
            'email.required' => '請輸入電子郵件',
            'email.string' => '電子郵件必須是字串',
            'email.email' => '請輸入有效的電子郵件地址',
            'email.max' => '電子郵件不能超過 :max 個字',
            'email.unique' => '此電子郵件已被註冊',
            
            'password.required' => '請輸入密碼',
            'password.string' => '密碼必須是字串',
            'password.min' => '密碼至少需要 :min 個字元',
            'password.confirmed' => '密碼確認不符'
        ]);
    }

    /**
     * 生成帳號
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'student_id' => strtoupper($data['student_id']), // 確保學號儲存為大寫
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }
}
