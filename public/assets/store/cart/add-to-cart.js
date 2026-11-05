(() => {
    let toastTimer;

    function setCartQuantity(quantity) {
        const badge = document.getElementById('store-cart-count');
        const cartLink = badge?.closest('a');

        if (!badge) return;

        const count = Math.max(0, Number(quantity) || 0);
        badge.textContent = count > 99 ? '99+' : String(count);
        badge.hidden = count === 0;
        cartLink?.setAttribute('aria-label', `Giỏ hàng, ${count} món`);
    }

    function showToast(message, isError = false) {
        let toast = document.getElementById('store-action-toast');

        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'store-action-toast';
            toast.className = 'store-action-toast';
            toast.setAttribute('role', 'status');
            toast.setAttribute('aria-live', 'polite');
            document.body.appendChild(toast);
        }

        toast.textContent = message;
        toast.classList.toggle('store-action-toast--error', isError);
        toast.classList.add('is-visible');
        window.clearTimeout(toastTimer);
        toastTimer = window.setTimeout(() => toast.classList.remove('is-visible'), 3000);
    }

    function validateVariant(form) {
        const variants = form.querySelectorAll('.variant-option');
        const selectedVariant = form.querySelector('#selectedVariantId');

        if (variants.length === 0 || selectedVariant?.value) return true;

        const hint = form.querySelector('#clientHint');
        if (hint) {
            hint.textContent = 'Vui lòng chọn mẫu mã sản phẩm.';
            hint.hidden = false;
        }

        form.querySelector('.variant-list')?.scrollIntoView({
            behavior: 'smooth',
            block: 'center',
        });

        return false;
    }

    async function addFormToCart(form, submitter) {
        if (form.dataset.submitting === 'true') return;

        if (!validateVariant(form)) return;

        const quantityInput = form.querySelector('[name="quantity"]');
        if (quantityInput && !quantityInput.checkValidity()) {
            quantityInput.reportValidity();
            return;
        }

        const payload = new FormData(form);
        payload.set('intent', 'cart');

        const token = form.querySelector('[name="_token"]')?.value
            || document.querySelector('meta[name="csrf-token"]')?.content;
        const buttons = [...form.querySelectorAll('button[type="submit"]')];
        const disabledStates = buttons.map((button) => button.disabled);

        form.dataset.submitting = 'true';
        form.classList.add('is-cart-submitting');
        buttons.forEach((button) => {
            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
        });

        try {
            const response = await fetch(form.action, {
                method: (form.method || 'POST').toUpperCase(),
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(token ? { 'X-CSRF-TOKEN': token } : {}),
                },
                body: payload,
            });

            const data = await response.json().catch(() => null);
            if (!data) {
                throw new Error('Máy chủ không trả về dữ liệu hợp lệ. Vui lòng thử lại.');
            }

            if (!response.ok) {
                if (response.status === 401 && data.login_url) {
                    window.location.assign(data.login_url);
                    return;
                }

                const validationMessage = Object.values(data.errors || {}).flat()[0];
                throw new Error(validationMessage || data.message || 'Không thể thêm sản phẩm vào giỏ.');
            }

            setCartQuantity(data.cart_quantity);
            showToast(data.message || 'Đã thêm sản phẩm vào giỏ hàng.');
        } catch (error) {
            showToast(error.message || 'Có lỗi xảy ra. Vui lòng thử lại.', true);
        } finally {
            form.dataset.submitting = 'false';
            form.classList.remove('is-cart-submitting');
            buttons.forEach((button, index) => {
                button.disabled = disabledStates[index];
                button.removeAttribute('aria-busy');
            });
        }
    }

    // Bắt cú nhấp trước hành động mặc định của nút submit.
    document.addEventListener('click', (event) => {
        const target = event.target instanceof Element ? event.target : null;
        const button = target?.closest('button[type="submit"][name="intent"][value="cart"]');
        const form = button?.form;

        if (!form?.matches('[data-cart-ajax-form]')) return;

        event.preventDefault();
        addFormToCart(form, button);
    }, true);

    // Hỗ trợ gửi form bằng Enter hoặc requestSubmit; “Mua ngay” vẫn gửi bình thường.
    document.addEventListener('submit', (event) => {
        const form = event.target instanceof HTMLFormElement
            ? event.target.closest('[data-cart-ajax-form]')
            : null;

        if (!form) return;

        const submitter = event.submitter;
        const intent = submitter?.name === 'intent'
            ? submitter.value
            : form.querySelector('[name="intent"]')?.value || 'cart';

        if (intent === 'buy_now') return;

        event.preventDefault();
        addFormToCart(form, submitter);
    }, true);
})();
