{{-- Sử dụng layout chung --}}
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="mb-4">Đăng ký tài khoản</h3>

        {{-- Thông báo sau khi đăng ký thành công --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            {{-- Họ tên --}}
            <div class="mb-3">
                <label class="form-label">Họ tên</label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}">
                @error('full_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Mật khẩu --}}
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control">
                <small class="text-muted">Tối thiểu 6 ký tự, có chữ hoa, chữ thường, số và ký tự đặc biệt</small>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Nhập lại mật khẩu --}}
            <div class="mb-3">
                <label class="form-label">Nhập lại mật khẩu</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
        </form>
    </div>
</div>
@endsection
