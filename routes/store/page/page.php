<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\PageController;

// Điều khoản dịch vụ
Route::get('/terms', [PageController::class, 'terms'])
    ->name('page.terms');

// Chính sách bảo mật
Route::get('/privacy', [PageController::class, 'privacy'])
    ->name('page.privacy');
