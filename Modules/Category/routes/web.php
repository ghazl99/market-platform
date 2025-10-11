<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\App\CategoryController;

Route::middleware('check.store.status')->group(function () {

    Route::resource('categories', CategoryController::class)->names('category');
    // Route to show category image by media ID
    Route::get('/category/image/{media}', [CategoryController::class, 'showImage'])->name('category.image');
    Route::get('/category/{id}/subCategories', [CategoryController::class, 'getSubCategoryById'])->name('category.subCategories');
});
