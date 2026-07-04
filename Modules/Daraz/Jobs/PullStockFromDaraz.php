<?php

namespace Modules\Daraz\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Daraz\Models\DarazStore;
use Modules\Daraz\Services\DarazStockSyncService;

class PullStockFromDaraz implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 120;

    /**
     * The store to pull from.
     */
    protected DarazStore $store;

    /**
     * Create a new job instance.
     */
    public function __construct(DarazStore $store)
    {
        $this->store = $store;
        $this->onQueue('daraz-sync');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Skip if store is inactive or disconnected
        if (!$this->store->is_active || !$this->store->isConnected()) {
            Log::info('Daraz Pull: Skipping - store inactive or disconnected', [
                'store_id' => $this->store->id,
            ]);
            return;
        }

        $syncService = new DarazStockSyncService();

        // Pull for all enabled mappings
        $mappings = $this->store->productMappings()->enabled()->get();

        $stats = [
            'processed' => 0,
            'succeeded' => 0,
            'failed' => 0,
        ];

        foreach ($mappings as $mapping) {
            $stats['processed']++;

            $result = $syncService->pullStockFromDaraz($mapping);

            if ($result['success']) {
                $stats['succeeded']++;
            } else {
                $stats['failed']++;
                Log::warning('Daraz Pull: Failed for mapping', [
                    'mapping_id' => $mapping->id,
                    'error' => $result['error'] ?? 'Unknown',
                ]);
            }
        }

        Log::info('Daraz Pull Job Completed', [
            'store_id' => $this->store->id,
            'stats' => $stats,
        ]);

        // Update store last sync time
        $this->store->update(['last_synced_at' => now()]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Daraz Pull Job Failed', [
            'store_id' => $this->store->id,
            'error' => $exception->getMessage(),
        ]);
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'daraz-pull',
            'store:' . $this->store->id,
        ];
    }
}
