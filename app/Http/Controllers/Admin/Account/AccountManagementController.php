<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Account\AccountManagementRequest;
use App\Models\Account;

class AccountManagementController extends Controller
{
    public function index(AccountManagementRequest $request)
    {
        $filters = $request->validated();
        $query = Account::query();
        if (! empty($filters['q'])) $query->where('email', 'like', '%' . trim($filters['q']) . '%');
        if (! empty($filters['role'])) $query->where('role', $filters['role']);
        if (! empty($filters['status'])) $query->where('status', $filters['status']);
        return view('admin.accounts.index', ['accounts' => $query->orderByDesc('account_id')->paginate(12)->withQueryString()]);
    }
    public function toggle(AccountManagementRequest $request, Account $account)
    {
        $data = $request->validated();
        if ($account->account_id === $request->user()->account_id && $data['status'] === 'locked') return back()->with('error', 'Không thể tự khóa tài khoản đang đăng nhập.');
        $account->update(['status' => $data['status']]);
        return back()->with('success', 'Đã cập nhật trạng thái tài khoản.');
    }
}
