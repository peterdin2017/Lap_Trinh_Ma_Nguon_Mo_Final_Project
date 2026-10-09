<?php

use Illuminate\Support\Facades\Route;

Route::view('/terms', 'pages.terms')->name('page.terms');
Route::view('/privacy', 'pages.privacy')->name('page.privacy');
