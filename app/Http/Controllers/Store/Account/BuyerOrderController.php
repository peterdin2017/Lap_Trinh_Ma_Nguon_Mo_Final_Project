<?php

namespace App\Http\Controllers\Store\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BuyerOrderController extends Controller
{
    private const STATUSES = [
        'all',
        'pending',
        'shipping',
        'completed',
        'cancelled',
    ];

    public function index(Request $request): View
    {
        $accountId = $this->accountId();

        $filters = $request->validate([
            'status' => ['nullable', 'in:' . implode(',', self::STATUSES)],
        ]);

        $status = $filters['status'] ?? 'all';

        $orders = Order::query()
            ->where('account_id', $accountId)
            ->with([
                'seller',
                'items.product',
                'items.variant',
            ])
            ->when(
                $status !== 'all',
                fn ($query) => $query->where('order_status', $status)
            )
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        /*
         * Đơn cũ có thể thiếu product_id/product_variant_id.
         * Thử khôi phục quan hệ theo tên sản phẩm trong đúng cửa hàng.
         */
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $product = $item->product;

                if (! $product && $item->product_name) {
                    $product = Product::query()
                        ->where('seller_id', $order->seller_id)
                        ->where('product_name', $item->product_name)
                        ->first();

                    if ($product) {
                        $item->setRelation('product', $product);
                    }
                }

                $variant = $item->variant;

                if (! $variant && $product && $item->variant_name) {
                    $variant = ProductVariant::query()
                        ->where('product_id', $product->product_id)
                        ->where('variant_name', $item->variant_name)
                        ->first();

                    if ($variant) {
                        $item->setRelation('variant', $variant);
                    }
                }
            }
        }

        $orderItemIds = $orders->getCollection()
            ->flatMap(fn ($order) => $order->items->pluck('order_item_id'))
            ->values();

        $reviewedItemIds = Review::query()
            ->whereIn('order_item_id', $orderItemIds)
            ->pluck('order_item_id')
            ->map(fn ($id) => (int) $id);

        return view('store.account.orders.index', compact(
            'orders',
            'status',
            'reviewedItemIds'
        ));
    }

    public function confirmReceipt(int $id): RedirectResponse
    {
        $accountId = $this->accountId();

        DB::transaction(function () use ($id, $accountId): void {
            $order = Order::query()
                ->where('account_id', $accountId)
                ->lockForUpdate()
                ->findOrFail($id);

            if ($order->order_status !== 'shipping') {
                throw ValidationException::withMessages([
                    'order' => 'Chỉ có thể xác nhận đơn hàng đang được giao.',
                ]);
            }

            $order->order_status = 'completed';
            $order->payment_status = 'paid';
            $order->save();
        });

        return back()->with('success', 'Bạn đã xác nhận nhận hàng thành công.');
    }

    public function cancelOrder(int $id): RedirectResponse
    {
        $accountId = $this->accountId();

        DB::transaction(function () use ($id, $accountId): void {
            $order = Order::query()
                ->where('account_id', $accountId)
                ->lockForUpdate()
                ->findOrFail($id);

            if ($order->order_status !== 'pending') {
                throw ValidationException::withMessages([
                    'order' => 'Chỉ có thể hủy đơn hàng đang chờ xác nhận.',
                ]);
            }

            $items = $order->items()
                ->lockForUpdate()
                ->get();

            foreach ($items as $item) {
                $product = null;

                if ($item->product_id) {
                    $product = Product::query()
                        ->where('product_id', $item->product_id)
                        ->lockForUpdate()
                        ->first();
                }

                if (! $product && $item->product_name) {
                    $product = Product::query()
                        ->where('seller_id', $order->seller_id)
                        ->where('product_name', $item->product_name)
                        ->lockForUpdate()
                        ->first();
                }

                if (! $product) {
                    continue;
                }

                $variant = null;

                if ($item->product_variant_id) {
                    $variant = ProductVariant::query()
                        ->where('product_id', $product->product_id)
                        ->where('variant_id', $item->product_variant_id)
                        ->lockForUpdate()
                        ->first();
                }

                if (! $variant && $item->variant_name) {
                    $variant = ProductVariant::query()
                        ->where('product_id', $product->product_id)
                        ->where('variant_name', $item->variant_name)
                        ->lockForUpdate()
                        ->first();
                }

                if ($variant) {
                    // Checkout giảm cả tồn kho biến thể và tồn kho tổng sản phẩm.
                    $variant->increment('stock_quantity', (int) $item->quantity);
                    $product->increment('stock_quantity', (int) $item->quantity);
                } else {
                    $product->increment('stock_quantity', (int) $item->quantity);
                }

                // Ghi lại các ID đã khôi phục cho đơn cũ.
                $item->product_id = $product->product_id;
                $item->product_variant_id = $variant?->variant_id;
                $item->save();
            }

            $order->order_status = 'cancelled';
            $order->save();
        }, 3);

        return back()->with('success', 'Đơn hàng đã được hủy.');
    }

    private function accountId(): int
    {
        abort_unless(
            session()->has('account_id'),
            401,
            'Vui lòng đăng nhập để xem đơn hàng.'
        );

        return (int) session('account_id');
    }
}
