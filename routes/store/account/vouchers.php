<?php

use App\Http\Controllers\Store\Account\BuyerVoucherController;
use Illuminate\Support\Facades\Route;

Route::get('/account/vouchers', [BuyerVoucherController::class, 'index'])
    ->name('store.account.vouchers.index');

Route::post('/account/vouchers/{type}/{voucher}/save', [BuyerVoucherController::class, 'save'])
    ->whereIn('type', ['seller', 'platform'])
    ->whereNumber('voucher')
    ->name('store.account.vouchers.save');

Route::delete('/account/vouchers/{type}/{voucher}/save', [BuyerVoucherController::class, 'remove'])
    ->whereIn('type', ['seller', 'platform'])
    ->whereNumber('voucher')
    ->name('store.account.vouchers.remove');
