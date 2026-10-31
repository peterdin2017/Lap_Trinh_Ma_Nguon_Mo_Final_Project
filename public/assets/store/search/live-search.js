(() => {
    const form = document.querySelector('.shop-header__search');
    const input = form?.querySelector('input[name="q"]');
    const suggestions = document.getElementById('store-search-suggestions');
    if (!form || !input || !suggestions) return;

    let debounceTimer = null;
    let controller = null;
    let latestQuery = '';
    let composing = false;

    const priceFormatter = new Intl.NumberFormat('vi-VN', {
        maximumFractionDigits: 0,
    });

    function setOpen(open) {
        suggestions.classList.toggle('d-none', !open);
        input.setAttribute('aria-expanded', String(open));
    }

    function addState(message) {
        const state = document.createElement('div');
        state.className = 'shop-search-suggestions__state';
        state.textContent = message;
        suggestions.append(state);
    }

    function buildSearchUrl(query) {
        const url = new URL(form.action, window.location.origin);
        url.search = '';
        url.searchParams.set('q', query);
        return url;
    }

    function appendAllResultsLink(query) {
        const link = document.createElement('a');
        link.className = 'shop-search-suggestions__all';
        link.href = buildSearchUrl(query).toString();
        link.textContent = `Xem tất cả kết quả cho “${query}”`;
        suggestions.append(link);
    }

    function renderProducts(products, query) {
        suggestions.replaceChildren();

        if (!products.length) {
            addState('Không tìm thấy sản phẩm phù hợp.');
            appendAllResultsLink(query);
            setOpen(true);
            return;
        }

        products.forEach((product) => {
            const link = document.createElement('a');
            link.className = 'shop-search-suggestions__item';
            link.href = product.url;
            link.setAttribute('role', 'option');
            link.tabIndex = -1;

            const imageWrap = document.createElement('span');
            imageWrap.className = 'shop-search-suggestions__image';
            if (product.image_url) {
                const image = document.createElement('img');
                image.src = product.image_url;
                image.alt = '';
                image.loading = 'lazy';
                image.addEventListener('error', () => {
                    imageWrap.replaceChildren();
                    const icon = document.createElement('i');
                    icon.className = 'bi bi-image';
                    icon.setAttribute('aria-hidden', 'true');
                    imageWrap.append(icon);
                }, { once: true });
                imageWrap.append(image);
            } else {
                const icon = document.createElement('i');
                icon.className = 'bi bi-image';
                icon.setAttribute('aria-hidden', 'true');
                imageWrap.append(icon);
            }

            const copy = document.createElement('span');
            copy.className = 'shop-search-suggestions__copy';
            const name = document.createElement('strong');
            name.className = 'shop-search-suggestions__name';
            name.textContent = product.name || 'Sản phẩm';
            const meta = document.createElement('small');
            meta.className = 'shop-search-suggestions__meta';
            meta.textContent = [product.brand, product.shop_name || product.category_name]
                .filter(Boolean)
                .join(' · ');
            copy.append(name, meta);

            const price = document.createElement('span');
            price.className = 'shop-search-suggestions__price';
            price.textContent = `${priceFormatter.format(Number(product.price) || 0)}₫`;

            link.append(imageWrap, copy, price);
            suggestions.append(link);
        });

        appendAllResultsLink(query);
        setOpen(true);
    }

    async function search(query) {
        controller?.abort();
        controller = new AbortController();
        const currentController = controller;
        input.setAttribute('aria-busy', 'true');
        suggestions.replaceChildren();
        addState('Đang tìm sản phẩm...');
        setOpen(true);

        try {
            const response = await fetch(buildSearchUrl(query), {
                method: 'GET',
                cache: 'no-store',
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                signal: currentController.signal,
            });

            if (!response.ok) throw new Error('Không thể tải gợi ý sản phẩm.');

            const data = await response.json();
            if (query !== latestQuery || currentController !== controller) return;
            renderProducts(Array.isArray(data.products) ? data.products : [], query);
        } catch (error) {
            if (error.name === 'AbortError') return;
            if (query !== latestQuery) return;
            suggestions.replaceChildren();
            addState('Chưa thể tải gợi ý. Nhấn Enter để tìm kiếm.');
            appendAllResultsLink(query);
            setOpen(true);
        } finally {
            if (currentController === controller) {
                input.removeAttribute('aria-busy');
            }
        }
    }

    function scheduleSearch() {
        window.clearTimeout(debounceTimer);
        const query = input.value.trim();
        latestQuery = query;

        if (query.length < 2) {
            controller?.abort();
            suggestions.replaceChildren();
            setOpen(false);
            input.removeAttribute('aria-busy');
            return;
        }

        debounceTimer = window.setTimeout(() => search(query), 250);
    }

    input.addEventListener('compositionstart', () => { composing = true; });
    input.addEventListener('compositionend', () => {
        composing = false;
        scheduleSearch();
    });
    input.addEventListener('input', () => {
        if (!composing) scheduleSearch();
    });
    input.addEventListener('focus', () => {
        if (input.value.trim().length >= 2 && suggestions.classList.contains('d-none')) {
            scheduleSearch();
        }
    });
    input.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowDown' && !suggestions.classList.contains('d-none')) {
            const first = suggestions.querySelector('a[role="option"]');
            if (first) {
                event.preventDefault();
                first.focus();
            }
        } else if (event.key === 'Escape') {
            setOpen(false);
        }
    });

    suggestions.addEventListener('keydown', (event) => {
        const options = [...suggestions.querySelectorAll('a[role="option"]')];
        const index = options.indexOf(document.activeElement);

        if (event.key === 'ArrowDown' && index < options.length - 1) {
            event.preventDefault();
            options[index + 1]?.focus();
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            if (index <= 0) input.focus();
            else options[index - 1]?.focus();
        } else if (event.key === 'Escape') {
            event.preventDefault();
            setOpen(false);
            input.focus();
        }
    });

    document.addEventListener('click', (event) => {
        if (!form.contains(event.target)) setOpen(false);
    });
})();
