<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Report\AdminStatisticsRequest;
use Carbon\CarbonImmutable;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminStatisticsController extends Controller
{
    private const PLATFORM_FEE_PER_ORDER = 30_000;

    public function index(AdminStatisticsRequest $request): View
    {
        [$from, $to] = $this->resolveDateRange($request);
        $report = $this->buildReportData($from, $to);

        return view('admin.reports.statistics', [
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            ...$report,
        ]);
    }

    public function export(AdminStatisticsRequest $request): StreamedResponse
    {
        [$from, $to] = $this->resolveDateRange($request);
        $report = $this->buildReportData($from, $to);

        $spreadsheet = new Spreadsheet();

        /*
         * Sheet 1: Tổng quan
         */
        $summarySheet = $spreadsheet->getActiveSheet();
        $summarySheet->setTitle('Tổng quan');

        $summarySheet->mergeCells('A1:B1');
        $summarySheet->setCellValue('A1', 'BÁO CÁO THỐNG KÊ TOÀN SÀN');
        $summarySheet->mergeCells('A2:B2');
        $summarySheet->setCellValue(
            'A2',
            'Thời gian: ' . $from->format('d/m/Y') . ' - ' . $to->format('d/m/Y')
        );

        $summarySheet->setCellValue('A4', 'Chỉ số');
        $summarySheet->setCellValue('B4', 'Giá trị');

        $summaryRows = [
            ['Tổng đơn hàng', $report['totalOrders']],
            ['Đơn hoàn thành', $report['completedOrders']],
            ['Đơn đã hủy', $report['cancelledOrders']],
            ['Doanh thu phí sàn', $report['revenue']],
            ['Khách hàng mới', $report['newCustomers']],
            ['Shop mới', $report['newShops']],
            ['Sản phẩm mới', $report['newProducts']],
        ];

        $rowNumber = 5;

        foreach ($summaryRows as [$label, $value]) {
            $summarySheet->setCellValue("A{$rowNumber}", $label);
            $summarySheet->setCellValue("B{$rowNumber}", $value);
            $rowNumber++;
        }

        $summarySheet->mergeCells('A13:B13');
        $summarySheet->setCellValue(
            'A13',
            'Quy tắc tính: phí sàn = 30.000₫ × số đơn hoàn thành. Đơn đã hủy không tính phí.'
        );
        $summarySheet->getStyle('A13')->getFont()->setItalic(true);

        $this->styleTitle($summarySheet, 'A1:B1');
        $this->styleHeader($summarySheet, 'A4:B4');
        $this->styleTable($summarySheet, 'A4:B11');
        $summarySheet->getStyle('B8')
            ->getNumberFormat()
            ->setFormatCode('#,##0" ₫"');

        /*
         * Sheet 2: Chi tiết từng ngày
         */
        $detailSheet = $spreadsheet->createSheet();
        $detailSheet->setTitle('Chi tiết theo ngày');

        $detailSheet->mergeCells('A1:K1');
        $detailSheet->setCellValue('A1', 'CHI TIẾT THỐNG KÊ THEO NGÀY');
        $detailSheet->mergeCells('A2:K2');
        $detailSheet->setCellValue(
            'A2',
            'Thời gian: ' . $from->format('d/m/Y') . ' - ' . $to->format('d/m/Y')
        );

        $headers = [
            'Ngày',
            'Tổng đơn',
            'Chờ xác nhận',
            'Đang xử lý',
            'Đang vận chuyển',
            'Hoàn thành',
            'Đã hủy',
            'Khách hàng mới',
            'Shop mới',
            'Sản phẩm mới',
            'Phí sàn',
        ];

        foreach ($headers as $index => $header) {
            $column = chr(ord('A') + $index);
            $detailSheet->setCellValue("{$column}4", $header);
        }

        $rowNumber = 5;

        foreach ($report['dailyStatistics'] as $day) {
            $detailSheet->setCellValue("A{$rowNumber}", $day['date']);
            $detailSheet->setCellValue("B{$rowNumber}", $day['orders']);
            $detailSheet->setCellValue("C{$rowNumber}", $day['pending_orders']);
            $detailSheet->setCellValue("D{$rowNumber}", $day['processing_orders']);
            $detailSheet->setCellValue("E{$rowNumber}", $day['shipping_orders']);
            $detailSheet->setCellValue("F{$rowNumber}", $day['completed_orders']);
            $detailSheet->setCellValue("G{$rowNumber}", $day['cancelled_orders']);
            $detailSheet->setCellValue("H{$rowNumber}", $day['new_customers']);
            $detailSheet->setCellValue("I{$rowNumber}", $day['new_shops']);
            $detailSheet->setCellValue("J{$rowNumber}", $day['new_products']);
            $detailSheet->setCellValue("K{$rowNumber}", $day['revenue']);

            $rowNumber++;
        }

        $totalRow = $rowNumber;
        $detailSheet->setCellValue("A{$totalRow}", 'TỔNG CỘNG');

        foreach (range('B', 'K') as $column) {
            $detailSheet->setCellValue(
                "{$column}{$totalRow}",
                "=SUM({$column}5:{$column}" . ($totalRow - 1) . ')'
            );
        }

        $this->styleTitle($detailSheet, 'A1:K1');
        $this->styleHeader($detailSheet, 'A4:K4');
        $this->styleTable($detailSheet, "A4:K{$totalRow}");

        $detailSheet->getStyle("A5:A{$totalRow}")
            ->getNumberFormat()
            ->setFormatCode('dd/mm/yyyy');

        $detailSheet->getStyle("K5:K{$totalRow}")
            ->getNumberFormat()
            ->setFormatCode('#,##0" ₫"');

        $detailSheet->getStyle("A{$totalRow}:K{$totalRow}")
            ->getFont()
            ->setBold(true);

        foreach ([$summarySheet, $detailSheet] as $sheet) {
            foreach (range('A', $sheet->getHighestDataColumn()) as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        $detailSheet->freezePane('A5');
        $spreadsheet->setActiveSheetIndex(0);

        $filename = sprintf(
            'bao-cao-toan-san-%s-den-%s.xlsx',
            $from->format('Ymd'),
            $to->format('Ymd')
        );

        return response()->streamDownload(
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

    private function resolveDateRange(AdminStatisticsRequest $request): array
    {
        $filters = $request->validated();

        $to = CarbonImmutable::parse($filters['to'] ?? now()->toDateString())
            ->endOfDay();

        $from = isset($filters['from'])
            ? CarbonImmutable::parse($filters['from'])->startOfDay()
            : $to->subDays(29)->startOfDay();

        return [$from, $to];
    }

    private function buildReportData(
        CarbonImmutable $from,
        CarbonImmutable $to
    ): array {
        $period = [$from, $to];

        $orders = DB::table('orders')->whereBetween('created_at', $period);

        $totalOrders = (clone $orders)->count();

        $completedOrders = (clone $orders)
            ->where('order_status', 'completed')
            ->count();

        $cancelledOrders = (clone $orders)
            ->where('order_status', 'cancelled')
            ->count();

        $revenue = $completedOrders * self::PLATFORM_FEE_PER_ORDER;

        $newCustomers = DB::table('accounts')
            ->where('role', 'user')
            ->whereBetween('created_at', $period)
            ->count();

        $newShops = DB::table('sellers')
            ->whereBetween('created_at', $period)
            ->count();

        $newProducts = DB::table('products')
            ->whereBetween('created_at', $period)
            ->count();

        $statusCounts = (clone $orders)
            ->select('order_status', DB::raw('COUNT(*) as total'))
            ->groupBy('order_status')
            ->pluck('total', 'order_status');

        $dailyOrders = DB::table('orders')
            ->selectRaw(
                'DATE(created_at) as report_date,
                COUNT(*) as order_count,
                SUM(CASE WHEN order_status = ? THEN 1 ELSE 0 END) as completed_orders,
                SUM(CASE WHEN order_status = ? THEN 1 ELSE 0 END) as cancelled_orders,
                SUM(CASE WHEN order_status = ? THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN order_status = ? THEN 1 ELSE 0 END) as processing_orders,
                SUM(CASE WHEN order_status = ? THEN 1 ELSE 0 END) as shipping_orders',
                ['completed', 'cancelled', 'pending', 'processing', 'shipping']
            )
            ->whereBetween('created_at', $period)
            ->groupBy('report_date')
            ->get()
            ->keyBy('report_date');

        $dailyCustomers = DB::table('accounts')
            ->selectRaw('DATE(created_at) as report_date, COUNT(*) as total')
            ->where('role', 'user')
            ->whereBetween('created_at', $period)
            ->groupBy('report_date')
            ->get()
            ->keyBy('report_date');

        $dailyShops = DB::table('sellers')
            ->selectRaw('DATE(created_at) as report_date, COUNT(*) as total')
            ->whereBetween('created_at', $period)
            ->groupBy('report_date')
            ->get()
            ->keyBy('report_date');

        $dailyProducts = DB::table('products')
            ->selectRaw('DATE(created_at) as report_date, COUNT(*) as total')
            ->whereBetween('created_at', $period)
            ->groupBy('report_date')
            ->get()
            ->keyBy('report_date');

        $dailyStatistics = collect(
            CarbonPeriod::create(
                $from->startOfDay(),
                '1 day',
                $to->startOfDay()
            )
        )->map(function (CarbonImmutable|\DateTimeInterface $day) use (
            $dailyOrders,
            $dailyCustomers,
            $dailyShops,
            $dailyProducts
        ): array {
            $date = $day->format('Y-m-d');
            $orderRow = $dailyOrders->get($date);

            $completedCount = (int) ($orderRow->completed_orders ?? 0);

            return [
                'date' => $date,
                'label' => $day->format('d/m'),
                'orders' => (int) ($orderRow->order_count ?? 0),
                'completed_orders' => $completedCount,
                'cancelled_orders' => (int) ($orderRow->cancelled_orders ?? 0),
                'pending_orders' => (int) ($orderRow->pending_orders ?? 0),
                'processing_orders' => (int) ($orderRow->processing_orders ?? 0),
                'shipping_orders' => (int) ($orderRow->shipping_orders ?? 0),
                'new_customers' => (int) ($dailyCustomers->get($date)->total ?? 0),
                'new_shops' => (int) ($dailyShops->get($date)->total ?? 0),
                'new_products' => (int) ($dailyProducts->get($date)->total ?? 0),
                'revenue' => $completedCount * self::PLATFORM_FEE_PER_ORDER,
            ];
        });

        return [
            'totalOrders' => $totalOrders,
            'completedOrders' => $completedOrders,
            'cancelledOrders' => $cancelledOrders,
            'revenue' => $revenue,
            'newCustomers' => $newCustomers,
            'newShops' => $newShops,
            'newProducts' => $newProducts,
            'statusCounts' => $statusCounts,
            'dailyStatistics' => $dailyStatistics,
        ];
    }

    private function styleTitle($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
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
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(30);
    }

    private function styleHeader($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E78'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);
    }

    private function styleTable($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D9E2F3'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
    }
}
