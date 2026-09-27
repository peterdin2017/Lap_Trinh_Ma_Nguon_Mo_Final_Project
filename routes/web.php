<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\LoginController;

// Trang index
Route::get('/',function(){
    return view('home.index');
});

// Phần tạo tài khoản
// Trang nhập email
Route::get('/register', [RegisterController::class, 'showEmailForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'submitEmail'])->name('register.submit');

// Trang xác thực OTP
Route::get('/verify-otp', [OtpController::class, 'showForm'])->name('otp.form');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');

// Trang tạo mật khẩu
Route::get('/register/password', [RegisterController::class, 'showPasswordForm'])->name('register.password.form');
Route::post('/register/password', [RegisterController::class, 'submitPassword'])->name('register.password.submit');

// Trang hoàn thành
Route::get('/register/complete', [RegisterController::class, 'complete'])->name('register.complete');

// Trang đăng nhập
Route::get('/login', [LoginController::class, 'showForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
