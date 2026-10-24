<div
    class="modal fade"
    id="orderDetail{{ $order->order_id }}"
    tabindex="-1"
    aria-labelledby="orderDetailTitle{{ $order->order_id }}"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title h5 fw-bold" id="orderDetailTitle{{ $order->order_id }}">
                        Đơn {{ $order->order_code }}
                    </h2>
                    <div class="small text-secondary">
                        Đặt lúc {{ $order->created_at?->format('d/m/Y H:i') }}
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h3 class="h6 fw-bold">Thông tin người nhận</h3>
                            <div>{{ $order->shipping_name }}</div>
                            <div>{{ $order->shipping_phone }}</div>
                            <div class="small text-secondary mt-1">{{ $order->shipping_address }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h3 class="h6 fw-bold">Thanh toán</h3>
                            <div>
                                Phương thức:
                                <strong>
                                    {{ $paymentLabels[$order->payment_method] ?? strtoupper($order->payment_method) }}
                                </strong>
                            </div>
                            <div>
                                Trạng thái:
                                {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chờ thanh toán' }}
                            </div>
                            <div class="small text-secondary mt-1">
                                Ghi chú: {{ $order->note ?: 'Không có' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-center">SL</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->product_name }}</div>
                                        @if ($item->variant_name)
                                            <small class="text-secondary">{{ $item->variant_name }}</small>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        {{ number_format((float) $item->price, 0, ',', '.') }}₫
                                    </td>
                                    <td class="text-center">{{ number_format($item->quantity) }}</td>
                                    <td class="text-end">
                                        {{ number_format((float) $item->total_price, 0, ',', '.') }}₫
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="ms-auto" style="max-width: 340px">
                    <div class="d-flex justify-content-between py-1">
                        <span>Tạm tính</span>
                        <span>{{ number_format((float) $order->subtotal, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span>Phí vận chuyển</span>
                        <span>{{ number_format((float) $order->shipping_fee, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span>Giảm giá</span>
                        <span>-{{ number_format((float) $order->discount_amount, 0, ',', '.') }}₫</span>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Tổng thanh toán</span>
                        <span class="text-danger">
                            {{ number_format((float) $order->total_amount, 0, ',', '.') }}₫
                        </span>
                    </div>
                </div>

                @if ($order->order_status === 'cancelled')
                    <div class="alert alert-danger mt-3 mb-0">
                        <strong>Lý do hủy:</strong>
                        {{ $order->cancellation_reason ?: 'Chưa ghi nhận lý do.' }}
                    </div>
                @endif
            </div>

            <div class="modal-footer">
                <a href="{{ route('seller.orders.show', $order->order_id) }}" class="btn btn-outline-primary">
                    Mở trang chi tiết
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@if ($order->order_status === 'pending')
    <div
        class="modal fade"
        id="cancelOrder{{ $order->order_id }}"
        tabindex="-1"
        aria-labelledby="cancelOrderTitle{{ $order->order_id }}"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('seller.orders.status', $order->order_id) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">

                    <div class="modal-header">
                        <h2 class="modal-title h5 fw-bold" id="cancelOrderTitle{{ $order->order_id }}">
                            Hủy đơn {{ $order->order_code }}
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>

                    <div class="modal-body">
                        <label for="cancelReason{{ $order->order_id }}" class="form-label">
                            Lý do hủy đơn <span class="text-danger">*</span>
                        </label>
                        <textarea
                            id="cancelReason{{ $order->order_id }}"
                            name="cancellation_reason"
                            rows="4"
                            minlength="5"
                            maxlength="1000"
                            class="form-control"
                            required
                            placeholder="Nhập lý do để thông báo cho khách"
                        ></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Quay lại
                        </button>
                        <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
