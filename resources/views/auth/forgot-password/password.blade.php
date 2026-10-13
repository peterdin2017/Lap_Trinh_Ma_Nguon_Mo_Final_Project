@extends('auth.steps.layout')

@section('title', 'Tạo mật khẩu mới')
@section('step', 'BƯỚC 3 / 4')
@section('step_label', 'Tạo mật khẩu mới')
@section('step_number', 3)
@section('step_total', 4)
@section('step_percent', '75%')

@section('content')
    <div class="text-center mb-4">
        <div class="text-primary mb-3" aria-hidden="true">
            <i class="bi bi-shield-lock fs-1"></i>
        </div>

        <h1 class="h3 fw-bold text-primary mb-2">Tạo mật khẩu mới</h1>
        <p class="text-secondary mb-0">
            Nhập mật khẩu mới để bảo vệ tài khoản của bạn.
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

    <form method="POST" action="{{ route('forgot-password.password.submit') }}">
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Mật khẩu mới</label>
            <input
                id="password"
                name="password"
                type="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror"
                autocomplete="new-password"
                placeholder="Nhập mật khẩu mới"
                required
                autofocus
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">
                Xác nhận mật khẩu mới
            </label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                class="form-control form-control-lg"
                autocomplete="new-password"
                placeholder="Nhập lại mật khẩu mới"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">
            Đặt lại mật khẩu
        </button>
    </form>
@endsection
