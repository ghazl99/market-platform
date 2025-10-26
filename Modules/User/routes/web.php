<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/profile-photo/image/{media}', [AuthenticatedSessionController::class, 'showImage'])->name('profile-photo.image');
