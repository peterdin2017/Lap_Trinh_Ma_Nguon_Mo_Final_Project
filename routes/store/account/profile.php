<?php

use App\Http\Controllers\Store\Account\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('account')->name('store.account.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
});
