<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Dashboard\OrderController;

Route::middleware(['auth', 'ensure-store-access', 'check.store.status', 'check-permission'])->group(function () {
    Route::resource('orders', OrderController::class)->names('order');
});
