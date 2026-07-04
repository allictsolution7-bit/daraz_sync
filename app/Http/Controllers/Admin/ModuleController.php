<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Modules\ModuleManager;
use App\Services\Modules\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class ModuleController extends Controller
{
    protected ModuleManager $moduleManager;

    public function __construct(ModuleManager $moduleManager)
    {
        $this->moduleManager = $moduleManager;
    }

    /**
     * Display list of all modules (built-in tools + system modules)
     */
    public function index()
    {
        // Get system modules (installable modules from config)
        $systemModules = $this->moduleManager->getAllWithStatus();

        // Format system modules
        $formattedSystemModules = [];
        foreach ($systemModules as $name => $module) {
            $formattedSystemModules[] = [
                'name' => $name,
                'display_name' => $module['display_name'],
                'description' => $module['description'],
                'version' => $module['version'],
                'status' => $module['status'],
                'is_enabled' => $module['is_enabled'],
                'is_licensed' => $module['is_licensed'],
                'is_premium' => $module['is_premium'],
                'exists' => $module['exists'],
                'icon' => $this->getModuleIcon($name),
                'color' => $this->getModuleColor($name),
                'route' => $this->getModuleRoute($name),
                'is_system_module' => true,
            ];
        }

        // Built-in tools (always active, part of core system)
        $builtInTools = $this->getBuiltInTools();

        return view('admin.modules.index', [
            'builtInTools' => $builtInTools,
            'systemModules' => $formattedSystemModules,
        ]);
    }

    /**
     * Get built-in tools that are always active
     */
    protected function getBuiltInTools(): array
    {
        $tools = [
            [
                'key' => 'landing-pages',
                'name' => 'Landing Page Builder',
                'icon' => 'fas fa-palette',
                'description' => 'Create and manage custom landing pages with drag-and-drop builder.',
                'route_name' => 'admin.landing-pages.index',
                'color' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            ],
            [
                'key' => 'incomplete-orders',
                'name' => 'Incomplete Orders',
                'icon' => 'fas fa-shopping-basket',
                'description' => 'Track and manage incomplete orders, abandoned carts, and conversions.',
                'route_name' => 'admin.incomplete-orders.index',
                'color' => 'linear-gradient(135deg, #ffd89b 0%, #19547b 100%)',
            ],
            [
                'key' => 'telegram-notification',
                'name' => 'Telegram Notification',
                'icon' => 'fab fa-telegram',
                'description' => 'Configure Telegram bot settings for order notifications and updates.',
                'route_name' => 'admin.telegram-settings.index',
                'color' => 'linear-gradient(135deg, #0088cc 0%, #00a8e8 100%)',
            ],
            [
                'key' => 'roles-permissions',
                'name' => 'Roles & Permissions',
                'icon' => 'fas fa-user-lock',
                'description' => 'Manage user roles, permissions, and access control for your system.',
                'route_name' => 'admin.roles_permissions.index',
                'color' => 'linear-gradient(135deg, #434343 0%, #000000 100%)',
            ],
            [
                'key' => 'pos',
                'name' => 'Point of Sale (POS)',
                'icon' => 'fas fa-cash-register',
                'description' => 'Point of sale system for in-store sales and quick order processing.',
                'route_name' => 'admin.pos.index',
                'color' => 'linear-gradient(135deg, #30cfd0 0%, #330867 100%)',
            ],
            [
                'key' => 'multi-seller',
                'name' => 'Multi Seller',
                'icon' => 'fas fa-store',
                'description' => 'Manage vendors, verify sellers, and handle vendor products and commissions.',
                'route_name' => 'admin.vendors.index',
                'color' => 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)',
            ],
            [
                'key' => 'fraud-protection',
                'name' => 'Fraud Protection',
                'icon' => 'fas fa-shield-alt',
                'description' => 'Advanced fraud detection and prevention system for your orders.',
                'route_name' => 'admin.fraud-protection.index',
                'color' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
            ],
            [
                'key' => 'inventory',
                'name' => 'Inventory Management',
                'icon' => 'fas fa-boxes',
                'description' => 'Track stock levels, manage inventory, and get low stock alerts.',
                'route_name' => 'admin.inventory.index',
                'color' => 'linear-gradient(135deg, #28a745 0%, #20c997 100%)',
            ],
            [
                'key' => 'woocommerce-migration',
                'name' => 'WooCommerce Migration',
                'icon' => 'fas fa-exchange-alt',
                'description' => 'Migrate orders, products, users, and categories from WooCommerce to your website.',
                'route_name' => 'admin.woocommerce-migration.index',
                'color' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            ],
            [
                'key' => 'delivery',
                'name' => 'Courier Integration',
                'icon' => 'fas fa-shipping-fast',
                'description' => 'Integrate with Pathao, Steadfast, and other courier services for automated shipping.',
                'route_name' => 'admin.delivery.index',
                'color' => 'linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%)',
            ],
            [
                'key' => 'fraud-checker',
                'name' => 'Fraud Checker',
                'icon' => 'fas fa-shield-alt',
                'description' => 'Check customer phone numbers and prevent fraudulent orders.',
                'route_name' => 'admin.fraud-checker.index',
                'color' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            ],
            [
                'key' => 'backup',
                'name' => 'Backup System',
                'icon' => 'fas fa-database',
                'description' => 'Automated database backups and restore functionality.',
                'route_name' => 'admin.backup.settings',
                'color' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            ],
            [
                'key' => 'shipping-rules',
                'name' => 'Shipping Rules',
                'icon' => 'fas fa-truck',
                'description' => 'Configure shipping rules and rates for different zones and conditions.',
                'route_name' => 'admin.shipping.rules.index',
                'color' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
            ],
            [
                'key' => 'blog',
                'name' => 'Blog Management',
                'icon' => 'fas fa-blog',
                'description' => 'Manage blog posts, categories, and content for your website.',
                'route_name' => 'admin.post.index',
                'color' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
            ],
            [
                'key' => 'sliders',
                'name' => 'Sliders',
                'icon' => 'fas fa-images',
                'description' => 'Manage homepage sliders and banners to showcase your products.',
                'route_name' => 'admin.sliders.index',
                'color' => 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)',
            ],
            [
                'key' => 'subscriptions',
                'name' => 'Subscriptions',
                'icon' => 'fas fa-envelope',
                'description' => 'View and manage email newsletter subscriptions from customers.',
                'route_name' => 'admin.subscriptions.index',
                'color' => 'linear-gradient(135deg, #ffc3a0 0%, #ffafbd 100%)',
            ],
            [
                'key' => 'contact-messages',
                'name' => 'Contact Messages',
                'icon' => 'fas fa-comments',
                'description' => 'View and manage contact form messages and inquiries.',
                'route_name' => 'admin.contacts.index',
                'color' => 'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)',
            ],
            [
                'key' => 'combo-offers',
                'name' => 'Combo Offers',
                'icon' => 'fas fa-gift',
                'description' => 'Create and manage combo offers, bundle deals, and special product packages.',
                'route_name' => 'admin.combo_offers.index',
                'color' => 'linear-gradient(135deg, #f6d365 0%, #fda085 100%)',
            ],
        ];

        // Process tools - only include those with existing routes
        $processedTools = [];
        foreach ($tools as $tool) {
            $routeName = $tool['route_name'];
            if (\Route::has($routeName)) {
                $tool['route'] = route($routeName);
                $tool['active'] = true;
                unset($tool['route_name']);
                $processedTools[] = $tool;
            }
        }

        return $processedTools;
    }

    /**
     * Enable a module
     */
    public function enable(Request $request, string $name)
    {
        if (!$this->moduleManager->has($name)) {
            return response()->json([
                'success' => false,
                'message' => "Module '{$name}' not found."
            ], 404);
        }

        $status = $this->moduleManager->getModuleStatus($name);

        if (!$status['is_licensed']) {
            return response()->json([
                'success' => false,
                'message' => "Module '{$name}' is not licensed. Please upgrade your license."
            ], 403);
        }

        if ($this->moduleManager->enable($name)) {
            // Run module migrations
            $this->runModuleMigrations($name);

            return response()->json([
                'success' => true,
                'message' => "Module '{$name}' has been enabled successfully."
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Failed to enable module '{$name}'."
        ], 500);
    }

    /**
     * Disable a module
     */
    public function disable(Request $request, string $name)
    {
        if (!$this->moduleManager->has($name)) {
            return response()->json([
                'success' => false,
                'message' => "Module '{$name}' not found."
            ], 404);
        }

        if ($this->moduleManager->disable($name)) {
            return response()->json([
                'success' => true,
                'message' => "Module '{$name}' has been disabled."
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Failed to disable module '{$name}'."
        ], 500);
    }

    /**
     * Upload and install a module
     */
    public function upload(Request $request)
    {
        $request->validate([
            'module' => 'required|file|mimes:zip|max:51200', // 50MB max
        ]);

        $file = $request->file('module');
        $tempPath = storage_path('app/temp');

        // Ensure temp directory exists
        if (!File::isDirectory($tempPath)) {
            File::makeDirectory($tempPath, 0755, true);
        }

        $zipPath = $file->storeAs('temp', 'module_upload.zip');
        $fullZipPath = storage_path('app/' . $zipPath);

        try {
            $zip = new ZipArchive();

            if ($zip->open($fullZipPath) !== true) {
                throw new \Exception('Failed to open zip file.');
            }

            // Find module.json to determine module name
            $moduleJson = null;
            $moduleName = null;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if (preg_match('/^([^\/]+)\/module\.json$/', $filename, $matches)) {
                    $moduleName = $matches[1];
                    $moduleJson = json_decode($zip->getFromIndex($i), true);
                    break;
                }
                // Also check for root-level module.json
                if ($filename === 'module.json') {
                    $moduleJson = json_decode($zip->getFromIndex($i), true);
                    $moduleName = $moduleJson['name'] ?? null;
                    break;
                }
            }

            if (!$moduleJson || !$moduleName) {
                $zip->close();
                throw new \Exception('Invalid module: module.json not found.');
            }

            // Validate module.json structure
            if (empty($moduleJson['name']) || empty($moduleJson['version'])) {
                $zip->close();
                throw new \Exception('Invalid module.json: name and version are required.');
            }

            // Check version compatibility - module version must match core version
            $coreVersion = config('app.version', '1.0.0');
            $moduleVersion = $moduleJson['version'];

            if ($moduleVersion !== $coreVersion) {
                $zip->close();
                return response()->json([
                    'success' => false,
                    'version_mismatch' => true,
                    'message' => "Version mismatch: This module requires version {$moduleVersion}, but your system is running version {$coreVersion}. Please update your core system first.",
                    'module_version' => $moduleVersion,
                    'core_version' => $coreVersion,
                ], 400);
            }

            $modulesPath = base_path('Modules');
            $targetPath = $modulesPath . '/' . $moduleName;

            // Check if module already exists
            $isUpdate = File::isDirectory($targetPath);

            if ($isUpdate) {
                // Backup existing module
                $backupPath = storage_path('app/module_backups/' . $moduleName . '_' . date('Y-m-d_His'));
                File::copyDirectory($targetPath, $backupPath);

                // Remove existing module
                File::deleteDirectory($targetPath);
            }

            // Extract to modules directory
            $zip->extractTo($modulesPath);
            $zip->close();

            // If zip contained files in root (not in a folder), move them
            if (!File::isDirectory($targetPath)) {
                // Files were extracted to root, need to organize
                File::makeDirectory($targetPath, 0755, true);
                // Move files - this handles edge cases
            }

            // Run composer dump-autoload
            $this->runComposerDumpAutoload();

            // Run module migrations
            $this->runModuleMigrations($moduleName);

            // Enable the module by default
            $this->moduleManager->enable($moduleName);

            // Cleanup
            File::delete($fullZipPath);

            $message = $isUpdate
                ? "Module '{$moduleName}' has been updated successfully."
                : "Module '{$moduleName}' has been installed successfully.";

            return response()->json([
                'success' => true,
                'message' => $message,
                'module' => $moduleJson
            ]);

        } catch (\Exception $e) {
            // Cleanup on failure
            if (File::exists($fullZipPath)) {
                File::delete($fullZipPath);
            }

            Log::error('Module upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Module installation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete/uninstall a module
     */
    public function delete(Request $request, string $name)
    {
        if (!$this->moduleManager->has($name)) {
            return response()->json([
                'success' => false,
                'message' => "Module '{$name}' not found."
            ], 404);
        }

        $modulePath = base_path('Modules/' . $name);

        try {
            // Disable module first
            $this->moduleManager->disable($name);

            // Backup before deleting
            $backupPath = storage_path('app/module_backups/' . $name . '_' . date('Y-m-d_His'));
            File::copyDirectory($modulePath, $backupPath);

            // Delete module directory
            File::deleteDirectory($modulePath);

            // Run composer dump-autoload
            $this->runComposerDumpAutoload();

            return response()->json([
                'success' => true,
                'message' => "Module '{$name}' has been uninstalled. Backup saved."
            ]);

        } catch (\Exception $e) {
            Log::error('Module deletion failed', [
                'module' => $name,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to uninstall module: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sync modules with license
     */
    public function sync()
    {
        try {
            $this->moduleManager->syncWithLicense();

            return response()->json([
                'success' => true,
                'message' => 'Modules synced with license successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get module details
     */
    public function show(string $name)
    {
        $status = $this->moduleManager->getModuleStatus($name);

        if (!$status['exists']) {
            return response()->json([
                'success' => false,
                'message' => "Module '{$name}' not found."
            ], 404);
        }

        return response()->json([
            'success' => true,
            'module' => $status
        ]);
    }

    /**
     * Run module migrations
     */
    protected function runModuleMigrations(string $name): void
    {
        $migrationPath = base_path("Modules/{$name}/Database/Migrations");

        if (File::isDirectory($migrationPath)) {
            try {
                Artisan::call('migrate', [
                    '--path' => "Modules/{$name}/Database/Migrations",
                    '--force' => true,
                ]);
            } catch (\Exception $e) {
                Log::warning("Module migration failed for {$name}: " . $e->getMessage());
            }
        }
    }

    /**
     * Run composer dump-autoload
     */
    protected function runComposerDumpAutoload(): void
    {
        try {
            $output = [];
            $returnVar = 0;
            exec('cd ' . base_path() . ' && composer dump-autoload 2>&1', $output, $returnVar);

            if ($returnVar !== 0) {
                Log::warning('Composer dump-autoload returned non-zero', [
                    'output' => implode("\n", $output),
                    'return' => $returnVar
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Composer dump-autoload failed: ' . $e->getMessage());
        }
    }

    /**
     * Get module icon based on name
     */
    protected function getModuleIcon(string $name): string
    {
        $icons = [
            'POS' => 'fas fa-cash-register',
            'LandingPage' => 'fas fa-rocket',
            'Blog' => 'fas fa-blog',
            'Inventory' => 'fas fa-boxes',
            'Analytics' => 'fas fa-chart-line',
            'SMS' => 'fas fa-sms',
            'Email' => 'fas fa-envelope',
            'Daraz' => 'fas fa-sync-alt',
        ];

        return $icons[$name] ?? 'fas fa-puzzle-piece';
    }

    /**
     * Get module color based on name
     */
    protected function getModuleColor(string $name): string
    {
        $colors = [
            'POS' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'LandingPage' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            'Blog' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
            'Inventory' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
            'Analytics' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
            'SMS' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)',
            'Email' => 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)',
            'Daraz' => 'linear-gradient(135deg, #f5af19 0%, #f12711 100%)',
        ];

        return $colors[$name] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
    }

    /**
     * Get module route based on name
     */
    protected function getModuleRoute(string $name): ?string
    {
        $routes = [
            'POS' => 'admin.pos.index',
            'LandingPage' => 'admin.landing-pages.index',
            'Blog' => 'admin.post.index',
            'Daraz' => 'admin.daraz.index',
        ];

        if (isset($routes[$name]) && \Route::has($routes[$name])) {
            return route($routes[$name]);
        }

        return null;
    }
}
