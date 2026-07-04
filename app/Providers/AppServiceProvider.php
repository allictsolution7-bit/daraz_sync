<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\View\Composers\ProductSettingsComposer;

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
    public function boot(): void
    {
        Blade::if('perm', function ($permission) {
            return Auth::check() && Auth::user()->can($permission);
        });

        Blade::directive('menu', function ($expression) {
            return "<?php echo App\Helpers\MenuHelper::renderMenu($expression); ?>";
        });

        Paginator::useBootstrapFive();
        
        // Register View Composer for product settings
        // This caches settings and makes them available to product-item partial
        View::composer('frontend.partials.product-item', ProductSettingsComposer::class);

        // Register Observers
        \App\Models\order::observe(\App\Observers\OrderObserver::class);
        \App\Models\order_item::observe(\App\Observers\OrderItemObserver::class);
    }
}
