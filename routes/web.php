<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Auth\ForgotPasswordController;

// Trang index
Route::get('/',function(){
    return view('home.index');
});

// Phần tạo tài khoản
// Trang nhập email
Route::get('/register', [RegisterController::class, 'showEmailForm'])->name('register.form');   //Request được xử lý bởi showEmail trong RegisterController, ->name('register.form'): đặt tên cho route là register.form
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

Route::middleware('checklogin')->group(function () {
    Route::get('/account/profile', [ProfileController::class, 'show'])->name('account.profile');
    Route::post('/account/profile', [ProfileController::class, 'update'])->name('account.profile.update');
});

// Quên mật khẩu
// Bước 1: Nhập email
Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmailForm'])
    ->name('forgot.form');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])
    ->name('forgot.send');

// Bước 2: Nhập OTP
Route::get('/forgot-password/otp', [ForgotPasswordController::class, 'showOtpForm'])
    ->name('forgot.otp.form');

Route::post('/forgot-password/otp', [ForgotPasswordController::class, 'verifyOtp'])
    ->name('forgot.otp.verify');

Route::post('/forgot-password/otp/resend', [ForgotPasswordController::class, 'resendOtp'])
    ->name('forgot.otp.resend');

// Bước 3: Đặt mật khẩu mới
Route::get('/forgot-password/password', [ForgotPasswordController::class, 'showPasswordForm'])
    ->name('forgot.password.form');

Route::post('/forgot-password/password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('forgot.password.reset');

// Bước 4: Hoàn thành
Route::get('/forgot-password/success', [ForgotPasswordController::class, 'showSuccess'])
    ->name('forgot.success');
