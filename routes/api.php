<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [AuthController::class, 'register'])->name('users.register');
Route::post('/login', [AuthController::class, 'login'])->name('users.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('users.logout');
Route::post('/logout_all', [AuthController::class, 'logout_all'])->name('users.logout_all');

Route::get('/user', [UserController::class, 'show'])->name('users.show');

Route::apiResource('books', BookController::class);
Route::post('books/{book}/checkout', [BookController::class, 'checkout'])->name('books.checkout');
Route::post('books/{book}/checkin', [BookController::class, 'checkin'])->name('books.checkin');
