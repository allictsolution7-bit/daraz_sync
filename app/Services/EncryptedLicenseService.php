<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class EncryptedLicenseService
{
    private const ENCRYPTION_KEY = 'thikana_license_key_2025';
    private const MOTHER_PANEL_URL = 'https://uddoktaecommerce.com/api/licenses/encrypted-validate';
    
    /**
     * Encrypted license validation with obfuscated checks
     */
    public function validateLicenseEncrypted(string $licenseKey): array
    {
        // Obfuscated method names to prevent easy bypass
        $method1 = 'call' . '_' . 'mother' . '_' . 'panel';
        $method2 = 'verify' . '_' . 'response' . '_' . 'encrypted';
        
        try {
            $response = $this->$method1($licenseKey);
            
            if ($this->$method2($response)) {
                return $this->decryptResponse($response);
            }
            
            return ['success' => false, 'message' => 'License validation failed'];
            
        } catch (Exception $e) {
            Log::error('Encrypted license validation failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'License system error'];
        }
    }
    
    /**
     * Obfuscated mother panel call
     */
    private function call_mother_panel(string $licenseKey): array
    {
        $encryptedData = $this->encryptRequestData([
            'license_key' => $licenseKey,
            'domain' => request()->getHost(),
            'timestamp' => time(),
            'checksum' => $this->generateChecksum($licenseKey)
        ]);
        
        $response = Http::timeout(5)->post(self::MOTHER_PANEL_URL, [
            'data' => $encryptedData,
            'signature' => $this->generateRequestSignature($encryptedData)
        ]);
        
        return $response->json();
    }
    
    /**
     * Obfuscated response verification
     */
    private function verify_response_encrypted(array $response): bool
    {
        if (!isset($response['data']) || !isset($response['signature'])) {
            return false;
        }
        
        // Verify signature
        $expectedSignature = $this->generateResponseSignature($response['data']);
        if (!hash_equals($expectedSignature, $response['signature'])) {
            return false;
        }
        
        // Verify timestamp (prevent replay attacks)
        $decryptedData = $this->decryptResponse($response);
        if (isset($decryptedData['timestamp'])) {
            $timeDiff = time() - $decryptedData['timestamp'];
            if ($timeDiff > 300) { // 5 minutes max
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Encrypt request data
     */
    private function encryptRequestData(array $data): string
    {
        $jsonData = json_encode($data);
        $encrypted = openssl_encrypt($jsonData, 'AES-256-CBC', self::ENCRYPTION_KEY, 0, $this->getIV());
        return base64_encode($encrypted);
    }
    
    /**
     * Decrypt response data
     */
    private function decryptResponse(array $response): array
    {
        $encryptedData = base64_decode($response['data']);
        $decrypted = openssl_decrypt($encryptedData, 'AES-256-CBC', self::ENCRYPTION_KEY, 0, $this->getIV());
        return json_decode($decrypted, true) ?? [];
    }
    
    /**
     * Generate checksum for integrity
     */
    private function generateChecksum(string $licenseKey): string
    {
        return hash('sha256', $licenseKey . self::ENCRYPTION_KEY . time());
    }
    
    /**
     * Generate request signature
     */
    private function generateRequestSignature(string $data): string
    {
        return hash_hmac('sha256', $data, self::ENCRYPTION_KEY);
    }
    
    /**
     * Generate response signature
     */
    private function generateResponseSignature(string $data): string
    {
        return hash_hmac('sha256', $data, self::ENCRYPTION_KEY);
    }
    
    /**
     * Get initialization vector
     */
    private function getIV(): string
    {
        return substr(hash('sha256', self::ENCRYPTION_KEY), 0, 16);
    }
}
