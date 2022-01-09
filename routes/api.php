<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoriesController;

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
    Route::post('/register', [UsersController::class, 'register'])
        ->name('signup_route');
    Route::post('/login', [UsersController::class, 'login'])
        ->name('login_route');
    Route::patch('/edit_profile', [UsersController::class, 'edit_profile'])->middleware('auth:users')
        ->name('edit_route');
    Route::get('/info', [UsersController::class, 'show'])->middleware('auth:users')
        ->name('test_middleware');
});

Route::prefix('/admin')->group(function () {
    Route::post('/login', [AdminsController::class, 'login'])
        ->name('admin_login');
    Route::get('/all_users', [UsersController::class, 'all_users'])->middleware('auth:admins')
        ->name('all_users');
    Route::get('/users', [UsersController::class, 'User_Paginate'])->middleware('auth:admins')
        ->name('user_pagination');
    Route::patch('/edit_user/{id}', [UsersController::class, 'edit_user'])->middleware('auth:admins')
        ->name('edit_user');
    Route::post('/product/add_category', [CategoriesController::class, 'add_category'])->middleware('auth:admins')
        ->name('add_category');
    Route::post('/product/add', [ProductsController::class, 'add_product'])->middleware('auth:admins')
        ->name('add_product');
    Route::post('/product/update/{id}', [ProductsController::class, 'update_product'])->middleware('auth:admins')
        ->name('update_product');
    Route::delete('/product/delete/{id}', [ProductsController::class, 'delete_product'])->middleware('auth:admins')
        ->name('delete_product');
    Route::get('/product/all', [ProductsController::class, 'products'])->middleware('auth:admins')
        ->name('show_products');
    Route::get('/info', [AdminsController::class, 'show'])->middleware('auth:admins')
        ->name('test_middleware');
});
