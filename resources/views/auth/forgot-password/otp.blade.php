@extends('auth.steps.layout')

@section('title', 'Xác minh OTP đặt lại mật khẩu')
@section('step', 'BƯỚC 2 / 4')
@section('step_label', 'Xác minh OTP')
@section('step_number', 2)
@section('step_total', 4)
@section('step_percent', '50%')

@section('content')
    <style>
        .reset-otp {
            --auth-primary: #1A94FF;
            --auth-primary-hover: #0077E6;
            --auth-primary-light: #EBF5FF;
            --auth-text: #0F172A;
            --auth-muted: #64748B;
            --auth-border: #E2E8F0;
            color: var(--auth-text);
        }

        .reset-otp__icon {
            color: var(--auth-primary);
        }

        .reset-otp__title {
            color: var(--auth-text);
        }

        .reset-otp__description {
            color: var(--auth-muted);
        }

        .reset-otp__notice {
            color: var(--auth-text);
            background: var(--auth-primary-light);
            border: 1px solid #D0E4FF;
            border-radius: 10px;
        }

        .reset-otp__notice-icon {
            color: var(--auth-primary);
        }

        .reset-otp__inputs {
            display: flex;
            justify-content: center;
            gap: .5rem;
        }

        .reset-otp__input {
            width: 100%;
            max-width: 3.25rem;
            height: 3.75rem;
            padding: 0;
            color: var(--auth-text);
            border: 1px solid var(--auth-border);
            border-radius: 10px;
        }

        .reset-otp__input:focus {
            border-color: var(--auth-primary);
            box-shadow: 0 0 0 4px rgba(26, 148, 255, .15);
        }

        .reset-otp .btn-primary {
            min-height: 48px;
            color: #fff;
            font-weight: 600;
            background: var(--auth-primary);
            border-color: var(--auth-primary);
            border-radius: 10px;
        }

        .reset-otp .btn-primary:hover,
        .reset-otp .btn-primary:focus-visible {
            color: #fff;
            background: var(--auth-primary-hover);
            border-color: var(--auth-primary-hover);
        }

        .reset-otp__resend {
            color: var(--auth-primary);
            font-weight: 600;
        }

        .reset-otp__resend:hover {
            color: var(--auth-primary-hover);
        }

        @media (max-width: 380px) {
            .reset-otp__inputs {
                gap: .35rem;
            }

            .reset-otp__input {
                max-width: 2.8rem;
                height: 3.4rem;
                font-size: 1.5rem !important;
            }
        }
    </style>

    <div class="reset-otp">
        <div class="text-center mb-4">
            <div class="reset-otp__icon mb-3" aria-hidden="true">
                <i class="bi bi-envelope-check fs-1"></i>
            </div>

            <h1 class="reset-otp__title h3 fw-bold mb-2">
                Xác minh email
            </h1>

            <p class="reset-otp__description mb-0">
                Nhập mã xác nhận gồm 6 chữ số để tiếp tục đặt lại mật khẩu.
            </p>
        </div>

        <div class="reset-otp__notice d-flex align-items-center gap-3 mb-4 p-3"
             role="status">
            <i class="reset-otp__notice-icon bi bi-envelope fs-4" aria-hidden="true"></i>
            <div class="small">
                Mã OTP đã được gửi đến
                <strong class="d-block text-break">{{ $email }}</strong>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="status">
                {{ session('success') }}
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

        <form method="POST" action="{{ route('forgot-password.otp.verify') }}" id="otpForm">
            @csrf

            <input type="hidden" name="otp_code" id="otpValue">

            <label for="otpInput0" class="form-label fw-semibold">Mã OTP</label>

            <div class="reset-otp__inputs mb-4" id="otpInputs">
                @for ($i = 0; $i < 6; $i++)
                    <input
                        id="otpInput{{ $i }}"
                        type="text"
                        class="form-control reset-otp__input text-center fs-3"
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

        <form method="POST"
              action="{{ route('forgot-password.otp.resend') }}"
              class="text-center mt-3">
            @csrf

            <button type="submit"
                    class="btn btn-link reset-otp__resend text-decoration-none">
                Gửi lại mã OTP
            </button>
        </form>
    </div>
@endsection

@push('scripts')
    @include('auth.partials.otp-script')
@endpush
