<?php

use App\Http\Controllers\Seller\Review\SellerReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller/reviews')
    ->name('seller.reviews.')
    ->group(function (): void {
        Route::get('/', [SellerReviewController::class, 'index'])
            ->name('index');

        Route::put('/{review}', [SellerReviewController::class, 'update'])
            ->whereNumber('review')
            ->name('update');
    });
