<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CartsController;

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
    Route::get('/myprofile', [UsersController::class, 'user_profile'])->middleware('auth:users')
        ->name('show user profile');
    Route::post('/add_to_cart', [CartsController::class, 'add_to_cart'])->middleware('auth:users')
        ->name('add_to_cart_route');
    Route::get('/carts', [CartsController::class, 'carts'])->middleware('auth:users')
        ->name('carts_route');
    Route::delete('carts/delete/{id}', [CartsController::class, 'delete_cart'])->middleware('auth:users')
        ->name('delete_cart_item');
    Route::get('/info', [UsersController::class, 'show'])->middleware('auth:users')
        ->name('test_middleware');
});

Route::prefix('/shop')->group(function () {
    Route::get('/products', [ProductsController::class, 'products'])->name('get_all_product');
    Route::get('product/{id}', [ProductsController::class, 'single_product'])->name('show_single_product');
});

Route::prefix('/admin')->group(function () {
    Route::post('/login', [AdminsController::class, 'login'])
        ->name('admin_login');
    Route::post('/register', [AdminsController::class, 'register'])
        ->name('admin_register');
    Route::get('/all_users', [UsersController::class, 'all_users'])->middleware('auth:admins')
        ->name('all_users');
    Route::get('/users', [UsersController::class, 'User_Paginate'])->middleware('auth:admins')
        ->name('user_pagination');
    Route::patch('/edit_user/{id}', [UsersController::class, 'edit_user'])->middleware('auth:admins')
        ->name('edit_user');
    Route::get('/user/{id}', [UsersController::class, 'get_user_by_id'])->middleware('auth:admins')
        ->name('get user by id');
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
    Route::get('/product/{id}', [ProductsController::class, 'single_product'])->middleware('auth:admins')
        ->name('single_product');
    Route::get('/info', [AdminsController::class, 'show'])->middleware('auth:admins')
        ->name('test_middleware');
});
