@extends('layouts.master')

@section('title', 'Edit Backup Schedule')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #schedules-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        min-height: 100vh;
    }

    /* ── ALERTS ──────────────────────────────────── */
    .alert-premium {
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex; align-items: flex-start; gap: 10px;
        position: relative;
    }
    .alert-err-p { background: linear-gradient(135deg,#fef2f2,#fee2e2); border: 1px solid #fca5a5; color: #b91c1c; }
    .alert-premium .close-btn {
        position: absolute; top: 12px; right: 14px;
        background: none; border: none; cursor: pointer;
        color: inherit; opacity: 0.6; font-size: 1rem;
        padding: 0; line-height: 1;
    }

    /* ── HEADER BANNER ── */
    .hdr-panel {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .hdr-title h4 { font-size: 1.3rem; font-weight: 800; color: #0f172a; margin: 0 0 4px; }
    .hdr-title p { font-size: 0.85rem; color: #64748b; margin: 0; }

    .btn-hdr {
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 0.825rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        background: #fff; color: #475569; border: 1.5px solid #e2e8f0;
    }
    .btn-hdr:hover { background: #ede9fe; border-color: #a5b4fc; color: #4338ca; }

    /* ── FORM WRAP ── */
    .module-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 22px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .module-body { padding: 24px; }

    .section-band {
        padding: 16px 22px;
        display: flex;
        align-items: center;
        gap: 13px;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
    }
    .section-band .band-icon {
        width: 40px; height: 40px;
        border-radius: 11px;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; color: #fff;
        flex-shrink: 0;
        backdrop-filter: blur(4px);
    }
    .section-band h2 { font-size: 1rem; font-weight: 800; color: #fff; margin: 0 0 2px; line-height: 1; }
    .section-band span { font-size: 0.78rem; color: rgba(255,255,255,0.72); font-weight: 500; }

    /* ── FIELDS ── */
    .field-label {
        font-size: 0.76rem; font-weight: 700;
        color: #475569; text-transform: uppercase;
        letter-spacing: 0.04em; margin-bottom: 6px; display: block;
    }
    .field-label .req { color: #ef4444; }
    .field-input, .field-select {
        width: 100%; padding: 10px 13px;
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        font-size: 0.875rem; color: #0f172a;
        background: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
        transition: all 0.2s ease; outline: none;
    }
    .field-input:focus, .field-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    .field-input.is-invalid, .field-select.is-invalid { border-color: #ef4444; }
    .invalid-feedback { font-size: 0.78rem; color: #dc2626; margin-top: 4px; display: block; }
    .field-hint { font-size: 0.76rem; color: #94a3b8; margin-top: 5px; line-height: 1.5; }

    .option-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 768px) { .option-grid-2 { grid-template-columns: 1fr; } }

    /* ── TOGGLE ROW ── */
    .toggle-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }
    .toggle-row .tl { font-size: 0.92rem; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
    .toggle-row .ts { font-size: 0.8rem; color: #64748b; line-height: 1.5; }

    .toggle-wrap { display: flex; align-items: center; gap: 9px; flex-shrink: 0; }
    .premium-toggle {
        appearance: none; -webkit-appearance: none;
        width: 44px; height: 23px;
        border-radius: 99px; background: #cbd5e1;
        cursor: pointer; position: relative;
        transition: background 0.22s ease; flex-shrink: 0;
        border: none; outline: none;
    }
    .premium-toggle::after {
        content: ''; position: absolute;
        top: 2px; left: 2px;
        width: 19px; height: 19px;
        background: #fff; border-radius: 50%;
        transition: left 0.22s ease;
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }
    .premium-toggle:checked { background: #6366f1; }
    .premium-toggle:checked::after { left: 23px; }
    .toggle-wrap label { font-size: 0.82rem; font-weight: 600; color: #334155; cursor: pointer; white-space: nowrap; }

    /* ── ACTION BUTTONS ── */
    .btn-action {
        padding: 11px 24px; border-radius: 10px;
        font-size: 0.875rem; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; transition: all 0.2s ease;
    }
    .btn-action.indigo { background: linear-gradient(135deg,#4338ca,#6366f1); color: #fff; box-shadow: 0 3px 10px rgba(99,102,241,0.3); }
    .btn-action.indigo:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(99,102,241,0.4); color: #fff; }
    .btn-action.ghost { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-action.ghost:hover { background: #e2e8f0; color: #0f172a; }
</style>
@endsection

@section('content')
<div id="schedules-page" class="container-fluid px-4 py-4">

    {{-- Error summary if any --}}
    @if($errors->any())
        <div class="alert-premium alert-err-p">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Please resolve validation issues:</strong>
                <ul style="margin:6px 0 0;padding-left:16px;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- Header Banner --}}
    <div class="hdr-panel">
        <div class="hdr-title">
            <h4>Edit Backup Schedule</h4>
            <p>Update settings and intervals for this scheduled automation profile</p>
        </div>
        <a href="{{ route('admin.backup.schedules.index') }}" class="btn-hdr">
            <i class="fas fa-arrow-left"></i> Back to Schedules
        </a>
    </div>

    {{-- Form wrap --}}
    <div class="module-wrap">
        <div class="section-band">
            <div class="band-icon"><i class="fas fa-calendar-alt"></i></div>
            <div>
                <h2>Edit Schedule Parameters</h2>
                <span>Modify details for the automated schedule</span>
            </div>
        </div>
        <div class="module-body">
            <form action="{{ route('admin.backup.schedules.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="option-grid-2" style="margin-bottom: 20px;">
                    <div>
                        <label class="field-label">Schedule Name <span class="req">*</span></label>
                        <input type="text" name="name" class="field-input @error('name') is-invalid @enderror" 
                            value="{{ old('name', $schedule->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="field-hint">A descriptive name for this automated schedule</div>
                    </div>

                    <div>
                        <label class="field-label" style="opacity: 0;">Status Offset</label>
                        <div class="toggle-row" style="margin-bottom:0;">
                            <div>
                                <div class="tl">Active State</div>
                                <div class="ts">Allow tasks to trigger on next cron execution</div>
                            </div>
                            <div class="toggle-wrap">
                                <input class="premium-toggle" type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
                                <label for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="option-grid-2" style="margin-bottom: 20px;">
                    <div>
                        <label class="field-label">Backup Type <span class="req">*</span></label>
                        <select name="backup_type" class="field-select @error('backup_type') is-invalid @enderror" required>
                            <option value="database" {{ old('backup_type', $schedule->backup_type) === 'database' ? 'selected' : '' }}>Database Only</option>
                            <option value="files" {{ old('backup_type', $schedule->backup_type) === 'files' ? 'selected' : '' }}>Files Only</option>
                            <option value="both" {{ old('backup_type', $schedule->backup_type) === 'both' ? 'selected' : '' }}>Both (Database + Files)</option>
                        </select>
                        @error('backup_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="field-hint">Choose files, database dump, or both targets</div>
                    </div>

                    <div>
                        <label class="field-label">Storage Destination <span class="req">*</span></label>
                        <select name="storage_destination" class="field-select @error('storage_destination') is-invalid @enderror" required>
                            <option value="local" {{ old('storage_destination', $schedule->storage_destination) === 'local' ? 'selected' : '' }}>Local Storage</option>
                            <option value="google_drive" {{ old('storage_destination', $schedule->storage_destination) === 'google_drive' ? 'selected' : '' }}>Google Drive</option>
                            <option value="both" {{ old('storage_destination', $schedule->storage_destination) === 'both' ? 'selected' : '' }}>Both (Local + Google Drive)</option>
                        </select>
                        @error('storage_destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="field-hint">Target destination where backups will be stored</div>
                    </div>
                </div>

                <div class="option-grid-2" style="margin-bottom: 24px;">
                    <div>
                        <label class="field-label">Cron Expression <span class="req">*</span></label>
                        <input type="text" name="cron_expression" class="field-input @error('cron_expression') is-invalid @enderror" 
                            value="{{ old('cron_expression', $schedule->cron_expression) }}" required>
                        @error('cron_expression')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="field-hint">
                            Format: <code>minute hour day month weekday</code><br>
                            Examples: <code>0 2 * * *</code> (Daily at 2 AM), <code>0 0 * * 0</code> (Weekly on Sunday)
                        </div>
                    </div>

                    <div>
                        <label class="field-label">Cron Description</label>
                        <input type="text" name="cron_description" class="field-input" 
                            value="{{ old('cron_description', $schedule->cron_description) }}">
                        <div class="field-hint">A human-readable label explaining when this runs</div>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:12px;margin-top:20px;">
                    <button type="submit" class="btn-action indigo">
                        <i class="fas fa-save"></i> Update Schedule
                    </button>
                    <a href="{{ route('admin.backup.schedules.index') }}" class="btn-action ghost">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection
