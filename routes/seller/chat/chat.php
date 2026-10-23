<?php

use App\Http\Controllers\Seller\Chat\ConversationController;
use App\Http\Controllers\Seller\Chat\MessageController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller/chat')
    ->name('seller.chat.')
    ->group(function (): void {
        Route::get('/', [ConversationController::class, 'index'])
            ->name('index');

        Route::get('/conversations/updates', [ConversationController::class, 'updates'])
            ->name('conversations.updates');

        Route::get('/{conversationId}', [ConversationController::class, 'show'])
            ->whereNumber('conversationId')
            ->name('show');

        Route::get('/{conversationId}/messages', [MessageController::class, 'index'])
            ->whereNumber('conversationId')
            ->name('messages.index');

        Route::post('/{conversationId}/messages', [MessageController::class, 'store'])
            ->whereNumber('conversationId')
            ->middleware('throttle:30,1')
            ->name('messages.store');
    });
