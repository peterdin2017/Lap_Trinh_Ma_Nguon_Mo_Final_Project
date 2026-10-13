@extends('auth.steps.layout')

@section('title', 'Quên mật khẩu')
@section('step', 'BƯỚC 1 / 4')
@section('step_label', 'Nhập email')
@section('step_number', 1)
@section('step_total', 4)
@section('step_percent', '25%')

@section('content')
    <h1 class="h2 fw-bold text-primary mb-2">Quên mật khẩu?</h1>

    <p class="text-secondary mb-4">
        Nhập email tài khoản. Chúng tôi sẽ gửi mã OTP để xác minh.
    </p>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('forgot-password.send-otp') }}">
        @csrf

        <input
            type="hidden"
            name="portal"
            value="{{ old('portal', $portal ?? request()->query('portal', 'store')) }}"
        >

        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                class="form-control form-control-lg @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                placeholder="Nhập email tài khoản"
                autocomplete="email"
                required
                autofocus
            >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">Gửi mã OTP</button>
    </form>
@endsection
