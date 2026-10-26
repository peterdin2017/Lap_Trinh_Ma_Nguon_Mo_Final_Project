<section class="card checkout-card" aria-labelledby="order-items-title">
    <div class="card-body p-3 p-md-4">
        <h2 id="order-items-title" class="h5 fw-bold mb-3">
            <i class="bi bi-bag-check text-primary me-2" aria-hidden="true"></i>
            Sản phẩm đặt mua
        </h2>

        @foreach ($cartGroups as $sellerId => $sellerItems)
            @php
                $shop = $sellerItems->first()->product->seller;
                $voucherDiscount = (float) ($voucherDiscounts[$sellerId] ?? 0);
                $voucherCode = $voucherCodes[$sellerId] ?? '';
                $voucherErrorKey = 'voucher_codes.' . $sellerId;
            @endphp

            <div class="checkout-store-block">
                <div class="checkout-store-title">
                    <i class="bi bi-shop me-2" aria-hidden="true"></i>
                    {{ $shop->shop_name }}
                </div>

                <div class="checkout-voucher-shop">
                    @php
                        $shopSavedVouchers = $savedSellerVouchers->get((int) $sellerId, collect());
                        $selectedSavedVoucher = $shopSavedVouchers->first(
                            fn ($savedVoucher) => strcasecmp(
                                (string) $voucherCode,
                                (string) $savedVoucher->code
                            ) === 0
                        );
                        $selectedVoucherLabel = $selectedSavedVoucher?->code ?? 'Chọn voucher';
                    @endphp

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <div class="small fw-semibold">Voucher của Shop</div>
                            <div class="small text-secondary">{{ $shop->shop_name }}</div>
                        </div>

                        <button
                            id="voucher-picker-trigger-{{ $sellerId }}"
                            type="button"
                            class="btn btn-outline-primary btn-sm checkout-voucher-picker-trigger"
                            data-bs-toggle="modal"
                            data-bs-target="#voucher-picker-{{ $sellerId }}"
                        >
                            <i class="bi bi-ticket-perforated me-1" aria-hidden="true"></i>
                            <span data-voucher-trigger-label>{{ $selectedVoucherLabel }}</span>
                            <i class="bi bi-chevron-down ms-1" aria-hidden="true"></i>
                        </button>
                    </div>

                    <input
                        id="voucher-code-{{ $sellerId }}"
                        type="hidden"
                        name="voucher_codes[{{ $sellerId }}]"
                        value="{{ $voucherCode }}"
                    >

                    @if ($voucherDiscount > 0)
                        <div class="checkout-voucher-applied small mt-2">
                            <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                            Đã áp dụng, giảm {{ number_format($voucherDiscount, 0, ',', '.') }}₫
                        </div>
                    @endif

                    @if ($errors->has($voucherErrorKey))
                        <div class="text-danger small mt-2">
                            {{ $errors->first($voucherErrorKey) }}
                        </div>
                    @endif
                </div>

                <div
                    class="modal fade checkout-voucher-modal"
                    id="voucher-picker-{{ $sellerId }}"
                    data-voucher-picker-modal
                    data-seller-id="{{ $sellerId }}"
                    tabindex="-1"
                    aria-labelledby="voucher-picker-title-{{ $sellerId }}"
                    aria-hidden="true"
                >
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div>
                                    <h2
                                        class="modal-title h5 fw-bold"
                                        id="voucher-picker-title-{{ $sellerId }}"
                                    >
                                        Chọn voucher Shop
                                    </h2>
                                    <div class="small text-secondary">
                                        {{ $shop->shop_name }} · Chọn 1 voucher
                                    </div>
                                </div>

                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Đóng"
                                ></button>
                            </div>

                            <div class="modal-body">
                                @if ($shopSavedVouchers->isEmpty())
                                    <div class="text-center text-secondary py-5">
                                        <i class="bi bi-ticket-perforated fs-2" aria-hidden="true"></i>
                                        <p class="fw-semibold mt-2 mb-1">
                                            Bạn chưa lưu voucher của Shop này
                                        </p>
                                        <a
                                            href="{{ route('store.shops.show', $shop->seller_id) }}"
                                            class="text-decoration-none"
                                        >
                                            Quay lại trang Shop để lưu voucher
                                        </a>
                                    </div>
                                @else
                                    <label class="checkout-voucher-option checkout-voucher-none">
                                        <span class="checkout-voucher-option__side">
                                            <i class="bi bi-x-circle" aria-hidden="true"></i>
                                            <span>Không dùng</span>
                                        </span>

                                        <span class="checkout-voucher-option__content">
                                            <span>
                                                <span class="d-block fw-semibold">
                                                    Không sử dụng voucher
                                                </span>
                                                <span class="checkout-voucher-option__condition">
                                                    Bạn có thể chọn lại voucher bất cứ lúc nào.
                                                </span>
                                            </span>

                                            <input
                                                class="checkout-voucher-option__radio"
                                                type="radio"
                                                name="voucher-picker-ui-{{ $sellerId }}"
                                                value=""
                                                data-voucher-choice
                                                data-label="Chọn voucher"
                                            >
                                        </span>
                                    </label>

                                    @foreach ($shopSavedVouchers as $savedVoucher)
                                        @php
                                            $savedDiscountLabel = $savedVoucher->discount_type === 'percent'
                                                ? 'Giảm ' . rtrim(
                                                    rtrim(number_format((float) $savedVoucher->discount_value, 2, '.', ''), '0'),
                                                    '.'
                                                ) . '%'
                                                : 'Giảm ' . number_format(
                                                    (float) $savedVoucher->discount_value,
                                                    0,
                                                    ',',
                                                    '.'
                                                ) . '₫';

                                            $voucherOptionLabel = $savedVoucher->code
                                                . ' — '
                                                . $savedDiscountLabel;
                                        @endphp

                                        <label class="checkout-voucher-option">
                                            <span class="checkout-voucher-option__side">
                                                <i class="bi bi-ticket-perforated-fill" aria-hidden="true"></i>
                                                <span>Voucher Shop</span>
                                            </span>

                                            <span class="checkout-voucher-option__content">
                                                <span>
                                                    <span class="checkout-voucher-option__discount d-block">
                                                        {{ $savedDiscountLabel }}
                                                    </span>
                                                    <span class="checkout-voucher-option__code d-block">
                                                        Mã {{ $savedVoucher->code }}
                                                    </span>
                                                    <span class="checkout-voucher-option__condition d-block">
                                                        Đơn tối thiểu
                                                        {{ number_format((float) $savedVoucher->min_order_amount, 0, ',', '.') }}₫

                                                        @if ($savedVoucher->discount_type === 'percent' && $savedVoucher->max_discount)
                                                            · Tối đa
                                                            {{ number_format((float) $savedVoucher->max_discount, 0, ',', '.') }}₫
                                                        @endif
                                                    </span>
                                                    <span class="checkout-voucher-option__condition d-block">
                                                        Hạn dùng đến
                                                        {{ $savedVoucher->expires_at->format('d/m/Y H:i') }}
                                                    </span>
                                                </span>

                                                <input
                                                    class="checkout-voucher-option__radio"
                                                    type="radio"
                                                    name="voucher-picker-ui-{{ $sellerId }}"
                                                    value="{{ $savedVoucher->code }}"
                                                    data-voucher-choice
                                                    data-label="{{ $voucherOptionLabel }}"
                                                    @checked(strcasecmp((string) $voucherCode, $savedVoucher->code) === 0)
                                                >
                                            </span>
                                        </label>
                                    @endforeach
                                @endif
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary px-4"
                                    data-bs-dismiss="modal"
                                >
                                    Trở lại
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-primary px-4"
                                    data-confirm-voucher
                                >
                                    Đồng ý
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($sellerItems as $cartItem)
                    @php
                        $product = $cartItem->product;
                        $variant = $cartItem->variant;
                        $price = (float) ($variant?->price ?? $product->price);
                        $lineTotal = $price * $cartItem->quantity;
                        $image = $variant?->image_url ?: $product->image_url;
                    @endphp

                    <div class="checkout-product-row">
                        <div class="checkout-product-image">
                            @if ($image)
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image) }}"
                                    alt=""
                                    loading="lazy"
                                >
                            @else
                                <i class="bi bi-image text-secondary" aria-hidden="true"></i>
                            @endif
                        </div>

                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold">
                                {{ $product->product_name }}
                            </div>

                            @if ($variant)
                                <div class="small text-secondary">
                                    Phân loại: {{ $variant->variant_name }}
                                </div>
                            @endif

                            <div class="small text-secondary">
                                {{ number_format($price, 0, ',', '.') }}₫
                                × {{ $cartItem->quantity }}
                            </div>
                        </div>

                        <strong class="checkout-line-total">
                            {{ number_format($lineTotal, 0, ',', '.') }}₫
                        </strong>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</section>
