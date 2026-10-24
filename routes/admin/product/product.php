<?php

use App\Http\Controllers\Admin\Product\ProductModerationController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/products', [ProductModerationController::class, 'index'])
    ->name('admin.products.index');

Route::patch('/admin/products/{product}/lock', [ProductModerationController::class, 'lock'])
    ->name('admin.products.lock');

Route::patch('/admin/products/{product}/unlock', [ProductModerationController::class, 'unlock'])
    ->name('admin.products.unlock');
