<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\Http\Controllers\Dashboard\PaymentRequestController;
use Modules\Wallet\Http\Controllers\Dashboard\PaymentMethodController;

Route::group(['middleware' => [ 'auth', 'ensure-store-access', 'check.store.status', 'check-permission']], function () {
    Route::get('payment-requests', [PaymentRequestController::class, 'index'])
        ->name('payment-requests.index');

    Route::put('payment-requests/{id}/update', [PaymentRequestController::class, 'update'])
        ->name('payment-requests.update');

    // Payment Methods Routes
    Route::resource('payment-methods', PaymentMethodController::class)->names('payment-methods');
    Route::patch('payment-methods/{paymentMethod}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])
        ->name('payment-methods.toggle-status');
});
