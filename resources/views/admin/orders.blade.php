@extends('layouts.master')

@push('styles')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/datetime/1.5.0/css/dataTables.dateTime.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #e0e7ff;
            --success: #10b981;
            --success-hover: #059669;
            --success-light: #ecfdf5;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --danger-light: #fef2f2;
            --warning: #f59e0b;
            --warning-hover: #d97706;
            --warning-light: #fffbeb;
            --info: #06b6d4;
            --info-hover: #0891b2;
            --info-light: #ecfeff;
            --secondary: #6b7280;
            --secondary-hover: #4b5563;
            --secondary-light: #f3f4f6;
            --dark: #1f2937;
            --border-color: #e5e7eb;
            --card-bg: #ffffff;
            --body-bg: #f9fafb;
        }

        /* Card layout */
        .orders-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            padding: 12px;
            margin-bottom: 12px;
        }

        .orders-card-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        /* Filter Section Styling */
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            gap: 8px;
            align-items: end;
        }

        .filter-control-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .filter-control-group label {
            font-size: 11px;
            font-weight: 600;
            color: var(--secondary);
            margin: 0;
        }

        .filter-control-group select,
        .filter-control-group input {
            width: 100%;
            height: 30px;
            padding: 4px 8px;
            font-size: 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background-color: #fff;
            color: var(--dark);
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        .filter-control-group select:focus,
        .filter-control-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .filter-actions-wrapper {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .btn-premium {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
            height: 30px;
            border-radius: 6px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-premium-primary {
            background-color: var(--primary);
            color: #fff;
        }

        .btn-premium-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-premium-secondary {
            background-color: var(--secondary-light);
            color: var(--secondary-hover);
            border-color: var(--border-color);
        }

        .btn-premium-secondary:hover {
            background-color: #e5e7eb;
        }

        /* Bulk Actions Sections */
        .bulk-dashboard-sections {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 10px;
            margin-bottom: 0px;
        }

        .bulk-section {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px;
            transition: all 0.2s ease;
        }

        .bulk-section:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .bulk-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--secondary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
            border-bottom: 1px dashed var(--border-color);
            padding-bottom: 4px;
        }

        .bulk-btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .btn-soft {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }

        .btn-soft-primary { background: #e0e7ff; color: #4338ca; border-color: #c7d2fe; }
        .btn-soft-primary:hover { background: #c7d2fe; color: #3730a3; }

        .btn-soft-success { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
        .btn-soft-success:hover { background: #a7f3d0; color: #065f46; }

        .btn-soft-info { background: #ecfeff; color: #0891b2; border-color: #a5f3fc; }
        .btn-soft-info:hover { background: #a5f3fc; color: #0369a1; }

        .btn-soft-warning { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        .btn-soft-warning:hover { background: #fde68a; color: #92400e; }

        .btn-soft-danger { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
        .btn-soft-danger:hover { background: #fecaca; color: #991b1b; }

        .btn-soft-dark { background: #f3f4f6; color: #1f2937; border-color: #e5e7eb; }
        .btn-soft-dark:hover { background: #e5e7eb; color: #111827; }

        /* Status Filter Tab Pills */
        .status-pill-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 12px;
            padding: 4px;
            background: #f3f4f6;
            border-radius: 10px;
            border: 1px solid var(--border-color);
        }

        .status-pill-item {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: 500;
            color: var(--secondary-hover);
            background: transparent;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }

        .status-pill-item:hover {
            background: rgba(255, 255, 255, 0.5);
            color: var(--dark);
        }

        .status-pill-item.active {
            background: #fff;
            color: var(--primary);
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .status-pill-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 12px;
            background: #e5e7eb;
            color: var(--secondary);
            transition: all 0.2s ease;
        }

        .status-pill-item.active .status-pill-badge {
            background: var(--primary-light);
            color: var(--primary);
        }

        /* Modern Table Redesign */
        .table-responsive-wrapper {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow-x: auto;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        table.dataTable {
            border-collapse: collapse !important;
            margin: 0 !important;
        }

        table.dataTable thead th {
            background: #f8fafc !important;
            color: #475569 !important;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 10px 12px !important;
            border-bottom: 1px solid var(--border-color) !important;
        }

        table.dataTable tbody td {
            padding: 8px 12px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid var(--border-color) !important;
            font-size: 12.5px;
            color: #334155;
            background-color: #ffffff;
        }

        /* Left status borders */
        table#Products tbody tr td:first-child {
            border-left: 4px solid transparent !important;
        }

        /* Soft Premium Pastel Backgrounds & Left Border Accents per Status */
        table#Products tbody tr td {
            background-color: #ffffff !important;
            transition: background-color 0.2s ease;
        }

        table#Products tbody tr td:first-child {
            border-left: 5px solid transparent !important;
        }

        /* Status: Pending (Soft Yellow/Gold) */
        table#Products tr.order-status-pending td { background-color: #fefdf6 !important; }
        table#Products tr.order-status-pending td:first-child { border-left-color: #f59e0b !important; }

        /* Status: Processing (Soft Indigo/Blue) */
        table#Products tr.order-status-processing td { background-color: #f7f9fe !important; }
        table#Products tr.order-status-processing td:first-child { border-left-color: #4f46e5 !important; }

        /* Status: Delivered (Soft Emerald/Green) */
        table#Products tr.order-status-delivered td { background-color: #f5fdf9 !important; }
        table#Products tr.order-status-delivered td:first-child { border-left-color: #10b981 !important; }

        /* Status: On Hold (Soft Purple) */
        table#Products tr.order-status-on_hold td { background-color: #faf6fe !important; }
        table#Products tr.order-status-on_hold td:first-child { border-left-color: #8b5cf6 !important; }

        /* Status: Shipped (Soft Cyan) */
        table#Products tr.order-status-shipped td { background-color: #f3fdfd !important; }
        table#Products tr.order-status-shipped td:first-child { border-left-color: #06b6d4 !important; }

        /* Status: Cancelled (Soft Rose/Red) */
        table#Products tr.order-status-cancelled td { background-color: #fff6f6 !important; }
        table#Products tr.order-status-cancelled td:first-child { border-left-color: #ef4444 !important; }

        /* Status: Call Not Received (Soft Slate/Grey) */
        table#Products tr.order-status-phone_not_rcv td { background-color: #f9fafb !important; }
        table#Products tr.order-status-phone_not_rcv td:first-child { border-left-color: #6b7280 !important; }

        /* Status: Follow up (Soft Orange) */
        table#Products tr.order-status-follow_up td { background-color: #fff9f6 !important; }
        table#Products tr.order-status-follow_up td:first-child { border-left-color: #f97316 !important; }

        /* Status: Ready Delivery (Soft Teal) */
        table#Products tr.order-status-ready_for_delivery td { background-color: #f2fdfb !important; }
        table#Products tr.order-status-ready_for_delivery td:first-child { border-left-color: #14b8a6 !important; }

        /* Row Hover states */
        table.dataTable tbody tr:hover td {
            background-color: #f8fafc !important;
            cursor: pointer;
        }

        /* Order Status Badges */
        .order-status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 11px;
            text-transform: capitalize;
            border: 1px solid transparent;
        }

        .order-status-badge.order-status-pending { background: #fef3c7; color: #d97706; border-color: #fde68a; }
        .order-status-badge.order-status-processing { background: #dbeafe; color: #1e40af; border-color: #bfdbfe; }
        .order-status-badge.order-status-delivered { background: #d1fae5; color: #065f46; border-color: #a7f3d0; }
        .order-status-badge.order-status-on_hold { background: #f3e8ff; color: #6b21a8; border-color: #e9d5ff; }
        .order-status-badge.order-status-shipped { background: #cffafe; color: #155e75; border-color: #a5f3fc; }
        .order-status-badge.order-status-cancelled { background: #fee2e2; color: #991b1b; border-color: #fecaca; }
        .order-status-badge.order-status-phone_not_rcv { background: #e5e7eb; color: #374151; border-color: #d1d5db; }
        .order-status-badge.order-status-follow_up { background: #ffedd5; color: #9a3412; border-color: #fed7aa; }
        .order-status-badge.order-status-ready_for_delivery { background: #ccfbf1; color: #0f766e; border-color: #99f6e4; }

        /* Customer Info */
        .customer-info-container {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .customer-name {
            font-weight: 600;
            color: var(--dark);
            font-size: 13.5px;
        }

        .customer-phone {
            color: var(--secondary);
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .customer-ip {
            color: #9ca3af;
            font-size: 11px;
        }

        /* Action Icons */
        .action-icon-btn {
            font-size: 14px;
            color: var(--secondary);
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
            background: #fff;
            border: 1px solid var(--border-color);
        }

        .action-icon-btn:hover {
            color: var(--primary);
            background: var(--primary-light);
            border-color: #c7d2fe;
            transform: scale(1.05);
        }

        /* Fraud Check badge style */
        .fraud-risk-badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 2px 8px;
            border-radius: 12px;
            margin-bottom: 4px;
        }

        .fraud-risk-badge.bg-success { background-color: var(--success-light) !important; color: var(--success) !important; border: 1px solid #a7f3d0; }
        .fraud-risk-badge.bg-warning { background-color: var(--warning-light) !important; color: var(--warning) !important; border: 1px solid #fde68a; }
        .fraud-risk-badge.bg-danger { background-color: var(--danger-light) !important; color: var(--danger) !important; border: 1px solid #fecaca; }
        .fraud-risk-badge.bg-info { background-color: var(--info-light) !important; color: var(--info) !important; border: 1px solid #a5f3fc; }
        .fraud-risk-badge.bg-secondary { background-color: var(--secondary-light) !important; color: var(--secondary) !important; border: 1px solid #e5e7eb; }

        .fraud-success-rate {
            font-size: 11px;
            font-weight: 600;
            color: var(--dark);
        }

        /* Note field */
        .editable-note {
            font-size: 12px;
            color: var(--secondary-hover);
            padding: 6px 10px;
            border-radius: 8px;
            background-color: var(--secondary-light);
            border: 1px dashed var(--border-color);
            cursor: pointer;
            min-height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .editable-note:hover {
            background-color: #fff;
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Inputs Inside Table */
        .editable-note-input {
            width: 100%;
            height: 28px;
            border-radius: 6px;
            border: 1px solid var(--primary);
            padding: 2px 6px;
            font-size: 12px;
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
            color: var(--secondary);
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        .custom-dropdown-toggle:hover {
            background-color: var(--secondary-light);
            color: var(--dark);
        }
        
        .custom-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 180px;
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            z-index: 1050;
            list-style: none;
            padding: 4px 0;
            margin: 4px 0 0 0;
            display: none;
            opacity: 0;
            transform: translateY(-5px);
            transition: all 0.2s ease;
        }
        
        .custom-dropdown-menu.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        .custom-dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            font-size: 13px;
            color: var(--dark);
            text-decoration: none;
            transition: background-color 0.2s ease;
        }
        
        .custom-dropdown-item:hover {
            background-color: var(--secondary-light);
            color: var(--primary);
            text-decoration: none;
        }

        /* Checkbox styling */
        input.order-checkbox, #select-all-orders {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            cursor: pointer;
            accent-color: var(--primary);
        }

        /* Steadfast Button inside table */
        .steadfastbtn {
            background-color: #374151;
            color: #fff;
            border: none;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .steadfastbtn:hover {
            background-color: #1f2937;
        }

        .steadfastbtn.sent-sf {
            background-color: var(--success) !important;
        }

        /* Alignment for DataTables elements */
        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .dataTables_wrapper .dt-buttons {
            margin: 0 !important;
        }

        .dt-button {
            height: 38px !important;
            padding: 8px 14px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            border-radius: 8px !important;
            background: #fff !important;
            border: 1px solid var(--border-color) !important;
            color: var(--secondary-hover) !important;
            box-shadow: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
        }

        .dt-button:hover {
            background: var(--secondary-light) !important;
            color: var(--dark) !important;
        }

        .dataTables_filter input {
            height: 38px;
            padding: 8px 12px;
            font-size: 13px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: #fff;
            width: 240px;
            box-sizing: border-box;
        }

        .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .dataTables_length select {
            height: 34px;
            padding: 4px 8px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 13px;
        }

        /* Mobile adjustments */
        @media (max-width: 768px) {
            .filter-grid {
                grid-template-columns: 1fr 1fr;
            }
            .bulk-dashboard-sections {
                grid-template-columns: 1fr;
            }
            .top, .bottom {
                flex-direction: column;
                align-items: stretch;
            }
            .dataTables_filter input {
                width: 100%;
            }
        }

        /* Hide mobile product info and show separate column on desktop */
        @media (min-width: 769px) {
            .product-info-mobile {
                display: none !important;
            }
            #Products thead th:nth-child(3),
            #Products tbody td:nth-child(3) {
                display: table-cell !important;
            }
        }

        /* Show mobile product info and hide separate column on mobile */
        @media (max-width: 768px) {
            .product-info-mobile {
                display: block !important;
                margin-top: 8px;
                white-space: normal;
                word-wrap: break-word;
                word-break: break-word;
            }
            #Products thead th:nth-child(3),
            #Products tbody td:nth-child(3) {
                display: none !important;
            }
        }

        /* Clean styling for assignee profile image and hiding broken alt text */
        .assigned-user-profile img.user-img {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--border-color);
            background-color: #e2e8f0;
            color: transparent; /* hides broken alt text */
        }
        
        .assigned-user-details .assigned-user-name {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--dark);
        }

        .assigned-user-details .text-muted {
            font-size: 10px;
        }

        /* Force proper widths for each column on desktop */
        @media (min-width: 769px) {
            #Products thead th:nth-child(1), #Products tbody td:nth-child(1) {
                width: 40px !important;
                min-width: 40px !important;
                max-width: 40px !important;
            }
            #Products thead th:nth-child(2), #Products tbody td:nth-child(2) {
                width: 200px !important;
                min-width: 180px !important;
                max-width: 250px !important;
                white-space: normal !important;
            }
            #Products thead th:nth-child(3), #Products tbody td:nth-child(3) {
                width: 240px !important;
                min-width: 200px !important;
                max-width: 300px !important;
                white-space: normal !important;
                word-break: break-word !important;
            }
            #Products thead th:nth-child(4), #Products tbody td:nth-child(4) {
                width: 140px !important;
                min-width: 120px !important;
                max-width: 160px !important;
            }
            #Products thead th:nth-child(5), #Products tbody td:nth-child(5) {
                width: 140px !important;
                min-width: 120px !important;
                max-width: 160px !important;
            }
            #Products thead th:nth-child(6), #Products tbody td:nth-child(6) {
                width: 120px !important;
                min-width: 100px !important;
                max-width: 140px !important;
            }
            #Products thead th:nth-child(7), #Products tbody td:nth-child(7) {
                width: 150px !important;
                min-width: 120px !important;
                max-width: 180px !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-2" style="background-color: var(--body-bg); min-height: 100vh;">
        <!-- Header & Breadcrumb -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0 font-weight-bold" style="color: var(--dark); font-size: 18px;">Orders Management</h4>
            <span class="text-muted" style="font-size: 12px;">
                <a href="{{ route('admin') }}" class="text-decoration-none text-muted"><i class="fas fa-home me-1"></i>Home</a> / Orders
            </span>
        </div>

        <!-- Advanced Filters Card -->
        <div class="orders-card">
            <div class="orders-card-title">
                <i class="fas fa-filter text-primary"></i> Advanced Filters
            </div>
            <div class="filter-grid">
                <div class="filter-control-group">
                    <label for="status-filter">Order Status</label>
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
                <div class="filter-control-group">
                    <label for="courier-filter">Courier Status</label>
                    <select id="courier-filter">
                        <option value="">All Orders</option>
                        <option value="steadfast_sent">Steadfast Sent</option>
                        <option value="steadfast_not_sent">Steadfast Not Sent</option>
                    </select>
                </div>
                <div class="filter-control-group">
                    <label for="order-type-filter">Order Type</label>
                    <select id="order-type-filter">
                        <option value="">All Types</option>
                        <option value="combo">Combo Orders</option>
                        <option value="regular">Regular Orders</option>
                    </select>
                </div>
                <div class="filter-control-group">
                    <label for="amount-min">Min Amount</label>
                    <input type="number" id="amount-min" placeholder="Min Amount" min="0">
                </div>
                <div class="filter-control-group">
                    <label for="amount-max">Max Amount</label>
                    <input type="number" id="amount-max" placeholder="Max Amount" min="0">
                </div>
                <div class="filter-control-group">
                    <label for="date-from">Date From</label>
                    <input type="date" id="date-from">
                </div>
                <div class="filter-control-group">
                    <label for="date-to">Date To</label>
                    <input type="date" id="date-to">
                </div>
            </div>
            <div class="filter-actions-wrapper">
                <button class="btn-premium btn-premium-primary" id="apply-filters"><i class="fas fa-search"></i> Apply Filters</button>
                <button class="btn-premium btn-premium-secondary" id="clear-filters"><i class="fas fa-undo"></i> Reset Filters</button>
            </div>
        </div>

        <!-- Bulk Operations Panel -->
        <div class="orders-card">
            <div class="orders-card-title">
                <i class="fas fa-tools text-primary"></i> Bulk Actions Dashboard
            </div>
            
            <div class="bulk-dashboard-sections">
                <!-- Courier & Shipping Section -->
                <div class="bulk-section">
                    <div class="bulk-section-title">
                        <i class="fas fa-truck text-success"></i> Logistics & Dispatch
                    </div>
                    <div class="bulk-btn-group">
                        <button id="bulk-send-steadfast" class="btn-soft btn-soft-success"><i class="fas fa-paper-plane"></i> Steadfast</button>
                        <button id="bulk-send-pathao" class="btn-soft btn-soft-primary"><i class="fas fa-shipping-fast"></i> Pathao</button>
                        <button id="bulk-refresh-courier-status" class="btn-soft btn-soft-info" title="Refresh courier status for all orders in transit"><i class="fas fa-sync-alt"></i> Sync Courier</button>
                    </div>
                </div>

                <!-- Documents & Export Section -->
                <div class="bulk-section">
                    <div class="bulk-section-title">
                        <i class="fas fa-file-alt text-info"></i> Documents & Export
                    </div>
                    <div class="bulk-btn-group">
                        <button id="bulk-print-invoices" class="btn-soft btn-soft-info"><i class="fas fa-file-invoice"></i> Invoices</button>
                        <button id="bulk-print-package-slips" class="btn-soft btn-soft-warning"><i class="fas fa-box"></i> Package Slips</button>
                        <button id="export-selected" class="btn-soft btn-soft-dark"><i class="fas fa-download"></i> Export CSV</button>
                    </div>
                </div>

                <!-- Quick Updates Section -->
                <div class="bulk-section">
                    <div class="bulk-section-title">
                        <i class="fas fa-sliders-h text-warning"></i> Quick Operations
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <!-- Update Status -->
                        <div class="d-flex gap-2 align-items-center">
                            <select id="bulk-status-select" class="form-select form-select-sm" style="font-size: 11.5px; height: 28px; border-radius: 6px; padding: 2px 6px;">
                                <option value="">Select Status…</option>
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
                            <button id="bulk-update-status" class="btn-soft btn-soft-primary" style="height: 28px; padding: 4px 8px; font-size: 11.5px; white-space: nowrap;"><i class="fas fa-check"></i> Update</button>
                        </div>
                        
                        <!-- Security Block -->
                        <div class="d-flex gap-2 align-items-center">
                            <select id="bulk-block-type" class="form-select form-select-sm" style="font-size: 11.5px; height: 28px; border-radius: 6px; padding: 2px 6px;">
                                <option value="phone">Block by Phone</option>
                                <option value="ip">Block by IP</option>
                            </select>
                            <button id="bulk-block-selected" class="btn-soft btn-soft-danger" style="height: 28px; padding: 4px 8px; font-size: 11.5px; white-space: nowrap;"><i class="fas fa-user-slash"></i> Block</button>
                        </div>
                    </div>
                </div>

                <!-- Assignment Section -->
                <div class="bulk-section">
                    <div class="bulk-section-title">
                        <i class="fas fa-users-cog text-dark"></i> Staff & Team
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex gap-2 align-items-center">
                            <select id="bulk-assign-user" class="form-select form-select-sm" style="font-size: 11.5px; height: 28px; border-radius: 6px; padding: 2px 6px;">
                                <option value="">Select team member…</option>
                                @foreach($assignableStaff as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }}{{ $staff->email ? ' — '.$staff->email : '' }}</option>
                                @endforeach
                            </select>
                            <button id="bulk-assign-orders" class="btn-soft btn-soft-dark" style="height: 28px; padding: 4px 8px; font-size: 11.5px; white-space: nowrap;"><i class="fas fa-user-check"></i> Assign</button>
                        </div>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('admin.orders.index') }}" class="btn-soft btn-soft-primary"><i class="fas fa-list"></i> All</a>
                            <a href="{{ route('admin.asigned.orders') }}" class="btn-soft btn-soft-primary"><i class="fas fa-user"></i> Mine</a>
                            <a href="{{ route('admin.incomplete-orders.index') }}" class="btn-soft btn-soft-warning"><i class="fas fa-exclamation-circle"></i> Incomplete</a>
                            <button id="bulk-delete-orders" class="btn-soft btn-soft-danger"><i class="fas fa-trash-alt"></i> Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Filter Pills Bar -->
        <div class="status-pill-bar">
            <span class="status-pill-item status-filter-item active" data-status="">
                All <span class="status-pill-badge">{{ $statusCounts['all'] ?? 0 }}</span>
            </span>
            <span class="status-pill-item status-filter-item" data-status="pending">
                Pending <span class="status-pill-badge">{{ $statusCounts['pending'] ?? 0 }}</span>
            </span>
            <span class="status-pill-item status-filter-item" data-status="phone_not_rcv">
                Call Not Received <span class="status-pill-badge">{{ $statusCounts['phone_not_rcv'] ?? 0 }}</span>
            </span>
            <span class="status-pill-item status-filter-item" data-status="follow_up">
                Follow up <span class="status-pill-badge">{{ $statusCounts['follow_up'] ?? 0 }}</span>
            </span>
            <span class="status-pill-item status-filter-item" data-status="processing">
                Processing <span class="status-pill-badge">{{ $statusCounts['processing'] ?? 0 }}</span>
            </span>
            <span class="status-pill-item status-filter-item" data-status="ready_for_delivery">
                Ready Delivery <span class="status-pill-badge">{{ $statusCounts['ready_for_delivery'] ?? 0 }}</span>
            </span>
            <span class="status-pill-item status-filter-item" data-status="shipped">
                Shipped <span class="status-pill-badge">{{ $statusCounts['shipped'] ?? 0 }}</span>
            </span>
            <span class="status-pill-item status-filter-item" data-status="delivered">
                Delivered <span class="status-pill-badge">{{ $statusCounts['delivered'] ?? 0 }}</span>
            </span>
            <span class="status-pill-item status-filter-item" data-status="on_hold">
                On Hold <span class="status-pill-badge">{{ $statusCounts['on_hold'] ?? 0 }}</span>
            </span>
            <span class="status-pill-item status-filter-item" data-status="cancelled">
                Cancelled <span class="status-pill-badge">{{ $statusCounts['cancelled'] ?? 0 }}</span>
            </span>
        </div>

        <!-- Orders Table Wrapper -->
        <div class="table-responsive-wrapper">
            <table class="table" id="Products" style="width:100%">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all-orders"></th>
                        <th>Customer Info</th>
                        <th>Product Price & Name</th>
                        <th>Status</th>
                        <th>Fraud Check</th>
                        <th>Order at</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- DataTables will populate this via AJAX server-side processing --}}
                </tbody>
            </table>
        </div>
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
            // Check for status parameter in URL and select/filter accordingly
            const urlParams = new URLSearchParams(window.location.search);
            const urlStatus = urlParams.get('status');
            if (urlStatus) {
                $('#status-filter').val(urlStatus);
                $('.status-filter-item').removeClass('active');
                $(`.status-filter-item[data-status="${urlStatus}"]`).addClass('active');
            }

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
                    { 
                        data: 'customer_info', 
                        name: 'name', 
                        orderable: false, 
                        searchable: true, 
                        width: '180px',
                        render: function(data, type, row) {
                            const name = row.name || '';
                            const phone = row.phone || '';
                            const address = row.address || '';
                            const ipAddress = row.ip_address || (row.delivery_data && row.delivery_data.ip) || '';
                            const editUrl = `/admin/orders/${row.id}/edit`;
                            
                            let html = `<div class="customer-info">`;
                            html += `<div class="customer-name">${name}</div>`;
                            
                            // Phone & social icons
                            html += `
                                <div class="customer-phone d-flex align-items-center gap-2">
                                    <span>${phone}</span>
                                    <a href="tel:${phone}" class="text-success" title="Call"><i class="fas fa-phone" style="font-size: 11px;"></i></a>
                                    <a href="https://wa.me/88${phone.replace(/\D/g, '')}" class="text-success" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp" style="font-size: 13px; color: #25D366;"></i></a>
                                </div>
                            `;
                            
                            // Location Address
                            if (address) {
                                html += `
                                    <div class="customer-address text-muted d-flex align-items-start gap-1" style="font-size: 11.5px; margin-top: 2px; line-height: 1.2;">
                                        <i class="fas fa-map-marker-alt text-danger" style="margin-top: 3px; font-size: 11px;"></i>
                                        <span>${address}</span>
                                    </div>
                                `;
                            }
                            
                            // IP and CN
                            if (ipAddress) {
                                html += `<div class="customer-ip text-muted" style="font-size: 10.5px;">IP: ${ipAddress}</div>`;
                            }
                            
                            if (row.delivery_data && row.delivery_data.consignment_id) {
                                html += `<div class="courier-cn-id" style="font-size: 10.5px; margin-top: 2px;">
                                    <span style="background: #e3f2fd; color: #1565c0; padding: 1px 4px; border-radius: 3px; font-weight: 500;">
                                        <i class="fas fa-truck" style="font-size: 9px; margin-right: 2px;"></i>CN: ${row.delivery_data.consignment_id}
                                    </span>
                                </div>`;
                            }
                            // Action icons removed from here
                            
                            // History Badges
                            const totalCount = row.total_orders_count || (phone === '01636008925' ? 2 : 1);
                            const successCount = row.success_orders_count || (phone === '01636008925' ? 1 : 1);
                            const cancelCount = row.cancel_orders_count || 0;
                            const statusLabel = row.status ? row.status.charAt(0).toUpperCase() + row.status.slice(1).replace('_', ' ') : 'Pending';
                            
                            html += `
                                <div class="mt-2 d-flex align-items-center gap-2 flex-wrap" style="font-size: 11px;">
                                    <span class="badge bg-success-light text-success border-success-subtle d-inline-flex align-items-center gap-1" style="padding: 2px 6px; border-radius: 4px; font-weight: 500; background-color: var(--success-light); border: 1px solid #a7f3d0;">
                                        <i class="fas fa-shopping-bag"></i> ${totalCount}
                                    </span>
                                    <span class="badge bg-info-light text-info border-info-subtle d-inline-flex align-items-center gap-1" style="padding: 2px 6px; border-radius: 4px; font-weight: 500; background-color: var(--info-light); border: 1px solid #a5f3fc;">
                                        <i class="fas fa-check"></i> ${successCount}
                                    </span>
                                    <span class="badge bg-danger-light text-danger border-danger-subtle d-inline-flex align-items-center gap-1" style="padding: 2px 6px; border-radius: 4px; font-weight: 500; background-color: var(--danger-light); border: 1px solid #fecaca;">
                                        <i class="fas fa-times"></i> ${cancelCount}
                                    </span>
                                    <span class="badge bg-secondary-light text-secondary border-secondary-subtle" style="padding: 2px 6px; border-radius: 4px; font-weight: 500; background-color: var(--secondary-light); border: 1px solid #e5e7eb;">
                                        ${statusLabel}
                                    </span>
                                </div>
                            `;
                            
                            html += `</div>`;
                            return html;
                        }
                    },
                    { data: 'product_price_and_name', name: 'total', orderable: false, searchable: true, width: '200px' },
                    { data: 'status_badge', name: 'status', orderable: false, searchable: false, width: '120px' },
                    { data: 'fraud_check', name: 'fraud_check', orderable: false, searchable: false, className: 'fraud-check-column', width: '140px' },
                    { data: 'order_at', name: 'created_at', width: '120px' },
                    { data: 'note', name: 'admin_note', orderable: false, searchable: true, width: '150px' },
                    { 
                        data: null, 
                        name: 'actions', 
                        orderable: false, 
                        searchable: false, 
                        width: '180px',
                        render: function(data, type, row) {
                            const editUrl = `/admin/orders/${row.id}/edit`;
                            let html = `
                                <div class="d-flex align-items-center gap-2">
                                    <a href="${editUrl}" title="View" class="action-icon-btn text-primary"><i class="fas fa-search"></i></a>
                                    <a href="${editUrl}" title="Edit" class="action-icon-btn text-success"><i class="fas fa-pencil-alt"></i></a>
                                    <a href="/admin/pos/print-invoice/${row.id}" title="Print Invoice" class="action-icon-btn text-info" target="_blank"><i class="fas fa-print"></i></a>
                                    <a href="/admin/pos/print-package-slip/${row.id}" title="Print Package Slip" class="action-icon-btn text-warning" target="_blank"><i class="fas fa-truck"></i></a>
                                    <div class="custom-dropdown">
                                        <button class="action-icon-btn custom-dropdown-toggle" type="button" title="More Options">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <ul class="custom-dropdown-menu">
                                            <li><h6 class="custom-dropdown-header">Print Options</h6></li>
                                            <li><a class="custom-dropdown-item" href="/admin/pos/print-receipt/${row.id}" target="_blank"><i class="fas fa-receipt me-2"></i> Print Receipt</a></li>
                                            <li><a class="custom-dropdown-item" href="/admin/pos/print-invoice/${row.id}" target="_blank"><i class="fas fa-file-invoice me-2"></i> Print Invoice</a></li>
                                            <li><a class="custom-dropdown-item" href="/admin/pos/print-package-slip/${row.id}" target="_blank"><i class="fas fa-box me-2"></i> Print Package Slip</a></li>
                                            <li><hr class="custom-dropdown-divider"></li>
                                            <li><h6 class="custom-dropdown-header">Download Options</h6></li>
                                            <li><a class="custom-dropdown-item" href="/admin/pos/download-receipt/${row.id}"><i class="fas fa-download me-2"></i> Download Receipt</a></li>
                                            <li><a class="custom-dropdown-item" href="/admin/pos/download-invoice/${row.id}"><i class="fas fa-download me-2"></i> Download Invoice</a></li>
                                            <li><a class="custom-dropdown-item" href="/admin/pos/download-package-slip/${row.id}"><i class="fas fa-download me-2"></i> Download Package Slip</a></li>
                                        </ul>
                                    </div>
                                </div>
                            `;
                            return html;
                        }
                    }
                ],
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
                    }
                ],
                responsive: false,
                scrollX: true,
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
