<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupSchedule;
use App\Jobs\CreateBackupJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BackupScheduleController extends Controller
{
    /**
     * Display a listing of backup schedules
     */
    public function index()
    {
        Gate::authorize('backup.schedules.view');

        $schedules = BackupSchedule::latest()->get();
        
        return view('admin.backup.schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new schedule
     */
    public function create()
    {
        Gate::authorize('backup.schedules.create');

        return view('admin.backup.schedules.create');
    }

    /**
     * Store a newly created schedule
     */
    public function store(Request $request)
    {
        Gate::authorize('backup.schedules.create');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'backup_type' => 'required|in:database,files,both',
            'storage_destination' => 'required|in:local,google_drive,both',
            'cron_expression' => 'required|string',
            'cron_description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        try {
            $schedule = BackupSchedule::create([
                'name' => $validated['name'],
                'backup_type' => $validated['backup_type'],
                'storage_destination' => $validated['storage_destination'],
                'cron_expression' => $validated['cron_expression'],
                'cron_description' => $validated['cron_description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'next_run_at' => (new BackupSchedule($validated))->calculateNextRun(),
            ]);

            return redirect()->route('admin.backup.schedules.index')
                ->with('success', 'Backup schedule created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create schedule: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a schedule
     */
    public function edit($id)
    {
        Gate::authorize('backup.schedules.edit');

        $schedule = BackupSchedule::findOrFail($id);
        
        return view('admin.backup.schedules.edit', compact('schedule'));
    }

    /**
     * Update a schedule
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('backup.schedules.edit');

        $schedule = BackupSchedule::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'backup_type' => 'required|in:database,files,both',
            'storage_destination' => 'required|in:local,google_drive,both',
            'cron_expression' => 'required|string',
            'cron_description' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        try {
            $schedule->update($validated);
            $schedule->updateNextRun();

            return redirect()->route('admin.backup.schedules.index')
                ->with('success', 'Backup schedule updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update schedule: ' . $e->getMessage());
        }
    }

    /**
     * Delete a schedule
     */
    public function destroy($id)
    {
        Gate::authorize('backup.schedules.delete');

        $schedule = BackupSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('admin.backup.schedules.index')
            ->with('success', 'Backup schedule deleted successfully.');
    }

    /**
     * Toggle schedule active status
     */
    public function toggle($id)
    {
        Gate::authorize('backup.schedules.edit');

        $schedule = BackupSchedule::findOrFail($id);
        $schedule->update([
            'is_active' => !$schedule->is_active,
        ]);

        if ($schedule->is_active) {
            $schedule->updateNextRun();
        }

        return redirect()->back()
            ->with('success', 'Schedule status updated.');
    }

    /**
     * Run schedule immediately
     */
    public function runNow($id)
    {
        Gate::authorize('backup.schedules.run');

        $schedule = BackupSchedule::findOrFail($id);

        try {
            CreateBackupJob::dispatch($schedule->id);

            return redirect()->back()
                ->with('success', 'Backup job queued successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to queue backup: ' . $e->getMessage());
        }
    }
}

