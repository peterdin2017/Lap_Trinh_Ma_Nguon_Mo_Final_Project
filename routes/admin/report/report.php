<?php

use App\Http\Controllers\Admin\Report\ReportManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/reports', [ReportManagementController::class, 'index'])
    ->name('admin.reports.index');

Route::patch('/admin/reports/{report}/handle', [ReportManagementController::class, 'handle'])
    ->name('admin.reports.handle');
