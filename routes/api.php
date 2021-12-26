<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

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
    Route::get('/info', [UsersController::class, 'show'])->middleware('auth:api');
});
