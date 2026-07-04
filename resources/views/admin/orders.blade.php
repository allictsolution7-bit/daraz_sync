@extends('layouts.master')

@section('styles')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/datetime/1.5.0/css/dataTables.dateTime.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <style>
        .bulk-actions-container {
            background: #ffffff;
            padding: 7px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 6px;
            box-shadow: 0 6px 14px rgba(15, 23, 42, 0.05);
        }

        .bulk-actions-container button {
            padding: 2px 10px;
        }

        .bulk-assign-group .assign-input-group {
            min-width: 250px;
            max-width: 250px;
        }

        .bulk-assign-group .assign-btn {
            white-space: nowrap;
        }

        .btn-soft-primary {
            background: #e2e8ff;
            color: #1d4ed8;
            border: 1px solid #cbd5ff;
            transition: all 0.15s ease-in-out;
        }
        .btn-soft-primary:hover { background: #cfd9ff; color: #1e3a8a; }

        .btn-soft-success {
            background: #e7f7ed;
            color: #15803d;
            border: 1px solid #c4ebd3;
            transition: all 0.15s ease-in-out;
        }
        .btn-soft-success:hover { background: #d6f0e1; color: #166534; }

        .btn-soft-info {
            background: #e0f2ff;
            color: #0369a1;
            border: 1px solid #bde3ff;
            transition: all 0.15s ease-in-out;
        }
        .btn-soft-info:hover { background: #cfe9ff; color: #075985; }

        .btn-soft-warning {
            background: #fff4e0;
            color: #b45309;
            border: 1px solid #ffe0b3;
            transition: all 0.15s ease-in-out;
        }
        .btn-soft-warning:hover { background: #ffe9c2; color: #92400e; }

        .btn-soft-danger {
            background: #ffe5e5;
            color: #b91c1c;
            border: 1px solid #ffc7c7;
            transition: all 0.15s ease-in-out;
        }
        .btn-soft-danger:hover { background: #ffd6d6; color: #991b1b; }

        .btn-soft-dark {
            background: #eef1f6;
            color: #1f2937;
            border: 1px solid #d8dde6;
            transition: all 0.15s ease-in-out;
        }
        .btn-soft-dark:hover { background: #e1e5ed; color: #111827; }

        /* Add hover effect to rows */
        .clickable-row:hover {
            background-color: #f1f5f9 !important;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        /* Order Status Row Colors */
        table#Products tr.order-status-pending td {
            background-color: #fff7e6 !important;
            color: #8a5600;
        }

        table#Products tr.order-status-processing td {
            background-color: #e7f0ff !important;
            color: #0f4fb4;
        }

        table#Products tr.order-status-delivered td {
            background-color: #e6f6ed !important;
            color: #1a6e37;
        }

        table#Products tr.order-status-on_hold td {
            background-color: #f3e8ff !important;
            color: #6b21a8;
        }

        table#Products tr.order-status-shipped td {
            background-color: #e0f7fa !important;
            color: #046c7a;
        }

        table#Products tr.order-status-cancelled td {
            background-color: #ffe5e5 !important;
            color: #b91c1c;
        }

        table#Products tr.order-status-phone_not_rcv td {
            background-color: #eceff1 !important;
            color: #374151;
        }

        table#Products tr.order-status-follow_up td {
            background-color: #fff1e6 !important;
            color: #a04b00;
        }

        table#Products tr.order-status-ready_for_delivery td {
            background-color: #e6f7f4 !important;
            color: #0f766e;
        }

        span.editable-note {
            position: relative;
            top: 4px;
            border-radius: 5px;
            padding: 2px 5px;
        }

        /* Order Status Badges */
        .order-status-badge {
            display: inline-block;
            padding: 0px 7px;
            border-radius: 12px;
            color: #1f2937;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 2px;
            border: 1px solid transparent;
        }

        .order-status-pending {
            background: #fff7e6;
            color: #8a5600;
            border-color: #ffd89a;
        }

        .order-status-processing {
            background: #e7f0ff;
            color: #0f4fb4;
            border-color: #c7dbff;
        }

        .order-status-delivered {
            background: #e6f6ed;
            color: #1a6e37;
            border-color: #c3e9d1;
        }

        .order-status-on_hold {
            background: #f3e8ff;
            color: #6b21a8;
            border-color: #e5d1ff;
        }

        .order-status-shipped {
            background: #e0f7fa;
            color: #046c7a;
            border-color: #b8ecf2;
        }

        .order-status-cancelled {
            background: #ffe5e5;
            color: #b91c1c;
            border-color: #ffc7c7;
        }

        .order-status-phone_not_rcv {
            background: #eceff1;
            color: #374151;
            border-color: #d8dde1;
        }

        .order-status-follow_up {
            background: #fff1e6;
            color: #a04b00;
            border-color: #ffd9b0;
        }

        .order-status-ready_for_delivery {
            background: #e6f7f4;
            color: #0f766e;
            border-color: #bcebe4;
        }

        .assigned-user-profile {
            font-size: 12px;
        }

        .assigned-user-profile .user-img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #e0e0e0;
        }

        .assigned-user-details .assigned-user-name {
            font-weight: 600;
            line-height: 1.1;
        }

        /* Steadfast Button Styles */
        .steadfastbtn {
            background: #4E4E4F;
            color: #fff;
            border-radius: 12px;
            padding: 0px 8px;
        }

        .steadfastbtn.sent-sf {
            background: #35A486 !important;
            color: #fff;
        }

        button.steadfastbtn:hover {
            background: #35A486;
        }

        input.order-checkbox {
            width: 15px;
            height: 15px;
            position: relative;
            top: 6px;
        }

        /* Combo Order Display Styles */
        .combo-order-display {
            line-height: 1.4;
        }

        .combo-selections-list {
            margin-left: 10px;
            border-left: 2px solid #e9ecef;
            padding-left: 8px;
        }

        .combo-selections-list div {
            margin-bottom: 2px;
        }

        /* Advanced Filter Section */
        .filter-section {
            background: #f8f9fa;
            padding: 7px;
            border-radius: 8px;
            margin-bottom: 7px;
            border: 1px solid #dee2e6;
        }

        /* Table header */
        table.dataTable thead th {
            background: #197A94 !important;
            color: #ffffffff !important;
            font-weight: 700;
        }

        /* DataTables buttons */
        .dt-buttons .dt-button {
            background: #e2e8ff !important;
            color: #1d4ed8 !important;
            border: 1px solid #cbd5ff !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            font-weight: 600 !important;
            box-shadow: 0 3px 8px rgba(15, 23, 42, 0.08) !important;
            transition: all 0.15s ease-in-out !important;
        }
        .dt-buttons .dt-button:hover {
            background: #cfd9ff !important;
            color: #1e3a8a !important;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: end;
        }

        .filter-group {
            flex: 1;
            min-width: 100px;
        }

        .filter-group label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 5px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            height: 32px;
            box-sizing: border-box;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            align-items: end;
        }

        .btn-filter {
            padding: 2px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
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
        /* Amount styling based on order status */
        .amount-pending { color: #8a5600; font-weight: 700; }
        .amount-processing { color: #0f4fb4; font-weight: 700; }
        .amount-delivered { color: #1a6e37; font-weight: 700; }
        .amount-on_hold { color: #6b21a8; font-weight: 700; }
        .amount-shipped { color: #046c7a; font-weight: 700; }
        .amount-cancelled { color: #b91c1c; font-weight: 700; }
        .amount-phone_not_rcv { color: #374151; font-weight: 700; }
        .amount-follow_up { color: #a04b00; font-weight: 700; }
        .amount-ready_for_delivery { color: #0f766e; font-weight: 700; }

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
            border-radius: 10px;
            font-weight: 600;
        }

        .fraud-risk-badge.badge-success,
        .fraud-risk-badge.bg-success {
            background: #e7f7ed;
            color: #ffffffff;
            border: 1px solid #c4ebd3;
        }

        .fraud-risk-badge.badge-warning,
        .fraud-risk-badge.bg-warning {
            background: #fff4e0;
            color: #ffffffff;
            border: 1px solid #ffe0b3;
        }

        .fraud-risk-badge.badge-danger,
        .fraud-risk-badge.bg-danger {
            background: #ffe5e5;
            color: #ffffffff;
            border: 1px solid #ffc7c7;
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
             margin-top:-5px;
        }
        .dataTables_length{
           margin-top: 10px;
           margin-bottom: 0 !important;
        }
        .dataTables_info{
            margin-top: 0 !important;
        }
        
        /* Note Column Width Control */
        #Products tbody td:nth-child(8) {
            max-width: 150px;
            min-width: 120px;
            width: 150px;
        }
        
        #Products thead th:nth-child(8) {
            max-width: 150px;
            min-width: 120px;
            width: 150px;
        }
        
        .editable-note {
            display: block;
            max-width: 100%;
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
            line-height: 1.6;
            padding: 4px;
            border-radius: 3px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        .editable-note:hover {
            background-color: #e9ecef;
        }
        
        .editable-note-input {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        
        /* Ensure checkboxes work properly */
        .order-checkbox {
            cursor: pointer !important;
            pointer-events: auto !important;
        }
        
        .order-checkbox:disabled {
            cursor: not-allowed !important;
            opacity: 0.6;
        }
        
        /* Prevent DataTables from interfering with checkboxes */
        .dataTables_wrapper .order-checkbox {
            pointer-events: auto !important;
        }
        
        /* Force checkbox functionality */
        input[type="checkbox"].order-checkbox {
            pointer-events: auto !important;
            cursor: pointer !important;
            z-index: 1000 !important;
            position: relative !important;
        }
        
        /* Ensure checkbox container doesn't interfere */
        td:first-child {
            position: relative !important;
            /* z-index: 1000 !important; */
        }
        
        /* Additional checkbox isolation */
        .order-checkbox {
            -webkit-appearance: auto !important;
            -moz-appearance: auto !important;
            appearance: auto !important;
            opacity: 1 !important;
            visibility: visible !important;
            display: inline-block !important;
        }
        
        /* Status Filter Bar Styles */
        .status-filter-bar {
            background-color: #f8f9fa;
            padding: 0px 5px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .status-filter-item {
            color: #197A94;
            cursor: pointer;
            padding: 5px 0px;
            border-radius: 4px;
            transition: all 0.2s ease;
            font-size: 13px;
        }
        
        .status-filter-item:hover {
            background-color: #e9ecef;
            color: #0056b3;
        }
        
        .status-filter-item.active {
            color: #000;
            font-weight: bold;
            background-color: #fff;
            border: 1px solid #dee2e6;
            padding: 0px 5px;
        }
        
        .status-filter-item.active strong {
            color: #000;
        }
        
        .status-count {
            color: #6c757d;
            font-weight: normal;
        }
        
        .status-separator {
            color: #6c757d;
            margin: 0 5px;
            font-weight: normal;
        }
        
        /* Action Icons Styling */
        .customer-info .d-flex a {
            text-decoration: none;
            padding: 4px 6px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        
        .customer-info .d-flex a:hover {
            background-color: rgba(0, 0, 0, 0.1);
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
        
        /* Prevent any DataTables or other CSS from affecting checkboxes */
        .dataTables_wrapper input[type="checkbox"].order-checkbox,
        table input[type="checkbox"].order-checkbox {
            -webkit-appearance: auto !important;
            -moz-appearance: auto !important;
            appearance: auto !important;
            opacity: 1 !important;
            visibility: visible !important;
            display: inline-block !important;
            pointer-events: auto !important;
            cursor: pointer !important;
        }
        
        
        /* Ensure checkboxes are clickable */
        input[type="checkbox"].order-checkbox {
            position: relative !important;
            z-index: 9999 !important;
            pointer-events: auto !important;
            cursor: pointer !important;
            user-select: none !important;
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
        }
        
        /* Make sure checkbox container doesn't block clicks */
        td:first-child,
        .order-checkbox-wrapper {
            position: relative !important;
            /* z-index: 9999 !important; */
            pointer-events: auto !important;
        }
        
        /* Mobile Product Info Styling */
        .product-info-mobile {
            border-top: 1px solid #e9ecef;
            padding-top: 8px;
            margin-top: 8px;
        }
        
        .product-info-mobile .amount-high {
            color: #28a745;
            font-weight: 600;
            font-size: 14px;
        }
        
        .product-info-mobile .amount-medium {
            color: #ffc107;
            font-weight: 600;
            font-size: 14px;
        }
        
        .product-info-mobile .amount-low {
            color: #dc3545;
            font-weight: 600;
            font-size: 14px;
        }
        
        .product-info-mobile .combo-order-display {
            margin-top: 4px;
            font-size: 12px;
        }
        
        .product-info-mobile .combo-selections-list {
            margin-top: 4px;
            font-size: 11px;
            color: #666;
        }
        
        .product-info-mobile .combo-selections-list div {
            margin-bottom: 2px;
        }
        
        /* Ensure mobile layout is clean */
        #Products tbody td:nth-child(2) {
            width: auto !important;
            max-width: none !important;
        }
        
        /* Mobile table layout */
        #Products {
            table-layout: auto;
        }
        
        /* Mobile responsive: Show product info in customer column on small screens */
        @media (max-width: 768px) {
            .product-info-mobile {
                display: block;
            }
            
            /* Hide the separate product column on mobile */
            #Products thead th:nth-child(3),
            #Products tbody td:nth-child(3) {
                display: none !important;
            }
            
            /* Mobile product info styling */
            .product-info-mobile {
                border-top: 1px solid #e9ecef;
                padding-top: 8px;
                margin-top: 8px;
                white-space: normal;
                word-wrap: break-word;
                word-break: break-word;
            }
            
            .product-info-mobile .amount-high,
            .product-info-mobile .amount-medium,
            .product-info-mobile .amount-low {
                font-weight: 600;
                font-size: 14px;
                margin-bottom: 4px;
            }
            
            .product-info-mobile .combo-order-display {
                margin-top: 4px;
                font-size: 12px;
                line-height: 1.4;
            }
            
            .product-info-mobile .combo-selections-list {
                margin-top: 4px;
                font-size: 11px;
                color: #666;
                margin-left: 10px;
                border-left: 2px solid #e9ecef;
                padding-left: 8px;
            }
            
            .product-info-mobile .combo-selections-list div {
                margin-bottom: 2px;
            }
            
            /* Ensure mobile layout is clean */
            #Products tbody td:nth-child(2) {
                width: auto !important;
                max-width: none !important;
            }
            
            /* Mobile table layout */
            #Products {
                table-layout: auto;
            }
        }
        
        /* Desktop: Hide mobile product info, show separate product column */
        @media (min-width: 769px) {
            .product-info-mobile {
                display: none;
            }
            
            /* Show the separate product column on desktop */
            #Products thead th:nth-child(3),
            #Products tbody td:nth-child(3) {
                display: table-cell;
            }
        }
        
        /* Product column styling for full text display */
        #Products tbody td:nth-child(3) {
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
            max-width: 200px;
            min-width: 200px;
            width: 200px;
        }
        
        /* Courier Status Badge Hover Effects */
        .courier-status-badge {
            transition: all 0.3s ease;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .courier-status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            opacity: 0.9;
        }
        
        .courier-status-badge:active {
            transform: translateY(0);
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .courier-status-badge .fa-sync-alt {
            transition: transform 0.3s ease;
        }
        
        .courier-status-badge:hover .fa-sync-alt {
            transform: rotate(180deg);
        }
        
        /* Set proper widths for all columns */
        #Products thead th:nth-child(1) {
            width: 50px;
            min-width: 50px;
            max-width: 50px;
        }
        
        #Products thead th:nth-child(2) {
            width: 180px;
            min-width: 180px;
            max-width: 180px;
        }
        
        #Products thead th:nth-child(3) {
            width: 200px;
            min-width: 200px;
            max-width: 200px;
        }
        
        #Products thead th:nth-child(4) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
        }
        
        #Products thead th:nth-child(5) {
            width: 140px;
            min-width: 140px;
            max-width: 140px;
        }
        
        #Products thead th:nth-child(6) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
        }
        
        #Products thead th:nth-child(7) {
            width: 150px;
            min-width: 120px;
            max-width: 150px;
        }
        
        /* Apply the same widths to table body cells */
        #Products tbody td:nth-child(1) {
            width: 50px;
            min-width: 50px;
            max-width: 50px;
        }
        
        #Products tbody td:nth-child(2) {
            width: 180px;
            min-width: 180px;
            max-width: 180px;
        }
        
        #Products tbody td:nth-child(4) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
        }
        
        #Products tbody td:nth-child(5) {
            width: 140px;
            min-width: 140px;
            max-width: 140px;
        }
        
        #Products tbody td:nth-child(6) {
            width: 120px;
            min-width: 120px;
            max-width: 120px;
        }
        
        #Products tbody td:nth-child(7) {
            width: 150px;
            min-width: 120px;
            max-width: 150px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" style="margin-bottom: -15px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Orders</li>
            </ol>
        </nav>
        {{-- <h5 class="mb-2">All Orders</h5> --}}
        {{-- <hr> --}}

        <!-- Advanced Filter Section -->
        <div class="filter-section">
            {{-- <h6 class="mb-3">Advanced Filters</h6> --}}
            <div class="filter-row">
                <div class="filter-group">
                    <!-- <label for="status-filter">Order Status</label> -->
                    <select id="status-filter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="phone_not_rcv">Call Not Received</option>
                        <option value="follow_up">Follow up</option>
                        <option value="processing">Processing</option>
                        <option value="ready_for_delivery">Ready Delivery</option>
                        <option value="delivered">Delivered</option>
                        <option value="on_hold">On Hold</option>
                        <option value="shipped">Shipped</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="filter-group">
                    <!-- <label for="courier-filter">Courier Status</label> -->
                    <select id="courier-filter">
                        <option value="">All Orders</option>
                        <option value="steadfast_sent">Steadfast Sent</option>
                        <option value="steadfast_not_sent">Steadfast Not Sent</option>
                    </select>
                </div>
                <div class="filter-group">
                    <!-- <label for="order-type-filter">Order Type</label> -->
                    <select id="order-type-filter">
                        <option value="">All Types</option>
                        <option value="combo">Combo Orders</option>
                        <option value="regular">Regular Orders</option>
                    </select>
                </div>
                <div class="filter-group">
                    <!-- <label for="amount-min">Min Amount</label> -->
                    <input type="number" id="amount-min" placeholder="Min Amount" min="0">
                </div>
                <div class="filter-group">
                    <!-- <label for="amount-max">Max Amount</label> -->
                    <input type="number" id="amount-max" placeholder="Max Amount" min="0">
                </div>
                <div class="filter-group">
                    <!-- <label for="date-from">Date From</label> -->
                    <input type="date" id="date-from">
                </div>
                <div class="filter-group">
                    <!-- <label for="date-to">Date To</label> -->
                    <input type="date" id="date-to">
                </div>
                <div class="filter-actions">
                    <button class="btn-filter btn-apply" id="apply-filters"><i class="fas fa-filter"></i> Apply Filters</button>
                    <button class="btn-filter btn-clear" id="clear-filters"><i class="fas fa-times"></i> Clear All</button>
                </div>
            </div>
        </div>

        <div class="bulk-actions-container d-flex flex-wrap align-items-center gap-1">
            <div class="">
                <button id="bulk-send-steadfast" class="btn btn-soft-success mb-1"><i class="fas fa-paper-plane"></i> Send
                    to Steadfast</button>
            </div>
            <div class="">
                <button id="bulk-send-pathao" class="btn btn-soft-primary mb-1"><i class="fas fa-shipping-fast"></i> Send
                    to Pathao</button>
            </div>
            <div class="">
                <button id="bulk-print-invoices" class="btn btn-soft-info mb-1"><i class="fas fa-file-invoice"></i> Print Invoices</button>
            </div>
            <div class="">
                <button id="bulk-print-package-slips" class="btn btn-soft-warning mb-1"><i class="fas fa-box"></i> Print Package Slips</button>
            </div>
            <div class="">
                <button id="bulk-delete-orders" class="btn btn-soft-danger mb-1"><i class="fas fa-trash-alt"></i> Delete</button>
            </div>
            <div class="">
                <button id="export-selected" class="btn btn-soft-info mb-1"><i class="fas fa-download"></i> Export</button>
            </div>
            <div class="bulk-status-group d-flex align-items-center gap-2 mb-1">
                <div class="input-group input-group-sm" style="min-width: 180px;">
                    <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                    <select id="bulk-status-select" class="form-select">
                        <option value="">Status…</option>
                        <option value="pending">Pending</option>
                        <option value="phone_not_rcv">Call Not Received</option>
                        <option value="follow_up">Follow up</option>
                        <option value="processing">Processing</option>
                        <option value="ready_for_delivery">Ready Delivery</option>
                        <option value="delivered">Delivered</option>
                        <option value="on_hold">On Hold</option>
                        <option value="shipped">Shipped</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <button id="bulk-update-status" class="d-flex align-items-center gap-1 btn btn-soft-primary btn-sm">
                    <i class="fas fa-random"></i>
                    <span class="d-none d-md-inline">Update</span>
                </button>
            </div>
            <div class="bulk-block-group d-flex align-items-center gap-2 mb-1">
                <div class="input-group input-group-sm" style="min-width: 60px;">
                    <span class="input-group-text text-danger"><i class="fas fa-ban"></i></span>
                    <select id="bulk-block-type" class="form-select">
                        <option value="phone">Block by Phone</option>
                        <option value="ip">Block by IP</option>
                    </select>
                </div>
                <button id="bulk-block-selected" class="d-flex align-items-center gap-1 btn btn-soft-danger btn-sm">
                    <i class="fas fa-user-slash"></i>
                    <span class="d-none d-md-inline">Block</span>
                </button>
            </div>
            <div class="bulk-assign-group d-flex align-items-center gap-2 mb-1">
                <div class="input-group input-group-sm assign-input-group">
                    <span class="input-group-text"><i class="fas fa-user-plus"></i></span>
                    <select id="bulk-assign-user" class="form-select">
                        <option value="">Select team member…</option>
                        @foreach($assignableStaff as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}{{ $staff->email ? ' — '.$staff->email : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <button id="bulk-assign-orders" class="d-flex align-items-center gap-1 btn btn-soft-dark btn-sm assign-btn">
                    <i class="fas fa-user-check"></i>
                    <span class="d-none d-md-inline">Assign</span>
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-soft-primary btn-sm">
                    <i class="fas fa-list"></i>
                    <span class="d-none d-md-inline">All Orders</span>
                </a>
                <a href="{{ route('admin.asigned.orders') }}" class="btn btn-soft-primary btn-sm">
                    <i class="fas fa-user"></i>
                    <span class="d-none d-md-inline">My Orders</span>
                </a>
                <a href="{{ route('admin.incomplete-orders.index') }}" class="btn btn-soft-primary btn-sm">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="d-none d-md-inline">Incomplete Orders</span>
                </a>
                <button id="bulk-refresh-courier-status" class="btn btn-soft-info btn-sm" title="Refresh courier status for all orders in transit">
                    <i class="fas fa-sync-alt"></i>
                    <span class="d-none d-md-inline">Refresh Courier</span>
                </button>
            </div>
        </div>

         <!-- Status Filter Bar -->
         <div class="status-filter-bar mb-1">
            <div class="d-flex align-items-center flex-wrap gap-1">
                <span class="status-filter-item active" data-status="">
                    <strong>All</strong> <span class="status-count">({{ $statusCounts['all'] ?? 0 }})</span>
                </span>
                <span class="status-separator">|</span>
                <span class="status-filter-item" data-status="pending">
                    Pending <span class="status-count">({{ $statusCounts['pending'] ?? 0 }})</span>
                </span>
                <span class="status-separator">|</span>
                <span class="status-filter-item" data-status="phone_not_rcv">
                    Call Not Received <span class="status-count">({{ $statusCounts['phone_not_rcv'] ?? 0 }})</span>
                </span>
                <span class="status-separator">|</span>
                <span class="status-filter-item" data-status="follow_up">
                    Follow up <span class="status-count">({{ $statusCounts['follow_up'] ?? 0 }})</span>
                </span>
                <span class="status-separator">|</span>
                <span class="status-filter-item" data-status="processing">
                    Processing <span class="status-count">({{ $statusCounts['processing'] ?? 0 }})</span>
                </span>
                <span class="status-separator">|</span>
                <span class="status-filter-item" data-status="ready_for_delivery">
                    Ready Delivery <span class="status-count">({{ $statusCounts['ready_for_delivery'] ?? 0 }})</span>
                </span>
                <span class="status-separator">|</span>
                <span class="status-filter-item" data-status="shipped">
                    Shipped <span class="status-count">({{ $statusCounts['shipped'] ?? 0 }})</span>
                </span>
                <span class="status-separator">|</span>
                <span class="status-filter-item" data-status="delivered">
                    Delivered <span class="status-count">({{ $statusCounts['delivered'] ?? 0 }})</span>
                </span>
                <span class="status-separator">|</span>
                <span class="status-filter-item" data-status="on_hold">
                    On Hold <span class="status-count">({{ $statusCounts['on_hold'] ?? 0 }})</span>
                </span>
                <span class="status-separator">|</span>
                <span class="status-filter-item" data-status="cancelled">
                    Cancelled <span class="status-count">({{ $statusCounts['cancelled'] ?? 0 }})</span>
                </span>
            </div>
        </div>


        <table class="table table-striped" id="Products" style="width:100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-orders"></th>
                    <th>Customer Info</th>
                    <th>Product Price & Name</th>
                    <th>Status</th>
                        <th>Fraud Check</th>
                        <th>Order at</th>
                        <th>Note</th>
                </tr>
            </thead>
            <tbody>
                {{-- DataTables will populate this via AJAX server-side processing --}}
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
                        <input type="hidden" name="payment_method" id="modalPaymentMethod">
                        <select name="status" id="modalStatusSelect" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="phone_not_rcv">Call Not Received</option>
                            <option value="follow_up">Follow up</option>
                            <option value="processing">Processing</option>
                            <option value="ready_for_delivery">Ready Delivery</option>
                            <option value="delivered">Delivered</option>
                            <option value="on_hold">On Hold</option>
                            <option value="shipped">Shipped</option>
                            <option value="cancelled">Cancelled</option>
                        </select>

                        <!-- Purchase Event Section (COD & Offline Orders) -->
                        <div id="codPurchaseEventSection" class="mt-3 p-3 border rounded bg-light" style="display: none;">
                            <h6 class="mb-2"><i class="fas fa-chart-line me-1"></i> Purchase Event (Analytics)</h6>
                            <p class="text-muted small mb-2" id="purchaseEventDescription">
                                This order has a pending purchase event. Click below to send the purchase event to Facebook/Google.
                            </p>
                            <div id="purchaseEventStatus" class="mb-2"></div>
                            <button type="button" id="firePurchaseEventBtn" class="btn btn-success btn-sm">
                                <i class="fas fa-paper-plane me-1"></i> Fire Purchase Event
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Change Status</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Hide Fraud Checker Column */
        /* .fraud-check-column {
            display: none !important;
        } */
    </style>

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.0/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

    <script>
        const fraudCheckQueue = [];
        let fraudCheckProcessing = false;
        const fraudCheckUrlTemplate = "{{ route('admin.fraud-checker.check-order-fraud', ['orderId' => '__ORDER_ID__']) }}";

        function csrfTokenValue() {
            const el = document.getElementById('laravel-csrf-token');
            return el ? el.value : '{{ csrf_token() }}';
        }

        function escapeHtml(value) {
            if (value === undefined || value === null) {
                return '';
            }
            const div = document.createElement('div');
            div.textContent = value;
            return div.innerHTML;
        }

        function getRiskLevelClass(riskLevel) {
            switch ((riskLevel || '').toLowerCase()) {
                case 'high':
                    return 'badge bg-danger';
                case 'medium':
                    return 'badge bg-warning';
                case 'low':
                    return 'badge bg-info';
                case 'very_low':
                    return 'badge bg-success';
                default:
                    return 'badge bg-secondary';
            }
        }

        function getRiskLevelDisplay(riskLevel) {
            switch ((riskLevel || '').toLowerCase()) {
                case 'high':
                    return 'High Risk';
                case 'medium':
                    return 'Medium Risk';
                case 'low':
                    return 'Low Risk';
                case 'very_low':
                    return 'Very Low Risk';
                default:
                    return 'Unknown';
            }
        }

        function renderFraudResult(result, message = '') {
            if (!result) {
                return '';
            }

            const badgeClass = result.risk_level_badge_class || getRiskLevelClass(result.risk_level);
            const displayText = result.risk_level_display || getRiskLevelDisplay(result.risk_level);
            const successRate = result.success_rate_display
                || (result.has_courier_history ? `${Number(result.delivery_success_rate || 0).toFixed(2)}% Success` : '0% (New Customer)');
            const relativeLastChecked = result.relative_last_checked || '';
            const formattedLastChecked = result.formatted_last_checked || '';
            const lastCheckedText = relativeLastChecked || formattedLastChecked;
            const timeHtml = lastCheckedText
                ? `<small class="${result.is_stale ? 'text-warning' : 'text-muted'}">(${escapeHtml(lastCheckedText)})</small>`
                : '';
            const messageHtml = message
                ? `<small class="text-muted d-block mt-1">${escapeHtml(message)}</small>`
                : '';

            return `<div class="fraud-check-info fraud-check-column">
                <span class="${escapeHtml(badgeClass)} fraud-risk-badge">${escapeHtml(displayText)}</span>
                <div class="fraud-success-rate">${escapeHtml(successRate)}</div>
                ${timeHtml}
                ${messageHtml}
            </div>`;
        }

        function renderFraudMissing(orderId, phone, message) {
            const safeMessage = message ? `<div class="text-muted small mt-1">${escapeHtml(message)}</div>` : '';
            return `<div class="fraud-check-missing fraud-check-column">
                <small class="text-muted">No data</small>
                <button class="btn btn-sm btn-outline-secondary check-fraud-cache-btn" data-order-id="${escapeHtml(orderId)}" data-phone="${escapeHtml(phone || '')}" title="Check from cache">
                    <i class="fas fa-database"></i>
                </button>
                ${safeMessage}
            </div>`;
        }

        function processFraudCheckQueue() {
            if (fraudCheckProcessing) {
                return;
            }

            const nextElement = fraudCheckQueue.shift();
            if (!nextElement) {
                return;
            }

            if (!document.body.contains(nextElement)) {
                processFraudCheckQueue();
                return;
            }

            const orderId = nextElement.dataset.orderId;
            const phone = nextElement.dataset.phone || '';
            if (!orderId) {
                processFraudCheckQueue();
                return;
            }

            fraudCheckProcessing = true;

            const url = fraudCheckUrlTemplate.replace('__ORDER_ID__', orderId);
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfTokenValue(),
                },
                body: JSON.stringify({}),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.result) {
                        nextElement.outerHTML = renderFraudResult(data.result, data.message || '');
                    } else {
                        const message = data && data.message ? data.message : 'No fraud data found';
                        nextElement.outerHTML = renderFraudMissing(orderId, phone, message);
                    }
                })
                .catch(() => {
                    nextElement.outerHTML = renderFraudMissing(orderId, phone, 'Fraud check failed');
                })
                .finally(() => {
                    fraudCheckProcessing = false;
                    setTimeout(processFraudCheckQueue, 150);
                });
        }

        function enqueueFraudChecksFromTable() {
            const pendingElements = document.querySelectorAll('.fraud-check-loading[data-order-id]:not([data-fraud-enqueued])');
            pendingElements.forEach(element => {
                element.setAttribute('data-fraud-enqueued', 'true');
                fraudCheckQueue.push(element);
            });

            if (pendingElements.length) {
                setTimeout(processFraudCheckQueue, 100);
            }
        }

        $(document).ready(function() {
            let customFilters = {};

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
                        d.date_from = $('#date-from').val();
                        d.date_to = $('#date-to').val();
                        @if(isset($isAssignedOrdersPage) && $isAssignedOrdersPage)
                            d.assigned_to_me = 1;
                        @endif
                    }
                },
                columns: [
                    { data: 'select', name: 'select', orderable: false, searchable: false, width: '50px' },
                    { data: 'customer_info', name: 'name', orderable: false, searchable: true, width: '180px' },
                    { data: 'product_price_and_name', name: 'total', orderable: false, searchable: true, width: '200px' },
                    { data: 'status_badge', name: 'status', orderable: false, searchable: false, width: '120px' },
                    { data: 'fraud_check', name: 'fraud_check', orderable: false, searchable: false, className: 'fraud-check-column', width: '140px' },
                    { data: 'order_at', name: 'created_at', width: '120px' },
                    { data: 'note', name: 'admin_note', orderable: false, searchable: true, width: '150px' },
                ],
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copy',
                        className: 'btn btn-sm btn-outline-secondary'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-sm btn-outline-secondary'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
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
                    }
                ],
                responsive: true,
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                // Sort by "Order at" column (index 5) using numeric data-order timestamp
                order: [[5, 'desc']]
            });

            window.ordersDataTable = table;
            table.on('draw.dt', function() {
                enqueueFraudChecksFromTable();
            });
            enqueueFraudChecksFromTable();

            // Status filter bar functionality
            $('.status-filter-item').on('click', function() {
                const status = $(this).data('status');
                
                // Update active state
                $('.status-filter-item').removeClass('active');
                $(this).addClass('active');
                
                // Set the status filter dropdown
                $('#status-filter').val(status);
                
                // Reload table with new filter
                table.ajax.reload();
            });

            // Filter functionality
            $('#apply-filters').on('click', function() {
                table.ajax.reload();
            });

            $('#clear-filters').on('click', function() {
                $('#status-filter, #courier-filter, #order-type-filter').val('');
                $('#amount-min, #amount-max, #date-from, #date-to').val('');
                
                // Reset status filter bar
                $('.status-filter-item').removeClass('active');
                $('.status-filter-item[data-status=""]').addClass('active');
                
                table.ajax.reload();
            });
            
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

            // Remove client-side custom filter. Filters are passed with ajax.data

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
                
                // Check if already in edit mode
                if ($(this).find('input').length > 0) {
                    return; // Already editing, don't start another edit session
                }
                
                const noteElement = $(this);
                const orderId = noteElement.data('order-id');
                const currentNote = noteElement.text().trim() === 'Add Note' ? '' : noteElement.text().trim();
                
                // Store the original text for restoration if needed
                const originalText = noteElement.text().trim();
                
                // Replace with input field
                noteElement.html(`<input type="text" class="editable-note-input" value="${currentNote}" style="width: 100%; padding: 2px; border: 1px solid #197A94; border-radius: 3px;" />`);
                
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
                                    noteElement.text(originalText);
                                }
                            },
                            error: function() {
                                alert('An error occurred while updating the note.');
                                noteElement.text(originalText);
                            }
                        });
                    }
                });
                
                // Handle Escape key to cancel editing
                inputField.on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        noteElement.text(originalText);
                    }
                });
            });

            // Offline order sources that can have delayed purchase events
            var offlineOrderSources = ['Physical Store', 'Phone Call', 'WhatsApp', 'Messenger', 'Facebook', 'Instagram'];

            // When badge is clicked, show modal (delegated)
            $(document).on('click', '.change-status-btn', function(event) {
                event.stopPropagation();
                var orderId = $(this).data('order-id');
                var currentStatus = $(this).data('current-status');
                var paymentMethod = $(this).data('payment-method') || '';
                var orderSource = $(this).data('order-source') || '';

                $('#modalOrderId').val(orderId);
                $('#modalStatusSelect').val(currentStatus);
                $('#modalPaymentMethod').val(paymentMethod);

                // Check if this order can have delayed purchase events (COD or offline source)
                var isCod = paymentMethod === 'cod';
                var isOfflineSource = offlineOrderSources.indexOf(orderSource) !== -1;

                // Show/hide purchase event section for COD or offline orders
                if (isCod || isOfflineSource) {
                    $('#codPurchaseEventSection').show();
                    checkPendingPurchaseEvent(orderId);
                } else {
                    $('#codPurchaseEventSection').hide();
                }

                $('#statusChangeModal').modal('show');
            });

            // Check pending purchase event status
            function checkPendingPurchaseEvent(orderId) {
                $('#purchaseEventStatus').html('<span class="text-muted"><i class="fas fa-spinner fa-spin"></i> Checking...</span>');
                $('#firePurchaseEventBtn').prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.orders.checkPendingPurchaseEvent') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_id: orderId
                    },
                    success: function(response) {
                        if (response.was_fired) {
                            $('#purchaseEventStatus').html('<span class="text-success"><i class="fas fa-check-circle"></i> Event already fired at ' + new Date(response.fired_at).toLocaleString() + '</span>');
                            $('#firePurchaseEventBtn').hide();
                        } else if (response.fire_failed) {
                            $('#purchaseEventStatus').html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Previous attempt failed: ' + (response.fire_error || 'Unknown error') + '</span>');
                            $('#firePurchaseEventBtn').show().prop('disabled', false).text('Retry Fire Event');
                        } else if (response.has_pending_event) {
                            $('#purchaseEventStatus').html('<span class="text-warning"><i class="fas fa-clock"></i> Event pending - ready to fire</span>');
                            $('#firePurchaseEventBtn').show().prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Fire Purchase Event');
                        } else {
                            $('#purchaseEventStatus').html('<span class="text-info"><i class="fas fa-info-circle"></i> No pending event found - will create and fire</span>');
                            $('#firePurchaseEventBtn').show().prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Create & Fire Event');
                        }
                    },
                    error: function() {
                        $('#purchaseEventStatus').html('<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Error checking status</span>');
                        $('#firePurchaseEventBtn').show().prop('disabled', false);
                    }
                });
            }

            // Fire purchase event button handler
            $('#firePurchaseEventBtn').on('click', function() {
                var orderId = $('#modalOrderId').val();
                var btn = $(this);

                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Firing...');

                $.ajax({
                    url: '{{ route('admin.orders.firePurchaseEvent') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_id: orderId
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#purchaseEventStatus').html('<span class="text-success"><i class="fas fa-check-circle"></i> ' + response.message + '</span>');
                            btn.hide();
                        } else {
                            $('#purchaseEventStatus').html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> ' + response.message + '</span>');
                            btn.prop('disabled', false).html('<i class="fas fa-redo me-1"></i> Retry');
                        }
                    },
                    error: function(xhr) {
                        var message = xhr.responseJSON?.message || 'An error occurred';
                        $('#purchaseEventStatus').html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> ' + message + '</span>');
                        btn.prop('disabled', false).html('<i class="fas fa-redo me-1"></i> Retry');
                    }
                });
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
                            // Update the badge inline or refresh the table without full reload
                            if (window.ordersDataTable) {
                                window.ordersDataTable.ajax.reload(null, false);
                            } else {
                                const badge = document.querySelector('.change-status-btn[data-order-id="' + orderId + '"]');
                                if (badge) {
                                    badge.dataset.currentStatus = newStatus;
                                    badge.textContent = newStatus.replace('_', ' ');
                                    badge.className = 'order-status-badge order-status-' + newStatus + ' change-status-btn';
                                    const row = badge.closest('tr');
                                    if (row) {
                                        row.className = row.className
                                            .split(' ')
                                            .filter(c => !c.startsWith('order-status-'))
                                            .concat(['order-status-' + newStatus])
                                            .join(' ');
                                    }
                                }
                            }
                            $('#statusChangeModal').modal('hide');
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while updating the status.');
                    }
                });
            });

            // Bulk assign functionality
            const bulkAssignBtn = $('#bulk-assign-orders');
            bulkAssignBtn.on('click', function() {
                const userId = $('#bulk-assign-user').val();
                if (!userId) {
                    alert('Please select a staff member to assign the orders to.');
                    return;
                }

                const selectedOrders = $('.order-checkbox:checked').map(function() {
                    return this.value;
                }).get();

                if (selectedOrders.length === 0) {
                    alert('Please select at least one order to assign.');
                    return;
                }

                const originalText = bulkAssignBtn.html();
                bulkAssignBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Assigning...');

                fetch("{{ route('admin.orders.bulk-assign') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        order_ids: selectedOrders
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Orders assigned successfully.');
                        $('#bulk-assign-user').val('');
                        $('.order-checkbox').prop('checked', false);
                        $('#select-all-orders').prop('checked', false).prop('indeterminate', false);
                        if (window.ordersDataTable) {
                            window.ordersDataTable.ajax.reload(null, false);
                        }
                    } else {
                        alert(data.message || 'Failed to assign orders.');
                    }
                })
                .catch(() => {
                    alert('An error occurred while assigning orders.');
                })
                .finally(() => {
                    bulkAssignBtn.prop('disabled', false).html(originalText);
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

            // Bulk status update
            $('#bulk-update-status').on('click', function() {
                const status = $('#bulk-status-select').val();
                const selected = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);

                if (!status) {
                    alert('Please select a status.');
                    return;
                }
                if (selected.length === 0) {
                    alert('Please select at least one order.');
                    return;
                }

                const btn = $('#bulk-update-status');
                const originalText = btn.html();
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');

                fetch("{{ route('admin.orders.bulkUpdateStatus') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_ids: selected,
                        status: status
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Status updated.');
                        if (window.ordersDataTable) {
                            window.ordersDataTable.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                    } else {
                        alert(data.message || 'Failed to update status.');
                    }
                })
                .catch(() => {
                    alert('An error occurred while updating status.');
                })
                .finally(() => {
                    btn.prop('disabled', false).html(originalText);
                });
            });

            // Bulk refresh courier status
            $('#bulk-refresh-courier-status').on('click', function() {
                const btn = $(this);
                const originalText = btn.html();

                if (!confirm('This will refresh courier status for all orders in transit. This may take a few minutes. Continue?')) {
                    return;
                }

                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Refreshing...');

                fetch('{{ route("admin.orders.bulkRefreshCourierStatus") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message + '\n\nTotal checked: ' + data.total);
                        if (window.ordersDataTable) {
                            window.ordersDataTable.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                    } else {
                        alert(data.message || 'Failed to refresh courier status.');
                    }
                })
                .catch(() => {
                    alert('An error occurred while refreshing courier status.');
                })
                .finally(() => {
                    btn.prop('disabled', false).html(originalText);
                });
            });

            // Bulk block (phone/IP) functionality
            $('#bulk-block-selected').on('click', function() {
                const blockType = $('#bulk-block-type').val() || 'phone';
                const selectedCheckboxes = Array.from(document.querySelectorAll('.order-checkbox:checked'));

                if (selectedCheckboxes.length === 0) {
                    alert('Please select at least one order to block.');
                    return;
                }

                const values = selectedCheckboxes
                    .map(cb => {
                        const raw = blockType === 'ip' ? cb.dataset.ip : cb.dataset.phone;
                        return (raw || '').trim();
                    })
                    .filter(val => val.length > 0);

                const uniqueValues = [...new Set(values)];

                if (uniqueValues.length === 0) {
                    alert(`No ${blockType === 'ip' ? 'IP addresses' : 'phone numbers'} found on the selected orders.`);
                    return;
                }

                const blockBtn = $('#bulk-block-selected');
                const originalText = blockBtn.html();
                blockBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Blocking...');

                fetch("{{ route('admin.fraud-protection.add-to-blacklist') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        type: blockType,
                        values: uniqueValues
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || `Blocked ${uniqueValues.length} ${blockType === 'ip' ? 'IP' : 'phone'} entr${uniqueValues.length > 1 ? 'ies' : 'y'}.`);
                    } else {
                        alert(data.message || 'Failed to update blacklist.');
                    }
                })
                .catch(() => {
                    alert('An error occurred while updating the blacklist.');
                })
                .finally(() => {
                    blockBtn.prop('disabled', false).html(originalText);
                });
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
            // Custom checkbox handling for bulk operations
            $(document).ready(function() {
                // Wait for DataTables to be fully initialized
                const table = $('#Products').DataTable();
                
                // Prevent checkbox clicks from bubbling to row click handlers
                $(document).on('click', 'input.order-checkbox, #select-all-orders', function(e) {
                    e.stopPropagation();
                });
                
                // Individual checkbox change handler
                $(document).on('change', 'input.order-checkbox', function(e) {
                    updateSelectAllState();
                });
                
                // Select all functionality
                $(document).on('change', '#select-all-orders', function(e) {
                    const isChecked = this.checked;
                    $('input.order-checkbox').prop('checked', isChecked);
                });
                
                // Function to update select-all checkbox state
                function updateSelectAllState() {
                    const totalCheckboxes = $('input.order-checkbox').length;
                    const checkedCheckboxes = $('input.order-checkbox:checked').length;
                    
                    if (checkedCheckboxes === 0) {
                        $('#select-all-orders').prop('checked', false).prop('indeterminate', false);
                    } else if (checkedCheckboxes === totalCheckboxes) {
                        $('#select-all-orders').prop('checked', true).prop('indeterminate', false);
                    } else {
                        $('#select-all-orders').prop('checked', false).prop('indeterminate', true);
                    }
                }
                
                // Initialize select-all state after DataTables is ready
                table.on('draw.dt', function() {
                    setTimeout(updateSelectAllState, 100);
                });
                
                // Initial state update
                setTimeout(() => {
                    updateSelectAllState();
                }, 500);
            });

            // Bulk Send functionality
            const bulkSendBtn = document.getElementById('bulk-send-steadfast');
            if (bulkSendBtn) {
                bulkSendBtn.addEventListener('click', function() {
                    // Filter out orders already sent to ANY courier
                    const allSelected = Array.from(document.querySelectorAll('.order-checkbox:checked'));
                    const alreadySent = allSelected.filter(cb => cb.dataset.courierSent === 'true');
                    const selected = allSelected
                        .filter(cb => cb.dataset.courierSent !== 'true')
                        .map(cb => cb.value);

                    if (selected.length === 0) {
                        if (alreadySent.length > 0) {
                            const providers = alreadySent.map(cb => cb.dataset.courierProvider).filter(p => p);
                            alert(`All selected orders have already been sent to a courier provider (${[...new Set(providers)].join(', ')}). Cannot send to Steadfast.`);
                        } else {
                            alert('Please select at least one order to send to Steadfast.');
                        }
                        return;
                    }
                    
                    if (alreadySent.length > 0) {
                        const providers = [...new Set(alreadySent.map(cb => cb.dataset.courierProvider).filter(p => p))];
                        alert(`Note: ${alreadySent.length} order(s) already sent to ${providers.join(', ')} will be excluded.`);
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

            // Bulk Send functionality - Pathao
            const bulkSendPathaoBtn = document.getElementById('bulk-send-pathao');
            if (bulkSendPathaoBtn) {
                bulkSendPathaoBtn.addEventListener('click', function() {
                    // Filter out orders already sent to ANY courier
                    const allSelected = Array.from(document.querySelectorAll('.order-checkbox:checked'));
                    const alreadySent = allSelected.filter(cb => cb.dataset.courierSent === 'true');
                    const selected = allSelected
                        .filter(cb => cb.dataset.courierSent !== 'true')
                        .map(cb => cb.value);

                    if (selected.length === 0) {
                        if (alreadySent.length > 0) {
                            const providers = alreadySent.map(cb => cb.dataset.courierProvider).filter(p => p);
                            alert(`All selected orders have already been sent to a courier provider (${[...new Set(providers)].join(', ')}). Cannot send to Pathao.`);
                        } else {
                            alert('Please select at least one order to send to Pathao.');
                        }
                        return;
                    }
                    
                    if (alreadySent.length > 0) {
                        const providers = [...new Set(alreadySent.map(cb => cb.dataset.courierProvider).filter(p => p))];
                        alert(`Note: ${alreadySent.length} order(s) already sent to ${providers.join(', ')} will be excluded.`);
                    }

                    bulkSendPathaoBtn.disabled = true;
                    bulkSendPathaoBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

                    fetch("{{ route('admin.pathao.sendBulk') }}", {
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
                                alert(data.message || 'Bulk orders sent to Pathao successfully!');
                                location.reload(); // Reload to update UI
                            } else {
                                alert(data.message || 'Failed to send bulk orders to Pathao.');
                            }
                        })
                        .catch(() => {
                            alert('An error occurred while sending bulk orders to Pathao.');
                        })
                        .finally(() => {
                            bulkSendPathaoBtn.disabled = false;
                            bulkSendPathaoBtn.innerHTML = '<i class="fas fa-shipping-fast"></i> Bulk Send to Pathao';
                        });
                });
            }

            // Check fraud from database cache
            $(document).on('click', '.check-fraud-cache-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const btn = $(this);
                const orderId = btn.data('order-id');
                const phone = btn.data('phone');
                
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                
                $.ajax({
                    url: '{{ route('admin.fraud-checker.check-order-fraud', ':orderId') }}'.replace(':orderId', orderId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        from_cache: true
                    },
                    success: function(response) {
                        if (response.success && response.result) {
                            const cell = btn.closest('td');
                            if (cell) {
                                cell.innerHTML = renderFraudResult(response.result, response.message || '');
                            }
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

                    let popupBlockedNoticeShown = false;

                    selected.forEach((orderId, index) => {
                        const url = `/admin/pos/print-invoice/${orderId}`;
                        setTimeout(() => {
                            const newWindow = window.open(url, '_blank');
                            if (!newWindow && !popupBlockedNoticeShown) {
                                alert('Your browser blocked the invoice pop-up windows. Please allow pop-ups for this site and try again.');
                                popupBlockedNoticeShown = true;
                            }
                        }, index * 600);
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

                    let popupBlockedNoticeShown = false;

                    selected.forEach((orderId, index) => {
                        const url = `/admin/pos/print-package-slip/${orderId}`;
                        setTimeout(() => {
                            const newWindow = window.open(url, '_blank');
                            if (!newWindow && !popupBlockedNoticeShown) {
                                alert('Your browser blocked the package slip pop-up windows. Please allow pop-ups for this site and try again.');
                                popupBlockedNoticeShown = true;
                            }
                        }, index * 600);
                    });
                });
            }

            // Courier Status Click Handler
            document.addEventListener('click', function(e) {
                if (e.target.closest('.courier-status-badge')) {
                    const badge = e.target.closest('.courier-status-badge');
                    const orderId = badge.dataset.orderId;
                    const courier = badge.dataset.courier;
                    
                    // Show loading state
                    const statusText = badge.querySelector('.courier-status-text');
                    const originalText = statusText.textContent;
                    const originalBg = badge.style.background;
                    const originalColor = badge.style.color;
                    const originalBorder = badge.style.borderColor;
                    
                    statusText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';
                    
                    // Disable badge during request
                    badge.style.pointerEvents = 'none';
                    badge.style.opacity = '0.7';
                    
                    // Make API call based on courier provider
                    let apiUrl = '';
                    if (courier === 'pathao') {
                        apiUrl = "{{ route('admin.pathao.orderStatus', ':orderId') }}".replace(':orderId', orderId);
                    } else if (courier === 'steadfast') {
                        apiUrl = "{{ route('admin.steadfast.orderStatus', ':orderId') }}".replace(':orderId', orderId);
                    }
                    
                    fetch(apiUrl, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.getElementById('laravel-csrf-token').value
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Update the status display
                            statusText.textContent = data.status || 'Unknown';
                            
                            // Update timestamp if available
                            const timestampElement = badge.parentElement.querySelector('.text-muted');
                            if (timestampElement && data.updated_at) {
                                timestampElement.textContent = `Updated ${data.updated_at}`;
                            }
                            
                            // Apply color based on status
                            const statusLower = (data.status || '').toLowerCase();
                            const statusColors = getStatusColors(statusLower);
                            
                            badge.style.background = statusColors.bg;
                            badge.style.color = statusColors.text;
                            badge.style.borderColor = statusColors.border;
                        } else {
                            statusText.textContent = originalText;
                            badge.style.background = originalBg;
                            badge.style.color = originalColor;
                            badge.style.borderColor = originalBorder;
                            alert(data.message || 'Failed to fetch courier status');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching courier status:', error);
                        statusText.textContent = originalText;
                        badge.style.background = originalBg;
                        badge.style.color = originalColor;
                        badge.style.borderColor = originalBorder;
                        alert('Error fetching courier status. Please try again.');
                    })
                    .finally(() => {
                        // Re-enable badge
                        badge.style.pointerEvents = 'auto';
                        badge.style.opacity = '1';
                    });
                }
            });
            
            // Function to get status colors
            function getStatusColors(status) {
                const statusColors = {
                    // Delivered - Green
                    'delivered': { bg: '#e8f5e9', text: '#2e7d32', border: '#81c784' },
                    'delivered approval pending': { bg: '#e8f5e9', text: '#2e7d32', border: '#81c784' },
                    'partial delivered': { bg: '#e8f5e9', text: '#388e3c', border: '#81c784' },
                    
                    // In Transit - Cyan/Teal
                    'in transit': { bg: '#e0f7fa', text: '#00838f', border: '#4dd0e1' },
                    'shipped': { bg: '#e0f7fa', text: '#00838f', border: '#4dd0e1' },
                    'out for delivery': { bg: '#e0f7fa', text: '#00695c', border: '#4dd0e1' },
                    
                    // Processing - Blue
                    'processing': { bg: '#e3f2fd', text: '#1565c0', border: '#90caf9' },
                    'picked up': { bg: '#e3f2fd', text: '#1565c0', border: '#90caf9' },
                    'order created': { bg: '#e3f2fd', text: '#1976d2', border: '#90caf9' },
                    
                    // Pending - Orange/Amber
                    'pending': { bg: '#fff3e0', text: '#e65100', border: '#ffb74d' },
                    'in review': { bg: '#fff3e0', text: '#ef6c00', border: '#ffb74d' },
                    'ready to ship': { bg: '#fff3e0', text: '#e65100', border: '#ffb74d' },
                    
                    // Hold/Unknown - Purple
                    'hold': { bg: '#f3e5f5', text: '#6a1b9a', border: '#ba68c8' },
                    'unknown': { bg: '#f3e5f5', text: '#6a1b9a', border: '#ba68c8' },
                    'unknown approval pending': { bg: '#f3e5f5', text: '#6a1b9a', border: '#ba68c8' },
                    
                    // Cancelled/Failed - Red
                    'cancelled': { bg: '#ffebee', text: '#c62828', border: '#ef5350' },
                    'cancelled approval pending': { bg: '#ffebee', text: '#c62828', border: '#ef5350' },
                    'pickup cancel': { bg: '#ffebee', text: '#c62828', border: '#ef5350' },
                    'pickup cancelled': { bg: '#ffebee', text: '#c62828', border: '#ef5350' },
                    'failed': { bg: '#ffebee', text: '#c62828', border: '#ef5350' },
                    'return': { bg: '#ffebee', text: '#d32f2f', border: '#ef5350' }
                };
                
                return statusColors[status] || { bg: '#e3f2fd', text: '#1976d2', border: '#90caf9' };
            }
        });
    </script>
@endsection
