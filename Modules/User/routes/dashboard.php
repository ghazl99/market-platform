<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::resource('staff', \Modules\User\Http\Controllers\Dashboard\StaffController::class)
        ->names('staff')
        ->parameters(['staff' => 'user']);

    Route::post('/staff/{user}/toggle-activation', [\Modules\User\Http\Controllers\Dashboard\StaffController::class, 'toggleActivation'])
        ->name('staff.toggleActivation');

        // Customer routes
        Route::get('/customer', [\Modules\User\Http\Controllers\Dashboard\CustomerController::class, 'index'])->name('customer.index');
        Route::get('/customer/create', [\Modules\User\Http\Controllers\Dashboard\CustomerController::class, 'create'])->name('customer.create');
        Route::post('/customer', [\Modules\User\Http\Controllers\Dashboard\CustomerController::class, 'store'])->name('customer.store');
        Route::get('/customer/{customer}', [\Modules\User\Http\Controllers\Dashboard\CustomerController::class, 'show'])->name('customer.show');
        Route::get('/customer/{customer}/edit', [\Modules\User\Http\Controllers\Dashboard\CustomerController::class, 'edit'])->name('customer.edit');
        Route::put('/customer/{customer}', [\Modules\User\Http\Controllers\Dashboard\CustomerController::class, 'update'])->name('customer.update');
        Route::delete('/customer/{customer}', [\Modules\User\Http\Controllers\Dashboard\CustomerController::class, 'destroy'])->name('customer.destroy');

});
