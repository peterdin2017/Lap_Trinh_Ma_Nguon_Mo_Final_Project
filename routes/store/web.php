<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Store\HomeController;

Route::domain('store.localhost')->group(function(){

    // Trang chủ
    Route::get('/', [HomeController::class,'index'])
        ->name('store.home');

    // Gọi phần đăng ký
    require __DIR__ . '/auth/register.php';

    // Gọi phần đăng nhập
    require __DIR__ . '/auth/login.php';

    // Phần quên tài khoản trong đăng nhập
    require __DIR__ . '/auth/forgot-password.php';

    // Gọi các trang thông tin tĩnh (điều khoản, chính sách...)
    require __DIR__ . '/page/page.php';
});


?>
