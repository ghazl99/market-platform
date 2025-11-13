<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Paginator::useBootstrap();

        View::composer([
            'core::dashboard.layouts.app'
        ], function ($view) {
            $host = request()->getHost();
            $store = app(\Modules\Core\Services\Admin\HomeService::class)->getStoreByHost($host);
            $view->with('store', $store);
        });
    }
}
