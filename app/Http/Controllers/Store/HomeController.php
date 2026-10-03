<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('store.home');
    }
}
