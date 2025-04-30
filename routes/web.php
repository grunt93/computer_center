<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\ClassroomController;
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
        // 一般用戶路由
        Route::get('/', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::put('/email', 'updateEmail')->name('email.update');
        Route::put('/password', 'updatePassword')->name('password.update');
        Route::delete('/', 'deleteAccount')->name('delete');

        // 管理員路由
        Route::middleware(['admin'])->group(function () {
            Route::get('/users', 'index')->name('users.index');
            Route::get('/users/{user}', 'showUser')->name('users.show');
            Route::get('/users/{user}/edit', 'editUser')->name('users.edit');
            Route::put('/users/{user}', 'updateUser')->name('users.update');
            Route::put('/users/{user}/email', 'updateUserEmail')->name('users.email.update');
            Route::put('/users/{user}/password', 'updateUserPassword')->name('users.password.update');
            Route::delete('/users/{user}', 'deleteUser')->name('users.delete');
        });
    });

Route::get('/classroom/refresh', [ClassroomController::class, 'showRefreshForm'])->name('classroom.refresh.form');
Route::post('/classroom/refresh', [ClassroomController::class, 'refresh'])->name('classroom.refresh');
Route::get('/classroom/status', [ClassroomController::class, 'status'])->name('classroom.status');