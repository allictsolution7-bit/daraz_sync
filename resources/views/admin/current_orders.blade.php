@extends('layouts.master')

@section('styles')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/datetime/1.5.0/css/dataTables.dateTime.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/colreorder/1.7.0/css/colReorder.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <style>
        /* Add hover effect to rows */
        .clickable-row:hover {
            background-color: #fa2b2b !important;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        /* Order Status Row Colors */
        table#Products tr.order-status-pending td {
            background-color: #fff3e0 !important;
        }

        table#Products tr.order-status-processing td {
            background-color: #e3f2fd !important;
        }

        table#Products tr.order-status-delivered td {
            background-color: #e8f5e9 !important;
        }

        table#Products tr.order-status-on_hold td {
            background-color: #f3e5f5 !important;
        }

        table#Products tr.order-status-shipped td {
            background-color: #e0f7fa !important;
        }

        table#Products tr.order-status-cancelled td {
            background-color: #ffebee !important;
        }

        table#Products tr.order-status-phone_not_rcv td {
            background-color: #eceff1 !important;
        }

        table#Products tr.order-status-follow_up td {
            background-color: #f3e5f5 !important;
        }

        table#Products tr.order-status-ready_for_delivery td {
            background-color: #e0f2f1 !important;
        }

        span.editable-note {
            position: relative;
            top: 4px;
        }

        /* Order Status Badges */
        .order-status-badge {
            display: inline-block;
            padding: 0px 7px;
            border-radius: 12px;
            color: #fff;
            font-weight: bold;
            font-size: 9px;
            margin-bottom: 2px;
        }

        table.dataTable tbody tr.selected>*{
            box-shadow: inset 0 0 0 9999px rgb(201 227 255 / 90%) !important;
        }

        .order-status-pending {
            background: #fbc02d;
        }

        .order-status-processing {
            background: #1976d2;
        }

        .order-status-delivered {
            background: #388e3c;
        }

        .order-status-on_hold {
            background: #8e24aa;
        }

        .order-status-shipped {
            background: #00897b;
        }

        .order-status-cancelled {
            background: #d32f2f;
        }

        .order-status-phone_not_rcv {
            background: #455a64;
        }

        .order-status-follow_up {
            background: #f57c00;
        }

        .order-status-ready_for_delivery {
            background: #0097a7;
        }

        /* Steadfast Button Styles */
        .steadfastbtn {
            background: #4E4E4F;
            color: #fff;
            border-radius: 12px;
            padding: 0px 8px;
            margin-right: 3px;
        }

        .steadfastbtn.sent-sf {
            background: #35A486 !important;
            color: #fff;
        }

        button.steadfastbtn:hover {
            background: #35A486;
        }

        input.order-checkbox {
            width: 19px;
            height: 19px;
            position: relative;
            top: 0px;
        }

        /* Combo Order Display Styles - More Compact */
        .combo-order-display {
            line-height: 1.1;
            margin-top: 1px;
        }
        
        .combo-selections-list {
            margin-left: 6px;
            border-left: 1px solid #e9ecef;
            padding-left: 4px;
            margin-top: 0;
        }
        
        .combo-selections-list div {
            margin-bottom: 0;
            font-size: 10px;
        }

        /* Advanced Filter Section */
        .filter-section {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: end;
            margin-bottom: 10px;
        }

        .filter-group {
            flex: 1;
            min-width: 150px;
        }

        .filter-group label {
            font-weight: 600;
            margin-bottom: 3px;
            display: block;
            font-size: 12px;
            color: #495057;
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 6px 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            height: 32px;
            box-sizing: border-box;
            font-size: 12px;
        }

        .filter-actions {
            display: flex;
            gap: 8px;
            align-items: end;
        }

        .btn-filter {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            font-size: 12px;
            white-space: nowrap;
        }

        .btn-clear {
            background: #6c757d;
            color: white;
        }

        .btn-apply {
            background: #197A94;
            color: white;
        }

        .btn-apply:hover {
            background: #0056b3;
        }

        .btn-clear:hover {
            background: #545b62;
        }

        /* Filter Section Title */
        .filter-section h6 {
            color: #495057;
            font-weight: 600;
            margin: 0 0 8px 0;
            padding: 0;
        }

        /* Mobile Responsive Design */
        @media (max-width: 768px) {
            .filter-section {
                padding: 10px;
                margin-bottom: 12px;
            }

            .filter-row {
                flex-direction: row;
                gap: 8px;
                margin-bottom: 8px;
            }

            .filter-group {
                min-width: 20%;
                margin-bottom: 0;
            }

            .filter-group label {
                font-size: 11px;
                margin-bottom: 2px;
            }

            .filter-group select,
            .filter-group input {
                height: 28px;
                font-size: 11px;
                padding: 4px 8px;
            }

            .filter-actions {
                gap: 6px;
                margin-top: 5px;
            }

            .btn-filter {
                padding: 5px 10px;
                font-size: 11px;
                flex: 1;
            }
        }

        @media screen and (max-width: 640px) {
            div.dt-buttons {
                text-align: left !important;
            }
        }

        /* Extra Small Mobile Devices */
        @media (max-width: 480px) {
            .filter-section {
                padding: 8px;
            }

            .filter-group label {
                font-size: 10px;
            }

            .filter-group select,
            .filter-group input {
                height: 36px;
                font-size: 10px;
                padding: 3px 6px;
            }

            .btn-filter {
                padding: 4px 8px;
                font-size: 10px;
            }
        }

        /* DataTables Button Styling */
        .dataTables_wrapper .dt-buttons {
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
            background: #0056b3 !important;
        }

        /* Price and Amount Styling */
        .amount-high {
            color: #28a745;
            font-weight: 600;
        }

        .amount-medium {
            color: #ffc107;
            font-weight: 600;
        }

        .amount-low {
            color: #dc3545;
            font-weight: 600;
        }

        /* Customer Info Styling */
        .customer-info {
            font-size: 14px;
            line-height: 1.4;
        }

        .customer-name {
            font-weight: 600;
            color: #333;
        }

        .customer-phone {
            color: #666;
            font-size: 13px;
        }
        
        /* Combined Order Details Styling */
        .order-details-container {
            padding: 2px 0;
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3px;
            padding-bottom: 2px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .order-checkbox-wrapper {
            display: flex;
            align-items: center;
            margin-right: 4px;
        }
        
        .order-checkbox-wrapper .order-checkbox {
            margin: 0;
            transform: scale(1.1);
        }
        
        .order-checkbox-wrapper .order-checkbox:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .order-checkbox-wrapper .order-checkbox:not(:disabled):hover {
            cursor: pointer;
        }
        
        .order-id {
            font-weight: 600;
            font-size: 12px;
            color: #495057;
            padding: 0;
            border-radius: 0;
            flex-grow: 1;
            margin: 0 4px;
            background: none;
        }
        
        .order-date {
            font-size: 10px;
            color: #6c757d;
            font-weight: 500;
        }
        
        .order-details-container .customer-info {
            margin-bottom: 3px;
            padding: 1px 0;
        }
        
        .order-details-container .customer-name {
            font-size: 12px;
            margin-bottom: 0;
            font-weight: 500;
        }
        
        .order-details-container .customer-phone {
            font-size: 11px;
            color: #666;
        }
        
        .product-section {
            margin-top: -5px;
            min-height: 20px;
        }
        
        .order-amount {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: -2px;
        }
        
        .product-titles {
            font-size: 11px;
            color: #495057;
            line-height: 1.1;
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
            max-height: none;
            overflow: visible;
        }
        
        /* Status and Note Combined Column Styling */
        .status-note-container {
            display: inline-block;
            flex-direction: column;
            gap: 4px;
            min-height: 60px;
            width: auto;
        }
        
        .note-section {
            font-size: 11px;
            color: #666;
            line-height: 1.2;
            padding-top: 2px;
            border-top: 1px solid #f0f0f0;
            margin-top: 4px;
        }
        
        .note-section .editable-note {
            cursor: pointer;
            padding: 1px 2px;
            border-radius: 2px;
            transition: background-color 0.2s ease;
        }
        
        .note-section .editable-note:hover {
            background-color: #f8f9fa;
        }
        
        /* Enhanced Note Editing Styles */
        .admin-note-cell {
            max-width: 225px;
            position: relative;
            margin-top: 2px;
        }
        
        .admin-note-text {
            max-height: none;
            overflow: visible;
            text-overflow: unset;
            white-space: normal;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            font-size: 12px;
            line-height: 1.4;
            word-wrap: break-word;
            word-break: break-word;
            min-height: 20px;
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
        
        /* Note Editing Interface Styles */
        .note-editing-container {
            position: relative;
            width: 100%;
        }
        
        .note-editing-container textarea {
            border: 1px solid #197A94;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .note-editing-container .btn {
            margin-right: 5px;
            font-size: 10px;
            padding: 4px 8px;
        }
        
        .note-editing-container .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        
        .note-editing-container .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        /* Fraud Check Styles */
        .fraud-check-info {
            text-align: left;
            line-height: 1.3;
        }
        
        .fraud-risk-badge {
            display: inline-block;
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
            font-weight:600;
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
        
        .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
             margin-top: -10px;
        }
        .dataTables_length{
           margin-top: 15px;
           margin-bottom: 0 !important;
        }
        .dataTables_info{
            margin-top: 0 !important;
        }
        
        /* Compact Table Styling */
        #Products tbody td {
            padding: 4px 6px;
            vertical-align: middle;
        }
        
        #Products tbody td:first-child {
            padding-left: 3px;
            padding-right: 3px;
        }
        
        /* Status and Note Column Styling */
        #Products tbody td:nth-child(2) {
            /* min-width: 280px;
            max-width: 350px; */
            width: auto;
        }
        
        /* Compact Order Details */
        .order-details-container {
            min-height: 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        /* Optimize spacing for compact view */
        .order-header {
            min-height: 18px;
        }
        
        .customer-info {
            min-height: 14px;
        }
        
        .product-section {
            min-height: 18px;
        }
        
        /* Enhanced compact styling */
        .order-details-container {
            border-left: 3px solid transparent;
            transition: border-left-color 0.2s ease;
        }
        
        .order-details-container:hover {
            border-left-color: #197A94;
        }
        
        /* Compact badge styling */
        .badge {
            font-size: 9px !important;
            padding: 1px 4px !important;
            line-height: 1.2;
        }
        
        /* Optimize table row height */
        #Products tbody tr {
            height: auto;
            min-height: 55px;
        }
        
        /* Compact font weights */
        .order-id {
            font-weight: 600;
        }
        
        .customer-name {
            font-weight: 500;
        }
        
        .order-amount {
            font-weight: 600;
        }
        .notesave{
            margin-right: 2px;
        }
        .product-titles {
            font-size: 11px;
            color: #495057;
            line-height: 1.2;
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
            max-height: none;
            overflow: visible;
            max-width: 100%;
            display: block;
        }
        
        /* Combo Order Display Styles - More Compact */
        .combo-order-display {
            line-height: 1.2;
            margin-top: 1px;
            max-width: 100%;
        }
        
        .combo-selections-list {
            margin-left: 6px;
            border-left: 1px solid #e9ecef;
            padding-left: 4px;
            margin-top: 0;
            max-width: calc(100% - 10px);
        }
        
        .combo-selections-list div {
            margin-bottom: 0;
            font-size: 10px;
            line-height: 1.2;
            word-wrap: break-word;
            word-break: break-word;
            max-width: 100%;
            overflow-wrap: break-word;
        }
        
        /* Ensure proper column width and prevent responsive hiding */
        #Products tbody td:first-child {
            min-width: 300px;
            max-width: 400px;
            width: auto;
        }
        
        #Products tbody td:nth-child(2) {
            min-width: 200px;
            max-width: 250px;
            width: auto;
        }
        
        #Products tbody td:nth-child(3) {
            min-width: 120px;
            max-width: 150px;
            width: auto;
        }
        
        #Products tbody td:nth-child(4) {
            min-width: 100px;
            max-width: 120px;
            width: auto;
        }
        
        /* Force DataTable to maintain column visibility */
        .dataTables_wrapper .dataTables_scroll {
            overflow-x: auto;
        }
        
        /* Ensure product titles don't break layout */
        .product-section {
            margin-top: 2px;
            min-height: 20px;
            max-width: 100%;
            overflow-wrap: break-word;
        }
        
        /* Force table to maintain column structure */
        #Products {
            table-layout: fixed;
            width: 100% !important;
        }
        
        #Products thead th {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Prevent DataTable responsive from hiding columns */
        .dataTables_wrapper .dataTables_scrollBody {
            overflow-x: auto !important;
        }
        
        .dataTables_wrapper .dataTables_scrollHead {
            overflow-x: auto !important;
        }
        
        /* Ensure all columns are visible */
        .dataTables_wrapper .dataTables_scroll {
            overflow-x: auto !important;
            overflow-y: hidden;
        }
        
        /* Force horizontal scroll when needed */
        .dataTables_wrapper .dataTables_scrollBody table {
            min-width: 100%;
        }
        
        /* Responsive breakpoint adjustments */
        @media screen and (max-width: 1200px) {
            #Products tbody td:first-child {
                min-width: 280px;
                max-width: 350px;
            }
            
            #Products tbody td:nth-child(2) {
                min-width: 180px;
                max-width: 220px;
            }
        }
        
        @media screen and (max-width: 992px) {
            #Products tbody td:first-child {
                min-width: 250px;
                max-width: 300px;
            }
            
            #Products tbody td:nth-child(2) {
                min-width: 160px;
                max-width: 200px;
            }
        }

        /* Show Fraud Check Column */
        #Products thead th:nth-child(3),
        #Products tbody td:nth-child(3) {
            display: none !important;
        } 
        
        /* Hide Steadfast button */
        button.steadfastbtn {
            display: none;
        }
        
        /* Action Icons Styling */
        .d-flex.gap-1 a {
            text-decoration: none;
            padding: 4px 6px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        
        .d-flex.gap-1 a:hover {
            transform: scale(1.1);
        }
        
        /* Custom Dropdown Styles */
        .custom-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .custom-dropdown-toggle {
            padding: 4px 8px;
            border: none;
            background: transparent;
            color: #6c757d;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        
        .custom-dropdown-toggle:hover {
            background-color: rgba(0, 0, 0, 0.1);
            color: #333;
        }
        
        .custom-dropdown-toggle.active {
            transform: rotate(90deg);
            color: #333;
        }
        
        .custom-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 200px;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1050;
            list-style: none;
            padding: 0;
            margin: 0;
            display: none;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }
        
        .custom-dropdown-menu.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        .custom-dropdown-item {
            display: block;
            padding: 8px 16px;
            font-size: 14px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }
        
        .custom-dropdown-item:hover {
            background-color: #f8f9fa;
            color: #333;
            text-decoration: none;
        }
        
        .custom-dropdown-header {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            padding: 8px 16px 4px;
            margin: 0;
        }
        
        .custom-dropdown-divider {
            margin: 4px 0;
            border: none;
            border-top: 1px solid #dee2e6;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Orders</li>
            </ol>
        </nav>
        <h5>All Orders</h5>
        <!-- Advanced Filter Section -->
        <div class="filter-section mt-2">
            <h6 class="mb-2" style="font-size: 14px; margin-bottom: 8px;">Advanced Filters</h6>
            <div class="filter-row">
                <div class="filter-group">
                    <label for="status-filter">Order Status</label>
                    <select id="status-filter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="phone_not_rcv">Call Not Received</option>
                        <option value="follow_up">Follow up</option>
                        <option value="processing">Processing</option>
                        <option value="ready_for_delivery">Ready For Delivery</option>
                        <option value="delivered">Delivered</option>
                        <option value="on_hold">On Hold</option>
                        <option value="shipped">Shipped</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="courier-filter">Courier Status</label>
                    <select id="courier-filter">
                        <option value="">All Orders</option>
                        <option value="steadfast_sent">Steadfast Sent</option>
                        <option value="steadfast_not_sent">Steadfast Not Sent</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="order-type-filter">Order Type</label>
                    <select id="order-type-filter">
                        <option value="">All Types</option>
                        <option value="combo">Combo Orders</option>
                        <option value="regular">Regular Orders</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="amount-min">Min Amount</label>
                    <input type="number" id="amount-min" placeholder="Min Amount" min="0">
                </div>
            </div>
            <div class="filter-row">
                <div class="filter-group">
                    <label for="amount-max">Max Amount</label>
                    <input type="number" id="amount-max" placeholder="Max Amount" min="0">
                </div>
                <div class="filter-group">
                    <label for="date-from">Date From</label>
                    <input type="date" id="date-from">
                </div>
                <div class="filter-group">
                    <label for="date-to">Date To</label>
                    <input type="date" id="date-to">
                </div>
                <div class="filter-actions">
                    <button class="btn-filter btn-apply" id="apply-filters"><i class="fas fa-filter"></i> Apply</button>
                    <button class="btn-filter btn-clear" id="clear-filters"><i class="fas fa-times"></i> Clear</button>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="">
                <button id="bulk-send-steadfast" class="btn btn-success mb-2"><i class="fas fa-paper-plane"></i> Bulk Send
                    to Steadfast</button>
            </div>
            <div class="">
                <button id="bulk-print-invoices" class="btn btn-info mb-2"><i class="fas fa-file-invoice"></i> Bulk Print Invoices</button>
            </div>
            <div class="">
                <button id="bulk-print-package-slips" class="btn btn-warning mb-2"><i class="fas fa-box"></i> Bulk Print Package Slips</button>
            </div>
            <div class="">
                <button id="bulk-delete-orders" class="btn btn-danger mb-2"><i class="fas fa-trash-alt"></i> Bulk Delete</button>
            </div>
            <div class="">
                <button id="export-selected" class="btn btn-info mb-2"><i class="fas fa-download"></i> Export Selected</button>
            </div>
        </div>

        <table class="table table-striped" id="Products" style="width:100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-orders"> Order Details</th>
                    <th>Status & Note</th>
                    <th>Fraud Check</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                    use Illuminate\Support\Str;
                @endphp
                @foreach ($orders as $order)
                    <tr data-href="{{ route('admin.orders.edit', $order->id) }}"
                        class="clickable-row order-status-{{ $order->status }}">
                        <td>
                            <div class="order-details-container">
                                <div class="order-header">
                                    <div class="order-checkbox-wrapper">
                                        <input type="checkbox" class="order-checkbox" value="{{ $order->id }}"
                                            data-steadfast-sent="{{ isset($order->delivery_data['courier_provider']) && $order->delivery_data['courier_provider'] === 'steadfast' ? 'true' : 'false' }}">
                                    </div>
                                    <div class="order-id">
                                        {{ $loop->iteration }} - {{ $order->id }}
                                    </div>
                                    <div class="order-date">
                                        {{ $order->created_at->format('d:m:y h:i A') }}
                                    </div>
                                </div>
                                <div class="customer-info">
                                    <div class="customer-name">{{ $order->name }}</div>
                                    <div class="customer-phone">{{ $order->phone }}</div>
                                </div>
                                <div class="product-section">
                                    <div class="order-amount amount-{{ $order->total > 1000 ? 'high' : ($order->total > 500 ? 'medium' : 'low') }}">
                                        ৳{{ number_format($order->total, 2) }}
                                    </div>
                                    <div class="product-titles">
                                        @if ($order->is_combo_order && $order->combo_offer_id)
                                            {{-- Display combo order with selections --}}
                                            @php
                                                $comboOffer = \App\Models\ComboOffer::find($order->combo_offer_id);
                                                $comboSelections = $order->combo_selections;
                                                if (is_string($comboSelections)) {
                                                    $comboSelections = json_decode($comboSelections, true);
                                                }
                                            @endphp
                                            @if ($comboOffer)
                                                <div class="combo-order-display">
                                                    <strong>{{ $comboOffer->title }}</strong>
                                                    <span class="badge bg-primary ms-1" style="font-size: 9px; padding: 1px 4px;">COMBO</span>
                                                    @if ($comboSelections && is_array($comboSelections))
                                                        <div class="combo-selections-list">
                                                            @foreach ($comboSelections as $selection)
                                                                @php
                                                                    $selectedProduct = \App\Models\Product::find($selection['product_id']);
                                                                    $selectedVariation = \App\Models\VariationCombination::find($selection['variation_id']);
                                                                @endphp
                                                                @if ($selectedProduct)
                                                                    <div>• {{ $selectedProduct->title }}
                                                                        @if ($selectedVariation)
                                                                            ({{ $selectedVariation->display_name }})
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                {{ $order->products->first()->title ?? 'Unknown Product' }}
                                            @endif
                                        @else
                                            {{-- Display regular order products --}}
                                            @foreach ($order->products as $product)
                                                {{ $product->title }}@if (!$loop->last)
                                                    <br>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="status-note-container">
                                <span class="order-status-badge order-status-{{ $order->status }} change-status-btn"
                                    data-order-id="{{ $order->id }}" data-current-status="{{ $order->status }}"
                                    style="cursor:pointer;">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    <i class="fas fa-chevron-down" style="margin-left: 1px; font-size: 11px; opacity: 0.7;"></i>
                                </span>
                                <div class="note-section">
                                    <div class="admin-note-cell">
                                        <div class="admin-note-text {{ $order->admin_note ? '' : 'empty' }} notesave" data-order-id="{{ $order->id }}" title="{{ $order->admin_note ?: 'Click to add note...' }}">
                                            {{ $order->admin_note ?: 'Click to add note...' }}
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary note-edit-btn" data-order-id="{{ $order->id }}" title="Edit note">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $fraudCheckResult = $order->fraudCheckResult;
                                $canCheckFraud = !in_array($order->status, ['delivered', 'shipped', 'ready_for_delivery']);
                            @endphp
                            
                            @if($order->hasFraudCheck() && $fraudCheckResult)
                                <div class="fraud-check-info">
                                    <span class="{{ $fraudCheckResult->risk_level_badge_class }} fraud-risk-badge">
                                        {{ $fraudCheckResult->risk_level_display }}
                                    </span>
                                    <div class="fraud-success-rate">{{ $fraudCheckResult->success_rate_display }}</div>
                                    @if($fraudCheckResult->isStale())
                                        <small class="text-warning">(Stale - {{ $fraudCheckResult->last_checked_at->diffForHumans() }})</small>
                                    @else
                                        <small class="text-muted">({{ $fraudCheckResult->last_checked_at->diffForHumans() }})</small>
                                    @endif
                                </div>
                            @elseif($canCheckFraud)
                                <div class="fraud-check-loading">
                                    <i class="fas fa-spinner fa-spin text-muted"></i>
                                    <small class="text-muted">Checking...</small>
                                </div>
                            @else
                                <div class="fraud-check-missing">
                                    <small class="text-muted">No data</small>
                                    <button class="btn btn-sm btn-outline-secondary check-fraud-cache-btn" data-order-id="{{ $order->id }}" data-phone="{{ $order->phone }}" title="Check from cache">
                                        <i class="fas fa-database"></i>
                                    </button>
                                </div>
                            @endif
                        </td>
                        <td>
                            @php
                                $isSteadfastSent =
                                    isset($order->delivery_data['courier_provider']) &&
                                    $order->delivery_data['courier_provider'] === 'steadfast';
                            @endphp

                            <button class="steadfastbtn{{ $isSteadfastSent ? ' sent-sf' : '' }}"
                                data-order-id="{{ $order->id }}" {{ $isSteadfastSent ? 'disabled' : '' }}>
                                @if ($isSteadfastSent)
                                    <i class="fas fa-check"></i> Steadfast
                                @else
                                    <i class="fas fa-paper-plane"></i> Steadfast
                                @endif
                            </button>

                            <div class="d-flex align-items-center gap-1">
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-success" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('order.print-invoice', $order->id) }}" class="btn btn-sm btn-outline-info" title="Print Invoice" target="_blank">
                                    <i class="fas fa-file-invoice"></i>
                                </a>
                                <a href="{{ route('order.print-package-slip', $order->id) }}" class="btn btn-sm btn-outline-warning" title="Print Package Slip" target="_blank">
                                    <i class="fas fa-box"></i>
                                </a>
                                <div class="custom-dropdown">
                                    <button class="btn btn-sm btn-outline-secondary custom-dropdown-toggle" type="button" title="More Options">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="custom-dropdown-menu">
                                        <li><h6 class="custom-dropdown-header">Print Options</h6></li>
                                        <li>
                                            <a class="custom-dropdown-item" href="{{ route('order.print-receipt', $order->id) }}" target="_blank">
                                                <i class="fas fa-receipt me-2"></i> Print Receipt
                                            </a>
                                        </li>
                                        <li>
                                            <a class="custom-dropdown-item" href="{{ route('order.print-invoice', $order->id) }}" target="_blank">
                                                <i class="fas fa-file-invoice me-2"></i> Print Invoice
                                            </a>
                                        </li>
                                        <li>
                                            <a class="custom-dropdown-item" href="{{ route('order.print-package-slip', $order->id) }}" target="_blank">
                                                <i class="fas fa-box me-2"></i> Print Package Slip
                                            </a>
                                        </li>
                                        <li><hr class="custom-dropdown-divider"></li>
                                        <li><h6 class="custom-dropdown-header">Download Options</h6></li>
                                        <li>
                                            <a class="custom-dropdown-item" href="{{ route('order.download-receipt', $order->id) }}">
                                                <i class="fas fa-download me-2"></i> Download Receipt
                                            </a>
                                        </li>
                                        <li>
                                            <a class="custom-dropdown-item" href="{{ route('order.download-invoice', $order->id) }}">
                                                <i class="fas fa-download me-2"></i> Download Invoice
                                            </a>
                                        </li>
                                        <li>
                                            <a class="custom-dropdown-item" href="{{ route('order.download-package-slip', $order->id) }}">
                                                <i class="fas fa-download me-2"></i> Download Package Slip
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Status Change Modal -->
    <div class="modal fade" id="statusChangeModal" tabindex="-1" aria-labelledby="statusChangeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="statusChangeForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusChangeModalLabel">Change Order Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="order_id" id="modalOrderId">
                        <select name="status" id="modalStatusSelect" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="phone_not_rcv">Call Not Received</option>
                            <option value="follow_up">Follow up</option>
                            <option value="processing">Processing</option>
                            <option value="ready_for_delivery">Ready For Delivery</option>
                            <option value="delivered">Delivered</option>
                            <option value="on_hold">On Hold</option>
                            <option value="shipped">Shipped</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Change Status</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.0/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/colreorder/1.7.0/js/dataTables.colReorder.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

    <script>
        $(document).ready(function() {
            let customFilters = {};
            
            // Custom Dropdown Functionality
            $(document).on('click', '.custom-dropdown-toggle', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const dropdown = $(this).closest('.custom-dropdown');
                const menu = dropdown.find('.custom-dropdown-menu');
                const toggle = $(this);
                const isCurrentlyOpen = menu.hasClass('show');
                
                // Close all other dropdowns and remove active state
                $('.custom-dropdown-menu').removeClass('show');
                $('.custom-dropdown-toggle').removeClass('active');
                
                // Toggle current dropdown only if it wasn't already open
                if (!isCurrentlyOpen) {
                    menu.addClass('show');
                    toggle.addClass('active');
                }
            });
            
            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.custom-dropdown').length) {
                    $('.custom-dropdown-menu').removeClass('show');
                    $('.custom-dropdown-toggle').removeClass('active');
                }
            });
            
            // Close dropdown when clicking on menu items
            $(document).on('click', '.custom-dropdown-item', function() {
                $('.custom-dropdown-menu').removeClass('show');
                $('.custom-dropdown-toggle').removeClass('active');
            });

            // Initialize DataTable with advanced features
            const table = $('#Products').DataTable({
                processing: true,
                serverSide: true,
                dom: '<"top"Bf>rt<"bottom"lip>',
                ajax: {
                    url: '{{ route('admin.orders.data') }}',
                    data: function(d) {
                        d.status = $('#status-filter').val();
                        d.courier_status = $('#courier-filter').val();
                        d.order_type = $('#order-type-filter').val();
                        d.amount_min = $('#amount-min').val();
                        d.amount_max = $('#amount-max').val();
                        d.date_from = $('#date-from').val();
                        d.date_to = $('#date-to').val();
                    }
                },
                columns: [
                    { data: 'order_details', name: 'order_details', orderable: false, searchable: true, width: '35%' },
                    { data: 'status_and_note', name: 'status_and_note', orderable: false, searchable: true, width: '25%' },
                    { data: 'fraud_check', name: 'fraud_check', orderable: false, searchable: false, width: '20%' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, width: '20%' },
                ],
                responsive: {
                    details: {
                        display: $.fn.dataTable.Responsive.display.modal({
                            header: function(row) {
                                var data = row.data();
                                return 'Order Details for Order #' + data.id;
                            }
                        }),
                        renderer: $.fn.dataTable.Responsive.renderer.tableAll()
                    }
                },
                autoWidth: false,
                scrollX: true,
                scrollCollapse: true,
                scrollY: false,
                fixedHeader: true,
                buttons: [
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-sm btn-outline-secondary'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-sm btn-outline-secondary'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-sm btn-outline-secondary'
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Columns',
                        className: 'btn btn-sm btn-outline-secondary'
                    }
                ],
                colReorder: true,
                select: {
                    style: 'multi',
                    selector: '.order-checkbox'
                },
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                // Sort by "Order Details" column (index 0) by order ID
                order: [],
                // Add callback to handle selection changes
                initComplete: function() {
                    // Sync select-all checkbox state after DataTable initialization
                    this.api().on('select deselect', function() {
                        const totalRows = this.rows({ selected: true }).count();
                        const totalSelectableRows = this.rows().count();
                        
                        if (totalRows === 0) {
                            selectAll.checked = false;
                            selectAll.indeterminate = false;
                        } else if (totalRows === totalSelectableRows) {
                            selectAll.checked = true;
                            selectAll.indeterminate = false;
                        } else {
                            selectAll.checked = false;
                            selectAll.indeterminate = true;
                        }
                    });
                }
            });

            // Filter functionality
            $('#apply-filters').on('click', function() {
                table.ajax.reload();
            });

            $('#clear-filters').on('click', function() {
                $('#status-filter, #courier-filter, #order-type-filter').val('');
                $('#amount-min, #amount-max, #date-from, #date-to').val('');
                table.ajax.reload();
            });

            // Remove client-side custom filter. Filters are passed with ajax.data

            // Make rows clickable
            $('#Products tbody').on('click', '.clickable-row', function(e) {
                // Don't navigate if clicking on the editable note or status dropdown
                if ($(e.target).closest('.editable-note').length ||
                    $(e.target).closest('.status-dropdown').length ||
                    $(e.target).closest('.order-checkbox').length ||
                    $(e.target).closest('.steadfastbtn').length ||
                    $(e.target).closest('button').length ||
                    $(e.target).closest('a').length ||
                    $(e.target).closest('.order-checkbox-wrapper').length ||
                    $(e.target).closest('.admin-note-cell').length) {
                    return;
                }
                const url = $(this).data('href');
                if (url) {
                    window.location.href = url;
                }
            });
            
            // Prevent row click on checkbox cell specifically
            $(document).on('click', '.dtr-control, .order-checkbox', function(e) {
                e.stopPropagation();
                e.preventDefault();
                return false;
            });

            // AJAX call to update status (delegated for dynamic rows)
            $(document).on('change', '.status-dropdown', function() {
                const orderId = $(this).data('order-id');
                const newStatus = $(this).val();

                $.ajax({
                    url: '{{ route('admin.orders.updateStatus') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_id: orderId,
                        status: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            // Update the status in the table without reordering
                            const statusClass = {
                                'pending': 'btn-warning',
                                'processing': 'btn-primary',
                                'delivered': 'btn-success',
                                'on_hold': 'btn-secondary',
                                'shipped': 'btn-info',
                                'phone_not_rcv': 'btn-info',
                                'follow_up': 'btn-info',
                                'ready_for_delivery': 'btn-info',
                                'cancelled': 'btn-danger'
                            };
                            // Update the button class and text dynamically
                            const row = $(`[data-order-id=${orderId}]`).closest('tr');
                            row.find('.btn')
                                .removeClass()
                                .addClass(`btn ${statusClass[newStatus]} btn-sm`)
                                .text(newStatus);
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while updating the status. Please try again.');
                    }
                });
            });

            // Editable note functionality (delegated)
            $(document).on('click', '.editable-note', function(event) {
                event.stopPropagation(); // Prevent row click
                const noteElement = $(this);
                const orderId = noteElement.data('order-id');
                const currentNote = noteElement.text().trim() === 'Add Note' ? '' : noteElement.text()
                    .trim();
                // Replace with input field
                noteElement.html(
                    `<input type=\"text\" class=\"editable-note-input\" value=\"${currentNote}\" />`);
                const inputField = noteElement.find('input');
                // Focus and select input field
                inputField.focus().select();
                // Save on blur or Enter key
                inputField.on('blur keyup', function(e) {
                    if (e.type === 'blur' || (e.type === 'keyup' && e.key === 'Enter')) {
                        const newNote = inputField.val().trim();
                        // Update via AJAX
                        $.ajax({
                            url: '{{ route('admin.orders.updateNote') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                order_id: orderId,
                                note: newNote
                            },
                            success: function(response) {
                                if (response.success) {
                                    noteElement.text(newNote || 'Add Note');
                                } else {
                                    alert('Error: ' + response.message);
                                    noteElement.text(currentNote || 'Add Note');
                                }
                            },
                            error: function() {
                                alert('An error occurred while updating the note.');
                                noteElement.text(currentNote || 'Add Note');
                            }
                        });
                    }
                });
            });

            // Enhanced Note editing functionality
            $(document).on('click', '.admin-note-text, .note-edit-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const cell = $(this).closest('.admin-note-cell');
                const orderId = cell.find('[data-order-id]').first().data('order-id');
                const currentNote = cell.find('.admin-note-text').text().replace('Click to add note...', '');
                
                // Create textarea for editing
                const textarea = $('<textarea class="form-control" style="width: 100%; min-height: 80px; max-height: 200px; font-size: 11px; resize: vertical; padding: 8px;">' + currentNote + '</textarea>');
                const saveBtn = $('<button class="btn btn-sm btn-success mt-1 notesave" style="font-size: 10px;"><i class="fas fa-save"></i> Save</button>');
                const cancelBtn = $('<button class="btn btn-sm btn-secondary mt-1 ml-1" style="font-size: 10px;"><i class="fas fa-times"></i> Cancel</button>');
                
                cell.html(textarea).append(saveBtn).append(cancelBtn);
                textarea.focus();
                
                // Save note
                saveBtn.on('click', function() {
                    const newNote = textarea.val().trim();
                    
                    $.ajax({
                        url: '{{ route('admin.orders.updateNote') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            order_id: orderId,
                            note: newNote
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

            // When badge is clicked, show modal (delegated)
            $(document).on('click', '.change-status-btn', function(event) {
                event.stopPropagation();
                var orderId = $(this).data('order-id');
                var currentStatus = $(this).data('current-status');
                $('#modalOrderId').val(orderId);
                $('#modalStatusSelect').val(currentStatus);
                $('#statusChangeModal').modal('show');
            });

            // Handle status change form submit
            $('#statusChangeForm').on('submit', function(e) {
                e.preventDefault();
                var orderId = $('#modalOrderId').val();
                var newStatus = $('#modalStatusSelect').val();

                $.ajax({
                    url: '{{ route('admin.orders.updateStatus') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_id: orderId,
                        status: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload(); // Reload to update the status badge color/text
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while updating the status.');
                    }
                });
            });

            // Export selected functionality
            $('#export-selected').on('click', function() {
                const selected = Array.from(document.querySelectorAll('.order-checkbox:checked'))
                    .map(cb => cb.value);

                if (selected.length === 0) {
                    alert('Please select at least one order to export.');
                    return;
                }

                // Create a temporary form to download the export
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('admin.orders.export-selected') }}";
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const orderIds = document.createElement('input');
                orderIds.type = 'hidden';
                orderIds.name = 'order_ids';
                orderIds.value = JSON.stringify(selected);
                
                form.appendChild(csrfToken);
                form.appendChild(orderIds);
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            });
        });
    </script>

    {{-- SteadFast One Sending --}}
    <input type="hidden" id="laravel-csrf-token" value="{{ csrf_token() }}">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delegate steadfast send for dynamic rows
            document.addEventListener('click', function(e) {
                const sendBtn = e.target.closest('.steadfastbtn');
                if (!sendBtn) return;
                    e.preventDefault();
                    e.stopPropagation(); // Prevent parent handlers from triggering navigation
                    sendBtn.disabled = true;
                    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                    // Get order ID from data attribute
                    const orderId = sendBtn.getAttribute('data-order-id');
                    if (!orderId) {
                        alert('Order ID not found!');
                        sendBtn.disabled = false;
                        sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Steadfast';
                        return;
                    }
    
                    fetch("{{ route('admin.steadfast.send') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.getElementById('laravel-csrf-token')
                                    .value
                            },
                            body: JSON.stringify({
                                order_id: orderId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message || 'Order sent to Steadfast successfully!');
                                sendBtn.disabled = true;
                                sendBtn.classList.add('sent-sf');
                                sendBtn.innerHTML = '<i class="fas fa-check"></i> Steadfast';
                            } else {
                                alert(data.message || 'Failed to send order to Steadfast.');
                            }
                        })
                        .catch(() => {
                            alert('An error occurred while sending the order.');
                        })
                        .finally(() => {
                            sendBtn.disabled = false;
                            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Steadfast';
                        });
            });
        });
    </script>

    {{-- SteadFast Bluk Sending --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select All functionality
            const selectAll = document.getElementById('select-all-orders');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const isChecked = this.checked;
                    // Update all checkboxes
                    document.querySelectorAll('.order-checkbox').forEach(cb => {
                        cb.checked = isChecked;
                        // Trigger change event for DataTable
                        $(cb).trigger('change');
                    });
                });
            }

            // Update select-all checkbox state when individual checkboxes change
            $(document).on('change', '.order-checkbox', function() {
                const totalCheckboxes = document.querySelectorAll('.order-checkbox').length;
                const checkedCheckboxes = document.querySelectorAll('.order-checkbox:checked').length;
                
                if (checkedCheckboxes === 0) {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;
                } else if (checkedCheckboxes === totalCheckboxes) {
                    selectAll.checked = true;
                    selectAll.indeterminate = false;
                } else {
                    selectAll.checked = false;
                    selectAll.indeterminate = true;
                }
            });

            // Bulk Send functionality
            const bulkSendBtn = document.getElementById('bulk-send-steadfast');
            if (bulkSendBtn) {
                bulkSendBtn.addEventListener('click', function() {
                    // Prevent row click when clicking on a checkbox
                    document.querySelectorAll('.order-checkbox').forEach(function(checkbox) {
                        checkbox.addEventListener('click', function(event) {
                            event.stopPropagation();
                        });
                    });
                    // Filter out orders already sent to Steadfast
                    const selected = Array.from(document.querySelectorAll('.order-checkbox'))
                        .filter(cb => cb.checked && cb.dataset.steadfastSent !== 'true')
                        .map(cb => cb.value);

                    if (selected.length === 0) {
                        alert('Please select at least one order to send (orders already sent to Steadfast are excluded).');
                        return;
                    }

                    bulkSendBtn.disabled = true;
                    bulkSendBtn.innerHTML = 'Sending...';

                    fetch("{{ route('admin.steadfast.sendBulk') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.getElementById('laravel-csrf-token').value
                            },
                            body: JSON.stringify({
                                order_ids: selected
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message || 'Bulk orders sent to Steadfast successfully!');
                                location.reload(); // Reload to update UI
                            } else {
                                alert(data.message || 'Failed to send bulk orders.');
                            }
                        })
                        .catch(() => {
                            alert('An error occurred while sending bulk orders.');
                        })
                        .finally(() => {
                            bulkSendBtn.disabled = false;
                            bulkSendBtn.innerHTML = 'Bulk Send to Steadfast';
                        });
                });
            }

            // Prevent row click when clicking on a checkbox
            document.addEventListener('click', function(event) {
                if (event.target.closest('.order-checkbox')) {
                    event.stopPropagation();
                }
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

            // Bulk Delete functionality
            const bulkDeleteBtn = document.getElementById('bulk-delete-orders');
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', function() {
                    const selected = Array.from(document.querySelectorAll('.order-checkbox'))
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);

                    if (selected.length === 0) {
                        alert('Please select at least one order to delete.');
                        return;
                    }

                    if (!confirm('Are you sure you want to delete the selected orders? This action cannot be undone.')) {
                        return;
                    }

                    bulkDeleteBtn.disabled = true;
                    bulkDeleteBtn.innerHTML = 'Deleting...';

                    fetch("{{ route('admin.orders.deleteMultiple') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.getElementById('laravel-csrf-token').value
                            },
                            body: JSON.stringify({
                                order_ids: selected
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message || 'Selected orders deleted successfully!');
                                location.reload();
                            } else {
                                alert(data.message || 'Failed to delete selected orders.');
                            }
                        })
                        .catch(() => {
                            alert('An error occurred while deleting orders.');
                        })
                        .finally(() => {
                            bulkDeleteBtn.disabled = false;
                            bulkDeleteBtn.innerHTML = '<i class="fas fa-trash-alt"></i> Bulk Delete';
                        });
                });
            }

            // Bulk Print Invoices functionality
            const bulkPrintInvoicesBtn = document.getElementById('bulk-print-invoices');
            if (bulkPrintInvoicesBtn) {
                bulkPrintInvoicesBtn.addEventListener('click', function() {
                    const selected = Array.from(document.querySelectorAll('.order-checkbox'))
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);

                    if (selected.length === 0) {
                        alert('Please select at least one order to print invoices.');
                        return;
                    }

                    // Open each invoice in a new tab
                    selected.forEach(orderId => {
                        const url = `/order/${orderId}/print-invoice`;
                        window.open(url, '_blank');
                    });
                });
            }

            // Bulk Print Package Slips functionality
            const bulkPrintPackageSlipsBtn = document.getElementById('bulk-print-package-slips');
            if (bulkPrintPackageSlipsBtn) {
                bulkPrintPackageSlipsBtn.addEventListener('click', function() {
                    const selected = Array.from(document.querySelectorAll('.order-checkbox'))
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);

                    if (selected.length === 0) {
                        alert('Please select at least one order to print package slips.');
                        return;
                    }

                    // Open each package slip in a new tab
                    selected.forEach(orderId => {
                        const url = `/order/${orderId}/print-package-slip`;
                        window.open(url, '_blank');
                    });
                });
            }
        });
    </script>
@endsection
