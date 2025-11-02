<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\Http\Controllers\Dashboard\PaymentRequestController;
use Modules\Wallet\Http\Controllers\Dashboard\PaymentMethodController;

Route::group(['prefix' => 'dashboard', 'middleware' => ['web', 'auth', 'ensure-store-access', 'check.store.status', 'check-permission']], function () {
    Route::get('payment-requests', [PaymentRequestController::class, 'index'])
        ->name('dashboard.payment-requests.index');

    Route::put('payment-requests/{id}/update', [PaymentRequestController::class, 'update'])
        ->name('dashboard.payment-requests.update');

    // Payment Methods Routes
    Route::resource('payment-methods', PaymentMethodController::class)->names('dashboard.payment-methods');
    Route::patch('payment-methods/{paymentMethod}/toggle-status', [PaymentMethodController::class, 'toggleStatus'])
        ->name('dashboard.payment-methods.toggle-status');
});
