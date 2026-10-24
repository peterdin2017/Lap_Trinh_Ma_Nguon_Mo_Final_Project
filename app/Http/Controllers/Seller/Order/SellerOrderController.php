<?php

namespace App\Http\Controllers\Seller\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Seller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SellerOrderController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => [
                'nullable',
                'in:all,pending,processing,shipping,completed,cancelled',
            ],
            'q' => ['nullable', 'string', 'max:100'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ], [
            'status.in' => 'Trạng thái đơn hàng không hợp lệ.',
            'q.max' => 'Từ khóa tìm kiếm không được vượt quá 100 ký tự.',
            'date_to.after_or_equal' => 'Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.',
        ]);

        $seller = $this->currentSeller($request);

        $orders = $this->sellerOrders($seller)
            ->with([
                'account',
                'items.product',
                'items.variant',
            ])
            ->when($filters['status'] ?? null, function ($query, $status) {
                if ($status !== 'all') {
                    $query->where('order_status', $status);
                }
            })
            ->when($filters['q'] ?? null, function ($query, $keyword) {
                $query->where(function ($query) use ($keyword) {
                    $query->where('order_code', 'like', "%{$keyword}%")
                        ->orWhere('shipping_name', 'like', "%{$keyword}%")
                        ->orWhere('shipping_phone', 'like', "%{$keyword}%");
                });
            })
            ->when(
                $filters['date_from'] ?? null,
                fn ($query, $date) => $query->whereDate('created_at', '>=', $date)
            )
            ->when(
                $filters['date_to'] ?? null,
                fn ($query, $date) => $query->whereDate('created_at', '<=', $date)
            )
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $statusCounts = $this->sellerOrders($seller)
            ->select('order_status', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('order_status')
            ->pluck('aggregate', 'order_status');

        $pendingOrdersCount = (int) ($statusCounts['pending'] ?? 0);

        return view('seller.orders.index', compact(
            'orders',
            'filters',
            'statusCounts',
            'pendingOrdersCount'
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

        return view('seller.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, int $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:processing,shipping,completed,cancelled',
            ],
            'cancellation_reason' => [
                'required_if:status,cancelled',
                'nullable',
                'string',
                'min:5',
                'max:1000',
            ],
        ], [
            'status.required' => 'Vui lòng chọn trạng thái đơn hàng.',
            'status.in' => 'Trạng thái đơn hàng không hợp lệ.',
            'cancellation_reason.required_if' => 'Vui lòng nhập lý do hủy đơn.',
            'cancellation_reason.min' => 'Lý do hủy đơn cần có ít nhất 5 ký tự.',
            'cancellation_reason.max' => 'Lý do hủy đơn không được vượt quá 1000 ký tự.',
        ]);

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
}
