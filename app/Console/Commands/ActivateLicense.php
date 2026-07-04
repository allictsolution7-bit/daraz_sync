<?php

namespace App\Console\Commands;

use App\Services\LicenseService;
use Illuminate\Console\Command;

class ActivateLicense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:activate {license_key? : The license key to activate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate a software license';

    protected $licenseService;

    public function __construct(LicenseService $licenseService)
    {
        parent::__construct();
        $this->licenseService = $licenseService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔐 License Activation Tool');
        $this->line('');

        // Get license key from argument or ask for it
        $licenseKey = $this->argument('license_key');
        
        if (!$licenseKey) {
            $licenseKey = $this->ask('Please enter your license key');
        }

        if (!$licenseKey || strlen(trim($licenseKey)) < 10) {
            $this->error('❌ Invalid license key provided. License key must be at least 10 characters long.');
            return 1;
        }

        $licenseKey = trim($licenseKey);

        $this->line('');
        $this->info('🔍 Validating license key...');
        
        // Show a progress bar for better UX
        $bar = $this->output->createProgressBar(3);
        $bar->start();

        try {
            $bar->advance(); // Step 1: Connecting to server
            $this->line(' Connecting to license server...');
            
            $bar->advance(); // Step 2: Validating
            $this->line(' Validating license...');
            
            $result = $this->licenseService->activateLicense($licenseKey);
            
            $bar->advance(); // Step 3: Processing
            $this->line(' Processing response...');
            
            $bar->finish();
            $this->line('');
            $this->line('');

            if ($result['success']) {
                $this->info('✅ License activated successfully!');
                $this->line('');
                
                // Display license information
                $licenseStatus = $this->licenseService->getLicenseStatus();
                $this->displayLicenseInfo($licenseStatus);
                
                return 0;
            } else {
                $this->error('❌ License activation failed: ' . $result['message']);
                $this->line('');
                $this->warn('💡 Troubleshooting tips:');
                $this->line('   • Check your internet connection');
                $this->line('   • Verify the license key is correct');
                $this->line('   • Ensure the license is not already activated on another domain');
                $this->line('   • Contact support if the issue persists');
                
                return 1;
            }
        } catch (\Exception $e) {
            $bar->finish();
            $this->line('');
            $this->line('');
            $this->error('❌ An error occurred: ' . $e->getMessage());
            
            return 1;
        }
    }

    /**
     * Display license information in a formatted table
     */
    private function displayLicenseInfo(array $licenseStatus)
    {
        $this->table(['Property', 'Value'], [
            ['License Key', $licenseStatus['license_key']],
            ['Domain', $licenseStatus['domain'] ?? 'Not specified'],
            ['Status', ucfirst($licenseStatus['status'])],
            ['Expiry Date', $licenseStatus['expiry_date'] ?? 'No expiry'],
            ['Modules', implode(', ', $licenseStatus['modules'] ?? [])],
            ['Landing Page Limit', $licenseStatus['landing_page_limit'] ?? 0],
            ['Landing Pages Used', $licenseStatus['landing_page_used'] ?? 0],
            ['Landing Pages Remaining', $licenseStatus['landing_page_remaining'] ?? 0],
            ['Last Synced', $licenseStatus['last_synced'] ?? 'Just now'],
        ]);

        // Display Support Status
        $supportStatus = $licenseStatus['support_status'] ?? [];
        if (!empty($supportStatus)) {
            $this->line('');
            $this->info('📞 Support Status: ' . ($supportStatus['active'] ? '✅ Active' : '❌ Inactive'));
            if ($supportStatus['active']) {
                $this->line("   Duration: {$supportStatus['duration']} days");
                $this->line("   Remaining: {$supportStatus['remaining_days']} days");
            }
        }

        // Display Update Status  
        $updateStatus = $licenseStatus['update_status'] ?? [];
        if (!empty($updateStatus)) {
            $this->line('');
            $this->info('🔄 Update Status: ' . ($updateStatus['active'] ? '✅ Active' : '❌ Inactive'));
            if ($updateStatus['active']) {
                $this->line("   Duration: {$updateStatus['duration']} days");
                $this->line("   Remaining: {$updateStatus['remaining_days']} days");
            }
        }

        $this->line('');
        
        if ($licenseStatus['valid']) {
            $this->info('🎉 Your license is now active and ready to use!');
            
            // Show enabled features
            $modules = $licenseStatus['modules'] ?? [];
            if (!empty($modules)) {
                $this->line('');
                $this->info('🚀 Enabled Features:');
                foreach ($modules as $module) {
                    $icon = $this->getModuleIcon($module);
                    $name = $this->getModuleName($module);
                    $this->line("   {$icon} {$name}");
                }
            }
        }
    }

    /**
     * Get icon for module
     */
    private function getModuleIcon(string $module): string
    {
        return match($module) {
            'core' => '⚙️',
            'pos' => '🏪',
            'landing_page' => '🖥️',
            default => '🔧'
        };
    }

    /**
     * Get display name for module
     */
    private function getModuleName(string $module): string
    {
        return match($module) {
            'core' => 'Core System',
            'pos' => 'Point of Sale (POS)',
            'landing_page' => 'Landing Page Builder',
            default => ucfirst(str_replace('_', ' ', $module))
        };
    }
}
