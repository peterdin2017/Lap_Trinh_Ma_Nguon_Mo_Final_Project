<footer class="admin-footer px-3 px-lg-4 py-3">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 small">
        <span>© {{ date('Y') }} Admin Center · Website Bán hàng Online</span>
        <span>
            <i class="bi bi-shield-check me-1" aria-hidden="true"></i>
            Khu vực quản trị
        </span>
    </div>
</footer>

<style>
    .admin-footer {
        color: var(--app-text-muted);
        background-color: var(--app-card-bg);
        border-top: 1px solid var(--app-border);
    }

    .admin-footer .bi-shield-check {
        color: var(--app-primary);
    }
</style>
