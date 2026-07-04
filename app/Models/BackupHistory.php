<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BackupHistory extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'backup_history';

    protected $fillable = [
        'schedule_id',
        'backup_type',
        'storage_destination',
        'disk_name',
        'file_path',
        'file_name',
        'file_size',
        'status',
        'error_message',
        'started_at',
        'completed_at',
        'duration_seconds',
        'is_manual',
        'created_by',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'file_size' => 'integer',
        'duration_seconds' => 'integer',
        'is_manual' => 'boolean',
    ];

    /**
     * Get the schedule that created this backup
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(BackupSchedule::class, 'schedule_id');
    }

    /**
     * Get the user who created this backup (if manual)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Scope: Only successful backups
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope: Only failed backups
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope: Only local backups
     */
    public function scopeLocal($query)
    {
        return $query->where('storage_destination', 'local')
            ->orWhere('storage_destination', 'both');
    }

    /**
     * Scope: Only Google Drive backups
     */
    public function scopeGoogleDrive($query)
    {
        return $query->where('storage_destination', 'google_drive')
            ->orWhere('storage_destination', 'both');
    }

    /**
     * Get human-readable file size
     */
    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Check if backup file exists and can be downloaded
     */
    public function canDownload(): bool
    {
        if ($this->status !== 'success') {
            return false;
        }

        if (!$this->file_path || !$this->file_name) {
            return false;
        }

        // Check if file exists on local disk
        if ($this->disk_name === 'local' || $this->storage_destination === 'local' || $this->storage_destination === 'both') {
            $candidatePaths = [];

            if ($this->file_path) {
                $candidatePaths[] = $this->file_path;
                // In case file_path is relative to storage/app
                $candidatePaths[] = storage_path('app/' . ltrim($this->file_path, '/'));
            }

            $candidatePaths[] = storage_path('app/backups/' . $this->file_name);

            foreach ($candidatePaths as $path) {
                if ($path && file_exists($path)) {
                    return true;
                }
            }

            return false;
        }

        // For Google Drive, we assume it exists if status is success
        return true;
    }

    /**
     * Check if backup can be restored
     */
    public function canRestore(): bool
    {
        return $this->canDownload() && in_array($this->backup_type, ['database', 'both']);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted(string $status = 'success', string $errorMessage = null): void
    {
        $this->update([
            'status' => $status,
            'error_message' => $errorMessage,
            'completed_at' => now(),
            'duration_seconds' => $this->started_at->diffInSeconds(now()),
        ]);
    }
}

