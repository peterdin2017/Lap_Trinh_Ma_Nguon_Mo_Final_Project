@extends('auth.steps.layout')

@section('title', 'Đặt lại mật khẩu thành công')
@section('step', 'BƯỚC 4 / 4')
@section('step_label', 'Hoàn tất')
@section('step_number', 4)
@section('step_total', 4)
@section('step_percent', '100%')

@section('content')
    <div class="text-center">
        <div class="display-4 text-success mb-3">✓</div>

        <h1 class="h2 fw-bold text-primary mb-2">Đặt lại mật khẩu thành công</h1>

        <p class="text-secondary mb-4">
            Bạn có thể đăng nhập bằng mật khẩu mới.
        </p>

        <a href="{{ route($loginRoute) }}" class="btn btn-primary btn-lg w-100">
            Quay về đăng nhập
        </a>
    </div>
@endsection
