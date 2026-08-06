@extends('layouts.master')

@section('title', 'Reseller Orders')

@section('styles')
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
<style>
    .reseller-orders-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
        border-radius: 16px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 8px 32px rgba(99,102,241,0.3);
    }
    .reseller-orders-header h2 { font-size: 1.6rem; font-weight: 700; margin: 0; }
    .reseller-orders-header p { opacity: 0.85; margin: 4px 0 0; font-size: 0.92rem; }
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 16px 20px;
        border: 1px solid rgba(99,102,241,0.15);
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: all .2s ease;
        cursor: pointer;
        text-decoration: none;
        display: block;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99,102,241,0.15); text-decoration: none; }
    .stat-card.active { border-color: #6366f1; background: linear-gradient(135deg,#eef2ff,#f5f3ff); }
    .stat-card .stat-num { font-size: 1.6rem; font-weight: 800; color: #1e1b4b; }
    .stat-card .stat-label { font-size: 0.78rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-card .stat-icon { font-size: 1.8rem; opacity: 0.15; }
    .filter-card {
        background: #fff;
        border-radius: 14px;
        padding: 20px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-bottom: 20px;
    }
    .table-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 16px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.06);
    }
    #resellerOrdersTable td, #resellerOrdersTable th { vertical-align: middle; font-size: 13px; padding: 10px 14px; }
    #resellerOrdersTable thead tr { background: linear-gradient(135deg,#6366f1,#8b5cf6); }
    #resellerOrdersTable thead th { color: #fff; font-weight: 600; border: none; font-size: 12px; text-transform: uppercase; letter-spacing: 0.4px; }
    .badge { font-size: 11px; padding: 4px 10px; }
    .reseller-order-status-change { border-radius: 8px; }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="reseller-orders-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-people-arrows me-2"></i>Reseller Orders</h2>
                <p>All orders placed by your resellers through the Reseller POS</p>
            </div>
            <div class="text-end">
                <span class="badge bg-white text-primary fw-bold px-3 py-2" style="font-size:1rem;">
                    {{ number_format($totalCount) }} Total
                </span>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-lg-2">
            <a href="#" class="stat-card active" id="filterAll" onclick="filterByStatus('', this)">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-num">{{ $statusCounts['all'] }}</div>
                        <div class="stat-label">All Orders</div>
                    </div>
                    <i class="fas fa-list stat-icon text-primary"></i>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="#" class="stat-card" id="filterPending" onclick="filterByStatus('pending', this)">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-num text-warning">{{ $statusCounts['pending'] }}</div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <i class="fas fa-clock stat-icon text-warning"></i>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="#" class="stat-card" id="filterProcessing" onclick="filterByStatus('processing', this)">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-num text-info">{{ $statusCounts['processing'] }}</div>
                        <div class="stat-label">Processing</div>
                    </div>
                    <i class="fas fa-spinner stat-icon text-info"></i>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="#" class="stat-card" id="filterDelivered" onclick="filterByStatus('delivered', this)">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-num text-success">{{ $statusCounts['delivered'] }}</div>
                        <div class="stat-label">Delivered</div>
                    </div>
                    <i class="fas fa-check-circle stat-icon text-success"></i>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="#" class="stat-card" id="filterCancelled" onclick="filterByStatus('cancelled', this)">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="stat-num text-danger">{{ $statusCounts['cancelled'] }}</div>
                        <div class="stat-label">Cancelled</div>
                    </div>
                    <i class="fas fa-times-circle stat-icon text-danger"></i>
                </div>
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-card">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold small text-muted">Filter by Reseller</label>
                <select id="filterReseller" class="form-select form-select-sm">
                    <option value="">All Resellers</option>
                    @foreach($resellers as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small text-muted">From Date</label>
                <input type="date" id="filterDateFrom" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold small text-muted">To Date</label>
                <input type="date" id="filterDateTo" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary btn-sm w-100" onclick="applyFilters()">
                    <i class="fas fa-filter me-1"></i>Apply
                </button>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary btn-sm w-100" onclick="resetFilters()">
                    <i class="fas fa-times me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <div class="table-responsive">
            <table id="resellerOrdersTable" class="table table-hover w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reseller</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
let currentStatus = '';
let table;

$(function() {
    table = $('#resellerOrdersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("admin.reseller-orders.data") }}',
            data: function(d) {
                d.status      = currentStatus;
                d.reseller_id = $('#filterReseller').val();
                d.date_from   = $('#filterDateFrom').val();
                d.date_to     = $('#filterDateTo').val();
            }
        },
        columns: [
            { data: 'id', name: 'id', width: '50px' },
            { data: 'reseller_info', name: 'reseller_info', orderable: false },
            { data: 'customer_info', name: 'name', orderable: false },
            { data: 'items_info', name: 'items_info', orderable: false },
            { data: 'total_info', name: 'total', orderable: false },
            { data: 'status_badge', name: 'status', orderable: true },
            { data: 'created_date', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false },
        ],
        order: [[6, 'desc']],
        pageLength: 25,
        language: {
            processing: '<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="text-muted mt-2">Loading reseller orders...</p></div>',
            emptyTable: '<div class="text-center py-5"><i class="fas fa-people-arrows fa-3x text-muted opacity-50 mb-3 d-block"></i><h6 class="text-muted">No reseller orders found</h6><p class="text-muted small">Reseller POS orders will appear here once resellers start placing orders.</p></div>',
            zeroRecords: '<div class="text-center py-4"><i class="fas fa-search fa-2x text-muted mb-2 d-block"></i><p class="text-muted">No orders match your filters.</p></div>'
        }
    });

    // Status change inline
    $(document).on('change', '.reseller-order-status-change', function() {
        const orderId = $(this).data('order-id');
        const newStatus = $(this).val();
        const $sel = $(this);
        $.ajax({
            url: '/admin/reseller-orders/' + orderId + '/status',
            method: 'POST',
            data: { status: newStatus, _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.success) {
                    toastr.success('Status updated to: ' + newStatus.replace(/_/g,' '));
                    table.ajax.reload(null, false);
                }
            },
            error: function() {
                toastr.error('Failed to update status.');
            }
        });
    });
});

function filterByStatus(status, el) {
    currentStatus = status;
    $('.stat-card').removeClass('active');
    $(el).addClass('active');
    table.ajax.reload();
    return false;
}

function applyFilters() {
    table.ajax.reload();
}

function resetFilters() {
    currentStatus = '';
    $('#filterReseller').val('');
    $('#filterDateFrom').val('');
    $('#filterDateTo').val('');
    $('.stat-card').removeClass('active');
    $('#filterAll').addClass('active');
    table.ajax.reload();
}
</script>
@endsection
