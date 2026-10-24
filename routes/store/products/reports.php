<?php

use App\Http\Controllers\Store\Product\ProductReportController;
use Illuminate\Support\Facades\Route;

Route::post('/products/{productId}/reports', [ProductReportController::class, 'store'])
    ->whereNumber('productId')
    ->name('store.products.reports.store');
