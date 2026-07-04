<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupHistory;
use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class BackupHistoryController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display backup history
     */
    public function index()
    {
        Gate::authorize('backup.history.view');

        $backups = BackupHistory::latest()
            ->with(['schedule', 'creator'])
            ->paginate(20);

        return view('admin.backup.history.index', compact('backups'));
    }

    /**
     * Download backup file
     */
    public function download($id)
    {
        Gate::authorize('backup.download');

        try {
            $downloadInfo = $this->backupService->downloadBackup($id);
            
            return Response::download(
                $downloadInfo['path'],
                $downloadInfo['name'],
                [
                    'Content-Type' => 'application/zip',
                ]
            )->deleteFileAfterSend($downloadInfo['delete_after']);
        } catch (\Exception $e) {
            return redirect()->route('admin.backup.history.index')
                ->with('error', 'Failed to download backup: ' . $e->getMessage());
        }
    }

    /**
     * Restore from backup
     */
    public function restore(Request $request, $id)
    {
        Gate::authorize('backup.restore');

        $validated = $request->validate([
            'type' => 'nullable|in:database,files,both',
            'confirm' => 'required|accepted',
        ]);

        try {
            $result = $this->backupService->restoreBackup($id, $validated['type'] ?? null);

            return redirect()->route('admin.backup.history.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            return redirect()->route('admin.backup.history.index')
                ->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete backup
     */
    public function destroy($id)
    {
        Gate::authorize('backup.history.delete');

        $backup = BackupHistory::findOrFail($id);

        try {
            // Delete file from storage
            if ($backup->disk_name === 'google' || $backup->storage_destination === 'google_drive') {
                // Delete from Google Drive if needed
            } else {
                $filePath = storage_path('app/backups/' . $backup->file_name);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $backup->delete();

            return redirect()->route('admin.backup.history.index')
                ->with('success', 'Backup deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.backup.history.index')
                ->with('error', 'Failed to delete backup: ' . $e->getMessage());
        }
    }
}

