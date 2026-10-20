@extends('auth.steps.layout')

@section('title', 'Đặt lại mật khẩu thành công')
@section('step', 'BƯỚC 4 / 4')
@section('step_label', 'Hoàn tất')
@section('step_number', 4)
@section('step_total', 4)
@section('step_percent', '100%')

@section('content')
    <style>
        .reset-password-success {
            --auth-primary: #1A94FF;
            --auth-primary-hover: #0077E6;
            --auth-primary-light: #EBF5FF;
            --auth-text: #0F172A;
            --auth-muted: #64748B;
        }

        .reset-password-success__icon {
            display: inline-flex;
            width: 76px;
            height: 76px;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: var(--auth-primary);
            font-size: 2.4rem;
            background: var(--auth-primary-light);
            border-radius: 50%;
        }

        .reset-password-success__title {
            color: var(--auth-text);
        }

        .reset-password-success__description {
            color: var(--auth-muted);
            line-height: 1.6;
        }

        .reset-password-success__button {
            min-height: 48px;
            color: #fff;
            font-weight: 600;
            background: var(--auth-primary);
            border: 1px solid var(--auth-primary);
            border-radius: 10px;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .reset-password-success__button:hover,
        .reset-password-success__button:focus-visible {
            color: #fff;
            background: var(--auth-primary-hover);
            border-color: var(--auth-primary-hover);
        }

        @media (prefers-reduced-motion: reduce) {
            .reset-password-success__button {
                transition: none;
            }
        }
    </style>

    <div class="reset-password-success text-center">
        <div class="reset-password-success__icon" aria-hidden="true">
            <i class="bi bi-check-lg"></i>
        </div>

        <h1 class="reset-password-success__title h3 fw-bold mb-2">
            Đặt lại mật khẩu thành công
        </h1>

        <p class="reset-password-success__description mb-4">
            Bạn có thể đăng nhập bằng mật khẩu mới.
        </p>

        <a href="{{ route($loginRoute) }}"
           class="btn reset-password-success__button btn-lg w-100">
            Quay về đăng nhập
            <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
        </a>
    </div>
@endsection
