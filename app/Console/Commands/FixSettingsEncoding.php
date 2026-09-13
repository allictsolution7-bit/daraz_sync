<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SiteSetting;
use App\Services\SettingsService;

class FixSettingsEncoding extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:fix-encoding';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean and repair corrupted character encoding (mojibake) in site_settings table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Scanning site_settings table for corrupted character encoding...');

        $settings = SiteSetting::all();
        $fixedCount = 0;

        foreach ($settings as $setting) {
            $original = $setting->value;
            if ($original === null || $original === '') {
                continue;
            }

            $cleaned = SettingsService::cleanMojibake($original);

            if ($cleaned !== $original) {
                $this->line("Fixing [{$setting->group}.{$setting->key}]:");
                $this->line("  Old: " . mb_substr($original, 0, 80));
                $this->line("  New: " . mb_substr($cleaned, 0, 80));

                $setting->value = $cleaned;
                $setting->save();
                $fixedCount++;
            }
        }

        SettingsService::clearCache();

        $this->info("Completed! Repaired {$fixedCount} settings records.");
        return 0;
    }
}
