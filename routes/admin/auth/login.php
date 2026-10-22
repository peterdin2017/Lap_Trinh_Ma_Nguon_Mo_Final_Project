<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])
    ->name('admin.login.form');

Route::post('/admin/login', [LoginController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('admin.login.submit');

Route::post('/admin/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');
