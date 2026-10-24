<?php

use App\Http\Controllers\Store\Account\BuyerOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/account/orders', [BuyerOrderController::class, 'index'])
    ->name('store.account.orders.index');

Route::patch('/account/orders/{id}/confirm-receipt', [BuyerOrderController::class, 'confirmReceipt'])
    ->whereNumber('id')
    ->name('store.account.orders.confirm-receipt');

Route::patch('/account/orders/{id}/cancel', [BuyerOrderController::class, 'cancelOrder'])
    ->whereNumber('id')
    ->name('store.account.orders.cancel');
