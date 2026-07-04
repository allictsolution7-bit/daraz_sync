<?php

namespace Modules\Daraz\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\Product;
use App\Models\VariationCombination;
use Modules\Daraz\Services\DarazStockSyncService;

class DarazServiceProvider extends ServiceProvider
{
    /**
     * Module name
     */
    protected string $moduleName = 'Daraz';

    /**
     * Module namespace
     */
    protected string $moduleNamespace = 'Modules\\Daraz\\Http\\Controllers';

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        // Register services as singletons
        $this->app->singleton(DarazStockSyncService::class, function ($app) {
            return new DarazStockSyncService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrations();
        $this->registerStockObservers();
        $this->registerCommands();
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = module_path($this->moduleName, 'Config/config.php');

        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'daraz');
        }

        $this->publishes([
            $configPath => config_path('daraz.php'),
        ], 'daraz-config');
    }

    /**
     * Register views.
     */
    protected function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . strtolower($this->moduleName));
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', strtolower($this->moduleName) . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), strtolower($this->moduleName));
    }

    /**
     * Load migrations.
     */
    protected function loadMigrations(): void
    {
        $migrationPath = module_path($this->moduleName, 'Database/Migrations');

        if (is_dir($migrationPath)) {
            $this->loadMigrationsFrom($migrationPath);
        }
    }

    /**
     * Register stock observers for real-time sync.
     * These only run when module is enabled.
     */
    protected function registerStockObservers(): void
    {
        // Listen for Product quantity changes (simple products)
        Product::updated(function ($product) {
            if ($product->isDirty('quantity') && $product->quantity !== null) {
                $this->handleStockChange($product);
            }
        });

        // Listen for VariationCombination stock changes (variable products)
        VariationCombination::updated(function ($combination) {
            if ($combination->isDirty('stock_quantity')) {
                $this->handleStockChange($combination->product, $combination);
            }
        });
    }

    /**
     * Handle stock change and trigger sync.
     */
    protected function handleStockChange(Product $product, ?VariationCombination $combination = null): void
    {
        try {
            $syncService = app(DarazStockSyncService::class);
            $syncService->queueStockSync($product, $combination);
        } catch (\Exception $e) {
            \Log::error('Daraz: Failed to queue stock sync', [
                'product_id' => $product->id,
                'combination_id' => $combination?->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Register Artisan commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\Daraz\Console\Commands\DarazSyncCommand::class,
                \Modules\Daraz\Console\Commands\DarazCleanLogsCommand::class,
            ]);
        }
    }

    /**
     * Register scheduled tasks.
     * Call this from app/Console/Kernel.php if module is enabled.
     */
    public static function schedule(\Illuminate\Console\Scheduling\Schedule $schedule): void
    {
        // Sync due stores every 15 minutes
        $schedule->command('daraz:sync --queue')
            ->everyFifteenMinutes()
            ->withoutOverlapping()
            ->runInBackground();

        // Clean old logs daily
        $schedule->command('daraz:clean-logs')
            ->daily()
            ->at('03:00');
    }

    /**
     * Get publishable view paths.
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . strtolower($this->moduleName))) {
                $paths[] = $path . '/modules/' . strtolower($this->moduleName);
            }
        }
        return $paths;
    }
}
