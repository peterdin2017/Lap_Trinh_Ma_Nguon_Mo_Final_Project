{{-- Thanh trên cùng: tiện ích nhỏ --}}
<div style="background-color: #002347;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center py-1" style="font-size: 0.85rem;">

            {{-- Bên trái --}}
            <div class="d-flex align-items-center text-white">
                <a href="#" class="text-white text-decoration-none me-3">Kênh Người Bán</a>
                <span class="me-3">|</span>
                <a href="#" class="text-white text-decoration-none me-3">Trở thành Người bán</a>
                <span class="me-3">|</span>
                <span class="me-2">Kết nối</span>
                <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
                <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
            </div>

            {{-- Bên phải --}}
            <div class="d-flex align-items-center text-white">
                @if (session('account_id'))
                    <div class="dropdown user-menu">
                        <a href="#" class="text-white text-decoration-none d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                style="width: 28px; height: 28px; background-color: #ffffff; color: #003B73; font-weight: bold; font-size: 0.85rem;">
                                {{ strtoupper(substr(session('user_name'), 0, 1)) }}
                            </div>
                            {{ session('user_name') }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person me-2"></i>Tài Khoản Của Tôi
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-bag me-2"></i>Đơn Mua
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Đăng Xuất
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('register.form') }}" class="text-white text-decoration-none me-3">Đăng Ký</a>
                    <span class="me-3">|</span>
                    <a href="{{ route('login.form') }}" class="text-white text-decoration-none">Đăng Nhập</a>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- Thanh chính: logo chữ + ô tìm kiếm + giỏ hàng --}}
<nav style="background: linear-gradient(135deg, #003B73 0%, #002347 100%);">
    <div class="container">
        <div class="d-flex align-items-center py-3 gap-4">

            {{-- Logo chữ --}}
            <a href="{{ url('/') }}" class="text-white text-decoration-none d-flex align-items-center">
                <i class="bi bi-shop-window" style="font-size: 2.2rem;"></i>
                <span class="fw-bold ms-2" style="font-size: 1.3rem;">SHOP ONLINE</span>
            </a>

            {{-- Ô tìm kiếm --}}
            <form action="#" method="GET" class="flex-grow-1">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm..."
                        style="border: none; height: 42px;">
                    <button class="btn" type="submit" style="background-color: #003B73; border: none;">
                        <i class="bi bi-search text-white"></i>
                    </button>
                </div>
            </form>

            {{-- Giỏ hàng --}}
            <a href="#" class="text-white text-decoration-none position-relative">
                <i class="bi bi-cart3" style="font-size: 1.8rem;"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
                    style="font-size: 0.65rem;">
                    0
                </span>
            </a>

        </div>

        {{-- Danh mục nhanh bên dưới ô tìm kiếm --}}
        <div class="d-flex gap-4 pb-2" style="font-size: 0.85rem;">
            <a href="#" class="text-white text-decoration-none">Thời Trang Nam</a>
            <a href="#" class="text-white text-decoration-none">Điện Thoại & Phụ Kiện</a>
            <a href="#" class="text-white text-decoration-none">Máy Tính & Laptop</a>
            <a href="#" class="text-white text-decoration-none">Đồng Hồ</a>
            <a href="#" class="text-white text-decoration-none">Giày Dép Nam</a>
        </div>
    </div>
</nav>
<style>
    /* Container cha cần position: relative để menu con định vị đúng theo nó */
    .user-menu {
        position: relative;
    }

    /* Định vị menu dính sát ngay dưới, không có khoảng hở */
    .user-menu .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 0;
        padding-top: 12px;   /* đệm trong suốt phía trên, để "nối liền" vùng hover */
    }

    /* Hiện menu khi rê chuột vào toàn bộ khu vực (bao gồm cả phần đệm phía trên) */
    .user-menu:hover .dropdown-menu {
        display: block;
    }
</style>
