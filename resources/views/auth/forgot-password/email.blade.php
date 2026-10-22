@extends('auth.steps.layout')

@section('title', 'Quên mật khẩu')
@section('step', 'BƯỚC 1 / 4')
@section('step_label', 'Nhập email')
@section('step_number', 1)
@section('step_total', 4)
@section('step_percent', '25%')

@section('content')
    <style>
        .forgot-password-step {
            --auth-primary: #1A94FF;
            --auth-primary-hover: #0077E6;
            --auth-primary-light: #EBF5FF;
            --auth-text: #0F172A;
            --auth-muted: #64748B;
            --auth-border: #E2E8F0;
            color: var(--auth-text);
        }

        .forgot-password-step__icon {
            display: inline-flex;
            width: 56px;
            height: 56px;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: var(--auth-primary);
            font-size: 1.6rem;
            background: var(--auth-primary-light);
            border-radius: 16px;
        }

        .forgot-password-step__title {
            color: var(--auth-text);
        }

        .forgot-password-step__description {
            color: var(--auth-muted);
            line-height: 1.6;
        }

        .forgot-password-step .form-label {
            color: var(--auth-text);
            font-size: .9rem;
            font-weight: 600;
        }

        .forgot-password-step__input-group {
            min-height: 48px;
            overflow: hidden;
            border: 1px solid var(--auth-border);
            border-radius: 10px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .forgot-password-step__input-group:focus-within {
            border-color: var(--auth-primary);
            box-shadow: 0 0 0 4px rgba(26, 148, 255, .15);
        }

        .forgot-password-step__input-group .input-group-text {
            min-width: 48px;
            justify-content: center;
            color: var(--auth-muted);
            background: #fff;
            border: 0;
        }

        .forgot-password-step__input-group .form-control {
            min-height: 46px;
            color: var(--auth-text);
            background: #fff;
            border: 0;
            box-shadow: none;
        }

        .forgot-password-step__input-group .form-control:focus {
            box-shadow: none;
        }

        .forgot-password-step__input-group .form-control::placeholder {
            color: #94A3B8;
        }

        .forgot-password-step__submit {
            min-height: 48px;
            color: #fff;
            font-weight: 600;
            background: var(--auth-primary);
            border: 1px solid var(--auth-primary);
            border-radius: 10px;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .forgot-password-step__submit:hover,
        .forgot-password-step__submit:focus-visible {
            color: #fff;
            background: var(--auth-primary-hover);
            border-color: var(--auth-primary-hover);
        }

        @media (prefers-reduced-motion: reduce) {
            .forgot-password-step__input-group,
            .forgot-password-step__submit {
                transition: none;
            }
        }
    </style>

    <div class="forgot-password-step">
        <div class="forgot-password-step__icon" aria-hidden="true">
            <i class="bi bi-key"></i>
        </div>

        <h1 class="forgot-password-step__title h3 fw-bold mb-2">
            Quên mật khẩu?
        </h1>

        <p class="forgot-password-step__description mb-4">
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

                <div class="input-group forgot-password-step__input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope" aria-hidden="true"></i>
                    </span>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="Nhập email tài khoản"
                        autocomplete="email"
                        required
                        autofocus
                    >
                </div>

                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn forgot-password-step__submit btn-lg w-100">
                Gửi mã OTP
                <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
            </button>
        </form>
    </div>
@endsection
