<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Core\Http\Controllers\DashboardController;
use Modules\Core\Http\Controllers\HomeController;

Route::middleware('check.store.status')->group(function () {
    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'ensure-store-access', 'check.store.status', 'check-permission']);

    Route::get('/demo', [CoreController::class, 'demo'])->name('demo');
});
// without middleware because home page need to access by public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('admin-dashboard', [DashboardController::class, 'dashboadAdmin'])->name('admin.dashboard')->middleware(['auth']);
// app
Route::get('/services', [CoreController::class, 'services'])->name('services');
Route::get('/pricing', [CoreController::class, 'pricing'])->name('pricing');
Route::get('/contact', [CoreController::class, 'contact'])->name('contact');
// Route::get('/demo/{storeId}', [DemoController::class, 'show'])->name('demo.store');
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::resource('cores', CoreController::class)->names('core');
// });
