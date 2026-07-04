<?php

namespace Modules\Daraz\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Daraz\Models\DarazProductMapping;
use Modules\Daraz\Services\DarazStockSyncService;

class SyncStockToDaraz implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * The product mapping to sync.
     */
    protected DarazProductMapping $mapping;

    /**
     * Create a new job instance.
     */
    public function __construct(DarazProductMapping $mapping)
    {
        $this->mapping = $mapping;
        $this->onQueue('daraz-sync');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Skip if sync is disabled
        if (!$this->mapping->sync_enabled) {
            Log::info('Daraz Sync: Skipping disabled mapping', [
                'mapping_id' => $this->mapping->id,
            ]);
            return;
        }

        // Skip if store is inactive or disconnected
        if (!$this->mapping->store || !$this->mapping->store->is_active || !$this->mapping->store->isConnected()) {
            Log::info('Daraz Sync: Skipping - store inactive or disconnected', [
                'mapping_id' => $this->mapping->id,
                'store_id' => $this->mapping->daraz_store_id,
            ]);
            return;
        }

        $syncService = new DarazStockSyncService();
        $result = $syncService->pushStockToDaraz($this->mapping);

        if (!$result['success'] && !isset($result['skipped'])) {
            Log::warning('Daraz Sync Job: Sync failed', [
                'mapping_id' => $this->mapping->id,
                'error' => $result['error'] ?? 'Unknown error',
                'attempt' => $this->attempts(),
            ]);

            // Re-throw to trigger retry
            if ($this->attempts() < $this->tries) {
                throw new \Exception($result['error'] ?? 'Stock sync failed');
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Daraz Sync Job Failed', [
            'mapping_id' => $this->mapping->id,
            'error' => $exception->getMessage(),
        ]);

        // Update mapping status
        $this->mapping->updateSyncStatus('failed', null, 'Job failed: ' . $exception->getMessage());
    }

    /**
     * Get the tags that should be assigned to the job.
     */
    public function tags(): array
    {
        return [
            'daraz-sync',
            'mapping:' . $this->mapping->id,
            'store:' . $this->mapping->daraz_store_id,
        ];
    }
}
