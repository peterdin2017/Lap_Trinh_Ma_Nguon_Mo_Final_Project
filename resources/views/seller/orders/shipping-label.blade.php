<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Phiếu giao hàng {{ $order->order_code }}</title>

    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 18px;
            background: #f1f5f9;
            color: #111827;
            font: 14px/1.45 Arial, sans-serif;
        }
        .label {
            width: 105mm;
            min-height: 148mm;
            margin: auto;
            padding: 7mm;
            background: #fff;
            border: 1px solid #d1d5db;
        }
        .head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 10px;
            border-bottom: 2px solid #111827;
        }
        .shop { font-size: 18px; font-weight: 700; }
        .muted { color: #6b7280; font-size: 12px; }
        .code { text-align: right; font-weight: 700; }
        .barcode-wrap {
            padding: 10px 0;
            border-bottom: 1px dashed #9ca3af;
            text-align: center;
        }
        .section {
            padding: 10px 0;
            border-bottom: 1px dashed #9ca3af;
        }
        .section-title {
            margin: 0 0 5px;
            color: #6b7280;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .recipient { font-size: 17px; font-weight: 700; }
        .address { font-size: 13px; }
        .items { width: 100%; margin: 6px 0; border-collapse: collapse; }
        .items td {
            padding: 4px 0;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        .items td:last-child { text-align: right; white-space: nowrap; }
        .total {
            display: flex;
            justify-content: space-between;
            padding-top: 7px;
            font-size: 16px;
            font-weight: 700;
        }
        .print {
            display: block;
            margin: 12px auto;
            padding: 9px 18px;
            border: 0;
            border-radius: 6px;
            color: #fff;
            background: #1a94ff;
            cursor: pointer;
        }
        @page { size: A6 portrait; margin: 0; }
        @media print {
            body { padding: 0; background: #fff; }
            .label { width: 105mm; min-height: 148mm; margin: 0; border: 0; }
            .print { display: none; }
        }
    </style>
</head>
<body>
    <button class="print" onclick="window.print()">In phiếu giao hàng</button>

    <main class="label">
        <header class="head">
            <div>
                <div class="shop">{{ $order->seller?->shop_name ?? 'Cửa hàng' }}</div>
                <div class="muted">
                    {{ $order->seller?->shop_address ?: 'Địa chỉ shop chưa cập nhật' }}
                </div>
            </div>
            <div class="code">
                MÃ ĐƠN<br>
                {{ $order->order_code }}
            </div>
        </header>

        <div class="barcode-wrap">
            <svg id="orderBarcode"></svg>
            <div class="muted">Quét mã để đối chiếu đơn hàng</div>
        </div>

        <section class="section">
            <h2 class="section-title">Người nhận</h2>
            <div class="recipient">{{ $order->shipping_name }}</div>
            <div><strong>{{ $order->shipping_phone }}</strong></div>
            <div class="address">{{ $order->shipping_address }}</div>
        </section>

        <section class="section">
            <h2 class="section-title">Kiện hàng</h2>
            <table class="items">
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                {{ $item->product_name }}
                                @if ($item->variant_name)
                                    <br><span class="muted">{{ $item->variant_name }}</span>
                                @endif
                            </td>
                            <td>×{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">
                <span>Thu hộ (COD)</span>
                <span>
                    {{ $order->payment_method === 'cod'
                        ? number_format((float) $order->total_amount, 0, ',', '.') . '₫'
                        : 'Đã thanh toán' }}
                </span>
            </div>
        </section>

        <footer class="muted" style="padding-top: 10px">
            Ngày tạo: {{ $order->created_at?->format('d/m/Y H:i') }}
            · Vui lòng gọi khách trước khi giao.
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
    <script>
        if (window.JsBarcode) {
            JsBarcode('#orderBarcode', @json($order->order_code), {
                format: 'CODE128',
                width: 1.5,
                height: 45,
                displayValue: true,
                fontSize: 13,
                margin: 0
            });
        }
    </script>
</body>
</html>
