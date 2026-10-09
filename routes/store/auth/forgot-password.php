<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\OtpController;

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])
    ->name('forgot-password.form');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
    ->name('forgot-password.send-otp');

Route::get('/forgot-password/otp', [OtpController::class, 'showForm'])
    ->name('forgot-password.otp.form');

Route::post('/forgot-password/otp', [OtpController::class, 'verify'])
    ->name('forgot-password.otp.verify');

Route::get('/forgot-password/password', [ForgotPasswordController::class, 'showPasswordForm'])
    ->name('forgot-password.password.form');

Route::post('/forgot-password/password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('forgot-password.password.submit');
