<?php

namespace App\Http\Controllers\Seller\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Order\HandleSellerReturnRequest;
use App\Http\Requests\Seller\Order\SellerOrderFilterRequest;
use App\Http\Requests\Seller\Order\UpdateSellerOrderStatusRequest;
use App\Models\Order;
use App\Models\OrderReturnRequest;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SellerOrderController extends Controller
{
    public function index(SellerOrderFilterRequest $request): View
    {
        $filters = $request->validated();

        $seller = $this->currentSeller($request);
        $selectedStatus = $filters['status'] ?? 'all';

        $ordersQuery = $this->sellerOrders($seller)
            ->with([
                'account',
                'items.product',
                'items.variant',
            ]);

        if ($selectedStatus === 'cancelled') {
            // Tab này gồm đơn đã hủy và đơn có yêu cầu trả hàng/hoàn tiền.
            $ordersQuery->where(function ($query) {
                $query->where('order_status', 'cancelled')
                    ->orWhereIn(
                        'order_id',
                        OrderReturnRequest::query()->select('order_id')
                    );
            });
        } elseif ($selectedStatus !== 'all') {
            $ordersQuery->where('order_status', $selectedStatus);
        }

        $orders = $ordersQuery
            ->when($filters['q'] ?? null, function ($query, $keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query->where('order_code', 'like', "%{$keyword}%")
                        ->orWhere('shipping_name', 'like', "%{$keyword}%")
                        ->orWhere('shipping_phone', 'like', "%{$keyword}%");
                });
            })
            ->when(
                $filters['date_from'] ?? null,
                fn($query, $date) => $query->whereDate('created_at', '>=', $date)
            )
            ->when(
                $filters['date_to'] ?? null,
                fn($query, $date) => $query->whereDate('created_at', '<=', $date)
            )
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $orderIds = $orders->getCollection()->pluck('order_id');

        $returnRequests = OrderReturnRequest::query()
            ->whereIn('order_id', $orderIds)
            ->get()
            ->keyBy('order_id');

        $statusCounts = $this->sellerOrders($seller)
            ->select('order_status', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('order_status')
            ->pluck('aggregate', 'order_status');

        $cancelledOrdersCount = (int) $this->sellerOrders($seller)
            ->where('order_status', 'cancelled')
            ->count();

        $returnRequestsCount = (int) OrderReturnRequest::query()
            ->whereIn(
                'order_id',
                $this->sellerOrders($seller)->select('order_id')
            )
            ->count();

        // Tab "Đơn hủy / Trả hàng" gộp hai loại đơn.
        $statusCounts->put(
            'cancelled',
            $cancelledOrdersCount + $returnRequestsCount
        );

        $allOrdersCount = (int) $this->sellerOrders($seller)->count();
        $pendingOrdersCount = (int) ($statusCounts['pending'] ?? 0);

        return view('seller.orders.index', compact(
            'orders',
            'filters',
            'statusCounts',
            'allOrdersCount',
            'pendingOrdersCount',
            'returnRequests'
        ));
    }

    public function show(Request $request, int $order): View
    {
        $seller = $this->currentSeller($request);

        $order = $this->sellerOrders($seller)
            ->with([
                'account',
                'items.product',
                'items.variant',
            ])
            ->where('order_id', $order)
            ->firstOrFail();

        $returnRequest = OrderReturnRequest::query()
            ->where('order_id', $order->order_id)
            ->first();

        return view('seller.orders.show', compact('order', 'returnRequest'));
    }

    public function updateStatus(UpdateSellerOrderStatusRequest $request, int $order): RedirectResponse
    {
        $validated = $request->validated();

        $seller = $this->currentSeller($request);

        DB::transaction(function () use ($seller, $order, $validated) {
            $orderModel = $this->sellerOrders($seller)
                ->where('order_id', $order)
                ->lockForUpdate()
                ->firstOrFail();

            $allowedTransitions = [
                'pending' => ['processing', 'cancelled'],
                'processing' => ['shipping', 'cancelled'],
                'shipping' => ['completed'],
                'completed' => [],
                'cancelled' => [],
            ];

            $currentStatus = $orderModel->order_status;
            $newStatus = $validated['status'];

            if (! in_array($newStatus, $allowedTransitions[$currentStatus] ?? [], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Không thể chuyển đơn hàng từ trạng thái hiện tại sang trạng thái này.',
                ]);
            }

            $orderModel->order_status = $newStatus;

            if ($newStatus === 'cancelled') {
                $orderModel->cancellation_reason = $validated['cancellation_reason'];
            }

            $orderModel->save();
        });

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }

    public function printShippingLabel(Request $request, int $order): View
    {
        $seller = $this->currentSeller($request);

        $order = $this->sellerOrders($seller)
            ->with([
                'seller',
                'items.product',
            ])
            ->where('order_id', $order)
            ->firstOrFail();

        return view('seller.orders.shipping-label', compact('order', 'seller'));
    }

    private function sellerOrders(Seller $seller)
    {
        return Order::query()
            ->where('seller_id', $seller->seller_id);
    }

    private function currentSeller(Request $request): Seller
    {
        $seller = $request->user()?->seller;

        abort_unless(
            $seller instanceof Seller,
            403,
            'Tài khoản không có quyền Seller.'
        );

        return $seller;
    }
    public function updateReturnRequest(HandleSellerReturnRequest $request, int $returnRequest): RedirectResponse
    {
        $validated = $request->validated();

        $seller = $this->currentSeller($request);

        DB::transaction(function () use ($seller, $returnRequest, $validated): void {
            $returnRequestModel = OrderReturnRequest::query()
                ->whereHas('order', function ($query) use ($seller): void {
                    $query->where('seller_id', $seller->seller_id);
                })
                ->lockForUpdate()
                ->findOrFail($returnRequest);

            if ($returnRequestModel->status !== 'pending') {
                throw ValidationException::withMessages([
                    'return_request' => 'Yêu cầu này đã được xử lý trước đó.',
                ]);
            }

            $returnRequestModel->status = $validated['decision'];
            $returnRequestModel->handled_by = $seller->account_id;
            $returnRequestModel->handled_at = now();
            $returnRequestModel->save();
        }, 3);

        $message = $validated['decision'] === 'approved'
            ? 'Đã chấp nhận yêu cầu trả hàng/hoàn tiền.'
            : 'Đã từ chối yêu cầu trả hàng/hoàn tiền.';

        return back()->with('success', $message);
    }
}
