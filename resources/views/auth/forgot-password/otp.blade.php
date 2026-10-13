@extends('auth.steps.layout')

@section('title', 'Xác minh OTP đặt lại mật khẩu')
@section('step', 'BƯỚC 2 / 4')
@section('step_label', 'Xác minh OTP')
@section('step_number', 2)
@section('step_total', 4)
@section('step_percent', '50%')

@section('content')
    <div class="text-center mb-4">
        <div class="text-primary mb-3" aria-hidden="true">
            <i class="bi bi-envelope-check fs-1"></i>
        </div>

        <h1 class="h3 fw-bold text-primary mb-2">Xác minh email</h1>

        <p class="text-secondary mb-0">
            Nhập mã xác nhận gồm 6 chữ số để tiếp tục đặt lại mật khẩu.
        </p>
    </div>

    <div class="alert alert-primary d-flex align-items-center gap-3 mb-4">
        <i class="bi bi-envelope fs-4" aria-hidden="true"></i>
        <div class="small">
            Mã OTP đã được gửi đến
            <strong class="d-block text-break">{{ $email }}</strong>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
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

    <form method="POST" action="{{ route('forgot-password.otp.verify') }}" id="otpForm">
        @csrf

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

    <form method="POST" action="{{ route('forgot-password.otp.resend') }}" class="text-center mt-3">
        @csrf

        <button type="submit" class="btn btn-link text-decoration-none">
            Gửi lại mã OTP
        </button>
    </form>
@endsection

@push('scripts')
    @include('auth.partials.otp-script')
@endpush
