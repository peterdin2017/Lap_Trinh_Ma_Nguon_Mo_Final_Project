<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Kiểm tra đã đăng nhập hay chưa
class CheckLogin
{
    public function handle(Request $request, Closure $next): Response
    {

        // Nếu chưa đăng nhập (không có account_id trong session), đá về trang đăng nhập
        if (!session('account_id')) {
            return redirect()->route('login.form');
        }

        return $next($request);
    }
}
