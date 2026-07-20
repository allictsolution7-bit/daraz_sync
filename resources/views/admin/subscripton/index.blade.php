@extends('layouts.master')

@section('title', 'Newsletter Subscriptions')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
<style>
    :root {
        --primary: #4f46e5;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark-slate: #1e293b;
        --text-main: #334155;
        --text-muted: #64748b;
        --bg-glass: rgba(255,255,255,0.9);
        --border-glass: rgba(226,232,240,0.8);
        --shadow-premium: 0 10px 30px -5px rgba(0,0,0,0.05), 0 4px 12px -2px rgba(0,0,0,0.03);
        --transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    }

    /* ── Stat Cards ─────────────────────────────────── */
    .stat-card {
        border-radius: 20px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-premium);
        transition: var(--transition);
        min-height: 130px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border: none;
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1); }
    .stat-card-icon { position:absolute; right:18px; bottom:14px; font-size:4rem; opacity:0.12; }
    .stat-card-title { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; opacity:0.85; }
    .stat-card-value { font-size:34px; font-weight:800; line-height:1; }
    .grad-purple  { background: linear-gradient(135deg,#4f46e5,#3b82f6); color:#fff; }
    .grad-green   { background: linear-gradient(135deg,#10b981,#059669); color:#fff; }
    .grad-amber   { background: linear-gradient(135deg,#f59e0b,#d97706); color:#fff; }
    .grad-rose    { background: linear-gradient(135deg,#f43f5e,#dc2626); color:#fff; }

    /* ── Workspace card ─────────────────────────────── */
    .workspace-card {
        background: var(--bg-glass);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        box-shadow: var(--shadow-premium);
        padding: 24px;
        margin-bottom: 40px;
    }

    /* ── Filter bar ─────────────────────────────────── */
    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 18px;
    }
    .filter-bar input[type=text],
    .filter-bar select {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 13px;
        padding: 7px 14px;
        outline: none;
        transition: border-color .2s;
    }
    .filter-bar input[type=text]:focus,
    .filter-bar select:focus { border-color: var(--primary); }
    .filter-bar .search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 280px; }
    .filter-bar .search-wrap i { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:12px; }
    .filter-bar .search-wrap input { padding-left: 32px; width:100%; }

    /* ── DataTable overrides ─────────────────────────── */
    table.subs-table { border-collapse: separate !important; border-spacing: 0 7px !important; width: 100% !important; }
    table.subs-table thead th {
        background-color: #f1f5f9 !important;
        color: #334155 !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        border: none !important;
        padding: 14px 16px !important;
    }
    table.dataTable thead .sorting,
    table.dataTable thead .sorting_asc,
    table.dataTable thead .sorting_desc {
        background-repeat: no-repeat !important;
        background-position: center right 8px !important;
    }
    table.subs-table tbody tr { background: #fff; transition: background .2s; }
    table.subs-table tbody tr:hover { background: #f8fafc !important; }
    table.subs-table tbody td {
        padding: 14px 16px !important;
        border-top: 1px solid #f1f5f9 !important;
        border-bottom: 1px solid #f1f5f9 !important;
        vertical-align: middle;
        font-size: 13.5px;
        color: var(--text-main);
    }
    table.subs-table tbody td:first-child { border-left:1px solid #f1f5f9 !important; border-radius:10px 0 0 10px; }
    table.subs-table tbody td:last-child  { border-right:1px solid #f1f5f9 !important; border-radius:0 10px 10px 0; }

    /* DT pagination / length / filter hide (we use our own) */
    .dataTables_length, .dataTables_filter { display:none !important; }
    .dataTables_wrapper .dataTables_info { font-size:12px; color:var(--text-muted); }
    .dataTables_wrapper .dataTables_paginate .paginate_button { border-radius:8px !important; border:none !important; font-size:13px; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: var(--primary) !important; color:#fff !important; }
    div.dt-buttons { margin-bottom: 0; }
    div.dt-buttons button { border-radius:10px !important; font-size:12px !important; border:1px solid #e2e8f0 !important; background:#fff !important; color:var(--text-muted) !important; padding:6px 14px !important; transition:var(--transition) !important; box-shadow:none !important; }
    div.dt-buttons button:hover { border-color:var(--primary) !important; color:var(--primary) !important; }

    /* ── Badges ─────────────────────────────────────── */
    .badge-active   { background:rgba(16,185,129,.1); color:#059669; padding:5px 12px; border-radius:8px; font-size:12px; font-weight:600; }
    .badge-inactive { background:rgba(239,68,68,.1);  color:#dc2626; padding:5px 12px; border-radius:8px; font-size:12px; font-weight:600; }

    /* ── Action circles ─────────────────────────────── */
    .btn-circle {
        width:34px; height:34px; border-radius:50%;
        display:inline-flex; align-items:center; justify-content:center;
        border:1px solid #e2e8f0; background:#fff; color:var(--text-muted);
        transition:var(--transition); font-size:13px;
    }
    .btn-circle:hover { color:#fff; transform:translateY(-2px); }
    .btn-circle.view:hover   { background:var(--primary); border-color:var(--primary); }
    .btn-circle.remove:hover { background:var(--danger);  border-color:var(--danger); }

    /* ── Bulk bar ───────────────────────────────────── */
    #bulkBar {
        background: rgba(79,70,229,0.06);
        border: 1px dashed #818cf8;
        border-radius: 12px;
        padding: 12px 18px;
        display: none;
        align-items: center;
        gap: 14px;
        margin-bottom: 16px;
        font-size: 13px;
        color: var(--primary);
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 pt-3">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active font-weight-bold" aria-current="page">Newsletter Subscriptions</li>
        </ol>
    </nav>

    <!-- Success alert -->
    @if(session('success'))
        <div class="alert border-0 rounded-3 mb-4" style="background:rgba(16,185,129,.1); color:#059669;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <!-- KPI Cards -->
    @php
        $total    = $subscriptions->count();
        $active   = $subscriptions->where('status', true)->count();
        $inactive = $subscriptions->where('status', false)->count();
        $today    = $subscriptions->filter(fn($s) => $s->created_at->isToday())->count();
    @endphp
    <div class="row mb-4 g-3">
        <div class="col-md-3 col-6">
            <div class="stat-card grad-purple">
                <div><div class="stat-card-title">Total Subscribers</div><div class="stat-card-value">{{ $total }}</div></div>
                <i class="fas fa-users stat-card-icon"></i>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card grad-green">
                <div><div class="stat-card-title">Active</div><div class="stat-card-value">{{ $active }}</div></div>
                <i class="fas fa-toggle-on stat-card-icon"></i>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card grad-amber">
                <div><div class="stat-card-title">Inactive</div><div class="stat-card-value">{{ $inactive }}</div></div>
                <i class="fas fa-toggle-off stat-card-icon"></i>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card grad-rose">
                <div><div class="stat-card-title">Joined Today</div><div class="stat-card-value">{{ $today }}</div></div>
                <i class="fas fa-calendar-day stat-card-icon"></i>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="workspace-card">

        <!-- Filter / Action Bar -->
        <div class="filter-bar">
            <div class="me-auto">
                <h5 class="mb-0 fw-bold" style="color:var(--dark-slate); font-size:15px;">Subscribers Directory</h5>
            </div>

            <!-- Live search -->
            <div class="search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" id="subSearch" placeholder="Search email, country, city…">
            </div>

            <!-- Status filter -->
            <select id="subStatusFilter">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <!-- Export buttons rendered by DT (injected by JS) -->
            <div id="dtExportBtns"></div>

            <!-- Refresh -->
            <button onclick="location.reload()" class="btn btn-sm" style="border-radius:10px; border:1px solid #e2e8f0; background:#fff; font-size:13px; color:var(--text-muted); padding:6px 14px;">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
        </div>

        <!-- Bulk action bar -->
        <div id="bulkBar">
            <i class="fas fa-check-square"></i>
            <span id="bulkCount">0</span> selected
            <form id="bulkDeleteForm" action="{{ route('admin.subscriptions.bulk-delete') }}" method="POST" class="d-inline ms-2">
                @csrf
                @method('DELETE')
                <div id="bulkHiddenInputs"></div>
                <button type="button" id="bulkDeleteBtn" class="btn btn-sm" style="border-radius:9px; background:rgba(239,68,68,.1); border:1px solid #fca5a5; color:#dc2626; font-size:13px; padding:5px 16px;">
                    <i class="fas fa-trash-alt me-1"></i> Delete Selected
                </button>
            </form>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table id="subscriptions-table" class="table subs-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll" style="width:15px;height:15px;cursor:pointer;"></th>
                        <th>#</th>
                        <th>Email Address</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Subscribed On</th>
                        <th>Status</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $i => $subscription)
                    <tr data-status="{{ $subscription->status ? 'active' : 'inactive' }}"
                        data-email="{{ strtolower($subscription->email) }}"
                        data-country="{{ strtolower($subscription->country ?? '') }}"
                        data-city="{{ strtolower($subscription->city ?? '') }}">
                        <td>
                            <input type="checkbox" class="sub-check" value="{{ $subscription->id }}" style="width:15px;height:15px;cursor:pointer;">
                        </td>
                        <td class="text-muted">{{ $i + 1 }}</td>
                        <td><strong>{{ $subscription->email }}</strong></td>
                        <td>{{ $subscription->country ?? '—' }}</td>
                        <td>{{ $subscription->city ?? '—' }}</td>
                        <td data-order="{{ $subscription->created_at->timestamp }}">
                            {{ $subscription->created_at->format('M d, Y H:i') }}
                        </td>
                        <td>
                            <form action="{{ route('admin.subscriptions.toggle-status', $subscription) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="{{ $subscription->status ? 'badge-active' : 'badge-inactive' }}" style="border:none; cursor:pointer;">
                                    <i class="fas {{ $subscription->status ? 'fa-toggle-on' : 'fa-toggle-off' }} me-1"></i>
                                    {{ $subscription->status ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="btn-circle view" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" class="d-inline m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-circle remove" title="Delete" onclick="return confirm('Delete this subscription?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-users fa-3x mb-3" style="opacity:.2;"></i>
                            <h6>No subscriptions found</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script>
$(document).ready(function () {

    // ── DataTable ───────────────────────────────────────────────────────────
    var dt = $('#subscriptions-table').DataTable({
        dom: 'Brtip',
        buttons: [
            { extend: 'csv',   text: '<i class="fas fa-file-csv me-1"></i> CSV',   exportOptions: { columns: [1,2,3,4,5,6] } },
            { extend: 'print', text: '<i class="fas fa-print me-1"></i> Print', exportOptions: { columns: [1,2,3,4,5,6] } }
        ],
        pageLength: 25,
        order: [[5, 'desc']],
        columnDefs: [
            { orderable: false, targets: [0, 7] }
        ]
    });

    // Move export buttons into our filter bar
    dt.buttons().container().appendTo('#dtExportBtns');

    // ── Live search & status filter ─────────────────────────────────────────
    function doFilter() {
        var q      = $('#subSearch').val().toLowerCase().trim();
        var status = $('#subStatusFilter').val();

        dt.rows().every(function () {
            var row  = this.node();
            var email   = $(row).data('email') || '';
            var country = $(row).data('country') || '';
            var city    = $(row).data('city') || '';
            var rowSt   = $(row).data('status') || '';

            var matchQ  = !q || email.includes(q) || country.includes(q) || city.includes(q);
            var matchSt = !status || rowSt === status;

            $(row).toggle(matchQ && matchSt);
        });
    }

    $('#subSearch').on('input', doFilter);
    $('#subStatusFilter').on('change', doFilter);

    // ── Bulk selection ──────────────────────────────────────────────────────
    function updateBulkBar() {
        var selected = $('.sub-check:checked');
        var count = selected.length;
        if (count > 0) {
            $('#bulkCount').text(count);
            $('#bulkBar').css('display', 'flex');
        } else {
            $('#bulkBar').hide();
        }
    }

    $('#selectAll').on('change', function () {
        $('.sub-check').prop('checked', this.checked);
        updateBulkBar();
    });

    $(document).on('change', '.sub-check', function () {
        updateBulkBar();
        if (!this.checked) $('#selectAll').prop('checked', false);
        else if ($('.sub-check:checked').length === $('.sub-check').length) $('#selectAll').prop('checked', true);
    });

    $('#bulkDeleteBtn').on('click', function () {
        var ids = [];
        $('.sub-check:checked').each(function () { ids.push($(this).val()); });
        if (!ids.length) return;
        if (!confirm('Delete ' + ids.length + ' selected subscriptions? This cannot be undone.')) return;

        $('#bulkHiddenInputs').empty();
        ids.forEach(function (id) {
            $('#bulkHiddenInputs').append('<input type="hidden" name="selected_ids[]" value="' + id + '">');
        });
        $('#bulkDeleteForm').submit();
    });

    // Auto-hide flash alerts
    setTimeout(function () { $('.alert').fadeOut('slow'); }, 5000);
});
</script>
@endpush
