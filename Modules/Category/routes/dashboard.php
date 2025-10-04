<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\Dashboard\CategoryController;

Route::middleware(['auth', 'ensure-store-access', 'check.store.status', 'check-permission'])->group(function () {
    // Route مخصص لعرض الأقسام الفرعية (يجب أن يكون قبل resource)
    Route::get('/categories/parent/{parent}', [CategoryController::class, 'index'])->name('category.index.parent');

    Route::resource('categories', CategoryController::class)->names('category');

    // Route to show category image by media ID
    Route::get('/category/image/{media}', [CategoryController::class, 'showImage'])->name('category.image');
});
