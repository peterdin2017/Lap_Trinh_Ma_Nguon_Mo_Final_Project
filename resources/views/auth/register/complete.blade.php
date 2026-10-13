@extends('auth.steps.layout')

@section('title', 'Đăng ký thành công - Website Bán hàng Online')
@section('step', 'BƯỚC 3 / 3')
@section('step_label', 'Hoàn tất')
@section('step_number', 3)
@section('step_total', 3)
@section('step_percent', '100%')

@section('content')
    <div class="text-center py-2">
        <div class="text-success display-4 mb-3" aria-hidden="true">
            <i class="bi bi-check-circle"></i>
        </div>

        <h1 class="h3 fw-bold text-primary mb-3">Đăng ký thành công!</h1>

        <p class="text-secondary mb-4">
            Tài khoản của bạn đã được tạo. Bạn có thể đăng nhập để tiếp tục.
        </p>

        <a href="{{ route('login.form') }}" class="btn btn-primary btn-lg w-100">
            Đăng nhập ngay
        </a>
    </div>
@endsection
