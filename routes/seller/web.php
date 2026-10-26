<?php

use App\Http\Controllers\Seller\HomeController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth/login.php';
require __DIR__ . '/auth/register.php';

// Trang thông báo cần đăng nhập, nhưng không chạy qua seller.status
// để tránh redirect chính trang pending/rejected/suspended về lại nó.
Route::middleware('auth')->group(function () {
    Route::view('/seller/application/pending', 'seller.application.pending')
        ->name('seller.application.pending');

    Route::view('/seller/application/rejected', 'seller.application.rejected')
        ->name('seller.application.rejected');

    Route::view('/seller/application/suspended', 'seller.application.suspended')
        ->name('seller.application.suspended');
});

// Chỉ trang Seller chính cần kiểm tra trạng thái hồ sơ.
Route::middleware(['auth', 'seller.status'])->group(function () {
    Route::get('/seller', [HomeController::class, 'index'])
        ->name('seller.home');

    require __DIR__ . '/products/product.php';
    require __DIR__ . '/account/shop-information.php';
    require __DIR__ . '/account/change-password.php';
    require __DIR__ . '/chat/chat.php';
    require __DIR__ . '/order/order.php';
    require __DIR__ . '/voucher/voucher.php';
});


