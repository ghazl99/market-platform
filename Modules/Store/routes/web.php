<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\App\StoreController;
use Modules\Store\Http\Controllers\Admin\StoreSettingsController;

Route::middleware(['auth'])->group(function () {
    Route::resource('stores', StoreController::class)->names('stores');
    // Store Settings Management
    Route::get('/store/settings/{store}/edit', [StoreSettingsController::class, 'editSettings'])->name('store.settings.edit');
    Route::put('/store/settings/{store}', [StoreSettingsController::class, 'updateSettings'])->name('store.settings.update');
});

Route::get('/store/image/{media}', [StoreController::class, 'showImage'])->name('store.image');
