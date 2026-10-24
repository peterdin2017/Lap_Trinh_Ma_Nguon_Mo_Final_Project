<?php

use Illuminate\Support\Facades\Route;

// Route đăng nhập/đăng xuất Admin không yêu cầu middleware admin.
require __DIR__ . '/auth/login.php';

// Các route bên dưới chỉ dành cho Admin đã đăng nhập.
Route::middleware(['auth', 'admin'])->group(function (): void {
    require __DIR__ . '/dashboard/dashboard.php';
    require __DIR__ . '/seller/seller.php';
    require __DIR__ . '/account/account.php';
    require __DIR__ . '/account/password.php';
    require __DIR__ . '/category/category.php';
    require __DIR__ . '/product/product.php';
    require __DIR__ . '/report/report.php';
});
