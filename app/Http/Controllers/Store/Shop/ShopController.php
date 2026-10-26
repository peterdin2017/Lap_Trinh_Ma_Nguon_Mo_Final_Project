<?php

namespace App\Http\Controllers\Store\Shop;

use App\Http\Controllers\Controller;
use App\Models\AccountSavedVoucher;
use App\Models\Seller;
use App\Models\SellerVoucher;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function show(Request $request, int $sellerId): View
    {
        $seller = Seller::query()
            ->where('status', 'active')
            ->with('account')
            ->findOrFail($sellerId);

        $products = $seller->products()
            ->with(['category', 'images'])
            ->where('status', 'active')
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = trim((string) $request->query('q'));
                $query->where('product_name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $productCount = $seller->products()
            ->where('status', 'active')
            ->count();

        $now = now();
        $shopVouchers = SellerVoucher::query()
            ->where('seller_id', $seller->seller_id)
            ->where('status', 'active')
            ->where('starts_at', '<=', $now)
            ->where('expires_at', '>=', $now)
            ->where(function ($query): void {
                $query->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->orderBy('expires_at')
            ->limit(12)
            ->get();

        $savedVoucherIds = [];
        $accountId = $request->session()->get('account_id');
        if (is_numeric($accountId) && $shopVouchers->isNotEmpty()) {
            $savedVoucherIds = AccountSavedVoucher::query()
                ->where('account_id', (int) $accountId)
                ->where('voucher_type', 'seller')
                ->whereIn('voucher_id', $shopVouchers->pluck('voucher_id'))
                ->pluck('voucher_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        return view('store.shop.show', compact(
            'seller',
            'products',
            'productCount',
            'shopVouchers',
            'savedVoucherIds'
        ));
    }
}
