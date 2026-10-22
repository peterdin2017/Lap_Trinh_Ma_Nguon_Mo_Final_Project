<?php

namespace App\Http\Controllers\Store\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Account\UpdateProfileRequest;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        $account = $this->currentUserAccount($request);

        if (! $account) {
            return redirect()->guest(route('login.form'));
        }

        $profile = User::query()->firstOrNew([
            'account_id' => $account->account_id,
        ]);

        return view('store.account.profile', compact('account', 'profile'));
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $account = $this->currentUserAccount($request);

        if (! $account) {
            return redirect()->guest(route('login.form'));
        }

        $data = $request->validated();

        $profile = User::query()->firstOrNew([
            'account_id' => $account->account_id,
        ]);

        $profile->full_name = $data['full_name'];
        $profile->phone = $data['phone'] ?? null;
        $profile->gender = $data['gender'] ?? null;
        $profile->birthdate = $data['birthdate'] ?? null;

        // Chỉ thay avatar khi người dùng chọn ảnh mới.
        if ($request->hasFile('avatar')) {
            $oldAvatar = $profile->avatar;
            $newAvatar = $request->file('avatar')->store('avatars', 'public');

            $profile->avatar = $newAvatar;

            // Xóa ảnh cũ sau khi đã lưu ảnh mới.
            if ($oldAvatar) {
                Storage::disk('public')->delete($oldAvatar);
            }
        }

        $profile->save();

        // Header dùng session nên cập nhật tên và avatar ngay sau khi lưu.
        $request->session()->put('user_name', $profile->full_name);
        $request->session()->put('avatar', $profile->avatar);

        return redirect()
            ->route('store.account.profile')
            ->with('success', 'Đã cập nhật thông tin cá nhân.');
    }

    /**
     * Lấy tài khoản Store từ session.
     * Seller và Admin không được dùng trang hồ sơ khách hàng này.
     */
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
}
