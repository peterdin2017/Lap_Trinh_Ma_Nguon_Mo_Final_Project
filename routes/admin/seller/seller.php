<?php

use App\Http\Controllers\Admin\Seller\SellerApprovalController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/sellers', [SellerApprovalController::class, 'index'])
    ->name('admin.sellers.index');

Route::post('/admin/sellers/{seller}/approve', [SellerApprovalController::class, 'approve'])
    ->name('admin.sellers.approve');

Route::post('/admin/sellers/{seller}/reject', [SellerApprovalController::class, 'reject'])
    ->name('admin.sellers.reject');
