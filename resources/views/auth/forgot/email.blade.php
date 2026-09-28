@extends('layouts.auth')

@section('title', 'Quên mật khẩu')

@section('content')
    <h4 class="fw-bold mb-2" style="color: #003B73;">Quên mật khẩu</h4>
    <p class="text-muted mb-4">Nhập email đã đăng ký, chúng tôi sẽ gửi mã xác nhận để bạn đặt lại mật khẩu.</p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('forgot.send') }}">
        @csrf
        <div class="mb-3">
            <input type="email" name="email"
                class="form-control form-control-lg @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="Nhập email">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn w-100 btn-lg text-white fw-semibold" style="background-color: #003B73;">
            GỬI MÃ XÁC NHẬN
        </button>
    </form>

    <p class="text-center mt-4 mb-0">
        <a href="{{ route('login.form') }}" class="text-decoration-none" style="color: #003B73;">
            Quay lại đăng nhập
        </a>
    </p>
@endsection
