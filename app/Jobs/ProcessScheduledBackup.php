<?php

namespace App\Jobs;

use App\Models\BackupSchedule;
use App\Jobs\CreateBackupJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessScheduledBackup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dueSchedules = BackupSchedule::active()->due()->get();

        if ($dueSchedules->isEmpty()) {
            Log::info('No backup schedules are due to run');
            return;
        }

        foreach ($dueSchedules as $schedule) {
            try {
                // Dispatch backup job for this schedule
                CreateBackupJob::dispatch($schedule->id);
                
                // Update schedule timing
                $schedule->update([
                    'last_run_at' => now(),
                    'next_run_at' => $schedule->calculateNextRun(),
                ]);

                Log::info("Backup scheduled for: {$schedule->name}", [
                    'schedule_id' => $schedule->id,
                    'next_run_at' => $schedule->next_run_at,
                ]);
            } catch (\Exception $e) {
                Log::error("Failed to schedule backup: {$schedule->name}", [
                    'schedule_id' => $schedule->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}

