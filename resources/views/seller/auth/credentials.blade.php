@extends('auth.steps.layout')

@section('title', 'Tạo tài khoản người bán')
@section('step', 'BƯỚC 2 / 3')
@section('step_label', 'Tạo tài khoản')
@section('step_number', 2)
@section('step_total', 3)
@section('step_percent', '66.67%')

@section('content')
    <h1 class="h2 fw-bold text-primary mb-2">Tạo tài khoản người bán</h1>

    <p class="text-secondary fs-5 mb-4">
        Email đã xác minh:
        <strong class="text-dark text-break">{{ $email }}</strong>
    </p>

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
            <label for="shop_name" class="form-label">Tên shop</label>
            <input
                id="shop_name"
                name="shop_name"
                type="text"
                class="form-control form-control-lg @error('shop_name') is-invalid @enderror"
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

        <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu mới</label>
            <input
                id="password"
                name="password"
                type="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror"
                autocomplete="new-password"
                required
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">
                Xác nhận mật khẩu mới
            </label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                class="form-control form-control-lg"
                autocomplete="new-password"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">Tiếp tục</button>
    </form>
@endsection
