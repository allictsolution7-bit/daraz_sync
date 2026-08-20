<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetSyncService;
use App\Models\BackupSetting;
use Illuminate\Support\Facades\Log;

class SyncGoogleSheetProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sheets:sync-products {--test : Only test the connection without syncing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync products and stock two-way with Google Sheets';

    /**
     * Execute the console command.
     */
    public function handle(GoogleSheetSyncService $syncService)
    {
        $enabled = BackupSetting::get('google_sheet_auto_sync_enabled', false);

        if ($this->option('test')) {
            $this->info('Testing Google Sheets connection...');
            try {
                $result = $syncService->testConnection();
                $this->info('Success: ' . ($result['message'] ?? 'Connected'));
                $this->line('Found headers: ' . implode(', ', $result['headers'] ?? []));
                return 0;
            } catch (\Exception $e) {
                $this->error('Failed to connect: ' . $e->getMessage());
                return 1;
            }
        }

        if (!$enabled) {
            $this->warn('Google Sheet auto-sync is currently disabled in settings.');
        }

        $this->info('Starting Google Sheets product sync...');
        try {
            $result = $syncService->pullAndSyncFromSheet();
            $this->info($result['message'] ?? 'Sync finished.');
            $this->table(
                ['Imported (New)', 'Updated (Stock/Price)', 'Skipped', 'Total Rows'],
                [[$result['imported'], $result['updated'], $result['skipped'], $result['total_rows'] ?? 0]]
            );
            return 0;
        } catch (\Exception $e) {
            $this->error('Sync Error: ' . $e->getMessage());
            Log::error('Google Sheet Sync Error: ' . $e->getMessage());
            return 1;
        }
    }
}
