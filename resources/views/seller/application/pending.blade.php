@extends('seller.layouts.app')

@section('title', 'Hồ sơ đang chờ duyệt - Seller Center')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5 text-center">
                    <div class="text-warning mb-3">
                        <i class="bi bi-hourglass-split display-4"></i>
                    </div>

                    <span class="badge text-bg-warning mb-3">Đang chờ duyệt</span>

                    <h1 class="h3 fw-bold mb-3">Hồ sơ cửa hàng đang được xem xét</h1>

                    <p class="text-secondary mb-4">
                        Chúng tôi đã nhận được hồ sơ đăng ký của bạn.
                        Bạn sẽ truy cập được Seller Center sau khi hồ sơ được Admin duyệt.
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
