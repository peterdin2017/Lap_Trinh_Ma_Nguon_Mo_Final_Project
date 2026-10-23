<?php

use App\Http\Controllers\Store\Chat\ConversationController;
use App\Http\Controllers\Store\Chat\MessageController;
use Illuminate\Support\Facades\Route;

Route::prefix('chat')->name('store.chat.')->group(function (): void {
    Route::post('/conversations', [ConversationController::class, 'store'])
        ->name('conversations.store');

    Route::get('/conversations/{conversationId}/messages', [MessageController::class, 'index'])
        ->whereNumber('conversationId')
        ->name('messages.index');

    Route::post('/conversations/{conversationId}/messages', [MessageController::class, 'store'])
        ->whereNumber('conversationId')
        ->middleware('throttle:30,1')
        ->name('messages.store');
});
