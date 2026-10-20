<?php

use App\Http\Controllers\Store\Account\PasswordController;
use Illuminate\Support\Facades\Route;

Route::prefix('account')->name('store.account.')->group(function () {
    Route::get('/password', [PasswordController::class, 'show'])
        ->name('password');

    Route::put('/password', [PasswordController::class, 'update'])
        ->name('password.update');
});
