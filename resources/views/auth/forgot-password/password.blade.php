@extends('auth.steps.layout')

@section('title', 'Tạo mật khẩu mới')
@section('step', 'BƯỚC 3 / 4')
@section('step_label', 'Tạo mật khẩu mới')
@section('step_number', 3)
@section('step_total', 4)
@section('step_percent', '75%')

@section('content')
    <style>
        .reset-password-step {
            --auth-primary: #1A94FF;
            --auth-primary-hover: #0077E6;
            --auth-primary-light: #EBF5FF;
            --auth-text: #0F172A;
            --auth-muted: #64748B;
            --auth-border: #E2E8F0;
            color: var(--auth-text);
        }

        .reset-password-step__icon {
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

        .reset-password-step__title {
            color: var(--auth-text);
        }

        .reset-password-step__description,
        .reset-password-step .form-text {
            color: var(--auth-muted) !important;
        }

        .reset-password-step .form-label {
            color: var(--auth-text);
            font-size: .9rem;
            font-weight: 600;
        }

        .reset-password-step__input-group {
            min-height: 48px;
            overflow: hidden;
            border: 1px solid var(--auth-border);
            border-radius: 10px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .reset-password-step__input-group:focus-within {
            border-color: var(--auth-primary);
            box-shadow: 0 0 0 4px rgba(26, 148, 255, .15);
        }

        .reset-password-step__input-group .input-group-text {
            min-width: 48px;
            justify-content: center;
            color: var(--auth-muted);
            background: #fff;
            border: 0;
        }

        .reset-password-step__input-group .form-control {
            min-height: 46px;
            color: var(--auth-text);
            background: #fff;
            border: 0;
            box-shadow: none;
        }

        .reset-password-step__input-group .form-control:focus {
            box-shadow: none;
        }

        .reset-password-step__input-group .form-control::placeholder {
            color: #94A3B8;
        }

        .reset-password-step__submit {
            min-height: 48px;
            color: #fff;
            font-weight: 600;
            background: var(--auth-primary);
            border: 1px solid var(--auth-primary);
            border-radius: 10px;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .reset-password-step__submit:hover,
        .reset-password-step__submit:focus-visible {
            color: #fff;
            background: var(--auth-primary-hover);
            border-color: var(--auth-primary-hover);
        }

        @media (prefers-reduced-motion: reduce) {
            .reset-password-step__input-group,
            .reset-password-step__submit {
                transition: none;
            }
        }
    </style>

    <div class="reset-password-step">
        <div class="text-center mb-4">
            <div class="reset-password-step__icon" aria-hidden="true">
                <i class="bi bi-shield-lock"></i>
            </div>

            <h1 class="reset-password-step__title h3 fw-bold mb-2">
                Tạo mật khẩu mới
            </h1>

            <p class="reset-password-step__description mb-0">
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
                <label for="password" class="form-label">Mật khẩu mới</label>

                <div class="input-group reset-password-step__input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock" aria-hidden="true"></i>
                    </span>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        autocomplete="new-password"
                        placeholder="Nhập mật khẩu mới"
                        required
                        autofocus
                    >
                </div>

                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">
                    Xác nhận mật khẩu mới
                </label>

                <div class="input-group reset-password-step__input-group">
                    <span class="input-group-text">
                        <i class="bi bi-shield-check" aria-hidden="true"></i>
                    </span>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="form-control"
                        autocomplete="new-password"
                        placeholder="Nhập lại mật khẩu mới"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn reset-password-step__submit btn-lg w-100">
                Đặt lại mật khẩu
                <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
            </button>
        </form>
    </div>
@endsection
