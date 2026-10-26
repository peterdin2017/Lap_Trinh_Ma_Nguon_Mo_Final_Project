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
