<?php

use App\Http\Controllers\Store\Checkout\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/checkout', [CheckoutController::class, 'index'])
    ->name('store.checkout.index');

Route::post('/checkout', [CheckoutController::class, 'store'])
    ->name('store.checkout.store');

Route::get('/checkout/success', [CheckoutController::class, 'success'])
    ->name('store.checkout.success');
