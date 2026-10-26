<?php

namespace App\Http\Controllers\Store\Account;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountSavedVoucher;
use App\Models\SellerVoucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BuyerVoucherController extends Controller
{
    private const VOUCHER_MODELS = [
        'seller' => SellerVoucher::class,
        'platform' => 'App\\Models\\PlatformVoucher',
    ];

    public function index(Request $request): View
    {
        $account = $this->currentAccount($request);
        $now = now();

        $savedVouchers = AccountSavedVoucher::query()
            ->where('account_id', $account->account_id)
            ->latest('created_at')
            ->limit(100)
            ->get()
            ->map(function (AccountSavedVoucher $saved): AccountSavedVoucher {
                $modelClass = $this->voucherModelClass($saved->voucher_type);

                $voucher = $modelClass
                    && class_exists($modelClass)
                    && Schema::hasTable((new $modelClass())->getTable())
                        ? $modelClass::query()->find($saved->voucher_id)
                        : null;

                $saved->setRelation('voucher', $voucher);

                return $saved;
            });

        $savedKeys = $savedVouchers
            ->map(fn (AccountSavedVoucher $saved) => (
                $saved->voucher_type . ':' . $saved->voucher_id
            ))
            ->all();

        // Danh sách tất cả voucher Shop và voucher sàn chưa được lưu.
        $availableVouchers = collect();

        // Danh sách riêng để hiển thị và nhận voucher sàn trong trang này.
        $availablePlatformVouchers = collect();

        foreach (self::VOUCHER_MODELS as $type => $modelClass) {
            if (! class_exists($modelClass)) {
                continue;
            }

            $model = new $modelClass();

            if (! Schema::hasTable($model->getTable())) {
                continue;
            }

            $vouchers = $modelClass::query()
                ->where('status', 'active')
                ->where('starts_at', '<=', $now)
                ->where('expires_at', '>=', $now)
                ->where(function ($query): void {
                    $query->whereNull('usage_limit')
                        ->orWhereColumn('used_count', '<', 'usage_limit');
                })
                ->orderBy('expires_at')
                ->limit(100)
                ->get();

            foreach ($vouchers as $voucher) {
                $key = $type . ':' . $voucher->getKey();

                if (in_array($key, $savedKeys, true)) {
                    continue;
                }

                $voucher->setAttribute('saved_voucher_type', $type);
                $availableVouchers->push($voucher);

                if ($type === 'platform') {
                    $availablePlatformVouchers->push($voucher);
                }
            }
        }

        return view('store.account.vouchers.index', [
            'savedVouchers' => $savedVouchers,
            'availableVouchers' => $availableVouchers,
            'availablePlatformVouchers' => $availablePlatformVouchers,
        ]);
    }

    public function save(
        Request $request,
        string $type,
        int $voucher
    ): RedirectResponse {
        $account = $this->currentAccount($request);
        $modelClass = $this->voucherModelClass($type);

        abort_unless(
            $modelClass && class_exists($modelClass),
            404,
            'Nguồn voucher này chưa được cấu hình.'
        );

        $model = new $modelClass();

        abort_unless(
            Schema::hasTable($model->getTable()),
            404,
            'Bảng voucher chưa được tạo.'
        );

        $voucherModel = $modelClass::query()
            ->whereKey($voucher)
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>=', now())
            ->where(function ($query): void {
                $query->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->firstOrFail();

        AccountSavedVoucher::query()->firstOrCreate([
            'account_id' => $account->account_id,
            'voucher_type' => $type,
            'voucher_id' => $voucherModel->getKey(),
        ]);

        return back()->with(
            'success',
            'Đã lưu voucher vào tài khoản của bạn.'
        );
    }

    public function remove(
        Request $request,
        string $type,
        int $voucher
    ): RedirectResponse {
        abort_unless(
            array_key_exists($type, self::VOUCHER_MODELS),
            404
        );

        $account = $this->currentAccount($request);

        AccountSavedVoucher::query()
            ->where('account_id', $account->account_id)
            ->where('voucher_type', $type)
            ->where('voucher_id', $voucher)
            ->delete();

        return back()->with(
            'success',
            'Đã bỏ voucher khỏi danh sách đã lưu.'
        );
    }

    private function voucherModelClass(string $type): ?string
    {
        return self::VOUCHER_MODELS[$type] ?? null;
    }

    private function currentAccount(Request $request): Account
    {
        $accountId = $request->session()->get('account_id');

        abort_unless(
            is_numeric($accountId),
            403,
            'Vui lòng đăng nhập để quản lý voucher.'
        );

        $account = Account::query()
            ->where('account_id', (int) $accountId)
            ->whereIn('role', ['user', 'seller'])
            ->where('status', 'active')
            ->first();

        abort_unless(
            $account instanceof Account,
            403,
            'Tài khoản không hợp lệ hoặc đã bị khóa.'
        );

        return $account;
    }
}
