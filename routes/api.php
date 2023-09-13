<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
Route::post('/register/{role}', [App\Http\Controllers\UserController::class, 'register'])->middleware('role_checker');
Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile'])->middleware('custom_auth');
Route::get('/index', [App\Http\Controllers\UserController::class, 'index'])->middleware('custom_auth', 'only_admin_author');
Route::post('/forgot/password', [App\Http\Controllers\UserController::class, 'forgotPassword']);
Route::post('/forgot/password/callback', [App\Http\Controllers\UserController::class, 'forgotPasswordCallback']);
