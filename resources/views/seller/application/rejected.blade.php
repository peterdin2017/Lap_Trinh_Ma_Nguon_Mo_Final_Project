@extends('seller.layouts.app')

@section('title', 'Hồ sơ chưa được duyệt - Seller Center')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5 text-center">
                    <div class="text-danger mb-3">
                        <i class="bi bi-x-circle display-4"></i>
                    </div>

                    <span class="badge text-bg-danger mb-3">Chưa được duyệt</span>

                    <h1 class="h3 fw-bold mb-3">Hồ sơ cửa hàng chưa được chấp thuận</h1>

                    <p class="text-secondary mb-3">
                        Vui lòng xem lý do bên dưới và liên hệ bộ phận hỗ trợ nếu cần giải đáp.
                    </p>

                    @if (auth()->user()->seller?->reject_reason)
                        <div class="alert alert-danger text-start">
                            <strong>Lý do:</strong>
                            {{ auth()->user()->seller->reject_reason }}
                        </div>
                    @endif

                    <p class="small text-secondary">
                        Bạn có thể liên hệ bộ phận hỗ trợ để biết cách cập nhật và gửi lại hồ sơ.
                    </p>

                    <form action="{{ route('seller.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
