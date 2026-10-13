@extends('seller.layouts.app')

@section('title', 'Tài khoản người bán bị tạm khóa')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5 text-center">
                <div class="text-danger mb-3">
                        <i class="bi bi-shield-lock display-4"></i>
                    </div>

                    <span class="badge text-bg-danger mb-3">Tạm khóa</span>

                    <h1 class="h3 fw-bold mb-3">Tài khoản người bán đang bị tạm khóa</h1>

                    <p class="text-secondary mb-4">
                        Hiện tại bạn chưa thể sử dụng các chức năng quản lý cửa hàng.
                        Vui lòng liên hệ bộ phận hỗ trợ để được hướng dẫn.
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
