<?php

namespace App\Http\Controllers\Store\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Account\AddressRequest;
use App\Models\Account;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $account = $this->currentUserAccount($request);

        if (! $account) {
            return redirect()->guest(route('login.form'));
        }

        $addresses = Address::query()
            ->where('account_id', $account->account_id)
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        return view('store.account.addresses.index', compact('addresses'));
    }

    public function create(Request $request): View|RedirectResponse
    {
        if (! $this->currentUserAccount($request)) {
            return redirect()->guest(route('login.form'));
        }

        return view('store.account.addresses.form', [
            'address' => new Address(),
        ]);
    }

    public function store(AddressRequest $request): RedirectResponse
    {
        $account = $this->currentUserAccount($request);

        if (! $account) {
            return redirect()->guest(route('login.form'));
        }

        $data = $request->validated();
        $makeDefault = (bool) ($data['is_default'] ?? false);
        unset($data['is_default']);

        DB::transaction(function () use ($account, $data, $makeDefault): void {
            $accountAddresses = Address::query()
                ->where('account_id', $account->account_id);

            $shouldBeDefault = $makeDefault || ! $accountAddresses->exists();

            if ($shouldBeDefault) {
                $accountAddresses->update(['is_default' => false]);
            }

            Address::query()->create($data + [
                'account_id' => $account->account_id,
                'is_default' => $shouldBeDefault,
            ]);
        });

        return redirect()
            ->route('store.account.addresses.index')
            ->with('success', 'Đã thêm địa chỉ nhận hàng.');
    }

    public function edit(Request $request, int $addressId): View|RedirectResponse
    {
        $account = $this->currentUserAccount($request);

        if (! $account) {
            return redirect()->guest(route('login.form'));
        }

        $address = $this->ownedAddress($account, $addressId);

        return view('store.account.addresses.form', compact('address'));
    }

    public function update(
        AddressRequest $request,
        int $addressId
    ): RedirectResponse {
        $account = $this->currentUserAccount($request);

        if (! $account) {
            return redirect()->guest(route('login.form'));
        }

        $address = $this->ownedAddress($account, $addressId);
        $data = $request->validated();
        $makeDefault = (bool) ($data['is_default'] ?? false);
        unset($data['is_default']);

        DB::transaction(function () use ($account, $address, $data, $makeDefault): void {
            if ($makeDefault) {
                Address::query()
                    ->where('account_id', $account->account_id)
                    ->where('address_id', '<>', $address->address_id)
                    ->update(['is_default' => false]);
            }

            $address->fill($data);

            if ($makeDefault) {
                $address->is_default = true;
            }

            $address->save();
        });

        return redirect()
            ->route('store.account.addresses.index')
            ->with('success', 'Đã cập nhật địa chỉ nhận hàng.');
    }

    public function destroy(Request $request, int $addressId): RedirectResponse
    {
        $account = $this->currentUserAccount($request);

        if (! $account) {
            return redirect()->guest(route('login.form'));
        }

        $address = $this->ownedAddress($account, $addressId);
        $wasDefault = $address->is_default;

        DB::transaction(function () use ($account, $address, $wasDefault): void {
            $address->delete();

            if ($wasDefault) {
                $nextAddress = Address::query()
                    ->where('account_id', $account->account_id)
                    ->orderBy('created_at')
                    ->orderBy('address_id')
                    ->first();

                $nextAddress?->update(['is_default' => true]);
            }
        });

        return redirect()
            ->route('store.account.addresses.index')
            ->with('success', 'Đã xóa địa chỉ nhận hàng.');
    }

    public function makeDefault(Request $request, int $addressId): RedirectResponse
    {
        $account = $this->currentUserAccount($request);

        if (! $account) {
            return redirect()->guest(route('login.form'));
        }

        $address = $this->ownedAddress($account, $addressId);

        DB::transaction(function () use ($account, $address): void {
            Address::query()
                ->where('account_id', $account->account_id)
                ->update(['is_default' => false]);

            $address->update(['is_default' => true]);
        });

        return redirect()
            ->route('store.account.addresses.index')
            ->with('success', 'Đã đặt làm địa chỉ mặc định.');
    }

    private function currentUserAccount(Request $request): ?Account
    {
        $accountId = $request->session()->get('account_id');

        if (! $accountId) {
            return null;
        }

        $account = Account::query()->find($accountId);

        if (! $account || $account->role !== 'user') {
            abort(403, 'Chỉ tài khoản khách hàng mới được sử dụng chức năng này.');
        }

        return $account;
    }

    private function ownedAddress(Account $account, int $addressId): Address
    {
        return Address::query()
            ->where('account_id', $account->account_id)
            ->where('address_id', $addressId)
            ->firstOrFail();
    }
}
