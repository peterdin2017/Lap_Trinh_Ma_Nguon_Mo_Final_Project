<?php

use App\Http\Controllers\Admin\Support\SupportController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/support')
    ->name('admin.support.')
    ->controller(SupportController::class)
    ->group(function (): void {
        Route::get('/', 'index')
            ->name('index');

        Route::get('/updates', 'updates')
            ->name('updates');

        Route::patch('/{requestId}', 'update')
            ->whereNumber('requestId')
            ->name('update');

        Route::get('/{requestId}/attachments/{attachmentIndex}/preview', 'previewAttachment')
            ->whereNumber('requestId')
            ->whereNumber('attachmentIndex')
            ->name('attachments.preview');


    });
