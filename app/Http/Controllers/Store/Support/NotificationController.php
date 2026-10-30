<?php

namespace App\Http\Controllers\Store\Support;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\CustomerNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $accountId = $request->session()->get('account_id');

        $account = is_numeric($accountId)
            ? Account::query()
                ->whereKey((int) $accountId)
                ->whereIn('role', ['user', 'seller'])
                ->where('status', 'active')
                ->first()
            : null;

        if (! $account) {
            return redirect()
                ->route('login.form')
                ->with('error', 'Vui lòng đăng nhập để xem thông báo.');
        }

        CustomerNotification::query()
            ->where('account_id', $account->account_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $notifications = CustomerNotification::query()
            ->where('account_id', $account->account_id)
            ->with('supportRequest')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('store.notifications.index', [
            'notifications' => $notifications,
        ]);
    }
}
