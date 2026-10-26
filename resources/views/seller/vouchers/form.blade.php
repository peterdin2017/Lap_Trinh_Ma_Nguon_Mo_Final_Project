@extends('seller.layouts.app')

@section('title', ($isEditing ? 'Sửa voucher' : 'Tạo voucher') . ' - Seller Center')

@php
    $discountType = old('discount_type', $voucher->discount_type ?? 'percent');
    $startsAt = old('starts_at', $voucher->starts_at?->format('Y-m-d\TH:i'));
    $expiresAt = old('expires_at', $voucher->expires_at?->format('Y-m-d\TH:i'));
    $voucherStatus = old('status', $voucher->status ?? 'active');
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

        [data-theme="dark"] .voucher-form__card {
            border-color: #334155;
            background: #1e293b;
            color: #e2e8f0;
        }

        [data-theme="dark"] .voucher-form .form-control,
        [data-theme="dark"] .voucher-form .form-select,
        [data-theme="dark"] .voucher-form .input-group-text {
            border-color: #334155;
            background-color: #172338;
            color: #e2e8f0;
        }

        [data-theme="dark"] .voucher-form .form-control::placeholder {
            color: #94a3b8;
        }

        [data-theme="dark"] .voucher-form .form-text,
        [data-theme="dark"] .voucher-form__muted {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .voucher-form .btn-outline-secondary {
            color: #cbd5e1;
            border-color: #475569;
        }

        [data-theme="dark"] .voucher-form .btn-outline-secondary:hover {
            color: #fff;
            border-color: #64748b;
            background: #26364d;
        }

        [hidden] {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <main class="container-fluid py-4 voucher-form">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge rounded-pill text-bg-primary">Voucher Shop</span>

                    @if ($isEditing)
                        <span class="voucher-form__muted small">
                            Mã: {{ $voucher->code }}
                        </span>
                    @endif
                </div>

                <h1 class="h3 fw-bold mb-1">
                    {{ $isEditing ? 'Sửa voucher' : 'Tạo voucher mới' }}
                </h1>

                <p class="voucher-form__muted mb-0">
                    Thiết lập mức ưu đãi, điều kiện và thời gian áp dụng cho khách hàng.
                </p>
            </div>

            <a href="{{ route('seller.vouchers.index') }}" class="btn btn-outline-secondary">
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

        <form method="POST"
            action="{{ $isEditing ? route('seller.vouchers.update', $voucher->voucher_id) : route('seller.vouchers.store') }}">
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
                                Đặt mã và chọn hình thức giảm giá.
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-lg-6">
                            <label for="code" class="form-label fw-semibold">
                                Mã voucher <span class="text-danger">*</span>
                            </label>
                            <input id="code" name="code" value="{{ old('code', $voucher->code) }}" maxlength="30"
                                class="form-control @error('code') is-invalid @enderror" placeholder="Ví dụ: PHUCSTORE10"
                                autocomplete="off" required>
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
                            <select id="discount_type" name="discount_type"
                                class="form-select @error('discount_type') is-invalid @enderror" required>
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
                                <input id="discount_value" name="discount_value" type="number" min="0.01"
                                    step="0.01" value="{{ old('discount_value', $voucher->discount_value) }}"
                                    class="form-control @error('discount_value') is-invalid @enderror"
                                    placeholder="{{ $discountType === 'percent' ? 'Ví dụ: 10' : 'Ví dụ: 50000' }}" required>
                                <span class="input-group-text" id="discountUnit">
                                    {{ $discountType === 'percent' ? '%' : '₫' }}
                                </span>
                            </div>
                            <div class="form-text" id="discountHelp">
                                {{ $discountType === 'percent' ? 'Nhập 10 tương ứng với mức giảm 10%.' : 'Nhập số tiền giảm tính bằng đồng.' }}
                            </div>
                            @error('discount_value')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6" id="maxDiscountField" @if ($discountType !== 'percent') hidden @endif>
                            <label for="max_discount" class="form-label fw-semibold">
                                Số tiền giảm tối đa
                                <span class="text-danger" id="maxDiscountRequiredMark">*</span>
                            </label>
                            <div class="input-group">
                                <input id="max_discount" name="max_discount" type="number" min="1" step="1"
                                    value="{{ old('max_discount', $voucher->max_discount) }}"
                                    class="form-control @error('max_discount') is-invalid @enderror"
                                    placeholder="Ví dụ: 100000" @required($discountType === 'percent')>
                                <span class="input-group-text">₫</span>
                            </div>
                            <div class="form-text">
                                Giới hạn số tiền giảm khi voucher áp dụng theo phần trăm.
                            </div>
                            @error('max_discount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
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
                            <h2 class="h5 fw-bold mb-1">Điều kiện sử dụng</h2>
                            <p class="voucher-form__muted small mb-0">
                                Đặt giá trị đơn tối thiểu và tổng lượt sử dụng.
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="min_order_amount" class="form-label fw-semibold">
                                Giá trị đơn hàng tối thiểu <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input id="min_order_amount" name="min_order_amount" type="number" min="0"
                                    step="1000" value="{{ old('min_order_amount', $voucher->min_order_amount ?? 0) }}"
                                    class="form-control @error('min_order_amount') is-invalid @enderror"
                                    placeholder="Nhập 0 nếu không yêu cầu" required>
                                <span class="input-group-text">₫</span>
                            </div>
                            <div class="form-text">
                                Khách cần đạt giá trị này mới dùng được voucher.
                            </div>
                            @error('min_order_amount')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="usage_limit" class="form-label fw-semibold">
                                Tổng lượt sử dụng
                            </label>
                            <input id="usage_limit" name="usage_limit" type="number" min="1" step="1"
                                value="{{ old('usage_limit', $voucher->usage_limit) }}"
                                class="form-control @error('usage_limit') is-invalid @enderror"
                                placeholder="Để trống nếu không giới hạn">
                            <div class="form-text">
                                Đây là tổng lượt dùng của tất cả khách hàng.
                            </div>
                            @error('usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- Thời hạn và trạng thái --}}
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
                        <div class="col-12 col-lg-4">
                            <label for="starts_at" class="form-label fw-semibold">
                                Thời gian bắt đầu <span class="text-danger">*</span>
                            </label>

                            <input id="starts_at" name="starts_at" type="datetime-local" value="{{ $startsAt }}"
                                class="form-control @error('starts_at') is-invalid @enderror" required>

                            <div class="form-text">Chọn ngày và giờ bắt đầu áp dụng.</div>

                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-lg-4">
                            <label for="expires_at" class="form-label fw-semibold">
                                Thời gian kết thúc <span class="text-danger">*</span>
                            </label>

                            <input id="expires_at" name="expires_at" type="datetime-local" value="{{ $expiresAt }}"
                                class="form-control @error('expires_at') is-invalid @enderror" required>

                            <div class="form-text">Phải sau thời gian bắt đầu.</div>

                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-lg-4">
                            <label for="status" class="form-label fw-semibold">
                                Trạng thái <span class="text-danger">*</span>
                            </label>

                            <select id="status" name="status"
                                class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" @selected($voucherStatus === 'active')>
                                    Đang hoạt động
                                </option>
                                <option value="paused" @selected($voucherStatus === 'paused')>
                                    Tạm dừng
                                </option>
                            </select>

                            <div class="form-text">Có thể tạm dừng voucher sau khi tạo.</div>

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
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const type = document.getElementById('discount_type');
            const valueInput = document.getElementById('discount_value');
            const maxField = document.getElementById('maxDiscountField');
            const maxInput = document.getElementById('max_discount');
            const requiredMark = document.getElementById('maxDiscountRequiredMark');
            const unit = document.getElementById('discountUnit');
            const help = document.getElementById('discountHelp');

            if (!type || !valueInput || !maxField || !maxInput || !unit || !help) {
                return;
            }

            function updateDiscountFields() {
                const isPercent = type.value === 'percent';

                maxField.hidden = !isPercent;
                maxInput.required = isPercent;
                requiredMark.hidden = !isPercent;
                unit.textContent = isPercent ? '%' : '₫';
                valueInput.max = isPercent ? '100' : '';
                valueInput.step = isPercent ? '0.01' : '1000';
                valueInput.placeholder = isPercent ? 'Ví dụ: 10' : 'Ví dụ: 50000';
                help.textContent = isPercent ?
                    'Nhập 10 tương ứng với mức giảm 10%.' :
                    'Nhập số tiền giảm tính bằng đồng.';
            }

            type.addEventListener('change', updateDiscountFields);
            updateDiscountFields();
        });
    </script>
@endpush
