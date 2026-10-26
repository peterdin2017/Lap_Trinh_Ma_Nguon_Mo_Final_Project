<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
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

        // Middleware seller.status chuyển hồ sơ chưa hoạt động
        // đến trang thông báo tương ứng.
        if ($seller->status !== 'active') {
            return match ($seller->status) {
                'pending' => redirect()->route('seller.application.pending'),
                'rejected' => redirect()->route('seller.application.rejected'),
                'suspended' => redirect()->route('seller.application.suspended'),
                default => abort(403, 'Trạng thái hồ sơ người bán không hợp lệ.'),
            };
        }

        $sellerId = (int) $seller->seller_id;

        $productCount = Product::query()
            ->where('seller_id', $sellerId)
            ->count();

        $newOrderCount = Order::query()
            ->where('seller_id', $sellerId)
            ->where('order_status', 'pending')
            ->count();

        $processingOrderCount = Order::query()
            ->where('seller_id', $sellerId)
            ->whereIn('order_status', ['processing', 'shipping'])
            ->count();

        $revenue = (float) Order::query()
            ->where('seller_id', $sellerId)
            ->where('order_status', 'completed')
            ->sum('total_amount');

        $recentOrders = Order::query()
            ->where('seller_id', $sellerId)
            ->latest('created_at')
            ->limit(5)
            ->get([
                'order_id',
                'order_code',
                'order_status',
                'total_amount',
                'created_at',
            ]);

        return view('seller.home', compact(
            'seller',
            'productCount',
            'newOrderCount',
            'processingOrderCount',
            'revenue',
            'recentOrders'
        ));
    }
}
