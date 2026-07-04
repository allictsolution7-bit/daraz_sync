<?php

namespace App\Services;

use App\Models\License;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class LicenseService
{
    // CRITICAL: These URLs are hardcoded for security - changing them will break the system
    private const MOTHER_PANEL_URL = 'https://uddoktaecommerce.com/api/licenses/validate';
    //private const MOTHER_PANEL_URL = 'http://127.0.0.1:8001/api/licenses/validate';
     private const BACKUP_MOTHER_PANEL_URL = 'https://uddoktaecommerce.com/api/licenses/validate';
    // private const BACKUP_MOTHER_PANEL_URL = 'http://127.0.0.1:8001/api/licenses/validate';
    private const CACHE_TTL = 3 * 60 * 60;  
    
    // Security constants - DO NOT MODIFY
    private const REQUIRED_SIGNATURE_VERIFICATION = true;
    private const REQUIRED_ENCRYPTION = true;
    private const REQUIRED_INTEGRITY_CHECK = true;

    /**
     * Validate license key with mother panel
     */
    public function validateLicense(string $licenseKey, bool $forceSync = false): array
    {
        $cacheKey = "license_validation_{$licenseKey}";
        
        // Check cache first unless force sync is requested
        if (!$forceSync && Cache::has($cacheKey)) {
            return Cache::get($cacheKey) ?? [];
        }

        try {
            $response = $this->callMotherPanelAPI($licenseKey);
            
            if ($response['success']) {
                // CRITICAL SECURITY: Always verify signature regardless of config
                $configSignatureEnabled = config('license.enable_signature_verification', true);
                
                // Force signature verification in production or if tampering detected
                $forceSignatureCheck = (config('app.env') === 'production') || 
                                     self::REQUIRED_SIGNATURE_VERIFICATION ||
                                     $this->detectTamperingAttempt();
                
                if ($forceSignatureCheck || $configSignatureEnabled) {
                    if ($this->verifySignature($response['data'], $response['signature'] ?? '')) {
                        // Cache the valid response
                        $cacheTtl = config('license.cache_ttl', self::CACHE_TTL);
                        Cache::put($cacheKey, $response, $cacheTtl);
                        
                        // Update or create license record
                        $this->updateLicenseRecord($licenseKey, $response['data']);
                        
                        return $response;
                    } else {
                        Log::critical('License validation failed: Invalid signature - TAMPERING DETECTED', [
                            'license_key' => $licenseKey,
                            'signature' => $response['signature'] ?? 'missing',
                            'forced_check' => $forceSignatureCheck,
                            'config_enabled' => $configSignatureEnabled,
                            'ip_address' => request()->ip()
                        ]);
                        
                        return [
                            'success' => false,
                            'message' => 'License validation failed: Invalid signature'
                        ];
                    }
                } else {
                    // Signature verification bypassed - log security risk
                    Log::warning('License validation: Signature verification bypassed - SECURITY RISK', [
                        'license_key' => $licenseKey,
                        'config_enabled' => $configSignatureEnabled,
                        'ip_address' => request()->ip()
                    ]);
                    
                    // Cache the response
                    $cacheTtl = config('license.cache_ttl', self::CACHE_TTL);
                    Cache::put($cacheKey, $response, $cacheTtl);
                    
                    // Update or create license record
                    $this->updateLicenseRecord($licenseKey, $response['data']);
                    
                    return $response;
                }
            } else {
                // Handle unsuccessful response (suspended, expired, etc.)
                // Update local license record to reflect the current status
                $this->handleLicenseError($licenseKey, $response);
                return $response;
            }
        } catch (Exception $e) {
            Log::error('License validation failed', [
                'license_key' => $licenseKey,
                'error' => $e->getMessage()
            ]);

            // Preserve last known good state on transient network issues
            if ($this->isNetworkError($e)) {
                if (Cache::has($cacheKey)) {
                    Log::info('Using cached license data due to network failure', [
                        'license_key' => $licenseKey
                    ]);
                    return Cache::get($cacheKey) ?? [];
                }

                return [
                    'success' => false,
                    'message' => 'Network issue during license sync. Will retry shortly.',
                    'data' => null
                ];
            }

            // Only suspend on explicit validation failures (non-network)
            $this->suspendLicenseOnValidationFailure($licenseKey, $e->getMessage());

            return [
                'success' => false,
                'message' => 'Unable to validate license: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Call the mother panel API
     */
    private function callMotherPanelAPI(string $licenseKey): array
    {
        // Resolve canonical domain to avoid false domain mismatches
        $domain = $this->getCanonicalDomain();
        
        // CRITICAL SECURITY: Validate URL is legitimate
        $motherPanelUrl = $this->validateAndGetSecureURL();
        $response = Http::timeout(5)->post($motherPanelUrl, [
            'license_key' => $licenseKey,
            'domain' => $domain,
            'app_version' => config('app.version', '1.0.0'),
            'timestamp' => now()->timestamp,
        ]);

        if (!$response->successful()) {
            // Handle 403 errors (license suspended/expired) specifically
            if ($response->status() === 403) {
                $errorData = $response->json();
                return [
                    'success' => false,
                    'message' => $errorData['message'] ?? 'License is inactive',
                    'status' => $errorData['status'] ?? 'suspended',
                    'expiry_date' => $errorData['expiry_date'] ?? null,
                ];
            }
            
            // Log other HTTP errors for debugging
            Log::error('Mother panel API response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers(),
                'url' => $motherPanelUrl,
                'data_sent' => [
                    'license_key' => $licenseKey,
                    'domain' => $domain,
                    'app_version' => config('app.version', '1.0.0'),
                    'timestamp' => now()->timestamp,
                ]
            ]);
            
            throw new Exception('HTTP request failed: ' . $response->status());
        }

        return $response->json();
    }

    /**
     * Validate and get secure URL - prevents tampering
     */
    private function validateAndGetSecureURL(): string
    {
        $configuredUrl = config('license.mother_panel_url');
        
        // CRITICAL: Only allow legitimate URLs
        $allowedDomains = [
            'uddoktaecommerce.com',
            'www.uddoktaecommerce.com',
            '127.0.0.1', // For development only
            'localhost'  // For development only
        ];
        
        // Parse the configured URL
        $parsedUrl = parse_url($configuredUrl);
        $host = $parsedUrl['host'] ?? '';
        
        // Check if host is in allowed list
        $isAllowed = false;
        foreach ($allowedDomains as $allowedDomain) {
            if ($host === $allowedDomain || str_ends_with($host, '.' . $allowedDomain)) {
                $isAllowed = true;
                break;
            }
        }
        
        // If URL is tampered with, use hardcoded URL and log the attempt
        if (!$isAllowed) {
            Log::critical('LICENSE SYSTEM TAMPERING DETECTED', [
                'configured_url' => $configuredUrl,
                'parsed_host' => $host,
                'allowed_domains' => $allowedDomains,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now()->toISOString()
            ]);
            
            // Use hardcoded URL as fallback
            return self::MOTHER_PANEL_URL;
        }
        
        // Additional validation for production
        if (config('app.env') === 'production') {
            // In production, only allow HTTPS
            if (!str_starts_with($configuredUrl, 'https://')) {
                Log::critical('SECURITY VIOLATION: Non-HTTPS URL in production', [
                    'configured_url' => $configuredUrl,
                    'environment' => config('app.env')
                ]);
                return self::MOTHER_PANEL_URL;
            }
        }
        
        return $configuredUrl;
    }

    /**
     * Normalize a domain/host for comparison and API usage.
     */
    private function normalizeDomain(?string $domain): string
    {
        if (!$domain) {
            return '';
        }

        // Strip protocol and trailing slashes
        $domain = preg_replace('#^https?://#i', '', $domain);
        $domain = rtrim($domain, '/');

        // Lowercase host part
        $parts = explode('/', $domain);
        $hostPort = strtolower($parts[0]);

        // Collapse www.
        $hostPort = preg_replace('/^www\./', '', $hostPort);

        return $hostPort;
    }

    /**
     * Resolve the canonical domain we should use for validation.
     * Priority: env override -> stored license domain -> current request host.
     */
    private function getCanonicalDomain(): string
    {
        // Env override for explicit domain binding
        $envDomain = env('LICENSE_DOMAIN');
        if ($envDomain) {
            return $this->normalizeDomain($envDomain);
        }

        // Stored license domain
        $license = License::first();
        if ($license && $license->domain) {
            return $this->normalizeDomain($license->domain);
        }

        // Fallback to current request host
        $current = request()->getHost();
        if (!$current) {
            $current = config('app.url', '127.0.0.1:8000');
        }

        return $this->normalizeDomain($current);
    }

    /**
     * Check if the current request host matches the licensed/canonical domain.
     */
    private function isCurrentDomainAllowed(): bool
    {
        $expected = $this->getCanonicalDomain();
        $current = $this->normalizeDomain(request()->getHost());

        if ($current === 'localhost' || $current === '127.0.0.1') {
            return true;
        }

        if ($expected && $current && $expected !== $current) {
            return false;
        }

        return true;
    }

    /**
     * Detect tampering attempts
     */
    private function detectTamperingAttempt(): bool
    {
        // Check if critical files have been modified
        $criticalFiles = [
            __FILE__, // This file itself
            base_path('config/license.php'),
            base_path('app/Http/Middleware/CheckLicense.php'),
            base_path('app/Models/License.php')
        ];
        
        foreach ($criticalFiles as $file) {
            if (file_exists($file)) {
                $fileHash = hash_file('sha256', $file);
                $expectedHash = $this->getExpectedFileHash($file);
                
                if ($expectedHash && $fileHash !== $expectedHash) {
                    Log::critical('FILE TAMPERING DETECTED', [
                        'file' => $file,
                        'expected_hash' => $expectedHash,
                        'actual_hash' => $fileHash,
                        'ip_address' => request()->ip()
                    ]);
                    return true;
                }
            }
        }
        
        // Check for suspicious configuration changes
        $suspiciousConfigs = [
            'license.enable_signature_verification' => false,
            'license.mother_panel_url' => ['localhost', '127.0.0.1', 'fake-domain.com']
        ];
        
        foreach ($suspiciousConfigs as $configKey => $suspiciousValues) {
            $configValue = config($configKey);
            if (in_array($configValue, (array)$suspiciousValues)) {
                Log::warning('SUSPICIOUS CONFIGURATION DETECTED', [
                    'config_key' => $configKey,
                    'config_value' => $configValue,
                    'ip_address' => request()->ip()
                ]);
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get expected file hash for integrity checking
     */
    private function getExpectedFileHash(string $file): ?string
    {
        // In production, these hashes should be stored securely
        // For now, we'll calculate them dynamically
        $fileHashes = [
            // Add known good hashes here in production
        ];
        
        return $fileHashes[$file] ?? null;
    }

    /**
     * Verify RSA signature of the response
     */
    private function verifySignature(array $data, string $signature): bool
    {
        try {
            if (empty($signature)) {
                Log::warning('Signature verification failed: Empty signature');
                return false;
            }

            // Get RSA public key from configuration
            $publicKey = config('license.public_key');
            if (empty($publicKey)) {
                Log::error('Signature verification failed: RSA public key not configured');
                return false;
            }

            // Create the data string exactly as the mother panel signed it
            $dataString = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            // Decode the base64 signature from mother panel
            $decodedSignature = base64_decode($signature);
            
            if ($decodedSignature === false) {
                Log::error('Signature verification failed: Invalid base64 signature');
                return false;
            }
            
            // Check if RSA key is valid
            $keyResource = openssl_pkey_get_public($publicKey);
            if ($keyResource === false) {
                Log::error('Signature verification failed: Invalid RSA public key format');
                return false;
            }
            
            // Get signature algorithm from configuration
            $algorithm = config('license.signature_algorithm', OPENSSL_ALGO_SHA256);
            
            // Verify using the RSA public key
            $result = openssl_verify(
                $dataString, 
                $decodedSignature, 
                $keyResource, 
                $algorithm
            );
            
            // Free the key resource (deprecated in PHP 8.0+)
            if (function_exists('openssl_free_key')) {
                openssl_free_key($keyResource);
            }
            
            if ($result === -1) {
                Log::error('Signature verification failed: openssl_verify error');
                return false;
            }
            
            $isValid = $result === 1; // 1 means signature is valid
            
            if (config('license.debug', false)) {
                Log::info('Signature verification result', [
                    'data_hash' => hash('sha256', $dataString),
                    'signature_length' => strlen($signature),
                    'is_valid' => $isValid
                ]);
            }
            
            return $isValid;
            
        } catch (Exception $e) {
            Log::error('Signature verification failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Verify a signed payload that comes from the mother panel.
     */
    public function verifySignedPayload(array $data, string $signature): bool
    {
        return $this->verifySignature($data, $signature);
    }

    /**
     * Handle license error response (suspended, expired, etc.)
     */
    private function handleLicenseError(string $licenseKey, array $response): void
    {
        $license = License::where('license_key', $licenseKey)->first();
        
        if (!$license) {
            return; // No local license to update
        }

        $message = $response['message'] ?? '';
        $statusFromApi = $response['status'] ?? null;

        // Domain mismatch: keep status unchanged but log and surface message
        if (str_contains($message, 'Invalid license key or domain')) {
            Log::warning('License domain mismatch detected', [
                'license_key' => $licenseKey,
                'message' => $message,
                'current_domain' => request()->getHost(),
                'registered_domain' => $license->domain ?? 'unknown'
            ]);
            $license->last_synced_at = now();
            $license->save();
            Cache::forget($cacheKey);
            return;
        }

        // Determine the status based on explicit API status or message
        $status = $license->status;

        if (in_array($statusFromApi, ['suspended', 'revoked', 'expired'])) {
            $status = $statusFromApi;
        } elseif (str_contains($message, 'expired')) {
            $status = 'expired';
        } elseif (str_contains($message, 'inactive')) {
            $status = 'suspended';
        }

        // Update the license status
        $license->status = $status;
        $license->last_synced_at = now();
        $license->save();

        // Clear any cached data for this license
        $cacheKey = "license_validation_{$licenseKey}";
        Cache::forget($cacheKey);
    }

    /**
     * Suspend license when validation fails (security measure)
     */
    private function suspendLicenseOnValidationFailure(string $licenseKey, string $errorMessage): void
    {
        $license = License::where('license_key', $licenseKey)->first();
        
        if (!$license) {
            return;
        }

        // Suspend license for validation failures
        $license->status = 'suspended';
        $license->last_synced_at = now();
        $license->save();

        // Clear cached data
        $cacheKey = "license_validation_{$licenseKey}";
        Cache::forget($cacheKey);

        Log::critical('License suspended due to validation failure', [
            'license_key' => $licenseKey,
            'error' => $errorMessage,
            'domain' => request()->getHost(),
            'ip' => request()->ip()
        ]);
    }

    /**
     * Check if error is a network issue (not validation failure)
     */
    private function isNetworkError(Exception $e): bool
    {
        $networkErrors = [
            'Connection refused',
            'Connection timed out',
            'Network is unreachable',
            'SSL connection error',
            'DNS resolution failed'
        ];

        $errorMessage = $e->getMessage();
        foreach ($networkErrors as $networkError) {
            if (str_contains($errorMessage, $networkError)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Clear all license-related caches
     */
    private function clearAllLicenseCaches(): void
    {
        try {
            // Get all licenses to clear their specific caches
            $licenses = License::all();
            foreach ($licenses as $license) {
                $cacheKey = "license_validation_{$license->license_key}";
                Cache::forget($cacheKey);
            }
            
            // Also clear any other license-related caches
            Cache::forget('license_status');
            Cache::forget('license_modules');
            Cache::forget('license_heartbeat');
            
            Log::info('All license caches cleared', [
                'license_count' => $licenses->count(),
                'timestamp' => now()->toISOString()
            ]);
        } catch (Exception $e) {
            Log::error('Failed to clear license caches', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Clear only license validation caches (used by clearSecurityViolations)
     */
    private function clearLicenseValidationCaches(): void
    {
        try {
            // Get all licenses to clear their specific caches
            $licenses = License::all();
            foreach ($licenses as $license) {
                $cacheKey = "license_validation_{$license->license_key}";
                Cache::forget($cacheKey);
            }
            
            // Also clear any other license-related caches
            Cache::forget('license_status');
            Cache::forget('license_modules');
            Cache::forget('license_heartbeat');
        } catch (Exception $e) {
            Log::error('Failed to clear license validation caches', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if domain has changed (security measure)
     */
    private function hasDomainChanged(License $license): bool
    {
        $currentDomain = $this->normalizeDomain(request()->getHost());
        $registeredDomain = $this->normalizeDomain($license->domain);

        if (!$registeredDomain || !$currentDomain) {
            return false; // Nothing to compare
        }

        return $currentDomain !== $registeredDomain;
    }

    /**
     * Update or create license record in database
     */
    private function updateLicenseRecord(string $licenseKey, array $data): void
    {
        $license = License::where('license_key', $licenseKey)->first();
        
        if (!$license) {
            // Check if there are any existing licenses (to avoid duplicates)
            $existingLicense = License::first();
            if ($existingLicense) {
                // Update the existing license instead of creating a new one
                $license = $existingLicense;
                $license->license_key = $licenseKey;
                Log::info('Replacing existing license with new license key', [
                    'old_key' => $existingLicense->license_key,
                    'new_key' => $licenseKey,
                    'domain' => $data['domain'] ?? request()->getHost()
                ]);
            } else {
                // No existing license, create new one
                $license = new License();
                $license->license_key = $licenseKey;
            }
        }

        $license->domain = $this->normalizeDomain($data['domain'] ?? $license->domain ?? request()->getHost());
        $license->modules = $data['modules'] ?? ['core'];
        $license->landing_page_limit = $data['landing_page_limit'] ?? 0;
        $license->expiry_date = isset($data['expiry_date']) ? Carbon::parse($data['expiry_date']) : null;
        $license->status = $data['status'] ?? 'active';
        
        // Support period data
        if (isset($data['support_start_date'])) {
            $license->support_start_date = Carbon::parse($data['support_start_date']);
        }
        // Convert support duration to days if it's a string like "1_year"
        $supportDuration = $data['support_duration'] ?? 0;
        if (is_string($supportDuration)) {
            $supportDuration = $this->convertDurationToDays($supportDuration);
        }
        $license->support_duration = $supportDuration;
        if (isset($data['support_end_date'])) {
            $license->support_end_date = Carbon::parse($data['support_end_date']);
        }
        
        // Update period data
        if (isset($data['update_start_date'])) {
            $license->update_start_date = Carbon::parse($data['update_start_date']);
        }
        // Convert update duration to days if it's a string like "1_year"
        $updateDuration = $data['update_duration'] ?? 0;
        if (is_string($updateDuration)) {
            $updateDuration = $this->convertDurationToDays($updateDuration);
        }
        $license->update_duration = $updateDuration;
        if (isset($data['update_end_date'])) {
            $license->update_end_date = Carbon::parse($data['update_end_date']);
        }
        
        $license->last_synced_at = now();
        
        $license->save();
    }

    /**
     * Check if license should be synced (more frequent than default)
     */
    private function shouldSyncLicense(License $license): bool
    {
        if (!$license->last_synced_at) {
            return true;
        }
        
        return (int) $license->last_synced_at->diffInHours(now()) >= 3;
    }

    /**
     * Check if license is valid for a specific module
     */
    public function isModuleAllowed(string $module): bool
    {
        // Look for any license, not just active ones
        $license = License::first();
        
        if (!$license) {
            return false;
        }

        if (!$this->isCurrentDomainAllowed()) {
            return false;
        }

        // Always check for recent updates (sync more frequently)
        if ($this->shouldSyncLicense($license)) {
            $this->validateLicense($license->license_key, true);
            $license->refresh();
        }

        return $license->isActive() && $license->hasModule($module);
    }

    /**
     * Check landing page quota
     */
    public function canCreateLandingPage(): bool
    {
        $license = License::first();
        
        if (!$license) {
            return false;
        }

        if (!$this->isCurrentDomainAllowed()) {
            return false;
        }

        // Check for recent updates
        if ($this->shouldSyncLicense($license)) {
            $this->validateLicense($license->license_key, true);
            $license->refresh();
        }

        return $license->canCreateLandingPage();
    }

    /**
     * Check if support period is active
     */
    public function isSupportActive(): bool
    {
        $license = License::first();
        
        if (!$license) {
            return false;
        }

        if (!$this->isCurrentDomainAllowed()) {
            return false;
        }

        // Check for recent updates
        if ($this->shouldSyncLicense($license)) {
            $this->validateLicense($license->license_key, true);
            $license->refresh();
        }

        return $license->isSupportActive();
    }

    /**
     * Check if update access is available
     */
    public function canAccessUpdates(): bool
    {
        $license = License::first();
        
        if (!$license) {
            return false;
        }

        if (!$this->isCurrentDomainAllowed()) {
            return false;
        }

        // Check for recent updates
        if ($this->shouldSyncLicense($license)) {
            $this->validateLicense($license->license_key, true);
            $license->refresh();
        }

        return $license->canAccessUpdates();
    }

    /**
     * Convert duration string to days
     * Examples: "1_year" -> 365, "6_months" -> 180, "30_days" -> 30
     */
    private function convertDurationToDays(string $duration): int
    {
        $duration = strtolower(trim($duration));
        
        if ($duration === 'lifetime') {
            return 0;
        }

        if (preg_match('/^(\d+)_year(s)?$/', $duration, $matches)) {
            return (int) $matches[1] * 365;
        }
        
        if (preg_match('/^(\d+)_month(s)?$/', $duration, $matches)) {
            return (int) $matches[1] * 30;
        }
        
        if (preg_match('/^(\d+)_day(s)?$/', $duration, $matches)) {
            return (int) $matches[1];
        }
        
        // If it's already a number, return it
        if (is_numeric($duration)) {
            return (int) $duration;
        }
        
        // Default fallback
        return 0;
    }

    /**
     * Check if premium features are accessible (requires active support)
     */
    public function canAccessPremiumFeatures(): bool
    {
        $license = License::first();
        
        if (!$license) {
            return false;
        }

        if (!$this->isCurrentDomainAllowed()) {
            return false;
        }

        // Check for recent updates
        if ($this->shouldSyncLicense($license)) {
            $this->validateLicense($license->license_key, true);
            $license->refresh();
        }

        return $license->hasFullAccess();
    }

    /**
     * Get support status for display
     */
    public function getSupportStatus(): array
    {
        $license = License::first();
        
        if (!$license) {
            return [
                'active' => false,
                'expired' => true,
                'start_date' => null,
                'end_date' => null,
                'duration' => 0,
                'remaining_days' => 0,
                'status_text' => 'No license found',
            ];
        }

        // Check for recent updates
        if ($this->shouldSyncLicense($license)) {
            $this->validateLicense($license->license_key, true);
            $license->refresh();
        }

        return $license->getSupportStatus();
    }

    /**
     * Get update status for display
     */
    public function getUpdateStatus(): array
    {
        $license = License::first();
        
        if (!$license) {
            return [
                'active' => false,
                'expired' => true,
                'start_date' => null,
                'end_date' => null,
                'duration' => 0,
                'remaining_days' => 0,
                'status_text' => 'No license found',
            ];
        }

        // Check for recent updates
        if ($this->shouldSyncLicense($license)) {
            $this->validateLicense($license->license_key, true);
            $license->refresh();
        }

        return $license->getUpdateStatus();
    }

    /**
     * Get license status for display
     */
    public function getLicenseStatus(): array
    {
        // LOCAL DEV MODE — set LICENSE_DEV_MODE=true in .env to use local license
        if (env('LICENSE_DEV_MODE', false)) {
            return [
                'valid'                   => true,
                'license_key'             => 'local-dev',
                'domain'                  => request()->getHost(),
                'expected_domain'         => request()->getHost(),
                'modules'                 => ['core', 'pos', 'landing_page'],
                'expiry_date'             => '2099-12-31',
                'status'                  => 'active',
                'landing_page_limit'      => 999,
                'landing_page_used'       => 0,
                'landing_page_remaining'  => 999,
                'last_synced'             => 'local',
                'last_synced_at'          => now()->toDateTimeString(),
                'needs_sync'              => false,
                'support_status'          => ['active' => true, 'status' => 'active', 'remaining_days' => 999],
                'update_status'           => ['active' => true, 'status' => 'active', 'remaining_days' => 999],
            ];
        }

        // Look for any license, not just active ones
        $license = License::first();
        
        if (!$license) {
            return [
                'valid' => false,
                'message' => 'No license found',
                'license_key' => 'Not set',
                'domain' => 'Not specified',
                'modules' => [],
                'expiry_date' => null,
                'status' => 'unknown',
                'landing_page_limit' => 0,
                'landing_page_used' => 0,
                'landing_page_remaining' => 0,
                'last_synced' => null,
                'needs_sync' => false,
                'support_status' => $this->getSupportStatus(),
                'update_status' => $this->getUpdateStatus(),
                'data' => null
            ];
        }

        // Check for recent updates (use TTL, not host drift)
        // Skip remote sync on localhost / local dev environments
        $isLocalDev = in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1']);
        if (!$isLocalDev && $this->shouldSyncLicense($license)) {
            $this->validateLicense($license->license_key, true);
            $license->refresh();
        }

        $canonicalDomain = $this->getCanonicalDomain();

        $status = [
            'valid' => $license->isActive(),
            'license_key' => substr($license->license_key, 0, 8) . '...',
            'domain' => $license->domain,
            'expected_domain' => $canonicalDomain,
            'modules' => $license->modules,
            'expiry_date' => $license->expiry_date?->format('Y-m-d'),
            'status' => $license->status,
            'landing_page_limit' => $license->landing_page_limit,
            'landing_page_used' => $license->getCurrentLandingPageCount(),
            'landing_page_remaining' => $license->getRemainingLandingPageQuota(),
            'last_synced' => $license->last_synced_at?->diffForHumans(),
            'last_synced_at' => $license->last_synced_at?->toDateTimeString(),
            'needs_sync' => $license->needsSync(),
            'support_status' => $license->getSupportStatus(),
            'update_status' => $license->getUpdateStatus(),
        ];

        // On localhost, always allow access (local development mode)
        if ($isLocalDev) {
            $status['valid'] = true;
            return $status;
        }

        // If current host doesn't match the licensed domain, block access without mutating status
        $currentDomainNormalized = $this->normalizeDomain(request()->getHost());
        if ($currentDomainNormalized !== 'localhost' && $currentDomainNormalized !== '127.0.0.1' && $canonicalDomain && $currentDomainNormalized && $canonicalDomain !== $currentDomainNormalized) {
            $status['valid'] = false;
            $status['message'] = "License is bound to {$canonicalDomain}. Accessed from {$currentDomainNormalized}.";
        }

        if (!$license->isActive()) {
            if ($license->isExpired()) {
                $status['message'] = 'License has expired';
            } elseif ($license->status === 'suspended') {
                $status['message'] = 'License is suspended';
            } else {
                $status['message'] = 'License is not active';
            }
        }

        return $status;
    }

    /**
     * Activate a new license
     */
    public function activateLicense(string $licenseKey): array
    {
        // Clear ALL license validation caches before activation
        $this->clearAllLicenseCaches();
        
        // Deactivate existing licenses
        License::where('status', 'active')->update(['status' => 'suspended']);
        
        // Validate new license
        $result = $this->validateLicense($licenseKey, true);
        
        if ($result['success']) {
            // Clear security violations on successful activation
            $this->clearSecurityViolations('License activated successfully');
            
            return [
                'success' => true,
                'message' => 'License activated successfully',
                'data' => $this->getLicenseStatus()
            ];
        }

        return $result;
    }

    /**
     * Revalidate current license
     */
    public function revalidateLicense(): array
    {
        $license = License::first();
        
        if (!$license) {
            return [
                'success' => false,
                'message' => 'No license found'
            ];
        }

        $result = $this->validateLicense($license->license_key, true);
        
        if ($result['success']) {
            // Clear security violations on successful revalidation
            $this->clearSecurityViolations('License revalidated successfully');
        }

        return $result;
    }

    /**
     * Clear security violations (called automatically on license activation/revalidation)
     */
    private function clearSecurityViolations(string $reason = 'License validation successful'): void
    {
        try {
            // Clear all security-related caches
            Cache::forget('tamper_attempts');
            Cache::forget('security_violations');
            Cache::forget('request_patterns');
            Cache::forget('suspicious_requests');
            
            // Also clear license validation caches to ensure fresh data
            $this->clearLicenseValidationCaches();
            
            // Log the security violation clear
            Log::info('Security violations cleared automatically', [
                'reason' => $reason,
                'license_key' => License::first()?->license_key,
                'domain' => request()->getHost(),
                'timestamp' => now()->toISOString()
            ]);
            
        } catch (Exception $e) {
            Log::error('Failed to clear security violations', [
                'error' => $e->getMessage(),
                'reason' => $reason
            ]);
        }
    }
}
