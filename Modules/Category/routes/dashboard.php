<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\Dashboard\CategoryController;

Route::middleware(['auth', 'ensure-store-access', 'check.store.status', 'check-permission'])->group(function () {
    Route::resource('categories', CategoryController::class)->names('category');
});
