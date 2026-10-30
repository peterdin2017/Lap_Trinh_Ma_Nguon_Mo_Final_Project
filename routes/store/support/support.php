<?php

use App\Http\Controllers\Store\Support\SupportController;
use App\Http\Controllers\Store\Support\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/support', [SupportController::class, 'index'])
    ->name('store.support.index');

Route::get('/support/request', [SupportController::class, 'create'])
    ->name('store.support.create');

Route::post('/support/request', [SupportController::class, 'store'])
    ->name('store.support.store');

Route::get('/notifications', [NotificationController::class, 'index'])
    ->name('store.notifications.index');
