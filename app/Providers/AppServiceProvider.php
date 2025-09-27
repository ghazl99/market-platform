<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Paginator::useBootstrap();

        View::composer([
            'core::dashboard.layouts.app',
            'core::store.layouts.app',
        ], function ($view) {
            $host = request()->getHost();
            $store = app(\Modules\Core\Services\Admin\HomeService::class)->getStoreByHost($host);
            $view->with('store', $store);
        });
    }
}
