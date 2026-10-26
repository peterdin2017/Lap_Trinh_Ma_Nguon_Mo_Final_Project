<?php

namespace App\Http\Controllers\Seller\Report;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seller;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SellerRevenueReportController extends Controller
{
    public function index(Request $request): View
    {
        $seller = $this->currentSeller($request);
        [$dateFrom, $dateTo] = $this->reportPeriod($request);

        $completedOrders = Order::query()
            ->where('seller_id', $seller->seller_id)
            ->where('order_status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo]);

        $summary = (clone $completedOrders)
            ->selectRaw('
                COUNT(*) AS completed_order_count,
                COALESCE(SUM(subtotal), 0) AS product_sales,
                COALESCE(SUM(discount_amount), 0) AS discount_total,
                COALESCE(SUM(shipping_fee), 0) AS shipping_total,
                COALESCE(SUM(total_amount), 0) AS customer_paid_total
            ')
            ->first();

        $revenue = max(
            (float) $summary->product_sales - (float) $summary->discount_total,
            0
        );

        $soldQuantity = OrderItem::query()
            ->whereHas('order', function ($query) use ($seller, $dateFrom, $dateTo): void {
                $query->where('seller_id', $seller->seller_id)
                    ->where('order_status', 'completed')
                    ->whereBetween('created_at', [$dateFrom, $dateTo]);
            })
            ->sum('quantity');

        $dailyRevenue = (clone $completedOrders)
            ->selectRaw(
                'DATE(created_at) AS report_date,
                SUM(CASE WHEN subtotal > discount_amount THEN subtotal - discount_amount ELSE 0 END) AS revenue,
                COUNT(*) AS order_count'
            )
            ->groupByRaw('DATE(created_at)')
            ->orderBy('report_date')
            ->get()
            ->keyBy('report_date');

        $chartRows = collect();
        $date = $dateFrom->startOfDay();
        $lastDate = $dateTo->startOfDay();

        while ($date->lessThanOrEqualTo($lastDate)) {
            $row = $dailyRevenue->get($date->toDateString());

            $chartRows->push([
                'date' => $date,
                'revenue' => (float) ($row->revenue ?? 0),
                'order_count' => (int) ($row->order_count ?? 0),
            ]);

            $date = $date->addDay();
        }

        $maxDailyRevenue = max((float) $chartRows->max('revenue'), 1);

        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.order_id', '=', 'order_items.order_id')
            ->where('orders.seller_id', $seller->seller_id)
            ->where('orders.order_status', 'completed')
            ->whereBetween('orders.created_at', [$dateFrom, $dateTo])
            ->select([
                'order_items.product_id',
                'order_items.product_name',
            ])
            ->selectRaw('SUM(order_items.quantity) AS units_sold')
            ->selectRaw('SUM(order_items.total_price) AS product_sales')
            ->selectRaw('COUNT(DISTINCT orders.order_id) AS order_count')
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('units_sold')
            ->limit(10)
            ->get();

        return view('seller.reports.revenue', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'summary' => $summary,
            'revenue' => $revenue,
            'soldQuantity' => (int) $soldQuantity,
            'averageOrderValue' => (int) $summary->completed_order_count > 0
                ? $revenue / (int) $summary->completed_order_count
                : 0,
            'chartRows' => $chartRows,
            'maxDailyRevenue' => $maxDailyRevenue,
            'topProducts' => $topProducts,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $seller = $this->currentSeller($request);
        [$dateFrom, $dateTo] = $this->reportPeriod($request);

        $summary = Order::query()
            ->where('seller_id', $seller->seller_id)
            ->where('order_status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('
                COUNT(*) AS completed_order_count,
                COALESCE(SUM(subtotal), 0) AS product_sales,
                COALESCE(SUM(discount_amount), 0) AS discount_total
            ')
            ->first();

        $netRevenue = max(
            (float) $summary->product_sales - (float) $summary->discount_total,
            0
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Doanh thu');

        // Tiêu đề báo cáo.
        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'BÁO CÁO DOANH THU SELLER');

        $sheet->mergeCells('A2:L2');
        $sheet->setCellValue(
            'A2',
            sprintf(
                'Cửa hàng: %s | Kỳ báo cáo: %s - %s',
                $seller->shop_name,
                $dateFrom->format('d/m/Y'),
                $dateTo->format('d/m/Y')
            )
        );

        // Các chỉ số tổng hợp.
        $sheet->setCellValue('A4', 'Doanh thu sau giảm');
        $sheet->setCellValue('B4', $netRevenue);
        $sheet->setCellValue('C4', 'Đơn hoàn tất');
        $sheet->setCellValue('D4', (int) $summary->completed_order_count);
        $sheet->setCellValue('E4', 'Tổng giảm giá');
        $sheet->setCellValue('F4', (float) $summary->discount_total);

        // Tiêu đề bảng chi tiết.
        $headers = [
            'Mã đơn hàng',
            'Ngày hoàn thành',
            'Sản phẩm',
            'Mẫu mã',
            'Số lượng',
            'Đơn giá',
            'Thành tiền sản phẩm',
            'Tạm tính đơn hàng',
            'Giảm giá đơn hàng',
            'Phí vận chuyển',
            'Tổng khách thanh toán',
            'Phương thức thanh toán',
        ];

        $sheet->fromArray($headers, null, 'A6');

        $rowNumber = 7;

        Order::query()
            ->where('seller_id', $seller->seller_id)
            ->where('order_status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('items')
            ->chunkById(200, function ($orders) use ($sheet, &$rowNumber): void {
                foreach ($orders as $order) {
                    if ($order->items->isEmpty()) {
                        $sheet->fromArray([
                            $this->safeSpreadsheetText($order->order_code),
                            $order->created_at?->format('d/m/Y H:i'),
                            '',
                            '',
                            0,
                            0,
                            0,
                            (float) $order->subtotal,
                            (float) $order->discount_amount,
                            (float) $order->shipping_fee,
                            (float) $order->total_amount,
                            $this->safeSpreadsheetText($order->payment_method),
                        ], null, 'A' . $rowNumber);

                        $rowNumber++;
                        continue;
                    }

                    foreach ($order->items as $item) {
                        $sheet->fromArray([
                            $this->safeSpreadsheetText($order->order_code),
                            $order->created_at?->format('d/m/Y H:i'),
                            $this->safeSpreadsheetText($item->product_name),
                            $this->safeSpreadsheetText($item->variant_name ?? ''),
                            (int) $item->quantity,
                            (float) $item->price,
                            (float) $item->total_price,
                            (float) $order->subtotal,
                            (float) $order->discount_amount,
                            (float) $order->shipping_fee,
                            (float) $order->total_amount,
                            $this->safeSpreadsheetText($order->payment_method),
                        ], null, 'A' . $rowNumber);

                        $rowNumber++;
                    }
                }
            }, 'order_id');

        $lastRow = max(6, $rowNumber - 1);

        // Kiểu chữ và màu tiêu đề.
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '17365D'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:L2')->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '475569'],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A4:F4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EAF3F8'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D5E1EA'],
                ],
            ],
        ]);

        $sheet->getStyle('A6:L6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1A94FF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        // Định dạng dữ liệu chi tiết.
        if ($lastRow >= 7) {
            $sheet->getStyle("A7:L{$lastRow}")
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(Border::BORDER_HAIR);

            $sheet->getStyle("F7:K{$lastRow}")
                ->getNumberFormat()
                ->setFormatCode('#,##0 "₫"');

            $sheet->getStyle("E7:E{$lastRow}")
                ->getNumberFormat()
                ->setFormatCode('#,##0');

            $sheet->getStyle("B7:B{$lastRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getStyle("C7:D{$lastRow}")
                ->getAlignment()
                ->setWrapText(true);
        }

        $sheet->getStyle('B4')->getNumberFormat()->setFormatCode('#,##0 "₫"');
        $sheet->getStyle('F4')->getNumberFormat()->setFormatCode('#,##0 "₫"');

        // Độ rộng các cột.
        $columnWidths = [
            'A' => 24,
            'B' => 20,
            'C' => 38,
            'D' => 26,
            'E' => 12,
            'F' => 18,
            'G' => 24,
            'H' => 22,
            'I' => 20,
            'J' => 18,
            'K' => 24,
            'L' => 24,
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        // Thiết lập hiển thị và in báo cáo.
        $sheet->getRowDimension(1)->setRowHeight(32);
        $sheet->getRowDimension(2)->setRowHeight(24);
        $sheet->getRowDimension(6)->setRowHeight(34);
        $sheet->freezePane('A7');
        $sheet->setAutoFilter("A6:L{$lastRow}");

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageSetup()->setFitToPage(true);

        $filename = sprintf(
            'bao-cao-doanh-thu-%s-den-%s.xlsx',
            $dateFrom->toDateString(),
            $dateTo->toDateString()
        );

        return Response::streamDownload(
            function () use ($spreadsheet): void {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                $spreadsheet->disconnectWorksheets();
            },
            $filename,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );
    }

    private function reportPeriod(Request $request): array
    {
        $filters = $request->validate([
            'date_from' => ['nullable', 'date', 'before_or_equal:today'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ]);

        $timezone = config('app.timezone', 'UTC');
        $today = CarbonImmutable::now($timezone)->startOfDay();

        $dateFrom = isset($filters['date_from'])
            ? CarbonImmutable::parse($filters['date_from'], $timezone)->startOfDay()
            : (isset($filters['date_to'])
                ? CarbonImmutable::parse($filters['date_to'], $timezone)->startOfMonth()
                : $today->startOfMonth());

        $dateTo = isset($filters['date_to'])
            ? CarbonImmutable::parse($filters['date_to'], $timezone)->endOfDay()
            : $today->endOfDay();

        if ($dateFrom->diffInDays($dateTo) > 366) {
            throw ValidationException::withMessages([
                'date_to' => 'Khoảng thời gian báo cáo tối đa là 366 ngày.',
            ]);
        }

        return [$dateFrom, $dateTo];
    }

    private function safeSpreadsheetText(?string $value): string
    {
        $value = (string) $value;

        return preg_match('/^[\s]*[=+\-@]/u', $value) === 1
            ? "'{$value}"
            : $value;
    }

    private function currentSeller(Request $request): Seller
    {
        $seller = $request->user()?->seller;

        abort_unless(
            $seller instanceof Seller,
            403,
            'Tài khoản hiện tại chưa liên kết với cửa hàng Seller.'
        );

        return $seller;
    }
}
