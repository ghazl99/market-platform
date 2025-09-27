<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Admin\UserController;

// User Management
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::patch('/users/{id}/admin', [UserController::class, 'toggleAdminStatus'])->name('users.admin');
