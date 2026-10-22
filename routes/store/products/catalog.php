<?php

use App\Http\Controllers\Store\Product\CatalogController;
use App\Http\Controllers\Store\Product\ReviewController;
use Illuminate\Support\Facades\Route;

// Danh sách / tìm kiếm: ?q=&category=&min_price=&max_price=&sort=newest|best_selling|price_asc|price_desc&page=
Route::get('/products', [CatalogController::class, 'index'])
    ->name('store.products.index');

Route::post('/products/{productId}/reviews', [ReviewController::class, 'store'])
    ->whereNumber('productId')
    ->name('store.products.reviews.store');
