<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WooCommerce\WooCommerceMigrationService;
use App\Services\WooCommerce\WooCommerceConnectionService;

class WooCommerceMigrateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'woocommerce:migrate-all {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all data from WooCommerce (Categories, Products, Users, Orders)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        $this->info('Starting full WooCommerce migration...');
        $this->newLine();

        if (!$this->confirm('This will migrate Categories → Products → Users → Orders. Continue?', true)) {
            return Command::SUCCESS;
        }

        $migrationService = new WooCommerceMigrationService(
            app(WooCommerceConnectionService::class)
        );

        // 1. Migrate Categories
        $this->info('Step 1/4: Migrating Categories...');
        $categoriesStats = $migrationService->migrateCategories($dryRun);
        $this->displayStats('Categories', $categoriesStats);
        $this->newLine();

        // 2. Migrate Products
        $this->info('Step 2/4: Migrating Products...');
        $productsStats = $migrationService->migrateProducts($dryRun);
        $this->displayStats('Products', $productsStats);
        $this->newLine();

        // 3. Migrate Users
        $this->info('Step 3/4: Migrating Users...');
        $usersStats = $migrationService->migrateUsers($dryRun);
        $this->displayStats('Users', $usersStats);
        $this->newLine();

        // 4. Migrate Orders
        $this->info('Step 4/4: Migrating Orders...');
        $ordersStats = $migrationService->migrateOrders($dryRun);
        $this->displayStats('Orders', $ordersStats);
        $this->newLine();

        // Summary
        $this->info('=== Migration Summary ===');
        $this->table(
            ['Entity', 'Created', 'Updated', 'Skipped', 'Errors'],
            [
                ['Categories', $categoriesStats['created'], $categoriesStats['updated'], $categoriesStats['skipped'], count($categoriesStats['errors'])],
                ['Products', $productsStats['created'], $productsStats['updated'], $productsStats['skipped'], count($productsStats['errors'])],
                ['Users', $usersStats['created'], $usersStats['updated'], $usersStats['skipped'], count($usersStats['errors'])],
                ['Orders', $ordersStats['created'], $ordersStats['updated'], $ordersStats['skipped'], count($ordersStats['errors'])],
            ]
        );

        if ($dryRun) {
            $this->warn('This was a dry run. Use without --dry-run to actually migrate.');
        } else {
            $this->info('Migration completed!');
        }

        return Command::SUCCESS;
    }

    protected function displayStats($entity, $stats)
    {
        $this->line("  Total: {$stats['total']} | Created: {$stats['created']} | Updated: {$stats['updated']} | Skipped: {$stats['skipped']} | Errors: " . count($stats['errors']));
    }
}

