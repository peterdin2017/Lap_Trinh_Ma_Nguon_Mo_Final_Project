<div
    class="modal fade checkout-voucher-modal checkout-voucher-platform-modal"
    id="platform-voucher-picker-modal"
    tabindex="-1"
    aria-labelledby="platform-voucher-picker-title"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2
                    class="modal-title h5 fw-bold"
                    id="platform-voucher-picker-title"
                >
                    Chọn voucher sàn
                </h2>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Đóng"
                ></button>
            </div>

            <div class="modal-body">
                @if ($savedPlatformVouchers->isEmpty())
                    <div class="text-center text-secondary py-5">
                        <i class="bi bi-ticket-perforated fs-2" aria-hidden="true"></i>

                        <p class="fw-semibold mt-2 mb-1">
                            Bạn chưa lưu voucher sàn nào
                        </p>

                        <a
                            href="{{ route('store.account.vouchers.index') }}"
                            class="text-decoration-none"
                        >
                            Xem voucher đã lưu
                        </a>
                    </div>
                @else
                    <label class="checkout-voucher-option checkout-voucher-none">
                        <span class="checkout-voucher-option__side">
                            <i class="bi bi-x-circle" aria-hidden="true"></i>
                            <span>Bỏ chọn</span>
                        </span>

                        <span class="checkout-voucher-option__content">
                            <span>
                                <span class="checkout-voucher-option__discount d-block">
                                    Không dùng voucher sàn
                                </span>
                            </span>

                            <input
                                class="checkout-voucher-option__radio"
                                type="radio"
                                name="platform-voucher-picker-ui"
                                value=""
                                data-platform-voucher-choice
                                data-label="Chọn voucher sàn"
                                @checked($platformVoucherCode === '')
                            >
                        </span>
                    </label>

                    @foreach ($savedPlatformVouchers as $platformVoucher)
                        @php
                            $discountLabel = $platformVoucher->discount_type === 'percentage'
                                ? 'Giảm ' . rtrim(
                                    rtrim(number_format((float) $platformVoucher->discount_value, 2, '.', ''), '0'),
                                    '.'
                                ) . '%'
                                : 'Giảm ' . number_format((float) $platformVoucher->discount_value, 0, ',', '.') . '₫';

                            $optionLabel = $platformVoucher->voucher_code . ' — ' . $discountLabel;
                        @endphp

                        <label class="checkout-voucher-option">
                            <span class="checkout-voucher-option__side">
                                <i class="bi bi-ticket-perforated-fill" aria-hidden="true"></i>
                                <span>Voucher sàn</span>
                            </span>

                            <span class="checkout-voucher-option__content">
                                <span>
                                    <span class="checkout-voucher-option__discount d-block">
                                        {{ $discountLabel }}
                                    </span>

                                    <span class="checkout-voucher-option__code d-block">
                                        Mã {{ $platformVoucher->voucher_code }}
                                    </span>

                                    <span class="checkout-voucher-option__condition d-block">
                                        Đơn tối thiểu
                                        {{ number_format((float) $platformVoucher->min_order_amount, 0, ',', '.') }}₫

                                        @if ($platformVoucher->discount_type === 'percentage' && $platformVoucher->max_discount_amount)
                                            · Tối đa
                                            {{ number_format((float) $platformVoucher->max_discount_amount, 0, ',', '.') }}₫
                                        @endif
                                    </span>

                                    <span class="checkout-voucher-option__condition d-block">
                                        Hạn dùng đến
                                        {{ $platformVoucher->expires_at->format('d/m/Y H:i') }}
                                    </span>
                                </span>

                                <input
                                    class="checkout-voucher-option__radio"
                                    type="radio"
                                    name="platform-voucher-picker-ui"
                                    value="{{ $platformVoucher->voucher_code }}"
                                    data-platform-voucher-choice
                                    data-label="{{ $optionLabel }}"
                                    @checked(
                                        strcasecmp(
                                            (string) $platformVoucherCode,
                                            (string) $platformVoucher->voucher_code
                                        ) === 0
                                    )
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
                    data-confirm-platform-voucher
                >
                    Đồng ý
                </button>
            </div>
        </div>
    </div>
</div>
