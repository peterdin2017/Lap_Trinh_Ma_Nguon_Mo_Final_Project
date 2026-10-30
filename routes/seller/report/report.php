<?php

use App\Http\Controllers\Seller\Report\SellerSalesAnalyticsController;
use App\Http\Controllers\Seller\Report\SellerRevenueReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller/reports')
    ->name('seller.reports.')
    ->group(function (): void {
        Route::get('/revenue', [SellerRevenueReportController::class, 'index'])
            ->name('revenue');

        Route::get('/revenue/export', [SellerRevenueReportController::class, 'export'])
            ->name('revenue.export');

        Route::get('/analytics', [SellerSalesAnalyticsController::class, 'index'])
            ->name('analytics');
    });
