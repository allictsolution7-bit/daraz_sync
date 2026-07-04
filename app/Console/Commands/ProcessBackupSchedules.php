<?php

namespace App\Console\Commands;

use App\Jobs\ProcessScheduledBackup;
use Illuminate\Console\Command;

class ProcessBackupSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:process-schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and queue due backup schedules';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Processing backup schedules...');

        try {
            ProcessScheduledBackup::dispatch();
            $this->info('Backup schedules processed successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to process backup schedules: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

