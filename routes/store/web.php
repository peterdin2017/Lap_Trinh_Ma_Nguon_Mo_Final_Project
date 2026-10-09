<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'store.home')->name('store.home');

// Các route Store
require __DIR__ . '/auth/login.php';
require __DIR__ . '/auth/register.php';
require __DIR__ . '/auth/forgot-password.php';
