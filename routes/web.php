<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\PasswordSetupController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DiskReplacementController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

// 密碼設置路由
Route::controller(PasswordSetupController::class)->group(function () {
    Route::get('/password/setup', 'showSetupForm')->name('password.setup');
    Route::post('/password/setup', 'setup')->name('password.setup.submit');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

// 個人資料相關路由
Route::controller(ProfileController::class)
    ->middleware(['auth'])
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {
        // 一般用戶路由
        Route::get('/', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::put('/email', 'updateEmail')->name('email.update');
        Route::put('/password', 'updatePassword')->name('password.update');
        Route::delete('/', 'deleteAccount')->name('delete');

        // 管理員路由
        Route::middleware(['super_admin'])->group(function () {
            Route::get('/users', 'index')->name('users.index');
            
            // 將具體路由放在參數化路由前面
            Route::get('/users/create', 'createUser')->name('users.create');
            Route::post('/users', 'storeUser')->name('users.store');
            
            // 參數化路由放在後面
            Route::get('/users/{user}', 'showUser')->name('users.show');
            Route::get('/users/{user}/edit', 'editUser')->name('users.edit');
            Route::put('/users/{user}', 'updateUser')->name('users.update');
            Route::put('/users/{user}/email', 'updateUserEmail')->name('users.email.update');
            Route::put('/users/{user}/password', 'updateUserPassword')->name('users.password.update');
            Route::delete('/users/{user}', 'deleteUser')->name('users.delete');
        });
    });

Route::controller(ClassroomController::class)
    ->prefix('classroom')
    ->name('classroom.')
    ->group(function(){
        Route::middleware(['auth'])->group(function(){
            Route::get('/refresh', 'showRefreshForm')->name('refresh.form');
            Route::post('/refresh', 'refresh')->name('refresh');
            Route::get('/status', 'status')->name('status');
        });
        
        Route::get('/open', 'open')->name('open');
    });

Route::controller(DiskReplacementController::class)
    ->middleware(['auth'])
    ->prefix('disk-replacement')
    ->name('disk-replacement.')
    ->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');

        // 管理員路由 - 注意這裡的中間件處理
        Route::middleware(['admin'])->group(function(){
            Route::get('/{diskReplacement}/edit', 'edit')->name('edit');
            Route::put('/{diskReplacement}', 'update')->name('update');
            Route::delete('/{diskReplacement}', 'destroy')->name('destroy');
        });
    });