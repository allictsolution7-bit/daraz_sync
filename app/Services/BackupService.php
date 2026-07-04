<?php

namespace App\Services;

use App\Models\BackupSetting;
use App\Models\BackupHistory;
use App\Models\BackupSchedule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class BackupService
{
    protected $googleDriveService;

    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    /**
     * Create backup
     */
    public function createBackup(string $type, string $storage, ?int $scheduleId = null, ?int $userId = null): BackupHistory
    {
        $history = BackupHistory::create([
            'schedule_id' => $scheduleId,
            'backup_type' => $type,
            'storage_destination' => $storage,
            'status' => 'running',
            'started_at' => now(),
            'is_manual' => $scheduleId === null,
            'created_by' => $userId,
        ]);

        try {
            // Create backup using spatie/laravel-backup
            $backupPath = $this->executeBackup($type);

            // Determine which disks to use
            $disks = [];
            if ($storage === 'local' || $storage === 'both') {
                $disks[] = 'local';
            }
            if ($storage === 'google_drive' || $storage === 'both') {
                $disks[] = 'google';
            }

            $uploadedFiles = [];

            // Upload to each disk
            foreach ($disks as $disk) {
                if ($disk === 'google') {
                    // Upload to Google Drive
                    $fileName = basename($backupPath);
                    $uploadResult = $this->googleDriveService->uploadFile($backupPath, $fileName);
                    $uploadedFiles[] = [
                        'disk' => 'google',
                        'file_id' => $uploadResult['file_id'],
                        'file_name' => $uploadResult['file_name'],
                    ];
                } else {
                    // File already in local storage
                    $uploadedFiles[] = [
                        'disk' => 'local',
                        'file_path' => $backupPath,
                        'file_name' => basename($backupPath),
                    ];
                }
            }

            // Update history with file information
            $primaryFile = $uploadedFiles[0];
            $fileSize = file_exists($backupPath) ? filesize($backupPath) : null;

            $history->update([
                'disk_name' => $primaryFile['disk'],
                'file_path' => $primaryFile['file_path'] ?? null,
                'file_name' => $primaryFile['file_name'],
                'file_size' => $fileSize,
                'status' => 'success',
                'completed_at' => now(),
                'duration_seconds' => $history->started_at->diffInSeconds(now()),
            ]);

            // Update schedule if exists
            if ($scheduleId) {
                $schedule = BackupSchedule::find($scheduleId);
                if ($schedule) {
                    $schedule->markAsSuccess('Backup completed successfully');
                }
            }

            // Apply retention policy
            $this->deleteOldBackups();

            return $history;

        } catch (\Exception $e) {
            Log::error('Backup failed: ' . $e->getMessage(), [
                'history_id' => $history->id,
                'type' => $type,
                'storage' => $storage,
            ]);

            $history->markAsCompleted('failed', $e->getMessage());

            if ($scheduleId) {
                $schedule = BackupSchedule::find($scheduleId);
                if ($schedule) {
                    $schedule->markAsFailed($e->getMessage());
                }
            }

            throw $e;
        }
    }

    /**
     * Execute backup using spatie/laravel-backup
     */
    protected function executeBackup(string $type): string
    {
        // Build artisan command based on type
        $command = 'backup:run';
        $options = [];

        if ($type === 'database') {
            $options['--only-db'] = true;
        } elseif ($type === 'files') {
            $options['--only-files'] = true;
        }

        // Execute backup command
        // Note: Notifications are disabled in config/backup.php to prevent mail connection errors
        // We handle notifications via our own backup history system
        Artisan::call($command, $options);
        
        // Spatie stores backups on the 'local' disk which is storage_path('app')
        // Backup files are stored in a subdirectory named after the app name
        $localDiskRoot = storage_path('app');
        $appName = config('backup.backup.name', config('app.name', 'Laravel'));
        
        // Search in the app-named directory (e.g., storage/app/Reading/)
        $backupDir = $localDiskRoot . '/' . $appName;
        $backupFiles = [];
        
        if (is_dir($backupDir)) {
            $backupFiles = glob($backupDir . '/*.zip');
        }
        
        // Fallback: search in root and other common locations
        if (empty($backupFiles)) {
            $backupFiles = glob($localDiskRoot . '/*.zip');
        }
        
        if (empty($backupFiles)) {
            $backupFiles = glob($localDiskRoot . '/*/*.zip');
        }
        
        if (empty($backupFiles)) {
            // Get more details about what happened
            $output = Artisan::output();
            throw new Exception('Backup file was not created. Checked: ' . $backupDir . '. Artisan output: ' . $output);
        }

        // Sort by modification time, get newest
        usort($backupFiles, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });

        $latestBackup = $backupFiles[0];

        // Rename backup file to include app name and timestamp
        $pathInfo = pathinfo($latestBackup);
        $timestamp = now()->format('Y-m-d-H-i-s');
        $appName = Str::slug(config('app.name', 'backup'), '_');
        $typeSuffix = match ($type) {
            'database' => 'db',
            'files' => 'files',
            default => 'full',
        };
        $newFileName = $appName . '_' . $typeSuffix . '_' . $timestamp . '.' . ($pathInfo['extension'] ?? 'zip');
        $newFilePath = $pathInfo['dirname'] . '/' . $newFileName;

        if ($latestBackup !== $newFilePath) {
            rename($latestBackup, $newFilePath);
        }

        return $newFilePath;
    }

    /**
     * Download backup file
     */
    public function downloadBackup(int $backupId): array
    {
        $backup = BackupHistory::findOrFail($backupId);

        if (!$backup->canDownload()) {
            throw new Exception('Backup file is not available for download');
        }

        // If stored on Google Drive, download it first
        if ($backup->disk_name === 'google' || $backup->storage_destination === 'google_drive') {
            // We need to get file_id from file_path or store it separately
            // For now, assume file_path contains file_id for Google Drive
            $tempPath = storage_path('app/temp/' . $backup->file_name);
            if (!is_dir(dirname($tempPath))) {
                mkdir(dirname($tempPath), 0755, true);
            }

            $this->googleDriveService->downloadFile($backup->file_path, $tempPath);
            
            return [
                'path' => $tempPath,
                'name' => $backup->file_name,
                'delete_after' => true,
            ];
        }

        // Local file
        $candidatePaths = [];

        if ($backup->file_path) {
            $candidatePaths[] = $backup->file_path;
            $candidatePaths[] = storage_path('app/' . ltrim($backup->file_path, '/'));
        }

        $candidatePaths[] = storage_path('app/backups/' . $backup->file_name);
        $candidatePaths[] = storage_path('app/' . $backup->file_name);

        $filePath = null;
        foreach ($candidatePaths as $path) {
            if ($path && file_exists($path)) {
                $filePath = $path;
                break;
            }
        }

        if (!$filePath) {
            throw new Exception('Backup file not found');
        }

        return [
            'path' => $filePath,
            'name' => $backup->file_name,
            'delete_after' => false,
        ];
    }

    /**
     * Restore from backup
     */
    public function restoreBackup(int $backupId, string $type = null): array
    {
        $backup = BackupHistory::findOrFail($backupId);

        if (!$backup->canRestore()) {
            throw new Exception('This backup cannot be restored');
        }

        try {
            // Download backup if needed
            $downloadInfo = $this->downloadBackup($backupId);
            $backupFile = $downloadInfo['path'];

            // Extract backup
            $extractPath = storage_path('app/temp/restore_' . time());
            mkdir($extractPath, 0755, true);

            $zip = new \ZipArchive();
            if ($zip->open($backupFile) !== true) {
                throw new Exception('Failed to open backup file');
            }

            $zip->extractTo($extractPath);
            $zip->close();

            $restored = [];

            // Restore database if needed
            if (($type === 'database' || $type === 'both' || $backup->backup_type === 'database' || $backup->backup_type === 'both') && 
                file_exists($extractPath . '/db-dumps')) {
                $dbFiles = glob($extractPath . '/db-dumps/*.sql');
                if (!empty($dbFiles)) {
                    $this->restoreDatabase($dbFiles[0]);
                    $restored[] = 'database';
                }
            }

            // Restore files if needed
            if (($type === 'files' || $type === 'both' || $backup->backup_type === 'files' || $backup->backup_type === 'both') && 
                file_exists($extractPath . '/files')) {
                $this->restoreFiles($extractPath . '/files');
                $restored[] = 'files';
            }

            // Cleanup
            $this->deleteDirectory($extractPath);
            if ($downloadInfo['delete_after']) {
                unlink($backupFile);
            }

            return [
                'success' => true,
                'message' => 'Backup restored successfully',
                'restored' => $restored,
            ];

        } catch (\Exception $e) {
            Log::error('Restore failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Restore database from SQL file
     */
    protected function restoreDatabase(string $sqlFile): void
    {
        $config = config('database.connections.' . config('database.default'));
        
        $command = sprintf(
            'mysql -h %s -u %s -p%s %s < %s',
            escapeshellarg($config['host']),
            escapeshellarg($config['username']),
            escapeshellarg($config['password']),
            escapeshellarg($config['database']),
            escapeshellarg($sqlFile)
        );

        exec($command, $output, $returnCode);

        if ($returnCode !== 0) {
            throw new Exception('Database restore failed');
        }
    }

    /**
     * Restore files from backup
     */
    protected function restoreFiles(string $filesPath): void
    {
        // Copy files back to their original locations
        // This is a simplified version - adjust based on your backup structure
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($filesPath),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isFile()) {
                $relativePath = str_replace($filesPath . DIRECTORY_SEPARATOR, '', $item->getPathname());
                $targetPath = base_path($relativePath);
                
                $targetDir = dirname($targetPath);
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                copy($item->getPathname(), $targetPath);
            }
        }
    }

    /**
     * Delete old backups based on retention policy
     */
    public function deleteOldBackups(): int
    {
        $retention = BackupSetting::getRetentionSettings();
        $deleted = 0;

        // Delete by days
        $cutoffDate = now()->subDays($retention['days']);
        $oldBackups = BackupHistory::where('created_at', '<', $cutoffDate)
            ->where('status', 'success')
            ->get();

        foreach ($oldBackups as $backup) {
            $this->deleteBackupFile($backup);
            $backup->delete();
            $deleted++;
        }

        // Delete by count (keep only N most recent)
        // Get total count first
        $totalBackups = BackupHistory::where('status', 'success')->count();
        
        if ($totalBackups > $retention['count']) {
            // Get IDs of backups to keep (most recent N)
            $keepBackupIds = BackupHistory::where('status', 'success')
                ->orderBy('created_at', 'desc')
                ->limit($retention['count'])
                ->pluck('id')
                ->toArray();
            
            // Delete all successful backups except the ones we're keeping
            $recentBackups = BackupHistory::where('status', 'success')
                ->whereNotIn('id', $keepBackupIds)
                ->get();

            foreach ($recentBackups as $backup) {
                $this->deleteBackupFile($backup);
                $backup->delete();
                $deleted++;
            }
        }

        return $deleted;
    }

    /**
     * Delete backup file from storage
     */
    protected function deleteBackupFile(BackupHistory $backup): void
    {
        try {
            if ($backup->disk_name === 'google' || $backup->storage_destination === 'google_drive') {
                // Delete from Google Drive
                if ($backup->file_path) {
                    $this->googleDriveService->deleteFile($backup->file_path);
                }
            } else {
                // Delete local file
                $filePath = storage_path('app/backups/' . $backup->file_name);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to delete backup file: ' . $e->getMessage());
        }
    }

    /**
     * Delete directory recursively
     */
    protected function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}

