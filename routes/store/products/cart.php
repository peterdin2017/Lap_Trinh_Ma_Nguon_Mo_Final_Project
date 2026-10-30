<?php

use App\Http\Controllers\Store\Product\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/cart', [CartController::class, 'index'])
    ->name('store.cart.index');

Route::post('/cart/items', [CartController::class, 'store'])
    ->name('store.cart.items.store');

Route::patch('/cart/items/{cartItemId}', [CartController::class, 'updateQuantity'])
    ->whereNumber('cartItemId')
    ->name('store.cart.items.update');

Route::delete('/cart/items', [CartController::class, 'destroySelected'])
    ->name('store.cart.items.destroy-selected');

Route::delete('/cart/items/{cartItemId}', [CartController::class, 'destroy'])
    ->whereNumber('cartItemId')
    ->name('store.cart.items.destroy');
