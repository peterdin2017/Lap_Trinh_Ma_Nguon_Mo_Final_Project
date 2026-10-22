@extends('auth.steps.layout')

@section('title', 'Đăng ký thành công - Website Bán hàng Online')
@section('step', 'BƯỚC 3 / 3')
@section('step_label', 'Hoàn tất')
@section('step_number', 3)
@section('step_total', 3)
@section('step_percent', '100%')

@section('content')
    <style>
        .register-success {
            --auth-primary: #1A94FF;
            --auth-primary-hover: #0077E6;
            --auth-primary-light: #EBF5FF;
            --auth-text: #0F172A;
            --auth-muted: #64748B;
            color: var(--auth-text);
        }

        .register-success__icon {
            display: inline-flex;
            width: 76px;
            height: 76px;
            align-items: center;
            justify-content: center;
            color: var(--auth-primary);
            font-size: 2.5rem;
            background: var(--auth-primary-light);
            border-radius: 50%;
        }

        .register-success__title {
            color: var(--auth-primary);
        }

        .register-success__description {
            color: var(--auth-muted);
            line-height: 1.6;
        }

        .register-success__button {
            min-height: 48px;
            color: #fff;
            font-weight: 600;
            background: var(--auth-primary);
            border: 1px solid var(--auth-primary);
            border-radius: 10px;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .register-success__button:hover,
        .register-success__button:focus-visible {
            color: #fff;
            background: var(--auth-primary-hover);
            border-color: var(--auth-primary-hover);
        }

        @media (prefers-reduced-motion: reduce) {
            .register-success__button {
                transition: none;
            }
        }
    </style>

    <div class="register-success text-center py-2">
        <div class="register-success__icon mb-3" aria-hidden="true">
            <i class="bi bi-check-lg"></i>
        </div>

        <h1 class="register-success__title h3 fw-bold mb-3">
            Đăng ký thành công!
        </h1>

        <p class="register-success__description mb-4">
            Tài khoản của bạn đã được tạo. Bạn có thể đăng nhập để tiếp tục.
        </p>

        <a href="{{ route('login.form') }}"
           class="btn register-success__button btn-lg w-100">
            Đăng nhập ngay
            <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
        </a>
    </div>
@endsection
