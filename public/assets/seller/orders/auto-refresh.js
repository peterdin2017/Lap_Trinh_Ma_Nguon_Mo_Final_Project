(() => {
    let page = document.getElementById('sellerOrdersPage');
    if (!page) return;

    let loading = false;

    async function refreshOrders() {
        if (loading || document.hidden || !page.isConnected) return;

        const focused = document.activeElement;
        if (focused instanceof Element && focused.closest('.seller-orders__filter')) return;
        if (page.querySelector('.modal.show')) return;

        loading = true;

        try {
            const snapshotUrl = new URL(window.location.href);
            snapshotUrl.searchParams.set('refresh', '1');

            const snapshotResponse = await fetch(snapshotUrl, {
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!snapshotResponse.ok) return;

            const snapshot = await snapshotResponse.json();
            if (!snapshot.version || snapshot.version === page.dataset.ordersVersion) return;

            const pageResponse = await fetch(window.location.href, {
                credentials: 'same-origin',
                headers: {
                    Accept: 'text/html',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!pageResponse.ok) return;

            const html = await pageResponse.text();
            const parsed = new DOMParser().parseFromString(html, 'text/html');
            const freshPage = parsed.getElementById('sellerOrdersPage');

            if (!freshPage) return;

            page.replaceWith(freshPage);
            page = freshPage;
        } catch (error) {
            // A temporary network failure should not interrupt order management.
        } finally {
            loading = false;
        }
    }

    window.setInterval(refreshOrders, 12000);
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) refreshOrders();
    });
})();
