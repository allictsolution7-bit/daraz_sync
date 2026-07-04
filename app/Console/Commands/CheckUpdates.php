<?php

namespace App\Console\Commands;

use App\Services\UpdateService;
use Illuminate\Console\Command;

class CheckUpdates extends Command
{
    protected $signature = 'thikana:update:check {--force : Skip cache and query the mother panel immediately}';
    protected $description = 'Check for new updates from the Thikana mother panel.';

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
            $this->info('No updates are currently available.');
            return 0;
        }

        $this->info("Update available: v{$manifest['version']} (sequence {$manifest['sequence']})");
        $this->line("Release channel: {$manifest['release_channel']}");
        $this->line("Notes: " . ($manifest['notes'] ?? 'No release notes provided.'));

        if (!empty($manifest['commands'])) {
            $this->line('Commands that will run during apply:');
            foreach ($manifest['commands'] as $command) {
                $this->line("  - {$command}");
            }
        }

        return 0;
    }
}
