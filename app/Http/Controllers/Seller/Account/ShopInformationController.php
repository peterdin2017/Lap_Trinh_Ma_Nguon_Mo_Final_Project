<?php

namespace App\Http\Controllers\Seller\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Account\UpdateShopInformationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        $data = $request->validated();
        unset($data['avatar']);

        $seller->fill($data);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = $seller->seller_id
                . '-'
                . now()->format('YmdHisv')
                . '.'
                . $extension;

            $oldAvatar = $seller->avatar;
            $newAvatar = $file->storeAs('avatars/sellers', $fileName, 'public');

            if (! $newAvatar) {
                return back()
                    ->withInput()
                    ->with('error', 'Không thể lưu ảnh đại diện. Vui lòng thử lại.');
            }

            $seller->avatar = $newAvatar;

            // Chỉ xóa ảnh cũ sau khi ảnh mới lưu thành công.
            if ($oldAvatar && $oldAvatar !== $newAvatar) {
                Storage::disk('public')->delete($oldAvatar);
            }
        }

        $seller->save();

        return redirect()
            ->route('seller.shop-information.edit')
            ->with('success', 'Đã cập nhật thông tin shop.');
    }
}
