<?php

use App\Http\Controllers\Store\Product\ReviewController;
use Illuminate\Support\Facades\Route;

Route::post('/products/{productId}/reviews', [ReviewController::class, 'store'])
    ->whereNumber('productId')
    ->name('store.products.reviews.store');
