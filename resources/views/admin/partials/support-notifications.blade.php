<style>
    .admin-nav-link > .admin-support-count-badge {
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

    .admin-nav-link > .admin-support-count-badge:not(.d-none) {
        animation: admin-support-pulse 1.8s ease-in-out infinite;
    }

    @keyframes admin-support-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(255, 66, 78, .22); }
        50% { box-shadow: 0 0 0 5px rgba(255, 66, 78, 0); }
    }

    .admin-support-toast-container {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 1090;
        width: min(380px, calc(100vw - 32px));
    }

    .admin-support-toast {
        width: 100%;
        color: #0F172A;
        background: #fff;
        border: 1px solid #E2E8F0 !important;
        border-radius: 12px;
        box-shadow: 0 12px 36px rgba(15, 23, 42, .18);
        transform: translateY(18px);
        transition: opacity .22s ease, transform .28s ease;
    }

    .admin-support-toast.showing,
    .admin-support-toast.show { transform: translateY(0); }

    .admin-support-toast .toast-header {
        gap: 8px;
        color: #0F172A;
        background: #fff;
        border-bottom-color: #E2E8F0;
        border-radius: 12px 12px 0 0;
    }

    .admin-support-toast .toast-body { padding: 14px; }
    .admin-support-toast-customer { display: block; margin-bottom: 3px; font-size: 13px; }
    .admin-support-toast-topic { display: block; margin-bottom: 8px; color: #64748B; font-size: 12px; }
    .admin-support-toast-message {
        display: -webkit-box;
        overflow: hidden;
        margin: 0 0 12px;
        color: #475569;
        font-size: 13px;
        overflow-wrap: anywhere;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 3;
    }

    @media (max-width: 575.98px) {
        .admin-support-toast-container { right: 12px; bottom: 12px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .admin-nav-link > .admin-support-count-badge:not(.d-none) { animation: none; }
        .admin-support-toast { transition: none; }
    }
</style>

<div class="admin-support-toast-container"
     data-admin-support-toast-container
     data-updates-url="{{ route('admin.support.updates') }}"
     aria-live="assertive"></div>
