@extends('seller.layouts.app')

@section('title', 'Sản phẩm - Seller Center')

@section('content')
    @php
        $status = $status ?? request('status', 'all');
        $search = $search ?? request('q', '');
        $categoryId = $categoryId ?? request('category');
        $statusTabs = ['all' => 'Tất cả', 'active' => 'Đang hoạt động', 'out_of_stock' => 'Hết hàng', 'hidden' => 'Đã ẩn'];
        $statusMap = [
            'active' => ['Đang bán', 'status-active'],
            'hidden' => ['Đã ẩn', 'status-hidden'],
            'locked' => ['Bị khóa', 'status-locked'],
        ];
    @endphp

    <style>
        .seller-products-page { color: var(--seller-text, #0f172a); }
        .seller-products-page .products-heading, .seller-products-page .products-filter-card,
        .seller-products-page .products-table-card {
            color: var(--seller-text, #0f172a); background: var(--seller-card-bg, #fff);
            border: 1px solid var(--seller-border, #e2e8f0); border-radius: 12px;
            box-shadow: var(--seller-shadow, 0 4px 16px rgba(15, 23, 42, .04));
        }
        .seller-products-page .products-heading { padding: 1rem 1.25rem; }
        .seller-products-page .products-table-card { overflow: hidden; }
        .seller-products-page .products-tabs { display: flex; gap: 1.35rem; overflow-x: auto; border-bottom: 1px solid var(--seller-border, #e2e8f0); }
        .seller-products-page .products-tabs a { padding: .55rem .1rem .8rem; color: var(--seller-text-muted, #64748b); font-size: .9rem; text-decoration: none; white-space: nowrap; border-bottom: 2px solid transparent; transition: color .18s, border-color .18s; }
        .seller-products-page .products-tabs a:hover, .seller-products-page .products-tabs a.active { color: var(--seller-primary, #1a94ff); }
        .seller-products-page .products-tabs a.active { font-weight: 700; border-bottom-color: var(--seller-primary, #1a94ff); }
        .seller-products-page .products-table { min-width: 940px; color: var(--seller-text, #0f172a); border-color: var(--seller-border, #e2e8f0); }
        .seller-products-page .products-table thead th { padding: .85rem 1rem; color: var(--seller-text-muted, #64748b); background: var(--seller-hover-bg, #f8fafc); border-color: var(--seller-border, #e2e8f0); font-size: .76rem; font-weight: 700; letter-spacing: .035em; text-transform: uppercase; white-space: nowrap; }
        .seller-products-page .products-table tbody td { padding: .9rem 1rem; color: var(--seller-text, #0f172a); background: var(--seller-card-bg, #fff); border-color: var(--seller-border, #e2e8f0); vertical-align: middle; }
        .seller-products-page .products-table tbody tr:hover td { background: var(--seller-hover-bg, #f8fafc); }
        .seller-products-page .product-image { display: block; width: 52px; height: 52px; flex: 0 0 52px; object-fit: cover; border: 1px solid var(--seller-border, #e2e8f0); border-radius: 9px; background: var(--seller-page-bg, #f4f7fa); }
        .seller-products-page .product-name { max-width: 320px; overflow-wrap: anywhere; color: var(--seller-text, #0f172a); font-weight: 600; line-height: 1.4; }
        .seller-products-page .product-price { color: var(--seller-price, #e5484d) !important; font-weight: 700; white-space: nowrap; }
        .seller-products-page .status-active, .seller-products-page .stock-healthy { color: #166534; background: #dcfce7; }
        .seller-products-page .status-hidden { color: var(--seller-text-muted, #64748b); background: var(--seller-hover-bg, #f8fafc); border: 1px solid var(--seller-border, #e2e8f0); }
        .seller-products-page .status-locked, .seller-products-page .stock-empty { color: #991b1b; background: #fee2e2; }
        .seller-products-page .stock-low { color: #9a3412; background: #ffedd5; }
        [data-theme="dark"] .seller-products-page .status-active, [data-theme="dark"] .seller-products-page .stock-healthy { color: #86efac; background: #14532d; }
        [data-theme="dark"] .seller-products-page .status-locked, [data-theme="dark"] .seller-products-page .stock-empty { color: #fca5a5; background: #7f1d1d; }
        [data-theme="dark"] .seller-products-page .stock-low { color: #fdba74; background: #7c2d12; }
        .seller-products-page .stock-badge { min-width: 78px; font-weight: 600; }
        .seller-products-page .products-empty-icon { display: inline-flex; width: 3.25rem; height: 3.25rem; align-items: center; justify-content: center; border-radius: 50%; color: var(--seller-primary, #1a94ff); background: var(--seller-primary-light, #eaf5ff); }
        .seller-products-page .form-control, .seller-products-page .form-select { min-height: 40px; color: var(--seller-text, #0f172a); background: var(--seller-card-bg, #fff); border-color: var(--seller-border, #e2e8f0); border-radius: 8px; }
        .seller-products-page .form-control:focus, .seller-products-page .form-select:focus { border-color: var(--seller-primary, #1a94ff); box-shadow: 0 0 0 .2rem rgba(26, 148, 255, .14); }
        .seller-products-page .bulk-action-bar { display: flex; max-height: 0; align-items: center; justify-content: space-between; gap: 1rem; overflow: hidden; padding: 0 1rem; margin-bottom: 0; opacity: 0; transform: translateY(-8px); border: 0 solid transparent; border-radius: 10px; background: #eaf5ff; transition: max-height .24s, opacity .2s, transform .2s, padding .24s, margin .24s, border-width .24s; }
        .seller-products-page .bulk-action-bar.is-visible { max-height: 100px; padding: .75rem 1rem; margin-bottom: .75rem; opacity: 1; transform: translateY(0); border-width: 1px; border-color: #bfdbfe; }
        .seller-products-page .bulk-action-bar[hidden] { display: none !important; }
        .seller-products-page .bulk-action-bar__actions { display: flex; flex-wrap: wrap; gap: .5rem; }
        .seller-products-page .action-icon { display: inline-flex; width: 34px; height: 34px; align-items: center; justify-content: center; padding: 0; }
        .seller-products-page .products-loading { opacity: .58; pointer-events: none; transition: opacity .15s; }
        .seller-products-page .pagination { margin-bottom: 0; }
        @media (max-width: 575.98px) { .seller-products-page .products-heading { align-items: stretch !important; } .seller-products-page .products-heading .btn { width: 100%; } .seller-products-page .bulk-action-bar { align-items: flex-start; flex-direction: column; } }
        @media (prefers-reduced-motion: reduce) { .seller-products-page *, .seller-products-page *::before, .seller-products-page *::after { transition-duration: .01ms !important; } }
    </style>

    <div class="seller-products-page" id="sellerProductsPage">
        <div class="seller-products-content" id="sellerProductsContent">
            <header class="products-heading mb-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div>
                    <h1 class="h4 fw-bold mb-1">Sản phẩm</h1>
                    <p class="small text-secondary mb-0">{{ number_format($products->total()) }} sản phẩm</p>
                </div>
                <a href="{{ route('seller.products.create') }}" class="btn btn-primary px-3 rounded-3">
                    <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Thêm sản phẩm mới
                </a>
            </header>

            <section class="products-filter-card p-3 p-lg-4 mb-3">
                <nav class="products-tabs mb-3" aria-label="Lọc theo trạng thái">
                    @foreach ($statusTabs as $value => $label)
                        <a href="{{ route('seller.products.index', array_merge(request()->except(['status', 'page']), ['status' => $value])) }}"
                            class="js-products-ajax-link {{ $status === $value ? 'active' : '' }}"
                            @if ($status === $value) aria-current="page" @endif>{{ $label }}</a>
                    @endforeach
                </nav>
                <form method="GET" action="{{ route('seller.products.index') }}" id="productFilterForm">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="row g-2">
                        <div class="col-12 col-md-6">
                            <label for="product-search" class="visually-hidden">Tìm tên hoặc mã sản phẩm</label>
                            <input id="product-search" type="search" name="q" value="{{ $search }}" class="form-control" placeholder="Tìm theo tên hoặc mã sản phẩm..." autocomplete="off">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="product-category" class="visually-hidden">Lọc theo danh mục</label>
                            <select id="product-category" name="category" class="form-select">
                                <option value="">Tất cả danh mục</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}" @selected((string) $categoryId === (string) $category->category_id)>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-2">
                            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-secondary w-100 js-products-ajax-link">
                                <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i>Xóa lọc
                            </a>
                        </div>
                    </div>
                </form>
            </section>

            <div class="bulk-action-bar" id="bulkActionBar" hidden aria-live="polite">
                <div class="fw-semibold text-primary">Đã chọn <span id="selectedProductCount">0</span> sản phẩm</div>
                <div class="bulk-action-bar__actions">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="bulkHideButton"><i class="bi bi-eye-slash me-1" aria-hidden="true"></i>Ẩn các mục đã chọn</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="bulkDeleteButton"><i class="bi bi-trash3 me-1" aria-hidden="true"></i>Xóa các mục đã chọn</button>
                </div>
            </div>

            <section class="products-table-card" aria-labelledby="products-list-title">
                <div class="px-3 px-lg-4 py-3 border-bottom" style="background: var(--seller-card-bg, #fff); border-color: var(--seller-border, #e2e8f0) !important;">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div><h2 class="h6 fw-bold mb-1" id="products-list-title">Danh sách sản phẩm</h2><p class="small text-secondary mb-0">Thông tin sản phẩm, giá bán và tồn kho.</p></div>
                        <span class="badge rounded-pill text-secondary" style="background: var(--seller-page-bg, #f4f7fa); border: 1px solid var(--seller-border, #e2e8f0);">{{ number_format($products->total()) }} sản phẩm</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table products-table align-middle mb-0">
                        <thead><tr>
                            <th class="text-center" style="width:48px"><input class="form-check-input" type="checkbox" id="selectAllProducts" aria-label="Chọn tất cả sản phẩm trên trang"></th>
                            <th style="width:72px">Ảnh</th><th>Sản phẩm</th><th>Danh mục</th><th class="text-end">Giá</th><th class="text-center">Tồn kho</th><th class="text-center">Trạng thái</th><th class="text-center">Thao tác</th>
                        </tr></thead>
                        <tbody>
                            @forelse ($products as $product)
                                @php
                                    [$statusLabel, $statusClass] = $statusMap[$product->status] ?? [$product->status, 'status-hidden'];
                                    $stock = (int) $product->stock_quantity;
                                @endphp
                                <tr data-product-row data-product-id="{{ $product->product_id }}" data-product-status="{{ $product->status }}" data-visibility-url="{{ route('seller.products.visibility', $product->product_id) }}" data-delete-url="{{ route('seller.products.destroy', $product->product_id) }}">
                                    <td class="text-center"><input class="form-check-input js-product-select" type="checkbox" value="{{ $product->product_id }}" aria-label="Chọn {{ $product->product_name }}" @disabled($product->status === 'locked')></td>
                                    <td>
                                        @if ($product->image_url)
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->image_url) }}" alt="{{ $product->product_name }}" class="product-image" loading="lazy">
                                        @else
                                            <div class="product-image d-flex align-items-center justify-content-center"><i class="bi bi-image text-secondary fs-4" aria-hidden="true"></i></div>
                                        @endif
                                    </td>
                                    <td><div class="product-name">{{ $product->product_name }}</div><small class="text-secondary">Mã SP: {{ $product->product_id }}</small></td>
                                    <td class="text-secondary">{{ $product->category?->category_name ?? '—' }}</td>
                                    <td class="text-end product-price">{{ number_format((float) $product->price, 0, ',', '.') }}₫</td>
                                    <td class="text-center">
                                        @if ($stock === 0)<span class="badge rounded-pill stock-badge stock-empty">Hết hàng</span>
                                        @elseif ($stock <= 10)<span class="badge rounded-pill stock-badge stock-low">Sắp hết · {{ number_format($stock) }}</span>
                                        @else<span class="badge rounded-pill stock-badge stock-healthy">{{ number_format($stock) }}</span>@endif
                                    </td>
                                    <td class="text-center">
                                        @if ($product->status === 'locked')<span class="badge rounded-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                                        @else
                                            <div class="d-inline-flex align-items-center gap-2"><div class="form-check form-switch mb-0"><input class="form-check-input js-product-visibility" type="checkbox" role="switch" data-url="{{ route('seller.products.visibility', $product->product_id) }}" @checked($product->status === 'active') aria-label="Bật hoặc ẩn {{ $product->product_name }}"></div><span class="small js-product-status">{{ $statusLabel }}</span></div>
                                        @endif
                                    </td>
                                    <td class="text-center text-nowrap">
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <a href="{{ route('seller.products.edit', $product->product_id) }}" class="btn btn-sm btn-outline-primary rounded-2 action-icon" aria-label="Sửa {{ $product->product_name }}" title="Sửa sản phẩm"><i class="bi bi-pencil-square" aria-hidden="true"></i></a>
                                            <a href="{{ route('store.products.show', $product->product_id) }}" class="btn btn-sm btn-outline-secondary rounded-2 action-icon" aria-label="Xem {{ $product->product_name }} trên cửa hàng" title="Xem ngoài Store" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right" aria-hidden="true"></i></a>
                                            <form method="POST" action="{{ route('seller.products.destroy', $product->product_id) }}" class="d-inline" data-delete-form>@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger rounded-2 action-icon" aria-label="Xóa {{ $product->product_name }}" title="Xóa sản phẩm"><i class="bi bi-trash3" aria-hidden="true"></i></button></form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-secondary py-5"><span class="products-empty-icon mb-3"><i class="bi bi-box-seam fs-3" aria-hidden="true"></i></span><h3 class="h6 fw-bold">Chưa tìm thấy sản phẩm</h3><p class="small mb-3">Thử thay đổi bộ lọc hoặc thêm sản phẩm mới.</p><a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Thêm sản phẩm</a></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($products->hasPages())<div class="px-3 px-lg-4 py-3 border-top" style="background: var(--seller-card-bg, #fff); border-color: var(--seller-border, #e2e8f0) !important;">{{ $products->withQueryString()->links('pagination::bootstrap-5') }}</div>@endif
            </section>
        </div>
    </div>

    <script>
        (() => {
            const root = document.getElementById('sellerProductsPage');
            if (!root) return;
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value || '';
            let timer;
            let controller;

            const rows = () => [...root.querySelectorAll('[data-product-row]')];
            const selected = () => rows().filter(row => row.querySelector('.js-product-select')?.checked);

            function updateSelection() {
                const bar = root.querySelector('#bulkActionBar');
                const boxes = [...root.querySelectorAll('.js-product-select:not(:disabled)')];
                const checked = boxes.filter(box => box.checked).length;
                if (!bar) return;
                root.querySelector('#selectedProductCount').textContent = checked;
                if (checked) {
                    bar.hidden = false;
                    requestAnimationFrame(() => bar.classList.add('is-visible'));
                } else {
                    bar.classList.remove('is-visible');
                    setTimeout(() => { if (!bar.classList.contains('is-visible')) bar.hidden = true; }, 240);
                }
                const all = root.querySelector('#selectAllProducts');
                if (all) { all.checked = boxes.length > 0 && checked === boxes.length; all.indeterminate = checked > 0 && checked < boxes.length; }
            }

            function formUrl() {
                const form = root.querySelector('#productFilterForm');
                const params = new URLSearchParams(new FormData(form));
                for (const [key, value] of [...params.entries()]) if (!value) params.delete(key);
                const url = new URL(form.action, location.origin);
                url.search = params.toString();
                return url;
            }

            async function loadPage(url, replace = false) {
                controller?.abort();
                controller = new AbortController();
                root.classList.add('products-loading');
                try {
                    const response = await fetch(url, {headers: {'Accept': 'text/html', 'X-Requested-With': 'XMLHttpRequest'}, signal: controller.signal});
                    if (!response.ok) throw new Error('Không thể tải danh sách sản phẩm.');
                    const parsed = new DOMParser().parseFromString(await response.text(), 'text/html');
                    const replacement = parsed.querySelector('#sellerProductsContent');
                    const current = root.querySelector('#sellerProductsContent');
                    if (!replacement || !current) throw new Error('Phản hồi danh sách không hợp lệ.');
                    current.replaceWith(replacement);
                    history[replace ? 'replaceState' : 'pushState']({}, '', url);
                    updateSelection();
                } catch (error) {
                    if (error.name !== 'AbortError') location.assign(url);
                } finally { root.classList.remove('products-loading'); }
            }

            root.addEventListener('input', event => {
                if (event.target.id !== 'product-search') return;
                clearTimeout(timer);
                timer = setTimeout(() => loadPage(formUrl(), true), 300);
            });

            root.addEventListener('change', event => {
                if (event.target.id === 'product-category') { loadPage(formUrl()); return; }
                if (event.target.id === 'selectAllProducts') {
                    root.querySelectorAll('.js-product-select:not(:disabled)').forEach(box => box.checked = event.target.checked);
                    updateSelection(); return;
                }
                if (event.target.matches('.js-product-select')) updateSelection();
                if (!event.target.matches('.js-product-visibility')) return;
                const toggle = event.target;
                const old = !toggle.checked;
                toggle.disabled = true;
                fetch(toggle.dataset.url, {method: 'PATCH', headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest'}})
                    .then(async response => { const data = await response.json().catch(() => ({})); if (!response.ok) throw new Error(data.message || 'Không thể đổi trạng thái sản phẩm.'); toggle.checked = data.status === 'active'; toggle.closest('tr').dataset.productStatus = data.status; toggle.closest('td').querySelector('.js-product-status').textContent = data.label; })
                    .catch(error => { toggle.checked = old; alert(error.message); })
                    .finally(() => toggle.disabled = false);
            });

            root.addEventListener('submit', event => {
                if (event.target.matches('[data-delete-form]')) {
                    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) event.preventDefault();
                    return;
                }
                if (event.target.id === 'productFilterForm') { event.preventDefault(); loadPage(formUrl()); }
            });

            root.addEventListener('click', async event => {
                const link = event.target.closest('.js-products-ajax-link, .pagination a');
                if (link?.href) { event.preventDefault(); loadPage(link.href); return; }

                if (event.target.closest('#bulkHideButton')) {
                    const chosen = selected();
                    if (!chosen.length || !confirm(`Ẩn ${chosen.length} sản phẩm đã chọn?`)) return;
                    try {
                        for (const row of chosen.filter(row => row.dataset.productStatus === 'active')) {
                            const response = await fetch(row.dataset.visibilityUrl, {method: 'PATCH', headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest'}});
                            if (!response.ok) throw new Error('Có sản phẩm không thể chuyển sang trạng thái ẩn.');
                        }
                        await loadPage(location.href, true);
                    } catch (error) { alert(error.message); await loadPage(location.href, true); }
                    return;
                }

                if (event.target.closest('#bulkDeleteButton')) {
                    const chosen = selected();
                    if (!chosen.length || !confirm(`Xóa ${chosen.length} sản phẩm đã chọn? Sản phẩm đã phát sinh đơn hàng có thể được chuyển sang trạng thái ẩn.`)) return;
                    try {
                        for (const row of chosen) {
                            const response = await fetch(row.dataset.deleteUrl, {method: 'DELETE', headers: {'Accept': 'text/html, application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest'}});
                            if (!response.ok) throw new Error('Có sản phẩm không thể xóa.');
                        }
                        await loadPage(location.href, true);
                    } catch (error) { alert(error.message); await loadPage(location.href, true); }
                }
            });

            addEventListener('popstate', () => loadPage(location.href, true));
            updateSelection();
        })();
    </script>
@endsection
