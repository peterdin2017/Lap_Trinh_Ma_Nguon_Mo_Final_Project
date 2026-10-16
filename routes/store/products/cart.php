<?php

use App\Http\Controllers\Store\Product\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/cart', [CartController::class, 'index'])
    ->name('store.cart.index');

Route::post('/cart/items', [CartController::class, 'store'])
    ->name('store.cart.items.store');

Route::delete('/cart/items/{key}', [CartController::class, 'destroy'])
    ->where('key', '[^/]+')
    ->name('store.cart.items.destroy');
