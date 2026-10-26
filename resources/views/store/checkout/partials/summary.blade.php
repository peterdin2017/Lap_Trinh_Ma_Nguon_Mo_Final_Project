<aside class="col-12 col-lg-4" aria-labelledby="checkout-summary-title">
    <div class="card checkout-card checkout-summary">
        <div class="card-body p-3 p-md-4">
            <h2 id="checkout-summary-title" class="h5 fw-bold mb-3">
                Tóm tắt thanh toán
            </h2>

            <div class="checkout-voucher-shop mb-3">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <div class="fw-semibold">
                            <i class="bi bi-ticket-perforated text-primary me-1" aria-hidden="true"></i>
                            Voucher sàn
                        </div>
                        <div class="small text-secondary">
                            Áp dụng một lần cho toàn bộ giỏ hàng.
                        </div>
                    </div>

                    <button
                        type="button"
                        id="platform-voucher-picker-trigger"
                        class="btn btn-outline-primary checkout-voucher-picker-trigger"
                        data-bs-toggle="modal"
                        data-bs-target="#platform-voucher-picker-modal"
                    >
                        <i class="bi bi-ticket-perforated me-1" aria-hidden="true"></i>
                        <span data-platform-voucher-trigger-label>
                            {{ $platformVoucherCode !== '' ? $platformVoucherCode : 'Chọn voucher sàn' }}
                        </span>
                    </button>
                </div>

                @if ($platformVoucherDiscount > 0)
                    <div class="checkout-voucher-applied small mt-2">
                        <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                        Voucher sàn giảm
                        {{ number_format($platformVoucherDiscount, 0, ',', '.') }}₫
                    </div>
                @endif

                @error('platform_voucher_code')
                    <div class="small text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="alert alert-info py-2 mb-3 small">
                Voucher Shop được chọn riêng cho từng Shop. Voucher sàn áp dụng chung cho toàn giỏ.
            </div>

            <div class="checkout-summary-line">
                <span>Tổng tiền hàng</span>
                <strong>{{ number_format($subtotal, 0, ',', '.') }}₫</strong>
            </div>

            <div class="checkout-summary-line">
                <span>Phí vận chuyển</span>
                <strong>{{ number_format($shippingFee, 0, ',', '.') }}₫</strong>
            </div>

            <div class="checkout-summary-line">
                <span>Voucher Shop</span>
                <strong class="text-success">
                    −{{ number_format(array_sum($voucherDiscounts), 0, ',', '.') }}₫
                </strong>
            </div>

            <div class="checkout-summary-line">
                <span>Voucher sàn</span>
                <strong class="text-success">
                    −{{ number_format($platformVoucherDiscount, 0, ',', '.') }}₫
                </strong>
            </div>

            <hr>

            <div class="checkout-summary-line checkout-summary-total">
                <span>Tổng thanh toán</span>
                <strong>{{ number_format($totalAmount, 0, ',', '.') }}₫</strong>
            </div>

            <p class="small text-secondary mt-2">
                Phí vận chuyển tạm tính
                {{ number_format(30000, 0, ',', '.') }}₫ mỗi cửa hàng.
            </p>

            <button
                type="submit"
                name="intent"
                value="place_order"
                class="btn btn-primary btn-lg w-100 fw-bold mt-2"
            >
                <i class="bi bi-lock me-2" aria-hidden="true"></i>
                Đặt hàng
            </button>

            <a
                href="{{ route('store.cart.index') }}"
                class="checkout-back-link d-block text-center mt-3"
            >
                Quay lại giỏ hàng
            </a>
        </div>
    </div>
</aside>
