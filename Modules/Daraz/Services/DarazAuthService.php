<?php

namespace Modules\Daraz\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Daraz\Models\DarazStore;
use Modules\Daraz\Models\DarazSyncLog;

class DarazAuthService
{
    /**
     * Generate the authorization URL for OAuth.
     */
    public function getAuthorizationUrl(DarazStore $store, string $redirectUri): string
    {
        $params = [
            'response_type' => 'code',
            'client_id' => $store->app_key,
            'redirect_uri' => $redirectUri,
            'force_auth' => 'true',
        ];

        return $store->getAuthUrl() . '?' . http_build_query($params);
    }

    /**
     * Exchange authorization code for access token.
     */
    public function exchangeCodeForToken(DarazStore $store, string $code): array
    {
        try {
            $action = '/auth/token/create';
            $url = rtrim($store->getApiBaseUrl(), '/') . $action;

            $params = [
                'app_key' => $store->app_key,
                'timestamp' => (string) (int) (microtime(true) * 1000),
                'sign_method' => 'sha256',
                'code' => $code,
            ];

            // Sort and sign
            ksort($params);
            $stringToSign = $action;
            foreach ($params as $key => $value) {
                $stringToSign .= $key . $value;
            }
            $params['sign'] = strtoupper(hash_hmac('sha256', $stringToSign, $store->app_secret));

            $response = Http::timeout(30)->asForm()->post($url, $params);
            $data = $response->json();

            if ($response->failed() || (isset($data['code']) && $data['code'] !== '0')) {
                Log::error('Daraz Auth Error: Code exchange failed', [
                    'store_id' => $store->id,
                    'response' => $data,
                ]);

                return [
                    'success' => false,
                    'error' => $data['message'] ?? 'Failed to exchange code for token',
                ];
            }

            // Save tokens
            $store->update([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'token_expires_at' => now()->addSeconds($data['expires_in'] ?? 604800), // Default 7 days
            ]);

            Log::info('Daraz Auth: Successfully connected store', ['store_id' => $store->id]);

            return [
                'success' => true,
                'data' => $data,
            ];

        } catch (\Exception $e) {
            Log::error('Daraz Auth Exception', [
                'store_id' => $store->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Refresh the access token.
     */
    public function refreshToken(DarazStore $store): array
    {
        if (!$store->refresh_token) {
            return [
                'success' => false,
                'error' => 'No refresh token available. Please re-authorize.',
            ];
        }

        try {
            $action = '/auth/token/refresh';
            $url = rtrim($store->getApiBaseUrl(), '/') . $action;

            $params = [
                'app_key' => $store->app_key,
                'timestamp' => (string) (int) (microtime(true) * 1000),
                'sign_method' => 'sha256',
                'refresh_token' => $store->refresh_token,
            ];

            // Sort and sign
            ksort($params);
            $stringToSign = $action;
            foreach ($params as $key => $value) {
                $stringToSign .= $key . $value;
            }
            $params['sign'] = strtoupper(hash_hmac('sha256', $stringToSign, $store->app_secret));

            $response = Http::timeout(30)->asForm()->post($url, $params);
            $data = $response->json();

            if ($response->failed() || (isset($data['code']) && $data['code'] !== '0')) {
                Log::error('Daraz Auth Error: Token refresh failed', [
                    'store_id' => $store->id,
                    'response' => $data,
                ]);

                DarazSyncLog::logTokenRefresh($store, 'failed', $data['message'] ?? 'Token refresh failed');

                return [
                    'success' => false,
                    'error' => $data['message'] ?? 'Failed to refresh token',
                ];
            }

            // Save new tokens
            $store->update([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'token_expires_at' => now()->addSeconds($data['expires_in'] ?? 604800),
            ]);

            DarazSyncLog::logTokenRefresh($store, 'success');

            Log::info('Daraz Auth: Successfully refreshed token', ['store_id' => $store->id]);

            return [
                'success' => true,
                'data' => $data,
            ];

        } catch (\Exception $e) {
            Log::error('Daraz Auth Exception during refresh', [
                'store_id' => $store->id,
                'error' => $e->getMessage(),
            ]);

            DarazSyncLog::logTokenRefresh($store, 'failed', $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Ensure the store has a valid token, refreshing if needed.
     */
    public function ensureValidToken(DarazStore $store): bool
    {
        if (!$store->access_token) {
            return false;
        }

        if ($store->isTokenExpiringSoon()) {
            $result = $this->refreshToken($store);
            return $result['success'];
        }

        return true;
    }

    /**
     * Disconnect store (clear tokens).
     */
    public function disconnect(DarazStore $store): void
    {
        $store->update([
            'access_token' => null,
            'refresh_token' => null,
            'token_expires_at' => null,
        ]);

        Log::info('Daraz Auth: Disconnected store', ['store_id' => $store->id]);
    }
}
