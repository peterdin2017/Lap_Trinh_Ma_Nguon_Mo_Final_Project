<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\Seller\SellerApprovalController;
use App\Http\Controllers\Admin\Account\PasswordController;

use App\Http\Controllers\Admin\Account\AccountManagementController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\Admin\ProductModerationController;
use App\Http\Controllers\Admin\ReportManagementController;

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

    Route::get('/admin/accounts', [AccountManagementController::class, 'index'])->name('admin.accounts.index');
    Route::patch('/admin/accounts/{account}/status', [AccountManagementController::class, 'toggle'])->name('admin.accounts.status');
    Route::get('/admin/categories', [CategoryManagementController::class, 'index'])->name('admin.categories.index');
    Route::post('/admin/categories', [CategoryManagementController::class, 'store'])->name('admin.categories.store');
    Route::put('/admin/categories/{category}', [CategoryManagementController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryManagementController::class, 'destroy'])->name('admin.categories.destroy');
    Route::get('/admin/products', [ProductModerationController::class, 'index'])->name('admin.products.index');
    Route::patch('/admin/products/{product}/lock', [ProductModerationController::class, 'lock'])->name('admin.products.lock');
    Route::patch('/admin/products/{product}/unlock', [ProductModerationController::class, 'unlock'])->name('admin.products.unlock');
    Route::get('/admin/reports', [ReportManagementController::class, 'index'])->name('admin.reports.index');
    Route::patch('/admin/reports/{report}/handle', [ReportManagementController::class, 'handle'])->name('admin.reports.handle');

    // Quên mật khẩu
    Route::get('/admin/account/password', [PasswordController::class, 'edit'])
        ->name('admin.account.password.edit');

    Route::put('/admin/account/password', [PasswordController::class, 'update'])
        ->name('admin.account.password.update');

});
