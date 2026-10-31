<?php

namespace App\Http\Controllers\Store\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Services\Store\ProductCatalog;
use App\Services\Store\ReviewEligibility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(
        string $slug,
        ProductCatalog $catalog,
        ReviewEligibility $eligibility
    ): View|RedirectResponse {
        // Chỉ cho xem sản phẩm đang bán từ shop đã được Admin duyệt.
        $visibleProducts = Product::query()
            ->with(['category.parent', 'seller', 'variants'])
            ->where('status', 'active')
            ->whereHas('seller', function ($query): void {
                $query->where('status', 'active');
            });

        $product = (clone $visibleProducts)
            ->where('slug', $slug)
            ->first();

        // URL số cũ vẫn hoạt động và được chuyển sang URL slug chuẩn.
        if (! $product && ctype_digit($slug)) {
            $legacyProduct = (clone $visibleProducts)
                ->whereKey((int) $slug)
                ->first();

            if ($legacyProduct) {
                if (filled($legacyProduct->slug)) {
                    return redirect()->route(
                        'store.products.show',
                        ['slug' => $legacyProduct->slug],
                        301
                    );
                }

                // Sản phẩm cũ chưa có slug vẫn mở được bằng URL số.
                $product = $legacyProduct;
            }
        }

        abort_unless($product, 404);

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

        // Đánh giá: danh sách 5 mỗi trang và kiểm tra quyền đánh giá.
        $reviews = $product->reviews()
            ->latest('created_at')
            ->latest('review_id')
            ->paginate(5, ['*'], 'reviews_page')
            ->fragment('reviews');

        // Chỉ hiển thị tên, không hiển thị email người đánh giá.
        $reviewerNames = User::query()
            ->whereIn('account_id', $reviews->pluck('account_id'))
            ->pluck('full_name', 'account_id');

        $accountId = session('account_id');
        $productCount = $product->seller
            ? $product->seller->products()->where('status', 'active')->count()
            : 0;
        $products = Product::query()
            ->with(['category', 'seller'])
            ->where('status', 'active')
            ->where('product_id', '!=', $product->product_id)
            ->whereHas('seller', fn ($query) => $query->where('status', 'active'))
            ->where(function ($query) use ($product): void {
                $query->where('category_id', $product->category_id)
                    ->orWhere('seller_id', $product->seller_id);
            })
            ->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$product->category_id])
            ->latest('product_id')
            ->paginate(8, ['*'], 'products_page')
            ->withQueryString();
        $reviewableOrderItemId = $accountId
            ? $eligibility->reviewableOrderItemId(
                (int) $accountId,
                $product->product_id
            )
            : null;

        return view('store.products.show', [
            'product' => $product,
            'seller' => $product->seller,
            'productCount' => $productCount,
            'products' => $products,
            'images' => $images,
            'breadcrumb' => $catalog->breadcrumb($product->category),
            'reviews' => $reviews,
            'reviewerNames' => $reviewerNames,
            'isGuest' => ! $accountId,
            'reviewableOrderItemId' => $reviewableOrderItemId,
            'canReview' => $reviewableOrderItemId !== null,
        ]);
    }
}
