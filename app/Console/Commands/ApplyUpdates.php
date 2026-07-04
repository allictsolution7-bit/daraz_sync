<?php

namespace App\Console\Commands;

use App\Services\UpdateService;
use Illuminate\Console\Command;
use Throwable;

class ApplyUpdates extends Command
{
    protected $signature = 'thikana:update:apply {--force : Force a fresh manifest fetch before applying}';
    protected $description = 'Download and apply the next available Thikana update.';

    protected UpdateService $updateService;

    public function __construct(UpdateService $updateService)
    {
        parent::__construct();
        $this->updateService = $updateService;
    }

    public function handle(): int
    {
        $manifest = $this->updateService->fetchAvailableUpdate($this->option('force'));

        if (!$manifest) {
            $this->info('There are no updates available to apply.');
            return 0;
        }

        $this->info('Applying update ' . $manifest['version'] . '...');

        try {
            $history = $this->updateService->applyUpdate($manifest);
            $this->info("Update {$history->version} applied successfully.");
            return 0;
        } catch (Throwable $e) {
            $this->error('Update failed: ' . $e->getMessage());
            return 1;
        }
    }
}
