<?php

namespace App\Http\Controllers\Seller\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Account\UpdateShopInformationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopInformationController extends Controller
{
    public function edit(Request $request): View
    {
        $account = $request->user();

        abort_unless($account && $account->role === 'seller', 403);

        $seller = $account->seller;

        abort_unless($seller, 403, 'Tài khoản chưa có hồ sơ người bán.');

        return view('seller.account.shop-information', compact('account', 'seller'));
    }

    public function update(
        UpdateShopInformationRequest $request
    ): RedirectResponse {
        $account = $request->user();

        abort_unless($account && $account->role === 'seller', 403);

        $seller = $account->seller;

        abort_unless($seller, 403, 'Tài khoản chưa có hồ sơ người bán.');

        $seller->update($request->validated());

        return redirect()
            ->route('seller.shop-information.edit')
            ->with('success', 'Đã cập nhật thông tin shop.');
    }
}
