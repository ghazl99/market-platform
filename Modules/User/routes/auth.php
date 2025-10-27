<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Auth\AuthenticatedSessionController;
use Modules\User\Http\Controllers\Auth\Customer\LoginController;
use Modules\User\Http\Controllers\Auth\Customer\ProfileController;
use Modules\User\Http\Controllers\Auth\Customer\RegisterController;
use Modules\User\Http\Controllers\Auth\PassWordController;
use Modules\User\Http\Controllers\Auth\RegisteredUserController;

Route::middleware('guest')->group(function () {

    // ...existing code for guest (register/login)  to owner...
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.post');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'login'])->name('login.post');

    // ...existing code for guest (register/login)  to customer...
    Route::get('register-customer', [RegisterController::class, 'create'])
        ->name('customer.register.get');
    Route::post('register-customer', [RegisterController::class, 'store'])->name('customer.register.post');
    Route::get('login-customer', [LoginController::class, 'create'])
        ->name('customer.login.get');
    Route::post('login-customer', [AuthenticatedSessionController::class, 'customerLogin'])->name('customer.login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
Route::middleware(['customer.auth', 'ensure-store-access', 'check.store.status'])->group(function () {
    Route::resource('profile', ProfileController::class)->names('profile');
    Route::get('security', [ProfileController::class, 'index'])
        ->name('security');

    Route::get('sessions', [ProfileController::class, 'activeSessions'])
        ->name('sessions');

    Route::delete('/sessions/{id}', [ProfileController::class, 'destroy'])
        ->name('sessions.destroy');

    Route::get('/change-password', [PassWordController::class, 'changePasswordForm'])
        ->name('change-password');

    Route::post('/change-password', [PassWordController::class, 'changePassword'])
        ->name('password.update');
});
