<?php

namespace App\Http\Controllers\Store\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Account\BuyerOrderFilterRequest;
use App\Http\Requests\Store\Account\BuyerReturnRequest;
use App\Models\Order;
use App\Models\OrderReturnRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BuyerOrderController extends Controller
{
    public function index(BuyerOrderFilterRequest $request): View|JsonResponse
    {
        $accountId = $this->accountId();

        $filters = $request->validated();

        $status = $filters['status'] ?? 'all';

        $ordersQuery = Order::query()
            ->where('account_id', $accountId)
            ->when(
                $status === 'return_requested',
                fn ($query) => $query->whereIn(
                    'order_id',
                    OrderReturnRequest::query()
                        ->where('account_id', $accountId)
                        ->select('order_id')
                )
            )
            ->when(
                $status !== 'all' && $status !== 'return_requested',
                fn ($query) => $query->where('order_status', $status)
            )
            ->latest('created_at');

        if ($request->boolean('refresh')) {
            $snapshotOrders = (clone $ordersQuery)
                ->select([
                    'order_id',
                    'order_status',
                    'cancellation_reason',
                    'updated_at',
                ])
                ->paginate(10);

            return response()->json([
                'fingerprint' => $this->ordersFingerprint($snapshotOrders),
            ]);
        }

        $orders = $ordersQuery
            ->with([
                'seller',
                'items.product',
                'items.variant',
            ])
            ->paginate(10)
            ->withQueryString();
        $ordersFingerprint = $this->ordersFingerprint($orders);

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

        $orderIds = $orders->getCollection()->pluck('order_id');

        $reviewedItemIds = Review::query()
            ->whereIn(
                'order_item_id',
                $orders->getCollection()
                    ->flatMap(fn ($order) => $order->items->pluck('order_item_id'))
                    ->values()
            )
            ->pluck('order_item_id')
            ->map(fn ($id) => (int) $id);

        $returnRequests = OrderReturnRequest::query()
            ->where('account_id', $accountId)
            ->whereIn('order_id', $orderIds)
            ->get()
            ->keyBy('order_id');

        return view('store.account.orders.index', compact(
            'orders',
            'ordersFingerprint',
            'status',
            'reviewedItemIds',
            'returnRequests'
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

            if (! in_array($order->order_status, ['pending', 'processing'], true)) {
                throw ValidationException::withMessages([
                    'order' => 'Chỉ có thể hủy đơn hàng trước khi bàn giao cho đơn vị vận chuyển.',
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
                    // Checkout giảm tồn kho biến thể và tồn kho tổng sản phẩm.
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
            $order->cancellation_reason = 'Đơn hàng được hủy theo yêu cầu của khách hàng.';
            $order->save();
        }, 3);

        return back()->with('success', 'Đơn hàng đã được hủy.');
    }

    public function requestReturn(BuyerReturnRequest $request, int $id): RedirectResponse
    {
        $accountId = $this->accountId();

        $data = $request->validated();

        DB::transaction(function () use ($id, $accountId, $data): void {
            $order = Order::query()
                ->where('account_id', $accountId)
                ->lockForUpdate()
                ->findOrFail($id);

            if ($order->order_status !== 'completed') {
                throw ValidationException::withMessages([
                    'return' => 'Chỉ có thể yêu cầu trả hàng/hoàn tiền cho đơn đã hoàn thành.',
                ]);
            }

            $alreadyRequested = OrderReturnRequest::query()
                ->where('order_id', $order->order_id)
                ->exists();

            if ($alreadyRequested) {
                throw ValidationException::withMessages([
                    'return' => 'Đơn hàng này đã có yêu cầu trả hàng/hoàn tiền.',
                ]);
            }

            OrderReturnRequest::query()->create([
                'order_id' => $order->order_id,
                'account_id' => $accountId,
                'request_type' => $data['request_type'],
                'reason' => $data['reason'],
                'description' => $data['description'] ?? null,
                'status' => 'pending',
            ]);
        }, 3);

        return back()->with(
            'return_success',
            'Yêu cầu trả hàng/hoàn tiền đã được gửi. Cửa hàng sẽ kiểm tra và phản hồi.'
        );
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

    private function ordersFingerprint(LengthAwarePaginator $orders): string
    {
        $snapshot = $orders->getCollection()
            ->map(fn (Order $order): array => [
                (int) $order->order_id,
                (string) $order->order_status,
                (string) ($order->cancellation_reason ?? ''),
                $order->updated_at?->toISOString(),
            ])
            ->values()
            ->all();

        return hash('sha256', json_encode([
            $orders->currentPage(),
            $orders->total(),
            $snapshot,
        ], JSON_THROW_ON_ERROR));
    }
}

