<?php

namespace Modules\Daraz\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Module namespace for controllers
     */
    protected string $moduleNamespace = 'Modules\\Daraz\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
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
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    /**
     * Define the "web" routes for the module.
     * NO license middleware - this is a free module.
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware(['web', 'auth'])
            ->prefix('admin/daraz')
            ->name('admin.daraz.')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Daraz', 'Routes/web.php'));
    }

    /**
     * Define the "api" routes for the module.
     */
    protected function mapApiRoutes(): void
    {
        $apiRoutesPath = module_path('Daraz', 'Routes/api.php');

        if (file_exists($apiRoutesPath)) {
            Route::prefix('api/daraz')
                ->middleware('api')
                ->namespace($this->moduleNamespace)
                ->group($apiRoutesPath);
        }
    }
}
