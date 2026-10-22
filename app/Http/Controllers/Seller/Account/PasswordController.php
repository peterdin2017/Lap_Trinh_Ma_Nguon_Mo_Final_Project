<?php

namespace App\Http\Controllers\Seller\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\Account\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(): View
    {
        return view('seller.account.change-password');
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $account = $request->user();
        $data = $request->validated();

        if (! Hash::check($data['current_password'], $account->password_hash)) {
            return back()
                ->withErrors([
                    'current_password' => 'Mật khẩu hiện tại không chính xác.',
                ]);
        }

        // Account dùng hashed cast cho password_hash,
        // nên gán mật khẩu dạng thường để Laravel tự băm khi lưu.
        $account->password_hash = $data['new_password'];
        $account->save();

        $request->session()->regenerate();

        return redirect()
            ->route('seller.account.password.edit')
            ->with('success', 'Đã đổi mật khẩu thành công.');
    }
}
