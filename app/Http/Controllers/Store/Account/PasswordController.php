<?php

namespace App\Http\Controllers\Store\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Account\UpdatePasswordRequest;
use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if (! $this->currentUserAccount($request)) {
            return redirect()->guest(route('login.form'));
        }

        return view('store.account.password');
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $account = $this->currentUserAccount($request);

        if (! $account) {
            return redirect()->guest(route('login.form'));
        }

        $data = $request->validated();

        if (! Hash::check($data['current_password'], $account->password_hash)) {
            throw ValidationException::withMessages([
                'current_password' => 'Mật khẩu hiện tại không chính xác.',
            ]);
        }

        // Mật khẩu được băm trước khi lưu vào cột password_hash.
        $account->password_hash = Hash::make($data['password']);
        $account->save();

        $request->session()->regenerate();

        return redirect()
            ->route('store.account.password')
            ->with('success', 'Đã đổi mật khẩu thành công.');
    }

    /**
     * Lấy tài khoản Store từ session.
     * Seller và Admin không được dùng trang đổi mật khẩu khách hàng này.
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
