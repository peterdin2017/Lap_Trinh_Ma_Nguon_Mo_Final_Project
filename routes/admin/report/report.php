<?php

use App\Http\Controllers\Admin\Report\AdminStatisticsController;
use App\Http\Controllers\Admin\Report\ReportManagementController;
use Illuminate\Support\Facades\Route;

// Thống kê hoạt động toàn sàn
Route::get('/admin/reports/statistics', [AdminStatisticsController::class, 'index'])
    ->name('admin.reports.statistics');

// Xuất báo cáo thống kê ra Excel
Route::get('/admin/reports/statistics/export', [AdminStatisticsController::class, 'export'])
    ->name('admin.reports.statistics.export');

// Quản lý báo cáo vi phạm
Route::get('/admin/reports', [ReportManagementController::class, 'index'])
    ->name('admin.reports.index');

Route::patch('/admin/reports/{report}/handle', [ReportManagementController::class, 'handle'])
    ->name('admin.reports.handle');
