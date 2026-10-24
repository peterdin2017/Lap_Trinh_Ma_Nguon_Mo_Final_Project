<nav class="small mb-3" aria-label="breadcrumb">
    <a href="{{ route('store.home') }}" class="text-decoration-none">Trang chủ</a>

    @isset($breadcrumb)
        @foreach ($breadcrumb as $crumb)
            <span class="text-secondary mx-2">/</span>
            <a
                href="{{ route('store.products.index', ['category' => $crumb->category_id]) }}"
                class="text-decoration-none"
            >
                {{ $crumb->category_name }}
            </a>
        @endforeach
    @endisset

    <span class="text-secondary mx-2">/</span>
    <span class="text-secondary">{{ $product->product_name }}</span>
</nav>
