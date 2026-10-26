@extends('admin.layouts.app')

@section('title', $isEditing ? 'Sửa voucher sàn' : 'Tạo voucher sàn')

@php
    $discountType = old('discount_type', $voucher->discount_type ?? 'fixed');
    $startValue = old(
        'starts_at',
        $voucher->exists
            ? $voucher->starts_at?->format('Y-m-d\TH:i')
            : now()->format('Y-m-d\TH:i')
    );
    $expiresValue = old(
        'expires_at',
        $voucher->exists ? $voucher->expires_at?->format('Y-m-d\TH:i') : ''
    );
    $voucherStatus = old('status', $voucher->status ?? 'inactive');
@endphp

@push('styles')
    <style>
        .voucher-form {
            color: var(--app-text, #0f172a);
        }

        .voucher-form__card {
            border: 1px solid var(--app-border, #e2e8f0);
            border-radius: 14px;
            background: var(--app-card-bg, #fff);
            box-shadow: 0 2px 10px rgba(15, 23, 42, .04);
        }

        .voucher-form__icon {
            display: inline-flex;
            flex: 0 0 42px;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            color: var(--app-primary, #1a94ff);
            background: var(--app-primary-light, #ebf5ff);
            font-size: 1.2rem;
        }

        .voucher-form__muted {
            color: var(--app-text-muted, #64748b);
        }

        .voucher-form .form-text {
            color: var(--app-text-muted, #64748b);
        }

        [data-bs-theme="dark"] .voucher-form__card {
            border-color: #334155;
            background: #1e293b;
        }

        [data-bs-theme="dark"] .voucher-form .form-control,
        [data-bs-theme="dark"] .voucher-form .form-select,
        [data-bs-theme="dark"] .voucher-form .input-group-text {
            border-color: #334155;
            background-color: #172338;
            color: #e2e8f0;
        }

        [data-bs-theme="dark"] .voucher-form .form-control::placeholder {
            color: #94a3b8;
        }

        [data-bs-theme="dark"] .voucher-form .form-text,
        [data-bs-theme="dark"] .voucher-form__muted {
            color: #94a3b8 !important;
        }

        [hidden] {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid voucher-form">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge rounded-pill text-bg-primary">Voucher của sàn</span>

                    @if ($isEditing)
                        <span class="voucher-form__muted small">
                            Mã: {{ $voucher->voucher_code }}
                        </span>
                    @endif
                </div>

                <h1 class="h3 fw-bold mb-1">
                    {{ $isEditing ? 'Sửa voucher sàn' : 'Tạo voucher sàn' }}
                </h1>

                <p class="voucher-form__muted mb-0">
                    Thiết lập ưu đãi áp dụng cho đơn hàng trên toàn sàn.
                </p>
            </div>

            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                Danh sách voucher
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <div class="fw-semibold">Thông tin chưa hợp lệ</div>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ $isEditing
                ? route('admin.vouchers.update', $voucher->platform_voucher_id)
                : route('admin.vouchers.store') }}"
        >
            @csrf

            @if ($isEditing)
                @method('PUT')
            @endif

            <section class="voucher-form__card mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <span class="voucher-form__icon">
                            <i class="bi bi-ticket-perforated" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Thông tin voucher</h2>
                            <p class="voucher-form__muted small mb-0">
                                Đặt mã, tên và mô tả để khách dễ nhận biết ưu đãi.
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label for="voucher_code" class="form-label fw-semibold">
                                Mã voucher <span class="text-danger">*</span>
                            </label>
                            <input
                                id="voucher_code"
                                name="voucher_code"
                                type="text"
                                value="{{ old('voucher_code', $voucher->voucher_code) }}"
                                maxlength="50"
                                class="form-control @error('voucher_code') is-invalid @enderror"
                                placeholder="Ví dụ: SALE2026"
                                autocomplete="off"
                                required
                            >
                            <div class="form-text">
                                Dùng chữ, số, dấu gạch ngang hoặc gạch dưới.
                            </div>
                            @error('voucher_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-lg-6">
                            <label for="voucher_name" class="form-label fw-semibold">
                                Tên voucher <span class="text-danger">*</span>
                            </label>
                            <input
                                id="voucher_name"
                                name="voucher_name"
                                type="text"
                                value="{{ old('voucher_name', $voucher->voucher_name) }}"
                                maxlength="150"
                                class="form-control @error('voucher_name') is-invalid @enderror"
                                placeholder="Ví dụ: Ưu đãi tháng 10"
                                required
                            >
                            @error('voucher_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Mô tả</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                maxlength="2000"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Mô tả ngắn về chương trình ưu đãi"
                            >{{ old('description', $voucher->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <section class="voucher-form__card mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <span class="voucher-form__icon">
                            <i class="bi bi-cart-check" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Mức giảm và điều kiện</h2>
                            <p class="voucher-form__muted small mb-0">
                                Chọn cách giảm, giá trị đơn tối thiểu và giới hạn lượt sử dụng.
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label for="discount_type" class="form-label fw-semibold">
                                Hình thức giảm <span class="text-danger">*</span>
                            </label>
                            <select
                                id="discount_type"
                                name="discount_type"
                                class="form-select @error('discount_type') is-invalid @enderror"
                                required
                            >
                                <option value="fixed" @selected($discountType === 'fixed')>
                                    Giảm số tiền cố định
                                </option>
                                <option value="percentage" @selected($discountType === 'percentage')>
                                    Giảm theo phần trăm
                                </option>
                            </select>
                            @error('discount_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
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
                                    required
                                >
                                <span class="input-group-text" id="discountSuffix">
                                    {{ $discountType === 'percentage' ? '%' : '₫' }}
                                </span>
                            </div>
                            <div class="form-text" id="discountHelp">
                                {{ $discountType === 'percentage'
                                    ? 'Nhập 10 tương ứng với mức giảm 10%.'
                                    : 'Nhập số tiền giảm tính bằng đồng.' }}
                            </div>
                            @error('discount_value')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div
                            class="col-12 col-md-4"
                            id="maxDiscountField"
                            @if ($discountType !== 'percentage') hidden @endif
                        >
                            <label for="max_discount_amount" class="form-label fw-semibold">
                                Mức giảm tối đa
                            </label>
                            <div class="input-group">
                                <input
                                    id="max_discount_amount"
                                    name="max_discount_amount"
                                    type="number"
                                    min="0.01"
                                    step="0.01"
                                    value="{{ old('max_discount_amount', $voucher->max_discount_amount) }}"
                                    class="form-control @error('max_discount_amount') is-invalid @enderror"
                                    placeholder="Để trống nếu không giới hạn"
                                >
                                <span class="input-group-text">₫</span>
                            </div>
                            <div class="form-text">
                                Chỉ áp dụng giới hạn này cho voucher phần trăm.
                            </div>
                            @error('max_discount_amount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="min_order_amount" class="form-label fw-semibold">
                                Giá trị đơn tối thiểu <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input
                                    id="min_order_amount"
                                    name="min_order_amount"
                                    type="number"
                                    min="0"
                                    step="1000"
                                    value="{{ old('min_order_amount', $voucher->min_order_amount ?? 0) }}"
                                    class="form-control @error('min_order_amount') is-invalid @enderror"
                                    required
                                >
                                <span class="input-group-text">₫</span>
                            </div>
                            @error('min_order_amount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
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
                            <div class="form-text">Tổng lượt dùng của tất cả khách hàng.</div>
                            @error('usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="per_user_limit" class="form-label fw-semibold">
                                Lượt dùng tối đa mỗi khách <span class="text-danger">*</span>
                            </label>
                            <input
                                id="per_user_limit"
                                name="per_user_limit"
                                type="number"
                                min="1"
                                step="1"
                                value="{{ old('per_user_limit', $voucher->per_user_limit ?? 1) }}"
                                class="form-control @error('per_user_limit') is-invalid @enderror"
                                required
                            >
                            @error('per_user_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <section class="voucher-form__card mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <span class="voucher-form__icon">
                            <i class="bi bi-calendar-event" aria-hidden="true"></i>
                        </span>
                        <div>
                            <h2 class="h5 fw-bold mb-1">Thời hạn và trạng thái</h2>
                            <p class="voucher-form__muted small mb-0">
                                Chọn thời điểm voucher bắt đầu và kết thúc hiệu lực.
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <label for="starts_at" class="form-label fw-semibold">
                                Thời gian bắt đầu <span class="text-danger">*</span>
                            </label>
                            <input
                                id="starts_at"
                                name="starts_at"
                                type="datetime-local"
                                value="{{ $startValue }}"
                                class="form-control @error('starts_at') is-invalid @enderror"
                                required
                            >
                            <div class="form-text">Chọn ngày và giờ bắt đầu áp dụng.</div>
                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="expires_at" class="form-label fw-semibold">
                                Thời gian kết thúc <span class="text-danger">*</span>
                            </label>
                            <input
                                id="expires_at"
                                name="expires_at"
                                type="datetime-local"
                                value="{{ $expiresValue }}"
                                class="form-control @error('expires_at') is-invalid @enderror"
                                required
                            >
                            <div class="form-text">Phải sau thời gian bắt đầu.</div>
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="status" class="form-label fw-semibold">
                                Trạng thái <span class="text-danger">*</span>
                            </label>
                            <select
                                id="status"
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required
                            >
                                <option value="inactive" @selected($voucherStatus === 'inactive')>
                                    Tắt — chưa áp dụng
                                </option>
                                <option value="active" @selected($voucherStatus === 'active')>
                                    Bật — cho phép sử dụng
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary">
                    Hủy
                </a>

                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check2 me-1" aria-hidden="true"></i>
                    {{ $isEditing ? 'Lưu thay đổi' : 'Tạo voucher' }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const type = document.getElementById('discount_type');
            const valueInput = document.getElementById('discount_value');
            const maxField = document.getElementById('maxDiscountField');
            const unit = document.getElementById('discountSuffix');
            const help = document.getElementById('discountHelp');

            if (!type || !valueInput || !maxField || !unit || !help) {
                return;
            }

            function updateDiscountFields() {
                const isPercentage = type.value === 'percentage';

                maxField.hidden = !isPercentage;
                unit.textContent = isPercentage ? '%' : '₫';
                valueInput.max = isPercentage ? '100' : '';
                valueInput.step = isPercentage ? '0.01' : '1000';
                help.textContent = isPercentage
                    ? 'Nhập 10 tương ứng với mức giảm 10%.'
                    : 'Nhập số tiền giảm tính bằng đồng.';
            }

            type.addEventListener('change', updateDiscountFields);
            updateDiscountFields();
        });
    </script>
@endpush
