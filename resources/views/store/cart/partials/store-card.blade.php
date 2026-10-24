@php
    $shopName = $items->first()['shop_name'] ?? 'Cửa hàng';
    $availableCount = $items
        ->filter(fn ($item) => $item['available'] ?? true)
        ->count();
@endphp

<section
    class="card cart-card cart-store-card"
    data-store-card
    data-store-id="{{ $sellerId }}"
    aria-label="Sản phẩm từ {{ $shopName }}"
>
    <header class="cart-store-header d-flex flex-wrap align-items-center justify-content-between gap-3 px-3 px-md-4 py-3">
        <div class="d-flex align-items-center gap-3">
            <input
                class="form-check-input store-checkbox"
                type="checkbox"
                aria-label="Chọn tất cả sản phẩm của {{ $shopName }}"
                @disabled($availableCount === 0)
            >

            <a
                class="cart-store-name"
                href="{{ $sellerId ? route('store.shops.show', $sellerId) : route('store.home') }}"
            >
                <i class="bi bi-shop me-2" aria-hidden="true"></i>
                {{ $shopName }}
            </a>
        </div>

        <button
            class="btn btn-sm btn-outline-primary"
            type="button"
            data-chat-start
            data-seller-id="{{ $sellerId }}"
            data-shop-name="{{ $shopName }}"
            aria-label="Chat với {{ $shopName }}"
        >
            <i class="bi bi-chat-dots-fill me-1" aria-hidden="true"></i>
            Chat
        </button>
    </header>

    <div class="cart-items-list">
        @foreach ($items as $key => $item)
            @include('store.cart.partials.item', [
                'key' => $key,
                'item' => $item,
            ])
        @endforeach
    </div>

    <footer class="cart-store-footer px-3 px-md-4 py-3">
        <i class="bi bi-ticket-perforated me-2" aria-hidden="true"></i>
        Ưu đãi riêng của cửa hàng sẽ hiển thị tại bước thanh toán.
    </footer>
</section>
