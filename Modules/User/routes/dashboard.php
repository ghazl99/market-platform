<?php

use Illuminate\Support\Facades\Route;

Route::middleware('check.store.status', 'ensure-store-access', 'check-permission')->group(function () {
    Route::resource('staff', \Modules\User\Http\Controllers\Dashboard\StaffController::class)
        ->names('staff')
        ->parameters(['staff' => 'user']);

    Route::post('/staff/{user}/toggle-activation', [\Modules\User\Http\Controllers\Dashboard\StaffController::class, 'toggleActivation'])
        ->name('staff.toggleActivation');
    Route::resource('customer', \Modules\User\Http\Controllers\Dashboard\CustomerController::class)->names('customer');
});
