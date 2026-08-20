<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupSetting;
use App\Services\GoogleSheetSyncService;
use Illuminate\Http\Request;

class GoogleSheetSyncController extends Controller
{
    /**
     * Display Google Sheet Sync settings page
     */
    public function index()
    {
        $sheetSettings = [
            'spreadsheet_id' => BackupSetting::get('google_sheet_spreadsheet_id', ''),
            'tab_name' => BackupSetting::get('google_sheet_tab_name', 'Sheet1') ?: 'Sheet1',
            'client_email' => BackupSetting::get('google_sheet_client_email', ''),
            'private_key' => BackupSetting::get('google_sheet_private_key', ''),
            'auto_sync_enabled' => (bool) BackupSetting::get('google_sheet_auto_sync_enabled', false),
            'sync_frequency' => BackupSetting::get('google_sheet_sync_frequency', '15'),
            'last_synced_at' => BackupSetting::get('google_sheet_last_synced_at', 'Never'),
            'last_sync_result' => BackupSetting::get('google_sheet_last_sync_result', null),
        ];

        return view('admin.settings.google_sheets', compact('sheetSettings'));
    }

    /**
     * Save Google Sheet settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'spreadsheet_id' => 'nullable|string',
            'tab_name' => 'nullable|string',
            'service_account_json_file' => 'nullable|file|mimes:json,txt',
            'service_account_json_text' => 'nullable|string',
            'client_email' => 'nullable|string',
            'private_key' => 'nullable|string',
            'sync_frequency' => 'nullable|integer',
        ]);

        // If JSON file was uploaded, extract credentials
        if ($request->hasFile('service_account_json_file')) {
            $jsonContent = file_get_contents($request->file('service_account_json_file')->getRealPath());
            $decoded = json_decode($jsonContent, true);
            if (isset($decoded['client_email'], $decoded['private_key'])) {
                BackupSetting::set('google_sheet_client_email', $decoded['client_email']);
                BackupSetting::set('google_sheet_private_key', $decoded['private_key'], 'encrypted');
            }
        } elseif ($request->filled('service_account_json_text')) {
            $decoded = json_decode($request->service_account_json_text, true);
            if (isset($decoded['client_email'], $decoded['private_key'])) {
                BackupSetting::set('google_sheet_client_email', $decoded['client_email']);
                BackupSetting::set('google_sheet_private_key', $decoded['private_key'], 'encrypted');
            }
        } else {
            if ($request->has('client_email')) {
                BackupSetting::set('google_sheet_client_email', trim($request->client_email));
            }
            if ($request->filled('private_key')) {
                BackupSetting::set('google_sheet_private_key', trim($request->private_key), 'encrypted');
            }
        }

        // Clean Spreadsheet ID if user entered full URL
        $sheetIdInput = trim($request->spreadsheet_id ?? '');
        if (preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $sheetIdInput, $matches)) {
            $sheetIdInput = $matches[1];
        }

        BackupSetting::set('google_sheet_spreadsheet_id', $sheetIdInput);
        BackupSetting::set('google_sheet_tab_name', $request->tab_name ?: 'Sheet1');
        BackupSetting::set('google_sheet_auto_sync_enabled', $request->has('auto_sync_enabled'));
        BackupSetting::set('google_sheet_sync_frequency', $request->sync_frequency ?: '15');

        flash('Google Sheet synchronization settings updated successfully!');
        return redirect()->back();
    }

    /**
     * Test Google Sheets API connection
     */
    public function testConnection(Request $request, GoogleSheetSyncService $syncService)
    {
        try {
            // If user tests without saving first, temporarily set sheet ID / tab if provided
            $spreadsheetId = $request->input('spreadsheet_id');
            if (!empty($spreadsheetId)) {
                if (preg_match('/\/d\/([a-zA-Z0-9-_]+)/', $spreadsheetId, $matches)) {
                    $spreadsheetId = $matches[1];
                }
                $syncService->setSpreadsheetId($spreadsheetId);
            }
            if ($request->filled('tab_name')) {
                $syncService->setSheetTab($request->tab_name);
            }

            $result = $syncService->testConnection();
            return response()->json([
                'success' => true,
                'message' => 'Connection verified successfully! Header row matched.',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Manual 1-click sync trigger from admin
     */
    public function triggerSync(GoogleSheetSyncService $syncService)
    {
        try {
            $result = $syncService->pullAndSyncFromSheet();
            
            BackupSetting::set('google_sheet_last_synced_at', now()->format('Y-m-d H:i:s'));
            BackupSetting::set('google_sheet_last_sync_result', $result['message']);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Manual 1-click push all software products to Google Sheet
     */
    public function pushToSheet(GoogleSheetSyncService $syncService)
    {
        try {
            $result = $syncService->pushAllProductsToSheet();
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Push failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
