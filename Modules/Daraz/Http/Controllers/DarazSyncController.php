<?php

namespace Modules\Daraz\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Daraz\Models\DarazStore;
use Modules\Daraz\Models\DarazProductMapping;
use Modules\Daraz\Models\DarazSyncLog;
use Modules\Daraz\Services\DarazStockSyncService;
use Modules\Daraz\Jobs\SyncStockToDaraz;
use Modules\Daraz\Jobs\PullStockFromDaraz;

class DarazSyncController extends Controller
{
    protected DarazStockSyncService $syncService;

    public function __construct(DarazStockSyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Display sync dashboard.
     */
    public function index()
    {
        $stores = DarazStore::active()
            ->withCount(['productMappings', 'productMappings as enabled_mappings_count' => function ($q) {
                $q->where('sync_enabled', true);
            }])
            ->get();

        $recentLogs = DarazSyncLog::with(['store', 'productMapping'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Stats
        $stats = [
            'total_stores' => $stores->count(),
            'connected_stores' => $stores->filter(fn($s) => $s->isConnected())->count(),
            'total_mappings' => DarazProductMapping::count(),
            'enabled_mappings' => DarazProductMapping::where('sync_enabled', true)->count(),
            'recent_syncs' => DarazSyncLog::where('created_at', '>=', now()->subHours(24))->count(),
            'failed_syncs' => DarazSyncLog::where('created_at', '>=', now()->subHours(24))
                ->where('status', 'failed')->count(),
        ];

        return view('daraz::sync.index', compact('stores', 'recentLogs', 'stats'));
    }

    /**
     * Sync a single mapping.
     */
    public function syncSingle(DarazProductMapping $mapping)
    {
        $result = $this->syncService->pushStockToDaraz($mapping);

        return response()->json([
            'success' => $result['success'],
            'message' => $result['success']
                ? 'Stock synced successfully. Quantity: ' . ($result['quantity'] ?? 'N/A')
                : ($result['error'] ?? 'Sync failed'),
            'data' => $result,
        ]);
    }

    /**
     * Sync all mappings for a store.
     */
    public function syncStore(Request $request, DarazStore $store)
    {
        $direction = $request->get('direction', 'to_daraz');

        if (!$store->isConnected()) {
            return response()->json([
                'success' => false,
                'message' => 'Store is not connected. Please authorize first.',
            ]);
        }

        $stats = $this->syncService->bulkSyncStore($store, $direction);

        $message = sprintf(
            'Sync complete. Processed: %d, Succeeded: %d, Failed: %d',
            $stats['processed'],
            $stats['succeeded'],
            $stats['failed']
        );

        return response()->json([
            'success' => $stats['failed'] === 0,
            'message' => $message,
            'stats' => $stats,
        ]);
    }

    /**
     * Sync all stores (push to Daraz).
     */
    public function syncAll()
    {
        $stores = DarazStore::active()->get();

        $totalStats = [
            'stores' => 0,
            'processed' => 0,
            'succeeded' => 0,
            'failed' => 0,
        ];

        foreach ($stores as $store) {
            if (!$store->isConnected()) {
                continue;
            }

            $totalStats['stores']++;
            $stats = $this->syncService->bulkSyncStore($store, 'to_daraz');

            $totalStats['processed'] += $stats['processed'];
            $totalStats['succeeded'] += $stats['succeeded'];
            $totalStats['failed'] += $stats['failed'];
        }

        return response()->json([
            'success' => true,
            'message' => sprintf(
                'Synced %d stores. Total - Processed: %d, Succeeded: %d, Failed: %d',
                $totalStats['stores'],
                $totalStats['processed'],
                $totalStats['succeeded'],
                $totalStats['failed']
            ),
            'stats' => $totalStats,
        ]);
    }

    /**
     * Pull stock from all Daraz stores.
     */
    public function pullAll()
    {
        $stores = DarazStore::active()->get();

        $totalStats = [
            'stores' => 0,
            'processed' => 0,
            'succeeded' => 0,
            'failed' => 0,
        ];

        foreach ($stores as $store) {
            if (!$store->isConnected()) {
                continue;
            }

            $totalStats['stores']++;
            $stats = $this->syncService->bulkSyncStore($store, 'from_daraz');

            $totalStats['processed'] += $stats['processed'];
            $totalStats['succeeded'] += $stats['succeeded'];
            $totalStats['failed'] += $stats['failed'];
        }

        return response()->json([
            'success' => true,
            'message' => sprintf(
                'Pulled from %d stores. Total - Processed: %d, Succeeded: %d, Failed: %d',
                $totalStats['stores'],
                $totalStats['processed'],
                $totalStats['succeeded'],
                $totalStats['failed']
            ),
            'stats' => $totalStats,
        ]);
    }

    /**
     * View sync logs.
     */
    public function logs(Request $request)
    {
        $storeId = $request->get('store_id');
        $type = $request->get('type');
        $status = $request->get('status');

        $stores = DarazStore::all();

        $logs = DarazSyncLog::with(['store', 'productMapping.product'])
            ->when($storeId, fn($q) => $q->where('daraz_store_id', $storeId))
            ->when($type, fn($q) => $q->where('type', $type))
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('daraz::sync.logs', compact('logs', 'stores', 'storeId', 'type', 'status'));
    }

    /**
     * Clear old logs.
     */
    public function clearLogs(Request $request)
    {
        $days = $request->get('days', 30);
        $deleted = DarazSyncLog::cleanOldLogs($days);

        return response()->json([
            'success' => true,
            'message' => "Deleted {$deleted} logs older than {$days} days.",
        ]);
    }

    /**
     * Get sync status for a store (AJAX).
     */
    public function storeStatus(DarazStore $store)
    {
        $mappings = $store->productMappings()
            ->with(['product', 'variationCombination'])
            ->enabled()
            ->get()
            ->map(function ($mapping) {
                return [
                    'id' => $mapping->id,
                    'product' => $mapping->product_title,
                    'thikana_stock' => $mapping->getCurrentStock(),
                    'effective_stock' => $mapping->getEffectiveStock(),
                    'last_synced' => $mapping->last_synced_quantity,
                    'needs_sync' => $mapping->needsSync(),
                    'status' => $mapping->last_sync_status,
                    'last_synced_at' => $mapping->last_synced_at?->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'store' => [
                'id' => $store->id,
                'name' => $store->name,
                'connected' => $store->isConnected(),
                'last_synced' => $store->last_synced_at?->diffForHumans(),
            ],
            'mappings' => $mappings,
            'needs_sync_count' => $mappings->where('needs_sync', true)->count(),
        ]);
    }
}
