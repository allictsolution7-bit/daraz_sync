<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // Generate sitemap daily at midnight
        $schedule->command('sitemap:generate')->daily();
        
        // Sync courier delivery statuses every 15 minutes
        $schedule->command('courier:sync-statuses')
                 ->everyFifteenMinutes()
                 ->withoutOverlapping();
        
        // Process backup schedules every 5 minutes
        $schedule->command('backup:process-schedules')
                 ->everyFiveMinutes()
                 ->withoutOverlapping()
                 ->onOneServer();
        
        // Cleanup old backups daily at 2 AM
        $schedule->command('backup:cleanup')
                 ->dailyAt('02:00')
                 ->onOneServer();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
        
    }
}
