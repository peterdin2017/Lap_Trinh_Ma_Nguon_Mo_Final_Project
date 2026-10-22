{{-- Cây danh mục đệ quy. Biến: $nodes, $activeId. Chọn cha = gồm cả sản phẩm của con. --}}
<ul class="list-unstyled mb-0 {{ ($nested ?? false) ? 'ms-3' : '' }}">
    @foreach ($nodes as $node)
        <li class="mb-1">
            <a href="{{ route('store.products.index', array_merge(request()->except(['category', 'page']), ['category' => $node->category_id])) }}"
                class="text-decoration-none {{ $activeId === $node->category_id ? 'fw-bold text-primary' : 'text-dark' }}">
                {{ $node->category_name }}
            </a>

            @if ($node->children->isNotEmpty())
                @include('store.products._category-tree', [
                    'nodes' => $node->children,
                    'activeId' => $activeId,
                    'nested' => true,
                ])
            @endif
        </li>
    @endforeach
</ul>
