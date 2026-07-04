<?php

namespace App\Services;

use App\Models\BackupSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class GoogleDriveService
{
    private $clientId;
    private $clientSecret;
    private $refreshToken;
    private $folderId;
    private $accessToken = null;

    public function __construct()
    {
        $credentials = BackupSetting::getGoogleDriveCredentials();
        $this->clientId = $credentials['client_id'] ?? '';
        $this->clientSecret = $credentials['client_secret'] ?? '';
        $this->refreshToken = $credentials['refresh_token'] ?? '';
        $this->folderId = $credentials['folder_id'] ?? null;
    }

    /**
     * Get OAuth authorization URL
     */
    public function getAuthUrl(): string
    {
        $redirectUri = route('admin.backup.google-drive.callback');
        
        $params = [
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/drive.file',
            'access_type' => 'offline',
            'prompt' => 'consent',
        ];

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    /**
     * Exchange authorization code for tokens
     */
    public function authenticate(string $code): array
    {
        $redirectUri = route('admin.backup.google-drive.callback');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUri,
        ]);

        if ($response->failed()) {
            throw new Exception('Failed to authenticate with Google Drive: ' . $response->body());
        }

        $data = $response->json();

        return [
            'access_token' => $data['access_token'] ?? null,
            'refresh_token' => $data['refresh_token'] ?? $this->refreshToken, // Keep existing if not provided
            'expires_in' => $data['expires_in'] ?? 3600,
        ];
    }

    /**
     * Refresh access token
     */
    public function refreshToken(): string
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $this->refreshToken,
            'grant_type' => 'refresh_token',
        ]);

        if ($response->failed()) {
            throw new Exception('Failed to refresh Google Drive token: ' . $response->body());
        }

        $data = $response->json();
        $this->accessToken = $data['access_token'];

        return $this->accessToken;
    }

    /**
     * Get access token (refresh if needed)
     */
    public function getAccessToken(): string
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        return $this->refreshToken();
    }

    /**
     * Check if Google Drive is connected
     */
    public function isConnected(): bool
    {
        if (empty($this->clientId) || empty($this->clientSecret) || empty($this->refreshToken)) {
            return false;
        }

        try {
            $this->refreshToken();
            return true;
        } catch (\Exception $e) {
            Log::error('Google Drive connection check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Upload file to Google Drive
     */
    public function uploadFile(string $localPath, string $remoteName): array
    {
        if (!file_exists($localPath)) {
            throw new Exception("Local file not found: {$localPath}");
        }

        $accessToken = $this->getAccessToken();
        $mimeType = mime_content_type($localPath) ?: 'application/zip';
        $fileContents = file_get_contents($localPath);

        // Prepare metadata
        $metadata = [
            'name' => $remoteName,
        ];

        // Add folder ID if specified
        if ($this->folderId) {
            $metadata['parents'] = [$this->folderId];
        }

        $boundary = 'foo_' . uniqid();
        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
        $body .= json_encode($metadata) . "\r\n";
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: {$mimeType}\r\n\r\n";
        $body .= $fileContents . "\r\n";
        $body .= "--{$boundary}--";

        $uploadUrl = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart';

        $response = Http::withToken($accessToken)
            ->withHeaders([
                'Content-Type' => "multipart/related; boundary={$boundary}",
            ])
            ->withBody($body, "multipart/related; boundary={$boundary}")
            ->post($uploadUrl);

        if ($response->failed()) {
            // Try refreshing token once
            $this->accessToken = null;
            $accessToken = $this->refreshToken();

            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Content-Type' => "multipart/related; boundary={$boundary}",
                ])
                ->withBody($body, "multipart/related; boundary={$boundary}")
                ->post($uploadUrl);

            if ($response->failed()) {
                throw new Exception('Failed to upload to Google Drive: ' . $response->body());
            }
        }

        $data = $response->json();

        return [
            'file_id' => $data['id'] ?? null,
            'file_name' => $data['name'] ?? $remoteName,
            'web_view_link' => $data['webViewLink'] ?? null,
        ];
    }

    /**
     * Download file from Google Drive
     */
    public function downloadFile(string $fileId, string $localPath): bool
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->get("https://www.googleapis.com/drive/v3/files/{$fileId}?alt=media");

        if ($response->failed()) {
            throw new Exception('Failed to download from Google Drive: ' . $response->body());
        }

        file_put_contents($localPath, $response->body());

        return file_exists($localPath);
    }

    /**
     * List files in Google Drive folder
     */
    public function listFiles(string $folderId = null): array
    {
        $accessToken = $this->getAccessToken();
        $folderId = $folderId ?? $this->folderId;

        $query = "mimeType != 'application/vnd.google-apps.folder'";
        if ($folderId) {
            $query .= " and '{$folderId}' in parents";
        }

        $response = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/drive/v3/files', [
                'q' => $query,
                'fields' => 'files(id, name, size, createdTime, modifiedTime)',
                'orderBy' => 'createdTime desc',
            ]);

        if ($response->failed()) {
            throw new Exception('Failed to list Google Drive files: ' . $response->body());
        }

        return $response->json('files', []);
    }

    /**
     * Delete file from Google Drive
     */
    public function deleteFile(string $fileId): bool
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)
            ->delete("https://www.googleapis.com/drive/v3/files/{$fileId}");

        return $response->successful();
    }

    /**
     * Test connection
     */
    public function testConnection(): array
    {
        try {
            if (!$this->isConnected()) {
                return [
                    'success' => false,
                    'message' => 'Google Drive is not connected. Please connect your account.',
                ];
            }

            // Try to list files (read operation)
            $this->listFiles();

            return [
                'success' => true,
                'message' => 'Google Drive connection is working correctly.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage(),
            ];
        }
    }
}

