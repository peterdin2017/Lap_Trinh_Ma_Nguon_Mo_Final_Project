@extends('store.layouts.app')

@section('title', 'Thanh toán')

@push('styles')
    <style>
        .checkout-page {
            --checkout-primary: #1a94ff;
            --checkout-border: #e2e8f0;
        }

        .checkout-page .btn-primary {
            --bs-btn-bg: var(--checkout-primary);
            --bs-btn-border-color: var(--checkout-primary);
            --bs-btn-hover-bg: #0077e6;
            --bs-btn-hover-border-color: #0077e6;
        }

        .checkout-card {
            border: 1px solid var(--checkout-border);
            border-radius: 12px;
            background: #fff;
        }

        .checkout-address-current {
            display: grid;
            grid-template-columns: minmax(180px, 270px) minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px 20px;
            padding: 14px 16px;
            border: 1px solid var(--checkout-border);
            border-radius: 8px;
            background: #fff;
        }

        .checkout-address-identity {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px 10px;
        }

        .checkout-address-text {
            color: #475569;
        }

        .checkout-address-badge {
            padding: 2px 8px;
            border: 1px solid #bfdbfe;
            color: var(--checkout-primary);
            font-size: .75rem;
            white-space: nowrap;
        }

        .checkout-address-option {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--checkout-border);
            border-radius: 10px;
            background: #fff;
            color: #0f172a;
            text-align: left;
            transition: border-color .15s ease, background-color .15s ease;
        }

        .checkout-address-option:hover,
        .checkout-address-option:focus-visible,
        .checkout-address-option.is-default {
            border-color: var(--checkout-primary);
            background: #f3f9ff;
        }

        .checkout-address-modal {
            border: 0;
            border-radius: 14px;
        }

        .checkout-store-block + .checkout-store-block {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--checkout-border);
        }

        .checkout-store-title {
            margin-bottom: 8px;
            color: #0f172a;
            font-weight: 700;
        }

        .checkout-voucher-shop {
            margin: 10px 0 12px;
            padding: 12px;
            border: 1px solid var(--checkout-border);
            border-radius: 8px;
            background: #f8fafc;
        }

        .checkout-voucher-applied {
            color: #198754;
        }

        .checkout-product-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-top: 1px solid #f1f5f9;
        }

        .checkout-product-image {
            display: grid;
            width: 64px;
            height: 64px;
            flex: 0 0 64px;
            place-items: center;
            overflow: hidden;
            border: 1px solid var(--checkout-border);
            border-radius: 8px;
            background: #f8fafc;
        }

        .checkout-product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .checkout-line-total {
            color: #ee4d2d;
            white-space: nowrap;
        }

        .checkout-payment-option {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px;
            border: 1px solid var(--checkout-border);
            border-radius: 8px;
            cursor: pointer;
        }

        .checkout-payment-option:has(input:checked) {
            border-color: var(--checkout-primary);
            background: #f3f9ff;
        }

        .checkout-payment-option .form-check-input {
            margin-top: 3px;
        }

        .checkout-summary {
            position: sticky;
            top: 90px;
        }

        .checkout-summary-line {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin: 12px 0;
            color: #475569;
        }

        .checkout-summary-line strong {
            color: #0f172a;
            white-space: nowrap;
        }

        .checkout-summary-total {
            align-items: center;
            color: #0f172a;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .checkout-summary-total strong {
            color: #ee4d2d;
            font-size: 1.35rem;
        }

        .checkout-back-link {
            color: var(--checkout-primary);
            font-size: .9rem;
            font-weight: 600;
            text-decoration: none;
        }

        @media (max-width: 767.98px) {
            .checkout-address-current {
                grid-template-columns: 1fr auto;
            }

            .checkout-address-identity,
            .checkout-address-text {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 991.98px) {
            .checkout-summary {
                position: static;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .checkout-address-option {
                transition: none;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('checkout-address-modal');

            if (!modal) {
                return;
            }

            const fields = {
                shipping_name: document.getElementById('shipping_name'),
                shipping_phone: document.getElementById('shipping_phone'),
                street_address: document.getElementById('street_address'),
                ward: document.getElementById('ward'),
                district: document.getElementById('district'),
                province: document.getElementById('province'),
            };

            const currentAddressName = document.getElementById('current-address-name');
            const currentAddressPhone = document.getElementById('current-address-phone');
            const currentAddressText = document.getElementById('current-address-text');
            const currentAddressBadge = document.getElementById('current-address-badge');

            modal.querySelectorAll('[data-checkout-address]').forEach((button) => {
                button.addEventListener('click', () => {
                    fields.shipping_name.value = button.dataset.name || '';
                    fields.shipping_phone.value = button.dataset.phone || '';
                    fields.street_address.value = button.dataset.street || '';
                    fields.ward.value = button.dataset.ward || '';
                    fields.district.value = button.dataset.district || '';
                    fields.province.value = button.dataset.province || '';

                    if (currentAddressName) {
                        currentAddressName.textContent = button.dataset.name || '';
                    }

                    if (currentAddressPhone) {
                        currentAddressPhone.textContent = button.dataset.phone || '';
                    }

                    if (currentAddressText) {
                        currentAddressText.textContent = [
                            button.dataset.street,
                            button.dataset.ward,
                            button.dataset.district,
                            button.dataset.province,
                        ].filter(Boolean).join(', ');
                    }

                    if (currentAddressBadge) {
                        currentAddressBadge.classList.toggle(
                            'd-none',
                            button.dataset.isDefault !== '1'
                        );
                    }

                    modal.querySelectorAll('[data-checkout-address]')
                        .forEach((option) => option.classList.remove('is-default'));

                    button.classList.add('is-default');

                    window.bootstrap?.Modal.getOrCreateInstance(modal).hide();
                });
            });
        });
    </script>
@endpush

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

            @foreach ($cartItemIds as $cartItemId)
                <input
                    type="hidden"
                    name="cart_item_ids[]"
                    value="{{ $cartItemId }}"
                >
            @endforeach

            <div class="row g-4 align-items-start">
                <div class="col-12 col-lg-8 d-flex flex-column gap-3">
                    <section class="card checkout-card" aria-labelledby="shipping-title">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                                <h2 id="shipping-title" class="h5 fw-bold mb-0">
                                    <i class="bi bi-geo-alt text-primary me-2" aria-hidden="true"></i>
                                    Địa chỉ nhận hàng
                                </h2>

                                @if ($addresses->isNotEmpty())
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#checkout-address-modal"
                                    >
                                        Thay đổi
                                    </button>
                                @else
                                    <span class="small text-secondary">
                                        Nhập địa chỉ bên dưới
                                    </span>
                                @endif
                            </div>

                            @if ($defaultAddress)
                                <div
                                    class="checkout-address-current mb-3"
                                    id="checkout-address-current"
                                >
                                    <div class="checkout-address-identity">
                                        <strong id="current-address-name">
                                            {{ $defaultAddress->recipient_name }}
                                        </strong>
                                        <span class="text-secondary" id="current-address-phone">
                                            {{ $defaultAddress->phone }}
                                        </span>
                                    </div>

                                    <div class="checkout-address-text" id="current-address-text">
                                        {{ $defaultAddress->street_address }},
                                        {{ $defaultAddress->ward }},
                                        {{ $defaultAddress->district }},
                                        {{ $defaultAddress->province }}
                                    </div>

                                    <span
                                        class="checkout-address-badge {{ $defaultAddress->is_default ? '' : 'd-none' }}"
                                        id="current-address-badge"
                                    >
                                        Mặc định
                                    </span>
                                </div>
                            @else
                                <div class="alert alert-info small">
                                    Chưa có địa chỉ đã lưu. Vui lòng nhập thông tin nhận hàng bên dưới.
                                </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="shipping_name" class="form-label">
                                        Người nhận
                                    </label>
                                    <input
                                        id="shipping_name"
                                        name="shipping_name"
                                        type="text"
                                        class="form-control @error('shipping_name') is-invalid @enderror"
                                        value="{{ old('shipping_name', $defaultAddress?->recipient_name) }}"
                                        maxlength="100"
                                        autocomplete="name"
                                        required
                                    >
                                    @error('shipping_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="shipping_phone" class="form-label">
                                        Số điện thoại
                                    </label>
                                    <input
                                        id="shipping_phone"
                                        name="shipping_phone"
                                        type="tel"
                                        class="form-control @error('shipping_phone') is-invalid @enderror"
                                        value="{{ old('shipping_phone', $defaultAddress?->phone) }}"
                                        maxlength="15"
                                        inputmode="tel"
                                        autocomplete="tel"
                                        required
                                    >
                                    @error('shipping_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="street_address" class="form-label">
                                        Địa chỉ cụ thể
                                    </label>
                                    <input
                                        id="street_address"
                                        name="street_address"
                                        type="text"
                                        class="form-control @error('street_address') is-invalid @enderror"
                                        value="{{ old('street_address', $defaultAddress?->street_address) }}"
                                        maxlength="255"
                                        autocomplete="street-address"
                                        required
                                    >
                                    @error('street_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="province" class="form-label">
                                        Tỉnh / Thành phố
                                    </label>
                                    <input
                                        id="province"
                                        name="province"
                                        type="text"
                                        class="form-control @error('province') is-invalid @enderror"
                                        value="{{ old('province', $defaultAddress?->province) }}"
                                        maxlength="100"
                                        autocomplete="address-level1"
                                        required
                                    >
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="district" class="form-label">
                                        Quận / Huyện
                                    </label>
                                    <input
                                        id="district"
                                        name="district"
                                        type="text"
                                        class="form-control @error('district') is-invalid @enderror"
                                        value="{{ old('district', $defaultAddress?->district) }}"
                                        maxlength="100"
                                        autocomplete="address-level2"
                                        required
                                    >
                                    @error('district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="ward" class="form-label">
                                        Phường / Xã
                                    </label>
                                    <input
                                        id="ward"
                                        name="ward"
                                        type="text"
                                        class="form-control @error('ward') is-invalid @enderror"
                                        value="{{ old('ward', $defaultAddress?->ward) }}"
                                        maxlength="100"
                                        autocomplete="address-level3"
                                        required
                                    >
                                    @error('ward')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </section>

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
                                        <label
                                            for="voucher-code-{{ $sellerId }}"
                                            class="form-label small fw-semibold mb-2"
                                        >
                                            Voucher của Shop
                                        </label>

                                        <div class="input-group">
                                            <input
                                                id="voucher-code-{{ $sellerId }}"
                                                name="voucher_codes[{{ $sellerId }}]"
                                                class="form-control"
                                                type="text"
                                                value="{{ $voucherCode }}"
                                                maxlength="30"
                                                placeholder="Nhập mã voucher của Shop"
                                                autocomplete="off"
                                                aria-describedby="voucher-help-{{ $sellerId }}"
                                            >
                                        </div>

                                        <div id="voucher-help-{{ $sellerId }}" class="form-text">
                                            Mỗi voucher chỉ áp dụng cho sản phẩm của Shop này.
                                        </div>

                                        @if ($voucherDiscount > 0)
                                            <div class="checkout-voucher-applied small mt-2">
                                                <i class="bi bi-check-circle me-1" aria-hidden="true"></i>
                                                Đã áp dụng, giảm
                                                {{ number_format($voucherDiscount, 0, ',', '.') }}₫
                                            </div>
                                        @endif

                                        @if ($errors->has($voucherErrorKey))
                                            <div class="text-danger small mt-2">
                                                {{ $errors->first($voucherErrorKey) }}
                                            </div>
                                        @endif
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

                    <section class="card checkout-card" aria-labelledby="payment-title">
                        <div class="card-body p-3 p-md-4">
                            <h2 id="payment-title" class="h5 fw-bold mb-3">
                                <i class="bi bi-credit-card text-primary me-2" aria-hidden="true"></i>
                                Phương thức thanh toán
                            </h2>

                            @php
                                $paymentMethod = old('payment_method', 'cod');
                            @endphp

                            <div class="d-flex flex-column gap-2">
                                <label class="checkout-payment-option">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="payment_method"
                                        value="cod"
                                        @checked($paymentMethod === 'cod')
                                    >
                                    <span>
                                        <strong>Thanh toán khi nhận hàng (COD)</strong>
                                        <small class="d-block text-secondary">
                                            Thanh toán trực tiếp cho đơn vị giao hàng.
                                        </small>
                                    </span>
                                </label>

                                <label class="checkout-payment-option">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="payment_method"
                                        value="vnpay"
                                        @checked($paymentMethod === 'vnpay')
                                    >
                                    <span>
                                        <strong>VNPAY</strong>
                                        <small class="d-block text-secondary">
                                            Đơn hàng sẽ chờ xác nhận thanh toán qua cổng VNPAY.
                                        </small>
                                    </span>
                                </label>

                                <label class="checkout-payment-option">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="payment_method"
                                        value="bank_transfer"
                                        @checked($paymentMethod === 'bank_transfer')
                                    >
                                    <span>
                                        <strong>Chuyển khoản qua QR</strong>
                                        <small class="d-block text-secondary">
                                            Đơn hàng ở trạng thái chờ thanh toán; QR cần cấu hình tài khoản nhận tiền.
                                        </small>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </section>

                    <section class="card checkout-card" aria-labelledby="note-title">
                        <div class="card-body p-3 p-md-4">
                            <label id="note-title" for="note" class="form-label fw-bold">
                                Ghi chú cho người bán
                            </label>

                            <textarea
                                id="note"
                                name="note"
                                rows="3"
                                maxlength="1000"
                                class="form-control"
                                placeholder="Ví dụ: Giao hàng giờ hành chính"
                            >{{ old('note') }}</textarea>
                        </div>
                    </section>
                </div>

                <aside class="col-12 col-lg-4" aria-labelledby="checkout-summary-title">
                    <div class="card checkout-card checkout-summary">
                        <div class="card-body p-3 p-md-4">
                            <h2 id="checkout-summary-title" class="h5 fw-bold mb-3">
                                Tóm tắt thanh toán
                            </h2>

                            <div class="checkout-voucher mb-3">
                                <div class="small text-secondary">
                                    Nhập mã trong từng Shop ở danh sách sản phẩm, sau đó áp dụng tại đây.
                                </div>

                                <button
                                    type="submit"
                                    name="intent"
                                    value="apply_vouchers"
                                    class="btn btn-outline-primary w-100 mt-2"
                                    formnovalidate
                                >
                                    <i class="bi bi-ticket-perforated me-1" aria-hidden="true"></i>
                                    Áp dụng voucher
                                </button>
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
                                <span>Giảm giá</span>
                                <strong class="text-success">
                                    −{{ number_format($discountTotal, 0, ',', '.') }}₫
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
            </div>
        </form>

        @if ($addresses->isNotEmpty())
            <div
                class="modal fade"
                id="checkout-address-modal"
                tabindex="-1"
                aria-labelledby="checkout-address-modal-title"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content checkout-address-modal">
                        <div class="modal-header">
                            <h2
                                class="modal-title h5 fw-bold"
                                id="checkout-address-modal-title"
                            >
                                Chọn địa chỉ nhận hàng
                            </h2>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Đóng"
                            ></button>
                        </div>

                        <div class="modal-body">
                            <div class="d-flex flex-column gap-2">
                                @foreach ($addresses as $address)
                                    <button
                                        type="button"
                                        class="checkout-address-option {{ $defaultAddress?->address_id === $address->address_id ? 'is-default' : '' }}"
                                        data-checkout-address
                                        data-name="{{ $address->recipient_name }}"
                                        data-phone="{{ $address->phone }}"
                                        data-street="{{ $address->street_address }}"
                                        data-ward="{{ $address->ward }}"
                                        data-district="{{ $address->district }}"
                                        data-province="{{ $address->province }}"
                                        data-is-default="{{ $address->is_default ? '1' : '0' }}"
                                    >
                                        <span class="d-flex flex-wrap align-items-center gap-2">
                                            <strong>{{ $address->recipient_name }}</strong>
                                            <span class="text-secondary">
                                                {{ $address->phone }}
                                            </span>

                                            @if ($address->is_default)
                                                <span class="badge rounded-pill text-bg-primary">
                                                    Mặc định
                                                </span>
                                            @endif
                                        </span>

                                        <span class="d-block small text-secondary mt-1">
                                            {{ $address->street_address }},
                                            {{ $address->ward }},
                                            {{ $address->district }},
                                            {{ $address->province }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a
                                href="{{ route('store.account.addresses.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Quản lý địa chỉ
                            </a>

                            <button
                                type="button"
                                class="btn btn-primary"
                                data-bs-dismiss="modal"
                            >
                                Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
