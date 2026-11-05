(() => {
    const container = document.querySelector('[data-admin-support-notifications]');
    const supportBadges = document.querySelectorAll('[data-admin-support-count]');
    const reportBadges = document.querySelectorAll('[data-admin-report-count]');
    if (!container || (!supportBadges.length && !reportBadges.length)) return;

    const updatesUrl = container.dataset.updatesUrl;
    let requestInProgress = false;

    function updateBadges(badges, total, description) {
        const count = Math.max(0, Number(total) || 0);
        const text = count > 99 ? '99+' : String(count);

        badges.forEach((badge) => {
            badge.textContent = text;
            badge.classList.toggle('d-none', count === 0);
            badge.setAttribute('aria-label', `${count} ${description} đang chờ xử lý`);
        });
    }

    async function refresh() {
        if (requestInProgress || document.hidden || !updatesUrl) return;
        requestInProgress = true;

        try {
            const response = await fetch(updatesUrl, {
                cache: 'no-store',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });

            if (!response.ok) return;

            const data = await response.json();
            updateBadges(supportBadges, data.open_total, 'yêu cầu hỗ trợ');
            updateBadges(reportBadges, data.pending_report_total, 'báo cáo vi phạm');
        } catch {
            // Giữ nguyên số badge nếu kết nối tạm thời bị gián đoạn.
        } finally {
            requestInProgress = false;
        }
    }

    refresh();
    window.setInterval(refresh, 5000);
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) refresh();
    });
})();
    