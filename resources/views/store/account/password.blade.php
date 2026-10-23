@extends('store.account.layouts.account')

@section('title', 'Đổi mật khẩu')

@section('account_content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <h1 class="h4 fw-bold mb-1">Đổi mật khẩu</h1>
            <p class="text-secondary border-bottom pb-3 mb-4">
                Nhập mật khẩu hiện tại để xác nhận thay đổi.
            </p>

            <form
                method="POST"
                action="{{ route('store.account.password.update') }}"
            >
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="current_password" class="form-label">
                        Mật khẩu hiện tại
                    </label>
                    <input
                        id="current_password"
                        name="current_password"
                        type="password"
                        autocomplete="current-password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        required
                    >
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        Mật khẩu mới
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        minlength="8"
                        aria-describedby="password-help"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="password-help" class="form-text">
                        Mật khẩu mới cần có ít nhất 8 ký tự.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">
                        Xác nhận mật khẩu mới
                    </label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        minlength="8"
                        class="form-control"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-shield-check me-1" aria-hidden="true"></i>
                    Cập nhật mật khẩu
                </button>
            </form>
        </div>
    </div>
@endsection
