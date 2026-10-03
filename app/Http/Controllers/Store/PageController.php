<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function terms()
    {
        return view('store.pages.terms', [
            'backUrl' => url()->previous(),
        ]);
    }

    public function privacy()
    {
        return view('store.pages.privacy', [
            'backUrl' => url()->previous(),
        ]);
    }
}
