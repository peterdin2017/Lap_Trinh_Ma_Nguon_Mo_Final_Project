@extends('store.layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
    <div class="container py-5">
        <section
            class="card checkout-success-card mx-auto text-center p-4 p-md-5"
            aria-labelledby="checkout-success-title"
        >
            <div class="text-success display-3 mb-3" aria-hidden="true">
                <i class="bi bi-check-circle"></i>
            </div>

            <h1 id="checkout-success-title" class="h3 fw-bold">
                Đặt hàng thành công
            </h1>

            <p class="text-secondary">Mã đơn hàng của bạn:</p>

            <ul class="list-unstyled mb-4">
                @foreach ($orderCodes as $orderCode)
                    <li class="fw-bold text-primary">{{ $orderCode }}</li>
                @endforeach
            </ul>

            <p class="small text-secondary">
                Đơn hàng đã được tạo và đang chờ xử lý. VNPAY và chuyển khoản sẽ
                chờ thanh toán cho đến khi cổng thanh toán được tích hợp.
            </p>

            <a href="{{ route('store.home') }}" class="btn btn-primary px-4">
                Tiếp tục mua sắm
            </a>
        </section>
    </div>
@endsection
