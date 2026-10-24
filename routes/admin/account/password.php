<?php

use App\Http\Controllers\Admin\Account\PasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/account/password', [PasswordController::class, 'edit'])
    ->name('admin.account.password.edit');

Route::put('/admin/account/password', [PasswordController::class, 'update'])
    ->name('admin.account.password.update');
