@push('styles')
    <style>
        .checkout-page {
            --checkout-primary: #1a94ff;
            --checkout-border: #e2e8f0;
        }

        .checkout-page .btn-primary {
            --bs-btn-bg: var(--checkout-primary);
            --bs-btn-border-color: var(--checkout-primary);
            --bs-btn-hover-bg: #0077e6;
            --bs-btn-hover-border-color: #0077e6;
        }

        .checkout-card {
            border: 1px solid var(--checkout-border);
            border-radius: 12px;
            background: #fff;
        }

        .checkout-address-current {
            display: grid;
            grid-template-columns: minmax(180px, 270px) minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px 20px;
            padding: 14px 16px;
            border: 1px solid var(--checkout-border);
            border-radius: 8px;
            background: #fff;
        }

        .checkout-address-identity {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px 10px;
        }

        .checkout-address-text {
            color: #475569;
        }

        .checkout-address-badge {
            padding: 2px 8px;
            border: 1px solid #bfdbfe;
            color: var(--checkout-primary);
            font-size: .75rem;
            white-space: nowrap;
        }

        .checkout-address-option {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--checkout-border);
            border-radius: 10px;
            background: #fff;
            color: #0f172a;
            text-align: left;
            transition: border-color .15s ease, background-color .15s ease;
        }

        .checkout-address-option:hover,
        .checkout-address-option:focus-visible,
        .checkout-address-option.is-default {
            border-color: var(--checkout-primary);
            background: #f3f9ff;
        }

        .checkout-address-modal {
            border: 0;
            border-radius: 14px;
        }

        .checkout-store-block + .checkout-store-block {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--checkout-border);
        }

        .checkout-store-title {
            margin-bottom: 8px;
            color: #0f172a;
            font-weight: 700;
        }

        .checkout-voucher-shop {
            margin: 10px 0 12px;
            padding: 12px;
            border: 1px solid var(--checkout-border);
            border-radius: 8px;
            background: #f8fafc;
        }

        .checkout-voucher-platform-modal .modal-content {
            overflow: hidden;
            border: 0;
            border-radius: 14px;
        }

        .checkout-voucher-platform-modal .modal-body {
            max-height: min(65vh, 620px);
            overflow-y: auto;
            background: #fff;
        }

        .checkout-voucher-picker-trigger {
            min-width: 190px;
            text-align: left;
        }

        .checkout-voucher-modal .modal-content {
            overflow: hidden;
            border: 0;
            border-radius: 14px;
        }

        .checkout-voucher-modal .modal-body {
            max-height: min(65vh, 620px);
            overflow-y: auto;
            background: #fff;
        }

        .checkout-voucher-option {
            display: flex;
            min-height: 112px;
            overflow: hidden;
            margin-bottom: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            background: #fff;
            cursor: pointer;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .checkout-voucher-option:hover,
        .checkout-voucher-option.is-selected {
            border-color: #ee4d2d;
            box-shadow: 0 3px 12px rgb(238 77 45 / 12%);
        }

        .checkout-voucher-option__side {
            display: flex;
            width: 112px;
            flex: 0 0 112px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 12px 8px;
            background: #91d5cf;
            color: #fff;
            text-align: center;
        }

        .checkout-voucher-option__side i {
            font-size: 1.6rem;
        }

        .checkout-voucher-option__content {
            display: flex;
            flex: 1;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 14px 16px;
        }

        .checkout-voucher-option__discount {
            color: #243044;
            font-weight: 700;
        }

        .checkout-voucher-option__code,
        .checkout-voucher-option__condition {
            color: #64748b;
            font-size: .85rem;
        }

        .checkout-voucher-option__radio {
            width: 20px;
            height: 20px;
            flex: 0 0 20px;
            accent-color: #ee4d2d;
        }

        .checkout-voucher-none .checkout-voucher-option__side {
            background: #e9eef5;
            color: #64748b;
        }

        .checkout-voucher-applied {
            color: #198754;
        }

        .checkout-product-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-top: 1px solid #f1f5f9;
        }

        .checkout-product-image {
            display: grid;
            width: 64px;
            height: 64px;
            flex: 0 0 64px;
            place-items: center;
            overflow: hidden;
            border: 1px solid var(--checkout-border);
            border-radius: 8px;
            background: #f8fafc;
        }

        .checkout-product-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .checkout-line-total {
            color: #ee4d2d;
            white-space: nowrap;
        }

        .checkout-payment-option {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px;
            border: 1px solid var(--checkout-border);
            border-radius: 8px;
            cursor: pointer;
        }

        .checkout-payment-option:has(input:checked) {
            border-color: var(--checkout-primary);
            background: #f3f9ff;
        }

        .checkout-payment-option .form-check-input {
            margin-top: 3px;
        }

        .checkout-summary {
            position: sticky;
            top: 90px;
        }

        .checkout-summary-line {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin: 12px 0;
            color: #475569;
        }

        .checkout-summary-line strong {
            color: #0f172a;
            white-space: nowrap;
        }

        .checkout-summary-total {
            align-items: center;
            color: #0f172a;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .checkout-summary-total strong {
            color: #ee4d2d;
            font-size: 1.35rem;
        }

        .checkout-back-link {
            color: var(--checkout-primary);
            font-size: .9rem;
            font-weight: 600;
            text-decoration: none;
        }

        @media (max-width: 767.98px) {
            .checkout-address-current {
                grid-template-columns: 1fr auto;
            }

            .checkout-address-identity,
            .checkout-address-text {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 991.98px) {
            .checkout-summary {
                position: static;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .checkout-address-option {
                transition: none;
            }
        }
    </style>
@endpush
