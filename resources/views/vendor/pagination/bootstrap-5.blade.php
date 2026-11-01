@php
    $paginationCurrent = $paginator->currentPage();
    $paginationLast = $paginator->lastPage();
    $paginationStart = max(1, min($paginationCurrent - 1, $paginationLast - 2));
    $paginationEnd = min($paginationLast, $paginationStart + 2);
@endphp

@once
    <style>
        .app-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            width: 100%;
        }

        .app-pagination__summary {
            margin: 0;
            color: var(--app-text-muted, var(--seller-text-muted, var(--pl-muted, #64748b)));
            font-size: .84rem;
        }

        .app-pagination__summary strong {
            color: var(--app-text, var(--seller-text, var(--pl-text, #17243b)));
            font-weight: 700;
        }

        .app-pagination .pagination {
            gap: 4px;
            margin: 0;
        }

        .app-pagination .page-link {
            display: inline-flex;
            min-width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
            color: var(--app-text-muted, var(--seller-text-muted, var(--pl-muted, #64748b))) !important;
            background: transparent !important;
            border: 0 !important;
            border-radius: 50% !important;
            font-size: .875rem;
            font-weight: 600;
            line-height: 1;
            box-shadow: none;
            transition: background-color .16s ease, color .16s ease;
        }

        .app-pagination .page-link:hover,
        .app-pagination .page-link:focus-visible {
            color: var(--app-primary, var(--seller-primary, var(--pl-primary, #1a94ff))) !important;
            background: color-mix(in srgb, var(--app-primary, var(--seller-primary, var(--pl-primary, #1a94ff))) 9%, transparent) !important;
        }

        .app-pagination .page-item.active .page-link {
            color: #fff !important;
            background: var(--app-primary, var(--seller-primary, var(--pl-primary, #1a94ff))) !important;
            box-shadow: 0 3px 8px color-mix(in srgb, var(--app-primary, var(--seller-primary, var(--pl-primary, #1a94ff))) 22%, transparent);
        }

        .app-pagination .page-item.disabled .page-link {
            color: var(--app-text-muted, var(--seller-text-muted, var(--pl-muted, #64748b))) !important;
            background: transparent !important;
            opacity: .45;
        }

        @media (max-width: 575.98px) {
            .app-pagination {
                justify-content: center;
            }

            .app-pagination__summary {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endonce

<div class="app-pagination">
    <p class="app-pagination__summary" aria-live="polite">
        Hiển thị
        <strong>{{ number_format($paginator->firstItem() ?? 0) }}–{{ number_format($paginator->lastItem() ?? 0) }}</strong>
        trên {{ number_format($paginator->total()) }} kết quả
    </p>

    <nav aria-label="Phân trang">
        <ul class="pagination">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-label="Trang trước">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Trang trước">&lsaquo;</a>
                </li>
            @endif

            @if ($paginationStart > 1)
                <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}">1</a></li>
                @if ($paginationStart > 2)
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">…</span></li>
                @endif
            @endif

            @for ($page = $paginationStart; $page <= $paginationEnd; $page++)
                @if ($page === $paginationCurrent)
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}" aria-label="Đến trang {{ $page }}">{{ $page }}</a></li>
                @endif
            @endfor

            @if ($paginationEnd < $paginationLast)
                @if ($paginationEnd < $paginationLast - 1)
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">…</span></li>
                @endif
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($paginationLast) }}">{{ $paginationLast }}</a></li>
            @endif

            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Trang sau">&rsaquo;</a></li>
            @else
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-label="Trang sau">&rsaquo;</span></li>
            @endif
        </ul>
    </nav>
</div>
