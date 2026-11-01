<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Report\ReportManagementRequest;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReportManagementController extends Controller
{
    public function index(ReportManagementRequest $request)
    {
        $filters = $request->validated();
        $query = DB::table('product_reports as r')->leftJoin('products as p', 'p.product_id', '=', 'r.product_id')->leftJoin('accounts as a', 'a.account_id', '=', 'r.account_id')->select('r.*', 'p.product_name', 'p.status as product_status', 'a.email');
        if (! empty($filters['status'])) $query->where('r.status', $filters['status']);
        return view('admin.reports.index', ['reports' => $query->orderByDesc('r.created_at')->paginate(12)->withQueryString()]);
    }
    public function handle(ReportManagementRequest $request, int $report)
    {
        $data = $request->validated();
        $done = DB::transaction(function () use ($request, $report, $data) {
            $row = DB::table('product_reports')->where('report_id', $report)->lockForUpdate()->first();
            if (!$row || $row->status !== 'pending') return false;
            if ($data['decision'] === 'resolved') {
                $product = Product::whereKey($row->product_id)->lockForUpdate()->first();
                if (!$product) return false;
                if ($product->status !== 'locked') $product->forceFill(['status' => 'locked', 'locked_reason' => $data['admin_note'], 'locked_at' => now()])->save();
            }
            DB::table('product_reports')->where('report_id', $report)->update(['status' => $data['decision'], 'admin_note' => $data['admin_note'], 'handled_by' => $request->user()->account_id, 'handled_at' => now(), 'updated_at' => now()]);
            return true;
        });
        return back()->with($done ? 'success' : 'error', $done ? 'Đã xử lý báo cáo.' : 'Báo cáo đã được xử lý hoặc không còn tồn tại.');
    }
}
