<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\Seller\SellerApprovalController;
use App\Http\Controllers\Admin\Account\PasswordController;

// Route đăng nhập và đăng xuất Admin
require __DIR__ . '/auth/login.php';

// Chỉ tài khoản đã đăng nhập với role admin mới được vào
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [HomeController::class, 'index'])
        ->name('admin.home');

    Route::get('/admin/sellers', [SellerApprovalController::class, 'index'])
        ->name('admin.sellers.index');

    Route::post('/admin/sellers/{seller}/approve', [SellerApprovalController::class, 'approve'])
        ->name('admin.sellers.approve');

    Route::post('/admin/sellers/{seller}/reject', [SellerApprovalController::class, 'reject'])
        ->name('admin.sellers.reject');

    // Quên mật khẩu
    Route::get('/admin/account/password', [PasswordController::class, 'edit'])
        ->name('admin.account.password.edit');

    Route::put('/admin/account/password', [PasswordController::class, 'update'])
        ->name('admin.account.password.update');

});
