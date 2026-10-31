document.addEventListener('DOMContentLoaded', () => {
    const config = document.getElementById('cart-config');
    const csrfToken = document.getElementById('cart-csrf-token')?.value;
    const cartLayout = document.getElementById('cart-layout');
    const emptyState = document.getElementById('cart-empty-state');
    const mobileContinue = document.getElementById('mobile-continue-shopping');
    const storesContainer = document.getElementById('cart-stores');
    const selectAll = document.getElementById('select-all');

    if (!config || !storesContainer || !selectAll) {
        return;
    }

    const deleteSelectedButton = document.getElementById('delete-selected');
    const selectedDeleteCount = document.getElementById('selected-delete-count');
    const cartItemCount = document.getElementById('cart-item-count');
    const cartTotalQuantity = document.getElementById('cart-total-quantity');
    const selectedCount = document.getElementById('summary-selected-count');
    const subtotalOutput = document.getElementById('summary-subtotal');
    const discountOutput = document.getElementById('summary-discount');
    const totalOutput = document.getElementById('summary-total');
    const checkoutButton = document.getElementById('checkout-button');
    const feedback = document.getElementById('cart-feedback');
    const confirmModalElement = document.getElementById('cart-confirm-modal');
    const confirmMessage = document.getElementById('cart-confirm-message');
    const confirmDeleteButton = document.getElementById('confirm-cart-delete');
    const voucherForm = document.getElementById('voucher-form');
    const voucherFeedback = document.getElementById('voucher-feedback');

    let pendingDelete = null;
    let confirmModal = null;

    const formatCurrency = (amount) =>
        new Intl.NumberFormat('vi-VN').format(Math.round(amount)) + '₫';

    function showFeedback(message, type = 'success') {
        feedback.textContent = message;
        feedback.className = `alert alert-${type} cart-feedback`;
        feedback.classList.remove('d-none');
    }

    function getRows() {
        return Array.from(storesContainer.querySelectorAll('[data-cart-item]'));
    }

    function getSelectableCheckboxes(container = storesContainer) {
        return Array.from(container.querySelectorAll('.cart-item-checkbox'))
            .filter((checkbox) => !checkbox.disabled);
    }

    function getSelectedRows() {
        return getRows().filter((row) => {
            const checkbox = row.querySelector('.cart-item-checkbox');
            return checkbox.checked && !checkbox.disabled;
        });
    }

    function syncStoreCheckboxes() {
        storesContainer.querySelectorAll('[data-store-card]').forEach((storeCard) => {
            const storeCheckbox = storeCard.querySelector('.store-checkbox');
            const checkboxes = getSelectableCheckboxes(storeCard);
            const checkedCount = checkboxes.filter((checkbox) => checkbox.checked).length;

            storeCheckbox.disabled = checkboxes.length === 0;
            storeCheckbox.checked = checkboxes.length > 0 && checkedCount === checkboxes.length;
            storeCheckbox.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        });
    }

    function updateSummary() {
        const rows = getRows();
        const selectedRows = getSelectedRows();
        const selectableRows = getSelectableCheckboxes();

        const subtotal = selectedRows.reduce(
            (sum, row) => sum + Number(row.dataset.subtotal || 0),
            0
        );

        const totalQuantity = rows.reduce(
            (sum, row) => sum + Number(row.querySelector('.cart-quantity-input').value || 0),
            0
        );

        updateHeaderCartCount(totalQuantity);

        syncStoreCheckboxes();

        selectedCount.textContent = selectedRows.length;
        subtotalOutput.textContent = formatCurrency(subtotal);
        discountOutput.textContent = formatCurrency(0);
        totalOutput.textContent = formatCurrency(subtotal);
        checkoutButton.disabled = selectedRows.length === 0;
        deleteSelectedButton.classList.toggle('d-none', selectedRows.length === 0);
        selectedDeleteCount.textContent = selectedRows.length;
        cartItemCount.textContent = rows.length;
        cartTotalQuantity.textContent = totalQuantity;

        selectAll.disabled = selectableRows.length === 0;
        selectAll.checked = selectableRows.length > 0
            && selectedRows.length === selectableRows.length;
        selectAll.indeterminate = selectedRows.length > 0
            && selectedRows.length < selectableRows.length;

        storesContainer.querySelectorAll('[data-store-card]').forEach((storeCard) => {
            if (storeCard.querySelectorAll('[data-cart-item]').length === 0) {
                storeCard.remove();
            }
        });

        if (rows.length === 0) {
            cartLayout.classList.add('d-none');
            emptyState.classList.remove('d-none');
            mobileContinue?.classList.add('d-none');
        }
    }

    function updateHeaderCartCount(quantity) {
        const badge = document.getElementById('store-cart-count');
        const cartLink = badge?.closest('a');

        if (!badge) {
            return;
        }

        const count = Math.max(0, Number(quantity) || 0);
        badge.textContent = count > 99 ? '99+' : String(count);
        badge.hidden = count === 0;
        cartLink?.setAttribute('aria-label', `Giỏ hàng, ${count} món`);
    }

    async function requestJson(url, method, payload = null) {
        const options = {
            method,
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
            },
        };

        if (payload !== null) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(payload);
        }

        const response = await fetch(url, options);
        let data = {};

        try {
            data = await response.json();
        } catch {
            throw new Error('Không đọc được phản hồi từ máy chủ. Vui lòng tải lại trang.');
        }

        if (!response.ok) {
            const validationMessage = Object.values(data.errors || {}).flat()[0];
            throw new Error(validationMessage || data.message || 'Thao tác chưa thực hiện được.');
        }

        return data;
    }

    function updateQuantityButtons(row, quantity, stock) {
        const decrementButton = row.querySelector('[data-step="-1"]');
        const incrementButton = row.querySelector('[data-step="1"]');
        const input = row.querySelector('.cart-quantity-input');

        input.value = quantity;
        input.disabled = false;
        input.max = stock;
        row.dataset.stock = stock;
        decrementButton.disabled = quantity <= 1;
        incrementButton.disabled = quantity >= stock;
    }

    async function saveQuantity(row, requestedQuantity) {
        const oldQuantity = Number(row.dataset.quantity);
        const stock = Number(row.dataset.stock);
        const quantity = Math.max(1, Math.min(stock, Number(requestedQuantity) || 1));
        const controls = Array.from(row.querySelectorAll('button, input'));

        if (quantity === oldQuantity) {
            updateQuantityButtons(row, oldQuantity, stock);
            return;
        }

        controls.forEach((control) => {
            control.disabled = true;
        });

        try {
            const data = await requestJson(row.dataset.updateUrl, 'PATCH', { quantity });
            const item = data.item;

            updateHeaderCartCount(data.cart_quantity);

            row.dataset.unitPrice = item.unit_price;
            row.dataset.quantity = item.quantity;
            row.dataset.subtotal = item.subtotal;

            row.querySelectorAll('[data-unit-price-display]').forEach((element) => {
                element.textContent = formatCurrency(item.unit_price);
            });

            row.querySelectorAll('[data-line-subtotal]').forEach((element) => {
                element.textContent = formatCurrency(item.subtotal);
            });

            updateQuantityButtons(row, item.quantity, item.stock_quantity);
            showFeedback('Đã cập nhật số lượng sản phẩm.', 'success');
        } catch (error) {
            updateQuantityButtons(row, oldQuantity, stock);
            showFeedback(error.message, 'danger');
        } finally {
            const checkbox = row.querySelector('.cart-item-checkbox');
            const deleteButtons = row.querySelectorAll('[data-delete-item]');

            checkbox.disabled = false;
            deleteButtons.forEach((button) => {
                button.disabled = false;
            });
        }

        updateSummary();
    }

    function askToDelete(action) {
        pendingDelete = action;
        confirmMessage.textContent = action.type === 'bulk'
            ? `Bạn có chắc muốn xóa ${action.keys.length} sản phẩm đã chọn khỏi giỏ hàng?`
            : 'Sản phẩm này sẽ được xóa khỏi giỏ hàng.';

        if (window.bootstrap?.Modal && confirmModalElement) {
            confirmModal ??= new window.bootstrap.Modal(confirmModalElement);
            confirmModal.show();
        } else if (window.confirm(confirmMessage.textContent)) {
            void confirmDelete();
        }
    }

    async function confirmDelete() {
        if (!pendingDelete) {
            return;
        }

        const action = pendingDelete;
        pendingDelete = null;
        if (confirmDeleteButton) {
            confirmDeleteButton.disabled = true;
        }

        try {
            let data;

            if (action.type === 'bulk') {
                data = await requestJson(config.dataset.bulkDeleteUrl, 'DELETE', {
                    keys: action.keys,
                });

                action.keys.forEach((key) => {
                    storesContainer
                        .querySelector(`[data-item-key="${CSS.escape(key)}"]`)
                        ?.remove();
                });
            } else {
                data = await requestJson(action.url, 'DELETE');
                action.row.remove();
            }

            showFeedback(data.message || 'Đã xóa sản phẩm khỏi giỏ hàng.', 'success');
            updateHeaderCartCount(data.cart_quantity);
            updateSummary();
        } catch (error) {
            showFeedback(error.message, 'danger');
        } finally {
            if (confirmDeleteButton) {
                confirmDeleteButton.disabled = false;
            }
            confirmModal?.hide();
        }
    }

    selectAll.addEventListener('change', () => {
        getSelectableCheckboxes().forEach((checkbox) => {
            checkbox.checked = selectAll.checked;
        });

        updateSummary();
    });

    storesContainer.addEventListener('change', (event) => {
        if (event.target.matches('.store-checkbox')) {
            const storeCard = event.target.closest('[data-store-card]');

            getSelectableCheckboxes(storeCard).forEach((checkbox) => {
                checkbox.checked = event.target.checked;
            });

            updateSummary();
        }

        if (event.target.matches('.cart-item-checkbox')) {
            updateSummary();
        }

        if (event.target.matches('.cart-quantity-input')) {
            saveQuantity(event.target.closest('[data-cart-item]'), event.target.value);
        }
    });

    storesContainer.addEventListener('click', (event) => {
        const stepButton = event.target.closest('.quantity-step');

        if (stepButton) {
            const row = stepButton.closest('[data-cart-item]');
            const input = row.querySelector('.cart-quantity-input');
            saveQuantity(row, Number(input.value) + Number(stepButton.dataset.step));
            return;
        }

        const deleteButton = event.target.closest('[data-delete-item]');

        if (deleteButton) {
            const row = deleteButton.closest('[data-cart-item]');
            askToDelete({ type: 'single', row, url: row.dataset.deleteUrl });
        }
    });

    deleteSelectedButton?.addEventListener('click', () => {
        const keys = getSelectedRows().map((row) => row.dataset.itemKey);

        if (keys.length > 0) {
            askToDelete({ type: 'bulk', keys });
        }
    });

    confirmDeleteButton?.addEventListener('click', confirmDelete);

    voucherForm?.addEventListener('submit', (event) => {
        event.preventDefault();
        const code = document.getElementById('voucher-code').value.trim();

        voucherFeedback.textContent = code
            ? 'Chức năng áp dụng voucher chưa được kết nối.'
            : 'Vui lòng nhập mã giảm giá.';
        voucherFeedback.className = 'small mt-2 text-warning';
    });

    checkoutButton?.addEventListener('click', () => {
        if (getSelectedRows().length > 0) {
            showFeedback(
                'Luồng thanh toán chưa được kết nối. Sản phẩm đã chọn vẫn được giữ trong giỏ.',
                'info'
            );
        }
    });

    updateSummary();
});
