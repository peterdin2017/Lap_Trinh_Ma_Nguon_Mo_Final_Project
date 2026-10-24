<?php

use App\Http\Controllers\Seller\Order\SellerOrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller/orders')
    ->name('seller.orders.')
    ->controller(SellerOrderController::class)
    ->group(function (): void {
        Route::get('/', 'index')->name('index');

        Route::get('/{order}', 'show')
            ->whereNumber('order')
            ->name('show');

        Route::patch('/{order}/status', 'updateStatus')
            ->whereNumber('order')
            ->name('status');

        Route::get('/{order}/shipping-label', 'printShippingLabel')
            ->whereNumber('order')
            ->name('shipping-label');
    });
