@extends('layouts.app')

@section('title', 'Hồ Sơ Của Tôi')

@section('content')
    <div class="container py-4">
        <div class="row">

            {{-- Sidebar bên trái, giống Shopee --}}
            <div class="col-md-3 mb-4">
                <div class="text-center mb-4">
                    @if ($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle mb-2"
                            style="width: 70px; height: 70px; object-fit: cover;">
                    @else
                        <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2"
                            style="width: 70px; height: 70px; background-color: #003B73; color: white; font-size: 1.8rem;">
                            {{ strtoupper(substr($user->full_name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="fw-semibold">{{ $user->full_name }}</div>
                </div>

                <div class="list-group">
                    <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action active"
                        style="background-color: #003B73; border-color: #003B73;">
                        Hồ Sơ
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">Đổi Mật Khẩu</a>
                    <a href="#" class="list-group-item list-group-item-action">Địa Chỉ</a>
                </div>
            </div>

            {{-- Nội dung chính --}}
            <div class="col-md-9">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-1" style="color: #003B73;">Hồ Sơ Của Tôi</h4>
                        <p class="text-muted mb-4">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" value="{{ $account->email }}" disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Họ và tên</label>
                                <div class="col-sm-6">
                                    <input type="text" name="full_name"
                                        class="form-control @error('full_name') is-invalid @enderror"
                                        value="{{ old('full_name', $user->full_name) }}">
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Số điện thoại</label>
                                <div class="col-sm-6">
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone) }}" maxlength="10" inputmode="numeric"
                                        placeholder="Ví dụ: 0912345678">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Giới tính</label>
                                <div class="col-sm-6 d-flex align-items-center gap-4">
                                    <div class="form-check">
                                        <input type="radio" name="gender" value="male" class="form-check-input"
                                            id="genderMale" {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="genderMale">Nam</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="gender" value="female" class="form-check-input"
                                            id="genderFemale"
                                            {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="genderFemale">Nữ</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="gender" value="other" class="form-check-input"
                                            id="genderOther"
                                            {{ old('gender', $user->gender) == 'other' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="genderOther">Khác</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label class="col-sm-3 col-form-label">Ngày sinh</label>

                                <div class="col-sm-6">
                                    <input type="date" name="birthdate"
                                        class="form-control @error('birthdate') is-invalid @enderror"
                                        value="{{ old('birthdate', $user->birthdate) }}"
                                        min="{{ now()->subYears(100)->format('Y-m-d') }}"
                                        max="{{ now()->subYears(13)->format('Y-m-d') }}">

                                    @error('birthdate')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                    <div class="form-text">
                                        Người dùng phải từ 13 tuổi trở lên.
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label class="col-sm-3 col-form-label">Ảnh đại diện</label>
                                <div class="col-sm-6">
                                    <input type="file" name="avatar"
                                        class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Dung lượng file tối đa 1 MB. Định dạng: .JPEG, .PNG</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn text-white fw-semibold px-4"
                                        style="background-color: #003B73;">
                                        Lưu
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
