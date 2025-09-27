<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\App\CartController;

Route::middleware(['customer.auth'])->group(function () {
    Route::resource('carts', CartController::class)->names('carts');
});
