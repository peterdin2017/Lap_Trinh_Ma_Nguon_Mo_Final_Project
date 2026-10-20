<?php

use App\Http\Controllers\Store\Account\AddressController;
use Illuminate\Support\Facades\Route;

Route::prefix('account/addresses')
    ->name('store.account.addresses.')
    ->group(function () {
        Route::get('/', [AddressController::class, 'index'])
            ->name('index');

        Route::get('/create', [AddressController::class, 'create'])
            ->name('create');

        Route::post('/', [AddressController::class, 'store'])
            ->name('store');

        Route::get('/{addressId}/edit', [AddressController::class, 'edit'])
            ->whereNumber('addressId')
            ->name('edit');

        Route::put('/{addressId}', [AddressController::class, 'update'])
            ->whereNumber('addressId')
            ->name('update');

        Route::delete('/{addressId}', [AddressController::class, 'destroy'])
            ->whereNumber('addressId')
            ->name('destroy');

        Route::patch('/{addressId}/default', [AddressController::class, 'makeDefault'])
            ->whereNumber('addressId')
            ->name('default');
    });
