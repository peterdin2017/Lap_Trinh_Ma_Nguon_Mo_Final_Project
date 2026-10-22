<?php

use App\Http\Controllers\Seller\Account\PasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/seller/account/change-password', [PasswordController::class, 'edit'])
    ->name('seller.account.password.edit');

Route::put('/seller/account/change-password', [PasswordController::class, 'update'])
    ->name('seller.account.password.update');
