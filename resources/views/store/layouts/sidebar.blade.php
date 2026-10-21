@php
    $topCategories = ($categories ?? collect())->take(5);
    $moreCategories = ($categories ?? collect())->skip(5);
    $remainingCategoryCount = max(($categories ?? collect())->count() - 5, 0);
@endphp

<aside class="store-sidebar">
    <section class="store-sidebar__section">
        <h2 class="h6 fw-bold mb-3">Danh mục sản phẩm</h2>

        <nav class="filter-category-list d-flex flex-column gap-1"
             aria-label="Danh mục sản phẩm">
            @foreach ($topCategories as $category)
                <a
                    href="{{ request()->fullUrlWithQuery(['category' => $category->category_id]) }}"
                    class="filter-category-link d-flex align-items-center justify-content-between {{ (string) request('category') === (string) $category->category_id ? 'active' : '' }}"
                    @if ((string) request('category') === (string) $category->category_id)
                        aria-current="page"
                    @endif
                >
                    <span>{{ $category->category_name }}</span>
                    <span class="filter-category-count rounded-pill">
                        {{ $category->products_count }}
                    </span>
                </a>
            @endforeach

            @if ($remainingCategoryCount > 0)
                <div class="collapse" id="moreCategories">
                    <div class="d-flex flex-column gap-1">
                        @foreach ($moreCategories as $category)
                            <a
                                href="{{ request()->fullUrlWithQuery(['category' => $category->category_id]) }}"
                                class="filter-category-link d-flex align-items-center justify-content-between {{ (string) request('category') === (string) $category->category_id ? 'active' : '' }}"
                                @if ((string) request('category') === (string) $category->category_id)
                                    aria-current="page"
                                @endif
                            >
                                <span>{{ $category->category_name }}</span>
                                <span class="filter-category-count rounded-pill">
                                    {{ $category->products_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <button
                    class="btn-toggle-more"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#moreCategories"
                    aria-expanded="false"
                    aria-controls="moreCategories"
                >
                    <span class="when-collapsed">
                        Xem thêm ({{ $remainingCategoryCount }})
                        <i class="bi bi-chevron-down" aria-hidden="true"></i>
                    </span>
                    <span class="when-expanded">
                        Thu gọn
                        <i class="bi bi-chevron-up" aria-hidden="true"></i>
                    </span>
                </button>
            @endif
        </nav>
    </section>

    <section class="store-sidebar__section" aria-labelledby="price-filter-title">
        <h2 id="price-filter-title" class="h6 fw-bold mb-3">Khoảng giá</h2>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="price-under-5">
            <label class="form-check-label" for="price-under-5">Dưới 5 triệu</label>
        </div>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="price-5-15">
            <label class="form-check-label" for="price-5-15">5–15 triệu</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="price-over-15">
            <label class="form-check-label" for="price-over-15">Trên 15 triệu</label>
        </div>
    </section>

    <section aria-labelledby="brand-filter-title">
        <h2 id="brand-filter-title" class="h6 fw-bold mb-3">Thương hiệu</h2>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="brand-apple">
            <label class="form-check-label" for="brand-apple">Apple</label>
        </div>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="brand-samsung">
            <label class="form-check-label" for="brand-samsung">Samsung</label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="brand-xiaomi">
            <label class="form-check-label" for="brand-xiaomi">Xiaomi</label>
        </div>
    </section>
</aside>

<style>
    .store-sidebar {
        padding: 16px;
        color: var(--shop-text, #0F172A);
        background: var(--shop-card-bg, #FFFFFF);
        border: 1px solid var(--shop-border, #E2E8F0);
        border-radius: 12px;
    }

    .store-sidebar__section {
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--shop-border, #E2E8F0);
    }

    .filter-category-list {
        display: flex;
        flex-direction: column;
        gap: .25rem;
    }

    .filter-category-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        padding: 8px 12px;
        color: #334155;
        font-size: .9rem;
        font-weight: 500;
        text-decoration: none;
        border-radius: 6px;
        transition: color .15s ease, background-color .15s ease;
    }

    .filter-category-link:hover,
    .filter-category-link:focus-visible {
        color: var(--shop-primary, #1A94FF);
        background: #F1F5F9;
    }

    .filter-category-link.active {
        padding-left: 9px;
        color: var(--shop-primary, #1A94FF) !important;
        font-weight: 600;
        background: var(--shop-primary-light, #EBF5FF) !important;
        border-left: 4px solid var(--shop-primary, #1A94FF);
        border-radius: 0 8px 8px 0;
    }

    .filter-category-count {
        flex: 0 0 auto;
        padding: 2px 8px;
        color: var(--shop-primary, #1A94FF);
        font-size: .75rem;
        font-weight: 600;
        background: #D0E4FF;
    }

    .btn-toggle-more {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        color: var(--shop-primary, #1A94FF);
        font-size: .85rem;
        font-weight: 600;
        text-decoration: none;
        background: transparent;
        border: 0;
    }

    .btn-toggle-more:hover,
    .btn-toggle-more:focus-visible {
        color: var(--shop-primary-hover, #0077E6);
    }

    .btn-toggle-more .when-expanded {
        display: none;
    }

    .btn-toggle-more[aria-expanded="true"] .when-collapsed {
        display: none;
    }

    .btn-toggle-more[aria-expanded="true"] .when-expanded {
        display: inline;
    }

    .store-sidebar .form-check-input:checked {
        background-color: var(--shop-primary, #1A94FF);
        border-color: var(--shop-primary, #1A94FF);
    }

    .store-sidebar .form-check-input:focus {
        border-color: var(--shop-primary, #1A94FF);
        box-shadow: 0 0 0 .2rem rgb(26 148 255 / 16%);
    }

    @media (prefers-reduced-motion: reduce) {
        .filter-category-link {
            transition: none;
        }
    }
</style>
