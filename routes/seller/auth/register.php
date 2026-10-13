<?php

use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Seller\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/seller/register', [RegisterController::class, 'showForm'])
    ->name('seller.register.form');

Route::post('/seller/register', [RegisterController::class, 'sendOtp'])
    ->name('seller.register.submit');

Route::get('/seller/register/otp', [OtpController::class, 'showForm'])
    ->name('seller.register.otp.form');

Route::post('/seller/register/otp', [OtpController::class, 'verify'])
    ->name('seller.register.otp.verify');

Route::post('/seller/register/otp/resend', [OtpController::class, 'resend'])
    ->name('seller.register.otp.resend');

Route::get('/seller/register/password', [RegisterController::class, 'showPasswordForm'])
    ->name('seller.register.password.form');

Route::post('/seller/register/password', [RegisterController::class, 'savePassword'])
    ->name('seller.register.password.submit');

Route::get(
    '/seller/register/credentials',
    [RegisterController::class, 'showCredentialsForm']
)->name('seller.register.credentials.form');

Route::post(
    '/seller/register/credentials',
    [RegisterController::class, 'saveCredentials']
)->name('seller.register.credentials.submit');

Route::get(
    '/seller/register/shop',
    [RegisterController::class, 'showShopForm']
)->name('seller.register.shop.form');

Route::post(
    '/seller/register/shop',
    [RegisterController::class, 'submitShopInfo']
)->name('seller.register.shop.submit');
