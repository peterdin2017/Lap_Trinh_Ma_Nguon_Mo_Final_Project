@extends('store.account.layouts.account')

@php
    $editing = $address->exists;
@endphp

@section('title', $editing ? 'Sửa địa chỉ' : 'Thêm địa chỉ')

@section('account_content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h1 class="h4 fw-bold mb-1">
                {{ $editing ? 'Sửa địa chỉ nhận hàng' : 'Thêm địa chỉ nhận hàng' }}
            </h1>

            <p class="text-secondary border-bottom pb-3 mb-4">
                Nhập thông tin người nhận và địa chỉ giao hàng.
            </p>

            <form
                method="POST"
                action="{{ $editing
                    ? route('store.account.addresses.update', $address->address_id)
                    : route('store.account.addresses.store') }}"
            >
                @csrf

                @if ($editing)
                    @method('PUT')
                @endif

                @foreach ([
                    'recipient_name' => ['Họ tên người nhận', 100, 'text', 'Nhập họ tên người nhận', 'name'],
                    'phone' => ['Số điện thoại', 15, 'tel', '0912345678', 'tel'],
                    'province' => ['Tỉnh/Thành phố', 100, 'text', 'Ví dụ: Thành phố Hồ Chí Minh', 'address-level1'],
                    'district' => ['Quận/Huyện', 100, 'text', 'Ví dụ: Quận 1', 'address-level2'],
                    'ward' => ['Phường/Xã', 100, 'text', 'Ví dụ: Phường Bến Nghé', 'address-level3'],
                    'street_address' => ['Địa chỉ cụ thể', 255, 'text', 'Số nhà, tên đường', 'street-address'],
                ] as $field => [$label, $max, $type, $placeholder, $autocomplete])
                    <div class="row mb-3">
                        <label for="{{ $field }}" class="col-md-3 col-form-label">
                            {{ $label }} <span class="text-danger">*</span>
                        </label>

                        <div class="col-12 col-md-8">
                            <input
                                id="{{ $field }}"
                                name="{{ $field }}"
                                type="{{ $type }}"
                                maxlength="{{ $max }}"
                                value="{{ old($field, $address->{$field}) }}"
                                placeholder="{{ $placeholder }}"
                                autocomplete="{{ $autocomplete }}"
                                @if ($field === 'phone') inputmode="tel" @endif
                                class="form-control @error($field) is-invalid @enderror"
                                required
                            >

                            @error($field)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endforeach

                <div class="row mb-4">
                    <div class="col-12 offset-md-3 col-md-8">
                        <div class="form-check">
                            <input
                                id="is_default"
                                name="is_default"
                                type="checkbox"
                                value="1"
                                class="form-check-input"
                                @checked(old('is_default', $address->is_default))
                            >

                            <label for="is_default" class="form-check-label">
                                Đặt làm địa chỉ mặc định
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 offset-md-3 col-md-8">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                Lưu địa chỉ
                            </button>

                            <a
                                href="{{ route('store.account.addresses.index') }}"
                                class="btn btn-outline-secondary"
                            >
                                Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
