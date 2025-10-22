<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Core\Http\Controllers\DashboardController;
use Modules\Core\Http\Controllers\HomeController;
use Modules\Core\Http\Controllers\NotificationController;

Route::middleware('check.store.status')->group(function () {
    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'ensure-store-access', 'check.store.status', 'check-permission']);

    // Dashboard Notifications
    Route::middleware(['auth', 'ensure-store-access', 'check.store.status', 'check-permission'])->group(function () {
        Route::get('/dashboard/notifications', [NotificationController::class, 'index'])->name('dashboard.notifications');
        Route::get('/dashboard/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('dashboard.notifications.read');
        Route::delete('/dashboard/notifications/{id}', [NotificationController::class, 'destroy'])->name('dashboard.notifications.destroy');
        Route::get('/dashboard/notifications/count', [NotificationController::class, 'getUnreadCount'])->name('dashboard.notifications.count');
    });

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
