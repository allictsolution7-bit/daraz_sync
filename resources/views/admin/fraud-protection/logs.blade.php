@extends('layouts.master')

@section('title', 'Protection Logs')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #logs-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        min-height: 100vh;
    }

    /* ── STAT CARDS ─────────────────────────────── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 992px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .stat-grid { grid-template-columns: 1fr; } }

    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    .stat-icon.si-blue   { background: #eff6ff; color: #2563eb; }
    .stat-icon.si-green  { background: #f0fdf4; color: #16a34a; }
    .stat-icon.si-amber  { background: #fffbeb; color: #d97706; }
    .stat-icon.si-red    { background: #fef2f2; color: #dc2626; }
    .stat-icon.si-purple { background: #f5f3ff; color: #7c3aed; }
    .stat-label { font-size: 0.76rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
    .stat-value { font-size: 1.6rem; font-weight: 800; color: #0f172a; line-height: 1; }
    .stat-sub   { font-size: 0.75rem; color: #94a3b8; margin-top: 3px; }

    /* ── FILTER PANEL ───────────────────────────── */
    .filter-panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .filter-panel-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .filter-row { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
    .filter-group { display: flex; flex-direction: column; gap: 5px; flex: 1; min-width: 150px; }
    .filter-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .filter-input, .filter-select {
        padding: 9px 13px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.875rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #0f172a;
        background: #f8fafc;
        outline: none;
        transition: all 0.2s ease;
        width: 100%;
    }
    .filter-input:focus, .filter-select:focus {
        border-color: #6366f1;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    .btn-filter {
        padding: 9px 20px;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.2s ease;
        white-space: nowrap;
        text-decoration: none;
    }
    .btn-filter.primary {
        background: linear-gradient(135deg, #4338ca, #6366f1);
        color: #fff;
        box-shadow: 0 3px 12px rgba(99,102,241,0.3);
    }
    .btn-filter.primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(99,102,241,0.4); color: #fff; }
    .btn-filter.ghost {
        background: #f1f5f9;
        color: #475569;
        border: 1.5px solid #e2e8f0;
    }
    .btn-filter.ghost:hover { background: #e2e8f0; color: #1e293b; }

    /* ── TIME PILL FILTERS ──────────────────────── */
    .time-pills {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 16px;
    }
    .time-pill {
        padding: 5px 14px;
        border-radius: 99px;
        font-size: 0.78rem;
        font-weight: 700;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        cursor: pointer;
        transition: all 0.18s ease;
        text-decoration: none;
        display: inline-block;
    }
    .time-pill:hover { border-color: #a5b4fc; color: #4338ca; background: #ede9fe; text-decoration: none; }
    .time-pill.active { background: linear-gradient(135deg, #4338ca, #6366f1); border-color: transparent; color: #fff; box-shadow: 0 2px 10px rgba(99,102,241,0.35); }

    /* ── MAIN TABLE CARD ────────────────────────── */
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
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
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
        font-size: 0.9rem;
        backdrop-filter: blur(4px);
    }
    .header-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .btn-hdr {
        padding: 7px 14px;
        border-radius: 9px;
        font-size: 0.8rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-hdr.light   { background: rgba(255,255,255,0.15); color: #fff; backdrop-filter: blur(4px); }
    .btn-hdr.light:hover { background: rgba(255,255,255,0.25); color: #fff; }
    .btn-hdr.success { background: #10b981; color: #fff; }
    .btn-hdr.success:hover { background: #059669; color: #fff; }
    .btn-hdr.danger  { background: #ef4444; color: #fff; }
    .btn-hdr.danger:hover { background: #dc2626; }

    /* ── BULK ACTIONS BAR ───────────────────────── */
    .bulk-bar {
        padding: 12px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        background: #fafafa;
    }

    /* ── DATA TABLE ─────────────────────────────── */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
    .data-table thead th {
        padding: 12px 16px;
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
        padding: 13px 16px;
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
    .time-line { font-size: 0.82rem; color: #0f172a; font-weight: 600; }
    .time-line small { display: block; color: #94a3b8; font-size: 0.72rem; font-weight: 500; margin-top: 1px; }

    /* ── BADGES ─────────────────────────────────── */
    .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 99px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }
    .bp-duplicate { background: #eff6ff; color: #1d4ed8; }
    .bp-fake      { background: #fffbeb; color: #b45309; }
    .bp-fraud     { background: #fef2f2; color: #b91c1c; }
    .bp-mod1      { background: #eff6ff; color: #2563eb; }
    .bp-mod2      { background: #fffbeb; color: #d97706; }
    .bp-mod3      { background: #fef2f2; color: #dc2626; }

    /* ── ROW ACTION BUTTONS ─────────────────────── */
    .row-actions { display: flex; gap: 6px; align-items: center; }
    .icon-btn {
        width: 30px; height: 30px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.18s ease;
    }
    .icon-btn.ib-delete:hover  { border-color: #fca5a5; background: #fef2f2; color: #dc2626; }
    .icon-btn.ib-ban:hover     { border-color: #fcd34d; background: #fffbeb; color: #d97706; }
    .icon-btn.ib-unlock:hover  { border-color: #6ee7b7; background: #f0fdf4; color: #16a34a; }

    /* ── EMPTY STATE ────────────────────────────── */
    .empty-state { padding: 60px 24px; text-align: center; }
    .empty-icon {
        width: 70px; height: 70px;
        border-radius: 50%;
        background: #f0fdf4;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.8rem;
        color: #16a34a;
    }
    .empty-state h4 { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
    .empty-state p  { font-size: 0.875rem; color: #94a3b8; margin-bottom: 20px; }

    /* ── SUB CARDS (offenders / blacklist) ──────── */
    .sub-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .sub-card-header {
        padding: 14px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f1f5f9;
    }
    .sub-card-header h3 { font-size: 0.92rem; font-weight: 800; color: #0f172a; margin: 0; }
    .count-chip {
        background: #ede9fe; color: #5b21b6;
        border-radius: 99px; padding: 2px 9px;
        font-size: 0.72rem; font-weight: 700;
    }
    .sub-card-body { padding: 16px 20px; }
    .mini-table { width: 100%; border-collapse: collapse; }
    .mini-table tr { border-bottom: 1px solid #f1f5f9; }
    .mini-table tr:last-child { border-bottom: none; }
    .mini-table td { padding: 10px 0; font-size: 0.84rem; color: #334155; vertical-align: middle; }

    /* ── SECTION TITLE ──────────────────────────── */
    .section-title {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin: 24px 0 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

    /* ── ALERT ──────────────────────────────────── */
    .alert-ok {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 1px solid #86efac;
        color: #15803d;
        border-radius: 12px;
        padding: 14px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* ── CUSTOM CHECKBOX ────────────────────────── */
    input[type="checkbox"].log-check {
        appearance: none;
        width: 16px; height: 16px;
        border: 2px solid #cbd5e1;
        border-radius: 4px;
        cursor: pointer;
        position: relative;
        transition: all 0.15s ease;
        vertical-align: middle;
    }
    input[type="checkbox"].log-check:checked { background: #6366f1; border-color: #6366f1; }
    input[type="checkbox"].log-check:checked::after {
        content: '✓';
        position: absolute;
        top: -2px; left: 1px;
        font-size: 10px; color: #fff; font-weight: 900;
    }

    .btn-bulk {
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s ease;
    }
    .btn-bulk.sel   { border: 1.5px solid #e2e8f0; background: #f8fafc; color: #475569; }
    .btn-bulk.sel:hover { border-color: #a5b4fc; color: #4338ca; background: #ede9fe; }
    .btn-bulk.del   { border: 1.5px solid #fca5a5; background: #fef2f2; color: #dc2626; }
    .btn-bulk.del:disabled { opacity: 0.45; cursor: not-allowed; }
    .btn-bulk.del:not(:disabled):hover { background: #dc2626; color: #fff; border-color: #dc2626; }

    /* ── PAGINATION ─────────────────────────────── */
    .pagination { gap: 4px; }
    .page-link { border-radius: 8px !important; border: 1.5px solid #e2e8f0 !important; color: #475569 !important; font-size: 0.82rem; font-weight: 600; padding: 6px 12px; }
    .page-item.active .page-link { background: linear-gradient(135deg, #4338ca, #6366f1) !important; border-color: transparent !important; color: #fff !important; }
</style>
@endsection

@section('content')
<div id="logs-page" class="container-fluid px-4 py-4">

    @if(session('success'))
        <div class="alert-ok"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    {{-- ── STAT CARDS ── --}}
    @if(class_exists('\App\Models\BlockedOrderAttempt') && isset($stats))
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon si-red"><i class="fas fa-ban"></i></div>
            <div>
                <div class="stat-label">Blocked Today</div>
                <div class="stat-value">{{ $stats['total_blocked'] ?? 0 }}</div>
                <div class="stat-sub">Fraudulent orders stopped</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-blue"><i class="fas fa-copy"></i></div>
            <div>
                <div class="stat-label">Duplicate</div>
                <div class="stat-value">{{ $stats['by_reason']['duplicate'] ?? 0 }}</div>
                <div class="stat-sub">Duplicate attempts</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-amber"><i class="fas fa-user-secret"></i></div>
            <div>
                <div class="stat-label">Fake Data</div>
                <div class="stat-value">{{ $stats['by_reason']['fake'] ?? 0 }}</div>
                <div class="stat-sub">Fake orders caught</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-purple"><i class="fas fa-phone-slash"></i></div>
            <div>
                <div class="stat-label">Unique Phones</div>
                <div class="stat-value">{{ $stats['unique_phones'] ?? 0 }}</div>
                <div class="stat-sub">Distinct numbers flagged</div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── FILTER PANEL ── --}}
    <div class="filter-panel">
        <div class="filter-panel-title">
            <i class="fas fa-sliders-h" style="color:#6366f1;"></i> Filter Logs
        </div>

        @php $currentDays = request('days'); $baseUrl = route('admin.fraud-protection.logs'); @endphp
        <div class="time-pills">
            <span style="font-size:0.75rem;font-weight:700;color:#94a3b8;margin-right:2px;">Range:</span>
            <a href="{{ $baseUrl }}?days=1"   class="time-pill {{ $currentDays=='1'   ? 'active':'' }}">24 hrs</a>
            <a href="{{ $baseUrl }}?days=7"   class="time-pill {{ $currentDays=='7'   ? 'active':'' }}">7 days</a>
            <a href="{{ $baseUrl }}?days=30"  class="time-pill {{ $currentDays=='30'  ? 'active':'' }}">30 days</a>
            <a href="{{ $baseUrl }}?days=90"  class="time-pill {{ $currentDays=='90'  ? 'active':'' }}">3 months</a>
            <a href="{{ $baseUrl }}?days=365" class="time-pill {{ $currentDays=='365' ? 'active':'' }}">1 year</a>
            <a href="{{ $baseUrl }}"          class="time-pill {{ !$currentDays       ? 'active':'' }}">All time</a>
        </div>

        <form method="GET" action="{{ route('admin.fraud-protection.logs') }}" id="filterForm">
            <input type="hidden" name="days" value="{{ request('days') }}">
            <div class="filter-row">
                <div class="filter-group" style="max-width:220px;">
                    <label class="filter-label">Search</label>
                    <input type="text" class="filter-input" name="search" value="{{ request('search') }}" placeholder="IP, phone, name…">
                </div>
                <div class="filter-group" style="max-width:165px;">
                    <label class="filter-label">From Date</label>
                    <input type="date" class="filter-input" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="filter-group" style="max-width:165px;">
                    <label class="filter-label">To Date</label>
                    <input type="date" class="filter-input" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="filter-group" style="max-width:185px;">
                    <label class="filter-label">Block Reason</label>
                    <select class="filter-select" name="fraud_type">
                        <option value="">All Reasons</option>
                        <option value="duplicate" {{ request('fraud_type')=='duplicate' ? 'selected':'' }}>Duplicate Order</option>
                        <option value="fake"      {{ request('fraud_type')=='fake'      ? 'selected':'' }}>Fake Data</option>
                        <option value="fraud"     {{ request('fraud_type')=='fraud'     ? 'selected':'' }}>Fraud Detected</option>
                    </select>
                </div>
                <div class="filter-group" style="max-width:185px;">
                    <label class="filter-label">Module</label>
                    <select class="filter-select" name="module">
                        <option value="">All Modules</option>
                        <option value="1" {{ request('module')=='1' ? 'selected':'' }}>Duplicate Protection</option>
                        <option value="2" {{ request('module')=='2' ? 'selected':'' }}>Fake Order Protection</option>
                        <option value="3" {{ request('module')=='3' ? 'selected':'' }}>Fraud & Scam Protection</option>
                    </select>
                </div>
                <div class="filter-group" style="flex:0;flex-direction:row;gap:8px;align-items:flex-end;">
                    <button type="submit" class="btn-filter primary"><i class="fas fa-search"></i> Apply</button>
                    <a href="{{ route('admin.fraud-protection.logs') }}" class="btn-filter ghost"><i class="fas fa-times"></i> Clear</a>
                </div>
            </div>
        </form>
    </div>

    {{-- ── MAIN TABLE CARD ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <h2>
                <span class="hdr-icon"><i class="fas fa-history"></i></span>
                Blocked Order Attempts
            </h2>
            <div class="header-actions">
                <a href="{{ route('admin.fraud-protection.index') }}" class="btn-hdr light">
                    <i class="fas fa-cog"></i> Settings
                </a>
                @if(class_exists('\App\Models\BlockedOrderAttempt'))
                    <a href="{{ route('admin.fraud-protection.export') }}" class="btn-hdr success">
                        <i class="fas fa-download"></i> Export CSV
                    </a>
                @endif
                <form action="{{ route('admin.fraud-protection.clear-logs') }}" method="POST" style="display:inline;"
                      onsubmit="return confirm('Clear ALL logs? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-hdr danger"><i class="fas fa-trash"></i> Clear All</button>
                </form>
            </div>
        </div>

        @if(class_exists('\App\Models\BlockedOrderAttempt') && isset($attempts) && $attempts->count() > 0)

            {{-- Bulk bar --}}
            <div class="bulk-bar">
                <div style="display:flex;gap:8px;align-items:center;">
                    <button class="btn-bulk sel" id="selectAllBtn" onclick="toggleSelectAll()">
                        <i class="fas fa-check-square"></i> Select All
                    </button>
                    <button class="btn-bulk sel" id="deselectAllBtn" onclick="deselectAll()" style="display:none;">
                        <i class="fas fa-square"></i> Deselect All
                    </button>
                    <span id="selectedCount" style="font-size:0.78rem;color:#94a3b8;font-weight:600;"></span>
                </div>
                <button class="btn-bulk del" id="bulkDeleteBtn" onclick="handleBulkDelete()" disabled>
                    <i class="fas fa-trash"></i> Delete Selected
                </button>
            </div>

            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:44px;padding-left:20px;">
                                <input type="checkbox" class="log-check" id="selectAllCheckbox" onchange="toggleSelectAll()">
                            </th>
                            <th>Time</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>IP Address</th>
                            <th>Module</th>
                            <th>Reason</th>
                            <th>Errors</th>
                            <th style="width:80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attempts as $attempt)
                        <tr>
                            <td style="padding-left:20px;">
                                <input type="checkbox" class="log-check row-checkbox" value="{{ $attempt->id }}">
                            </td>
                            <td>
                                <div class="time-line">
                                    {{ $attempt->blocked_at->format('M d, Y') }}
                                    <small>{{ $attempt->blocked_at->format('H:i:s') }}</small>
                                </div>
                            </td>
                            <td>{{ $attempt->name ?? '—' }}</td>
                            <td><code>{{ $attempt->phone ?? '—' }}</code></td>
                            <td><code>{{ $attempt->ip_address ?? '—' }}</code></td>
                            <td>
                                @php $mod = $attempt->blocked_by_module; @endphp
                                <span class="badge-pill bp-mod{{ $mod }}">
                                    <i class="fas fa-{{ $mod==1 ? 'copy' : ($mod==2 ? 'user-secret' : 'exclamation-triangle') }}"></i>
                                    Module {{ $mod }}
                                </span>
                            </td>
                            <td>
                                @php $r = strtolower($attempt->formatted_reason ?? ''); @endphp
                                <span class="badge-pill {{ str_contains($r,'duplicate') ? 'bp-duplicate' : (str_contains($r,'fake') ? 'bp-fake' : 'bp-fraud') }}">
                                    {{ $attempt->formatted_reason }}
                                </span>
                            </td>
                            <td>
                                @if($attempt->validation_errors)
                                    <ul style="margin:0;padding-left:14px;font-size:0.75rem;color:#64748b;">
                                        @foreach($attempt->validation_errors as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="row-actions">
                                    <button class="icon-btn ib-delete" onclick="deleteSingleItem({{ $attempt->id }})" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <button class="icon-btn ib-ban" onclick="addToBlacklist('{{ $attempt->phone }}')" title="Blacklist">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding:16px 24px;">{{ $attempts->links() }}</div>

        @elseif(isset($logs) && count($logs) > 0)
            <div style="padding:20px 24px;">
                <div class="alert-ok" style="background:linear-gradient(135deg,#f0f9ff,#e0f2fe);border-color:#bae6fd;color:#0369a1;">
                    <i class="fas fa-info-circle"></i> Showing file-based logs.
                </div>
                @foreach($logs as $log)
                    <pre style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px 16px;font-size:0.78rem;color:#334155;margin-bottom:8px;">{{ $log }}</pre>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-shield-alt"></i></div>
                <h4>No Blocked Attempts</h4>
                <p>Your store is clean — no fraudulent orders have been detected.</p>
                <a href="{{ route('admin.fraud-protection.index') }}" class="btn-filter primary" style="margin:0 auto;">
                    <i class="fas fa-cog"></i> Configure Protection
                </a>
            </div>
        @endif
    </div>

    {{-- ── TOP OFFENDERS & BLACKLIST ── --}}
    @if(isset($stats) && isset($stats['top_blocked_phones']) && $stats['top_blocked_phones']->count() > 0)
        <div class="section-title">
            <i class="fas fa-users-slash" style="color:#ef4444;"></i> Top Offenders & Blacklist
        </div>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:32px;" class="offender-grid">

            {{-- Top phones --}}
            <div class="sub-card">
                <div class="sub-card-header">
                    <i class="fas fa-phone-slash" style="color:#dc2626;font-size:0.9rem;"></i>
                    <h3>Top Blocked Phones</h3>
                    <span class="count-chip">{{ $stats['top_blocked_phones']->count() }}</span>
                </div>
                <div class="sub-card-body">
                    <table class="mini-table">
                        @foreach($stats['top_blocked_phones'] as $phone => $count)
                        <tr>
                            <td><code style="background:#f1f5f9;border-radius:5px;padding:2px 6px;font-size:0.78rem;color:#4338ca;font-weight:600;">{{ $phone }}</code></td>
                            <td style="text-align:right;"><span class="badge-pill bp-fraud">{{ $count }}×</span></td>
                            <td style="text-align:right;padding-left:8px;">
                                <button class="icon-btn ib-ban" onclick="addToBlacklist('{{ $phone }}')" title="Blacklist"><i class="fas fa-ban"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            {{-- Blacklisted phones --}}
            <div class="sub-card">
                <div class="sub-card-header">
                    <i class="fas fa-phone" style="color:#dc2626;font-size:0.9rem;"></i>
                    <h3>Blacklisted Phones</h3>
                    <span class="count-chip">{{ count($blacklistedPhones ?? []) }}</span>
                </div>
                <div class="sub-card-body" style="max-height:260px;overflow-y:auto;">
                    @if(!empty($blacklistedPhones))
                        <table class="mini-table">
                            @foreach($blacklistedPhones as $phone)
                            <tr>
                                <td><code style="background:#f1f5f9;border-radius:5px;padding:2px 6px;font-size:0.78rem;color:#4338ca;font-weight:600;">{{ $phone }}</code></td>
                                <td style="text-align:right;">
                                    <button class="icon-btn ib-unlock" onclick="unblockPhone('{{ $phone }}')" title="Remove"><i class="fas fa-unlock"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    @else
                        <div style="text-align:center;padding:28px 0;color:#94a3b8;font-size:0.82rem;">
                            <i class="fas fa-check-circle" style="color:#16a34a;font-size:1.4rem;display:block;margin-bottom:8px;"></i>
                            No blacklisted phone numbers
                        </div>
                    @endif
                </div>
            </div>

            {{-- Blacklisted IPs --}}
            <div class="sub-card">
                <div class="sub-card-header">
                    <i class="fas fa-globe" style="color:#d97706;font-size:0.9rem;"></i>
                    <h3>Blacklisted IPs</h3>
                    <span class="count-chip">{{ count($blacklistedIps ?? []) }}</span>
                </div>
                <div class="sub-card-body" style="max-height:260px;overflow-y:auto;">
                    @if(!empty($blacklistedIps))
                        <table class="mini-table">
                            @foreach($blacklistedIps as $ip)
                            <tr>
                                <td><code style="background:#f1f5f9;border-radius:5px;padding:2px 6px;font-size:0.78rem;color:#4338ca;font-weight:600;">{{ $ip }}</code></td>
                                <td style="text-align:right;">
                                    <button class="icon-btn ib-unlock" onclick="unblockIp('{{ $ip }}')" title="Remove"><i class="fas fa-unlock"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    @else
                        <div style="text-align:center;padding:28px 0;color:#94a3b8;font-size:0.82rem;">
                            <i class="fas fa-check-circle" style="color:#16a34a;font-size:1.4rem;display:block;margin-bottom:8px;"></i>
                            No blacklisted IP addresses
                        </div>
                    @endif
                </div>
            </div>

        </div>
    @endif

</div>

<style>
    @media (max-width: 992px) { .offender-grid { grid-template-columns: 1fr !important; } }
</style>

<script>
    // ── Blacklist helpers ─────────────────────────
    function addToBlacklist(phone) {
        if (!confirm('Add ' + phone + ' to blacklist?')) return;
        fetch('{{ route('admin.fraud-protection.add-to-blacklist') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ phone })
        }).then(r => r.json()).then(d => { alert(d.success ? '✅ Added to blacklist!' : '❌ ' + d.message); if (d.success) location.reload(); });
    }

    function unblockPhone(phone) {
        if (!confirm('Remove ' + phone + ' from blacklist?')) return;
        fetch('{{ route('admin.fraud-protection.unblock-phone') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ phone })
        }).then(r => r.json()).then(d => { alert(d.success ? '✅ ' + d.message : '❌ ' + d.message); if (d.success) location.reload(); });
    }

    function unblockIp(ip) {
        if (!confirm('Remove ' + ip + ' from blacklist?')) return;
        fetch('{{ route('admin.fraud-protection.unblock-ip') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ ip })
        }).then(r => r.json()).then(d => { alert(d.success ? '✅ ' + d.message : '❌ ' + d.message); if (d.success) location.reload(); });
    }

    // ── Delete helpers ────────────────────────────
    function deleteSingleItem(id) {
        if (!confirm('Delete this entry? Cannot be undone.')) return;
        fetch('{{ route('admin.fraud-protection.delete-single') }}', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ id })
        }).then(r => r.json()).then(d => { alert(d.success ? '✅ Deleted!' : '❌ ' + d.message); if (d.success) location.reload(); });
    }

    // ── Bulk selection ────────────────────────────
    function toggleSelectAll() {
        const master = document.getElementById('selectAllCheckbox');
        document.querySelectorAll('.row-checkbox').forEach(b => b.checked = master.checked);
        updateBulkUI();
    }

    function deselectAll() {
        document.getElementById('selectAllCheckbox').checked = false;
        document.querySelectorAll('.row-checkbox').forEach(b => b.checked = false);
        updateBulkUI();
    }

    function updateBulkUI() {
        const checked  = document.querySelectorAll('.row-checkbox:checked');
        const total    = document.querySelectorAll('.row-checkbox').length;
        const btn      = document.getElementById('bulkDeleteBtn');
        const countEl  = document.getElementById('selectedCount');
        const selBtn   = document.getElementById('selectAllBtn');
        const deselBtn = document.getElementById('deselectAllBtn');
        btn.disabled   = checked.length === 0;
        countEl.textContent = checked.length > 0 ? checked.length + ' selected' : '';
        selBtn.style.display   = checked.length === total ? 'none' : 'inline-flex';
        deselBtn.style.display = checked.length > 0 ? 'inline-flex' : 'none';
    }

    function handleBulkDelete() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        if (!checked.length) return;
        if (!confirm(`Delete ${checked.length} selected entries?`)) return;
        const ids = [...checked].map(c => c.value);
        $.ajax({
            url: '{{ route('admin.fraud-protection.bulk-delete') }}',
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}', selected_ids: ids },
            success: d => { alert(d.success ? `✅ ${d.message}` : '❌ ' + d.message); if (d.success) location.reload(); },
            error: () => alert('❌ Server error')
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.row-checkbox').forEach(c => c.addEventListener('change', updateBulkUI));
    });
</script>
@endsection
