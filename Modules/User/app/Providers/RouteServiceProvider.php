<?php

namespace Modules\User\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'User';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapAuthRoutes();
        $this->mapAdminRoutes();
        $this->mapDashboardRoutes();

        // Add global login route for compatibility with Laravel's built-in auth middleware
        $this->addGlobalLoginRoute();
    }

    /**
     * Add global login route alias
     */
    protected function addGlobalLoginRoute(): void
    {
        Route::get('login', function() {
            return redirect()->route('auth.login');
        })->name('login');
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->group(module_path($this->name, 'routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
        Route::middleware('api')->prefix('api')->name('api.')->group(module_path($this->name, 'routes/api.php'));
    }

    /**
     * Define the "auth" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapAuthRoutes(): void
    {
        $name = $this->name;
        Route::group([
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
        ], static function () use ($name) {
            Route::prefix('auth')
                ->name('auth.')
                ->middleware(['web'])
                ->group(module_path($name, '/routes/auth.php'));
        });
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapAdminRoutes(): void
    {
        $name = $this->name;
        Route::group([
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
        ], static function () use ($name) {
            Route::prefix('admin')
                ->name('admin.')
                ->middleware(['web'])
                ->group(module_path($name, '/routes/admin.php'));
        });
    }

    /** Define the "dashboard" routes for the application. */
    protected function mapDashboardRoutes(): void
    {
        $name = $this->name;
        Route::group([
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
        ], static function () use ($name) {
            Route::prefix('dashboard')
                ->name('dashboard.')
                ->middleware(['web'])
                ->group(module_path($name, '/routes/dashboard.php'));
        });
    }
}
