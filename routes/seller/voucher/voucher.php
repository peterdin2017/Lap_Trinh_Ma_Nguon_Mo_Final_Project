<?php

use App\Http\Controllers\Seller\Voucher\SellerVoucherController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller/vouchers')
    ->name('seller.vouchers.')
    ->middleware(['auth', 'seller.status'])
    ->controller(SellerVoucherController::class)
    ->group(function (): void {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');

        Route::get('/{voucher}/edit', 'edit')
            ->whereNumber('voucher')
            ->name('edit');

        Route::put('/{voucher}', 'update')
            ->whereNumber('voucher')
            ->name('update');

        Route::patch('/{voucher}/status', 'toggleStatus')
            ->whereNumber('voucher')
            ->name('toggle-status');

    });
