@extends('seller.layouts.app')

@section('title', ($isEditing ? 'Sửa voucher' : 'Tạo voucher') . ' - Seller Center')

@section('content')
    @php
        $discountType = old('discount_type', $voucher->discount_type ?? 'percent');
        $startsAt = old('starts_at', $voucher->starts_at?->format('Y-m-d\TH:i'));
        $expiresAt = old('expires_at', $voucher->expires_at?->format('Y-m-d\TH:i'));
        $voucherStatus = old('status', $voucher->status ?? 'active');
    @endphp

    <main class="container-fluid py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge rounded-pill text-bg-primary">Voucher Shop</span>

                    @if ($isEditing)
                        <span class="text-secondary small">
                            Mã: {{ $voucher->code }}
                        </span>
                    @endif
                </div>

                <h1 class="h3 fw-bold mb-1">
                    {{ $isEditing ? 'Sửa voucher' : 'Tạo voucher mới' }}
                </h1>

                <p class="text-secondary mb-0">
                    Điền thông tin ưu đãi và thời gian khách có thể sử dụng voucher.
                </p>
            </div>

            <a href="{{ route('seller.vouchers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                Quay lại danh sách
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <div class="fw-semibold">Thông tin chưa hợp lệ</div>
                <div class="small mt-1">
                    Vui lòng kiểm tra các nội dung được báo lỗi bên dưới.
                </div>
            </div>
        @endif

        <form
            method="POST"
            action="{{ $isEditing
                ? route('seller.vouchers.update', $voucher->voucher_id)
                : route('seller.vouchers.store') }}"
        >
            @csrf

            @if ($isEditing)
                @method('PUT')
            @endif

            {{-- Ưu đãi --}}
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <span class="voucher-section-icon">
                            <i class="bi bi-ticket-perforated" aria-hidden="true"></i>
                        </span>

                        <div>
                            <h2 class="h5 fw-bold mb-1">Thông tin ưu đãi</h2>
                            <p class="text-secondary small mb-0">
                                Đặt mã và chọn cách giảm giá cho khách hàng.
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label for="code" class="form-label fw-semibold">
                                Mã voucher <span class="text-danger">*</span>
                            </label>

                            <input
                                id="code"
                                name="code"
                                value="{{ old('code', $voucher->code) }}"
                                maxlength="30"
                                class="form-control @error('code') is-invalid @enderror"
                                placeholder="Ví dụ: PHUCSTORE10"
                                autocomplete="off"
                                required
                            >

                            <div class="form-text">
                                Dùng chữ không dấu, số, dấu gạch ngang hoặc gạch dưới.
                            </div>

                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="discount_type" class="form-label fw-semibold">
                                Hình thức giảm <span class="text-danger">*</span>
                            </label>

                            <select
                                id="discount_type"
                                name="discount_type"
                                class="form-select @error('discount_type') is-invalid @enderror"
                                required
                            >
                                <option value="percent" @selected($discountType === 'percent')>
                                    Giảm theo phần trăm
                                </option>
                                <option value="fixed" @selected($discountType === 'fixed')>
                                    Giảm số tiền cố định
                                </option>
                            </select>

                            @error('discount_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="discount_value" class="form-label fw-semibold">
                                Mức giảm <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <input
                                    id="discount_value"
                                    name="discount_value"
                                    type="number"
                                    min="0.01"
                                    step="0.01"
                                    value="{{ old('discount_value', $voucher->discount_value) }}"
                                    class="form-control @error('discount_value') is-invalid @enderror"
                                    placeholder="{{ $discountType === 'percent' ? 'Ví dụ: 10' : 'Ví dụ: 50000' }}"
                                    required
                                >

                                <span class="input-group-text" id="discountUnit">
                                    {{ $discountType === 'percent' ? '%' : '₫' }}
                                </span>
                            </div>

                            <div class="form-text" id="discountHelp">
                                {{ $discountType === 'percent'
                                    ? 'Nhập phần trăm giảm. Ví dụ: nhập 10 tương ứng giảm 10%.'
                                    : 'Nhập số tiền giảm bằng đồng.' }}
                            </div>

                            @error('discount_value')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div
                            class="col-12 col-md-6"
                            id="maxDiscountField"
                            @if ($discountType !== 'percent') hidden @endif
                        >
                            <label for="max_discount" class="form-label fw-semibold">
                                Số tiền giảm tối đa <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <input
                                    id="max_discount"
                                    name="max_discount"
                                    type="number"
                                    min="1"
                                    step="1"
                                    value="{{ old('max_discount', $voucher->max_discount) }}"
                                    class="form-control @error('max_discount') is-invalid @enderror"
                                    placeholder="Ví dụ: 100000"
                                    @required($discountType === 'percent')
                                >
                                <span class="input-group-text">₫</span>
                            </div>

                            <div class="form-text">
                                Giới hạn số tiền được giảm khi voucher tính theo phần trăm.
                            </div>

                            @error('max_discount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- Điều kiện sử dụng --}}
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <span class="voucher-section-icon">
                            <i class="bi bi-cart-check" aria-hidden="true"></i>
                        </span>

                        <div>
                            <h2 class="h5 fw-bold mb-1">Điều kiện sử dụng</h2>
                            <p class="text-secondary small mb-0">
                                Quy định giá trị đơn tối thiểu và số lượt dùng.
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="min_order_amount" class="form-label fw-semibold">
                                Giá trị đơn hàng tối thiểu <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <input
                                    id="min_order_amount"
                                    name="min_order_amount"
                                    type="number"
                                    min="0"
                                    step="1"
                                    value="{{ old('min_order_amount', $voucher->min_order_amount ?? 0) }}"
                                    class="form-control @error('min_order_amount') is-invalid @enderror"
                                    placeholder="Nhập 0 nếu không yêu cầu"
                                    required
                                >
                                <span class="input-group-text">₫</span>
                            </div>

                            <div class="form-text">
                                Đơn hàng phải đạt giá trị này mới dùng được voucher.
                            </div>

                            @error('min_order_amount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="usage_limit" class="form-label fw-semibold">
                                Tổng lượt sử dụng
                            </label>

                            <input
                                id="usage_limit"
                                name="usage_limit"
                                type="number"
                                min="1"
                                step="1"
                                value="{{ old('usage_limit', $voucher->usage_limit) }}"
                                class="form-control @error('usage_limit') is-invalid @enderror"
                                placeholder="Để trống nếu không giới hạn"
                            >

                            <div class="form-text">
                                Đây là tổng số lượt dùng của tất cả khách hàng.
                            </div>

                            @error('usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- Thời hạn và trạng thái --}}
            <section class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <span class="voucher-section-icon">
                            <i class="bi bi-calendar-event" aria-hidden="true"></i>
                        </span>

                        <div>
                            <h2 class="h5 fw-bold mb-1">Thời hạn và trạng thái</h2>
                            <p class="text-secondary small mb-0">
                                Bấm biểu tượng lịch để chọn ngày và giờ áp dụng voucher.
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="starts_at" class="form-label fw-semibold">
                                Thời gian bắt đầu <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <input
                                    id="starts_at"
                                    name="starts_at"
                                    type="datetime-local"
                                    value="{{ $startsAt }}"
                                    class="form-control @error('starts_at') is-invalid @enderror"
                                    required
                                >
                                <button
                                    class="btn btn-outline-secondary js-open-datetime"
                                    type="button"
                                    data-target="starts_at"
                                    aria-label="Mở lịch chọn thời gian bắt đầu"
                                    title="Chọn ngày và giờ"
                                >
                                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="form-text">
                                Chọn ngày trước, sau đó chọn giờ bắt đầu.
                            </div>

                            @error('starts_at')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="expires_at" class="form-label fw-semibold">
                                Thời gian kết thúc <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <input
                                    id="expires_at"
                                    name="expires_at"
                                    type="datetime-local"
                                    value="{{ $expiresAt }}"
                                    class="form-control @error('expires_at') is-invalid @enderror"
                                    required
                                >
                                <button
                                    class="btn btn-outline-secondary js-open-datetime"
                                    type="button"
                                    data-target="expires_at"
                                    aria-label="Mở lịch chọn thời gian kết thúc"
                                    title="Chọn ngày và giờ"
                                >
                                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="form-text">
                                Thời gian kết thúc phải sau thời gian bắt đầu.
                            </div>

                            @error('expires_at')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label fw-semibold">
                                Trạng thái khi lưu
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required
                            >
                                <option value="active" @selected($voucherStatus === 'active')>
                                    Bật voucher
                                </option>
                                <option value="paused" @selected($voucherStatus === 'paused')>
                                    Tạm dừng
                                </option>
                            </select>

                            <div class="form-text">
                                Bạn có thể đổi trạng thái voucher sau khi tạo.
                            </div>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <a href="{{ route('seller.vouchers.index') }}" class="btn btn-outline-secondary">
                    Hủy
                </a>

                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check2 me-1" aria-hidden="true"></i>
                    {{ $isEditing ? 'Lưu thay đổi' : 'Tạo voucher' }}
                </button>
            </div>
        </form>
    </main>

    <style>
        .voucher-section-icon {
            display: inline-flex;
            flex: 0 0 42px;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            color: var(--seller-primary, #1A94FF);
            background: var(--seller-primary-light, #EBF5FF);
            font-size: 1.2rem;
        }

        [hidden] {
            display: none !important;
        }
    </style>

    <script>
        (() => {
            const type = document.getElementById('discount_type');
            const valueInput = document.getElementById('discount_value');
            const maxField = document.getElementById('maxDiscountField');
            const maxInput = document.getElementById('max_discount');
            const unit = document.getElementById('discountUnit');
            const help = document.getElementById('discountHelp');

            if (type && valueInput && maxField && maxInput && unit && help) {
                const updateDiscountFields = () => {
                    const isPercent = type.value === 'percent';

                    maxField.hidden = !isPercent;
                    maxInput.required = isPercent;
                    unit.textContent = isPercent ? '%' : '₫';
                    valueInput.placeholder = isPercent ? 'Ví dụ: 10' : 'Ví dụ: 50000';
                    help.textContent = isPercent
                        ? 'Nhập phần trăm giảm. Ví dụ: nhập 10 tương ứng giảm 10%.'
                        : 'Nhập số tiền giảm bằng đồng.';
                };

                type.addEventListener('change', updateDiscountFields);
                updateDiscountFields();
            }

            document.querySelectorAll('.js-open-datetime').forEach((button) => {
                button.addEventListener('click', () => {
                    const input = document.getElementById(button.dataset.target);

                    if (!input) {
                        return;
                    }

                    if (typeof input.showPicker === 'function') {
                        input.showPicker();
                    } else {
                        input.focus();
                    }
                });
            });
        })();
    </script>
@endsection
