<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $account = Auth::user();

        if (! $account || $account->role !== 'seller') {
            return redirect()->route('seller.login.form');
        }

        $seller = $account->seller;

        if (! $seller) {
            abort(403, 'Tài khoản chưa có hồ sơ người bán.');
        }

        // Middleware seller.status sẽ chuyển hồ sơ pending, rejected,
        // suspended đến trang thông báo tương ứng.
        if ($seller->status !== 'active') {
            return match ($seller->status) {
                'pending' => redirect()->route('seller.application.pending'),
                'rejected' => redirect()->route('seller.application.rejected'),
                'suspended' => redirect()->route('seller.application.suspended'),
                default => abort(403, 'Trạng thái hồ sơ người bán không hợp lệ.'),
            };
        }

        return view('seller.home', compact('seller'));
    }
}
