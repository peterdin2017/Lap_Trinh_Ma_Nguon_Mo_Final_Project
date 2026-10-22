<?php

use App\Http\Controllers\Seller\Account\ShopInformationController;
use Illuminate\Support\Facades\Route;

Route::get('/seller/shop-information', [ShopInformationController::class, 'edit'])
    ->name('seller.shop-information.edit');

Route::put('/seller/shop-information', [ShopInformationController::class, 'update'])
    ->name('seller.shop-information.update');
