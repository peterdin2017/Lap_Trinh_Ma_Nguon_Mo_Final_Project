<?php

namespace App\Http\Controllers\Store\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Product\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Services\Store\ProductCatalog;
use App\Services\Store\ReviewEligibility;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(
        ReviewRequest $request,
        int $productId,
        ProductCatalog $catalog,
        ReviewEligibility $eligibility,
    ): RedirectResponse {
        $productUrl = route('store.products.show', $productId);
        $accountId = $request->session()->get('account_id');

        if (! $accountId) {
            session(['url.intended' => $productUrl]);

            return redirect()->route('login.form');
        }

        $product = $catalog->visible()->findOrFail($productId);

        // Server tự chọn dòng hàng được phép đánh giá; không nhận order_item_id từ form.
        $orderItemId = $eligibility->reviewableOrderItemId((int) $accountId, $product->product_id);

        if ($orderItemId === null) {
            return redirect($productUrl . '#reviews')->withErrors([
                'review' => 'Bạn chỉ có thể đánh giá sản phẩm đã mua và nhận hàng thành công (mỗi lượt mua đánh giá một lần).',
            ]);
        }

        try {
            DB::transaction(function () use ($request, $accountId, $product, $orderItemId) {
                Review::create([
                    'account_id' => $accountId,
                    'product_id' => $product->product_id,
                    'order_item_id' => $orderItemId,
                    'rating' => (int) $request->validated('rating'),
                    'comment' => trim((string) $request->validated('comment')) ?: null,
                ]);

                // Cập nhật số liệu lưu sẵn trên products để trang danh sách/chi tiết hiện nhanh.
                $stats = Review::where('product_id', $product->product_id)
                    ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total')
                    ->first();

                Product::where('product_id', $product->product_id)->update([
                    'rating_avg' => round((float) $stats->avg_rating, 1),
                    'rating_count' => (int) $stats->total,
                ]);
            });
        } catch (UniqueConstraintViolationException) {
            // Bấm gửi hai lần liên tiếp: unique(order_item_id) chặn bản thứ hai.
            return redirect($productUrl . '#reviews')->withErrors(['review' => 'Bạn đã đánh giá lượt mua này rồi.']);
        }

        return redirect($productUrl . '#reviews')->with('review_success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}
