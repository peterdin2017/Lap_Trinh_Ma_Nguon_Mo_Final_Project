<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\Auth\LoginController;

Route::get('/login', [LoginController::class, 'showForm'])
    ->name('login.form');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
