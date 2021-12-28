<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/user')->group(function () {
    Route::post('/register', [UsersController::class, 'register'])->name('signup_route');
    Route::post('/login', [UsersController::class, 'login'])->name('login_route');
    Route::get('/info', [UsersController::class, 'show'])->middleware('auth:users')->name('test_middleware');
});

Route::prefix('/admin')->group(function () {
    Route::post('/login', [AdminsController::class, 'login'])->name('admin_login');
    Route::get('/all_users', [AdminsController::class, 'all_users'])->middleware('auth:admins')->name('all_users');
    Route::get('/users', [AdminsController::class, 'User_Paginate'])->middleware('auth:admins')->name('user_pagination');
    Route::get('/info', [AdminsController::class, 'show'])->middleware('auth:admins')->name('test_middleware');
});
