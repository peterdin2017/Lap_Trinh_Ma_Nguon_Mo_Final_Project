<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\OtpController;

// Bước 1: nhập email và gửi OTP
Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmailForm'])
    ->name('forgot-password.form');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
    ->name('forgot-password.send-otp');

// Bước 2: nhập và xác minh OTP
Route::get('/forgot-password/otp', [OtpController::class, 'showForm'])
    ->name('forgot-password.otp.form');

Route::post('/forgot-password/otp', [OtpController::class, 'verify'])
    ->name('forgot-password.otp.verify');

Route::post('/forgot-password/otp/resend', [OtpController::class, 'resend'])
    ->name('forgot-password.otp.resend');

// Bước 3: nhập mật khẩu mới
Route::get('/forgot-password/password', [ForgotPasswordController::class, 'showPasswordForm'])
    ->name('forgot-password.password.form');

Route::post('/forgot-password/password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('forgot-password.password.submit');

// Bước 4: thông báo thành công, sau đó quay về đăng nhập
Route::get('/forgot-password/complete', [ForgotPasswordController::class, 'complete'])
    ->name('forgot-password.complete');
