@extends('layouts.master')

@section('title', 'Backup Schedules')

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
    }
    .btn-hdr.primary { background: linear-gradient(135deg, #4338ca, #6366f1); color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,0.3); }
    .btn-hdr.primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(99,102,241,0.4); color: #fff; }
    .btn-hdr.ghost { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    .btn-hdr.ghost:hover { background: #ede9fe; border-color: #a5b4fc; color: #4338ca; }

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
    .name-line { font-size: 0.875rem; color: #0f172a; font-weight: 700; }
    .name-line small { display: block; color: #64748b; font-size: 0.78rem; font-weight: 500; margin-top: 2px; }

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
    .bp-active   { background: #dcfce7; color: #15803d; }
    .bp-inactive { background: #fee2e2; color: #b91c1c; }
    .bp-running  { background: #fef3c7; color: #b45309; }

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
    }
    .icon-btn:hover { border-color: #a5b4fc; background: #ede9fe; color: #4338ca; }
    .icon-btn.ib-delete:hover { border-color: #fca5a5; background: #fef2f2; color: #dc2626; }
    .icon-btn.ib-run:hover    { border-color: #a7f3d0; background: #ecfdf5; color: #059669; }

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
</style>
@endsection

@section('content')
<div id="schedules-page" class="container-fluid px-4 py-4">

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
            <h4>Backup Schedules</h4>
            <p>Automate regular system backups to secure your database and files</p>
        </div>
        <div class="header-actions">
            @can('backup.schedules.create')
                <a href="{{ route('admin.backup.schedules.create') }}" class="btn-hdr primary">
                    <i class="fas fa-plus"></i> Create Schedule
                </a>
            @endcan
            <a href="{{ route('admin.backup.settings') }}" class="btn-hdr ghost">
                <i class="fas fa-cog"></i> Settings
            </a>
            <a href="{{ route('admin.backup.history.index') }}" class="btn-hdr ghost">
                <i class="fas fa-history"></i> History
            </a>
        </div>
    </div>

    {{-- Schedules Table --}}
    <div class="table-card">
        <div class="table-card-header">
            <h2>
                <span class="hdr-icon"><i class="fas fa-calendar-alt"></i></span>
                Active Schedules
            </h2>
        </div>

        @if($schedules->count() > 0)
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Storage</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Last Run</th>
                            <th>Next Run</th>
                            <th>Stats</th>
                            <th style="width:140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td>
                                    <div class="name-line">
                                        {{ $schedule->name }}
                                        @if($schedule->cron_description)
                                            <small>{{ $schedule->cron_description }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($schedule->backup_type === 'database')
                                        <span class="badge-pill bp-db"><i class="fas fa-database"></i> Database</span>
                                    @elseif($schedule->backup_type === 'files')
                                        <span class="badge-pill bp-files"><i class="fas fa-folder"></i> Files</span>
                                    @else
                                        <span class="badge-pill bp-both"><i class="fas fa-archive"></i> Both</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->storage_destination === 'local')
                                        <span class="badge-pill bp-local"><i class="fas fa-server"></i> Local</span>
                                    @elseif($schedule->storage_destination === 'google_drive')
                                        <span class="badge-pill bp-drive"><i class="fab fa-google-drive"></i> G-Drive</span>
                                    @else
                                        <span class="badge-pill bp-cloud"><i class="fas fa-cloud"></i> Both</span>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $schedule->cron_expression }}</code>
                                </td>
                                <td>
                                    @if($schedule->is_active)
                                        <span class="badge-pill bp-active"><i class="fas fa-check"></i> Active</span>
                                    @else
                                        <span class="badge-pill bp-inactive"><i class="fas fa-pause"></i> Paused</span>
                                    @endif

                                    @if($schedule->last_status === 'running')
                                        <span class="badge-pill bp-running" style="margin-left:4px;"><i class="fas fa-spinner fa-spin"></i> Running</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->last_run_at)
                                        <span style="font-weight:600;">{{ $schedule->last_run_at->diffForHumans() }}</span>
                                    @else
                                        <span style="color:#94a3b8;">Never</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->next_run_at)
                                        <span style="font-weight:600;color:#6366f1;">{{ $schedule->next_run_at->diffForHumans() }}</span>
                                    @else
                                        <span style="color:#94a3b8;">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-size:0.78rem;font-weight:700;line-height:1.4;">
                                        <span style="color:#10b981;"><i class="fas fa-check-circle"></i> {{ $schedule->success_count }}</span><br>
                                        <span style="color:#ef4444;"><i class="fas fa-times-circle"></i> {{ $schedule->failure_count }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        @can('backup.schedules.run')
                                            <form action="{{ route('admin.backup.schedules.run-now', $schedule->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="icon-btn ib-run" title="Run Now">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </form>
                                        @endcan
                                        
                                        @can('backup.schedules.edit')
                                            <a href="{{ route('admin.backup.schedules.edit', $schedule->id) }}" class="icon-btn" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        @endcan

                                        @can('backup.schedules.edit')
                                            <form action="{{ route('admin.backup.schedules.toggle', $schedule->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="icon-btn" title="{{ $schedule->is_active ? 'Pause' : 'Activate' }}">
                                                    <i class="fas fa-{{ $schedule->is_active ? 'pause' : 'check' }}"></i>
                                                </button>
                                            </form>
                                        @endcan

                                        @can('backup.schedules.delete')
                                            <form action="{{ route('admin.backup.schedules.destroy', $schedule->id) }}" method="POST" style="display:inline;"
                                                  onsubmit="return confirm('Delete this schedule?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="icon-btn ib-delete" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-calendar-times"></i></div>
                <h4>No Backup Schedules</h4>
                <p>Set up automated backups to secure your platform regularly.</p>
                @can('backup.schedules.create')
                    <a href="{{ route('admin.backup.schedules.create') }}" class="btn-hdr primary" style="margin:0 auto;">
                        <i class="fas fa-plus"></i> Create First Schedule
                    </a>
                @endcan
            </div>
        @endif
    </div>

</div>
@endsection
