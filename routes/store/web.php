<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\HomeController;
use App\Http\Controllers\Store\Product\ProductController;
use App\Http\Controllers\Store\Account\BuyerOrderController;

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
require __DIR__ . '/products/catalog.php';

// Account
require __DIR__ . '/account/profile.php';
require __DIR__ . '/account/password.php';
require __DIR__ . '/account/addresses.php';
require __DIR__ . '/account/orders.php';
require __DIR__ . '/products/reviews.php';
require __DIR__ . '/products/reports.php';
require __DIR__ . '/account/vouchers.php';

// Checkout
require __DIR__ . '/checkout/checkout.php';

// Shop
require __DIR__ . '/shop/shop.php';

// Chat
require __DIR__ . '/chat/chat.php';
