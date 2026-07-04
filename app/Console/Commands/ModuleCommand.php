<?php

namespace App\Console\Commands;

use App\Services\Modules\Module;
use App\Services\Modules\ModuleManager;
use Illuminate\Console\Command;

class ModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'module
                            {action : Action to perform (list, enable, disable, status, sync)}
                            {name? : Module name (required for enable/disable/status)}';

    /**
     * The console command description.
     */
    protected $description = 'Manage application modules';

    /**
     * Execute the console command.
     */
    public function handle(ModuleManager $manager): int
    {
        $action = $this->argument('action');
        $name = $this->argument('name');

        return match ($action) {
            'list' => $this->listModules($manager),
            'enable' => $this->enableModule($manager, $name),
            'disable' => $this->disableModule($manager, $name),
            'status' => $this->showStatus($manager, $name),
            'sync' => $this->syncModules($manager),
            default => $this->invalidAction($action),
        };
    }

    /**
     * List all modules
     */
    protected function listModules(ModuleManager $manager): int
    {
        $modules = $manager->getAllWithStatus();

        if (empty($modules)) {
            $this->info('No modules found.');
            return 0;
        }

        $this->info('Available Modules:');
        $this->newLine();

        $headers = ['Name', 'Display Name', 'Version', 'Status', 'Licensed', 'Premium'];
        $rows = [];

        foreach ($modules as $name => $info) {
            $rows[] = [
                $name,
                $info['display_name'],
                $info['version'],
                $this->formatStatus($info['status']),
                $info['is_licensed'] ? '<fg=green>Yes</>' : '<fg=red>No</>',
                $info['is_premium'] ? 'Yes' : 'No',
            ];
        }

        $this->table($headers, $rows);

        return 0;
    }

    /**
     * Enable a module
     */
    protected function enableModule(ModuleManager $manager, ?string $name): int
    {
        if (!$name) {
            $this->error('Module name is required for enable action.');
            return 1;
        }

        if (!$manager->has($name)) {
            $this->error("Module '{$name}' not found.");
            return 1;
        }

        $status = $manager->getModuleStatus($name);

        if (!$status['is_licensed']) {
            $this->error("Module '{$name}' is not licensed. Please activate a license that includes this module.");
            return 1;
        }

        if ($manager->enable($name)) {
            $this->info("Module '{$name}' has been enabled.");
            return 0;
        }

        $this->error("Failed to enable module '{$name}'.");
        return 1;
    }

    /**
     * Disable a module
     */
    protected function disableModule(ModuleManager $manager, ?string $name): int
    {
        if (!$name) {
            $this->error('Module name is required for disable action.');
            return 1;
        }

        if (!$manager->has($name)) {
            $this->error("Module '{$name}' not found.");
            return 1;
        }

        if ($manager->disable($name)) {
            $this->info("Module '{$name}' has been disabled.");
            return 0;
        }

        $this->error("Failed to disable module '{$name}'.");
        return 1;
    }

    /**
     * Show module status
     */
    protected function showStatus(ModuleManager $manager, ?string $name): int
    {
        if (!$name) {
            $this->error('Module name is required for status action.');
            return 1;
        }

        $status = $manager->getModuleStatus($name);

        $this->info("Module: {$name}");
        $this->newLine();

        $this->line("  Display Name:     {$status['display_name']}");
        $this->line("  Description:      {$status['description']}");
        $this->line("  Version:          {$status['version']}");
        $this->line("  Exists:           " . ($status['exists'] ? 'Yes' : 'No'));
        $this->line("  Licensed:         " . ($status['is_licensed'] ? 'Yes' : 'No'));
        $this->line("  Enabled:          " . ($status['is_enabled'] ? 'Yes' : 'No'));
        $this->line("  Manually Disabled:" . ($status['is_manually_disabled'] ? 'Yes' : 'No'));
        $this->line("  Premium:          " . ($status['is_premium'] ? 'Yes' : 'No'));
        $this->line("  Requires Support: " . ($status['requires_support'] ? 'Yes' : 'No'));
        $this->line("  Status:           " . $this->formatStatus($status['status']));

        return 0;
    }

    /**
     * Sync modules with license
     */
    protected function syncModules(ModuleManager $manager): int
    {
        $this->info('Syncing modules with license...');

        $manager->syncWithLicense();

        $this->info('Modules synced successfully.');
        $this->newLine();

        return $this->listModules($manager);
    }

    /**
     * Handle invalid action
     */
    protected function invalidAction(string $action): int
    {
        $this->error("Invalid action: {$action}");
        $this->line('Available actions: list, enable, disable, status, sync');
        return 1;
    }

    /**
     * Format status for display
     */
    protected function formatStatus(string $status): string
    {
        return match ($status) {
            'Active' => '<fg=green>Active</>',
            'Not Installed' => '<fg=yellow>Not Installed</>',
            'Not Licensed' => '<fg=red>Not Licensed</>',
            'Manually Disabled' => '<fg=yellow>Manually Disabled</>',
            'Inactive' => '<fg=gray>Inactive</>',
            default => $status,
        };
    }
}
