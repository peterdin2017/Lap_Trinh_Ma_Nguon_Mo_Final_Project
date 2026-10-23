<?php

namespace App\Http\Controllers\Store\Shop;

use App\Http\Controllers\Controller;
use App\Models\Seller;
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

        return view('store.shop.show', compact(
            'seller',
            'products',
            'productCount'
        ));
    }
}
