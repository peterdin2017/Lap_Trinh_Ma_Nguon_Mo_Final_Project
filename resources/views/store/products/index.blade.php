@extends('store.layouts.app')

@section('title', $filters['q'] !== '' ? 'Tìm kiếm: ' . $filters['q'] : ($currentCategory?->category_name ?? 'Tất cả sản phẩm'))

@section('content')
    <main class="container py-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="row g-4">
            {{-- Bộ lọc --}}
            <aside class="col-lg-3">
                <form method="GET" action="{{ route('store.products.index') }}" class="bg-white rounded shadow-sm p-3">
                    <input type="hidden" name="category" value="{{ $filters['category'] }}">

                    <h2 class="h6 fw-bold">Tìm kiếm</h2>
                    <input type="text" name="q" value="{{ $filters['q'] }}" maxlength="100" class="form-control mb-3"
                        placeholder="Tên hoặc thương hiệu">

                    <h2 class="h6 fw-bold">Khoảng giá (₫)</h2>
                    <div class="d-flex gap-2 mb-3">
                        <input type="number" name="min_price" value="{{ $filters['min_price'] }}" min="0"
                            class="form-control" placeholder="Từ">
                        <input type="number" name="max_price" value="{{ $filters['max_price'] }}" min="0"
                            class="form-control" placeholder="Đến">
                    </div>

                    <h2 class="h6 fw-bold">Sắp xếp</h2>
                    <select name="sort" class="form-select mb-3">
                        <option value="newest" @selected($filters['sort'] === 'newest')>Mới nhất</option>
                        <option value="best_selling" @selected($filters['sort'] === 'best_selling')>Bán chạy</option>
                        <option value="price_asc" @selected($filters['sort'] === 'price_asc')>Giá tăng dần</option>
                        <option value="price_desc" @selected($filters['sort'] === 'price_desc')>Giá giảm dần</option>
                    </select>

                    <button type="submit" class="btn btn-primary w-100">Áp dụng</button>
                    <a href="{{ route('store.products.index') }}" class="btn btn-link w-100 mt-1">Xóa bộ lọc</a>
                </form>

                <div class="bg-white rounded shadow-sm p-3 mt-3">
                    <h2 class="h6 fw-bold">Danh mục</h2>
                    <a href="{{ route('store.products.index', request()->except(['category', 'page'])) }}"
                        class="d-block mb-1 text-decoration-none {{ $filters['category'] === null ? 'fw-bold text-primary' : 'text-dark' }}">
                        Tất cả
                    </a>
                    @include('store.products._category-tree', [
                        'nodes' => $categoryTree,
                        'activeId' => $filters['category'],
                    ])
                </div>
            </aside>

            {{-- Kết quả --}}
            <section class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h5 mb-0">
                        @if ($filters['q'] !== '')
                            Kết quả cho "{{ $filters['q'] }}"
                        @else
                            {{ $currentCategory?->category_name ?? 'Tất cả sản phẩm' }}
                        @endif
                    </h1>
                    <span class="text-secondary small">{{ number_format($products->total()) }} sản phẩm</span>
                </div>

                @if ($products->isEmpty())
                    <div class="alert alert-light border text-center py-5">
                        Không tìm thấy sản phẩm phù hợp. Hãy thử đổi từ khóa hoặc bộ lọc.
                    </div>
                @else
                    <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
                        @foreach ($products as $product)
                            @include('store.products._card', ['product' => $product])
                        @endforeach
                    </div>

                    <div class="mt-4 d-flex justify-content-center">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </section>
        </div>
    </main>

    <style>
        .product-card {
            transition: transform .18s ease, box-shadow .18s ease;
        }

        a:hover .product-card {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .product-title {
            display: -webkit-box;
            min-height: 2.7em;
            overflow: hidden;
            line-height: 1.35;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }
    </style>
@endsection
