@extends('store.layouts.app')

@section('content')
    <main class="container py-4">
        <div class="row g-4">
            <aside class="col-12 col-lg-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h5 fw-bold mb-3">Tài khoản của tôi</h2>

                        <nav class="nav nav-pills flex-column gap-2">
                            <a href="{{ route('store.account.profile') }}"
                                class="nav-link {{ request()->routeIs('store.account.profile*') ? 'active' : 'text-dark' }}">
                                <i class="bi bi-person me-2"></i>Hồ sơ
                            </a>

                            <a href="{{ route('store.account.password') }}"
                                class="nav-link {{ request()->routeIs('store.account.password*') ? 'active' : 'text-dark' }}">
                                <i class="bi bi-shield-lock me-2"></i>Đổi mật khẩu
                            </a>
                            
                            <a href="{{ route('store.account.addresses.index') }}"
                                class="nav-link {{ request()->routeIs('store.account.addresses.*') ? 'active' : 'text-dark' }}">
                                <i class="bi bi-geo-alt me-2"></i>Địa chỉ nhận hàng
                            </a>
                        </nav>
                    </div>
                </div>
            </aside>

            <section class="col-12 col-lg-9">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                @yield('account_content')
            </section>
        </div>
    </main>
@endsection
