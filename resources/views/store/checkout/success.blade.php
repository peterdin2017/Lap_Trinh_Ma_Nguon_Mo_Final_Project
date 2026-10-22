@extends('store.layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
    <main class="container py-5">
        <section class="card mx-auto text-center p-4 p-md-5"
                 style="max-width: 680px; border: 1px solid #E2E8F0; border-radius: 12px;">
            <div class="text-success display-3 mb-3" aria-hidden="true">
                <i class="bi bi-check-circle"></i>
            </div>

            <h1 class="h3 fw-bold">Đặt hàng thành công</h1>
            <p class="text-secondary">Mã đơn hàng của bạn:</p>

            <ul class="list-unstyled mb-4">
                @foreach ($orderCodes as $orderCode)
                    <li class="fw-bold text-primary">{{ $orderCode }}</li>
                @endforeach
            </ul>

            <p class="small text-secondary">
                Đơn hàng đã được tạo và đang chờ xử lý. VNPAY và chuyển khoản sẽ chờ thanh
                toán cho đến khi cổng thanh toán được tích hợp.
            </p>

            <a href="{{ route('store.home') }}" class="btn btn-primary px-4">
                Tiếp tục mua sắm
            </a>
        </section>
    </main>
@endsection
