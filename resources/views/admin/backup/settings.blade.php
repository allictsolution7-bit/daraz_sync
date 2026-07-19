@extends('layouts.master')

@section('title', 'Backup Settings')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #backup-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        min-height: 100vh;
    }

    /* ── TAB NAV ─────────────────────────────────── */
    .nav-tabs-bp {
        background: #ffffff;
        padding: 6px;
        border-radius: 16px;
        gap: 6px;
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        display: flex;
    }
    .nav-tabs-bp .nav-item { flex: 1; }
    .nav-tabs-bp .nav-link {
        width: 100%;
        border-radius: 12px;
        color: #64748b;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 12px 20px;
        text-align: center;
        transition: all 0.22s ease;
        border: none !important;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .nav-tabs-bp .nav-link:hover {
        color: #1e293b;
        background: #f8fafc;
    }
    .nav-tabs-bp .nav-link.active {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(67,56,202,0.35);
    }
    .nav-tabs-bp .tab-badge {
        background: rgba(255,255,255,0.2);
        border-radius: 99px;
        padding: 2px 8px;
        font-size: 0.7rem;
        font-weight: 800;
    }
    .nav-tabs-bp .nav-link:not(.active) .tab-badge {
        background: #f1f5f9;
        color: #64748b;
    }

    /* ── MODULE WRAP ─────────────────────────────── */
    .module-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 22px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: box-shadow 0.2s ease;
    }
    .module-wrap:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.07); }
    .module-body { padding: 24px; }

    /* ── SECTION BAND ─────────────────────────────── */
    .section-band {
        padding: 16px 22px;
        display: flex;
        align-items: center;
        gap: 13px;
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
    .band-indigo { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%); }
    .band-green  { background: linear-gradient(135deg, #047857, #10b981); }
    .band-amber  { background: linear-gradient(135deg, #b45309, #f59e0b); }
    .band-blue   { background: linear-gradient(135deg, #1d4ed8, #3b82f6); }
    .band-cyan   { background: linear-gradient(135deg, #0369a1, #0ea5e9); }
    .band-purple { background: linear-gradient(135deg, #6d28d9, #8b5cf6); }

    /* ── TOGGLE ROW ──────────────────────────────── */
    .toggle-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 14px;
    }
    .toggle-row:last-child { margin-bottom: 0; }
    .toggle-row .tl { font-size: 0.92rem; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
    .toggle-row .ts { font-size: 0.8rem; color: #64748b; line-height: 1.5; }

    /* ── CUSTOM TOGGLE ───────────────────────────── */
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

    /* ── FIELD ELEMENTS ──────────────────────────── */
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
    .field-input.is-invalid { border-color: #ef4444; }
    .invalid-feedback { font-size: 0.78rem; color: #dc2626; margin-top: 4px; }
    .field-hint { font-size: 0.76rem; color: #94a3b8; margin-top: 5px; line-height: 1.5; }

    /* ── OPTION GRID ─────────────────────────────── */
    .option-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    @media (max-width: 768px) { .option-grid-2 { grid-template-columns: 1fr; } }

    /* ── RADIO PILLS ─────────────────────────────── */
    .radio-pill-group { display: flex; gap: 10px; flex-wrap: wrap; }
    .radio-pill {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 9px 18px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.85rem; font-weight: 600; color: #475569;
    }
    .radio-pill input[type="radio"] { display: none; }
    .radio-pill:has(input:checked) { border-color: #6366f1; background: #ede9fe; color: #4338ca; }
    .radio-pill .dot {
        width: 14px; height: 14px;
        border-radius: 50%;
        border: 2px solid #94a3b8;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: all 0.2s;
    }
    .radio-pill:has(input:checked) .dot { background: #6366f1; border-color: #6366f1; }
    .radio-pill:has(input:checked) .dot::after { content: ''; width: 5px; height: 5px; background: #fff; border-radius: 50%; }

    /* ── GOOGLE DRIVE SECTION ────────────────────── */
    .gdrive-section {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 22px;
        margin-top: 18px;
    }
    .gdrive-title {
        font-size: 0.85rem; font-weight: 800; color: #0f172a;
        margin-bottom: 18px;
        display: flex; align-items: center; gap: 8px;
    }

    /* ── INPUT GROUP ─────────────────────────────── */
    .input-group-premium { display: flex; }
    .input-group-premium .field-input { border-radius: 10px 0 0 10px; }
    .input-group-btn {
        padding: 0 14px;
        border: 1.5px solid #e2e8f0; border-left: none;
        border-radius: 0 10px 10px 0;
        background: #f1f5f9; color: #475569;
        cursor: pointer; font-size: 0.85rem;
        transition: all 0.2s ease;
    }
    .input-group-btn:hover { background: #e2e8f0; color: #0f172a; }

    /* ── INFO / STATUS BOXES ─────────────────────── */
    .info-box {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border: 1px solid #bae6fd;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 0.82rem;
        color: #0369a1;
        line-height: 1.6;
        margin-top: 14px;
    }
    .info-box code {
        background: rgba(3,105,161,0.1);
        border-radius: 4px;
        padding: 1px 5px;
        font-size: 0.78rem;
        font-weight: 700;
        word-break: break-all;
    }
    .status-ok {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 1px solid #86efac;
        color: #15803d;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.85rem; font-weight: 600;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 14px;
    }
    .status-warn {
        background: linear-gradient(135deg, #fffbeb, #fef3c7);
        border: 1px solid #fcd34d;
        color: #92400e;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.85rem; font-weight: 600;
        display: flex; align-items: center; gap: 8px;
        margin-bottom: 14px;
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
    .alert-ok-p  { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border: 1px solid #86efac; color: #15803d; }
    .alert-err-p { background: linear-gradient(135deg,#fef2f2,#fee2e2); border: 1px solid #fca5a5; color: #b91c1c; }
    .alert-premium .close-btn {
        position: absolute; top: 12px; right: 14px;
        background: none; border: none; cursor: pointer;
        color: inherit; opacity: 0.6; font-size: 1rem;
        padding: 0; line-height: 1;
    }

    /* ── SHORTCUT PILLS ──────────────────────────── */
    .shortcut-bar { display: flex; gap: 10px; flex-wrap: wrap; }
    .shortcut-pill {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 8px 16px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        font-size: 0.82rem; font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .shortcut-pill:hover { border-color: #a5b4fc; color: #4338ca; background: #ede9fe; text-decoration: none; }

    /* ── MANUAL BACKUP CARD ─────────────────────── */
    .backup-now-card {
        background: linear-gradient(135deg, #f5f3ff, #ede9fe);
        border: 1.5px solid #c4b5fd;
        border-radius: 14px;
        padding: 22px;
    }
    .backup-now-title { font-size: 0.92rem; font-weight: 800; color: #4338ca; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }

    /* ── SAVE FOOTER ─────────────────────────────── */
    .save-footer {
        position: sticky; bottom: 0;
        background: rgba(241,245,249,0.96);
        backdrop-filter: blur(12px);
        border-top: 1.5px solid #e2e8f0;
        padding: 16px 0; margin-top: 8px; z-index: 10;
    }
    .btn-save {
        background: linear-gradient(135deg, #4338ca, #6366f1);
        color: #fff; font-weight: 800; font-size: 0.95rem;
        padding: 13px 30px; border-radius: 12px; border: none;
        cursor: pointer; display: inline-flex; align-items: center; gap: 9px;
        box-shadow: 0 4px 18px rgba(99,102,241,0.3);
        transition: all 0.22s ease; font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(99,102,241,0.4); }

    /* ── ACTION BUTTONS ──────────────────────────── */
    .btn-action {
        padding: 8px 18px; border-radius: 10px;
        font-size: 0.82rem; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
        text-decoration: none; transition: all 0.2s ease;
    }
    .btn-action.indigo { background: linear-gradient(135deg,#4338ca,#6366f1); color: #fff; box-shadow: 0 3px 10px rgba(99,102,241,0.3); }
    .btn-action.indigo:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(99,102,241,0.4); color: #fff; }
    .btn-action.danger { background: #fef2f2; color: #dc2626; border: 1.5px solid #fca5a5; }
    .btn-action.danger:hover { background: #dc2626; color: #fff; }
    .btn-action.green { background: #10b981; color: #fff; }
    .btn-action.green:hover { background: #059669; color: #fff; }

    /* ── DIVIDER ─────────────────────────────────── */
    .premium-divider { border: none; border-top: 1px solid #f1f5f9; margin: 18px 0; }
</style>
@endsection

@section('content')
<div id="backup-page" class="container-fluid px-4 py-4">

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert-premium alert-ok-p">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert-premium alert-err-p">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert-premium alert-err-p">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Validation Errors:</strong>
                <ul style="margin:6px 0 0;padding-left:16px;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- ── TAB NAV ── --}}
    <ul class="nav nav-tabs-bp" id="backupTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-backup" data-bs-toggle="tab" data-bs-target="#pane-backup"
                type="button" role="tab">
                <i class="fas fa-database"></i>
                Backup
                <span class="tab-badge">Run & Options</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-config" data-bs-toggle="tab" data-bs-target="#pane-config"
                type="button" role="tab">
                <i class="fas fa-cog"></i>
                Configuration
                <span class="tab-badge">Storage & Alerts</span>
            </button>
        </li>
    </ul>

    <form id="settingsForm" action="{{ route('admin.backup.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="tab-content" id="backupTabsContent">

            {{-- ════════════════════════════════════════════ --}}
            {{-- TAB 1 — BACKUP --}}
            {{-- ════════════════════════════════════════════ --}}
            <div class="tab-pane fade show active" id="pane-backup" role="tabpanel">

                {{-- Manual Backup --}}
                <div class="module-wrap">
                    <div class="section-band band-indigo">
                        <div class="band-icon"><i class="fas fa-bolt"></i></div>
                        <div>
                            <h2>Manual Backup</h2>
                            <span>Create a backup snapshot immediately on demand</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="backup-now-card">
                            <div class="backup-now-title"><i class="fas fa-play"></i> Run Backup Now</div>
                            <div class="option-grid-2" style="margin-bottom:18px;">
                                <div>
                                    <label class="field-label">Backup Type</label>
                                    <select name="manual_backup_type" id="manual_backup_type" class="field-select">
                                        <option value="database">Database Only</option>
                                    </select>
                                    <div class="field-hint">Select what content to include</div>
                                </div>
                                <div>
                                    <label class="field-label">Storage Destination</label>
                                    <select name="manual_backup_storage" id="manual_backup_storage" class="field-select">
                                        <option value="local">Local</option>
                                        <option value="google_drive">Google Drive</option>
                                        <option value="both">Both</option>
                                    </select>
                                    <div class="field-hint">Where to store this backup</div>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                                <button type="button" class="btn-action indigo" id="run-manual-backup">
                                    <i class="fas fa-play"></i> Create Backup Now
                                </button>
                                <span style="font-size:0.78rem;color:#7c3aed;">Configure Google Drive credentials in the Configuration tab first.</span>
                            </div>
                        </div>

                        <hr class="premium-divider">

                        <div style="display:flex;align-items:center;gap:8px;font-size:0.76rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:12px;">
                            <i class="fas fa-link" style="color:#6366f1;"></i> Quick Links
                        </div>
                        <div class="shortcut-bar">
                            <a href="{{ route('admin.backup.history.index') }}" class="shortcut-pill">
                                <i class="fas fa-history"></i> Backup History
                            </a>
                            <a href="{{ route('admin.backup.schedules.index') }}" class="shortcut-pill">
                                <i class="fas fa-calendar-alt"></i> Manage Schedules
                            </a>
                            <a href="https://console.cloud.google.com/apis/credentials" target="_blank" class="shortcut-pill">
                                <i class="fab fa-google"></i> Google Cloud Console
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Backup Options --}}
                <div class="module-wrap">
                    <div class="section-band band-green">
                        <div class="band-icon"><i class="fas fa-sliders-h"></i></div>
                        <div>
                            <h2>Backup Options</h2>
                            <span>Control what gets included in each backup</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="toggle-row">
                            <div>
                                <div class="tl">Enable Database Backup</div>
                                <div class="ts">Include the full database dump in every backup operation</div>
                            </div>
                            <div class="toggle-wrap">
                                <input class="premium-toggle" type="checkbox" name="backup_db_enabled" id="backup_db_enabled" value="1"
                                    {{ \App\Models\BackupSetting::get('backup_db_enabled', true) ? 'checked' : '' }}>
                                <label for="backup_db_enabled">Active</label>
                            </div>
                        </div>
                        <div class="toggle-row">
                            <div>
                                <div class="tl">Enable Files Backup</div>
                                <div class="ts">Include application files in every backup operation</div>
                            </div>
                            <div class="toggle-wrap">
                                <input class="premium-toggle" type="checkbox" name="backup_files_enabled" id="backup_files_enabled" value="1"
                                    {{ \App\Models\BackupSetting::get('backup_files_enabled', true) ? 'checked' : '' }}>
                                <label for="backup_files_enabled">Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Retention Policy --}}
                <div class="module-wrap">
                    <div class="section-band band-amber">
                        <div class="band-icon"><i class="fas fa-trash-alt"></i></div>
                        <div>
                            <h2>Retention Policy</h2>
                            <span>Auto-clean old backups to save storage space</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="option-grid-2">
                            <div>
                                <label class="field-label">Retention Days</label>
                                <input type="number" name="retention_days" class="field-input"
                                    value="{{ \App\Models\BackupSetting::get('retention_days', 30) }}" min="1" max="365" required>
                                <div class="field-hint">Delete backups older than this many days (1–365)</div>
                            </div>
                            <div>
                                <label class="field-label">Retention Count</label>
                                <input type="number" name="retention_count" class="field-input"
                                    value="{{ \App\Models\BackupSetting::get('retention_count', 10) }}" min="1" max="100" required>
                                <div class="field-hint">Keep only the most recent N backups (1–100)</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- end pane-backup --}}

            {{-- ════════════════════════════════════════════ --}}
            {{-- TAB 2 — CONFIGURATION --}}
            {{-- ════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="pane-config" role="tabpanel">

                {{-- Storage Configuration --}}
                <div class="module-wrap">
                    <div class="section-band band-blue">
                        <div class="band-icon"><i class="fas fa-hdd"></i></div>
                        <div>
                            <h2>Storage Configuration</h2>
                            <span>Choose where your backups are stored</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <label class="field-label" style="margin-bottom:10px;">Storage Destination</label>
                        <div class="radio-pill-group">
                            <label class="radio-pill">
                                <input type="radio" name="storage_destination" id="storage_local" value="local"
                                    {{ \App\Models\BackupSetting::get('storage_destination', 'local') === 'local' ? 'checked' : '' }}>
                                <span class="dot"></span>
                                <i class="fas fa-server" style="color:#2563eb;"></i> Local Storage
                            </label>
                            <label class="radio-pill">
                                <input type="radio" name="storage_destination" id="storage_google" value="google_drive"
                                    {{ \App\Models\BackupSetting::get('storage_destination') === 'google_drive' ? 'checked' : '' }}>
                                <span class="dot"></span>
                                <i class="fab fa-google-drive" style="color:#d97706;"></i> Google Drive
                            </label>
                            <label class="radio-pill">
                                <input type="radio" name="storage_destination" id="storage_both" value="both"
                                    {{ \App\Models\BackupSetting::get('storage_destination') === 'both' ? 'checked' : '' }}>
                                <span class="dot"></span>
                                <i class="fas fa-cloud" style="color:#7c3aed;"></i> Both (Local + Drive)
                            </label>
                        </div>
                        <div class="field-hint" style="margin-top:10px;">Choose one or both destinations for your backups.</div>

                        @php
                            $googleDriveClientId   = \App\Models\BackupSetting::get('google_drive_client_id');
                            $googleDriveSecretValue= \App\Models\BackupSetting::get('google_drive_client_secret');
                            $hasGoogleDriveSecret  = !empty($googleDriveSecretValue);
                            $googleDriveFolderId   = \App\Models\BackupSetting::get('google_drive_folder_id');
                            $maskedSecret          = $hasGoogleDriveSecret ? str_repeat('•', max(10, strlen($googleDriveSecretValue))) : '';
                        @endphp

                        <div class="gdrive-section" id="google-drive-section" style="display:none;">
                            <div class="gdrive-title">
                                <i class="fab fa-google-drive" style="color:#d97706;font-size:1.1rem;"></i> Google Drive Connection
                            </div>

                            <div style="margin-bottom:16px;">
                                <label class="field-label">Client ID <span class="req">*</span></label>
                                <input type="text" name="google_drive_client_id"
                                    class="field-input @error('google_drive_client_id') is-invalid @enderror"
                                    value="{{ old('google_drive_client_id', $googleDriveClientId) }}"
                                    placeholder="Enter Google OAuth Client ID">
                                <div class="field-hint">Create OAuth credentials in Google Cloud Console (Application type: Web application)</div>
                                @error('google_drive_client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div style="margin-bottom:16px;">
                                <label class="field-label">Client Secret <span class="req">*</span></label>
                                <input type="password" name="google_drive_client_secret"
                                    class="field-input @error('google_drive_client_secret') is-invalid @enderror"
                                    placeholder="{{ $hasGoogleDriveSecret ? 'Enter a new secret to replace the saved one (optional)' : 'Enter Google OAuth Client Secret' }}">
                                <div class="field-hint">{{ $hasGoogleDriveSecret ? 'Leave blank to keep existing secret. Enter a new one to replace it.' : 'Paste the OAuth client secret from Google Cloud Console.' }}</div>
                                @error('google_drive_client_secret')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                @if($hasGoogleDriveSecret)
                                    <div style="margin-top:12px;">
                                        <label class="field-label">Saved Secret</label>
                                        <div class="input-group-premium">
                                            <input type="password" class="field-input secret-display"
                                                value="{{ $maskedSecret }}" readonly
                                                data-secret="{{ $googleDriveSecretValue }}"
                                                data-mask="{{ $maskedSecret }}">
                                            <button type="button" class="input-group-btn toggle-secret-view" title="Show/Hide"><i class="fas fa-eye"></i></button>
                                            <button type="button" class="input-group-btn copy-secret-btn" title="Copy"><i class="fas fa-copy"></i></button>
                                        </div>
                                        <div class="field-hint">Eye icon to view, copy icon to copy the saved secret.</div>
                                    </div>
                                @endif
                            </div>

                            <div style="margin-bottom:16px;">
                                <label class="field-label">Folder ID <span style="font-weight:400;text-transform:none;color:#94a3b8;">(optional)</span></label>
                                <input type="text" name="google_drive_folder_id"
                                    class="field-input @error('google_drive_folder_id') is-invalid @enderror"
                                    value="{{ old('google_drive_folder_id', $googleDriveFolderId) }}"
                                    placeholder="Google Drive folder ID (leave empty for Drive root)">
                                <div class="field-hint">Open the folder in Google Drive and copy the ID from the URL.</div>
                                @error('google_drive_folder_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="info-box">
                                <strong>Redirect URI:</strong><br>
                                <code>{{ url('/admin/backup/google-drive/callback') }}</code><br>
                                <span style="margin-top:4px;display:inline-block;">Add this URI to your OAuth client in Google Cloud Console.</span>
                            </div>

                            <hr class="premium-divider">

                            @if(\App\Models\BackupSetting::isGoogleDriveEnabled())
                                <div class="status-ok">
                                    <i class="fas fa-check-circle"></i> Google Drive is connected and authorized
                                </div>
                                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                                    <a href="{{ route('admin.backup.google-drive.test') }}" class="btn-action green" id="test-connection">
                                        <i class="fas fa-vial"></i> Test Connection
                                    </a>
                                    <form action="{{ route('admin.backup.google-drive.disconnect') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-action danger"
                                            onclick="return confirm('Disconnect Google Drive?')">
                                            <i class="fas fa-unlink"></i> Disconnect
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="status-warn">
                                    <i class="fas fa-exclamation-triangle"></i> Google Drive is not connected yet
                                </div>
                                <a href="{{ route('admin.backup.google-drive.connect') }}" class="btn-action indigo">
                                    <i class="fab fa-google"></i> Connect Google Drive
                                </a>
                                <div class="field-hint" style="margin-top:8px;">Save your Client ID & Secret above, then click Connect to authorize.</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Notification Settings --}}
                <div class="module-wrap">
                    <div class="section-band band-cyan">
                        <div class="band-icon"><i class="fas fa-bell"></i></div>
                        <div>
                            <h2>Notification Settings</h2>
                            <span>Get email alerts when backups succeed or fail</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div style="margin-bottom:18px;">
                            <label class="field-label">Notification Email</label>
                            <input type="email" name="notification_email" class="field-input"
                                value="{{ \App\Models\BackupSetting::get('notification_email', '') }}"
                                placeholder="admin@yourdomain.com">
                            <div class="field-hint">Email address to receive backup status notifications</div>
                        </div>
                        <div class="option-grid-2">
                            <div class="toggle-row" style="margin-bottom:0;">
                                <div>
                                    <div class="tl">Notify on Success</div>
                                    <div class="ts">Send an email when backup completes successfully</div>
                                </div>
                                <div class="toggle-wrap">
                                    <input class="premium-toggle" type="checkbox" name="notification_on_success" id="notification_on_success" value="1"
                                        {{ \App\Models\BackupSetting::get('notification_on_success', false) ? 'checked' : '' }}>
                                    <label for="notification_on_success">On</label>
                                </div>
                            </div>
                            <div class="toggle-row" style="margin-bottom:0;">
                                <div>
                                    <div class="tl">Notify on Failure</div>
                                    <div class="ts">Send an alert email when a backup fails</div>
                                </div>
                                <div class="toggle-wrap">
                                    <input class="premium-toggle" type="checkbox" name="notification_on_failure" id="notification_on_failure" value="1"
                                        {{ \App\Models\BackupSetting::get('notification_on_failure', true) ? 'checked' : '' }}>
                                    <label for="notification_on_failure">On</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- end pane-config --}}

        </div>{{-- end tab-content --}}

        {{-- ── STICKY SAVE FOOTER ── --}}
        <div class="save-footer">
            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Save Settings
                </button>
                <span style="font-size:0.78rem;color:#94a3b8;">Changes apply to both tabs simultaneously</span>
            </div>
        </div>

    </form>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ── Google Drive section visibility ───────────
        const storageRadios      = document.querySelectorAll('input[name="storage_destination"]');
        const googleDriveSection = document.getElementById('google-drive-section');

        function toggleGoogleDriveSection() {
            const selected = document.querySelector('input[name="storage_destination"]:checked')?.value;
            googleDriveSection.style.display = (selected === 'google_drive' || selected === 'both') ? 'block' : 'none';
        }
        storageRadios.forEach(r => r.addEventListener('change', toggleGoogleDriveSection));
        if (storageRadios.length) toggleGoogleDriveSection();

        // ── If validation errors exist, switch to config tab if needed ──
        @if($errors->has('google_drive_client_id') || $errors->has('google_drive_client_secret') || $errors->has('google_drive_folder_id') || $errors->has('notification_email'))
            const configTab = document.getElementById('tab-config');
            if (configTab) {
                const bsTab = new bootstrap.Tab(configTab);
                bsTab.show();
            }
        @endif

        // ── Secret visibility ─────────────────────────
        document.querySelectorAll('.toggle-secret-view').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const input = this.closest('.input-group-premium').querySelector('.secret-display');
                const isPassword = input.type === 'password';
                input.type  = isPassword ? 'text' : 'password';
                input.value = isPassword ? input.dataset.secret : input.dataset.mask;
                this.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
            });
        });

        // ── Copy secret ───────────────────────────────
        document.querySelectorAll('.copy-secret-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const secret = this.closest('.input-group-premium').querySelector('.secret-display').dataset.secret;
                navigator.clipboard.writeText(secret)
                    .then(() => alert('Secret copied to clipboard'))
                    .catch(() => alert('Unable to copy. Please copy manually.'));
            });
        });

        // ── Manual backup ─────────────────────────────
        const manualBackupBtn = document.getElementById('run-manual-backup');
        if (manualBackupBtn) {
            manualBackupBtn.addEventListener('click', function() {
                const type    = document.getElementById('manual_backup_type').value;
                const storage = document.getElementById('manual_backup_storage').value;
                if (!confirm('Create a backup now? This may take a few minutes.')) return;

                const btn = this;
                btn.disabled  = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating…';

                fetch('{{ route('admin.backup.run') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ backup_type: type, storage_destination: storage })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        alert('Backup started! Check the history page for status.');
                        window.location.href = '{{ route('admin.backup.history.index') }}';
                    } else {
                        alert('Backup failed: ' + data.message);
                    }
                })
                .catch(e => alert('Error: ' + e))
                .finally(() => {
                    btn.disabled  = false;
                    btn.innerHTML = '<i class="fas fa-play"></i> Create Backup Now';
                });
            });
        }

        // ── Test Google Drive connection ──────────────
        const testBtn = document.getElementById('test-connection');
        if (testBtn) {
            testBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const btn = this;
                btn.disabled  = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing…';
                fetch('{{ route('admin.backup.google-drive.test') }}')
                    .then(r => r.json())
                    .then(data => alert(data.message))
                    .catch(e => alert('Test failed: ' + e))
                    .finally(() => {
                        btn.disabled  = false;
                        btn.innerHTML = '<i class="fas fa-vial"></i> Test Connection';
                    });
            });
        }
    });
</script>
@endsection
