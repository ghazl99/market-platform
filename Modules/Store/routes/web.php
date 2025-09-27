<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\App\StoreController;

Route::middleware(['auth'])->group(function () {
    Route::resource('stores', StoreController::class)->names('stores');
});

Route::get('/store/image/{media}', [StoreController::class, 'showImage'])->name('store.image');
