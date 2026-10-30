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

        // Lưu trước để tạo hồ sơ nếu đây là lần cập nhật đầu tiên.
        $profile->save();

        $oldAvatar = $profile->avatar;

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = $profile->account_id
                . '-'
                . now()->format('YmdHis')
                . '.'
                . $file->extension();

            $newAvatar = $file->storeAs('avatars/users', $fileName, 'public');

            if ($newAvatar === false) {
                return back()
                    ->withInput()
                    ->withErrors(['avatar' => 'Không thể lưu ảnh đại diện.']);
            }

            $profile->avatar = $newAvatar;

            // Lưu đường dẫn avatar mới vào database.
            $profile->save();

            // Chỉ xóa ảnh cũ sau khi avatar mới đã được lưu thành công.
            if ($oldAvatar && $oldAvatar !== $newAvatar) {
                Storage::disk('public')->delete($oldAvatar);
            }
        }

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
