@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkoutForm = document.getElementById('checkout-submit-form');

            document.querySelectorAll('[data-voucher-picker-modal]').forEach((modal) => {
                const sellerId = modal.dataset.sellerId;
                const hiddenInput = document.getElementById(`voucher-code-${sellerId}`);
                const trigger = document.getElementById(`voucher-picker-trigger-${sellerId}`);
                const triggerLabel = trigger?.querySelector('[data-voucher-trigger-label]');
                const choiceRadios = modal.querySelectorAll('[data-voucher-choice]');
                const confirmButton = modal.querySelector('[data-confirm-voucher]');

                const syncSelectedStyle = () => {
                    choiceRadios.forEach((radio) => {
                        radio.closest('.checkout-voucher-option')?.classList.toggle(
                            'is-selected',
                            radio.checked
                        );
                    });
                };

                modal.addEventListener('show.bs.modal', () => {
                    choiceRadios.forEach((radio) => {
                        radio.checked = radio.value === (hiddenInput?.value ?? '');
                    });

                    syncSelectedStyle();
                });

                choiceRadios.forEach((radio) => {
                    radio.addEventListener('change', syncSelectedStyle);
                });

                confirmButton?.addEventListener('click', () => {
                    const selected = modal.querySelector('[data-voucher-choice]:checked');

                    if (!hiddenInput || !checkoutForm) {
                        return;
                    }

                    hiddenInput.value = selected?.value ?? '';

                    if (triggerLabel) {
                        triggerLabel.textContent = selected?.dataset.label || 'Chọn voucher';
                    }

                    window.bootstrap?.Modal.getOrCreateInstance(modal).hide();

                    const applyButton = checkoutForm.querySelector(
                        'button[name="intent"][value="apply_vouchers"]'
                    );

                    if (applyButton) {
                        checkoutForm.requestSubmit(applyButton);
                    }
                });
            });

            const platformModal = document.getElementById(
                'platform-voucher-picker-modal'
            );

            if (platformModal) {
                const platformInput = document.getElementById('platform-voucher-code');
                const platformTrigger = document.getElementById(
                    'platform-voucher-picker-trigger'
                );
                const platformTriggerLabel = platformTrigger?.querySelector(
                    '[data-platform-voucher-trigger-label]'
                );
                const radios = platformModal.querySelectorAll(
                    '[data-platform-voucher-choice]'
                );
                const confirmButton = platformModal.querySelector(
                    '[data-confirm-platform-voucher]'
                );

                const syncSelectedStyle = () => {
                    radios.forEach((radio) => {
                        radio.closest('.checkout-voucher-option')?.classList.toggle(
                            'is-selected',
                            radio.checked
                        );
                    });
                };

                platformModal.addEventListener('show.bs.modal', () => {
                    radios.forEach((radio) => {
                        radio.checked = radio.value === (platformInput?.value ?? '');
                    });

                    syncSelectedStyle();
                });

                radios.forEach((radio) => {
                    radio.addEventListener('change', syncSelectedStyle);
                });

                confirmButton?.addEventListener('click', () => {
                    const selected = platformModal.querySelector(
                        '[data-platform-voucher-choice]:checked'
                    );

                    if (!platformInput || !checkoutForm) {
                        return;
                    }

                    platformInput.value = selected?.value ?? '';

                    if (platformTriggerLabel) {
                        platformTriggerLabel.textContent =
                            selected?.dataset.label || 'Chọn voucher sàn';
                    }

                    window.bootstrap?.Modal.getOrCreateInstance(platformModal).hide();

                    const applyButton = checkoutForm.querySelector(
                        'button[name="intent"][value="apply_vouchers"]'
                    );

                    if (applyButton) {
                        checkoutForm.requestSubmit(applyButton);
                    }
                });
            }

            const modal = document.getElementById('checkout-address-modal');

            if (!modal) {
                return;
            }

            const fields = {
                shipping_name: document.getElementById('shipping_name'),
                shipping_phone: document.getElementById('shipping_phone'),
                street_address: document.getElementById('street_address'),
                ward: document.getElementById('ward'),
                district: document.getElementById('district'),
                province: document.getElementById('province'),
            };

            const currentAddressName = document.getElementById('current-address-name');
            const currentAddressPhone = document.getElementById('current-address-phone');
            const currentAddressText = document.getElementById('current-address-text');
            const currentAddressBadge = document.getElementById('current-address-badge');

            modal.querySelectorAll('[data-checkout-address]').forEach((button) => {
                button.addEventListener('click', () => {
                    fields.shipping_name.value = button.dataset.name || '';
                    fields.shipping_phone.value = button.dataset.phone || '';
                    fields.street_address.value = button.dataset.street || '';
                    fields.ward.value = button.dataset.ward || '';
                    fields.district.value = button.dataset.district || '';
                    fields.province.value = button.dataset.province || '';

                    if (currentAddressName) {
                        currentAddressName.textContent = button.dataset.name || '';
                    }

                    if (currentAddressPhone) {
                        currentAddressPhone.textContent = button.dataset.phone || '';
                    }

                    if (currentAddressText) {
                        currentAddressText.textContent = [
                            button.dataset.street,
                            button.dataset.ward,
                            button.dataset.district,
                            button.dataset.province,
                        ].filter(Boolean).join(', ');
                    }

                    if (currentAddressBadge) {
                        currentAddressBadge.classList.toggle(
                            'd-none',
                            button.dataset.isDefault !== '1'
                        );
                    }

                    modal.querySelectorAll('[data-checkout-address]')
                        .forEach((option) => option.classList.remove('is-default'));

                    button.classList.add('is-default');

                    window.bootstrap?.Modal.getOrCreateInstance(modal).hide();
                });
            });
        });
    </script>
@endpush
