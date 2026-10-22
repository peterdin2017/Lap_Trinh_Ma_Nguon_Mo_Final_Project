<?php

use App\Http\Controllers\Seller\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/seller/login', [LoginController::class, 'showLoginForm'])
    ->name('seller.login.form');

Route::post('/seller/login', [LoginController::class, 'login'])
    ->name('seller.login.submit');

Route::post('/seller/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('seller.logout');
