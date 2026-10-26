<?php

use App\Http\Controllers\Seller\Report\SellerRevenueReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller/reports')
    ->name('seller.reports.')
    ->controller(SellerRevenueReportController::class)
    ->group(function (): void {
        Route::get('/revenue/export', 'export')->name('revenue.export');
        Route::get('/revenue', 'index')->name('revenue');
    });
