@php
    $currentMinPrice = $filters['min_price'] ?? request('min_price');
    $currentMaxPrice = $filters['max_price'] ?? request('max_price');
    $currentQuery = $filters['q'] ?? request('q', '');
    $currentSort = $filters['sort'] ?? request('sort', 'newest');
    $currentCategory = $filters['category'] ?? request('category');
@endphp

<div class="store-sidebar">
    <form method="GET" action="{{ route('store.products.index') }}">
        <h2 class="h6 fw-bold mb-3">Tìm kiếm và lọc sản phẩm</h2>

        <div class="mb-3">
            <label for="sidebar-search" class="form-label">Từ khóa</label>
            <input
                id="sidebar-search"
                type="search"
                name="q"
                value="{{ $currentQuery }}"
                maxlength="100"
                class="form-control"
                placeholder="Tên hoặc thương hiệu"
            >
        </div>

        @if ($currentCategory)
            <input
                type="hidden"
                name="category"
                value="{{ $currentCategory }}"
            >
        @endif

        <section class="store-sidebar__section">
            <h3 class="h6 fw-bold mb-3">Khoảng giá (₫)</h3>

            <div class="d-flex gap-2">
                <div class="flex-fill min-w-0">
                    <label for="sidebar-min-price" class="form-label small">
                        Từ
                    </label>
                    <input
                        id="sidebar-min-price"
                        type="number"
                        name="min_price"
                        value="{{ $currentMinPrice }}"
                        min="0"
                        class="form-control"
                        placeholder="Giá thấp nhất"
                    >
                </div>

                <div class="flex-fill min-w-0">
                    <label for="sidebar-max-price" class="form-label small">
                        Đến
                    </label>
                    <input
                        id="sidebar-max-price"
                        type="number"
                        name="max_price"
                        value="{{ $currentMaxPrice }}"
                        min="0"
                        class="form-control"
                        placeholder="Giá cao nhất"
                    >
                </div>
            </div>
        </section>

        <div class="mb-3">
            <label for="sidebar-sort" class="form-label fw-bold">
                Sắp xếp
            </label>
            <select id="sidebar-sort" name="sort" class="form-select">
                <option value="newest" @selected($currentSort === 'newest')>
                    Mới nhất
                </option>
                <option value="best_selling" @selected($currentSort === 'best_selling')>
                    Bán chạy
                </option>
                <option value="price_asc" @selected($currentSort === 'price_asc')>
                    Giá tăng dần
                </option>
                <option value="price_desc" @selected($currentSort === 'price_desc')>
                    Giá giảm dần
                </option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Áp dụng bộ lọc
        </button>

        <a
            href="{{ route('store.products.index') }}"
            class="btn btn-outline-secondary w-100 mt-2"
        >
            Xóa bộ lọc
        </a>
    </form>
</div>
