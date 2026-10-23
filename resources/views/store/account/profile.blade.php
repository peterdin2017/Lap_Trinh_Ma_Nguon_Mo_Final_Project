@extends('store.account.layouts.account')

@section('title', 'Hồ sơ của tôi')

@section('account_content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h1 class="h4 fw-bold mb-1">Hồ sơ của tôi</h1>
            <p class="text-secondary border-bottom pb-3 mb-4">
                Quản lý thông tin cá nhân của bạn.
            </p>

            <form
                method="POST"
                action="{{ route('store.account.profile.update') }}"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <label for="account-email" class="col-md-3 col-form-label">
                        Email đăng nhập
                    </label>
                    <div class="col-md-7">
                        <input
                            id="account-email"
                            type="email"
                            class="form-control"
                            value="{{ $account->email }}"
                            autocomplete="email"
                            readonly
                        >
                        <div class="form-text">
                            Email đăng nhập chưa thể thay đổi trực tiếp.
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="avatar" class="col-md-3 col-form-label">
                        Ảnh đại diện
                    </label>
                    <div class="col-md-7">
                        @if ($profile->avatar)
                            <div class="mb-2">
                                <img
                                    src="{{ asset('storage/' . $profile->avatar) }}"
                                    alt="Ảnh đại diện hiện tại"
                                    class="rounded-circle"
                                    width="80"
                                    height="80"
                                    style="object-fit: cover;"
                                >
                            </div>
                        @endif

                        <input
                            id="avatar"
                            name="avatar"
                            type="file"
                            accept="image/jpeg,image/png,image/webp"
                            class="form-control @error('avatar') is-invalid @enderror"
                            aria-describedby="avatar-help"
                        >

                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div id="avatar-help" class="form-text">
                            JPG, PNG hoặc WEBP; dung lượng tối đa 2 MB.
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="full_name" class="col-md-3 col-form-label">
                        Tên hiển thị <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-7">
                        <input
                            id="full_name"
                            name="full_name"
                            type="text"
                            class="form-control @error('full_name') is-invalid @enderror"
                            value="{{ old('full_name', $profile->full_name) }}"
                            maxlength="100"
                            autocomplete="name"
                            required
                        >

                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="phone" class="col-md-3 col-form-label">
                        Số điện thoại
                    </label>
                    <div class="col-md-7">
                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $profile->phone) }}"
                            maxlength="15"
                            autocomplete="tel"
                            aria-describedby="phone-help"
                        >

                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <div id="phone-help" class="form-text">
                            Bạn có thể cập nhật số điện thoại trực tiếp. Số mới chưa được xác minh qua SMS.
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="gender" class="col-md-3 col-form-label">
                        Giới tính
                    </label>
                    <div class="col-md-7">
                        <select
                            id="gender"
                            name="gender"
                            class="form-select @error('gender') is-invalid @enderror"
                        >
                            <option value="">Chưa chọn</option>
                            <option value="male" @selected(old('gender', $profile->gender) === 'male')>
                                Nam
                            </option>
                            <option value="female" @selected(old('gender', $profile->gender) === 'female')>
                                Nữ
                            </option>
                            <option value="other" @selected(old('gender', $profile->gender) === 'other')>
                                Khác
                            </option>
                        </select>

                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="birthdate" class="col-md-3 col-form-label">
                        Ngày sinh
                    </label>
                    <div class="col-md-7">
                        <input
                            id="birthdate"
                            name="birthdate"
                            type="date"
                            class="form-control @error('birthdate') is-invalid @enderror"
                            value="{{ old('birthdate', $profile->birthdate?->format('Y-m-d')) }}"
                        >

                        @error('birthdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="offset-md-3 col-md-7">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2 me-1" aria-hidden="true"></i>
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
