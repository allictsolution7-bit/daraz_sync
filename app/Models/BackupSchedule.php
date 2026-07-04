<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Dragonmantank\CronExpression\CronExpression;

class BackupSchedule extends Model
{
    protected $fillable = [
        'name',
        'is_active',
        'backup_type',
        'storage_destination',
        'cron_expression',
        'cron_description',
        'last_run_at',
        'next_run_at',
        'run_count',
        'success_count',
        'failure_count',
        'last_status',
        'last_message',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
        'run_count' => 'integer',
        'success_count' => 'integer',
        'failure_count' => 'integer',
    ];

    /**
     * Get backup history for this schedule
     */
    public function history(): HasMany
    {
        return $this->hasMany(BackupHistory::class, 'schedule_id');
    }

    /**
     * Scope: Only active schedules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Schedules due to run
     */
    public function scopeDue($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('next_run_at')
                  ->orWhere('next_run_at', '<=', now());
            });
    }

    /**
     * Check if schedule is due to run
     */
    public function isDue(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if (!$this->next_run_at) {
            return true;
        }

        return $this->next_run_at->isPast();
    }

    /**
     * Calculate next run time from cron expression
     */
    public function calculateNextRun(): ?\DateTime
    {
        try {
            $cron = CronExpression::factory($this->cron_expression);
            return $cron->getNextRunDate(now());
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get human-readable cron description
     */
    public function getCronDescriptionAttribute(): ?string
    {
        if ($this->attributes['cron_description']) {
            return $this->attributes['cron_description'];
        }

        // Try to generate description from cron expression
        try {
            $cron = CronExpression::factory($this->cron_expression);
            return $cron->getExpression();
        } catch (\Exception $e) {
            return $this->cron_expression;
        }
    }

    /**
     * Update next run time
     */
    public function updateNextRun(): void
    {
        $this->next_run_at = $this->calculateNextRun();
        $this->save();
    }

    /**
     * Mark as running
     */
    public function markAsRunning(): void
    {
        $this->update([
            'last_status' => 'running',
            'last_run_at' => now(),
        ]);
    }

    /**
     * Mark as success
     */
    public function markAsSuccess(string $message = null): void
    {
        $this->increment('success_count');
        $this->update([
            'last_status' => 'success',
            'last_message' => $message,
            'last_run_at' => now(),
            'next_run_at' => $this->calculateNextRun(),
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(string $message): void
    {
        $this->increment('failure_count');
        $this->update([
            'last_status' => 'failed',
            'last_message' => $message,
            'last_run_at' => now(),
            'next_run_at' => $this->calculateNextRun(),
        ]);
    }
}

