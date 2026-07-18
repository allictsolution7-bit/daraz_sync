@extends('layouts.master')

@section('styles')
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/1.7.0/css/colReorder.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colvis/1.7.0/css/colvis.dataTables.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    :root {
        --primary-color: #197A94;
        --primary-hover: #136377;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --warning-color: #ffc107;
        --info-color: #17a2b8;
        --dark-color: #343a40;
        --light-bg: #fdfdfd;
        --card-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
        --border-radius: 12px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        background-color: #f4f7f6;
    }

    .container-fluid {
        padding: 24px;
    }

    h5 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 8px;
    }

    hr {
        border-top: 1px solid #e3e6f0;
        margin-bottom: 24px;
    }

    .modal-header {
        justify-content: space-between;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        color: white;
        border-top-left-radius: var(--border-radius);
        border-top-right-radius: var(--border-radius);
        padding: 16px 24px;
    }

    .modal-header .modal-title {
        font-weight: 600;
        font-size: 1.15rem;
    }

    .modal-header button.close {
        font-size: 24px;
        color: white;
        opacity: 0.8;
        background: transparent;
        border: none;
        outline: none;
        transition: var(--transition);
        display: block !important;
    }

    .modal-header button.close:hover {
        opacity: 1;
        transform: scale(1.1);
    }

    .modal-content {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    /* Filter Section Styles */
    .filter-section {
        background: #ffffff;
        border: 1px solid #e3e8ec;
        border-radius: var(--border-radius);
        padding: 14px 18px;
        margin-bottom: 20px;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
    }

    .filter-section:hover {
        box-shadow: 0 6px 24px 0 rgba(0, 0, 0, 0.08);
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        align-items: center;
    }

    .filter-row-basic {
        margin-bottom: 0;
    }

    .filter-row-advanced {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px dashed #e2e8f0;
        display: none; /* Collapsed by default */
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-weight: 600;
        font-size: 0.78rem;
        margin-bottom: 4px;
        color: #5a6a85;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        height: 36px;
        padding: 6px 12px;
        font-size: 0.85rem;
        border-radius: 6px;
        border: 1px solid #d1d9e2;
        background-color: #fff;
        color: #495057;
        box-sizing: border-box;
        transition: var(--transition);
        outline: none;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(25, 122, 148, 0.12);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        align-items: center;
        height: 36px;
    }

    .btn-filter {
        height: 36px;
        padding: 0 14px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.82rem;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: var(--transition);
    }

    .btn-apply {
        background: var(--primary-color);
        color: white;
    }

    .btn-apply:hover {
        background: var(--primary-hover);
    }

    .btn-clear {
        background: #f1f3f5;
        color: #495057;
    }

    .btn-clear:hover {
        background: #e9ecef;
        color: #212529;
    }

    .btn-toggle-advanced {
        background: transparent;
        color: var(--primary-color);
        border: 1px solid var(--primary-color);
    }
    .btn-toggle-advanced:hover {
        background: rgba(25, 122, 148, 0.05);
    }
    .btn-toggle-advanced.active {
        background: var(--primary-color);
        color: white;
    }

    /* Action Buttons Area */
    .mb-3 {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px !important;
    }

    .mb-3 .btn {
        padding: 8px 14px;
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: var(--transition);
        border: 1px solid transparent;
    }
    
    .mb-3 .btn i {
        font-size: 0.85rem;
    }

    .mb-3 .btn-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
        border-color: rgba(220, 53, 69, 0.2);
    }
    .mb-3 .btn-danger:hover {
        background-color: var(--danger-color);
        color: white;
        transform: translateY(-1px);
    }

    .mb-3 .btn-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
        border-color: rgba(40, 167, 69, 0.2);
    }
    .mb-3 .btn-success:hover {
        background-color: var(--success-color);
        color: white;
        transform: translateY(-1px);
    }

    .mb-3 .btn-outline-primary {
        background-color: transparent;
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .mb-3 .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateY(-1px);
    }

    .mb-3 .btn-outline-warning {
        background-color: transparent;
        color: #e0a800;
        border-color: #ffc107;
    }
    .mb-3 .btn-outline-warning:hover {
        background-color: #ffc107;
        color: #212529;
        transform: translateY(-1px);
    }
    .mb-3 .btn-warning {
        background-color: #ffc107;
        color: #212529;
        border-color: #ffc107;
    }
    .mb-3 .btn-warning:hover {
        background-color: #e0a800;
        transform: translateY(-1px);
    }

    /* Datatable Modern Card Styling */
    table#incomorders {
        background: #ffffff;
        border: 1px solid #e3e8ec;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border-collapse: separate;
        border-spacing: 0;
    }

    table#incomorders thead th {
        background-color: #f8fafc;
        color: #5a6a85;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e3e8ec;
        padding: 16px;
    }

    table#incomorders tbody td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f5;
        border-top: none;
        color: #495057;
        transition: var(--transition);
    }

    /* Restrict Product column width and enable text wrapping */
    table#incomorders th:nth-child(2),
    table#incomorders td:nth-child(2) {
        max-width: 220px;
        width: 220px !important;
        white-space: normal !important;
        word-wrap: break-word;
        word-break: break-word;
    }

    /* Clean Borders based on Status instead of full rows */
    table#incomorders tbody tr td:first-child {
        border-left: 5px solid transparent;
        transition: var(--transition);
    }
    
    table#incomorders tbody tr.status-pending td:first-child {
        border-left-color: var(--warning-color);
    }
    table#incomorders tbody tr.status-contacted td:first-child {
        border-left-color: var(--info-color);
    }
    table#incomorders tbody tr.status-follow_up td:first-child {
        border-left-color: #fd7e14;
    }
    table#incomorders tbody tr.status-converted td:first-child {
        border-left-color: var(--success-color);
    }
    table#incomorders tbody tr.status-cancelled td:first-child {
        border-left-color: var(--danger-color);
    }
    table#incomorders tbody tr.status-spam td:first-child {
        border-left-color: var(--secondary-color);
    }

    table#incomorders tbody tr:hover td {
        background-color: #f8fafd;
    }

    /* Customer Info Styles */
    .customer-info {
        max-width: 300px;
    }

    .customer-name {
        font-weight: 700;
        color: var(--dark-color);
        font-size: 0.95rem;
        margin-bottom: 4px;
    }

    .customer-phone a {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 0.88rem;
        transition: var(--transition);
    }

    .customer-phone a:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    /* Actions buttons in Datatable */
    .view-incomplete-order,
    .delete-incomplete-order {
        width: 32px;
        height: 32px;
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        border-radius: 6px !important;
        font-size: 0.85rem !important;
    }

    /* DataTables Button Styles */
    .dt-buttons {
        margin-bottom: 20px;
        display: inline-flex;
        gap: 8px;
    }

    .dt-button {
        background: #f1f3f5 !important;
        color: #495057 !important;
        border: 1px solid #d1d9e2 !important;
        padding: 8px 16px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        transition: var(--transition) !important;
        box-shadow: none !important;
    }

    .dt-button:hover {
        background: var(--primary-color) !important;
        color: white !important;
        border-color: var(--primary-color) !important;
        transform: translateY(-1px);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filter-row {
            grid-template-columns: 1fr;
        }
    }

    /* DataTables bottom spacing */
    .bottom {
        margin-top: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .dataTables_length select {
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #d1d9e2;
        outline: none;
    }

    /* Fraud Check Styles */
    .fraud-check-info {
        text-align: center;
        line-height: 1.3;
    }

    .fraud-risk-badge {
        display: block;
        margin-bottom: 6px;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 12px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .fraud-score {
        font-weight: 700;
        font-size: 13px;
        color: var(--dark-color);
        margin-top: 2px;
    }

    .fraud-success-rate {
        font-size: 12px;
        color: var(--success-color);
        margin-bottom: 2px;
        font-weight: 600;
    }

    .check-fraud-btn {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 6px;
    }

    .fraud-check-loading {
        text-align: center;
        color: #666;
    }

    .fraud-check-loading i {
        margin-right: 4px;
    }

    .fraud-check-missing {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }

    .check-fraud-cache-btn {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 6px;
        border-color: #dee2e6;
        color: #6c757d;
        background-color: transparent;
        transition: var(--transition);
    }
    .check-fraud-cache-btn:hover {
        background-color: #f1f3f5;
        color: #212529;
    }

    /* Status Badge & Dropdown Styles */
    .status-badge {
        font-size: 11px;
        padding: 6px 10px;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background-color: rgba(255, 193, 7, 0.15) !important;
        color: #856404 !important;
    }

    .status-contacted {
        background-color: rgba(23, 162, 184, 0.15) !important;
        color: #0f5132 !important;
    }

    .status-follow_up {
        background-color: rgba(253, 126, 20, 0.15) !important;
        color: #9c4c06 !important;
    }

    .status-converted {
        background-color: rgba(40, 167, 69, 0.15) !important;
        color: #155724 !important;
    }

    .status-cancelled {
        background-color: rgba(220, 53, 69, 0.15) !important;
        color: #721c24 !important;
    }

    .status-spam {
        background-color: rgba(108, 117, 125, 0.15) !important;
        color: #383d41 !important;
    }

    /* Note Styles */
    .admin-note-cell {
        max-width: 200px;
        position: relative;
    }

    .admin-note-text {
        max-height: 60px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 6px;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 12px;
        line-height: 1.4;
        transition: var(--transition);
    }

    .admin-note-text:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
    }

    .admin-note-text.empty {
        color: #94a3b8;
        font-style: italic;
    }

    .note-edit-btn {
        position: absolute;
        top: 2px;
        right: 2px;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 4px;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .admin-note-cell:hover .note-edit-btn {
        opacity: 1;
    }

    /* Status Dropdown Styles */
    .status-dropdown {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background-color: #fff;
        cursor: pointer;
        font-weight: 600;
        transition: var(--transition);
        outline: none;
    }

    .status-dropdown:hover {
        border-color: var(--primary-color);
    }

    .status-dropdown:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(25, 122, 148, 0.15);
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <h5>Incomplete Orders</h5>
    <hr>
    <!-- Advanced Filter Section -->
    <div class="filter-section">
        <!-- Basic Filters (Always Visible) -->
        <div class="filter-row filter-row-basic">
            <div class="filter-group">
                <input type="text" id="filter-name" class="form-control" placeholder="Search by name...">
            </div>
            <div class="filter-group">
                <input type="text" id="filter-phone" class="form-control" placeholder="Search by phone...">
            </div>
            <div class="filter-group">
                <select id="filter-status" class="form-control">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="contacted">Contacted</option>
                    <option value="follow_up">Follow Up</option>
                    <option value="converted">Converted</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="spam">Spam</option>
                </select>
            </div>
            <div class="filter-actions">
                <button id="apply-filters" class="btn-filter btn-apply">
                    <i class="fas fa-search"></i> Apply
                </button>
                <button id="clear-filters" class="btn-filter btn-clear">
                    <i class="fas fa-times"></i> Clear
                </button>
                <button id="toggle-advanced-filters" class="btn-filter btn-toggle-advanced">
                    <i class="fas fa-sliders-h"></i> Filters
                </button>
            </div>
        </div>

        <!-- Advanced Filters (Collapsible) -->
        <div id="advanced-filters-panel" style="display: none; margin-top: 12px; padding-top: 12px; border-top: 1px dashed #e2e8f0;">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Source</label>
                    <select id="filter-source" class="form-control">
                        <option value="">All Sources</option>
                        <option value="buynow">Buy Now</option>
                        <option value="checkout">Checkout</option>
                        <option value="landing">Landing</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Payment Method</label>
                    <select id="filter-payment" class="form-control">
                        <option value="">All Payment Methods</option>
                        <option value="cod">Cash on Delivery</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Min Amount</label>
                    <input type="number" id="filter-total-min" class="form-control" placeholder="Min...">
                </div>
                <div class="filter-group">
                    <label>Max Amount</label>
                    <input type="number" id="filter-total-max" class="form-control" placeholder="Max...">
                </div>
                <div class="filter-group">
                    <label>Date From</label>
                    <input type="date" id="filter-date-from" class="form-control">
                </div>
                <div class="filter-group">
                    <label>Date To</label>
                    <input type="date" id="filter-date-to" class="form-control">
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-3">

        @can('incomplete_orders.bulk_delete')
        <button id="delete-selected" class="btn btn-danger">
            <i class="fas fa-trash"></i> Delete Selected
        </button>
        @endcan
        @can('incomplete_orders.export_selected')
        <button id="export-selected" class="btn btn-success">
            <i class="fas fa-download"></i> Export Selected
        </button>
        @endcan

        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-list"></i> All Orders
        </a>
        <a href="{{ route('admin.asigned.orders') }}" class="btn btn-outline-primary">
            <i class="fas fa-user"></i> My Orders
        </a>
        <button id="filter-has-orders" type="button" class="btn btn-outline-warning">
            <i class="fas fa-link"></i> <span class="filter-label">Has Orders</span>
        </button>
    </div>

    <!-- DataTable -->
    <div class="table-responsive" style="width: 100%; overflow-x: auto;">
        <table class="table table-bordered" id="incomorders" style="width:100%">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="select-all" style="margin-right: 8px;">
                        Customer Info
                    </th>
                    <th>Product(s)</th>
                    <th>Payment / Source</th>
                    <th>Status</th>
                    <th>Admin Note</th>
                    <th>Fraud Check</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="incompleteOrderModal" tabindex="-1" role="dialog"
    aria-labelledby="incompleteOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="incompleteOrderModalLabel">Incomplete Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="incomplete-order-details"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/1.7.0/js/dataTables.colReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/colvis/1.7.0/js/dataTables.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    // Permission flags for client-side conditional UI rendering
        const CAN_UPDATE_STATUS = @json(auth()->user()->can('incomplete_orders.update_status'));
        const CAN_UPDATE_NOTE = @json(auth()->user()->can('incomplete_orders.update_note'));
        const CAN_CONVERT = @json(auth()->user()->can('incomplete_orders.convert_page'));
        const CAN_DELETE = @json(auth()->user()->can('incomplete_orders.delete'));
    $(document).ready(function() {
        // Store selected IDs across pages
        let selectedIds = new Set();
        let hasOrderFilter = false;

        function updateHasOrderButton() {
            const button = $('#filter-has-orders');
            if (!button.length) {
                return;
            }
            if (hasOrderFilter) {
                button.removeClass('btn-outline-warning').addClass('btn-warning');
                button.find('.filter-label').text('Has Orders (On)');
            } else {
                button.removeClass('btn-warning').addClass('btn-outline-warning');
                button.find('.filter-label').text('Has Orders');
            }
        }

        // Initialize DataTable
        var table = $('#incomorders').DataTable({
            serverSide: true,
            ajax: {
                url: '{{ route("admin.incomplete-orders.data") }}',
                data: function(d) {
                    // Add custom filters
                    d.name = $('#filter-name').val();
                    d.phone = $('#filter-phone').val();
                    d.source = $('#filter-source').val();
                    d.status = $('#filter-status').val();
                    d.payment_method = $('#filter-payment').val();
                    d.total_min = $('#filter-total-min').val();
                    d.total_max = $('#filter-total-max').val();
                    d.date_from = $('#filter-date-from').val();
                    d.date_to = $('#filter-date-to').val();
                    d.has_order = hasOrderFilter ? 1 : 0;
                }
            },
                order: [[0, 'desc']],
                columns: [
                    {
                    data: 'created_at',
                    name: 'created_at',
                    render: function(data, type, row) {
                        let createdDate = new Date(row.created_at);
                        let dateStr = createdDate.toLocaleDateString();
                            let timeStr = createdDate.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});

                        // Build action buttons
                        let actions = '<button class="btn btn-primary btn-sm view-incomplete-order" data-id="' + row.id + '">' +
                            '<i class="fas fa-eye"></i></button> ';
                        if (CAN_CONVERT) {
                            actions += '<a href="/admin/incomplete-orders/' + row.id + '/convert" target="_blank" class="btn btn-success btn-sm">' +
                                '<i class="fas fa-exchange-alt"></i></a> ';
                        }
                        if (CAN_DELETE) {
                            actions += '<button class="btn btn-danger btn-sm delete-incomplete-order" data-id="' + row.id + '">' +
                                '<i class="fas fa-trash"></i></button>';
                        }

                        return '<div style="display: flex; align-items: flex-start; gap: 8px;">' +
                            '<input type="checkbox" class="order-checkbox" value="' + row.id + '" style="margin-top: 4px;">' +
                            '<div class="customer-info" style="flex: 1;">' +
                            '<div class="customer-name">' + (row.name || '-') + '</div>' +
                            '<div class="customer-phone"><a href="tel:' + (row.phone || '') + '" class="text-decoration-none">' + (row.phone || '-') + '</a></div>' +
                            '<div class="text-muted" style="font-size: 0.85em; margin-top: 4px;"><i class="far fa-calendar-alt"></i> ' + dateStr + ' <i class="far fa-clock"></i> ' + timeStr + '</div>' +
                            '<div class="mt-2" style="display: flex; gap: 4px;">' + actions + '</div>' +
                            '</div>' +
                            '</div>';
                    }
                },
                {
                    data: 'product_details',
                    render: function(data, type, row) {
                        if (!data) return '<span class="text-muted">-</span>';

                        let products = data;
                        if (typeof products === 'string') {
                            try {
                                products = JSON.parse(products);
                            } catch (e) {
                                return '<span class="text-muted">-</span>';
                            }
                        }

                        if (!Array.isArray(products) || products.length === 0) {
                            return '<span class="text-muted">-</span>';
                        }

                        let html = '<ul style="padding-left: 15px; margin: 0;">';
                        products.forEach(function(product) {
                            let productName = product.name || '';
                            let displayName = productName.length > 20 ? productName.substring(0, 20) + '...' : productName;
                            html += '<li><strong title="' + productName + '">' + displayName + '</strong>';
                            if (product.is_combo) {
                                html += ' <span class="badge badge-success">Combo</span>';
                            }
                            if (product.quantity) {
                                html += ' <span class="text-muted">(x' + product.quantity + ')</span>';
                            }

                            // Add variations/selections
                            let variations = '';
                            if (product.variations && Array.isArray(product.variations) && product.variations.length > 0) {
                                variations = product.variations.join(', ');
                            } else if (product.variations && typeof product.variations === 'string') {
                                variations = product.variations;
                            } else if (product.selections && Array.isArray(product.selections) && product.selections.length > 0) {
                                variations = product.selections.join(', ');
                            } else if (product.selections && typeof product.selections === 'string') {
                                variations = product.selections;
                            }

                            if (variations) {
                                html += '<br><small class="text-info">' + variations + '</small>';
                            }

                            html += '</li>';
                        });
                        html += '</ul>';

                        // Add Total at the bottom
                        let total = '-';
                        if (row.total && row.total > 0) {
                            total = '৳' + parseFloat(row.total).toFixed(2);
                        }
                        html += '<div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #dee2e6;"><strong>Total:</strong> ' + total + '</div>';

                        return html;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        let html = '<div style="line-height: 1.5;">';

                        // Payment Method
                        let paymentMethod = row.payment_method || '-';
                        if (paymentMethod !== '-') {
                            paymentMethod = paymentMethod.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        }
                        html += '<div><strong>Payment:</strong> ' + paymentMethod + '</div>';

                        // Source
                        let source = row.source || '-';
                        html += '<div><strong>Source:</strong> ' + source + '</div>';

                        html += '</div>';
                        return html;
                    }
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        const status = data || 'pending';
                        const statusText = status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        if (!CAN_UPDATE_STATUS) {
                            return '<span class="badge status-badge status-' + status + '">' + statusText + '</span>';
                        }
                        return '<select class="status-dropdown" data-order-id="' + row.id + '" data-current-status="' + status + '">' +
                            '<option value="pending"' + (status === 'pending' ? ' selected' : '') + '>Pending</option>' +
                            '<option value="contacted"' + (status === 'contacted' ? ' selected' : '') + '>Contacted</option>' +
                            '<option value="follow_up"' + (status === 'follow_up' ? ' selected' : '') + '>Follow Up</option>' +
                            '<option value="converted"' + (status === 'converted' ? ' selected' : '') + '>Converted</option>' +
                            '<option value="cancelled"' + (status === 'cancelled' ? ' selected' : '') + '>Cancelled</option>' +
                            '<option value="spam"' + (status === 'spam' ? ' selected' : '') + '>Spam</option>' +
                            '</select>';
                    }
                },
                {
                    data: 'admin_note',
                    render: function(data, type, row) {
                        const note = data || '';
                        const noteClass = note ? '' : 'empty';
                        const noteText = note || 'Click to add note...';
                        if (!CAN_UPDATE_NOTE) {
                            return '<div class="admin-note-cell">' +
                                '<div class="admin-note-text ' + noteClass + '" data-order-id="' + row.id + '" title="' + (note || 'Note') + '">' + noteText + '</div>' +
                                '</div>';
                        }
                        return '<div class="admin-note-cell">' +
                            '<div class="admin-note-text ' + noteClass + '" data-order-id="' + row.id + '" title="' + (note || 'Click to add note') + '">' + noteText + '</div>' +
                            '<button class="btn btn-sm btn-outline-secondary note-edit-btn" data-order-id="' + row.id + '" title="Edit note">' +
                            '<i class="fas fa-edit"></i></button>' +
                            '</div>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        // Check if fraud check data exists
                        if (row.fraud_check_result && row.fraud_check_result.risk_level) {
                            const result = row.fraud_check_result;
                            const riskLevelClass = getRiskLevelClass(result.risk_level);
                            const riskLevelDisplay = getRiskLevelDisplay(result.risk_level);

                            return '<div class="fraud-check-info">' +
                                '<span class="badge ' + riskLevelClass + ' fraud-risk-badge">' +
                                riskLevelDisplay +
                                '</span>' +
                                '<div class="fraud-score">' + result.risk_score + '/100</div>' +
                                '<div class="fraud-success-rate">' + (result.has_courier_history ? result.delivery_success_rate + '% Success' : '0% (New Customer)') + '</div>' +
                                '<small class="text-muted">(' + result.last_checked_at + ')</small>' +
                                '</div>';
                        } else {
                            return '<div class="fraud-check-missing">' +
                                '<small class="text-muted">No data</small>' +
                                '<button class="btn btn-sm btn-outline-secondary check-fraud-cache-btn" data-order-id="' + row.id + '" data-phone="' + row.phone + '" title="Check from cache">' +
                                '<i class="fas fa-database"></i>' +
                                '</button>' +
                                '</div>';
                        }
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'pdf', 'csv', 'excel', 'print'
            ],
            responsive: false,
            colReorder: true,
            pageLength: 25,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            rowCallback: function(row, data) {
                // Remove all status classes first
                $(row).removeClass('status-pending status-contacted status-follow_up status-converted status-cancelled status-spam');
                // Add the current status class
                if (data.status) {
                    $(row).addClass('status-' + data.status);
                }
            }
        });

        // Filter functionality
        var customFilters = {};

        function applyFilters() {
            customFilters = {
                name: $('#filter-name').val(),
                phone: $('#filter-phone').val(),
                source: $('#filter-source').val(),
                status: $('#filter-status').val(),
                payment_method: $('#filter-payment').val(),
                total_min: $('#filter-total-min').val(),
                total_max: $('#filter-total-max').val(),
                date_from: $('#filter-date-from').val(),
                date_to: $('#filter-date-to').val()
            };
            table.ajax.reload();
        }

        $('#apply-filters').on('click', applyFilters);

        $('#toggle-advanced-filters').on('click', function() {
            $(this).toggleClass('active');
            $('#advanced-filters-panel').slideToggle(200);
        });

        $('#clear-filters').on('click', function() {
            $('#filter-name, #filter-phone, #filter-source, #filter-status').val('');
            $('#filter-payment').val('');
            $('#filter-total-min, #filter-total-max').val('');
            $('#filter-date-from, #filter-date-to').val('');
            customFilters = {};
            hasOrderFilter = false;
            updateHasOrderButton();
            table.ajax.reload();
        });

        $('#filter-has-orders').on('click', function() {
            hasOrderFilter = !hasOrderFilter;
            updateHasOrderButton();
            table.ajax.reload();
        });

        updateHasOrderButton();

        // Select/Deselect all checkboxes
        $('#select-all').on('click', function() {
            var isChecked = this.checked;
            $('.order-checkbox').prop('checked', isChecked);

            if (isChecked) {
                $('.order-checkbox:checked').each(function() {
                    selectedIds.add($(this).val());
                });
            } else {
                selectedIds.clear();
            }
        });

        // Individual checkbox handling
        $(document).on('change', '.order-checkbox', function() {
            var id = $(this).val();
            if (this.checked) {
                selectedIds.add(id);
            } else {
                selectedIds.delete(id);
            }

            // Update select all checkbox
            var totalCheckboxes = $('.order-checkbox').length;
            var checkedCheckboxes = $('.order-checkbox:checked').length;
            $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
        });

        // Bulk delete handler
        $('#delete-selected').on('click', function() {
            var ids = Array.from(selectedIds);

            if (ids.length === 0) {
                alert('Please select at least one order to delete.');
                return;
            }

            if (confirm('Are you sure you want to delete ' + ids.length + ' selected incomplete order(s)?')) {
                $.ajax({
                    url: '{{ route("admin.incomplete-orders.bulk-delete") }}',
                    type: 'POST',
                    data: {
                        ids: ids,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        selectedIds.clear();
                        table.ajax.reload();
                        alert('Successfully deleted ' + ids.length + ' incomplete order(s).');
                    },
                    error: function() {
                        alert('Failed to delete. Please try again.');
                    }
                });
            }
        });

        // Export selected handler
        $('#export-selected').on('click', function() {
            var ids = Array.from(selectedIds);

            if (ids.length === 0) {
                alert('Please select at least one order to export.');
                return;
            }

            $.ajax({
                url: '{{ route("admin.incomplete-orders.export-selected") }}',
                type: 'POST',
                data: {
                    ids: ids,
                    _token: '{{ csrf_token() }}'
                },
                success: function(result) {
                    // Create download link
                    var link = document.createElement('a');
                    link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(result);
                    link.download = 'incomplete_orders_' + new Date().toISOString().split('T')[0] + '.csv';
                    link.click();
                },
                error: function() {
                    alert('Failed to export. Please try again.');
                }
            });
        });



        // Status change handler
        $(document).on('change', '.status-dropdown', function() {
            const dropdown = $(this);
            const orderId = dropdown.data('order-id');
            const newStatus = dropdown.val();
            const currentStatus = dropdown.data('current-status');

            if (newStatus === currentStatus) return;

            $.ajax({
                url: '/admin/incomplete-orders/' + orderId + '/update-status',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        dropdown.data('current-status', newStatus);

                        // Update row class
                        const row = dropdown.closest('tr');
                        row.removeClass('status-pending status-contacted status-follow_up status-converted status-cancelled status-spam');
                        row.addClass('status-' + newStatus);

                        // Show success message
                        const statusText = newStatus.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        dropdown.closest('td').append('<div class="alert alert-success alert-dismissible fade show" style="position: absolute; top: 100%; left: 0; z-index: 1000; font-size: 10px; padding: 4px 8px; margin: 2px 0;">Status updated to ' + statusText + '</div>');
                        setTimeout(function() {
                            dropdown.closest('td').find('.alert').remove();
                        }, 2000);
                    }
                },
                error: function() {
                    alert('Failed to update status. Please try again.');
                    dropdown.val(currentStatus);
                }
            });
        });

        // Note editing functionality
        $(document).on('click', '.admin-note-text, .note-edit-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const cell = $(this).closest('.admin-note-cell');
            const orderId = cell.find('[data-order-id]').first().data('order-id');
            const currentNote = cell.find('.admin-note-text').text().replace('Click to add note...', '');

            // Create textarea for editing
            const textarea = $('<textarea class="form-control" style="width: 100%; min-height: 60px; font-size: 12px; resize: vertical;">' + currentNote + '</textarea>');
            const saveBtn = $('<button class="btn btn-sm btn-success mt-1" style="font-size: 10px;"><i class="fas fa-save"></i> Save</button>');
            const cancelBtn = $('<button class="btn btn-sm btn-secondary mt-1 ml-1" style="font-size: 10px;"><i class="fas fa-times"></i> Cancel</button>');

            cell.html(textarea).append(saveBtn).append(cancelBtn);
            textarea.focus();

            // Save note
            saveBtn.on('click', function() {
                const newNote = textarea.val().trim();

                $.ajax({
                    url: '/admin/incomplete-orders/' + orderId + '/update-note',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        admin_note: newNote
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the cell with new note
                            const noteClass = newNote ? '' : 'empty';
                            const noteText = newNote || 'Click to add note...';

                            cell.html('<div class="admin-note-text ' + noteClass + '" data-order-id="' + orderId + '" title="' + (newNote || 'Click to add note') + '">' + noteText + '</div>' +
                                '<button class="btn btn-sm btn-outline-secondary note-edit-btn" data-order-id="' + orderId + '" title="Edit note">' +
                                '<i class="fas fa-edit"></i></button>');
                        }
                    },
                    error: function() {
                        alert('Failed to update note. Please try again.');
                        // Restore original content
                        const noteClass = currentNote ? '' : 'empty';
                        const noteText = currentNote || 'Click to add note...';

                        cell.html('<div class="admin-note-text ' + noteClass + '" data-order-id="' + orderId + '" title="' + (currentNote || 'Click to add note') + '">' + noteText + '</div>' +
                            '<button class="btn btn-sm btn-outline-secondary note-edit-btn" data-order-id="' + orderId + '" title="Edit note">' +
                            '<i class="fas fa-edit"></i></button>');
                    }
                });
            });

            // Cancel editing
            cancelBtn.on('click', function() {
                const noteClass = currentNote ? '' : 'empty';
                const noteText = currentNote || 'Click to add note...';

                cell.html('<div class="admin-note-text ' + noteClass + '" data-order-id="' + orderId + '" title="' + (currentNote || 'Click to add note') + '">' + noteText + '</div>' +
                    '<button class="btn btn-sm btn-outline-secondary note-edit-btn" data-order-id="' + orderId + '" title="Edit note">' +
                    '<i class="fas fa-edit"></i></button>');
            });
        });

        // Check fraud from database cache
        $(document).on('click', '.check-fraud-cache-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const btn = $(this);
            const orderId = btn.data('order-id');
            const phone = btn.data('phone');

            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: `/admin/fraud-checker/orders/${orderId}/check-fraud`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    from_cache: true
                },
                success: function(response) {
                    if (response.success) {
                        // Replace the button with fraud check info
                        const result = response.result;
                        const riskLevelClass = getRiskLevelClass(result.risk_level);
                        const riskLevelDisplay = getRiskLevelDisplay(result.risk_level);

                        btn.closest('td').html(`
                                <div class="fraud-check-info">
                                    <span class="badge ${riskLevelClass} fraud-risk-badge">
                                        ${riskLevelDisplay}
                                    </span>
                                    <div class="fraud-score">${result.risk_score}/100</div>
                                    <div class="fraud-success-rate">${result.has_courier_history ? result.delivery_success_rate + '% Success' : '0% (New Customer - Unknown History)'}</div>
                                    <small class="text-muted">(From DB)</small>
                                </div>
                            `);
                    } else {
                        alert('No fraud check data found in database for this phone number.');
                        btn.prop('disabled', false).html('<i class="fas fa-database"></i>');
                    }
                },
                error: function() {
                    alert('An error occurred while checking fraud data.');
                    btn.prop('disabled', false).html('<i class="fas fa-database"></i>');
                }
            });
        });

        function getRiskLevelClass(riskLevel) {
                switch(riskLevel) {
                    case 'high': return 'bg-danger';
                    case 'medium': return 'bg-warning';
                    case 'low': return 'bg-info';
                    case 'very_low': return 'bg-success';
                    default: return 'bg-secondary';
            }
        }

        function getRiskLevelDisplay(riskLevel) {
                switch(riskLevel) {
                    case 'high': return 'High Risk';
                    case 'medium': return 'Medium Risk';
                    case 'low': return 'Low Risk';
                    case 'very_low': return 'Very Low Risk';
                    default: return 'Unknown';
            }
        }

        // View incomplete order
        $(document).on('click', '.view-incomplete-order', function() {
            var id = $(this).data('id');
            $('#incomplete-order-details').html('<div class="text-center">Loading...</div>');
            $('#incompleteOrderModal').modal('show');

            $.get('/admin/incomplete-orders/' + id, function(data) {
                let html = `
                        <ul class="list-group mb-3">
                            <li class="list-group-item"><strong>Name:</strong> ${data.name ?? ''}</li>
                            <li class="list-group-item"><strong>Address:</strong> ${data.address ?? ''}</li>
                            <li class="list-group-item"><strong>Upazila:</strong> ${data.upazila ?? ''}</li>
                            <li class="list-group-item"><strong>Source:</strong> ${data.source ?? ''}</li>
                            <li class="list-group-item"><strong>Status:</strong> <span class="badge status-badge status-${data.status || 'pending'}">${(data.status || 'pending').replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</span></li>
                            <li class="list-group-item"><strong>Message:</strong> ${data.message ?? ''}</li>
                            <li class="list-group-item"><strong>Phone:</strong> ${data.phone ?? ''}</li>
                            <li class="list-group-item"><strong>Total:</strong> ${data.total ?? ''}</li>
                            <li class="list-group-item"><strong>Payment Method:</strong> ${data.payment_method ?? ''}</li>
                            <li class="list-group-item"><strong>Shipping Method:</strong> ${data.shipping_method ?? ''}</li>
                            <li class="list-group-item"><strong>Shipping Cost:</strong> ${data.shipping_cost ?? ''}</li>
                            <li class="list-group-item"><strong>Admin Note:</strong> ${data.admin_note ? '<br><div class="mt-2 p-2 bg-light border rounded">' + data.admin_note + '</div>' : '<em class="text-muted">No note added</em>'}</li>
                            <li class="list-group-item"><strong>Created At:</strong> ${data.created_at}</li>
                        </ul>
                    `;

                // Product details
                if (data.product_details) {
                    let products = data.product_details;
                    if (typeof products === 'string') {
                        try {
                            products = JSON.parse(products);
                        } catch (e) {
                            products = [];
                        }
                    }

                    if (Array.isArray(products) && products.length > 0) {
                        html += `<h5 class='mt-3'>Products</h5>
                                <table class='table table-sm table-bordered'>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Variations/Selections</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                        products.forEach(function(product) {
                            let variations = '';
                            if (product.variations && Array.isArray(product.variations) && product.variations.length > 0) {
                                variations = product.variations.join('<br>');
                            } else if (product.variations && typeof product.variations === 'string') {
                                variations = product.variations;
                            } else if (product.selections && Array.isArray(product.selections) && product.selections.length > 0) {
                                variations = product.selections.join('<br>');
                            } else if (product.selections && typeof product.selections === 'string') {
                                variations = product.selections;
                            }

                            let formattedPrice = '';
                            if (product.price && product.price > 0) {
                                formattedPrice = '৳' + parseFloat(product.price).toFixed(2);
                            } else {
                                formattedPrice = '-';
                            }

                            let productName = product.name || '';
                            if (product.is_combo) {
                                productName += ' <span class="badge badge-success">Combo</span>';
                            }

                            html += `<tr>
                                    <td>${product.product_id ?? ''}</td>
                                    <td><strong>${productName}</strong></td>
                                    <td>${formattedPrice}</td>
                                    <td>${product.quantity ?? ''}</td>
                                    <td><small class="text-info">${variations}</small></td>
                                </tr>`;
                        });
                        html += `</tbody></table>`;
                    }
                }
                $('#incomplete-order-details').html(html);
            });
        });

        // Delete individual incomplete order
        $(document).on('click', '.delete-incomplete-order', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure you want to delete this incomplete order?')) {
                $.ajax({
                    url: '/admin/incomplete-orders/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        selectedIds.delete(id);
                        table.ajax.reload();
                    },
                    error: function() {
                        alert('Failed to delete. Please try again.');
                    }
                });
            }
        });
    });
</script>
@endsection
