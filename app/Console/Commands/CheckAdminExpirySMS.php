<?php

namespace App\Console\Commands;

use App\Models\AdminSubscriptionPayment;
use App\Models\License;
use App\Models\SmsSetting;
use App\Models\SiteSetting;
use App\Services\SMSService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckAdminExpirySMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:check-admin-expiry {--dry-run : Only check and print matching alerts without sending SMS}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and dispatch SMS notifications to Admins expiring in 3 days or today using Super Admin gateway';

    /**
     * Execute the console command.
     */
    public function handle(SMSService $smsService): int
    {
        $this->info("Checking admin subscriptions and licenses for expiration alerts...");

        // Fetch central/Super Admin settings (user_id IS NULL)
        $centralSettings = SmsSetting::getSettingsForUser(null);

        if (!$centralSettings->is_enabled) {
            $this->warn("SMS service is disabled in central settings. Skipping expiry checks.");
            return 0;
        }

        if (!$centralSettings->notify_admin_expiry) {
            $this->warn("Admin expiry notifications are turned off in settings. Skipping.");
            return 0;
        }

        $daysBefore = $centralSettings->admin_expiry_days_before ?: 3;
        $today = Carbon::today();
        $targetNearExpiry = Carbon::today()->addDays($daysBefore)->toDateString();
        $targetToday = $today->toDateString();

        $this->line("Checking for admins expiring on {$targetNearExpiry} ({$daysBefore} days before) or today ({$targetToday}).");

        $isDryRun = (bool)$this->option('dry-run');

        // 1. Check AdminSubscriptionPayments
        $expiringSubscriptions = AdminSubscriptionPayment::with('user')
            ->whereNotNull('expiry_date')
            ->where(function ($query) use ($targetNearExpiry, $targetToday) {
                $query->whereDate('expiry_date', $targetNearExpiry)
                      ->orWhereDate('expiry_date', $targetToday);
            })
            ->get();

        $this->info("Found {$expiringSubscriptions->count()} subscription record(s) matching criteria.");

        foreach ($expiringSubscriptions as $sub) {
            $phone = $sub->phone ?: $sub->user?->phone;
            $adminName = $sub->user?->name ?: 'Admin';
            $expiryDate = Carbon::parse($sub->expiry_date)->format('d M, Y');
            $daysLeft = (int)ceil(now()->diffInDays(Carbon::parse($sub->expiry_date), false));
            $daysLeftDisplay = max(0, $daysLeft);

            if (empty($phone)) {
                $this->warn("Skipping sub #{$sub->id} ({$adminName}) - no phone number found.");
                continue;
            }

            $cacheKey = "sms_admin_expiry_notified_{$sub->id}_{$targetToday}";
            if (Cache::has($cacheKey)) {
                $this->line("Already notified sub #{$sub->id} today.");
                continue;
            }

            $this->info("Admin: {$adminName} | Phone: {$phone} | Expiry: {$expiryDate} ({$daysLeftDisplay} days left)");

            if (!$isDryRun) {
                // Send SMS using Super Admin's Central Gateway (senderUserId = null)
                $result = $smsService->sendEventSMS('admin_expiry', $phone, [
                    'admin_name' => $adminName,
                    'days_left' => $daysLeftDisplay,
                    'expiry_date' => $expiryDate,
                ], null);

                if ($result['sent']) {
                    Cache::put($cacheKey, true, now()->endOfDay());
                    $this->info("  -> SMS alert sent successfully via Super Admin gateway.");
                } else {
                    $this->error("  -> Failed to send SMS: " . ($result['reason'] ?? 'Unknown error'));
                }
            } else {
                $this->comment("  [Dry Run] Would send SMS to {$phone} via Super Admin gateway.");
            }
        }

        // 2. Also check System License expiry if applicable
        $license = License::first();
        if ($license && $license->expiry_date) {
            $licExpiry = Carbon::parse($license->expiry_date);
            if ($licExpiry->isSameDay($today) || $licExpiry->isSameDay(Carbon::today()->addDays($daysBefore))) {
                $contactPhone = SiteSetting::get('general', 'phone_number');
                if ($contactPhone) {
                    $cacheKey = "sms_license_expiry_{$targetToday}";
                    if (!Cache::has($cacheKey)) {
                        $this->info("License expiring on {$licExpiry->format('d M, Y')}. Contact: {$contactPhone}");
                        if (!$isDryRun) {
                            $smsService->sendEventSMS('admin_expiry', $contactPhone, [
                                'admin_name' => 'Licensee',
                                'days_left' => max(0, (int)ceil(now()->diffInDays($licExpiry, false))),
                                'expiry_date' => $licExpiry->format('d M, Y'),
                            ], null);
                            Cache::put($cacheKey, true, now()->endOfDay());
                        }
                    }
                }
            }
        }

        $this->info("Admin expiration SMS check completed.");
        return 0;
    }
}
