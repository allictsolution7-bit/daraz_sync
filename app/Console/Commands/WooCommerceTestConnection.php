<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WooCommerce\WooCommerceConnectionService;

class WooCommerceTestConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'woocommerce:test-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test connection to WooCommerce';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing WooCommerce connection...');
        
        $connectionService = app(WooCommerceConnectionService::class);
        $result = $connectionService->testConnection();

        if ($result['success']) {
            $this->info('✓ Connection successful!');
            $this->line('Message: ' . $result['message']);
            
            if (isset($result['data'])) {
                $this->line('Response data available.');
            }
        } else {
            $this->error('✗ Connection failed!');
            $this->error('Error: ' . $result['message']);
            
            $this->newLine();
            $this->warn('Please check your configuration in .env file:');
            $this->line('- WC_CONNECTION_METHOD (api or database)');
            
            if (config('woocommerce.connection_method') === 'api') {
                $this->line('- WC_API_URL');
                $this->line('- WC_CONSUMER_KEY');
                $this->line('- WC_CONSUMER_SECRET');
            } else {
                $this->line('- WC_DB_HOST');
                $this->line('- WC_DB_PORT');
                $this->line('- WC_DB_DATABASE');
                $this->line('- WC_DB_USERNAME');
                $this->line('- WC_DB_PASSWORD');
                $this->line('- WC_TABLE_PREFIX');
            }
            
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

