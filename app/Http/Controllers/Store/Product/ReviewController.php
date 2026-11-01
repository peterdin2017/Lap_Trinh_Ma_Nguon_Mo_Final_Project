<?php

namespace App\Http\Controllers\Store\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Product\ReviewRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, int $productId): RedirectResponse
    {
        $accountId = $request->session()->get('account_id');

        if (! is_numeric($accountId)) {
            return redirect()->guest(route('login.form'));
        }

        $data = $request->validated();

        $storedPaths = [];

        try {
            DB::transaction(function () use (
                $productId,
                $accountId,
                $data,
                $request,
                &$storedPaths
            ): void {
                $product = Product::query()
                    ->whereKey($productId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $orderItem = OrderItem::query()
                    ->whereKey($data['order_item_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $order = Order::query()
                    ->where('order_id', $orderItem->order_id)
                    ->where('account_id', (int) $accountId)
                    ->where('seller_id', $product->seller_id)
                    ->where('order_status', 'completed')
                    ->lockForUpdate()
                    ->first();

                if (! $order) {
                    throw ValidationException::withMessages([
                        'review' => 'Chỉ có thể đánh giá sản phẩm trong đơn hàng đã hoàn thành của bạn.',
                    ]);
                }

                $itemMatchesProduct = (int) $orderItem->product_id === (int) $product->product_id;

                // Hỗ trợ đơn cũ thiếu product_id, đối chiếu tên trong cùng cửa hàng.
                if (! $itemMatchesProduct && $orderItem->product_id === null) {
                    $itemMatchesProduct =
                        $orderItem->product_name === $product->product_name;
                }

                if (! $itemMatchesProduct) {
                    throw ValidationException::withMessages([
                        'review' => 'Sản phẩm không khớp với dòng hàng trong đơn này.',
                    ]);
                }

                if (Review::query()->where('order_item_id', $orderItem->order_item_id)->exists()) {
                    throw ValidationException::withMessages([
                        'review' => 'Dòng sản phẩm này đã được đánh giá rồi.',
                    ]);
                }

                if ($orderItem->product_id === null) {
                    $orderItem->product_id = $product->product_id;
                    $orderItem->save();
                }

                $review = Review::query()->create([
                    'account_id' => (int) $accountId,
                    'product_id' => $product->product_id,
                    'order_item_id' => $orderItem->order_item_id,
                    'rating' => (int) $data['rating'],
                    'comment' => $data['comment'] ?? null,
                ]);

                $imageRows = [];
                $now = now();

                foreach ($request->file('images', []) as $image) {
                    $path = $image->store('reviews', 'public');

                    if (! $path) {
                        throw new \RuntimeException('Không lưu được ảnh đánh giá.');
                    }

                    $storedPaths[] = $path;

                    $imageRows[] = [
                        'review_id' => $review->review_id,
                        'image_path' => $path,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if ($imageRows !== []) {
                    ReviewImage::query()->insert($imageRows);
                }

                $product->rating_avg = (float) Review::query()
                    ->where('product_id', $product->product_id)
                    ->avg('rating');

                $product->rating_count = Review::query()
                    ->where('product_id', $product->product_id)
                    ->count();

                $product->save();
            }, 3);
        } catch (Throwable $exception) {
            if ($storedPaths !== []) {
                Storage::disk('public')->delete($storedPaths);
            }

            throw $exception;
        }

        return back()->with('review_success', 'Cảm ơn bạn đã đánh giá sản phẩm.');
    }
}
