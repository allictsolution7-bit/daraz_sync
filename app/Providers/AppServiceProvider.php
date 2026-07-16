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

        // Run code modification checker (Telegram Alerts on local code changes)
        $this->checkCodeChanges();
    }

    /**
     * Scan critical directories for code changes and send a Telegram notification
     */
    private function checkCodeChanges(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        try {
            $cacheKey = 'code_monitor_file_states';
            $pathsToScan = [
                app_path(),
                base_path('routes'),
                config_path(),
            ];

            $currentStates = [];
            foreach ($pathsToScan as $path) {
                if (!file_exists($path)) {
                    continue;
                }

                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS)
                );

                foreach ($iterator as $file) {
                    if ($file->isFile() && $file->getExtension() === 'php') {
                        $currentStates[$file->getPathname()] = $file->getMTime();
                    }
                }
            }

            $cachedStates = \Illuminate\Support\Facades\Cache::get($cacheKey);

            if ($cachedStates === null) {
                // Save current state as baseline
                \Illuminate\Support\Facades\Cache::put($cacheKey, $currentStates, 86400 * 30);
                return;
            }

            $changedFiles = [];
            foreach ($currentStates as $file => $mtime) {
                if (!isset($cachedStates[$file])) {
                    $changedFiles[] = "🆕 [NEW] " . str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
                } elseif ($cachedStates[$file] !== $mtime) {
                    $changedFiles[] = "✏️ [MODIFIED] " . str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
                }
            }

            // Also check for deleted files
            foreach ($cachedStates as $file => $mtime) {
                if (!isset($currentStates[$file])) {
                    $changedFiles[] = "❌ [DELETED] " . str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
                }
            }

            if (!empty($changedFiles)) {
                // Update cache immediately to prevent infinite notification loops
                \Illuminate\Support\Facades\Cache::put($cacheKey, $currentStates, 86400 * 30);

                // Send Telegram Notification
                $telegramService = new \App\Services\TelegramNotificationService();
                $message = "⚠️ <b>Code Modification Detected!</b>\n\n"
                    . "🖥️ Host: <b>" . request()->getHost() . "</b>\n"
                    . "📅 Time: <b>" . now()->format('Y-m-d H:i:s') . "</b>\n\n"
                    . "📂 <b>Changes:</b>\n"
                    . implode("\n", array_slice($changedFiles, 0, 10));

                if (count($changedFiles) > 10) {
                    $message .= "\n... and " . (count($changedFiles) - 10) . " more files.";
                }

                $telegramService->sendGeneralMessage($message);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Code monitor failed: ' . $e->getMessage());
        }
    }
}
