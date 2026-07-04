<?php

namespace App\Services;

use App\Models\FraudProtectionSetting;
use App\Models\order;
use App\Models\FraudCheckResult;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FraudProtectionService
{
    protected $settings;

    public function __construct()
    {
        $this->settings = FraudProtectionSetting::getSettings();
    }

    /**
     * Main validation method - validates all enabled checks
     */
    public function validateOrder(array $data): array
    {
        $errors = [];

        // Skip if no modules are enabled
        if (!$this->settings->isAnyModuleEnabled()) {
            return ['valid' => true];
        }

        // Module 1: Duplicate Order Protection
        if ($this->settings->duplicate_protection_enabled) {
            $duplicateCheck = $this->checkDuplicateOrder($data);
            if (!$duplicateCheck['valid']) {
                $errors[] = $duplicateCheck['message'];
            }
        }

        // Module 2: Fake Order Protection
        if ($this->settings->fake_protection_enabled) {
            $fakeCheck = $this->checkFakeData($data);
            if (!$fakeCheck['valid']) {
                $errors = array_merge($errors, $fakeCheck['errors']);
            }
        }

        // Module 3: Fraud & Scam Protection
        if ($this->settings->fraud_protection_enabled) {
            $fraudCheck = $this->checkFraudProtection($data);
            if (!$fraudCheck['valid']) {
                $errors = array_merge($errors, $fraudCheck['errors']);
            }
        }

        // Log blocked attempts if enabled
        if (!empty($errors) && $this->settings->log_blocked_attempts) {
            $this->logBlockedAttempt($data, $errors);
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * MODULE 1: Check Duplicate Orders
     */
    protected function checkDuplicateOrder(array $data): array
    {
        $phone = $data['phone'] ?? null;
        $email = $data['email'] ?? null;

        // Order Interval Restriction
        if ($this->settings->order_interval_enabled && $phone) {
            $minutesAgo = now()->subMinutes($this->settings->order_interval_minutes);
            $recentOrder = order::where('phone', $phone)
                ->where('created_at', '>=', $minutesAgo)
                ->first();

            if ($recentOrder) {
                $waitMinutes = $this->settings->order_interval_minutes;
                $message = $this->settings->duplicate_order_message ?? 
                    "আপনি সম্প্রতি একটি অর্ডার করেছেন। অনুগ্রহ করে {$waitMinutes} মিনিট পরে আবার চেষ্টা করুন।";
                
                return ['valid' => false, 'message' => $message];
            }
        }

        // Pending Order Restriction
        if ($this->settings->pending_order_restriction_enabled && $phone) {
            $pendingOrder = order::where('phone', $phone)
                ->where('status', 'pending')
                ->first();

            if ($pendingOrder) {
                $message = $this->settings->duplicate_order_message ??
                    "আপনার ইতিমধ্যে একটি অর্ডার প্রসেসিংয়ে রয়েছে। নতুন অর্ডার করার আগে অনুগ্রহ করে অপেক্ষা করুন।";
                return ['valid' => false, 'message' => $message];
            }
        }

        return ['valid' => true];
    }

    /**
     * MODULE 2: Check Fake Data
     */
    protected function checkFakeData(array $data): array
    {
        $errors = [];
        $phone = $data['phone'] ?? '';
        $name = $data['name'] ?? '';
        $address = $data['address'] ?? '';

        // Phone Validation
        if ($this->settings->phone_validation_enabled) {
            $phoneCheck = $this->validatePhoneNumber($phone);
            if (!$phoneCheck['valid']) {
                $errors[] = $phoneCheck['message'];
            }
        }

        // Block Sequential/Repeated Numbers
        if ($this->settings->block_sequential_numbers) {
            if ($this->hasSequentialOrRepeatedDigits($phone)) {
                $errors[] = "অবৈধ ফোন নম্বর সনাক্ত করা হয়েছে। অনুগ্রহ করে সঠিক নম্বর প্রদান করুন।";
            }
        }

        // Block Repeated Names
        if ($this->settings->block_repeated_names) {
            if ($this->hasRepeatedCharacters($name)) {
                $errors[] = "অবৈধ নাম সনাক্ত করা হয়েছে। অনুগ্রহ করে সঠিক নাম প্রদান করুন।";
            }
        }

        // Name validation (length, numeric)
        if ($this->settings->name_validation_enabled) {
            $min = (int) ($this->settings->name_min_length ?? 2);
            $max = (int) ($this->settings->name_max_length ?? 100);
            $len = mb_strlen(trim($name));
            if ($len < $min || $len > $max) {
                $errors[] = "নামটি {$min}-{$max} অক্ষরের মধ্যে হতে হবে।";
            }
            if (($this->settings->name_disallow_numeric ?? true) && preg_match('/\d/', $name)) {
                $errors[] = "নামে সংখ্যা ব্যবহার করা যাবে না।";
            }
        }

        // Block Gibberish Names
        if ($this->settings->block_gibberish_names) {
            if ($this->isGibberishName($name)) {
                $errors[] = "অবৈধ নাম প্যাটার্ন সনাক্ত করা হয়েছে। অনুগ্রহ করে আপনার প্রকৃত নাম প্রদান করুন।";
            }
        }

        // Custom Pattern Blacklist
        if ($this->settings->custom_pattern_enabled) {
            $customCheck = $this->checkCustomPatterns($phone, $name, $address);
            if (!$customCheck['valid']) {
                $errors = array_merge($errors, $customCheck['errors']);
            }
        }

        // Address validation
        if ($this->settings->address_validation_enabled) {
            $minAddr = (int) ($this->settings->address_min_length ?? 10);
            if (mb_strlen(trim($address)) < $minAddr) {
                $errors[] = "ঠিকানাটি কমপক্ষে {$minAddr} অক্ষরের হতে হবে।";
            }
        }


        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * MODULE 3: Check Fraud Protection
     */
    protected function checkFraudProtection(array $data): array
    {
        $errors = [];
        $phone = $data['phone'] ?? '';
        $ip = request()->ip();

        // Blacklist Check
        if ($this->settings->blacklist_enabled) {
            if ($this->isBlacklisted($phone, $ip)) {
                $message = $this->settings->blacklist_message ?? 
                    'আপনার অ্যাকাউন্ট সাময়িকভাবে স্থগিত করা হয়েছে।';
                $errors[] = $message;
            }
        }

        // IP Rate Limiting
        if ($this->settings->ip_rate_limiting_enabled) {
            if ($this->isIPRateLimitExceeded($ip)) {
                $errors[] = "অনেক বেশি অর্ডার সনাক্ত করা হয়েছে। অনুগ্রহ করে পরে আবার চেষ্টা করুন।";
            }
        }

        // Courier Success Rate Check
        if ($this->settings->courier_success_check_enabled && $phone) {
            $successRateCheck = $this->checkCourierSuccessRate($phone);
            if (!$successRateCheck['valid']) {
                $errors[] = $successRateCheck['message'];
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Validate phone number length
     */
    protected function validatePhoneNumber(string $phone): array
    {
        $cleanPhone = $this->cleanPhoneNumber($phone);
        
        // Check phone number whitelist first if enabled
        if ($this->settings->phone_whitelist_enabled) {
            $whitelistCheck = $this->validatePhoneWhitelist($phone);
            if (!$whitelistCheck['valid']) {
                return $whitelistCheck;
            }
        }
        
        // Always enforce allowed length rules (whitelist is an additional restriction)
        $allowedLengths = $this->settings->allowed_phone_lengths ?? [11, 12, 14];

        if (!in_array(strlen($cleanPhone), $allowedLengths)) {
            return [
                'valid' => false,
                'message' => "ফোন নম্বর " . implode(', ', $allowedLengths) . " ডিজিটের হতে হবে।",
            ];
        }

        return ['valid' => true];
    }

    /**
     * Validate phone number against whitelist patterns
     */
    protected function validatePhoneWhitelist(string $phone): array
    {
        $cleanPhone = $this->cleanPhoneNumber($phone);
        
        // Define allowed patterns
        $allowedPatterns = [
            '^014',      // 014xxxxxxxxx
            '^018',      // 018xxxxxxxxx
            '^88014',    // 88014xxxxxxxxx
            '^88018',    // 88018xxxxxxxxx
            '^013',      // 013xxxxxxxxx
            '^017',      // 017xxxxxxxxx
            '^88013',    // 88013xxxxxxxxx
            '^88017',    // 88017xxxxxxxxx
            '^019',      // 019xxxxxxxxx
            '^88019',    // 88019xxxxxxxxx
            '^015',      // 015xxxxxxxxx
            '^88015',    // 88015xxxxxxxxx
            '^016',      // 016xxxxxxxxx
            '^88016',    // 88016xxxxxxxxx
            '^011',      // 011xxxxxxxxx
            '^88011',    // 88011xxxxxxxxx
        ];
        
        // Also check for +880 patterns (with + sign)
        if (strpos($cleanPhone, '+880') === 0) {
            $plus880Patterns = [
                '^\\+88014',  // +88014xxxxxxxxx
                '^\\+88018',  // +88018xxxxxxxxx
                '^\\+88013',  // +88013xxxxxxxxx
                '^\\+88017',  // +88017xxxxxxxxx
                '^\\+88019',  // +88019xxxxxxxxx
                '^\\+88015',  // +88015xxxxxxxxx
                '^\\+88016',  // +88016xxxxxxxxx
                '^\\+88011',  // +88011xxxxxxxxx
            ];
            
            foreach ($plus880Patterns as $pattern) {
                if (preg_match('/' . $pattern . '/', $cleanPhone)) {
                    return ['valid' => true];
                }
            }
        }

        // Check if phone matches any allowed pattern
        foreach ($allowedPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/', $cleanPhone)) {
                return ['valid' => true];
            }
        }

        // If no pattern matches, phone is not allowed
        return [
            'valid' => false,
            'message' => 'এই ফোন নম্বরটি গ্রহণযোগ্য নয়। শুধুমাত্র নির্দিষ্ট অপারেটরের নম্বর গ্রহণ করা হয়।',
        ];
    }

    /**
     * Clean phone number by removing spaces, hyphens, and other formatting characters
     * while preserving + at the beginning if present
     */
    protected function cleanPhoneNumber(string $phone): string
    {
        if (empty($phone)) {
            return '';
        }
        
        $phone = trim($phone);
        
        // Convert Bengali digits to English digits first
        $phone = $this->convertBengaliToEnglish($phone);
        
        // Keep + at the beginning if present
        $hasPlus = str_starts_with($phone, '+');
        if ($hasPlus) {
            $phone = '+' . preg_replace('/\D/', '', substr($phone, 1));
        } else {
            $phone = preg_replace('/\D/', '', $phone);
        }
        
        return $phone;
    }

    /**
     * Convert Bengali digits to English digits
     */
    protected function convertBengaliToEnglish(string $text): string
    {
        if (empty($text)) {
            return '';
        }
        
        // Bengali to English digit mapping
        $bengaliToEnglish = [
            '০' => '0', '১' => '1', '২' => '2', '৩' => '3', '৪' => '4',
            '৫' => '5', '৬' => '6', '৭' => '7', '৮' => '8', '৯' => '9'
        ];
        
        return str_replace(array_keys($bengaliToEnglish), array_values($bengaliToEnglish), $text);
    }

    /**
     * Check for sequential or repeated digits
     */
    protected function hasSequentialOrRepeatedDigits(string $phone): bool
    {
        $cleanPhone = preg_replace('/\D/', '', $phone);

        // Check for repeated digits (e.g., 0000000000, 1111111111)
        if (preg_match('/^(\d)\1+$/', $cleanPhone)) {
            return true;
        }

        // Check for too many repeated consecutive digits (e.g., 0170000000)
        if (preg_match('/(\d)\1{5,}/', $cleanPhone)) {
            return true;
        }

        return false;
    }

    /**
     * Check for repeated characters in name
     */
    protected function hasRepeatedCharacters(string $name): bool
    {
        $cleanName = strtolower(trim($name));

        // Check if name consists of only one repeated character
        if (preg_match('/^(.)\1+$/', $cleanName)) {
            return true;
        }

        // Check for too many consecutive repeated characters
        if (preg_match('/(.)\1{4,}/', $cleanName)) {
            return true;
        }

        return false;
    }

    /**
     * Check if name is gibberish
     */
    protected function isGibberishName(string $name): bool
    {
        $cleanName = strtolower(trim($name));

        // Check for keyboard patterns
        $keyboardPatterns = ['asdf', 'qwer', 'zxcv', 'hjkl', '1234', 'abcd'];
        foreach ($keyboardPatterns as $pattern) {
            if (strpos($cleanName, $pattern) !== false && strlen($cleanName) < 10) {
                return true;
            }
        }

        // Skip vowel check if name contains Bangla/Unicode characters
        // Bangla Unicode range: \x{0980}-\x{09FF}
        // Also check for other non-ASCII characters
        if (preg_match('/[\x{0980}-\x{09FF}]/u', $cleanName) || preg_match('/[^\x00-\x7F]/', $cleanName)) {
            return false; // Allow Bangla and other Unicode names
        }

        // Check for lack of vowels (only for English/ASCII names)
        $vowelCount = preg_match_all('/[aeiou]/i', $cleanName);
        if ($vowelCount === 0 && strlen($cleanName) > 3) {
            return true;
        }

        return false;
    }

    /**
     * Check custom patterns
     */
    protected function checkCustomPatterns(string $phone, string $name, string $address = ''): array
    {
        $errors = [];

        // Check blocked phone patterns
        if ($this->settings->blocked_phone_patterns) {
            foreach ($this->settings->blocked_phone_patterns as $pattern) {
                if (preg_match('/' . $pattern . '/', $phone)) {
                    $errors[] = "আপনার সঠিক ফোন নম্বর দিন ।";
                    break;
                }
            }
        }

        // Check blocked name patterns
        if ($this->settings->blocked_name_patterns) {
            foreach ($this->settings->blocked_name_patterns as $pattern) {
                if (preg_match('/' . $pattern . '/i', $name)) {
                    $errors[] = "আপনার সঠিক নাম দিন ।";
                    break;
                }
            }
        }

        // Check blocked address patterns
        if ($this->settings->blocked_address_patterns && !empty($address)) {
            foreach ($this->settings->blocked_address_patterns as $pattern) {
                if (preg_match('/' . $pattern . '/i', $address)) {
                    $errors[] = "আপনার সঠিক ঠিকানা দিন ।";
                    break;
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Check if phone or IP is blacklisted
     */
    protected function isBlacklisted(string $phone, string $ip): bool
    {
        $blacklistedPhones = $this->settings->blacklisted_phones ?? [];
        $blacklistedIPs = $this->settings->blacklisted_ips ?? [];

        return in_array($phone, $blacklistedPhones) || in_array($ip, $blacklistedIPs);
    }

    /**
     * Check IP rate limiting
     */
    protected function isIPRateLimitExceeded(string $ip): bool
    {
        $cacheKey = 'order_ip_rate_limit_' . $ip;
        $attempts = Cache::get($cacheKey, 0);
        $maxAttempts = $this->settings->ip_max_orders_per_hour;

        if ($attempts >= $maxAttempts) {
            return true;
        }

        // Increment attempts
        Cache::put($cacheKey, $attempts + 1, now()->addHour());
        return false;
    }

    /**
     * Check courier success rate using hybrid approach: cached fraud data first, then local DB
     */
    protected function checkCourierSuccessRate(string $phone): array
    {
        // First try to get cached fraud data (pre-fetched via AJAX)
        $fraudData = $this->getCachedFraudData($phone);
        
        if ($fraudData) {
            return $this->evaluateFraudData($fraudData);
        }
        
        // Fallback to local database check
        $localData = $this->getLocalCourierData($phone);
        
        if ($localData['total_orders'] >= 3) {
            return $this->evaluateLocalData($localData);
        }
        
        // If no data available, allow by default (new customer)
        return ['valid' => true];
    }

    /**
     * Get cached fraud data from FraudCheckResult table
     */
    protected function getCachedFraudData(string $phone): ?array
    {
        $cleanPhone = preg_replace('/\D/', '', $phone);
        
        $fraudResult = FraudCheckResult::where('phone', $cleanPhone)
            ->where('last_checked_at', '>=', now()->subDays(30))
            ->first();
            
        if (!$fraudResult || !$fraudResult->has_courier_history) {
            return null;
        }
        
        return [
            'total_orders' => $fraudResult->total_parcels,
            'delivered_orders' => $fraudResult->delivered_parcels,
            'bad_orders' => $fraudResult->canceled_parcels,
            'success_rate' => $fraudResult->delivery_success_rate,
            'bad_rate' => $this->calculateBadRate($fraudResult->total_parcels, $fraudResult->canceled_parcels),
        ];
    }

    /**
     * Get courier data from local database
     */
    protected function getLocalCourierData(string $phone): array
    {
        $variants = $this->getPhoneVariants($phone);
        $last10 = preg_replace('/\D/', '', $phone ?? '');
        $last10 = strlen($last10) >= 10 ? substr($last10, -10) : $last10;

        $query = function($q) use ($variants, $last10) {
            $q->whereIn('phone', $variants)
              ->orWhere('phone', 'like', '%' . $last10 . '%')
              ->orWhere('phone', 'like', '%+88' . $last10 . '%')
              ->orWhere('phone', 'like', '%88' . $last10 . '%');
        };

        $totalOrders = order::where($query)->count();
        $deliveredOrders = order::where($query)->where('status', 'delivered')->count();
        $badOrders = order::where($query)->whereIn('status', ['cancelled', 'returned', 'failed'])->count();

        return [
            'total_orders' => $totalOrders,
            'delivered_orders' => $deliveredOrders,
            'bad_orders' => $badOrders,
            'success_rate' => $totalOrders > 0 ? ($deliveredOrders / $totalOrders) * 100 : 0,
            'bad_rate' => $totalOrders > 0 ? ($badOrders / $totalOrders) * 100 : 0,
        ];
    }

    /**
     * Evaluate fraud data against thresholds
     */
    protected function evaluateFraudData(array $data): array
    {
        $successRate = $data['success_rate'] ?? 0;
        $badRate = $data['bad_rate'] ?? 0;

        if ($successRate < $this->settings->min_success_rate || 
            $badRate > $this->settings->max_bad_history_rate) {
            
            $message = $this->settings->fraud_detected_message ?? 
                'আপনার পূর্ববর্তী অর্ডার ইতিহাসের কারণে নতুন অর্ডার করতে পারছেন না।';
            
            return ['valid' => false, 'message' => $message];
        }

        return ['valid' => true];
    }

    /**
     * Evaluate local data against thresholds
     */
    protected function evaluateLocalData(array $data): array
    {
        $successRate = $data['success_rate'] ?? 0;
        $badRate = $data['bad_rate'] ?? 0;

        if ($successRate < $this->settings->min_success_rate || 
            $badRate > $this->settings->max_bad_history_rate) {
            
            $message = $this->settings->fraud_detected_message ?? 
                'আপনার পূর্ববর্তী অর্ডার ইতিহাসের কারণে নতুন অর্ডার করতে পারছেন না।';
            
            return ['valid' => false, 'message' => $message];
        }

        return ['valid' => true];
    }

    /**
     * Calculate bad rate from total and bad orders
     */
    protected function calculateBadRate(int $total, int $bad): float
    {
        return $total > 0 ? ($bad / $total) * 100 : 0;
    }

    /**
     * Normalize phone to local 11-digit (Bangladesh) when possible and generate common variants
     */
    private function getPhoneVariants(string $phone): array
    {
        $digits = preg_replace('/\D/', '', $phone ?? '');

        // Try to derive local form starting with 0 and 11 digits
        $local = $digits;
        if (strpos($digits, '88') === 0 && strlen($digits) >= 13) {
            // 88 + 11-digit
            $local = '0' . substr($digits, -10);
        } elseif (strlen($digits) === 13 && strpos($digits, '88') === 0) {
            $local = '0' . substr($digits, -10);
        } elseif (strlen($digits) === 14 && strpos($digits, '880') === 0) {
            $local = '0' . substr($digits, -10);
        } elseif (strlen($digits) >= 11) {
            $local = '0' . substr($digits, -10);
        }

        $variants = array_values(array_unique([
            $phone,
            $digits,
            $local,
            '+88' . $local,
            '88' . ltrim($local, '+'),
        ]));

        return $variants;
    }

    /**
     * Log blocked attempt
     */
    protected function logBlockedAttempt(array $data, array $errors): void
    {
        // Determine blocking reason and module
        $reason = 'fraud'; // default
        $module = '3'; // default
        
        // Check which module blocked
        if ($this->settings->duplicate_protection_enabled) {
            $reason = 'duplicate';
            $module = '1';
        } elseif ($this->settings->fake_protection_enabled) {
            $reason = 'fake';
            $module = '2';
        }

        // Log to file
        Log::channel('daily')->warning('Fraud Protection: Order blocked', [
            'phone' => $data['phone'] ?? 'N/A',
            'name' => $data['name'] ?? 'N/A',
            'ip' => request()->ip(),
            'reason' => $reason,
            'module' => $module,
            'errors' => $errors,
            'timestamp' => now()->toDateTimeString(),
        ]);

        // Log to database (if BlockedOrderAttempt model exists)
        if (class_exists('\App\Models\BlockedOrderAttempt')) {
            \App\Models\BlockedOrderAttempt::logAttempt($data, $reason, $module, $errors);
        }
    }

    /**
     * Get settings for frontend
     */
    public function getFrontendSettings(): array
    {
        return [
            'enabled' => $this->settings->isAnyModuleEnabled(),
            'phone_validation' => [
                'enabled' => $this->settings->phone_validation_enabled,
                'whitelist_enabled' => $this->settings->phone_whitelist_enabled ?? false,
                'allowed_lengths' => $this->settings->allowed_phone_lengths ?? [11, 12, 14],
            ],
            'name_validation' => [
                'enabled' => $this->settings->name_validation_enabled,
                'min_length' => $this->settings->name_min_length ?? 2,
                'max_length' => $this->settings->name_max_length ?? 100,
                'disallow_numeric' => $this->settings->name_disallow_numeric ?? true,
            ],
            'address_validation' => [
                'enabled' => $this->settings->address_validation_enabled,
                'min_length' => $this->settings->address_min_length ?? 10,
            ],
            'messages' => [
                'phone_invalid' => $this->settings->fake_data_message,
                'sequential_phone' => $this->settings->fake_data_message,
                'repeated_name' => $this->settings->fake_data_message,
                'gibberish_name' => $this->settings->fake_data_message,
                'address_short' => $this->settings->fake_data_message,
                'name_length' => $this->settings->fake_data_message,
                'name_numeric' => $this->settings->fake_data_message,
            ],
            'block_sequential' => $this->settings->block_sequential_numbers,
            'block_repeated_names' => $this->settings->block_repeated_names,
            'block_gibberish' => $this->settings->block_gibberish_names,
        ];
    }
}
