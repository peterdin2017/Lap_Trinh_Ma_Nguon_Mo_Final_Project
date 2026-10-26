<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSellerStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $account = $request->user();

        if (! $account || $account->role !== 'seller') {
            return redirect()->route('seller.login.form');
        }

        $seller = $account->seller;

        if (! $seller) {
            abort(403, 'Tài khoản chưa có hồ sơ người bán.');
        }

        $currentRoute = $request->route()?->getName();

        return match ($seller->status) {
            'active' => $next($request),

            'pending' => $currentRoute === 'seller.application.pending'
                ? $next($request)
                : redirect()->route('seller.application.pending'),

            'rejected' => $currentRoute === 'seller.application.rejected'
                ? $next($request)
                : redirect()->route('seller.application.rejected'),

            'suspended' => $currentRoute === 'seller.application.suspended'
                ? $next($request)
                : redirect()->route('seller.application.suspended'),

            default => abort(403, 'Trạng thái hồ sơ người bán không hợp lệ.'),
        };
    }
}
