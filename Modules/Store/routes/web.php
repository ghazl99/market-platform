<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\App\StoreController;
use Modules\Store\Http\Controllers\Admin\StoreSettingsController;

Route::middleware(['auth'])->group(function () {
    Route::resource('stores', StoreController::class)->names('stores');
    // Store Settings Management
    Route::get('/store/settings/{store}/edit', [StoreSettingsController::class, 'editSettings'])->name('store.settings.edit');
    Route::put('/store/settings/{store}', [StoreSettingsController::class, 'updateSettings'])->name('store.settings.update');
    // Store Theme Management
    Route::put('/store/theme/{store}', [StoreSettingsController::class, 'updateTheme'])->name('store.theme.update');
});

// Providers Management (Dashboard)
Route::middleware(['web', 'auth', 'ensure-store-access', 'check.store.status', 'check-permission'])->group(function () {
    Route::get('/dashboard/providers', [\Modules\Store\Http\Controllers\Admin\ProviderController::class, 'index'])->name('dashboard.providers.index');
    Route::post('/dashboard/providers', [\Modules\Store\Http\Controllers\Admin\ProviderController::class, 'store'])->name('dashboard.providers.store');
    Route::delete('/dashboard/providers/{provider}', [\Modules\Store\Http\Controllers\Admin\ProviderController::class, 'destroy'])->name('dashboard.providers.destroy');
    Route::put('/dashboard/providers/{provider}/toggle-status', [\Modules\Store\Http\Controllers\Admin\ProviderController::class, 'toggleStatus'])->name('dashboard.providers.toggle-status');

    // Get products from provider API
    Route::get('/dashboard/providers/{provider}/products', [\Modules\Store\Http\Controllers\Admin\ProviderController::class, 'getProducts'])->name('providers.products');
});

Route::get('/store/image/{media}', [StoreController::class, 'showImage'])->name('store.image');
