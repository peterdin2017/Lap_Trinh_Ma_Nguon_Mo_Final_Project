@extends('auth.steps.layout')

@section('title', 'Tạo tài khoản - Website Bán hàng Online')
@section('step', 'BƯỚC 2 / 3')
@section('step_label', 'Hoàn tất thông tin')
@section('step_number', 2)
@section('step_total', 3)
@section('step_percent', '66.67%')

@section('content')
    <div class="text-center mb-4">
        <div class="text-primary mb-3" aria-hidden="true">
            <i class="bi bi-person-vcard fs-1"></i>
        </div>

        <h1 class="h3 fw-bold text-primary mb-2">Hoàn tất thông tin</h1>
        <p class="text-secondary mb-0">
            Nhập họ tên và tạo mật khẩu cho tài khoản của bạn.
        </p>
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

    <form method="POST" action="{{ route('register.password.submit') }}">
        @csrf

        <div class="mb-3">
            <label for="full_name" class="form-label fw-semibold">Họ và tên</label>
            <input
                id="full_name"
                type="text"
                name="full_name"
                class="form-control form-control-lg @error('full_name') is-invalid @enderror"
                value="{{ old('full_name') }}"
                placeholder="Nhập họ và tên"
                autocomplete="name"
                required
                autofocus
            >
            @error('full_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Mật khẩu</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror"
                placeholder="Nhập mật khẩu"
                autocomplete="new-password"
                required
            >
            <div class="form-text">
                Mật khẩu cần có chữ hoa, chữ thường, số và ký tự đặc biệt.
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">
                Nhập lại mật khẩu
            </label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="form-control form-control-lg"
                placeholder="Nhập lại mật khẩu"
                autocomplete="new-password"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">Hoàn tất</button>
    </form>
@endsection
