@extends('auth.steps.layout')

@section('title', 'Thông tin cửa hàng')
@section('step', 'BƯỚC 3 / 3')
@section('step_label', 'Thông tin cửa hàng')
@section('step_number', 3)
@section('step_total', 3)
@section('step_percent', '100%')

@section('content')
    <div class="text-center mb-4">
        <div class="text-primary mb-3" aria-hidden="true">
            <i class="bi bi-shop-window fs-1"></i>
        </div>

        <h1 class="h3 fw-bold text-primary mb-2">Thông tin cửa hàng</h1>
        <p class="text-secondary mb-0">
            Kiểm tra thông tin shop rồi gửi hồ sơ để quản trị viên xét duyệt.
        </p>
    </div>

    <div class="alert alert-primary border-0 rounded-3 p-3 mb-4" role="status">
        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-info-circle fs-5 mt-1" aria-hidden="true"></i>
            <div class="small">
                <div class="mb-1">
                    <span class="text-secondary">Tên shop:</span>
                    <strong class="text-dark">{{ $shopName }}</strong>
                </div>

                @isset($email)
                    <div>
                        <span class="text-secondary">Email người bán:</span>
                        <strong class="text-dark text-break">{{ $email }}</strong>
                    </div>
                @endisset
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('seller.register.shop.submit') }}">
        @csrf

        <div class="mb-3">
            <label for="shop_address" class="form-label fw-semibold">Địa chỉ shop</label>
            <div class="input-group input-group-lg auth-input-group">
                <span class="input-group-text" aria-hidden="true">
                    <i class="bi bi-geo-alt"></i>
                </span>
                <input
                    id="shop_address"
                    name="shop_address"
                    type="text"
                    class="form-control @error('shop_address') is-invalid @enderror"
                    value="{{ old('shop_address') }}"
                    maxlength="255"
                    autocomplete="street-address"
                >
                @error('shop_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="shop_province" class="form-label fw-semibold">Tỉnh / Thành phố</label>
            <div class="input-group input-group-lg auth-input-group">
                <span class="input-group-text" aria-hidden="true">
                    <i class="bi bi-map"></i>
                </span>
                <input
                    id="shop_province"
                    name="shop_province"
                    type="text"
                    class="form-control @error('shop_province') is-invalid @enderror"
                    value="{{ old('shop_province') }}"
                    maxlength="100"
                    autocomplete="address-level1"
                >
                @error('shop_province')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="shop_district" class="form-label fw-semibold">Quận / Huyện</label>
                <div class="input-group input-group-lg auth-input-group">
                    <span class="input-group-text" aria-hidden="true">
                        <i class="bi bi-signpost-2"></i>
                    </span>
                    <input
                        id="shop_district"
                        name="shop_district"
                        type="text"
                        class="form-control @error('shop_district') is-invalid @enderror"
                        value="{{ old('shop_district') }}"
                        maxlength="100"
                        autocomplete="address-level2"
                    >
                    @error('shop_district')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-12 col-md-6">
                <label for="shop_ward" class="form-label fw-semibold">Phường / Xã</label>
                <div class="input-group input-group-lg auth-input-group">
                    <span class="input-group-text" aria-hidden="true">
                        <i class="bi bi-geo"></i>
                    </span>
                    <input
                        id="shop_ward"
                        name="shop_ward"
                        type="text"
                        class="form-control @error('shop_ward') is-invalid @enderror"
                        value="{{ old('shop_ward') }}"
                        maxlength="100"
                        autocomplete="address-level3"
                    >
                    @error('shop_ward')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="alert alert-info d-flex align-items-start gap-2 small mt-4 mb-3" role="note">
            <i class="bi bi-clock-history mt-1" aria-hidden="true"></i>
            <span>
                Sau khi gửi, hồ sơ người bán sẽ ở trạng thái chờ duyệt.
                Bạn có thể sử dụng Kênh Người Bán sau khi được duyệt.
            </span>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">
            Gửi hồ sơ đăng ký
            <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
        </button>
    </form>
@endsection

@push('styles')
    <style>
        .auth-input-group .input-group-text,
        .auth-input-group .form-control {
            min-height: 48px;
            border-color: #E2E8F0;
            background-color: #fff;
        }

        .auth-input-group .input-group-text {
            color: #64748B;
        }

        .auth-input-group:focus-within .input-group-text,
        .auth-input-group:focus-within .form-control {
            border-color: #1A94FF;
        }

        .auth-input-group:focus-within .input-group-text {
            box-shadow: 4px 0 0 0 rgba(26, 148, 255, .12);
        }

        .auth-input-group .form-control:focus {
            box-shadow: 0 0 0 4px rgba(26, 148, 255, .12);
        }
    </style>
@endpush
