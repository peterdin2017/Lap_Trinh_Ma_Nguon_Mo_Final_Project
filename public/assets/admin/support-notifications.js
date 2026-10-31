(() => {
    const container = document.querySelector('[data-admin-support-toast-container]');
    const badges = document.querySelectorAll('[data-admin-support-count]');
    if (!container || !badges.length) return;

    const updatesUrl = container.dataset.updatesUrl;
    let initialized = false;
    let latestRequestId = 0;
    let requestInProgress = false;
    let activeToast = null;

    const topicLabels = {
        account: 'Tài khoản',
        order: 'Đơn hàng',
        payment: 'Thanh toán',
        shipping: 'Vận chuyển',
        return: 'Đổi trả và hoàn tiền',
        product: 'Sản phẩm',
        other: 'Vấn đề khác',
    };

    function updateBadges(total) {
        const count = Math.max(0, Number(total) || 0);
        const label = count > 99 ? '99+' : String(count);

        badges.forEach((badge) => {
            if (badge.textContent !== label) badge.textContent = label;
            badge.classList.toggle('d-none', count === 0);
            const accessibleLabel = `${count} yêu cầu hỗ trợ đang chờ`;
            if (badge.getAttribute('aria-label') !== accessibleLabel) {
                badge.setAttribute('aria-label', accessibleLabel);
            }
        });
    }

    function showToast(supportRequest, newCount = 1) {
        if (activeToast) {
            bootstrap.Toast.getInstance(activeToast)?.dispose();
            activeToast.remove();
        }

        const toast = document.createElement('div');
        toast.className = 'toast admin-support-toast';
        toast.setAttribute('role', 'status');
        toast.setAttribute('aria-live', 'polite');
        toast.setAttribute('aria-atomic', 'true');

        const header = document.createElement('div');
        header.className = 'toast-header';

        const icon = document.createElement('i');
        icon.className = 'bi bi-headset text-primary';
        icon.setAttribute('aria-hidden', 'true');

        const title = document.createElement('strong');
        title.className = 'me-auto small';
        title.textContent = newCount > 1
            ? `${newCount} yêu cầu hỗ trợ mới`
            : 'Yêu cầu hỗ trợ mới';

        const close = document.createElement('button');
        close.type = 'button';
        close.className = 'btn-close';
        close.setAttribute('data-bs-dismiss', 'toast');
        close.setAttribute('aria-label', 'Đóng thông báo');
        header.append(icon, title, close);

        const body = document.createElement('div');
        body.className = 'toast-body';

        const customer = document.createElement('strong');
        customer.className = 'admin-support-toast-customer';
        customer.textContent = supportRequest.customer || 'Khách hàng';

        const topic = document.createElement('span');
        topic.className = 'admin-support-toast-topic';
        const role = supportRequest.requester_role === 'seller' ? 'Người bán' : 'Người mua';
        topic.textContent = `${role} · ${topicLabels[supportRequest.topic] || supportRequest.topic || 'Hỗ trợ'}`;

        const message = document.createElement('p');
        message.className = 'admin-support-toast-message';
        message.textContent = supportRequest.message || '';

        const link = document.createElement('a');
        link.className = 'btn btn-sm btn-primary';
        link.href = supportRequest.url;
        link.textContent = 'Xem yêu cầu';

        body.append(customer, topic, message, link);
        toast.append(header, body);
        container.replaceChildren(toast);
        activeToast = toast;

        toast.addEventListener('hidden.bs.toast', () => {
            if (activeToast === toast) activeToast = null;
            toast.remove();
        }, { once: true });

        bootstrap.Toast.getOrCreateInstance(toast, { autohide: true, delay: 5000 }).show();
    }

    async function refresh() {
        if (requestInProgress || !updatesUrl) return;
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
            const requests = Array.isArray(data.requests) ? data.requests : [];
            updateBadges(data.open_total);
            const responseLatestId = Number(data.latest_request_id) || 0;

            if (!initialized) {
                latestRequestId = responseLatestId;
                initialized = true;
                return;
            }

            const newRequests = requests.filter((item) => Number(item.id) > latestRequestId);
            latestRequestId = Math.max(latestRequestId, responseLatestId);

            if (newRequests.length) showToast(newRequests[0], newRequests.length);
        } catch {
            // Keep the last badge state while the admin API is temporarily unavailable.
        } finally {
            requestInProgress = false;
        }
    }

    refresh();
    window.setInterval(refresh, 4000);
})();
