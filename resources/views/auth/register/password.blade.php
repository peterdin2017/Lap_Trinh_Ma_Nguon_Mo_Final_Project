@extends('auth.steps.layout')

@section('title', 'Tạo tài khoản - Website Bán hàng Online')
@section('step', 'BƯỚC 2 / 3')
@section('step_label', 'Hoàn tất thông tin')
@section('step_number', 2)
@section('step_total', 3)
@section('step_percent', '66.67%')

@section('content')
    <style>
        .register-details {
            --auth-primary: #1A94FF;
            --auth-primary-hover: #0077E6;
            --auth-primary-light: #EBF5FF;
            --auth-text: #0F172A;
            --auth-muted: #64748B;
            --auth-border: #E2E8F0;
            color: var(--auth-text);
        }

        .register-details__icon,
        .register-details__title {
            color: var(--auth-primary) !important;
        }

        .register-details__description,
        .register-details .form-text {
            color: var(--auth-muted) !important;
        }

        .register-details .form-label {
            color: var(--auth-text);
            font-size: .9rem;
            font-weight: 600;
        }

        .register-details__input-group {
            min-height: 48px;
            overflow: hidden;
            border: 1px solid var(--auth-border);
            border-radius: 10px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .register-details__input-group:focus-within {
            border-color: var(--auth-primary);
            box-shadow: 0 0 0 4px rgba(26, 148, 255, .15);
        }

        .register-details__input-group .input-group-text {
            min-width: 48px;
            justify-content: center;
            color: var(--auth-muted);
            background: #fff;
            border: 0;
        }

        .register-details__input-group .form-control {
            min-height: 46px;
            color: var(--auth-text);
            background: #fff;
            border: 0;
            box-shadow: none;
        }

        .register-details__input-group .form-control:focus {
            box-shadow: none;
        }

        .register-details__input-group .form-control::placeholder {
            color: #94A3B8;
        }

        .register-details__submit {
            min-height: 48px;
            color: #fff;
            font-weight: 600;
            background: var(--auth-primary);
            border: 1px solid var(--auth-primary);
            border-radius: 10px;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .register-details__submit:hover,
        .register-details__submit:focus-visible {
            color: #fff;
            background: var(--auth-primary-hover);
            border-color: var(--auth-primary-hover);
        }
    </style>

    <div class="register-details">
        <div class="text-center mb-4">
            <div class="register-details__icon mb-3" aria-hidden="true">
                <i class="bi bi-person-vcard fs-1"></i>
            </div>

            <h1 class="register-details__title h3 fw-bold mb-2">
                Hoàn tất thông tin
            </h1>

            <p class="register-details__description mb-0">
                Nhập họ tên và tạo mật khẩu cho tài khoản của bạn.
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

        <form method="POST" action="{{ route('register.password.submit') }}">
            @csrf

            <div class="mb-3">
                <label for="full_name" class="form-label">Họ và tên</label>

                <div class="input-group register-details__input-group">
                    <span class="input-group-text">
                        <i class="bi bi-person" aria-hidden="true"></i>
                    </span>
                    <input
                        id="full_name"
                        type="text"
                        name="full_name"
                        class="form-control @error('full_name') is-invalid @enderror"
                        value="{{ old('full_name') }}"
                        placeholder="Nhập họ và tên"
                        autocomplete="name"
                        required
                        autofocus
                    >
                </div>

                @error('full_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>

                <div class="input-group register-details__input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock" aria-hidden="true"></i>
                    </span>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Nhập mật khẩu"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div class="form-text mt-2">
                    Mật khẩu cần có chữ hoa, chữ thường, số và ký tự đặc biệt.
                </div>

                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label">
                    Nhập lại mật khẩu
                </label>

                <div class="input-group register-details__input-group">
                    <span class="input-group-text">
                        <i class="bi bi-shield-lock" aria-hidden="true"></i>
                    </span>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Nhập lại mật khẩu"
                        autocomplete="new-password"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn register-details__submit btn-lg w-100">
                Hoàn tất
                <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
            </button>
        </form>
    </div>
@endsection
