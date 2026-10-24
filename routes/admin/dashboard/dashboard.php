<?php

use App\Http\Controllers\Admin\Dashboard\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/admin', [HomeController::class, 'index'])
    ->name('admin.home');
