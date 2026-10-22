@extends('store.layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    @php
        $cartCount = $cartGroups->sum(fn ($items) => $items->count());
        $cartQuantity = $cartGroups->flatten(1)->sum('quantity');
    @endphp

    <link rel="stylesheet" href="{{ asset('assets/store/cart/cart.css') }}">

    <div class="container py-4 py-lg-5 cart-page">
        @include('store.cart.partials.heading')

        <div id="cart-feedback" class="alert d-none" role="status" aria-live="polite"></div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
        @endif

        <input type="hidden" id="cart-csrf-token" value="{{ csrf_token() }}">
        <div id="cart-config"
             data-bulk-delete-url="{{ route('store.cart.items.destroy-selected') }}"></div>

        @include('store.cart.partials.empty-state')

        <div id="cart-layout" class="row g-4 align-items-start {{ $cartCount === 0 ? 'd-none' : '' }}">
            <section class="col-12 col-lg-8" aria-label="Sản phẩm trong giỏ hàng">
                @include('store.cart.partials.toolbar', compact('cartCount', 'cartQuantity'))

                <div id="cart-stores" class="d-flex flex-column gap-3">
                    @foreach ($cartGroups as $sellerId => $items)
                        @include('store.cart.partials.store-card', compact('sellerId', 'items'))
                    @endforeach
                </div>
            </section>

            <aside class="col-12 col-lg-4" aria-labelledby="summary-title">
                @include('store.cart.partials.summary')
            </aside>
        </div>

        <div class="d-sm-none mt-3 {{ $cartCount === 0 ? 'd-none' : '' }}" id="mobile-continue-shopping">
            <a href="{{ route('store.home') }}" class="btn btn-outline-primary w-100">
                Tiếp tục mua sắm
            </a>
        </div>
    </div>

    @include('store.cart.partials.delete-modal')

    <script src="{{ asset('assets/store/cart/cart.js') }}" defer></script>
@endsection
