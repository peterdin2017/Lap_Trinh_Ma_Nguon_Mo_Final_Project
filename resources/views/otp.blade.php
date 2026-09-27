@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="mb-4">Xác thực mã OTP</h3>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <p class="text-muted">Mã OTP đã được gửi đến email của bạn, có hiệu lực trong 5 phút.</p>

        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf

            <input type="hidden" name="account_id" value="{{ $account_id }}">

            <div class="mb-3">
                <label class="form-label">Nhập mã OTP</label>
                <input type="text" name="otp_code" class="form-control" maxlength="6">
                @error('otp_code')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">Xác thực</button>
        </form>
    </div>
</div>
@endsection
