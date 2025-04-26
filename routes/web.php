<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// 個人資料相關路由
Route::controller(ProfileController::class)
    ->middleware(['auth'])
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {
        // 顯示個人資料
        Route::get('/', 'show')->name('show');
        
        // 編輯個人資料
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        
        // 更新電子郵件
        Route::put('/email', 'updateEmail')->name('email.update');
        
        // 更新密碼
        Route::put('/password', 'updatePassword')->name('password.update');
        
        // 刪除帳號
        Route::delete('/', 'deleteAccount')->name('delete');
    });
