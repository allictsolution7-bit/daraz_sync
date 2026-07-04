<?php

namespace Modules\Daraz\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Daraz\Jobs\PullStockFromDaraz;
use Modules\Daraz\Models\DarazStore;
use Modules\Daraz\Services\DarazStockSyncService;

class DarazSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daraz:sync
                            {--store= : Specific store ID to sync}
                            {--direction=both : Sync direction: to_daraz, from_daraz, or both}
                            {--force : Force sync even if not due}
                            {--queue : Queue the sync jobs instead of running synchronously}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync stock with Daraz stores';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $storeId = $this->option('store');
        $direction = $this->option('direction');
        $force = $this->option('force');
        $useQueue = $this->option('queue');

        $this->info('Starting Daraz stock sync...');

        // Get stores to sync
        if ($storeId) {
            $stores = DarazStore::where('id', $storeId)->active()->get();
            if ($stores->isEmpty()) {
                $this->error("Store #{$storeId} not found or inactive.");
                return 1;
            }
        } elseif ($force) {
            $stores = DarazStore::active()->get();
        } else {
            $stores = DarazStore::dueForSync()->get();
        }

        if ($stores->isEmpty()) {
            $this->info('No stores need syncing at this time.');
            return 0;
        }

        $this->info("Found {$stores->count()} store(s) to sync.");

        $syncService = new DarazStockSyncService();
        $totalStats = [
            'stores' => 0,
            'push_processed' => 0,
            'push_succeeded' => 0,
            'push_failed' => 0,
            'pull_processed' => 0,
            'pull_succeeded' => 0,
            'pull_failed' => 0,
        ];

        foreach ($stores as $store) {
            if (!$store->isConnected()) {
                $this->warn("Skipping {$store->name} - not connected.");
                continue;
            }

            $totalStats['stores']++;
            $this->info("Syncing {$store->name} ({$store->country_code})...");

            // Push to Daraz
            if (in_array($direction, ['to_daraz', 'both'])) {
                $this->line('  Pushing to Daraz...');

                if ($useQueue) {
                    // Queue individual mapping syncs
                    $mappings = $store->productMappings()->enabled()->get();
                    foreach ($mappings as $mapping) {
                        \Modules\Daraz\Jobs\SyncStockToDaraz::dispatch($mapping);
                    }
                    $this->line("    Queued {$mappings->count()} mappings for push.");
                    $totalStats['push_processed'] += $mappings->count();
                } else {
                    $stats = $syncService->bulkSyncStore($store, 'to_daraz');
                    $totalStats['push_processed'] += $stats['processed'];
                    $totalStats['push_succeeded'] += $stats['succeeded'];
                    $totalStats['push_failed'] += $stats['failed'];

                    $this->line("    Processed: {$stats['processed']}, Succeeded: {$stats['succeeded']}, Failed: {$stats['failed']}");
                }
            }

            // Pull from Daraz
            if (in_array($direction, ['from_daraz', 'both'])) {
                $this->line('  Pulling from Daraz...');

                if ($useQueue) {
                    PullStockFromDaraz::dispatch($store);
                    $this->line('    Queued pull job.');
                } else {
                    $stats = $syncService->bulkSyncStore($store, 'from_daraz');
                    $totalStats['pull_processed'] += $stats['processed'];
                    $totalStats['pull_succeeded'] += $stats['succeeded'];
                    $totalStats['pull_failed'] += $stats['failed'];

                    $this->line("    Processed: {$stats['processed']}, Succeeded: {$stats['succeeded']}, Failed: {$stats['failed']}");
                }
            }
        }

        // Summary
        $this->newLine();
        $this->info('Sync Summary:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Stores Synced', $totalStats['stores']],
                ['Push Processed', $totalStats['push_processed']],
                ['Push Succeeded', $totalStats['push_succeeded']],
                ['Push Failed', $totalStats['push_failed']],
                ['Pull Processed', $totalStats['pull_processed']],
                ['Pull Succeeded', $totalStats['pull_succeeded']],
                ['Pull Failed', $totalStats['pull_failed']],
            ]
        );

        Log::info('Daraz Sync Command Completed', $totalStats);

        return $totalStats['push_failed'] + $totalStats['pull_failed'] > 0 ? 1 : 0;
    }
}
