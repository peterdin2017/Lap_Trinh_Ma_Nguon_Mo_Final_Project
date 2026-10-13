@extends('seller.layouts.app')

@section('title', 'Tổng quan - Seller Center')

@section('content')
    <div class="mb-4">
        <h2 class="h4 fw-bold mb-1">Tổng quan cửa hàng</h2>
        <p class="text-secondary mb-0">
            Theo dõi tình hình hoạt động của cửa hàng tại đây.
        </p>
    </div>

    <div class="row g-3">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary mb-2">Sản phẩm</div>
                            <div class="fs-3 fw-bold">0</div>
                        </div>
                        <i class="bi bi-box-seam fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary mb-2">Đơn hàng mới</div>
                            <div class="fs-3 fw-bold">0</div>
                        </div>
                        <i class="bi bi-bag fs-3 text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary mb-2">Đơn cần xử lý</div>
                            <div class="fs-3 fw-bold">0</div>
                        </div>
                        <i class="bi bi-clock-history fs-3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary mb-2">Doanh thu</div>
                            <div class="fs-3 fw-bold">0₫</div>
                        </div>
                        <i class="bi bi-currency-exchange fs-3 text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-4">
            <h3 class="h5 fw-bold mb-2">Chào mừng đến với Seller Center</h3>
            <p class="text-secondary mb-0">
                Các số liệu sẽ được cập nhật khi chức năng sản phẩm và đơn hàng được triển khai.
            </p>
        </div>
    </div>
@endsection
