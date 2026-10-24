@extends('seller.layouts.app')

@section('title', 'Chi tiết đơn hàng ' . $order->order_code)

@section('content')
    @php
        $statusLabels = [
            'pending' => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang vận chuyển',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        $paymentLabels = [
            'cod' => 'COD',
            'vnpay' => 'VNPAY',
            'bank_transfer' => 'Chuyển khoản',
        ];
    @endphp

    <main class="container-fluid py-3">
        <a href="{{ route('seller.orders.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Quay lại đơn hàng
        </a>

        <section class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <header class="d-flex flex-wrap justify-content-between gap-3 border-bottom pb-3 mb-3">
                    <div>
                        <h1 class="h4 fw-bold mb-1">Đơn {{ $order->order_code }}</h1>
                        <div class="small text-secondary">
                            {{ $order->created_at?->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <span class="badge text-bg-primary align-self-start">
                        {{ $statusLabels[$order->order_status] ?? $order->order_status }}
                    </span>
                </header>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <h2 class="h6 fw-bold">Người nhận</h2>
                        <div>{{ $order->shipping_name }} · {{ $order->shipping_phone }}</div>
                        <div class="text-secondary">{{ $order->shipping_address }}</div>
                    </div>

                    <div class="col-md-6">
                        <h2 class="h6 fw-bold">Thanh toán</h2>
                        <div>
                            {{ $paymentLabels[$order->payment_method] ?? strtoupper($order->payment_method) }}
                            ·
                            {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chờ thanh toán' }}
                        </div>
                        <div class="text-secondary">{{ $order->note ?: 'Không có ghi chú.' }}</div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        {{ $item->product_name }}
                                        @if ($item->variant_name)
                                            <small class="text-secondary">— {{ $item->variant_name }}</small>
                                        @endif
                                    </td>
                                    <td>{{ number_format((float) $item->price, 0, ',', '.') }}₫</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ number_format((float) $item->total_price, 0, ',', '.') }}₫</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end">
                    <div>Tạm tính: {{ number_format((float) $order->subtotal, 0, ',', '.') }}₫</div>
                    <div>Phí vận chuyển: {{ number_format((float) $order->shipping_fee, 0, ',', '.') }}₫</div>
                    <div>Giảm giá: {{ number_format((float) $order->discount_amount, 0, ',', '.') }}₫</div>
                    <div class="fs-5 fw-bold text-danger">
                        Tổng: {{ number_format((float) $order->total_amount, 0, ',', '.') }}₫
                    </div>
                </div>

                @if ($order->order_status === 'cancelled')
                    <div class="alert alert-danger mt-3">
                        Lý do hủy: {{ $order->cancellation_reason ?: 'Chưa ghi nhận.' }}
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection
