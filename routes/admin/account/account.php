<?php

use App\Http\Controllers\Admin\Account\AccountManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/accounts', [AccountManagementController::class, 'index'])
    ->name('admin.accounts.index');

Route::patch('/admin/accounts/{account}/status', [AccountManagementController::class, 'toggle'])
    ->name('admin.accounts.status');
