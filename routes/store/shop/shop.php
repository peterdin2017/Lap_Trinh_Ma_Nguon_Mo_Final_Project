<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\Shop\ShopController;

Route::get('/shops/{sellerId}', [ShopController::class, 'show'])
    ->whereNumber('sellerId')
    ->name('store.shops.show');
