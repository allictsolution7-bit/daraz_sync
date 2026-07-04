<?php

namespace App\Jobs;

use App\Models\BackupSchedule;
use App\Services\BackupService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 600; // 10 minutes

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $scheduleId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(BackupService $backupService): void
    {
        $schedule = BackupSchedule::findOrFail($this->scheduleId);

        if (!$schedule->is_active) {
            Log::info("Skipping inactive schedule: {$schedule->name}");
            return;
        }

        try {
            $schedule->markAsRunning();

            $backupHistory = $backupService->createBackup(
                $schedule->backup_type,
                $schedule->storage_destination,
                $schedule->id,
                null // No user ID for scheduled backups
            );

            $schedule->markAsSuccess('Backup completed successfully');

            Log::info("Backup completed successfully", [
                'schedule_id' => $schedule->id,
                'backup_id' => $backupHistory->id,
            ]);

        } catch (\Exception $e) {
            $schedule->markAsFailed($e->getMessage());

            Log::error("Backup failed for schedule: {$schedule->name}", [
                'schedule_id' => $schedule->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        $schedule = BackupSchedule::find($this->scheduleId);
        if ($schedule) {
            $schedule->markAsFailed($exception->getMessage());
        }

        Log::error("Backup job failed permanently", [
            'schedule_id' => $this->scheduleId,
            'error' => $exception->getMessage(),
        ]);
    }
}

