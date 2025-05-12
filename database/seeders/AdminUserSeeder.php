<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 第一個超級管理員
        User::create([
            'name' => '管理員',
            'student_id' => 'X00000001',
            'role' => 'super_admin'
        ]);
    }
}
