<?php

namespace App\Http\Controllers\Store\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(int $productId): View
    {
        // Chỉ cho xem sản phẩm đang bán từ shop đã được Admin duyệt.
        $product = Product::query()
            ->with(['category', 'seller', 'variants'])
            ->where('status', 'active')
            ->whereHas('seller', function ($query) {
                $query->where('status', 'active');
            })
            ->findOrFail($productId);

        // Lấy ảnh chính và các ảnh phụ để hiện thành thư viện ảnh.
        $images = collect([$product->image_url])
            ->filter()
            ->merge(
                DB::table('product_images')
                    ->where('product_id', $product->product_id)
                    ->orderBy('sort_order')
                    ->pluck('image_url')
            )
            ->values();

        return view('store.products.show', compact('product', 'images'));
    }
}
