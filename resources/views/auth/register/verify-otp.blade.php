@extends('auth.steps.layout')

@section('title', 'Xác minh email')
@section('step', 'BƯỚC 1 / 3')
@section('step_label', 'Xác minh email')
@section('step_number', 1)
@section('step_total', 3)
@section('step_percent', '33.33%')

@section('content')
    @php
        $isSeller =
            session('register_role') === 'seller' ||
            request()->routeIs('seller.register.otp.*') ||
            request()->query('role') === 'seller';

        $verifyRoute = $isSeller ? 'seller.register.otp.verify' : 'otp.verify';
        $resendRoute = $isSeller ? 'seller.register.otp.resend' : 'otp.resend';
        $registerRoute = $isSeller ? 'seller.register.form' : 'register.form';
    @endphp

    <style>
        .otp-verification {
            --otp-primary: #1A94FF;
            --otp-primary-hover: #0077E6;
            --otp-primary-light: #EBF5FF;
            --otp-text: #0F172A;
            --otp-muted: #64748B;
            --otp-border: #E2E8F0;
            color: var(--otp-text);
        }

        .otp-verification__icon {
            color: var(--otp-primary);
        }

        .otp-verification__title {
            color: var(--otp-text);
        }

        .otp-verification__description {
            color: var(--otp-muted);
        }

        .otp-verification__notice {
            color: var(--otp-text);
            background: var(--otp-primary-light);
            border: 1px solid #D0E4FF;
            border-radius: 10px;
        }

        .otp-verification__notice-icon {
            color: var(--otp-primary);
        }

        .otp-verification__inputs {
            display: flex;
            justify-content: center;
            gap: .5rem;
        }

        .otp-verification__input {
            width: 100%;
            max-width: 3.25rem;
            height: 3.75rem;
            padding: 0;
            color: var(--otp-text);
            border: 1px solid var(--otp-border);
            border-radius: 10px;
        }

        .otp-verification__input:focus {
            border-color: var(--otp-primary);
            box-shadow: 0 0 0 4px rgba(26, 148, 255, .15);
        }

        .otp-verification .btn-primary {
            min-height: 48px;
            color: #fff;
            font-weight: 600;
            background: var(--otp-primary);
            border-color: var(--otp-primary);
            border-radius: 10px;
        }

        .otp-verification .btn-primary:hover,
        .otp-verification .btn-primary:focus-visible {
            color: #fff;
            background: var(--otp-primary-hover);
            border-color: var(--otp-primary-hover);
        }

        .otp-verification__resend {
            color: var(--otp-primary);
            font-weight: 600;
        }

        .otp-verification__resend:hover {
            color: var(--otp-primary-hover);
        }

        .otp-verification__back {
            color: var(--otp-muted);
        }

        .otp-verification__back:hover {
            color: var(--otp-primary);
        }

        @media (max-width: 380px) {
            .otp-verification__inputs {
                gap: .35rem;
            }

            .otp-verification__input {
                max-width: 2.8rem;
                height: 3.4rem;
                font-size: 1.5rem !important;
            }
        }
    </style>

    <div class="otp-verification">
        <div class="text-center mb-4">
            <div class="otp-verification__icon mb-3" aria-hidden="true">
                <i class="bi bi-envelope-check fs-1"></i>
            </div>

            <h1 class="otp-verification__title h3 fw-bold mb-2">
                Xác minh email
            </h1>

            <p class="otp-verification__description mb-0">
                Nhập mã xác nhận gồm 6 chữ số chúng tôi đã gửi cho bạn.
            </p>
        </div>

        <div class="otp-verification__notice d-flex align-items-center gap-3 mb-4 p-3"
             role="status">
            <i class="otp-verification__notice-icon bi bi-envelope fs-4"
               aria-hidden="true"></i>
            <div class="small">
                Mã OTP được gửi đến
                <strong class="d-block text-break">{{ $email }}</strong>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success" role="status">
                {{ session('status') }}
            </div>
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

            <label for="otpInput0" class="form-label fw-semibold">
                Mã OTP
            </label>

            <div class="otp-verification__inputs mb-4" id="otpInputs">
                @for ($i = 0; $i < 6; $i++)
                    <input
                        id="otpInput{{ $i }}"
                        type="text"
                        class="form-control otp-verification__input text-center fs-3"
                        inputmode="numeric"
                        maxlength="1"
                        pattern="[0-9]"
                        aria-label="Số OTP thứ {{ $i + 1 }}"
                        autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                        required
                    >
                @endfor
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100">
                Xác minh
            </button>
        </form>

        <form method="POST" action="{{ route($resendRoute) }}" class="text-center mt-3">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">

            <button type="submit"
                    class="btn btn-link otp-verification__resend text-decoration-none">
                Gửi lại mã OTP
            </button>
        </form>

        <div class="text-center border-top mt-3 pt-3">
            <a href="{{ route($registerRoute) }}"
               class="otp-verification__back text-decoration-none small">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>
                Quay lại nhập email
            </a>
        </div>
    </div>
@endsection

@push('scripts')
    @include('auth.partials.otp-script')
@endpush
