<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupSetting;
use App\Models\BackupHistory;
use App\Services\BackupService;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class BackupController extends Controller
{
    protected $backupService;
    protected $googleDriveService;

    public function __construct(BackupService $backupService, GoogleDriveService $googleDriveService)
    {
        $this->backupService = $backupService;
        $this->googleDriveService = $googleDriveService;
    }

    /**
     * Show backup settings page
     */
    public function settings()
    {
        Gate::authorize('backup.settings.view');

        $settings = BackupSetting::all()->groupBy('category');
        
        return view('admin.backup.settings', compact('settings'));
    }

    /**
     * Update backup settings
     */
    public function updateSettings(Request $request)
    {
        Gate::authorize('backup.settings.update');

        // Debug: Log incoming request data
        Log::info('Backup settings update request:', [
            'all_data' => $request->all(),
            'has_storage_destination' => $request->has('storage_destination'),
            'storage_destination_value' => $request->input('storage_destination'),
            'retention_days' => $request->input('retention_days'),
            'retention_count' => $request->input('retention_count'),
        ]);

        $validated = $request->validate([
            'storage_destination' => 'required|in:local,google_drive,both',
            'backup_db_enabled' => 'boolean',
            'backup_files_enabled' => 'boolean',
            'retention_days' => 'required|integer|min:1|max:365',
            'retention_count' => 'required|integer|min:1|max:100',
            'notification_email' => 'nullable|email',
            'notification_on_success' => 'boolean',
            'notification_on_failure' => 'boolean',
            'google_drive_client_id' => 'nullable|string|max:255',
            'google_drive_client_secret' => 'nullable|string|max:255',
            'google_drive_folder_id' => 'nullable|string|max:255',
        ]);

        // Debug: Log validated data
        Log::info('Backup settings validated data:', $validated);

        // Update settings
        BackupSetting::set('storage_destination', $validated['storage_destination'], 'text', 'storage', 'Storage Destination');
        BackupSetting::set('backup_db_enabled', $validated['backup_db_enabled'] ?? false, 'boolean', 'backup', 'Enable Database Backup');
        BackupSetting::set('backup_files_enabled', $validated['backup_files_enabled'] ?? false, 'boolean', 'backup', 'Enable Files Backup');
        BackupSetting::set('retention_days', $validated['retention_days'], 'text', 'retention', 'Retention Days');
        BackupSetting::set('retention_count', $validated['retention_count'], 'text', 'retention', 'Retention Count');
        BackupSetting::set('notification_email', $validated['notification_email'] ?? '', 'text', 'notification', 'Notification Email');
        BackupSetting::set('notification_on_success', $validated['notification_on_success'] ?? false, 'boolean', 'notification', 'Notify on Success');
        BackupSetting::set('notification_on_failure', $validated['notification_on_failure'] ?? false, 'boolean', 'notification', 'Notify on Failure');

        // Google Drive credentials
        if ($request->has('google_drive_client_id')) {
            BackupSetting::set(
                'google_drive_client_id',
                $request->input('google_drive_client_id') ?? '',
                'encrypted',
                'google_drive',
                'Google Drive Client ID',
                'OAuth client ID from Google Cloud'
            );
        }

        if ($request->filled('google_drive_client_secret')) {
            BackupSetting::set(
                'google_drive_client_secret',
                $request->input('google_drive_client_secret'),
                'encrypted',
                'google_drive',
                'Google Drive Client Secret',
                'OAuth client secret from Google Cloud'
            );
        }

        if ($request->has('google_drive_folder_id')) {
            BackupSetting::set(
                'google_drive_folder_id',
                $request->input('google_drive_folder_id') ?? '',
                'text',
                'google_drive',
                'Google Drive Folder ID',
                'Optional folder ID to store backups'
            );
        }

        return redirect()->route('admin.backup.settings', ['tab' => 'settings'])
            ->with('success', 'Backup settings updated successfully.');
    }

    /**
     * Redirect to Google Drive OAuth
     */
    public function googleDriveConnect()
    {
        Gate::authorize('backup.google-drive.connect');

        $credentials = BackupSetting::getGoogleDriveCredentials();
        if (empty($credentials['client_id']) || empty($credentials['client_secret'])) {
            return redirect()->route('admin.backup.settings')
                ->with('error', 'Please enter your Google Drive Client ID and Client Secret before connecting.');
        }

        try {
            $authUrl = $this->googleDriveService->getAuthUrl();
            return redirect($authUrl);
        } catch (\Exception $e) {
            return redirect()->route('admin.backup.settings', ['tab' => 'settings'])
                ->with('error', 'Failed to connect to Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Handle Google Drive OAuth callback
     */
    public function googleDriveCallback(Request $request)
    {
        Gate::authorize('backup.google-drive.connect');

        $code = $request->get('code');
        
        if (!$code) {
            return redirect()->route('admin.backup.settings')
                ->with('error', 'Authorization failed. No code received.');
        }

        try {
            $tokens = $this->googleDriveService->authenticate($code);
            
            // Get client ID and secret from request (they should be stored separately)
            $clientId = $request->get('client_id') ?? BackupSetting::get('google_drive_client_id');
            $clientSecret = $request->get('client_secret') ?? BackupSetting::get('google_drive_client_secret');

            // Store credentials
            BackupSetting::setGoogleDriveCredentials([
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $tokens['refresh_token'],
            ]);

            BackupSetting::set('google_drive_enabled', true, 'boolean', 'google_drive', 'Google Drive Enabled');

            return redirect()->route('admin.backup.settings', ['tab' => 'settings'])
                ->with('success', 'Google Drive connected successfully!');
        } catch (\Exception $e) {
            Log::error('Google Drive OAuth error: ' . $e->getMessage());
            return redirect()->route('admin.backup.settings', ['tab' => 'settings'])
                ->with('error', 'Failed to connect Google Drive: ' . $e->getMessage());
        }
    }

    /**
     * Disconnect Google Drive
     */
    public function googleDriveDisconnect()
    {
        Gate::authorize('backup.google-drive.connect');

        BackupSetting::set('google_drive_enabled', false, 'boolean', 'google_drive', 'Google Drive Enabled');
        BackupSetting::set('google_drive_refresh_token', '', 'encrypted', 'google_drive', 'Google Drive Refresh Token');

        return redirect()->route('admin.backup.settings', ['tab' => 'settings'])
            ->with('success', 'Google Drive disconnected successfully.');
    }

    /**
     * Test Google Drive connection
     */
    public function googleDriveTest()
    {
        Gate::authorize('backup.google-drive.test');

        try {
            $result = $this->googleDriveService->testConnection();
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Run manual backup
     */
    public function runManualBackup(Request $request)
    {
        Gate::authorize('backup.run');

        $validated = $request->validate([
            'backup_type' => 'required|in:database,files,both',
            'storage_destination' => 'required|in:local,google_drive,both',
        ]);

        try {
            $backupHistory = $this->backupService->createBackup(
                $validated['backup_type'],
                $validated['storage_destination'],
                null, // No schedule ID for manual backup
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Backup started successfully.',
                'backup_id' => $backupHistory->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Manual backup failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}

