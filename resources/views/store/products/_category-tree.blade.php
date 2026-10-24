@php
    $isInActivePath = function ($category) use (&$isInActivePath, $activeId) {
        if ((string) $category->category_id === (string) $activeId) {
            return true;
        }

        foreach ($category->children as $child) {
            if ($isInActivePath($child)) {
                return true;
            }
        }

        return false;
    };
@endphp

<ul class="store-category-tree {{ ($nested ?? false) ? 'store-category-tree--nested' : '' }}">
    @foreach ($nodes as $node)
        @php
            $hasChildren = $node->children->isNotEmpty();
            $isActive = (string) $activeId === (string) $node->category_id;
            $containsActive = $hasChildren && $isInActivePath($node);
            $isExpanded = $isActive || $containsActive;
            $collapseId = 'category-children-' . $node->category_id;
        @endphp

        <li class="store-category-tree__item">
            <div class="store-category-tree__row">
                <a
                    href="{{ route('store.products.index', array_merge(
                        request()->except(['category', 'page']),
                        ['category' => $node->category_id]
                    )) }}"
                    class="store-category-link {{ $isActive || $containsActive ? 'is-active' : '' }}"
                    @if ($isActive) aria-current="page" @endif
                >
                    <span class="store-category-link__name">
                        {{ $node->category_name }}
                    </span>

                    @if (isset($node->products_count))
                        <span class="store-category-link__count">
                            {{ $node->products_count }}
                        </span>
                    @endif
                </a>

                @if ($hasChildren)
                    <button
                        class="store-category-toggle {{ $isExpanded ? '' : 'collapsed' }}"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#{{ $collapseId }}"
                        aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
                        aria-controls="{{ $collapseId }}"
                        aria-label="{{ $isExpanded ? 'Thu gọn' : 'Mở rộng' }} danh mục {{ $node->category_name }}"
                    >
                        <i class="bi bi-chevron-down" aria-hidden="true"></i>
                    </button>
                @endif
            </div>

            @if ($hasChildren)
                <div
                    id="{{ $collapseId }}"
                    class="collapse {{ $isExpanded ? 'show' : '' }}"
                >
                    @include('store.products._category-tree', [
                        'nodes' => $node->children,
                        'activeId' => $activeId,
                        'nested' => true,
                    ])
                </div>
            @endif
        </li>
    @endforeach
</ul>
