<?php

use App\Http\Controllers\Admin\Voucher\PlatformVoucherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:admin', 'admin'])
    ->prefix('admin/vouchers')
    ->name('admin.vouchers.')
    ->controller(PlatformVoucherController::class)
    ->group(function (): void {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');

        Route::get('/{platformVoucher}/edit', 'edit')
            ->whereNumber('platformVoucher')
            ->name('edit');

        Route::put('/{platformVoucher}', 'update')
            ->whereNumber('platformVoucher')
            ->name('update');

        Route::patch('/{platformVoucher}/toggle-status', 'toggleStatus')
            ->whereNumber('platformVoucher')
            ->name('toggle-status');

    });
