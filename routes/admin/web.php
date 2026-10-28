<?php

use Illuminate\Support\Facades\Route;

// Route đăng nhập không yêu cầu guard admin.
require __DIR__ . '/auth/login.php';

Route::middleware(['auth:admin', 'admin'])->group(function (): void {
    require __DIR__ . '/dashboard/dashboard.php';
    require __DIR__ . '/seller/seller.php';
    require __DIR__ . '/account/account.php';
    require __DIR__ . '/account/password.php';
    require __DIR__ . '/category/category.php';
    require __DIR__ . '/product/product.php';
    require __DIR__ . '/report/report.php';
    require __DIR__ . '/vouchers/voucher.php';
});
