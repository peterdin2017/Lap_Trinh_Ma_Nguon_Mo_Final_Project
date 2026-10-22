@extends('auth.steps.layout')

@section('title', 'Tạo tài khoản người bán')
@section('step', 'BƯỚC 2 / 3')
@section('step_label', 'Tạo tài khoản')
@section('step_number', 2)
@section('step_total', 3)
@section('step_percent', '66.67%')

@section('content')
    <div class="text-center mb-4">
        <div class="text-primary mb-3" aria-hidden="true">
            <i class="bi bi-shop-window fs-1"></i>
        </div>

        <h1 class="h3 fw-bold text-primary mb-2">Tạo tài khoản người bán</h1>
        <p class="text-secondary mb-0">
            Hoàn thiện thông tin shop và tạo mật khẩu để bắt đầu bán hàng.
        </p>
    </div>

    <div class="alert alert-primary d-flex align-items-center gap-3 mb-4" role="status">
        <i class="bi bi-envelope-check fs-4" aria-hidden="true"></i>
        <div class="small">
            Email đã xác minh
            <strong class="d-block text-break">{{ $email }}</strong>
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

    <form method="POST" action="{{ route('seller.register.credentials.submit') }}">
        @csrf

        <div class="mb-3">
            <label for="shop_name" class="form-label fw-semibold">Tên shop</label>
            <div class="input-group input-group-lg auth-input-group">
                <span class="input-group-text" aria-hidden="true">
                    <i class="bi bi-shop"></i>
                </span>
                <input
                    id="shop_name"
                    name="shop_name"
                    type="text"
                    class="form-control @error('shop_name') is-invalid @enderror"
                    value="{{ old('shop_name') }}"
                    maxlength="100"
                    autocomplete="organization"
                    autofocus
                    required
                >
                @error('shop_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Mật khẩu mới</label>
            <div class="input-group input-group-lg auth-input-group">
                <span class="input-group-text" aria-hidden="true">
                    <i class="bi bi-lock"></i>
                </span>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    autocomplete="new-password"
                    required
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">
                Xác nhận mật khẩu mới
            </label>
            <div class="input-group input-group-lg auth-input-group">
                <span class="input-group-text" aria-hidden="true">
                    <i class="bi bi-shield-lock"></i>
                </span>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="form-control"
                    autocomplete="new-password"
                    required
                >
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">
            Tiếp tục
            <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
        </button>
    </form>
@endsection

@push('styles')
    <style>
        .auth-input-group .input-group-text,
        .auth-input-group .form-control {
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

        .auth-input-group .form-control::placeholder {
            color: #94A3B8;
        }
    </style>
@endpush
