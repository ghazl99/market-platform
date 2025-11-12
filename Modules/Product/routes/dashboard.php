<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\Admin\ProductController;

Route::middleware(['web', 'auth', 'ensure-store-access', 'check.store.status'])->group(function () {
    Route::resource('products', ProductController::class)->names([
        'index' => 'product.index',
        'create' => 'product.create',
        'store' => 'product.store',
        'show' => 'product.show',
        'edit' => 'product.edit',
        'update' => 'product.update',
        'destroy' => 'product.destroy',
    ]);

    // Route لعرض منتجات قسم معين
    Route::get('/products/category/{category}', [ProductController::class, 'index'])->name('product.category');

    // Route to show product image by media ID
    Route::get('/product/image/{media}', [ProductController::class, 'showImage'])->name('product.image');
});
