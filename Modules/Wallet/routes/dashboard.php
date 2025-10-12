<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\Http\Controllers\Dashboard\PaymentRequestController;

Route::group(['prefix' => 'dashboard', 'middleware' => ['web', 'auth']], function () {
    Route::get('payment-requests', [PaymentRequestController::class, 'index'])
        ->name('dashboard.payment-requests.index');

    Route::put('payment-requests/{id}/update', [PaymentRequestController::class, 'update'])
        ->name('dashboard.payment-requests.update');
});
