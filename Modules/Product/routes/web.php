<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\App\ProductController;

Route::middleware('check.store.status')->group(function () {

    Route::resource('products', ProductController::class)->names('product');
    // Route to show product image by media ID
    Route::get('/product/image/{media}', [ProductController::class, 'showImage'])->name('product.image');
});
