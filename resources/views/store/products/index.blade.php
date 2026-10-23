@extends('store.layouts.app')

@section('title', $filters['q'] !== '' ? 'Tìm kiếm: ' . $filters['q'] : ($currentCategory?->category_name ?? 'Tất cả sản phẩm'))

@section('content')
    <div class="container py-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="row g-4">
            <aside class="col-lg-3">
                @include('store.layouts.sidebar', [
                    'filters' => $filters,
                ])

                <div class="bg-white rounded shadow-sm p-3 mt-3">
                    <h2 class="h6 fw-bold">Danh mục</h2>

                    <a
                        href="{{ route('store.products.index', request()->except(['category', 'page'])) }}"
                        class="d-block mb-1 text-decoration-none {{ $filters['category'] === null ? 'fw-bold text-primary' : 'text-dark' }}"
                    >
                        Tất cả
                    </a>

                    @include('store.products._category-tree', [
                        'nodes' => $categoryTree,
                        'activeId' => $filters['category'],
                    ])
                </div>
            </aside>

            <section class="col-lg-9" aria-label="Danh sách sản phẩm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="h5 mb-0">
                        @if ($filters['q'] !== '')
                            Kết quả cho "{{ $filters['q'] }}"
                        @else
                            {{ $currentCategory?->category_name ?? 'Tất cả sản phẩm' }}
                        @endif
                    </h1>

                    <span class="text-secondary small">
                        {{ number_format($products->total()) }} sản phẩm
                    </span>
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
    </div>
@endsection
