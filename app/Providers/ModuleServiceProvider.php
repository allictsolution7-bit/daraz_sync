<?php

namespace App\Providers;

use App\Services\Modules\ModuleManager;
use App\Services\Modules\Module;
use App\Services\LicenseService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Module Manager as singleton
        $this->app->singleton(ModuleManager::class, function ($app) {
            return new ModuleManager($app->make(LicenseService::class));
        });

        // Register modules config
        $this->mergeConfigFrom(
            base_path('config/modules.php'),
            'modules'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Ensure Modules directory exists
        $modulesPath = base_path('Modules');
        if (!File::isDirectory($modulesPath)) {
            File::makeDirectory($modulesPath, 0755, true);
        }

        // Register Blade directives for modules
        $this->registerBladeDirectives();

        // Register module service providers
        $this->registerModuleProviders();

        // Share module data with views
        $this->shareModuleDataWithViews();
    }

    /**
     * Register Blade directives for module checks
     */
    protected function registerBladeDirectives(): void
    {
        // @module('ModuleName') ... @endmodule
        Blade::directive('module', function ($expression) {
            return "<?php if(\\App\\Services\\Modules\\Module::isEnabled({$expression})): ?>";
        });

        Blade::directive('endmodule', function () {
            return "<?php endif; ?>";
        });

        // @moduleDisabled('ModuleName') ... @endmoduleDisabled
        Blade::directive('moduleDisabled', function ($expression) {
            return "<?php if(\\App\\Services\\Modules\\Module::isDisabled({$expression})): ?>";
        });

        Blade::directive('endmoduleDisabled', function () {
            return "<?php endif; ?>";
        });

        // @hasModule('ModuleName') ... @endhasModule (checks if installed, not enabled)
        Blade::directive('hasModule', function ($expression) {
            return "<?php if(\\App\\Services\\Modules\\Module::has({$expression})): ?>";
        });

        Blade::directive('endhasModule', function () {
            return "<?php endif; ?>";
        });
    }

    /**
     * Register service providers from enabled modules
     */
    protected function registerModuleProviders(): void
    {
        $modulesPath = base_path('Modules');

        if (!File::isDirectory($modulesPath)) {
            return;
        }

        $directories = File::directories($modulesPath);

        foreach ($directories as $directory) {
            $moduleName = basename($directory);

            // Only load providers for enabled modules
            if (!Module::isEnabled($moduleName)) {
                continue;
            }

            // Load module.json to get provider class
            $moduleJsonPath = $directory . '/module.json';
            if (File::exists($moduleJsonPath)) {
                $moduleConfig = json_decode(File::get($moduleJsonPath), true);

                // Register providers
                $providers = $moduleConfig['providers'] ?? [];
                foreach ($providers as $provider) {
                    if (class_exists($provider)) {
                        $this->app->register($provider);
                    }
                }
            }

            // Auto-discover main provider if exists
            $mainProvider = "Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider";
            if (class_exists($mainProvider)) {
                $this->app->register($mainProvider);
            }
        }
    }

    /**
     * Share module data with all views
     */
    protected function shareModuleDataWithViews(): void
    {
        View::composer('*', function ($view) {
            // Make Module helper available in all views
            $view->with('Module', new class {
                public function has(string $name): bool
                {
                    return Module::has($name);
                }

                public function isEnabled(string $name): bool
                {
                    return Module::isEnabled($name);
                }

                public function isDisabled(string $name): bool
                {
                    return Module::isDisabled($name);
                }
            });
        });
    }
}
