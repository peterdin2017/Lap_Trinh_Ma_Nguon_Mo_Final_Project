<?php

namespace App\Http\Controllers\Admin\Voucher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Voucher\PlatformVoucherIndexRequest;
use App\Http\Requests\Admin\Voucher\PlatformVoucherRequest;
use App\Models\PlatformVoucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PlatformVoucherController extends Controller
{
    public function index(PlatformVoucherIndexRequest $request): View
    {
        $filters = $request->validated();
        $vouchers = PlatformVoucher::query()
            ->when(! empty($filters['keyword']), function ($query) use ($filters): void {
                $keyword = trim($filters['keyword']);

                $query->where(function ($query) use ($keyword): void {
                    $query->where('voucher_code', 'like', "%{$keyword}%")
                        ->orWhere('voucher_name', 'like', "%{$keyword}%");
                });
            })
            ->when(
                ! empty($filters['status']),
                fn ($query) => $query->where('status', $filters['status'])
            )
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create(): View
    {
        return view('admin.vouchers.form', [
            'voucher' => new PlatformVoucher(),
            'isEditing' => false,
        ]);
    }

    public function store(PlatformVoucherRequest $request): RedirectResponse
    {
        PlatformVoucher::create($request->validated());

        return redirect()
            ->route('admin.vouchers.index')
            ->with('success', 'Đã tạo voucher sàn.');
    }

    public function edit(int $platformVoucher): View
    {
        return view('admin.vouchers.form', [
            'voucher' => PlatformVoucher::findOrFail($platformVoucher),
            'isEditing' => true,
        ]);
    }

    public function update(
        PlatformVoucherRequest $request,
        int $platformVoucher
    ): RedirectResponse {
        $data = $request->validated();

        $limitRejected = DB::transaction(function () use ($platformVoucher, $data): bool {
            $voucher = PlatformVoucher::query()
                ->whereKey($platformVoucher)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $data['usage_limit'] !== null
                && (int) $data['usage_limit'] < (int) $voucher->used_count
            ) {
                return true;
            }

            $voucher->update($data);

            return false;
        });

        if ($limitRejected) {
            $usedCount = PlatformVoucher::query()
                ->whereKey($platformVoucher)
                ->value('used_count');

            return back()
                ->withErrors([
                    'usage_limit' => 'Tổng lượt sử dụng không thể thấp hơn số lượt voucher đã dùng ('
                        . (int) $usedCount . ').',
                ])
                ->withInput();
        }

        return redirect()
            ->route('admin.vouchers.index')
            ->with('success', 'Đã cập nhật voucher sàn.');
    }

    public function toggleStatus(int $platformVoucher): RedirectResponse
    {
        $voucher = PlatformVoucher::findOrFail($platformVoucher);

        $voucher->update([
            'status' => $voucher->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Đã cập nhật trạng thái voucher.');
    }

}
