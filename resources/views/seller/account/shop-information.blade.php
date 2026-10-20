@extends('seller.layouts.app')

@section('title', 'Thông tin Shop')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h2 class="h4 fw-bold mb-1">Thông tin Shop</h2>
            <p class="text-secondary border-bottom pb-3 mb-4">
                Cập nhật tên và địa chỉ cửa hàng.
            </p>

            <form method="POST" action="{{ route('seller.shop-information.update') }}">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <label class="col-md-3 col-form-label" for="email">
                        Email đăng nhập
                    </label>
                    <div class="col-md-8">
                        <input id="email"
                               type="email"
                               class="form-control"
                               value="{{ $account->email }}"
                               readonly>
                        <div class="form-text">
                            Email đăng nhập không thể chỉnh sửa tại trang này.
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-3 col-form-label" for="shop_name">
                        Tên Shop <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-8">
                        <input id="shop_name"
                               name="shop_name"
                               type="text"
                               maxlength="100"
                               value="{{ old('shop_name', $seller->shop_name) }}"
                               class="form-control @error('shop_name') is-invalid @enderror"
                               required>
                        @error('shop_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-3 col-form-label" for="shop_address">
                        Địa chỉ cụ thể
                    </label>
                    <div class="col-md-8">
                        <input id="shop_address"
                               name="shop_address"
                               type="text"
                               maxlength="255"
                               value="{{ old('shop_address', $seller->shop_address) }}"
                               class="form-control @error('shop_address') is-invalid @enderror">
                        @error('shop_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-3 col-form-label" for="shop_province">
                        Tỉnh/Thành phố
                    </label>
                    <div class="col-md-8">
                        <input id="shop_province"
                               name="shop_province"
                               type="text"
                               maxlength="100"
                               value="{{ old('shop_province', $seller->shop_province) }}"
                               class="form-control @error('shop_province') is-invalid @enderror">
                        @error('shop_province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-md-3 col-form-label" for="shop_district">
                        Quận/Huyện
                    </label>
                    <div class="col-md-8">
                        <input id="shop_district"
                               name="shop_district"
                               type="text"
                               maxlength="100"
                               value="{{ old('shop_district', $seller->shop_district) }}"
                               class="form-control @error('shop_district') is-invalid @enderror">
                        @error('shop_district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-md-3 col-form-label" for="shop_ward">
                        Phường/Xã
                    </label>
                    <div class="col-md-8">
                        <input id="shop_ward"
                               name="shop_ward"
                               type="text"
                               maxlength="100"
                               value="{{ old('shop_ward', $seller->shop_ward) }}"
                               class="form-control @error('shop_ward') is-invalid @enderror">
                        @error('shop_ward')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="offset-md-3 col-md-8">
                        <button type="submit" class="btn btn-primary">
                            Lưu thông tin
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
