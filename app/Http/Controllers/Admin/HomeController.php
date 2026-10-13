<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Seller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('admin.home', [
            'accountCount' => Account::count(),
            'shopCount' => Seller::count(),
            'pendingShopCount' => Seller::where('status', 'pending')->count(),
        ]);
    }
}
