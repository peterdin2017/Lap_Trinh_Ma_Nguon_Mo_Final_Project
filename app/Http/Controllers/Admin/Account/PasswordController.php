<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Account\UpdatePasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(): View
    {
        return view('admin.account.password');
    }

    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $account = $request->user();
        $data = $request->validated();

        if (! Hash::check($data['current_password'], $account->password_hash)) {
            throw ValidationException::withMessages([
                'current_password' => 'Mật khẩu hiện tại không chính xác.',
            ]);
        }

        $account->password_hash = Hash::make($data['password']);
        $account->save();

        $request->session()->regenerate();

        return redirect()
            ->route('admin.account.password.edit')
            ->with('success', 'Đã đổi mật khẩu thành công.');
    }
}
