<?php

use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Store\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [RegisterController::class, 'showEmailForm'])
    ->name('register.form');

Route::post('/register', [RegisterController::class, 'submitEmail'])
    ->name('register.submit');

Route::get('/register/otp', [OtpController::class, 'showForm'])
    ->name('otp.form');

Route::post('/register/otp', [OtpController::class, 'verify'])
    ->name('otp.verify');

Route::post('/register/otp/resend', [OtpController::class, 'resend'])
    ->name('otp.resend');

Route::get('/register/password', [RegisterController::class, 'showPasswordForm'])
    ->name('register.password.form');

Route::post('/register/password', [RegisterController::class, 'submitPassword'])
    ->name('register.password.submit');

Route::get('/register/complete', [RegisterController::class, 'complete'])
    ->name('register.complete');
