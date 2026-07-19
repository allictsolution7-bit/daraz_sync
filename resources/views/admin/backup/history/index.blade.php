@extends('layouts.master')

@section('title', 'Backup History')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #history-page {
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
    .alert-ok-p  { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border: 1px solid #86efac; color: #15803d; }
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

    .header-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .btn-hdr {
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 0.825rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        background: #fff; color: #475569; border: 1.5px solid #e2e8f0;
    }
    .btn-hdr:hover { background: #ede9fe; border-color: #a5b4fc; color: #4338ca; }

    /* ── TABLE CARD ────────────────────────── */
    .table-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 24px;
    }
    .table-card-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
        padding: 18px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .table-card-header h2 {
        font-size: 1rem;
        font-weight: 800;
        color: #fff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .table-card-header .hdr-icon {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.15);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; color: #fff;
        backdrop-filter: blur(4px);
    }

    /* ── DATA TABLE ─────────────────────────────── */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
    .data-table thead th {
        padding: 14px 18px;
        font-size: 0.72rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        text-align: left;
        white-space: nowrap;
    }
    .data-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s ease;
    }
    .data-table tbody tr:hover { background: #fafbff; }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table td {
        padding: 14px 18px;
        font-size: 0.85rem;
        color: #334155;
        vertical-align: middle;
    }
    .data-table td code {
        background: #f1f5f9;
        border-radius: 5px;
        padding: 2px 6px;
        font-size: 0.8rem;
        color: #4338ca;
        font-weight: 600;
    }
    .text-title { font-weight: 700; color: #0f172a; }
    .text-subtitle { font-size: 0.78rem; color: #64748b; margin-top: 2px; display: block; }

    /* ── BADGES ─────────────────────────────────── */
    .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }
    .bp-success  { background: #dcfce7; color: #15803d; }
    .bp-failed   { background: #fee2e2; color: #b91c1c; }
    .bp-running  { background: #fef3c7; color: #b45309; }
    .bp-generic  { background: #f1f5f9; color: #475569; }

    .bp-db    { background: #e0f2fe; color: #0369a1; }
    .bp-files { background: #f1f5f9; color: #475569; }
    .bp-both  { background: #ede9fe; color: #6d28d9; }

    .bp-local { background: #f1f5f9; color: #475569; }
    .bp-drive { background: #e0f2fe; color: #0369a1; }
    .bp-cloud { background: #ede9fe; color: #6d28d9; }

    /* ── ACTION BUTTONS ──────────────────────────── */
    .row-actions { display: flex; gap: 5px; align-items: center; }
    .icon-btn {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.825rem;
        cursor: pointer;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .icon-btn:hover { border-color: #a5b4fc; background: #ede9fe; color: #4338ca; }
    .icon-btn:disabled, .icon-btn.disabled { opacity: 0.45; cursor: not-allowed; border-color: #e2e8f0 !important; background: #fff !important; color: #cbd5e1 !important; }
    .icon-btn.ib-delete:hover { border-color: #fca5a5; background: #fef2f2; color: #dc2626; }
    .icon-btn.ib-restore:hover { border-color: #a7f3d0; background: #ecfdf5; color: #059669; }

    /* ── EMPTY STATE ────────────────────────────── */
    .empty-state { padding: 60px 24px; text-align: center; }
    .empty-icon {
        width: 70px; height: 70px;
        border-radius: 50%;
        background: #ede9fe;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.8rem;
        color: #6366f1;
    }
    .empty-state h4 { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
    .empty-state p  { font-size: 0.875rem; color: #94a3b8; margin-bottom: 20px; }

    /* ── PREMIUM MODAL ── */
    .modal-content-premium {
        border-radius: 16px;
        border: none;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .modal-header-premium {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
        color: #fff;
        border-bottom: none;
        padding: 18px 24px;
    }
    .modal-header-premium .modal-title { font-weight: 800; font-size: 1.05rem; }
    .modal-header-premium .btn-close { filter: invert(1); opacity: 0.8; }
    .modal-body-premium { padding: 24px; }
    .modal-footer-premium { border-top: 1px solid #f1f5f9; padding: 16px 24px; }

    /* ── PAGINATION ─────────────────────────────── */
    .pagination { gap: 4px; }
    .page-link { border-radius: 8px !important; border: 1.5px solid #e2e8f0 !important; color: #475569 !important; font-size: 0.82rem; font-weight: 600; padding: 6px 12px; }
    .page-item.active .page-link { background: linear-gradient(135deg, #4338ca, #6366f1) !important; border-color: transparent !important; color: #fff !important; }
</style>
@endsection

@section('content')
<div id="history-page" class="container-fluid px-4 py-4">

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

    {{-- Header Banner --}}
    <div class="hdr-panel">
        <div class="hdr-title">
            <h4>Backup History</h4>
            <p>Monitor execution state, file sizes, and perform restorations</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.backup.settings') }}" class="btn-hdr">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="{{ route('admin.backup.schedules.index') }}" class="btn-hdr">
                <i class="fas fa-calendar-alt"></i> Schedules
            </a>
        </div>
    </div>

    {{-- History Table --}}
    <div class="table-card">
        <div class="table-card-header">
            <h2>
                <span class="hdr-icon"><i class="fas fa-history"></i></span>
                Backup Executions
            </h2>
        </div>

        @if($backups->count() > 0)
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:70px;">ID</th>
                            <th>Type</th>
                            <th>Storage</th>
                            <th>Trigger Source</th>
                            <th>Status</th>
                            <th>Size</th>
                            <th>Duration</th>
                            <th>Date & Time</th>
                            <th style="width:120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $backup)
                            <tr>
                                <td><span style="font-weight:700;color:#64748b;">#{{ $backup->id }}</span></td>
                                <td>
                                    @if($backup->backup_type === 'database')
                                        <span class="badge-pill bp-db"><i class="fas fa-database"></i> Database</span>
                                    @elseif($backup->backup_type === 'files')
                                        <span class="badge-pill bp-files"><i class="fas fa-folder"></i> Files</span>
                                    @else
                                        <span class="badge-pill bp-both"><i class="fas fa-archive"></i> Both</span>
                                    @endif
                                </td>
                                <td>
                                    @if($backup->storage_destination === 'local')
                                        <span class="badge-pill bp-local"><i class="fas fa-server"></i> Local</span>
                                    @elseif($backup->storage_destination === 'google_drive')
                                        <span class="badge-pill bp-drive"><i class="fab fa-google-drive"></i> G-Drive</span>
                                    @else
                                        <span class="badge-pill bp-cloud"><i class="fas fa-cloud"></i> Both</span>
                                    @endif
                                </td>
                                <td>
                                    @if($backup->schedule)
                                        <a href="{{ route('admin.backup.schedules.edit', $backup->schedule_id) }}" class="text-title" style="text-decoration:none;">
                                            <i class="fas fa-calendar-check" style="color:#6366f1;margin-right:4px;"></i> {{ $backup->schedule->name }}
                                        </a>
                                    @else
                                        <span class="badge-pill bp-generic"><i class="fas fa-user-cog"></i> Manual</span>
                                    @endif
                                </td>
                                <td>
                                    @if($backup->status === 'success')
                                        <span class="badge-pill bp-success"><i class="fas fa-check"></i> Success</span>
                                    @elseif($backup->status === 'failed')
                                        <span class="badge-pill bp-failed"><i class="fas fa-times"></i> Failed</span>
                                        @if($backup->error_message)
                                            <span class="text-subtitle text-danger" title="{{ $backup->error_message }}">
                                                {{ Str::limit($backup->error_message, 28) }}
                                            </span>
                                        @endif
                                    @elseif($backup->status === 'running')
                                        <span class="badge-pill bp-running"><i class="fas fa-spinner fa-spin"></i> Running</span>
                                    @else
                                        <span class="badge-pill bp-generic">{{ ucfirst($backup->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($backup->file_size)
                                        <span style="font-weight:700;color:#0f172a;">{{ $backup->file_size_human }}</span>
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($backup->duration_seconds)
                                        <span style="font-weight:600;"><i class="far fa-clock" style="color:#94a3b8;margin-right:2px;"></i> {{ gmdate('H:i:s', $backup->duration_seconds) }}</span>
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-title">{{ $backup->created_at->diffForHumans() }}</span>
                                    <span class="text-subtitle">{{ $backup->created_at->format('Y-m-d H:i') }}</span>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        @can('backup.download')
                                            @if($backup->canDownload())
                                                <a href="{{ route('admin.backup.history.download', $backup->id) }}" class="icon-btn" title="Download Backup">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @else
                                                <button type="button" class="icon-btn" disabled title="Download not available on this destination">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            @endif
                                        @else
                                            <button type="button" class="icon-btn" disabled title="Download Permission Required">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        @endcan

                                        @can('backup.restore')
                                            @if($backup->canRestore())
                                                <button type="button" class="icon-btn ib-restore" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#restoreModal{{ $backup->id }}" 
                                                    title="Restore Backup">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            @endif
                                        @endcan

                                        @can('backup.history.delete')
                                            <form action="{{ route('admin.backup.history.destroy', $backup->id) }}" method="POST" style="display:inline;"
                                                onsubmit="return confirm('Delete this backup log?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="icon-btn ib-delete" title="Delete Log">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>

                                    {{-- Premium Restore Modal --}}
                                    @can('backup.restore')
                                    @if($backup->canRestore())
                                    <div class="modal fade" id="restoreModal{{ $backup->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content modal-content-premium">
                                                <form action="{{ route('admin.backup.history.restore', $backup->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header modal-header-premium">
                                                        <h5 class="modal-title"><i class="fas fa-undo"></i> Restore System State</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body modal-body-premium">
                                                        <div class="alert-premium alert-err-p" style="margin-bottom:18px;">
                                                            <i class="fas fa-exclamation-triangle" style="font-size:1.15rem;margin-top:2px;"></i>
                                                            <div>
                                                                <strong style="display:block;margin-bottom:2px;">Critical Warning</strong>
                                                                Restoring will overwrite your current active database tables or files. This operation is destructive and cannot be undone!
                                                            </div>
                                                        </div>

                                                        <div style="margin-bottom:18px;">
                                                            <label class="field-label">Restore Target Mode</label>
                                                            <select name="type" class="field-select">
                                                                @if($backup->backup_type === 'both')
                                                                    <option value="database">Database Tables Only</option>
                                                                    <option value="files">Storage Files Only</option>
                                                                    <option value="both">Both (Database + Files)</option>
                                                                @else
                                                                    <option value="{{ $backup->backup_type }}">{{ ucfirst($backup->backup_type) }} Only</option>
                                                                @endif
                                                            </select>
                                                        </div>

                                                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px 14px;">
                                                            <div class="form-check" style="margin:0;min-height:auto;">
                                                                <input class="form-check-input" type="checkbox" name="confirm" id="confirm{{ $backup->id }}" value="1" required style="cursor:pointer;">
                                                                <label class="form-check-label" for="confirm{{ $backup->id }}" style="font-size:0.8rem;font-weight:700;color:#475569;cursor:pointer;user-select:none;">
                                                                    I explicitly confirm and authorize overwriting the active environment.
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer modal-footer-premium">
                                                        <button type="button" class="btn-action ghost" data-bs-dismiss="modal" style="padding:8px 18px;font-size:0.82rem;">Cancel</button>
                                                        <button type="submit" class="btn-action danger" style="padding:8px 18px;font-size:0.82rem;background:#ef4444;border-color:#ef4444;color:#fff;">
                                                            <i class="fas fa-undo"></i> Execute Restoration
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endcan

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:16px 24px;">{{ $backups->links() }}</div>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-history"></i></div>
                <h4>No Backup History Found</h4>
                <p>There are no recorded executions or snapshots yet.</p>
                <a href="{{ route('admin.backup.settings') }}" class="btn-hdr primary" style="margin:0 auto;text-decoration:none;">
                    <i class="fas fa-play"></i> Run Backup Now
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
