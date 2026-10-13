@extends('auth.steps.layout')

@section('title', 'Xác minh email')
@section('step', 'BƯỚC 1 / 3')
@section('step_label', 'Xác minh email')
@section('step_number', 1)
@section('step_total', 3)
@section('step_percent', '33.33%')

@section('content')
    @php
        $isSeller = session('register_role') === 'seller'
            || request()->routeIs('seller.register.otp.*')
            || request()->query('role') === 'seller';

        $verifyRoute = $isSeller ? 'seller.register.otp.verify' : 'otp.verify';
        $resendRoute = $isSeller ? 'seller.register.otp.resend' : 'otp.resend';
        $registerRoute = $isSeller ? 'seller.register.form' : 'register.form';
    @endphp

    <div class="text-center mb-4">
        <div class="text-primary mb-3" aria-hidden="true">
            <i class="bi bi-envelope-check fs-1"></i>
        </div>

        <h1 class="h3 fw-bold text-primary mb-2">Xác minh email</h1>

        <p class="text-secondary mb-0">
            Nhập mã xác nhận gồm 6 chữ số chúng tôi đã gửi cho bạn.
        </p>
    </div>

    <div class="alert alert-primary d-flex align-items-center gap-3 mb-4" role="status">
        <i class="bi bi-envelope fs-4" aria-hidden="true"></i>
        <div class="small">
            Mã OTP được gửi đến
            <strong class="d-block text-break">{{ $email }}</strong>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif

    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route($verifyRoute) }}" id="otpForm">
        @csrf

        <input type="hidden" name="email" value="{{ $email }}">
        <input type="hidden" name="otp_code" id="otpValue">

        <label for="otpInput0" class="form-label fw-semibold">Mã OTP</label>

        <div class="d-flex justify-content-center gap-2 mb-4" id="otpInputs">
            @for ($i = 0; $i < 6; $i++)
                <input
                    id="otpInput{{ $i }}"
                    type="text"
                    class="form-control form-control-lg text-center fs-3 px-0"
                    style="width: 3.25rem; height: 3.75rem;"
                    inputmode="numeric"
                    maxlength="1"
                    pattern="[0-9]"
                    aria-label="Số OTP thứ {{ $i + 1 }}"
                    autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                    required
                >
            @endfor
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">Xác minh</button>
    </form>

    <form method="POST" action="{{ route($resendRoute) }}" class="text-center mt-3">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <button type="submit" class="btn btn-link text-decoration-none">
            Gửi lại mã OTP
        </button>
    </form>

    <div class="text-center border-top mt-3 pt-3">
        <a href="{{ route($registerRoute) }}"
           class="link-secondary text-decoration-none small">
            <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
            Quay lại nhập email
        </a>
    </div>
@endsection

@push('scripts')
    @include('auth.partials.otp-script')
@endpush
