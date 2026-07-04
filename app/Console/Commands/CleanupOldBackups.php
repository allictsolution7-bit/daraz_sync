<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class CleanupOldBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old backups based on retention policy';

    /**
     * Execute the console command.
     */
    public function handle(BackupService $backupService): int
    {
        $this->info('Cleaning up old backups...');

        try {
            $deleted = $backupService->deleteOldBackups();
            $this->info("Deleted {$deleted} old backup(s).");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to cleanup backups: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

