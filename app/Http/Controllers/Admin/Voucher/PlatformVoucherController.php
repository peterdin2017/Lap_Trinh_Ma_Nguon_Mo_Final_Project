<?php

namespace App\Http\Controllers\Admin\Voucher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Voucher\PlatformVoucherRequest;
use App\Models\PlatformVoucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlatformVoucherController extends Controller
{
    public function index(Request $request): View
    {
        $vouchers = PlatformVoucher::query()
            ->when($request->filled('keyword'), function ($query) use ($request): void {
                $keyword = trim((string) $request->input('keyword'));

                $query->where(function ($query) use ($keyword): void {
                    $query->where('voucher_code', 'like', "%{$keyword}%")
                        ->orWhere('voucher_name', 'like', "%{$keyword}%");
                });
            })
            ->when(
                $request->filled('status')
                    && in_array($request->input('status'), ['active', 'inactive'], true),
                fn ($query) => $query->where('status', $request->input('status'))
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
        $voucher = PlatformVoucher::findOrFail($platformVoucher);
        $voucher->update($request->validated());

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

    public function destroy(int $platformVoucher): RedirectResponse
    {
        PlatformVoucher::findOrFail($platformVoucher)->delete();

        return redirect()
            ->route('admin.vouchers.index')
            ->with('success', 'Đã xóa voucher sàn.');
    }
}
