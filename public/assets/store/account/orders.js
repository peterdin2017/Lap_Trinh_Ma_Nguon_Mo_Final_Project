(() => {
    const pageSelector = '.buyer-orders';
    let navigationController = null;
    let polling = false;

    function showError(message) {
        const page = document.querySelector(pageSelector);
        if (!page) return;

        page.querySelector('.buyer-orders__ajax-error')?.remove();

        const alert = document.createElement('div');
        alert.className = 'alert alert-danger buyer-orders__ajax-error';
        alert.setAttribute('role', 'alert');
        alert.textContent = message;
        page.querySelector('.buyer-orders__heading')?.after(alert);
    }

    function setBusy(page, busy) {
        page?.classList.toggle('buyer-orders--loading', busy);
        if (busy) page?.setAttribute('aria-busy', 'true');
        else page?.removeAttribute('aria-busy');
    }

    async function replacePage(response, fallbackUrl) {
        const html = await response.text();
        const documentFromResponse = new DOMParser().parseFromString(html, 'text/html');
        const nextPage = documentFromResponse.querySelector(pageSelector);

        if (!nextPage && response.redirected && response.url) {
            window.location.assign(response.url);
            return false;
        }

        if (!response.ok || !nextPage) {
            throw new Error('Không thể cập nhật lịch sử đơn hàng. Vui lòng thử lại.');
        }

        const currentPage = document.querySelector(pageSelector);
        if (!currentPage) {
            window.location.assign(fallbackUrl || response.url);
            return false;
        }

        currentPage.replaceWith(nextPage);
        return true;
    }

    async function loadPage(url, { pushState = true } = {}) {
        navigationController?.abort();
        navigationController = new AbortController();

        const currentPage = document.querySelector(pageSelector);
        setBusy(currentPage, true);

        try {
            const response = await fetch(url, {
                method: 'GET',
                credentials: 'same-origin',
                cache: 'no-store',
                headers: { 'Accept': 'text/html', 'X-Requested-With': 'XMLHttpRequest' },
                signal: navigationController.signal,
            });

            const updated = await replacePage(response, url);
            if (updated && pushState) {
                window.history.pushState({}, '', url);
            }
        } catch (error) {
            if (error.name !== 'AbortError') {
                showError(error.message || 'Không thể tải đơn hàng. Vui lòng thử lại.');
            }
        } finally {
            setBusy(document.querySelector(pageSelector), false);
        }
    }

    async function refreshOrderStatuses() {
        const page = document.querySelector(pageSelector);
        if (!page || document.hidden || polling || page.getAttribute('aria-busy') === 'true') {
            return;
        }

        polling = true;

        try {
            const snapshotUrl = new URL(window.location.href);
            snapshotUrl.searchParams.set('refresh', '1');

            const response = await fetch(snapshotUrl, {
                method: 'GET',
                credentials: 'same-origin',
                cache: 'no-store',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!response.ok) return;

            const snapshot = await response.json();
            if (snapshot.fingerprint === page.dataset.ordersFingerprint) return;

            const pageResponse = await fetch(window.location.href, {
                method: 'GET',
                credentials: 'same-origin',
                cache: 'no-store',
                headers: { 'Accept': 'text/html', 'X-Requested-With': 'XMLHttpRequest' },
            });

            await replacePage(pageResponse, window.location.href);
        } catch (error) {
            // Temporary connection errors should not interrupt the order page.
        } finally {
            polling = false;
        }
    }

    document.addEventListener('click', (event) => {
        const target = event.target instanceof Element ? event.target : null;
        const link = target?.closest(
            '.buyer-orders__tabs a, .buyer-orders .pagination a'
        );

        if (!link || event.defaultPrevented || event.button !== 0
            || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        const url = new URL(link.href, window.location.href);
        if (url.origin !== window.location.origin) return;

        event.preventDefault();
        loadPage(url.toString());
    });

    document.addEventListener('submit', async (event) => {
        const form = event.target instanceof HTMLFormElement
            ? event.target.closest('form[data-ajax-order-form]')
            : null;

        if (!form) return;

        event.preventDefault();

        if (form.dataset.confirm && !window.confirm(form.dataset.confirm)) return;
        if (form.dataset.submitting === 'true') return;

        const page = document.querySelector(pageSelector);
        const submitButtons = [...form.querySelectorAll('button[type="submit"]')];
        const disabledBefore = submitButtons.map((button) => button.disabled);

        form.dataset.submitting = 'true';
        submitButtons.forEach((button) => {
            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
        });
        setBusy(page, true);

        try {
            const response = await fetch(form.action, {
                method: (form.method || 'POST').toUpperCase(),
                credentials: 'same-origin',
                headers: { 'Accept': 'text/html', 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(form),
            });

            const updated = await replacePage(response, window.location.href);
            if (updated && response.url) {
                const modal = form.closest('.modal');
                if (modal && window.bootstrap?.Modal) {
                    window.bootstrap.Modal.getOrCreateInstance(modal).hide();
                }

                const responseUrl = new URL(response.url, window.location.href);
                if (responseUrl.origin === window.location.origin) {
                    window.history.replaceState({}, '', responseUrl.toString());
                }
            }
        } catch (error) {
            showError(error.message || 'Không thể gửi yêu cầu. Vui lòng thử lại.');
            form.dataset.submitting = 'false';
            submitButtons.forEach((button, index) => {
                button.disabled = disabledBefore[index];
                button.removeAttribute('aria-busy');
            });
        } finally {
            setBusy(document.querySelector(pageSelector), false);
        }
    });

    window.addEventListener('popstate', () => {
        loadPage(window.location.href, { pushState: false });
    });

    window.setInterval(refreshOrderStatuses, 5000);
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) refreshOrderStatuses();
    });
})();
