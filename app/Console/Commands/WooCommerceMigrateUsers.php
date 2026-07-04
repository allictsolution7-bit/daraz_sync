<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WooCommerce\WooCommerceMigrationService;
use App\Services\WooCommerce\WooCommerceConnectionService;

class WooCommerceMigrateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'woocommerce:migrate-users {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate users/customers from WooCommerce';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }

        $this->info('Starting user/customer migration...');
        $this->warn('Note: Users will be created with random passwords. They can reset via forgot password.');

        if (!$this->confirm('Do you want to continue?', true)) {
            return Command::SUCCESS;
        }

        $migrationService = new WooCommerceMigrationService(
            app(WooCommerceConnectionService::class)
        );

        $stats = $migrationService->migrateUsers($dryRun);

        $this->newLine(2);
        $this->displayStats('Users', $stats);

        if ($dryRun) {
            $this->warn('This was a dry run. Use without --dry-run to actually migrate.');
        }

        return Command::SUCCESS;
    }

    protected function displayStats($entity, $stats)
    {
        $this->info("{$entity} Migration Statistics:");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Processed', $stats['total']],
                ['Created', $stats['created']],
                ['Updated', $stats['updated']],
                ['Skipped', $stats['skipped']],
                ['Errors', count($stats['errors'])],
            ]
        );

        if (!empty($stats['errors'])) {
            $this->error('Errors encountered:');
            foreach (array_slice($stats['errors'], 0, 10) as $error) {
                $this->line('- ' . $error);
            }
            if (count($stats['errors']) > 10) {
                $this->line('... and ' . (count($stats['errors']) - 10) . ' more errors');
            }
        }
    }
}

