<?php

use Illuminate\Support\Facades\Route;
use Modules\Permission\Http\Controllers\PermissionController;

Route::group(['prefix' => 'dashboard', 'middleware' => ['web', 'auth']], function () {
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permission.index');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permission.store');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permission.update');
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');
});
