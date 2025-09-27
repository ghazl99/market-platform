<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Admin\ProductController;

Route::middleware(['auth', 'ensure-store-access', 'check.store.status', 'check-permission'])->group(function () {
    Route::resource('products', ProductController::class)->names('product');
});
