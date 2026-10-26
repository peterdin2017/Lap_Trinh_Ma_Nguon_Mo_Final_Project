<form
    method="POST"
    action="{{ route('store.cart.items.store') }}"
    id="purchaseForm"
    novalidate
>
    @csrf

    <input
        type="hidden"
        name="product_id"
        value="{{ $product->product_id }}"
    >

    <input
        type="hidden"
        name="variant_id"
        id="selectedVariantId"
        value="{{ $selectedVariant?->variant_id }}"
    >

    <div class="mb-3" style="max-width: 160px;">
        <label for="quantity" class="form-label">Số lượng</label>

        <input
            id="quantity"
            name="quantity"
            type="number"
            min="1"
            max="{{ max((int) $initialStock, 1) }}"
            value="{{ old('quantity', 1) }}"
            class="form-control @error('quantity') is-invalid @enderror"
            required
        >

        @error('quantity')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <div id="clientHint" class="text-danger small mt-1" hidden></div>
    </div>

    @error('variant_id')
        <div class="text-danger small mb-2">{{ $message }}</div>
    @enderror

    <div class="d-flex flex-wrap gap-2">
        <button
            type="submit"
            name="intent"
            value="cart"
            class="btn btn-outline-primary"
            id="addToCartButton"
            @disabled(
                ($hasVariants && !$selectedVariant)
                || (!$hasVariants && $initialStock < 1)
            )
        >
            <i class="bi bi-cart-plus me-1" aria-hidden="true"></i>
            Thêm vào giỏ hàng
        </button>

        <button
            type="submit"
            name="intent"
            value="buy_now"
            class="btn btn-primary"
            id="buyNowButton"
            @disabled(
                ($hasVariants && !$selectedVariant)
                || (!$hasVariants && $initialStock < 1)
            )
        >
            <i class="bi bi-lightning-charge me-1" aria-hidden="true"></i>
            Mua ngay
        </button>
    </div>
</form>
