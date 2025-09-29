<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\Http\Controllers\App\BalanceController;
use Modules\Wallet\Http\Controllers\App\WalletController;

Route::middleware(['customer.auth','ensure-store-access','check.store.status'])->group(function () {
    Route::resource('wallets', WalletController::class)->names('wallet');
});
Route::middleware(['customer.auth','ensure-store-access','check.store.status'])->group(function () {
    Route::resource('balance', BalanceController::class)->names('balance');
});
