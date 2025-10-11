<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;

Route::middleware(['customer.auth', 'ensure-store-access', 'check.store.status'])->group(function () {

    Route::get('notifications', [NotificationController::class, 'index'])
        ->name('notification.index');

    Route::get('notifications/read/{id}', [NotificationController::class, 'markAsRead'])
        ->name('notification.read');
});
