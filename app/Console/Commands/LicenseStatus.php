<?php

namespace App\Console\Commands;

use App\Services\LicenseService;
use Illuminate\Console\Command;

class LicenseStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'license:status {--refresh : Force refresh license from server}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the current license status';

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
        $this->info('📋 License Status Check');
        $this->line('');

        if ($this->option('refresh')) {
            $this->info('🔄 Refreshing license from server...');
            $this->licenseService->revalidateLicense();
            $this->line('');
        }

        $licenseStatus = $this->licenseService->getLicenseStatus();

        if ($licenseStatus['valid']) {
            $this->info('✅ License is ACTIVE and valid');
        } else {
            $this->error('❌ License is INVALID or expired');
            $this->line('   Reason: ' . ($licenseStatus['message'] ?? 'Unknown'));
        }

        $this->line('');
        $this->displayLicenseInfo($licenseStatus);

        return $licenseStatus['valid'] ? 0 : 1;
    }

    /**
     * Display license information
     */
    private function displayLicenseInfo(array $licenseStatus)
    {
        $this->table(['Property', 'Value'], [
            ['License Key', $licenseStatus['license_key'] ?? 'Not set'],
            ['Domain', $licenseStatus['domain'] ?? 'Not specified'],
            ['Status', ucfirst($licenseStatus['status'] ?? 'unknown')],
            ['Expiry Date', $licenseStatus['expiry_date'] ?? 'No expiry'],
            ['Modules', implode(', ', $licenseStatus['modules'] ?? [])],
            ['Landing Page Limit', $licenseStatus['landing_page_limit'] ?? 0],
            ['Landing Pages Used', $licenseStatus['landing_page_used'] ?? 0],
            ['Landing Pages Remaining', $licenseStatus['landing_page_remaining'] ?? 0],
            ['Last Synced', $licenseStatus['last_synced'] ?? 'Never'],
            ['Needs Sync', ($licenseStatus['needs_sync'] ?? false) ? 'Yes' : 'No'],
        ]);

        // Display Support Status
        $supportStatus = $licenseStatus['support_status'] ?? [];
        $this->line('');
        $this->info('📞 Support Status:');
        $this->table(['Property', 'Value'], [
            ['Support Active', $supportStatus['active'] ? '✅ Yes' : '❌ No'],
            ['Support Start Date', $supportStatus['start_date'] ?? 'Not set'],
            ['Support End Date', $supportStatus['end_date'] ?? 'Not set'],
            ['Support Duration', ($supportStatus['duration'] ?? 0) . ' days'],
            ['Remaining Days', $supportStatus['remaining_days'] ?? 0],
            ['Status', $supportStatus['status_text'] ?? 'Unknown'],
        ]);

        // Display Update Status
        $updateStatus = $licenseStatus['update_status'] ?? [];
        $this->line('');
        $this->info('🔄 Update Status:');
        $this->table(['Property', 'Value'], [
            ['Updates Active', $updateStatus['active'] ? '✅ Yes' : '❌ No'],
            ['Update Start Date', $updateStatus['start_date'] ?? 'Not set'],
            ['Update End Date', $updateStatus['end_date'] ?? 'Not set'],
            ['Update Duration', ($updateStatus['duration'] ?? 0) . ' days'],
            ['Remaining Days', $updateStatus['remaining_days'] ?? 0],
            ['Status', $updateStatus['status_text'] ?? 'Unknown'],
        ]);
    }
}
