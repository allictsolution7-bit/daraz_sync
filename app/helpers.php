<?php

use App\Services\SettingsService;

if (!function_exists('setting')) {
    /**
     * Get a setting value
     *
     * @param string $group
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($group, $key, $default = null)
    {
        return SettingsService::get($group, $key, $default);
    }
}

if (!function_exists('settings')) {
    /**
     * Get all settings for a group
     *
     * @param string $group
     * @return array
     */
    function settings($group)
    {
        return SettingsService::group($group);
    }
}

if (!function_exists('module_path')) {
    /**
     * Get the path to a module directory
     *
     * @param string $name Module name
     * @param string $path Additional path within the module
     * @return string
     */
    function module_path(string $name, string $path = ''): string
    {
        $modulePath = base_path('Modules/' . $name);

        if ($path) {
            $modulePath .= '/' . ltrim($path, '/');
        }

        return $modulePath;
    }
}

if (!function_exists('module_asset')) {
    /**
     * Get the URL to a module asset
     *
     * @param string $name Module name
     * @param string $path Asset path within the module
     * @return string
     */
    function module_asset(string $name, string $path): string
    {
        return asset('modules/' . strtolower($name) . '/' . ltrim($path, '/'));
    }
}

if (!function_exists('module_enabled')) {
    /**
     * Check if a module is enabled
     *
     * @param string $name Module name
     * @return bool
     */
    function module_enabled(string $name): bool
    {
        static $statuses = null;

        if ($statuses === null) {
            $statusFile = base_path('modules_statuses.json');
            if (file_exists($statusFile)) {
                $statuses = json_decode(file_get_contents($statusFile), true) ?? [];
            } else {
                $statuses = [];
            }
        }

        return $statuses[$name] ?? false;
    }
} 