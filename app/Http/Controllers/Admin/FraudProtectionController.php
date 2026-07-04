<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FraudProtectionSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FraudProtectionController extends Controller
{
    /**
     * Display fraud protection settings
     */
    public function index()
    {
        $fraudSettings = FraudProtectionSetting::getSettings();
        return view('admin.fraud-protection.index', compact('fraudSettings'));
    }

    /**
     * Update fraud protection settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // Module 1: Duplicate Order Protection
            'duplicate_protection_enabled' => 'boolean',
            'order_interval_enabled' => 'boolean',
            'order_interval_minutes' => 'nullable|integer|min:1',
            'pending_order_restriction_enabled' => 'boolean',
            
            // Module 2: Fake Order Protection
            'fake_protection_enabled' => 'boolean',
            'phone_validation_enabled' => 'boolean',
            'phone_whitelist_enabled' => 'boolean',
            'allowed_phone_lengths' => 'nullable|array',
            'name_validation_enabled' => 'boolean',
            'name_min_length' => 'nullable|integer|min:1',
            'name_max_length' => 'nullable|integer|min:1',
            'name_disallow_numeric' => 'boolean',
            'block_sequential_numbers' => 'boolean',
            'block_repeated_names' => 'boolean',
            'block_gibberish_names' => 'boolean',
            'custom_pattern_enabled' => 'boolean',
            'blocked_phone_patterns' => 'nullable|array',
            'blocked_name_patterns' => 'nullable|array',
            'blocked_address_patterns' => 'nullable|array',
            'address_validation_enabled' => 'boolean',
            'address_min_length' => 'nullable|integer|min:1',
            
            // Module 3: Fraud & Scam Protection
            'fraud_protection_enabled' => 'boolean',
            'blacklist_enabled' => 'boolean',
            'blacklisted_phones' => 'nullable|array',
            'blacklisted_ips' => 'nullable|array',
            'ip_rate_limiting_enabled' => 'boolean',
            'ip_max_orders_per_hour' => 'nullable|integer|min:1',
            'courier_success_check_enabled' => 'boolean',
            'min_success_rate' => 'nullable|numeric|min:0|max:100',
            'max_bad_history_rate' => 'nullable|numeric|min:0|max:100',
            
            // Alert & Notifications
            'send_admin_alerts' => 'boolean',
            'admin_alert_email' => 'nullable|email',
            'log_blocked_attempts' => 'boolean',
            
            // Custom Messages
            'duplicate_order_message' => 'nullable|string',
            'fake_data_message' => 'nullable|string',
            'fraud_detected_message' => 'nullable|string',
            'blacklist_message' => 'nullable|string',
        ]);

        // Convert checkboxes to boolean (handle unchecked = null)
        $booleanFields = [
            'duplicate_protection_enabled',
            'order_interval_enabled',
            'pending_order_restriction_enabled',
            'fake_protection_enabled',
            'phone_validation_enabled',
            'phone_whitelist_enabled',
            'name_validation_enabled',
            'name_disallow_numeric',
            'block_sequential_numbers',
            'block_repeated_names',
            'block_gibberish_names',
            'custom_pattern_enabled',
            'address_validation_enabled',
            'fraud_protection_enabled',
            'blacklist_enabled',
            'ip_rate_limiting_enabled',
            'courier_success_check_enabled',
            'send_admin_alerts',
            'log_blocked_attempts',
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->has($field);
        }

        // Process arrays from textarea inputs
        if ($request->has('blacklisted_phones_text')) {
            $validated['blacklisted_phones'] = $this->textToArray($request->blacklisted_phones_text);
        }

        if ($request->has('blacklisted_ips_text')) {
            $validated['blacklisted_ips'] = $this->textToArray($request->blacklisted_ips_text);
        }

        if ($request->has('blocked_phone_patterns_text')) {
            $validated['blocked_phone_patterns'] = $this->textToArray($request->blocked_phone_patterns_text);
        }

        if ($request->has('blocked_name_patterns_text')) {
            $validated['blocked_name_patterns'] = $this->textToArray($request->blocked_name_patterns_text);
        }

        if ($request->has('blocked_address_patterns_text')) {
            $validated['blocked_address_patterns'] = $this->textToArray($request->blocked_address_patterns_text);
        }

        // Process allowed phone lengths from checkboxes
        if ($request->has('allowed_phone_lengths')) {
            $validated['allowed_phone_lengths'] = array_map('intval', $request->allowed_phone_lengths);
        } else {
            $validated['allowed_phone_lengths'] = [];
        }

        $settings = FraudProtectionSetting::getSettings();
        $settings->update($validated);

        return redirect()
            ->route('admin.fraud-protection.index')
            ->with('success', 'Fraud Protection settings updated successfully!');
    }

    /**
     * Convert textarea input to array
     */
    private function textToArray($text)
    {
        if (empty($text)) {
            return [];
        }

        $lines = explode("\n", $text);
        $array = array_map('trim', $lines);
        return array_filter($array); // Remove empty lines
    }

    /**
     * Get fraud protection logs
     */
    public function logs(Request $request)
    {
        // Get blocked attempts from database (if model exists)
        if (class_exists('\App\Models\BlockedOrderAttempt')) {
            $query = \App\Models\BlockedOrderAttempt::query();

            // Apply filters
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('ip_address', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%")
                      ->orWhere('block_reason', 'like', "%{$search}%");
                });
            }

            // Date range filter
            if ($request->filled('date_from')) {
                $query->whereDate('blocked_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('blocked_at', '<=', $request->date_to);
            }

            // Quick days filter
            if ($request->filled('days')) {
                $days = (int) $request->days;
                $query->where('blocked_at', '>=', now()->subDays($days));
            }

            // Fraud type filter (block_reason column)
            if ($request->filled('fraud_type')) {
                $query->where('block_reason', $request->fraud_type);
            }

            // Module filter (blocked_by_module column)
            if ($request->filled('module')) {
                $query->where('blocked_by_module', $request->module);
            }

            $attempts = $query->orderBy('blocked_at', 'desc')->paginate(50);
            $stats = \App\Models\BlockedOrderAttempt::getStats(24);
            
            // Get blacklist data
            $settings = \App\Models\FraudProtectionSetting::getSettings();
            $blacklistedPhones = $settings->blacklisted_phones ?? [];
            $blacklistedIps = $settings->blacklisted_ips ?? [];
            
            return view('admin.fraud-protection.logs', compact('attempts', 'stats', 'blacklistedPhones', 'blacklistedIps'));
        }

        // Fallback to file-based logs
        $logFile = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logFile)) {
            $content = file_get_contents($logFile);
            preg_match_all('/Fraud Protection: Order blocked.*?\n/s', $content, $matches);
            $logs = array_slice(array_reverse($matches[0]), 0, 100);
        }

        return view('admin.fraud-protection.logs', compact('logs'));
    }

    /**
     * Clear blocked attempt logs
     */
    public function clearLogs()
    {
        if (class_exists('\App\Models\BlockedOrderAttempt')) {
            \App\Models\BlockedOrderAttempt::truncate();
        }

        return redirect()
            ->route('admin.fraud-protection.logs')
            ->with('success', 'Logs cleared successfully!');
    }

    /**
     * Add phone to blacklist from logs
     */
    public function addToBlacklist(Request $request)
    {
        $type = $request->input('type');
        $values = $request->input('values');

        // Backwards compatibility: allow single phone/ip payloads
        if (empty($values)) {
            if ($request->filled('phone')) {
                $type = $type ?: 'phone';
                $values = [$request->input('phone')];
            } elseif ($request->filled('ip')) {
                $type = $type ?: 'ip';
                $values = [$request->input('ip')];
            }
        }

        // Normalise payload
        $type = $type === 'phone_number' ? 'phone' : $type;
        $values = is_array($values) ? $values : ($values ? [$values] : []);

        $validator = Validator::make(
            ['type' => $type, 'values' => $values],
            [
                'type' => 'required|in:phone,ip',
                'values' => 'required|array|min:1',
                'values.*' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $cleanValues = array_values(array_unique(array_filter(array_map('trim', $values))));

        $settings = FraudProtectionSetting::getSettings();
        $addedCount = 0;
        $responseData = [
            'success' => true,
            'type' => $type,
        ];

        if ($type === 'phone') {
            $blacklist = $settings->blacklisted_phones ?? [];

            foreach ($cleanValues as $phone) {
                if ($phone !== '' && !in_array($phone, $blacklist)) {
                    $blacklist[] = $phone;
                    $addedCount++;
                }
            }

            $settings->update(['blacklisted_phones' => $blacklist]);
            $responseData['message'] = "Added {$addedCount} phone number(s) to blacklist";
        } else {
            $blacklist = $settings->blacklisted_ips ?? [];

            foreach ($cleanValues as $ip) {
                if ($ip !== '' && !in_array($ip, $blacklist)) {
                    $blacklist[] = $ip;
                    $addedCount++;
                }
            }

            $settings->update(['blacklisted_ips' => $blacklist]);
            $responseData['message'] = "Added {$addedCount} IP address(es) to blacklist";
        }

        $responseData['added_count'] = $addedCount;

        return response()->json($responseData);
    }

    /**
     * Export blocked attempts to CSV
     */
    public function exportLogs()
    {
        if (!class_exists('\App\Models\BlockedOrderAttempt')) {
            return redirect()->back()->with('error', 'Export not available');
        }

        $attempts = \App\Models\BlockedOrderAttempt::orderBy('blocked_at', 'desc')->get();
        
        $filename = 'blocked_attempts_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attempts) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['Time', 'Name', 'Phone', 'IP', 'Module', 'Reason', 'Errors']);
            
            // Data
            foreach ($attempts as $attempt) {
                fputcsv($file, [
                    $attempt->blocked_at->format('Y-m-d H:i:s'),
                    $attempt->name,
                    $attempt->phone,
                    $attempt->ip_address,
                    $attempt->module_name,
                    $attempt->formatted_reason,
                    implode('; ', $attempt->validation_errors ?? []),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk delete selected blocked attempts
     */
    public function bulkDelete(Request $request)
    {
        if (!class_exists('\App\Models\BlockedOrderAttempt')) {
            return response()->json([
                'success' => false,
                'message' => 'BlockedOrderAttempt model not found'
            ], 404);
        }

        $request->validate([
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'integer|exists:blocked_order_attempts,id'
        ]);

        try {
            $selectedIds = $request->selected_ids;
            $deletedCount = \App\Models\BlockedOrderAttempt::whereIn('id', $selectedIds)->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} entries",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting entries: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete single blocked attempt
     */
    public function deleteSingle(Request $request)
    {
        if (!class_exists('\App\Models\BlockedOrderAttempt')) {
            return response()->json([
                'success' => false,
                'message' => 'BlockedOrderAttempt model not found'
            ], 404);
        }

        $request->validate([
            'id' => 'required|integer|exists:blocked_order_attempts,id'
        ]);

        try {
            $attempt = \App\Models\BlockedOrderAttempt::findOrFail($request->id);
            $attempt->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Entry deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting entry: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unblock phone number from blacklist
     */
    public function unblockPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        try {
            $settings = \App\Models\FraudProtectionSetting::getSettings();
            $blacklistedPhones = $settings->blacklisted_phones ?? [];
            
            // Remove phone from blacklist
            $blacklistedPhones = array_filter($blacklistedPhones, function($phone) use ($request) {
                return $phone !== $request->phone;
            });
            
            $settings->update(['blacklisted_phones' => array_values($blacklistedPhones)]);
            
            return response()->json([
                'success' => true,
                'message' => "Phone number {$request->phone} has been removed from blacklist"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing phone from blacklist: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unblock IP address from blacklist
     */
    public function unblockIp(Request $request)
    {
        $request->validate([
            'ip' => 'required|string'
        ]);

        try {
            $settings = \App\Models\FraudProtectionSetting::getSettings();
            $blacklistedIps = $settings->blacklisted_ips ?? [];
            
            // Remove IP from blacklist
            $blacklistedIps = array_filter($blacklistedIps, function($ip) use ($request) {
                return $ip !== $request->ip;
            });
            
            $settings->update(['blacklisted_ips' => array_values($blacklistedIps)]);
            
            return response()->json([
                'success' => true,
                'message' => "IP address {$request->ip} has been removed from blacklist"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing IP from blacklist: ' . $e->getMessage()
            ], 500);
        }
    }

}
