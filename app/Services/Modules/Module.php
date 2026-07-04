<?php

namespace App\Services\Modules;

use Illuminate\Support\Facades\App;

/**
 * Module Facade Helper
 * Provides static access to module management functionality
 */
class Module
{
    /**
     * Get the module manager instance
     */
    protected static function manager(): ModuleManager
    {
        return App::make(ModuleManager::class);
    }

    /**
     * Check if a module exists
     */
    public static function has(string $name): bool
    {
        return static::manager()->has($name);
    }

    /**
     * Check if a module is enabled (licensed + file-enabled)
     */
    public static function isEnabled(string $name): bool
    {
        return static::manager()->isEnabled($name);
    }

    /**
     * Check if a module is disabled
     */
    public static function isDisabled(string $name): bool
    {
        return static::manager()->isDisabled($name);
    }

    /**
     * Enable a module
     */
    public static function enable(string $name): bool
    {
        return static::manager()->enable($name);
    }

    /**
     * Disable a module
     */
    public static function disable(string $name): bool
    {
        return static::manager()->disable($name);
    }

    /**
     * Get all modules
     */
    public static function all(): array
    {
        return static::manager()->all();
    }

    /**
     * Get all enabled modules
     */
    public static function getEnabled(): array
    {
        return static::manager()->getEnabled();
    }

    /**
     * Get all disabled modules
     */
    public static function getDisabled(): array
    {
        return static::manager()->getDisabled();
    }

    /**
     * Find a module by name
     */
    public static function find(string $name): ?array
    {
        return static::manager()->find($name);
    }

    /**
     * Get module path
     */
    public static function getModulePath(string $name): ?string
    {
        return static::manager()->getModulePath($name);
    }

    /**
     * Sync modules with license
     */
    public static function syncWithLicense(): void
    {
        static::manager()->syncWithLicense();
    }

    /**
     * Get module status details
     */
    public static function getModuleStatus(string $name): array
    {
        return static::manager()->getModuleStatus($name);
    }

    /**
     * Get all modules with their statuses
     */
    public static function getAllWithStatus(): array
    {
        return static::manager()->getAllWithStatus();
    }

    /**
     * Clear module cache
     */
    public static function clearCache(): void
    {
        static::manager()->clearCache();
    }
}
