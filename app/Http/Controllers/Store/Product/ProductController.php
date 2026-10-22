<?php

namespace App\Http\Controllers\Store\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Services\Store\ProductCatalog;
use App\Services\Store\ReviewEligibility;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(int $productId, ProductCatalog $catalog, ReviewEligibility $eligibility): View
    {
        // Chỉ cho xem sản phẩm đang bán từ shop đã được Admin duyệt.
        $product = Product::query()
            ->with(['category.parent', 'seller', 'variants'])
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

        // Đánh giá: danh sách (5 mỗi trang) + người đang xem có được đánh giá không.
        $reviews = $product->reviews()
            ->latest('created_at')
            ->latest('review_id')
            ->paginate(5, ['*'], 'reviews_page')
            ->fragment('reviews');

        // Chỉ lộ tên hiển thị, không bao giờ lộ email.
        $reviewerNames = User::whereIn('account_id', $reviews->pluck('account_id'))
            ->pluck('full_name', 'account_id');

        $accountId = session('account_id');

        return view('store.products.show', [
            'product' => $product,
            'images' => $images,
            'breadcrumb' => $catalog->breadcrumb($product->category),
            'reviews' => $reviews,
            'reviewerNames' => $reviewerNames,
            'isGuest' => ! $accountId,
            'canReview' => $accountId
                && $eligibility->reviewableOrderItemId((int) $accountId, $product->product_id) !== null,
        ]);
    }
}
