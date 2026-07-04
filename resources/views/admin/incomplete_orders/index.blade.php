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
    .modal-header {
        justify-content: space-between;
    }

    .modal-header button.close {
        font-size: 37px;
        color: red;
        display: none;
    }

    /* Filter Section Styles */
    .filter-section {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 7px;
        margin-bottom: 10px;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: end;
        margin-bottom: 2px;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        font-weight: 600;
        margin-bottom: 5px;
        color: #495057;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        height: 38px;
        box-sizing: border-box;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: end;
    }

    .btn-filter {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-apply {
        background: #197A94;
        color: white;
    }

    .btn-apply:hover {
        background: #197A94;
    }

    .btn-clear {
        background: #6c757d;
        color: white;
    }

    .btn-clear:hover {
        background: #545b62;
    }

    /* Customer Info Styles */
    .customer-info {
        max-width: 300px;
    }

    .customer-name {
        font-weight: 600;
        color: #495057;
    }

    .customer-phone {
        color: #6c757d;
        font-size: 0.9em;
    }

    /* DataTables Button Styles */
    .dt-buttons {
        margin-bottom: 10px;
    }

    .dt-button {
        background: #197A94 !important;
        color: white !important;
        border: none !important;
        padding: 8px 16px !important;
        border-radius: 4px !important;
        margin-right: 5px !important;
    }

    .dt-button:hover {
        background: #197A94 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
        }

        .filter-group {
            min-width: 100%;
        }
    }

    /* DataTables bottom spacing */
    .bottom {
        margin-top: 20px;
    }

    .dataTables_length,
    .dataTables_info {
        margin-top: 10px;
    }

    /* Fraud Check Styles */
    .fraud-check-info {
        text-align: center;
        line-height: 1.3;
    }

    .fraud-risk-badge {
        display: block;
        margin-bottom: 4px;
        font-size: 11px;
        padding: 2px 6px;
    }

    .fraud-score {
        font-weight: 600;
        font-size: 12px;
        color: #333;
    }

    .fraud-success-rate {
        font-size: 12px;
        color: #1f1f1f;
        margin-bottom: 2px;
        font-weight: 600;
    }

    .check-fraud-btn {
        font-size: 11px;
        padding: 4px 8px;
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
        gap: 4px;
    }

    .check-fraud-cache-btn {
        font-size: 10px;
        padding: 2px 6px;
    }

    /* Status Badge Styles */
    .status-badge {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background-color: #ffc107;
        color: #000;
    }

    .status-contacted {
        background-color: #17a2b8;
        color: #fff;
    }

    .status-follow_up {
        background-color: #fd7e14;
        color: #fff;
    }

    .status-converted {
        background-color: #28a745;
        color: #fff;
    }

    .status-cancelled {
        background-color: #dc3545;
        color: #fff;
    }

    .status-spam {
        background-color: #6c757d;
        color: #fff;
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
        padding: 4px 8px;
        border-radius: 4px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        font-size: 12px;
        line-height: 1.3;
    }

    .admin-note-text:hover {
        background-color: #e9ecef;
    }

    .admin-note-text.empty {
        color: #6c757d;
        font-style: italic;
    }

    .note-edit-btn {
        position: absolute;
        top: 2px;
        right: 2px;
        font-size: 10px;
        padding: 2px 4px;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .admin-note-cell:hover .note-edit-btn {
        opacity: 1;
    }

    /* Status Dropdown Styles */
    .status-dropdown {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid #ced4da;
        background-color: #fff;
        cursor: pointer;
        transition: all 0.2s;
    }

    .status-dropdown:hover {
        border-color: #197A94;
    }

    .status-dropdown:focus {
        outline: none;
        border-color: #197A94;
        box-shadow: 0 0 0 0.2rem rgba(25, 122, 148, 0.25);
    }

    /* Incomplete Order Status Row Colors */
    table#incomorders tr.status-pending td {
        background-color: #fff3e0 !important;
    }

    table#incomorders tr.status-contacted td {
        background-color: #e3f2fd !important;
    }

    table#incomorders tr.status-follow_up td {
        background-color: #f3e5f5 !important;
    }

    table#incomorders tr.status-converted td {
        background-color: #e8f5e9 !important;
    }

    table#incomorders tr.status-cancelled td {
        background-color: #ffebee !important;
    }

    table#incomorders tr.status-spam td {
        background-color: #eceff1 !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <h5>Incomplete Orders</h5>
    <hr>
    <!-- Advanced Filter Section -->
    <div class="filter-section">
        <div class="filter-row">
            <div class="filter-group">
                <input type="text" id="filter-name" class="form-control" placeholder="Search by name...">
            </div>
            <div class="filter-group">
                <input type="text" id="filter-phone" class="form-control" placeholder="Search by phone...">
            </div>
            <div class="filter-group">
                <select id="filter-source" class="form-control">
                    <option value="">All Sources</option>
                    <option value="buynow">Buy Now</option>
                    <option value="checkout">Checkout</option>
                    <option value="landing">Landing</option>
                </select>
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
            <div class="filter-group">
                <select id="filter-payment" class="form-control">
                    <option value="">All Payment Methods</option>
                    <option value="cod">Cash on Delivery</option>
                    <option value="bkash">bKash</option>
                    <option value="nagad">Nagad</option>
                    <option value="rocket">Rocket</option>
                </select>
            </div>
        </div>
        <div class="filter-row">
            <div class="filter-group">
                <input type="number" id="filter-total-min" class="form-control" placeholder="Min amount...">
            </div>
            <div class="filter-group">
                <input type="number" id="filter-total-max" class="form-control" placeholder="Max amount...">
            </div>
            <div class="filter-group">
                <input type="date" id="filter-date-from" class="form-control">
            </div>
            <div class="filter-group">
                <input type="date" id="filter-date-to" class="form-control">
            </div>
            <div class="filter-actions">
                <button id="apply-filters" class="btn-filter btn-apply">
                    <i class="fas fa-search"></i> Apply Filters
                </button>
                <button id="clear-filters" class="btn-filter btn-clear">
                    <i class="fas fa-times"></i> Clear All
                </button>
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
            responsive: true,
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
