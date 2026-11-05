<style>
    .admin-nav-link > .admin-count-badge,
    .admin-subnav-link > .admin-count-badge {
        display: inline-flex;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        min-width: 26px;
        height: 26px;
        margin-left: auto;
        padding: 0 6px;
        color: #fff;
        background: #FF424E;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        box-shadow: 0 2px 6px rgba(255, 66, 78, .22);
    }

    .admin-nav-link > .admin-count-badge:not(.d-none),
    .admin-subnav-link > .admin-count-badge:not(.d-none) {
        animation: admin-support-pulse 1.8s ease-in-out infinite;
    }

    .admin-nav-toggle[aria-expanded="true"] > .admin-report-parent-badge {
        display: none !important;
    }

    @keyframes admin-support-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(255, 66, 78, .22); }
        50% { box-shadow: 0 0 0 5px rgba(255, 66, 78, 0); }
    }

    @media (prefers-reduced-motion: reduce) {
        .admin-nav-link > .admin-count-badge:not(.d-none),
        .admin-subnav-link > .admin-count-badge:not(.d-none) { animation: none; }
    }
</style>

<div class="d-none"
     data-admin-support-notifications
     data-updates-url="{{ route('admin.support.updates') }}"
     aria-hidden="true"></div>
