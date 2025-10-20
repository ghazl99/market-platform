<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\Http\Controllers\App\WalletController;
use Modules\Wallet\Http\Controllers\App\PaymentMethodController;

Route::middleware(['customer.auth', 'ensure-store-access', 'check.store.status'])->group(function () {
    Route::get('wallets', [WalletController::class, 'index'])->name('wallet.index');

    Route::resource('payment-method', PaymentMethodController::class)->names('payment-method');
    Route::post('convert-currency', [PaymentMethodController::class, 'convertCurrency'])->name('convert.currency');

    Route::post('/payment-request', [\Modules\Wallet\Http\Controllers\App\PaymentRequestController::class, 'store'])
        ->name('payment-request.store');

    Route::post('payment-requests/{id}/update', [\Modules\Wallet\Http\Controllers\App\PaymentRequestController::class, 'update'])
        ->name('payment-requests.update');
    Route::get('/wallet/payment-requests', [\Modules\Wallet\Http\Controllers\App\PaymentRequestController::class, 'index'])
        ->name('wallet.payment-requests.index');
});
Route::get('/payment-methode/image/{media}', [PaymentMethodController::class, 'showImage'])->name('payment.methode.image');
