<?php

use App\Http\Controllers\Admin\Category\CategoryManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/categories', [CategoryManagementController::class, 'index'])
    ->name('admin.categories.index');

Route::post('/admin/categories', [CategoryManagementController::class, 'store'])
    ->name('admin.categories.store');

Route::put('/admin/categories/{category}', [CategoryManagementController::class, 'update'])
    ->name('admin.categories.update');

Route::delete('/admin/categories/{category}', [CategoryManagementController::class, 'destroy'])
    ->name('admin.categories.destroy');
