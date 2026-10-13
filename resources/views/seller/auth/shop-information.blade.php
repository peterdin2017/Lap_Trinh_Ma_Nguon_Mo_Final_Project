@extends('auth.steps.layout')

@section('title', 'Thông tin cửa hàng')
@section('step', 'BƯỚC 3 / 3')
@section('step_label', 'Thông tin cửa hàng')
@section('step_number', 3)
@section('step_total', 3)
@section('step_percent', '100%')

@section('content')
    <h1 class="h2 fw-bold text-primary mb-2">Thông tin cửa hàng</h1>

    <p class="text-secondary fs-5 mb-4">
        Kiểm tra thông tin shop rồi gửi hồ sơ để quản trị viên xét duyệt.
    </p>

    <div class="alert alert-light border mb-4">
        <div class="mb-1">
            <span class="text-secondary">Tên shop:</span>
            <strong>{{ $shopName }}</strong>
        </div>

        @isset($email)
            <div>
                <span class="text-secondary">Email người bán:</span>
                <strong class="text-break">{{ $email }}</strong>
            </div>
        @endisset
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
            <label for="shop_address" class="form-label">Địa chỉ shop</label>
            <input
                id="shop_address"
                name="shop_address"
                type="text"
                class="form-control form-control-lg @error('shop_address') is-invalid @enderror"
                value="{{ old('shop_address') }}"
                maxlength="255"
                autocomplete="street-address"
            >
            @error('shop_address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="shop_province" class="form-label">Tỉnh / Thành phố</label>
            <input
                id="shop_province"
                name="shop_province"
                type="text"
                class="form-control form-control-lg @error('shop_province') is-invalid @enderror"
                value="{{ old('shop_province') }}"
                maxlength="100"
                autocomplete="address-level1"
            >
            @error('shop_province')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-12 col-md-6 mb-3">
                <label for="shop_district" class="form-label">Quận / Huyện</label>
                <input
                    id="shop_district"
                    name="shop_district"
                    type="text"
                    class="form-control form-control-lg @error('shop_district') is-invalid @enderror"
                    value="{{ old('shop_district') }}"
                    maxlength="100"
                    autocomplete="address-level2"
                >
                @error('shop_district')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6 mb-3">
                <label for="shop_ward" class="form-label">Phường / Xã</label>
                <input
                    id="shop_ward"
                    name="shop_ward"
                    type="text"
                    class="form-control form-control-lg @error('shop_ward') is-invalid @enderror"
                    value="{{ old('shop_ward') }}"
                    maxlength="100"
                    autocomplete="address-level3"
                >
                @error('shop_ward')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="alert alert-info small mt-2">
            Sau khi gửi, hồ sơ người bán sẽ ở trạng thái chờ duyệt.
            Bạn có thể sử dụng Kênh Người Bán sau khi được duyệt.
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 mt-2">
            Gửi hồ sơ đăng ký
        </button>
    </form>
@endsection
