@extends('store.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row g-4">
            <aside class="col-12 col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">Tài khoản của tôi</h2>

                        <nav class="nav nav-pills flex-column gap-2" aria-label="Điều hướng tài khoản">
                            <a href="{{ route('store.account.profile') }}"
                                class="nav-link {{ request()->routeIs('store.account.profile*') ? 'active' : 'text-dark' }}"
                                @if (request()->routeIs('store.account.profile*')) aria-current="page" @endif>
                                <i class="bi bi-person me-2" aria-hidden="true"></i>
                                Hồ sơ
                            </a>

                            <a href="{{ route('store.account.password') }}"
                                class="nav-link {{ request()->routeIs('store.account.password*') ? 'active' : 'text-dark' }}"
                                @if (request()->routeIs('store.account.password*')) aria-current="page" @endif>
                                <i class="bi bi-shield-lock me-2" aria-hidden="true"></i>
                                Đổi mật khẩu
                            </a>

                            <a href="{{ route('store.account.addresses.index') }}"
                                class="nav-link {{ request()->routeIs('store.account.addresses.*') ? 'active' : 'text-dark' }}"
                                @if (request()->routeIs('store.account.addresses.*')) aria-current="page" @endif>
                                <i class="bi bi-geo-alt me-2" aria-hidden="true"></i>
                                Địa chỉ nhận hàng
                            </a>
                            <a href="{{ route('store.account.orders.index') }}"
                                class="nav-link {{ request()->routeIs('store.account.orders.*') ? 'active' : 'text-dark' }}"
                                @if (request()->routeIs('store.account.orders.*')) aria-current="page" @endif>
                                <i class="bi bi-receipt me-2" aria-hidden="true"></i>
                                Đơn hàng của tôi
                            </a>
                        </nav>
                    </div>
                </div>
            </aside>

            <section class="col-12 col-lg-9" aria-label="Nội dung tài khoản">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                @yield('account_content')
            </section>
        </div>
    </div>
@endsection
