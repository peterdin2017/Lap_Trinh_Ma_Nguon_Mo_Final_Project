<?php

use App\Http\Controllers\Seller\Product\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller/products')
    ->name('seller.products.')
    ->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])
            ->name('edit');

        Route::put('/{product}', [ProductController::class, 'update'])
            ->name('update');

        Route::patch('/{product}/visibility', [ProductController::class, 'toggleVisibility'])
            ->name('visibility');
    });
