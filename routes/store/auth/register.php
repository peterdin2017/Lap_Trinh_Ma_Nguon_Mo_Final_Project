<?php
// Đăng ký và OTP 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\Auth\RegisterController;
use App\Http\Controllers\Store\Auth\OtpController;

// Phần đăng ký
// Trang đăng ký
Route::get('/register',[RegisterController::class,'showEmailForm']) ->name('register.form');

// Xử lý email đăng ký
Route::post('/register',[RegisterController::class,'submitEmail'])->name('register.submit');

// Phần OTP
// Trang nhập OTP
Route::get('/register/otp', [OtpController::class, 'showForm'])
    ->name('otp.form');

// Trang kiểm tra OTP
Route::post('/register/otp', [OtpController::class, 'verify'])
    ->name('otp.verify');

// Trang gửi lại OTP
Route::post('/register/otp/resend', [OtpController::class, 'resend'])
    ->name('otp.resend');

// Phần mật khẩu
// Trang hiển thị form tạo mật khẩu
Route::get('/register/password', [RegisterController::class, 'showPasswordForm'])
    ->name('register.password.form');

// Xử lý họ tên + mật khẩu
Route::post('/register/password', [RegisterController::class, 'submitPassword'])
    ->name('register.password.submit');

// Phần hoàn tất
// Trang hoàn tất
Route::get('/register/complete', [RegisterController::class, 'complete'])
    ->name('register.complete');
?>
