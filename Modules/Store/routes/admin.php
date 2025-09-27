<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\Admin\StoreController;

Route::middleware(['auth'])->group(function () {

    // Store Management
    Route::resource('stores', StoreController::class)->names('stores');
    Route::patch('/stores/{id}/status', [StoreController::class, 'updateStoreStatus'])->name('stores.status');

});
