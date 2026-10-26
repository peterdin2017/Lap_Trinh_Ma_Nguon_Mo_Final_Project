@extends('store.layouts.app')

@section('title', 'Thanh toán')

@include('store.checkout.partials.styles')
@include('store.checkout.partials.scripts')

@section('content')
    <div class="container py-4 py-lg-5 checkout-page">
        <div class="mb-4">
            <div class="small text-primary fw-semibold text-uppercase mb-1">
                Hoàn tất đơn hàng
            </div>
            <h1 class="h3 fw-bold mb-1">Thanh toán</h1>
            <p class="text-secondary mb-0">
                Kiểm tra địa chỉ và thông tin đơn trước khi đặt hàng.
            </p>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('store.checkout.store') }}"
            id="checkout-submit-form"
        >
            @csrf

            <button
                type="submit"
                name="intent"
                value="apply_vouchers"
                class="visually-hidden"
                formnovalidate
                tabindex="-1"
                aria-hidden="true"
            ></button>

            @foreach ($cartItemIds as $cartItemId)
                <input
                    type="hidden"
                    name="cart_item_ids[]"
                    value="{{ $cartItemId }}"
                >
            @endforeach

            <input
                type="hidden"
                id="platform-voucher-code"
                name="platform_voucher_code"
                value="{{ $platformVoucherCode }}"
            >

            <div class="row g-4 align-items-start">
                <div class="col-12 col-lg-8 d-flex flex-column gap-3">
                    @include('store.checkout.partials.shipping-address')
                    @include('store.checkout.partials.order-items')
                    @include('store.checkout.partials.payment-method')
                    @include('store.checkout.partials.note')
                </div>

                @include('store.checkout.partials.summary')
            </div>
        </form>

        @include('store.checkout.partials.platform-voucher-modal')
        @include('store.checkout.partials.address-modal')
    </div>
@endsection
