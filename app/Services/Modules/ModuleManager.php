<?php

namespace App\Services\Modules;

use App\Services\LicenseService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ModuleManager
{
    protected LicenseService $licenseService;
    protected string $modulesPath;
    protected string $statusesFile;
    protected array $modules = [];

    public function __construct(LicenseService $licenseService)
    {
        $this->licenseService = $licenseService;
        $this->modulesPath = base_path('Modules');
        $this->statusesFile = base_path('modules_statuses.json');
        $this->loadModules();
    }

    /**
     * Load all available modules from the Modules directory
     */
    protected function loadModules(): void
    {
        if (!File::isDirectory($this->modulesPath)) {
            File::makeDirectory($this->modulesPath, 0755, true);
            return;
        }

        $directories = File::directories($this->modulesPath);

        foreach ($directories as $directory) {
            $moduleName = basename($directory);
            $moduleJsonPath = $directory . '/module.json';

            if (File::exists($moduleJsonPath)) {
                $moduleConfig = json_decode(File::get($moduleJsonPath), true);
                $this->modules[$moduleName] = $moduleConfig;
            }
        }
    }

    /**
     * Get all registered modules
     */
    public function all(): array
    {
        return $this->modules;
    }

    /**
     * Check if a module exists
     */
    public function has(string $name): bool
    {
        return isset($this->modules[$name]) 
            || File::isDirectory($this->modulesPath . '/' . $name) 
            || isset(config('modules.available', [])[$name]);
    }

    /**
     * Check if a module is enabled
     * Uses hybrid approach: checks both file status AND license
     */
    public function isEnabled(string $name): bool
    {
        // First check if module files exist
        if (!$this->has($name)) {
            return false;
        }

        // Get module config to find license key
        $moduleConfig = config("modules.available.{$name}", []);
        $licenseKey = $moduleConfig['license_key'] ?? null;

        // Free modules (license_key = null) don't need license check
        if ($licenseKey !== null) {
            // Check license status from Uddokta
            if (!$this->licenseService->isModuleAllowed($licenseKey)) {
                return false;
            }
        }

        // Check file-based status (can be manually disabled even with license)
        $statuses = $this->getStatuses();

        // Default to enabled if license allows and no explicit disable
        return $statuses[$name] ?? true;
    }

    /**
     * Check if a module is disabled
     */
    public function isDisabled(string $name): bool
    {
        return !$this->isEnabled($name);
    }

    /**
     * Enable a module (file-based, license must also allow)
     */
    public function enable(string $name): bool
    {
        if (!$this->has($name)) {
            return false;
        }

        $statuses = $this->getStatuses();
        $statuses[$name] = true;
        $this->saveStatuses($statuses);

        $this->clearCache();

        Log::info("Module enabled: {$name}");

        return true;
    }

    /**
     * Disable a module (file-based)
     */
    public function disable(string $name): bool
    {
        if (!$this->has($name)) {
            return false;
        }

        $statuses = $this->getStatuses();
        $statuses[$name] = false;
        $this->saveStatuses($statuses);

        $this->clearCache();

        Log::info("Module disabled: {$name}");

        return true;
    }

    /**
     * Get module statuses from file
     */
    protected function getStatuses(): array
    {
        if (!File::exists($this->statusesFile)) {
            return [];
        }

        $content = File::get($this->statusesFile);
        return json_decode($content, true) ?? [];
    }

    /**
     * Save module statuses to file
     */
    protected function saveStatuses(array $statuses): void
    {
        File::put(
            $this->statusesFile,
            json_encode($statuses, JSON_PRETTY_PRINT)
        );
    }

    /**
     * Sync module statuses with license from Uddokta
     * Called during license validation/sync
     */
    public function syncWithLicense(): void
    {
        $licenseStatus = $this->licenseService->getLicenseStatus();
        $licensedModules = $licenseStatus['modules'] ?? ['core'];

        $availableModules = config('modules.available', []);
        $statuses = $this->getStatuses();

        foreach ($availableModules as $moduleName => $moduleConfig) {
            $licenseKey = $moduleConfig['license_key'] ?? strtolower($moduleName);

            // If module exists and is licensed, enable it (unless manually disabled)
            if ($this->has($moduleName)) {
                $isLicensed = in_array($licenseKey, $licensedModules);

                // Only auto-enable if not explicitly disabled by user
                if ($isLicensed && !isset($statuses[$moduleName])) {
                    $statuses[$moduleName] = true;
                }

                // Force disable if not licensed
                if (!$isLicensed) {
                    $statuses[$moduleName] = false;
                }
            }
        }

        $this->saveStatuses($statuses);
        $this->clearCache();

        Log::info('Module statuses synced with license', [
            'licensed_modules' => $licensedModules,
            'statuses' => $statuses
        ]);
    }

    /**
     * Get module info
     */
    public function find(string $name): ?array
    {
        return $this->modules[$name] ?? null;
    }

    /**
     * Get module path
     */
    public function getModulePath(string $name): ?string
    {
        if (!$this->has($name)) {
            return null;
        }

        return $this->modulesPath . '/' . $name;
    }

    /**
     * Get all enabled modules
     */
    public function getEnabled(): array
    {
        return array_filter($this->modules, function ($module, $name) {
            return $this->isEnabled($name);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Get all disabled modules
     */
    public function getDisabled(): array
    {
        return array_filter($this->modules, function ($module, $name) {
            return $this->isDisabled($name);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Clear module cache
     */
    public function clearCache(): void
    {
        Cache::forget('module_statuses');
        Cache::forget('modules');
    }

    /**
     * Get module status for display
     */
    public function getModuleStatus(string $name): array
    {
        $exists = $this->has($name);
        $moduleConfig = config("modules.available.{$name}", []);
        $licenseKey = $moduleConfig['license_key'] ?? null;

        // Free modules (license_key = null) are always licensed
        $isLicensed = $licenseKey === null ? true : $this->licenseService->isModuleAllowed($licenseKey);
        $isEnabled = $this->isEnabled($name);

        $statuses = $this->getStatuses();
        $isManuallyDisabled = isset($statuses[$name]) && $statuses[$name] === false;

        return [
            'name' => $name,
            'display_name' => $moduleConfig['name'] ?? $name,
            'description' => $moduleConfig['description'] ?? '',
            'version' => $moduleConfig['version'] ?? '1.0.0',
            'exists' => $exists,
            'is_licensed' => $isLicensed,
            'is_enabled' => $isEnabled,
            'is_manually_disabled' => $isManuallyDisabled,
            'is_premium' => $moduleConfig['is_premium'] ?? false,
            'requires_support' => $moduleConfig['requires_support'] ?? false,
            'status' => $this->getStatusText($exists, $isLicensed, $isEnabled, $isManuallyDisabled),
        ];
    }

    /**
     * Get human-readable status text
     */
    protected function getStatusText(bool $exists, bool $isLicensed, bool $isEnabled, bool $isManuallyDisabled): string
    {
        if (!$exists) {
            return 'Not Installed';
        }

        if (!$isLicensed) {
            return 'Not Licensed';
        }

        if ($isManuallyDisabled) {
            return 'Manually Disabled';
        }

        if ($isEnabled) {
            return 'Active';
        }

        return 'Inactive';
    }

    /**
     * Get all modules with their statuses
     */
    public function getAllWithStatus(): array
    {
        $result = [];
        $availableModules = config('modules.available', []);

        foreach ($availableModules as $name => $config) {
            $result[$name] = $this->getModuleStatus($name);
        }

        return $result;
    }
}
