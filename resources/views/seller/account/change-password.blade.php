@extends('seller.layouts.app')

@section('title', 'Đổi mật khẩu')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h2 class="h4 fw-bold mb-1">Đổi mật khẩu</h2>
            <p class="text-secondary border-bottom pb-3 mb-4">
                Nhập mật khẩu hiện tại và mật khẩu mới.
            </p>

            <form method="POST" action="{{ route('seller.account.password.update') }}">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <label for="current_password" class="col-md-3 col-form-label">
                        Mật khẩu hiện tại
                    </label>
                    <div class="col-md-8">
                        <input id="current_password"
                               name="current_password"
                               type="password"
                               autocomplete="current-password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="new_password" class="col-md-3 col-form-label">
                        Mật khẩu mới
                    </label>
                    <div class="col-md-8">
                        <input id="new_password"
                               name="new_password"
                               type="password"
                               autocomplete="new-password"
                               class="form-control @error('new_password') is-invalid @enderror"
                               required>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự.</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="new_password_confirmation" class="col-md-3 col-form-label">
                        Xác nhận mật khẩu mới
                    </label>
                    <div class="col-md-8">
                        <input id="new_password_confirmation"
                               name="new_password_confirmation"
                               type="password"
                               autocomplete="new-password"
                               class="form-control"
                               required>
                    </div>
                </div>

                <div class="row">
                    <div class="offset-md-3 col-md-8">
                        <button type="submit" class="btn btn-primary">
                            Lưu mật khẩu mới
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
