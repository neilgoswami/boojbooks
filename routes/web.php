<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReadingListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [LoginController::class, 'loginForm'])->name('login');
Route::post('/login-user', [LoginController::class, 'login'])->name('login.user');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [LoginController::class, 'registerForm'])->name('register.form');
Route::post('/register-user', [LoginController::class, 'register'])->name('register.user');

Route::middleware('auth')->group(function () {
    Route::get('/lists', [ReadingListController::class, 'index'])->name('lists');
});
