<?php

namespace Modules\Daraz\Console\Commands;

use Illuminate\Console\Command;
use Modules\Daraz\Models\DarazSyncLog;

class DarazCleanLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daraz:clean-logs
                            {--days= : Number of days to retain (default from config)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old Daraz sync logs';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = $this->option('days') ?? config('daraz.logging.retention_days', 30);

        $this->info("Cleaning logs older than {$days} days...");

        $deleted = DarazSyncLog::cleanOldLogs((int) $days);

        $this->info("Deleted {$deleted} old log entries.");

        return 0;
    }
}
