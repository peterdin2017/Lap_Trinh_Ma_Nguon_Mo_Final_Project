(() => {
    let liveList = document.querySelector('[data-store-product-live]');

    if (!liveList) return;

    const currentIds = () => [...liveList.querySelectorAll('[data-product-id]')]
        .map((item) => Number(item.dataset.productId));

    const hasChanged = (snapshot) => {
        const shownIds = currentIds();
        const newIds = (snapshot.product_ids || []).map(Number);
        const shownTotal = Number(liveList.dataset.storeProductTotal || 0);

        return shownTotal !== Number(snapshot.total)
            || shownIds.length !== newIds.length
            || shownIds.some((id, index) => id !== newIds[index]);
    };

    let checking = false;

    const refreshProducts = async () => {
        if (checking || document.hidden || !liveList.isConnected) return;

        checking = true;

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
            if (!hasChanged(snapshot)) return;

            const pageResponse = await fetch(window.location.href, {
                credentials: 'same-origin',
                headers: {
                    Accept: 'text/html',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!pageResponse.ok) return;

            const page = new DOMParser().parseFromString(
                await pageResponse.text(),
                'text/html'
            );
            const freshList = page.querySelector(`#${liveList.id}`);

            if (!freshList) return;

            liveList.replaceWith(freshList);
            liveList = freshList;

            const currentCount = document.querySelector('[data-store-product-count]');
            const freshCount = page.querySelector('[data-store-product-count]');

            if (currentCount && freshCount) {
                currentCount.replaceWith(freshCount);
            }
        } catch (error) {
            // A temporary network failure should not interrupt shopping.
        } finally {
            checking = false;
        }
    };

    window.setInterval(refreshProducts, 12000);
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) refreshProducts();
    });
})();
