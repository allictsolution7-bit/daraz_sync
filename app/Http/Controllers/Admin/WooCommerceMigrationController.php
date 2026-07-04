<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WooCommerce\WooCommerceConnectionService;
use App\Services\WooCommerce\WooCommerceMigrationService;
use App\Models\WooCommerceSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WooCommerceMigrationController extends Controller
{
    protected $connectionService;
    protected $migrationService;

    public function __construct()
    {
        $this->connectionService = app(WooCommerceConnectionService::class);
        $this->migrationService = app(WooCommerceMigrationService::class);
    }

    /**
     * Display migration dashboard
     */
    public function index()
    {
        // Get migration statistics
        $migrationStats = $this->getMigrationStats();
        
        // Get connection status
        $connectionStatus = $this->connectionService->testConnection();
        
        // Get settings
        $settings = WooCommerceSetting::getSettings();
        
        return view('admin.woocommerce-migration.index', compact('migrationStats', 'connectionStatus', 'settings'));
    }

    /**
     * Save WooCommerce settings
     */
    public function saveSettings(Request $request)
    {
        $request->validate([
            'connection_method' => 'required|in:api,database',
            'api_url' => 'required_if:connection_method,api|nullable|url',
            'consumer_key' => 'required_if:connection_method,api|nullable|string',
            'consumer_secret' => 'required_if:connection_method,api|nullable|string',
            'api_version' => 'nullable|string',
            'verify_ssl' => 'nullable|boolean',
            'db_host' => 'required_if:connection_method,database|nullable|string',
            'db_port' => 'nullable|string',
            'db_database' => 'required_if:connection_method,database|nullable|string',
            'db_username' => 'required_if:connection_method,database|nullable|string',
            'db_password' => 'nullable|string',
            'db_table_prefix' => 'nullable|string',
        ]);

        try {
            $settings = WooCommerceSetting::first();
            
            if (!$settings) {
                $settings = new WooCommerceSetting();
            }

            $data = $request->only([
                'connection_method',
                'api_url',
                'consumer_key',
                'consumer_secret',
                'api_version',
                'verify_ssl',
                'db_host',
                'db_port',
                'db_database',
                'db_username',
                'db_password',
                'db_table_prefix',
            ]);
            
            // Convert verify_ssl to boolean
            $data['verify_ssl'] = $request->boolean('verify_ssl');
            
            $settings->fill($data);

            $settings->save();

            return response()->json([
                'success' => true,
                'message' => 'Settings saved successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save WooCommerce settings', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save settings: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test WooCommerce connection
     */
    public function testConnection(Request $request)
    {
        try {
            $result = $this->connectionService->testConnection();
            
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'] ?? 'Connection test completed',
                'data' => $result['data'] ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Migrate entity (categories, products, users, orders)
     */
    public function migrateEntity(Request $request, $entity)
    {
        $allowedEntities = ['categories', 'products', 'users', 'orders'];
        
        if (!in_array($entity, $allowedEntities)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid entity type',
            ], 400);
        }

        try {
            $dryRun = $request->boolean('dry_run', false);
            $method = 'migrate' . ucfirst($entity);
            
            $stats = $this->migrationService->$method($dryRun);
            
            return response()->json([
                'success' => true,
                'message' => ucfirst($entity) . ' migration completed',
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error("{$entity} migration failed", ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Migration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Migrate categories
     */
    public function migrateCategories(Request $request)
    {
        try {
            $dryRun = $request->boolean('dry_run', false);
            
            $stats = $this->migrationService->migrateCategories($dryRun);
            
            return response()->json([
                'success' => true,
                'message' => 'Categories migration completed',
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('Category migration failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Migration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Migrate products
     */
    public function migrateProducts(Request $request)
    {
        try {
            $dryRun = $request->boolean('dry_run', false);
            
            $stats = $this->migrationService->migrateProducts($dryRun);
            
            return response()->json([
                'success' => true,
                'message' => 'Products migration completed',
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('Product migration failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Migration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Migrate users
     */
    public function migrateUsers(Request $request)
    {
        try {
            $dryRun = $request->boolean('dry_run', false);
            
            $stats = $this->migrationService->migrateUsers($dryRun);
            
            return response()->json([
                'success' => true,
                'message' => 'Users migration completed',
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('User migration failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Migration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Migrate orders
     */
    public function migrateOrders(Request $request)
    {
        try {
            $dryRun = $request->boolean('dry_run', false);
            
            $stats = $this->migrationService->migrateOrders($dryRun);
            
            return response()->json([
                'success' => true,
                'message' => 'Orders migration completed',
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            Log::error('Order migration failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Migration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Migrate all data
     */
    public function migrateAll(Request $request)
    {
        try {
            $dryRun = $request->boolean('dry_run', false);
            
            $results = [
                'categories' => $this->migrationService->migrateCategories($dryRun),
                'products' => $this->migrationService->migrateProducts($dryRun),
                'users' => $this->migrationService->migrateUsers($dryRun),
                'orders' => $this->migrationService->migrateOrders($dryRun),
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Full migration completed',
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            Log::error('Full migration failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Migration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get migration statistics
     */
    protected function getMigrationStats()
    {
        $stats = [
            'categories' => ['migrated' => 0],
            'products' => ['migrated' => 0],
            'users' => ['migrated' => 0],
            'orders' => ['migrated' => 0],
        ];

        // Check if mapping table exists
        if (DB::getSchemaBuilder()->hasTable('woocommerce_migration_mapping')) {
            $stats['categories']['migrated'] = DB::table('woocommerce_migration_mapping')
                ->where('entity_type', 'category')
                ->count();
            $stats['products']['migrated'] = DB::table('woocommerce_migration_mapping')
                ->where('entity_type', 'product')
                ->count();
            $stats['users']['migrated'] = DB::table('woocommerce_migration_mapping')
                ->where('entity_type', 'user')
                ->count();
            $stats['orders']['migrated'] = DB::table('woocommerce_migration_mapping')
                ->where('entity_type', 'order')
                ->count();
        }

        return $stats;
    }
}

