<?php

use App\Http\Controllers\Store\HomeController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Store\Product\ProductController;

Route::get('/', [HomeController::class, 'index'])
    ->name('store.home');

Route::get('/products/{productId}', [ProductController::class, 'show'])
    ->whereNumber('productId')
    ->name('store.products.show');


// Các route Store
require __DIR__ . '/auth/login.php';
require __DIR__ . '/auth/register.php';
require __DIR__ . '/auth/forgot-password.php';

require __DIR__ . '/products/cart.php';

// Account
require __DIR__ . '/account/profile.php';
require __DIR__ . '/account/password.php';
require __DIR__ . '/account/addresses.php';

// Checkout
require __DIR__ . '/checkout/checkout.php';
