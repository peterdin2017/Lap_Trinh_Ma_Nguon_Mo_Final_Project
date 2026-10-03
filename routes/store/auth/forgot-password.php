<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\Auth\ForgotPasswordController;

Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmailForm'])
    ->name('forgot.password.form');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
    ->name('forgot.password.submit');

Route::get('/forgot-password/otp', [ForgotPasswordController::class, 'showOtpForm'])
    ->name('forgot.password.otp.form');

Route::post('/forgot-password/otp', [ForgotPasswordController::class, 'verifyOtp'])
    ->name('forgot.password.otp.verify');

Route::post('/forgot-password/otp/resend', [ForgotPasswordController::class, 'resendOtp'])
    ->name('forgot.password.otp.resend');

Route::get('/forgot-password/reset', [ForgotPasswordController::class, 'showPasswordForm'])
    ->name('forgot.password.reset.form');

Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])
    ->name('forgot.password.reset.submit');

Route::get('/forgot-password/success', [ForgotPasswordController::class, 'showSuccess'])
    ->name('forgot.success');
