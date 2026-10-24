@php
    $unitPrice = (float) $item['price'];
    $quantity = (int) $item['quantity'];
    $stock = (int) $item['stock_quantity'];
    $available = (bool) ($item['available'] ?? true);
    $lineTotal = $unitPrice * $quantity;

    $buyNowKey = (string) request()->query('buy_now', '');
    $cartItemKey = (string) $key;
    $cartItemId = (string) ($item['cart_item_id'] ?? '');

    $isBuyNowItem = $buyNowKey !== ''
        && ($buyNowKey === $cartItemKey || $buyNowKey === $cartItemId);
@endphp

<article
    class="cart-item {{ $available ? '' : 'cart-item--unavailable' }}"
    data-cart-item
    data-item-key="{{ $key }}"
    data-cart-item-id="{{ $item['cart_item_id'] }}"
    data-unit-price="{{ $unitPrice }}"
    data-quantity="{{ $quantity }}"
    data-stock="{{ $stock }}"
    data-subtotal="{{ $lineTotal }}"
    data-update-url="{{ route('store.cart.items.update', ['key' => $key]) }}"
    data-delete-url="{{ route('store.cart.items.destroy', ['key' => $key]) }}"
>
    <div class="cart-item__select">
        <input
            class="form-check-input cart-item-checkbox"
            type="checkbox"
            name="cart_item_ids[]"
            value="{{ $item['cart_item_id'] }}"
            form="checkout-form"
            @checked($isBuyNowItem && $available)
            @disabled(!$available)
            aria-label="Chọn {{ $item['product_name'] }}"
        >
    </div>

    <a
        class="cart-item__image-link"
        href="{{ route('store.products.show', $item['product_id']) }}"
        tabindex="-1"
        aria-hidden="true"
    >
        @if (!empty($item['image_url']))
            <img
                class="cart-item__image"
                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item['image_url']) }}"
                alt=""
            >
        @else
            <span class="cart-item__placeholder">
                <i class="bi bi-image" aria-hidden="true"></i>
            </span>
        @endif
    </a>

    <div class="cart-item__details">
        <a
            class="cart-item__name"
            href="{{ route('store.products.show', $item['product_id']) }}"
        >
            {{ $item['product_name'] }}
        </a>

        @if (!empty($item['variant_name']))
            <div class="cart-item__variant">
                <i class="bi bi-tag me-1" aria-hidden="true"></i>
                {{ $item['variant_name'] }}
            </div>
        @endif

        @unless ($available)
            <div class="small text-danger mt-1" role="status">
                Sản phẩm hiện không khả dụng.
            </div>
        @endunless

        <div class="cart-item__unit-price d-lg-none">
            <span data-unit-price-display>
                {{ number_format($unitPrice, 0, ',', '.') }}₫
            </span>
            / sản phẩm
        </div>

        <div
            class="cart-quantity-control input-group input-group-sm"
            role="group"
            aria-label="Điều chỉnh số lượng {{ $item['product_name'] }}"
        >
            <button
                class="btn btn-outline-secondary quantity-step"
                type="button"
                data-step="-1"
                aria-label="Giảm số lượng"
                @disabled(!$available || $quantity <= 1)
            >
                <i class="bi bi-dash" aria-hidden="true"></i>
            </button>

            <input
                class="form-control cart-quantity-input"
                type="number"
                min="1"
                max="{{ max($stock, 1) }}"
                value="{{ $quantity }}"
                inputmode="numeric"
                aria-label="Số lượng {{ $item['product_name'] }}"
                @disabled(!$available)
            >

            <button
                class="btn btn-outline-secondary quantity-step"
                type="button"
                data-step="1"
                aria-label="Tăng số lượng"
                @disabled(!$available || $quantity >= $stock)
            >
                <i class="bi bi-plus" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <div class="cart-item__pricing d-none d-lg-block">
        <div class="cart-item__unit-price">
            <span data-unit-price-display>
                {{ number_format($unitPrice, 0, ',', '.') }}₫
            </span>
        </div>

        <div class="cart-item__subtotal" data-line-subtotal>
            {{ number_format($lineTotal, 0, ',', '.') }}₫
        </div>

        <button
            type="button"
            class="cart-item__delete"
            data-delete-item
            aria-label="Xóa {{ $item['product_name'] }} khỏi giỏ"
        >
            <i class="bi bi-trash3" aria-hidden="true"></i>
            <span>Xóa</span>
        </button>
    </div>

    <div class="cart-item__mobile-footer d-lg-none">
        <div class="cart-item__subtotal" data-line-subtotal>
            {{ number_format($lineTotal, 0, ',', '.') }}₫
        </div>

        <button
            type="button"
            class="cart-item__delete"
            data-delete-item
            aria-label="Xóa {{ $item['product_name'] }} khỏi giỏ"
        >
            <i class="bi bi-trash3" aria-hidden="true"></i>
            <span>Xóa</span>
        </button>
    </div>
</article>
