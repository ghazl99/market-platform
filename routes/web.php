<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Dashboard\GroupController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Admin Groups Routes with localization support
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function() {
    Route::middleware(['auth', 'role:owner'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('groups', GroupController::class);
    });
});
