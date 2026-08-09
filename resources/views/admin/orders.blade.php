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

        /* Modern Table Redesign - OrderFlow Style */
        .table-responsive-wrapper {
            border: 1px solid rgba(200, 196, 213, 0.4) !important;
            border-radius: 2rem !important;
            overflow: hidden !important;
            background: #ffffff !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
        }

        table.dataTable {
            border-collapse: collapse !important;
            margin: 0 !important;
            width: 100% !important;
        }

        table.dataTable thead th {
            background: #f2f4f6 !important;
            color: #464553 !important;
            font-size: 11.5px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.08em !important;
            padding: 16px 20px !important;
            border-bottom: 1px solid rgba(200, 196, 213, 0.3) !important;
        }

        table.dataTable tbody td {
            padding: 16px 18px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid rgba(200, 196, 213, 0.15) !important;
            font-size: 13.5px !important;
            color: #191c1e !important;
            background-color: #ffffff;
        }

        /* Left status borders */
        table#Products tbody tr td:first-child {
            border-left: 4px solid transparent !important;
        }

        /* Soft Premium Pastel Backgrounds & Left Border Accents per Status */
        table#Products tbody tr td {
            background-color: #ffffff !important;
            transition: background-color 0.15s ease-in-out;
        }

        table#Products tbody tr td:first-child {
            border-left: 5px solid transparent !important;
        }

        /* Status: Pending (Soft Yellow/Gold) */
        table#Products tr.order-status-pending td { background-color: #fffdfa !important; }
        table#Products tr.order-status-pending td:first-child { border-left-color: #752c00 !important; }

        /* Status: Processing (Soft Indigo/Blue) */
        table#Products tr.order-status-processing td { background-color: #f7f7fe !important; }
        table#Products tr.order-status-processing td:first-child { border-left-color: #1f108e !important; }

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
        table#Products tr.order-status-cancelled td:first-child { border-left-color: #ba1a1a !important; }

        /* Status: Call Not Received (Soft Slate/Grey) */
        table#Products tr.order-status-phone_not_rcv td { background-color: #f9fafb !important; }
        table#Products tr.order-status-phone_not_rcv td:first-child { border-left-color: #464553 !important; }

        /* Status: Follow up (Soft Orange) */
        table#Products tr.order-status-follow_up td { background-color: #fff9f6 !important; }
        table#Products tr.order-status-follow_up td:first-child { border-left-color: #f97316 !important; }

        /* Status: Ready Delivery (Soft Teal) */
        table#Products tr.order-status-ready_for_delivery td { background-color: #f2fdfb !important; }
        table#Products tr.order-status-ready_for_delivery td:first-child { border-left-color: #14b8a6 !important; }

        /* Row Hover states */
        table.dataTable tbody tr:hover td {
            background-color: #f2f4f6 !important;
            cursor: pointer;
        }

        /* Order Status Badges - Rounded Pill Design */
        .order-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 12.5px;
            text-transform: capitalize;
            border: none;
        }

        .order-status-badge.order-status-pending { background: #ffdbcc; color: #511c00; }
        .order-status-badge.order-status-processing { background: #e1e0ff; color: #07006c; }
        .order-status-badge.order-status-delivered { background: #d1fae5; color: #065f46; }
        .order-status-badge.order-status-on_hold { background: #f3e8ff; color: #6b21a8; }
        .order-status-badge.order-status-shipped { background: #cffafe; color: #155e75; }
        .order-status-badge.order-status-cancelled { background: #ffdad6; color: #93000a; }
        .order-status-badge.order-status-phone_not_rcv { background: #e0e3e5; color: #191c1e; }
        .order-status-badge.order-status-follow_up { background: #ffedd5; color: #9a3412; }
        .order-status-badge.order-status-ready_for_delivery { background: #ccfbf1; color: #0f766e; }

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

        /* Fraud Check badge style - OrderFlow theme */
        .fraud-risk-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: capitalize;
            padding: 4px 10px;
            border-radius: 8px;
            margin-bottom: 4px;
            border: none !important;
        }

        .fraud-risk-badge.bg-success, .fraud-risk-badge.bg-info { background-color: #e1e0ff !important; color: #07006c !important; }
        .fraud-risk-badge.bg-warning { background-color: #ffdbcc !important; color: #7a3003 !important; }
        .fraud-risk-badge.bg-danger { background-color: #ffdad6 !important; color: #93000a !important; }
        .fraud-risk-badge.bg-secondary { background-color: #e0e3e5 !important; color: #464553 !important; }

        .fraud-success-rate {
            font-size: 11.5px;
            font-weight: 600;
            color: #191c1e;
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
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .custom-dropdown-toggle:hover,
        .custom-dropdown-toggle.active {
            background-color: #f1f5f9;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .custom-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 220px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 20px 35px -8px rgba(15, 23, 42, 0.2), 0 8px 16px -4px rgba(0, 0, 0, 0.08);
            z-index: 999999 !important;
            list-style: none;
            padding: 8px;
            margin: 6px 0 0 0;
            display: none;
            opacity: 0;
            transform: translateY(-6px);
            transition: opacity 0.2s cubic-bezier(0.4, 0, 0.2, 1), transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .table-responsive-wrapper {
            overflow-x: auto !important;
            position: relative;
        }

        table.dataTable tbody tr.dropdown-active-row {
            position: relative;
            z-index: 99999 !important;
        }
        
        .custom-dropdown-menu.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .custom-dropdown-menu.dropup {
            top: auto !important;
            bottom: 100% !important;
            margin-top: 0 !important;
            margin-bottom: 6px !important;
            transform: translateY(6px);
        }

        .custom-dropdown-menu.dropup.show {
            transform: translateY(0);
        }
        
        .custom-dropdown-header {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #94a3b8;
            padding: 6px 12px 4px 12px;
            margin: 0;
        }

        .custom-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 500;
            color: #334155;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.15s ease;
        }

        .custom-dropdown-item i {
            font-size: 14px;
            width: 18px;
            text-align: center;
            transition: transform 0.15s ease;
        }
        
        .custom-dropdown-item:hover {
            background-color: #f1f5f9;
            color: #0f172a;
            transform: translateX(2px);
            text-decoration: none;
        }

        .custom-dropdown-item:hover i {
            transform: scale(1.15);
        }

        /* Color accents per option */
        .custom-dropdown-item.print-receipt i { color: #0284c7; }
        .custom-dropdown-item.print-receipt:hover { background-color: #e0f2fe; color: #0369a1; }

        .custom-dropdown-item.print-invoice i { color: #16a34a; }
        .custom-dropdown-item.print-invoice:hover { background-color: #dcfce7; color: #15803d; }

        .custom-dropdown-item.print-slip i { color: #d97706; }
        .custom-dropdown-item.print-slip:hover { background-color: #fef3c7; color: #b45309; }

        .custom-dropdown-item.download-receipt i { color: #6366f1; }
        .custom-dropdown-item.download-receipt:hover { background-color: #e0e7ff; color: #4338ca; }

        .custom-dropdown-item.download-invoice i { color: #0d9488; }
        .custom-dropdown-item.download-invoice:hover { background-color: #ccfbf1; color: #0f766e; }

        .custom-dropdown-item.download-slip i { color: #9333ea; }
        .custom-dropdown-item.download-slip:hover { background-color: #f3e8ff; color: #6b21a8; }

        .custom-dropdown-divider {
            height: 1px;
            margin: 6px 0;
            overflow: hidden;
            background-color: #f1f5f9;
            border: 0;
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

        /* Status Grid Cards in Status Change Modal */
        .status-card-option {
            transition: all 0.2s ease-in-out;
            user-select: none;
        }

        .status-card-option:hover {
            border-color: #6366f1 !important;
            background-color: #f5f3ff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.12);
        }

        .status-card-option.active-status-card {
            border-color: #4f46e5 !important;
            background-color: #eef2ff !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25) !important;
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

        }

        /* === COMPACT PRODUCT COLUMN === */
        .prod-col {
            display: flex;
            flex-direction: column;
            gap: 3px;
            max-width: 190px;
            min-width: 150px;
        }
        .prod-price {
            font-size: 14px;
            font-weight: 700;
            color: #1f108e;
            letter-spacing: -0.01em;
            white-space: nowrap;
        }
        .prod-item {
            margin-top: 2px;
        }
        .prod-name {
            font-size: 12.5px;
            font-weight: 600;
            color: #191c1e;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 185px;
        }
        .prod-pills {
            display: flex;
            align-items: center;
            gap: 3px;
            flex-wrap: nowrap;
            margin-top: 2px;
            overflow: hidden;
        }
        .prod-pill {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 600;
            white-space: nowrap;
        }
        .prod-pill-var { background: #e1e0ff; color: #1f108e; }
        .prod-pill-qty { background: #f2f4f6; color: #464553; }
        .prod-badge-combo {
            display: inline-block;
            background: #1f108e;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 5px;
            border-radius: 4px;
            margin-left: 4px;
        }
        .prod-more {
            font-size: 10.5px;
            color: #9e9e9e;
            margin-top: 1px;
        }

        /* === TABLE HEADER & STICKY LAST (ACTION) COLUMN === */
        table#Products {
            border-collapse: separate !important;
            border-spacing: 0;
            width: 100% !important;
        }
        table#Products thead th {
            background-color: #f8fafc !important;
            color: #475569 !important;
            font-size: 11.5px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            padding: 12px 14px !important;
            border-bottom: 2px solid #e2e8f0 !important;
            vertical-align: middle !important;
            white-space: nowrap !important;
        }
        table#Products tbody td {
            vertical-align: middle !important;
            padding: 12px 14px !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        table#Products thead th:last-child,
        table#Products tbody td:last-child {
            position: sticky !important;
            right: 0 !important;
            z-index: 10 !important;
            box-shadow: -4px 0 10px -2px rgba(0,0,0,0.06);
        }
        table#Products thead th:last-child {
            background-color: #f8fafc !important;
        }
        table#Products tbody td:last-child {
            background-color: #ffffff !important;
        }
        table#Products tbody tr.order-status-pending td:last-child { background-color: #fffdfa !important; }
        table#Products tbody tr.order-status-processing td:last-child { background-color: #f7f7fe !important; }
        table#Products tbody tr.order-status-delivered td:last-child { background-color: #f5fdf9 !important; }
        table#Products tbody tr.order-status-on_hold td:last-child { background-color: #faf6fe !important; }
        table#Products tbody tr.order-status-shipped td:last-child { background-color: #f3fdfd !important; }
        table#Products tbody tr.order-status-cancelled td:last-child { background-color: #fff6f6 !important; }
        table#Products tbody tr.order-status-phone_not_rcv td:last-child { background-color: #f9fafb !important; }
        table#Products tbody tr.order-status-follow_up td:last-child { background-color: #fff9f6 !important; }
        table#Products tbody tr.order-status-ready_for_delivery td:last-child { background-color: #f2fdfb !important; }
        table#Products tbody tr:hover td:last-child { background-color: #f2f4f6 !important; }

        /* Note Modal */
        #noteModal .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        #noteModal textarea {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            padding: 12px;
            resize: vertical;
            transition: border-color 0.2s;
        }
        #noteModal textarea:focus {
            border-color: #1f108e;
            box-shadow: 0 0 0 3px rgba(31,16,142,0.1);
            outline: none;
        }
        #noteModal .btn-save-note {
            background: #1f108e;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 24px;
            font-weight: 600;
            font-size: 14px;
        }
        #noteModal .btn-save-note:hover {
            background: #160b70;
        }

        /* Premium Status Change Modal Styling */
        #statusChangeModal .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            font-family: 'Outfit', sans-serif;
        }
        
        #statusChangeModal .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 20px 24px;
            background-color: #fff;
        }

        #statusChangeModal .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }

        #statusChangeModal .btn-close {
            background-size: 10px;
            padding: 10px;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        #statusChangeModal .btn-close:hover {
            background-color: #f1f5f9;
        }

        #statusChangeModal .modal-body {
            padding: 24px;
            background-color: #fff;
        }

        #statusChangeModal .form-select {
            height: 46px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            transition: all 0.2s ease;
            background-color: #f8fafc;
        }

        #statusChangeModal .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background-color: #fff;
        }

        /* Purchase Event Card */
        #codPurchaseEventSection {
            margin-top: 20px;
            padding: 18px;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px;
            background-color: #f8fafc !important;
            transition: all 0.3s ease;
        }

        #codPurchaseEventSection h6 {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #purchaseEventDescription {
            font-size: 12.5px;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        #purchaseEventStatus {
            font-size: 12px;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: 100%;
        }

        #purchaseEventStatus .text-warning {
            color: #d97706 !important;
            background-color: #fffbeb;
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #fef3c7;
            width: 100%;
        }

        #purchaseEventStatus .text-success {
            color: #059669 !important;
            background-color: #ecfdf5;
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #d1fae5;
            width: 100%;
        }

        #purchaseEventStatus .text-danger {
            color: #dc2626 !important;
            background-color: #fef2f2;
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #fee2e2;
            width: 100%;
        }

        #firePurchaseEventBtn {
            width: 100%;
            height: 38px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
            border: none;
            background-color: #10b981;
            color: #fff;
        }

        #firePurchaseEventBtn:hover {
            background-color: #059669;
            transform: translateY(-1px);
        }

        #statusChangeModal .modal-footer {
            border-top: 1px solid #f1f5f9;
            padding: 16px 24px;
            background-color: #f8fafc;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        #statusChangeModal .btn-secondary {
            background-color: #94a3b8;
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            height: 38px;
            padding: 0 18px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        #statusChangeModal .btn-secondary:hover {
            background-color: #64748b;
        }

        #statusChangeModal .btn-primary {
            background-color: #2563eb;
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            height: 38px;
            padding: 0 18px;
            border-radius: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        #statusChangeModal .btn-primary:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
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
                            <a href="{{ route('admin.vendor-orders.index') }}" class="btn-soft btn-soft-success"><i class="fas fa-store"></i> Vendor Orders</a>
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
                        <th>Customer Profile</th>
                        <th>Product &amp; Logistics</th>
                        <th>Courier</th>
                        <th>Order Status</th>
                        <th>Fraud Analysis</th>
                        <th>Timeline</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- DataTables will populate this via AJAX server-side processing --}}
                </tbody>
            </table>
        </div>
    </div>

    <!-- Note Modal -->
    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 px-4 py-3" style="background: linear-gradient(135deg, #1f108e 0%, #3730a3 100%);">
                    <div class="d-flex align-items-center gap-2 text-white">
                        <i class="fas fa-sticky-note fs-5"></i>
                        <h5 class="modal-title fw-bold mb-0 text-white" id="noteModalLabel">Order Note</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <label class="form-label fw-semibold text-secondary mb-2" style="font-size: 13px;">Write a note for this order</label>
                    <textarea id="noteModalTextarea" class="form-control" rows="4" placeholder="Add your note here..."></textarea>
                    <div id="noteModalFeedback" class="mt-2" style="font-size: 12px;"></div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px; font-size: 14px;">Cancel</button>
                    <button type="button" class="btn btn-save-note px-4" id="saveNoteBtn">
                        <i class="fas fa-save me-1"></i> Save Note
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Change Modal -->

    <div class="modal fade" id="statusChangeModal" tabindex="-1" aria-labelledby="statusChangeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form id="statusChangeForm">
                <div class="modal-content border-0 shadow-lg overflow-hidden" style="border-radius: 16px;">
                    <!-- Modal Header -->
                    <div class="modal-header border-0 px-4 py-2.5 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary text-white rounded-3 p-1.5 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;">
                                <i class="fas fa-sliders-h fs-6"></i>
                            </div>
                            <div>
                                <h6 class="modal-title fw-bold mb-0 text-white" id="statusChangeModalLabel" style="font-size: 15px;">Update Order Status & Analytics</h6>
                                <p class="mb-0 text-white-50" style="font-size: 11px;">Select status and trigger sync events</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body p-3 bg-slate-50" style="background-color: #f8fafc;">
                        <input type="hidden" name="order_id" id="modalOrderId">
                        <input type="hidden" name="payment_method" id="modalPaymentMethod">
                        <input type="hidden" name="status" id="modalStatusSelect" value="pending">

                        <!-- Status Grid Selection Section -->
                        <div class="mb-2">
                            <label class="text-uppercase text-secondary fw-bold small mb-2 d-flex align-items-center gap-2" style="font-size: 10px; letter-spacing: 0.08em;">
                                <i class="fas fa-list-check text-primary"></i> Choose Fulfillment Status
                            </label>

                            <div class="row row-cols-lg-5 row-cols-md-3 row-cols-2 g-2" id="statusGridOptions">
                                <div class="col">
                                    <div class="status-card-option py-1.5 px-2 border rounded-3 bg-white text-center cursor-pointer h-100" data-status="pending">
                                        <div class="fs-6 mb-0.5">⏳</div>
                                        <div class="fw-bold text-dark" style="font-size: 11px;">Pending</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="status-card-option py-1.5 px-2 border rounded-3 bg-white text-center cursor-pointer h-100" data-status="phone_not_rcv">
                                        <div class="fs-6 mb-0.5">📞</div>
                                        <div class="fw-bold text-dark" style="font-size: 11px;">Call Not Rcv</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="status-card-option py-1.5 px-2 border rounded-3 bg-white text-center cursor-pointer h-100" data-status="follow_up">
                                        <div class="fs-6 mb-0.5">🔄</div>
                                        <div class="fw-bold text-dark" style="font-size: 11px;">Follow up</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="status-card-option py-1.5 px-2 border rounded-3 bg-white text-center cursor-pointer h-100" data-status="processing">
                                        <div class="fs-6 mb-0.5">⚙️</div>
                                        <div class="fw-bold text-dark" style="font-size: 11px;">Processing</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="status-card-option py-1.5 px-2 border rounded-3 bg-white text-center cursor-pointer h-100" data-status="ready_for_delivery">
                                        <div class="fs-6 mb-0.5">📦</div>
                                        <div class="fw-bold text-dark" style="font-size: 11px;">Ready Del</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="status-card-option py-1.5 px-2 border rounded-3 bg-white text-center cursor-pointer h-100" data-status="shipped">
                                        <div class="fs-6 mb-0.5">🚚</div>
                                        <div class="fw-bold text-dark" style="font-size: 11px;">Shipped</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="status-card-option py-1.5 px-2 border rounded-3 bg-white text-center cursor-pointer h-100" data-status="delivered">
                                        <div class="fs-6 mb-0.5">✅</div>
                                        <div class="fw-bold text-dark" style="font-size: 11px;">Delivered</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="status-card-option py-1.5 px-2 border rounded-3 bg-white text-center cursor-pointer h-100" data-status="on_hold">
                                        <div class="fs-6 mb-0.5">⏸️</div>
                                        <div class="fw-bold text-dark" style="font-size: 11px;">On Hold</div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="status-card-option py-1.5 px-2 border rounded-3 bg-white text-center cursor-pointer h-100" data-status="cancelled">
                                        <div class="fs-6 mb-0.5">❌</div>
                                        <div class="fw-bold text-dark" style="font-size: 11px;">Cancelled</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Event Section (COD & Offline Orders) -->
                        <div id="codPurchaseEventSection" class="p-2.5 border rounded-3 bg-white shadow-sm mt-2" style="display: none;">
                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                        <i class="fas fa-chart-line" style="font-size: 12px;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark" style="font-size: 13.5px;">Pixel & Analytics Conversion Sync</h6>
                                        <span class="text-muted" style="font-size: 10px;">Meta Pixel & Google Analytics Events</span>
                                    </div>
                                </div>
                                <span class="badge bg-info-subtle text-info border px-2 py-0.5" style="font-size: 9px;">Automated</span>
                            </div>
                            
                            <p class="text-muted small mb-2" id="purchaseEventDescription" style="font-size: 11.5px; line-height: 1.35;">
                                Fire server-side Purchase event conversions to validate marketing attribution and ROAS metrics.
                            </p>
                            
                            <div id="purchaseEventStatus" class="mb-2"></div>
                            
                            <button type="button" id="firePurchaseEventBtn" class="btn btn-emerald w-100 fw-bold py-1.5 d-flex align-items-center justify-content-center gap-2 shadow-sm" style="background-color: #10b981; color: #ffffff; border: none; border-radius: 8px; font-size: 12.5px;">
                                <i class="fas fa-paper-plane"></i> Sync Purchase Event Now
                            </button>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer bg-white border-top px-4 py-2.5 d-flex justify-content-between">
                        <button type="button" class="btn btn-light border px-3 fw-semibold py-1.5" data-bs-dismiss="modal" style="font-size: 13px; border-radius: 8px;">
                            <i class="fas fa-xmark me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm py-1.5" style="background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); border: none; border-radius: 8px; font-size: 13px;">
                            <i class="fas fa-check-double me-1"></i> Update Order Status
                        </button>
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

@push('scripts')
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

        function fetchStatusCounts() {
            let params = {};
            @if(isset($isAssignedOrdersPage) && $isAssignedOrdersPage)
                params.assigned_to_me = 1;
            @elseif(isset($isVendorOrdersPage) && $isVendorOrdersPage)
                params.vendor_orders = 1;
            @elseif(isset($isResellerOrdersPage) && $isResellerOrdersPage)
                params.reseller_orders = 1;
            @endif

            $.ajax({
                url: '{{ route("admin.orders.status-counts") }}',
                data: params,
                success: function(counts) {
                    $.each(counts, function(statusKey, countVal) {
                        const badge = $(`.status-filter-item[data-status="${statusKey === 'all' ? '' : statusKey}"] .status-pill-badge`);
                        if (badge.length) {
                            badge.text(countVal);
                        }
                    });
                }
            });
        }

        $(document).ready(function() {
            fetchStatusCounts();

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
                    url: '{{ isset($isVendorOrdersPage) && $isVendorOrdersPage ? route('admin.vendor-orders.data') : (isset($isResellerOrdersPage) && $isResellerOrdersPage ? route('admin.reseller-orders.data') : route('admin.orders.data')) }}',
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
                    { data: 'select', name: 'select', orderable: false, searchable: false, width: '45px' },
                    { 
                        data: 'customer_info', 
                        name: 'name', 
                        orderable: false, 
                        searchable: true, 
                        width: '240px',
                        render: function(data, type, row) {
                            const name = row.name || 'Customer';
                            const phone = row.phone || '';
                            const address = row.address || '';
                            const ipAddress = row.ip_address || (row.delivery_data && row.delivery_data.ip) || '';
                            const totalCount = row.total_orders_count || 1;
                            const isElite = totalCount > 1;
                            
                            let html = `<div class="d-flex flex-column gap-1">`;
                            html += `
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-dark" style="font-size: 14px;">${escapeHtml(name)}</span>
                                    ${isElite ? '<span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 2px 6px; border-radius: 20px;">Elite</span>' : ''}
                                </div>
                            `;
                            if (phone) {
                                html += `
                                    <div class="text-muted d-flex align-items-center gap-1" style="font-size: 12.5px;">
                                        <i class="fas fa-phone-alt text-secondary" style="font-size: 11px;"></i>
                                        <span>${escapeHtml(phone)}</span>
                                        <a href="tel:${phone}" class="text-success ms-1"><i class="fas fa-phone" style="font-size: 11px;"></i></a>
                                        <a href="https://wa.me/88${phone.replace(/\D/g, '')}" class="ms-1" target="_blank"><i class="fab fa-whatsapp" style="font-size: 13px; color: #25D366;"></i></a>
                                    </div>
                                `;
                            }
                            if (address) {
                                html += `
                                    <div class="text-muted d-flex align-items-start gap-1" style="font-size: 12px; line-height: 1.3; max-width: 210px;">
                                        <i class="fas fa-map-marker-alt text-danger" style="margin-top: 2px; font-size: 11px;"></i>
                                        <span class="text-truncate" title="${escapeHtml(address)}">${escapeHtml(address)}</span>
                                    </div>
                                `;
                            }
                            if (ipAddress) {
                                html += `<div class="mt-1"><span class="text-muted" style="font-size: 10.5px; font-family: monospace;">IP: ${escapeHtml(ipAddress)}</span></div>`;
                            }
                            if (row.order_source === 'Reseller POS') {
                                let resellerName = 'Reseller';
                                if (row.order_items && row.order_items[0] && row.order_items[0].others) {
                                    try {
                                        const othersObj = typeof row.order_items[0].others === 'string' ? JSON.parse(row.order_items[0].others) : row.order_items[0].others;
                                        if (othersObj && othersObj.reseller_name) {
                                            resellerName = othersObj.reseller_name;
                                        }
                                    } catch (e) {}
                                }
                                html += `<div class="mt-1"><span class="badge bg-purple text-white" style="font-size: 10px; background-color: #8b5cf6;"><i class="fas fa-user-tie me-1"></i>POS: ${escapeHtml(resellerName)}</span></div>`;
                                if (row.delivery_data && row.delivery_data.amount_paid > 0) {
                                    html += `<div class="mt-1"><span class="badge bg-success-subtle text-success border" style="font-size: 10.5px;"><i class="fas fa-check-circle me-1"></i>Paid: ৳${parseFloat(row.delivery_data.amount_paid).toFixed(2)}</span></div>`;
                                }
                            }

                            // Payment method and status display
                            const pm = row.payment_method ? row.payment_method.toUpperCase() : 'COD';
                            const ps = row.payment_status || 'pending';
                            let psBadgeClass = 'bg-warning-subtle text-warning border-warning-subtle';
                            if (ps === 'paid') {
                                psBadgeClass = 'bg-success-subtle text-success border-success-subtle';
                            } else if (ps === 'failed' || ps === 'refunded') {
                                psBadgeClass = 'bg-danger-subtle text-danger border-danger-subtle';
                            }
                            
                            html += `
                                <div class="mt-1.5 d-flex align-items-center gap-1 flex-wrap">
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 10px; font-weight: 600;">
                                        <i class="fas fa-credit-card me-1"></i>${pm}
                                    </span>
                                    <span class="badge ${psBadgeClass} border change-payment-status-btn" style="font-size: 10px; font-weight: 600; text-transform: uppercase; cursor: pointer;" data-order-id="${row.id}" data-current-payment-status="${ps}">
                                        ${ps}
                                    </span>
                                </div>
                            `;
                            
                            html += `</div>`;
                            return html;
                        }
                    },
                    // Product & Logistics — uses server-rendered HTML directly (compact with truncated names)
                    { data: 'product_price_and_name', name: 'total', orderable: false, searchable: true, width: '200px' },
                    // Courier — uses server-rendered HTML (empty string when no courier sent)
                    { data: 'courier_column', name: 'courier_column', orderable: false, searchable: false, width: '150px' },
                    { data: 'status_badge', name: 'status', orderable: false, searchable: false, width: '160px' },
                    { data: 'fraud_check', name: 'fraud_check', orderable: false, searchable: false, className: 'fraud-check-column', width: '150px' },
                    { data: 'order_at', name: 'created_at', width: '125px' },
                    { 
                        data: null, 
                        name: 'actions', 
                        orderable: false, 
                        searchable: false, 
                        width: '120px',
                        className: 'text-end',
                        render: function(data, type, row) {
                            const editUrl = `/admin/transactions/${row.id}/edit`;
                            const hasNote = row.admin_note && typeof row.admin_note === 'string' && row.admin_note.trim().length > 0;
                            const noteTitle = hasNote ? escapeHtml(row.admin_note) : 'Add Note';
                            const noteIconColor = hasNote ? '#4f46e5' : '#9e9e9e';
                            let html = `
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <button type="button" 
                                        class="action-icon-btn editable-note ${hasNote ? 'has-note' : ''}" 
                                        data-order-id="${row.id}" 
                                        title="${noteTitle}"
                                        style="color: ${noteIconColor};">
                                        <i class="fas ${hasNote ? 'fa-comment-alt' : 'fa-sticky-note'}"></i>
                                    </button>
                                    <a href="${editUrl}" title="Edit" class="action-icon-btn" style="color: #2e7d32;"><i class="fas fa-pencil-alt"></i></a>
                                    <div class="custom-dropdown">
                                        <button class="action-icon-btn custom-dropdown-toggle" type="button" title="More Options">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="custom-dropdown-menu">
                                            <li><h6 class="custom-dropdown-header"><i class="fas fa-print me-1"></i> Print Options</h6></li>
                                            <li><a class="custom-dropdown-item print-receipt" href="/order/${row.id}/print-receipt" target="_blank"><i class="fas fa-receipt"></i> Print Receipt</a></li>
                                            <li><a class="custom-dropdown-item print-invoice" href="/order/${row.id}/print-invoice" target="_blank"><i class="fas fa-file-invoice"></i> Print Invoice</a></li>
                                            <li><a class="custom-dropdown-item print-slip" href="/order/${row.id}/print-package-slip" target="_blank"><i class="fas fa-box"></i> Print Package Slip</a></li>
                                            <li><a class="custom-dropdown-item print-steadfast" href="/order/${row.id}/print-steadfast-invoice" target="_blank"><i class="fas fa-shipping-fast text-danger"></i> Print Steadfast Invoice</a></li>
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
                // Sort by "Order at / Timeline" column (index 6) using numeric data-order timestamp
                order: [[6, 'desc']]
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
                
                const toggle = $(this);
                const dropdown = toggle.closest('.custom-dropdown');
                const menu = dropdown.find('.custom-dropdown-menu');
                const isCurrentlyOpen = toggle.hasClass('active');
                
                // Close all open menus
                $('.custom-dropdown-menu').removeClass('show dropup').css({ position: '', top: '', left: '', right: '' });
                $('.custom-dropdown-toggle').removeClass('active');
                $('tr').removeClass('dropdown-active-row');
                
                if (!isCurrentlyOpen) {
                    toggle.addClass('active');
                    dropdown.closest('tr').addClass('dropdown-active-row');

                    // Measure menu dimensions
                    menu.css({ display: 'block', visibility: 'hidden', position: 'fixed' });
                    const menuWidth = menu.outerWidth() || 220;
                    const menuHeight = menu.outerHeight() || 200;
                    menu.css({ display: '', visibility: '', position: '' });

                    const toggleRect = toggle[0].getBoundingClientRect();
                    const windowHeight = $(window).height();
                    const windowWidth = $(window).width();

                    let top = toggleRect.bottom + 4;
                    let left = toggleRect.right - menuWidth;

                    // If space below is limited, open dropup above toggle button
                    if (windowHeight - toggleRect.bottom < menuHeight + 15 && toggleRect.top > menuHeight + 15) {
                        top = toggleRect.top - menuHeight - 4;
                        menu.addClass('dropup');
                    } else {
                        menu.removeClass('dropup');
                    }

                    // Keep inside left window boundary
                    if (left < 10) left = 10;

                    menu.css({
                        position: 'fixed',
                        top: top + 'px',
                        left: left + 'px',
                        right: 'auto',
                        zIndex: 999999
                    }).addClass('show');
                }
            });
            
            // Close dropdown when scrolling or clicking outside
            $(window).on('scroll resize', function() {
                $('.custom-dropdown-menu').removeClass('show dropup');
                $('.custom-dropdown-toggle').removeClass('active');
                $('tr').removeClass('dropdown-active-row');
            });
            
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.custom-dropdown').length) {
                    $('.custom-dropdown-menu').removeClass('show dropup');
                    $('.custom-dropdown-toggle').removeClass('active');
                    $('tr').removeClass('dropdown-active-row');
                }
            });
            
            // Close dropdown when clicking on menu items
            $(document).on('click', '.custom-dropdown-item', function() {
                $('.custom-dropdown-menu').removeClass('show dropup');
                $('.custom-dropdown-toggle').removeClass('active');
                $('tr').removeClass('dropdown-active-row');
            });

            // Note icon click → open modal
            $(document).on('click', '.editable-note', function(event) {
                event.stopPropagation();
                const orderId = $(this).data('order-id');
                const currentNote = $(this).attr('title') || '';
                const displayNote = (currentNote === 'Add Note') ? '' : currentNote;
                $('#noteModal').data('order-id', orderId);
                $('#noteModalTextarea').val(displayNote);
                $('#noteModalFeedback').html('');
                $('#noteModal').modal('show');
                setTimeout(function() { $('#noteModalTextarea').focus(); }, 300);
            });

            // Save note button
            $('#saveNoteBtn').on('click', function() {
                const orderId = $('#noteModal').data('order-id');
                const newNote = $('#noteModalTextarea').val().trim();
                const btn = $(this);
                btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...').prop('disabled', true);

                $.ajax({
                    url: '{{ route('admin.orders.updateNote') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        order_id: orderId,
                        note: newNote
                    },
                    success: function(response) {
                        btn.html('<i class="fas fa-save me-1"></i> Save Note').prop('disabled', false);
                        if (response.success) {
                            $('#noteModal').modal('hide');
                            // Update the note icon tooltip and color
                            const noteBtn = $(`.editable-note[data-order-id="${orderId}"]`);
                            const displayTitle = newNote || 'Add Note';
                            const hasNote = newNote.length > 0;
                            noteBtn.attr('title', displayTitle);
                            noteBtn.css('color', hasNote ? '#4f46e5' : '#9e9e9e');
                            noteBtn.find('i').attr('class', hasNote ? 'fas fa-comment-alt' : 'fas fa-sticky-note');
                            $('#noteModalFeedback').html('');
                        } else {
                            $('#noteModalFeedback').html('<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>' + (response.message || 'Error saving note') + '</span>');
                        }
                    },
                    error: function() {
                        btn.html('<i class="fas fa-save me-1"></i> Save Note').prop('disabled', false);
                        $('#noteModalFeedback').html('<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> An error occurred. Please try again.</span>');
                    }
                });
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

            // Note icon click → open modal
            $(document).on('click', '.editable-note', function(event) {
                event.stopPropagation();
                const orderId = $(this).data('order-id');
                const currentNote = $(this).attr('title') || '';
                const displayNote = (currentNote === 'Add Note') ? '' : currentNote;
                $('#noteModal').data('order-id', orderId);
                $('#noteModalTextarea').val(displayNote);
                $('#noteModal').modal('show');
            });

            // Offline order sources that can have delayed purchase events
            var offlineOrderSources = ['Physical Store', 'Phone Call', 'WhatsApp', 'Messenger', 'Facebook', 'Instagram'];

            // Status card option click selection handler
            $(document).on('click', '.status-card-option', function() {
                const targetStatus = $(this).data('status');
                $('.status-card-option').removeClass('active-status-card');
                $(this).addClass('active-status-card');
                $('#modalStatusSelect').val(targetStatus);
            });

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

                // Highlight corresponding card in status grid
                $('.status-card-option').removeClass('active-status-card');
                $(`.status-card-option[data-status="${currentStatus}"]`).addClass('active-status-card');

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

            // Quick update payment status (delegated)
            $(document).on('click', '.change-payment-status-btn', function(event) {
                event.stopPropagation();
                var orderId = $(this).data('order-id');
                var currentPaymentStatus = $(this).data('current-payment-status') || 'pending';

                Swal.fire({
                    title: '<span style="font-weight: 700; color: #1e293b;">Update Payment Status</span>',
                    html: '<div style="font-size: 14px; color: #64748b; margin-bottom: 10px;">Choose the payment status for this order:</div>',
                    icon: 'question',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Full Paid',
                    denyButtonText: '<i class="fas fa-clock me-1"></i> Pending',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#10b981', // Emerald green
                    denyButtonColor: '#f59e0b', // Amber/Orange
                    cancelButtonColor: '#94a3b8', // Slate grey
                    customClass: {
                        popup: 'premium-swal-popup border-radius-12',
                        confirmButton: 'btn fw-bold px-4 py-2 border-0 shadow-sm',
                        denyButton: 'btn fw-bold px-4 py-2 border-0 shadow-sm',
                        cancelButton: 'btn fw-semibold px-4 py-2 border-0 shadow-sm'
                    }
                }).then((result) => {
                    let newPaymentStatus = null;
                    if (result.isConfirmed) {
                        newPaymentStatus = 'paid';
                    } else if (result.isDenied) {
                        newPaymentStatus = 'pending';
                    }

                    if (newPaymentStatus) {
                        $.ajax({
                            url: '{{ route('admin.orders.updatePaymentStatus') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                order_id: orderId,
                                payment_status: newPaymentStatus
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Updated!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false,
                                        customClass: {
                                            popup: 'premium-swal-popup'
                                        }
                                    });
                                    if (typeof table !== 'undefined') {
                                        table.ajax.reload(null, false);
                                    } else {
                                        location.reload();
                                    }
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: response.message || 'Something went wrong',
                                        icon: 'error',
                                        customClass: {
                                            popup: 'premium-swal-popup'
                                        }
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'Failed to update payment status',
                                    icon: 'error',
                                    customClass: {
                                        popup: 'premium-swal-popup'
                                    }
                                });
                            }
                        });
                    }
                });
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
                            $('#purchaseEventStatus').html('<span class="text-success"><i class="fas fa-check-circle"></i> Synced successfully at ' + new Date(response.fired_at).toLocaleString() + '</span>');
                            $('#firePurchaseEventBtn').hide();
                        } else if (response.fire_failed) {
                            $('#purchaseEventStatus').html('<span class="text-danger"><i class="fas fa-exclamation-circle"></i> Sync failed: ' + (response.fire_error || 'Unknown error') + '</span>');
                            $('#firePurchaseEventBtn').show().prop('disabled', false).text('Retry Sync');
                        } else if (response.has_pending_event) {
                            $('#purchaseEventStatus').html('<span class="text-warning"><i class="fas fa-clock"></i> Event pending - ready to sync</span>');
                            $('#firePurchaseEventBtn').show().prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Sync Purchase Event Now');
                        } else {
                            $('#purchaseEventStatus').html('<span class="text-info"><i class="fas fa-info-circle"></i> No record - will generate & sync</span>');
                            $('#firePurchaseEventBtn').show().prop('disabled', false).html('<i class="fas fa-paper-plane me-1"></i> Generate & Sync Event');
                        }
                    },
                    error: function() {
                        $('#purchaseEventStatus').html('<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Sync status check failed</span>');
                        $('#firePurchaseEventBtn').show().prop('disabled', false);
                    }
                });
            }

            // Fire purchase event button handler
            $('#firePurchaseEventBtn').on('click', function() {
                var orderId = $('#modalOrderId').val();
                var btn = $(this);

                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Syncing...');

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
                            console.log('=== Steadfast Courier Send Response ===');
                            console.dir(data);
                            if (data.success) {
                                alert(data.message || 'Order sent to Steadfast successfully!');
                                sendBtn.disabled = true;
                                sendBtn.classList.add('sent-sf');
                                sendBtn.innerHTML = '<i class="fas fa-check"></i> Steadfast';
                                if (window.ordersDataTable) {
                                    window.ordersDataTable.ajax.reload(null, false);
                                }
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
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                order_ids: selected
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            console.log('=== Steadfast Bulk Courier Send Response ===');
                            console.dir(data);
                            if (data.success) {
                                alert(data.message || 'Bulk orders sent to Steadfast successfully!');
                                if (window.ordersDataTable) {
                                    window.ordersDataTable.ajax.reload(null, false);
                                } else {
                                    location.reload();
                                }
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
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                order_ids: selected
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            console.log('=== Pathao Bulk Courier Send Response ===');
                            console.dir(data);
                            if (data.success) {
                                alert(data.message || 'Bulk orders sent to Pathao successfully!');
                                if (window.ordersDataTable) {
                                    window.ordersDataTable.ajax.reload(null, false);
                                } else {
                                    location.reload();
                                }
                            } else {
                                alert(data.message || 'Failed to send bulk orders to Pathao.');
                            }
                        })
                        .catch((err) => {
                            console.error('Pathao Bulk Send JS Error:', err);
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
                        const url = `/order/${orderId}/print-invoice`;
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
                        const url = `/order/${orderId}/print-package-slip`;
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
@endpush
