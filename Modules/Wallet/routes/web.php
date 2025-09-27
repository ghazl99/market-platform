<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\Http\Controllers\App\WalletController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('wallets', WalletController::class)->names('wallet');
});
