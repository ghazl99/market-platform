<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\App\OrderController;

Route::middleware(['customer.auth','ensure-store-access','check.store.status'])->group(function () {
    Route::resource('orders', OrderController::class)->names('order');
    Route::get('/orders-export', [OrderController::class, 'export'])
        ->name('order.export');
});
