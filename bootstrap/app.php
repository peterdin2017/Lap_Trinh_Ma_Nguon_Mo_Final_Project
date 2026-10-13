<?php

use App\Http\Middleware\EnsureSellerStatus;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Chuyển khách chưa đăng nhập đến trang đăng nhập theo khu vực.
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login.form');
            }

            if ($request->is('seller') || $request->is('seller/*')) {
                return route('seller.login.form');
            }

            return route('login.form');
        });

        // Đăng ký alias middleware dùng trong routes.
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'seller.status' => EnsureSellerStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })
    ->create();
