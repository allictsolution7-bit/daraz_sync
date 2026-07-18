@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 Custom Styles */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.5rem !important;
            padding: 4px 6px !important;
            min-height: 42px !important;
            transition: all 0.2s ease;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #eff6ff !important;
            border: 1px solid #bfdbfe !important;
            color: #2563eb !important;
            border-radius: 0.375rem !important;
            padding: 2px 8px !important;
            font-size: 0.8rem !important;
            font-weight: 500 !important;
            margin-top: 2px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #3b82f6 !important;
            margin-right: 5px !important;
            border: none !important;
            background: transparent !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #1d4ed8 !important;
        }
        .select2-dropdown {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
        
        .sales-report {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            border-radius: 18px;
            padding: 18px;
        }

        .report-hero {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border-radius: 16px;
            padding: 24px;
            color: #f8fafc;
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.2);
            position: relative;
            overflow: hidden;
        }
        .report-hero::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .report-hero .eyebrow {
            text-transform: uppercase;
            letter-spacing: 0.14em;
            font-size: 11px;
            color: #dcfce7;
            font-weight: 600;
        }

        .report-hero h4 {
            color: #fff;
            margin: 4px 0 6px;
            font-weight: 800;
        }

        .hero-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            color: #f8fafc;
            border: 1px solid rgba(255, 255, 255, 0.22);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
            font-size: 0.85rem;
        }

        .hero-actions .btn {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .hero-actions .btn:hover {
            transform: translateY(-1px);
        }

        .filter-card {
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            background: #fff;
        }

        .sales-report .filter-chip {
            border-radius: 30px;
            padding: 6px 14px;
            font-weight: 500;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }

        .stat-tile {
            position: relative;
            border-radius: 1rem;
            padding: 1.25rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 120px;
        }
        .stat-tile:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08);
        }

        /* Accents */
        .tile-revenue { border-left: 4px solid #10b981; }
        .tile-revenue .stat-accent { background: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; }
        
        .tile-orders { border-left: 4px solid #3b82f6; }
        .tile-orders .stat-accent { background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe; }
        
        .tile-costs { border-left: 4px solid #f59e0b; }
        .tile-costs .stat-accent { background: #fffbeb; color: #f59e0b; border: 1px solid #fde68a; }
        
        .tile-returns { border-left: 4px solid #ef4444; }
        .tile-returns .stat-accent { background: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; }
        
        .tile-customers { border-left: 4px solid #8b5cf6; }
        .tile-customers .stat-accent { background: #f5f3ff; color: #8b5cf6; border: 1px solid #ddd6fe; }

        .stat-tile .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .stat-tile .stat-label {
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-tile .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .stat-tile .stat-sub {
            color: #64748b;
            font-size: 12px;
        }

        .stat-accent {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .report-loader {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 1050;
        }

        .report-card {
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .sparkline-card {
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
            height: 100%;
            overflow: hidden;
        }

        .card-header-premium {
            background-color: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
            padding: 0.85rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-premium h6 {
            font-size: 0.88rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0;
        }

        .sparkline-body {
            padding: 12px;
        }

        .table-premium thead th {
            background-color: #1e293b !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 0.72rem !important;
            letter-spacing: 0.05em !important;
            padding: 0.85rem 0.75rem !important;
            border: none !important;
        }
        .table-premium thead th:first-child {
            border-top-left-radius: 0.5rem !important;
        }
        .table-premium thead th:last-child {
            border-top-right-radius: 0.5rem !important;
        }

        .table-premium td {
            vertical-align: middle !important;
            border-top: 1px solid #f1f5f9 !important;
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 0.85rem 0.75rem !important;
            font-size: 0.85rem !important;
        }

        .table-premium tbody tr:hover td {
            background: #f8fafc !important;
        }

        .range-btn.active {
            color: #fff !important;
            background: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }

        .text-soft {
            color: #64748b;
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 20px 0 12px;
            font-weight: 700;
            color: #0f172a;
            font-size: 1.1rem;
        }

        .section-heading .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #10b981);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        }
        .form-control, .form-select {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid sales-report">
        <div class="report-hero mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="eyebrow">Sales intelligence</div>
                    <h4 class="mb-1">Advanced Sales Report</h4>
                    <div class="text-white">Live pulse of revenue, payments, channels, and products.</div>
                    <div class="hero-chips">
                        <span class="hero-chip">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            <span id="range-label">Loading...</span>
                        </span>
                        <span class="hero-chip">
                            <i class="fas fa-filter text-warning"></i> Dynamic filters
                        </span>
                        <span class="hero-chip">
                            <i class="fas fa-chart-line text-success"></i> Compact sparklines
                        </span>
                    </div>
                </div>
                <div class="hero-actions d-flex flex-wrap align-items-center gap-2">
                    <button class="btn btn-light btn-sm" id="refresh-report">
                        <i class="fas fa-rotate"></i> Refresh data
                    </button>
                </div>
            </div>
        </div>

        <div class="filter-card mb-3 p-3">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @php
                        $presets = [
                            'today' => 'Today',
                            'yesterday' => 'Yesterday',
                            'last_7_days' => 'Last 7 days',
                            'last_30_days' => 'Last 30 days',
                            'this_month' => 'This month',
                            'last_month' => 'Last month',
                            'custom' => 'Custom',
                        ];
                    @endphp
                    @foreach ($presets as $key => $label)
                        <button class="btn btn-sm btn-outline-primary range-btn filter-chip"
                            data-range="{{ $key }}">{{ $label }}</button>
                    @endforeach
                </div>

                <div class="row g-2 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label mb-1">Start date</label>
                        <input type="date" class="form-control" id="start_date">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label mb-1">End date</label>
                        <input type="date" class="form-control" id="end_date">
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label mb-1">Order status</label>
                        <select class="form-control" id="status_filter" name="statuses[]" multiple>
                            @foreach ($statusOptions as $status)
                                <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label mb-1">Payment method</label>
                        <select class="form-control" id="payment_filter" name="payment_methods[]" multiple>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method }}">{{ strtoupper($method) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label mb-1">Order source</label>
                        <select class="form-control" id="source_filter" name="order_sources[]" multiple>
                            @foreach ($orderSources as $source)
                                <option value="{{ $source }}">{{ $source }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 d-flex align-items-end">
                        <button class="btn btn-primary w-100" id="apply-filters">
                            <i class="fas fa-filter"></i> Apply filters
                        </button>
                    </div>
                    <div class="col-12">
                        <div class="text-soft small mt-1">
                            Tip: hold Ctrl (or Cmd) to pick multiple values.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-heading"><span class="dot"></span> Snapshot</div>

        <div class="stat-grid mb-3">
            <!-- Revenue Category -->
            <div class="stat-tile tile-revenue">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Total revenue</div>
                        <div class="stat-value text-success" id="metric-gross-sales">--</div>
                        <div class="stat-sub">Gross sales</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-sack-dollar"></i></div>
                </div>
            </div>
            
            <div class="stat-tile tile-revenue">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Net revenue</div>
                        <div class="stat-value text-success" id="metric-net">--</div>
                        <div class="stat-sub">Revenue - discount + shipping</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-wallet"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-revenue">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Profit</div>
                        <div class="stat-value text-success" id="metric-profit">--</div>
                        <div class="stat-sub" id="metric-profit-margin">Margin %</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-chart-pie"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-revenue">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Delivered revenue</div>
                        <div class="stat-value text-success" id="metric-delivered">--</div>
                        <div class="stat-sub">Marked delivered</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-truck"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-revenue">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Prepaid revenue</div>
                        <div class="stat-value text-success" id="metric-prepaid-rev">--</div>
                        <div class="stat-sub" id="metric-prepaid-count">Prepaid orders</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-credit-card"></i></div>
                </div>
            </div>

            <!-- Orders/Ops Category -->
            <div class="stat-tile tile-orders">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Orders</div>
                        <div class="stat-value text-primary" id="metric-orders">--</div>
                        <div class="stat-sub" id="metric-paid">Paid vs unpaid</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-shopping-basket"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-orders">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Average order value</div>
                        <div class="stat-value text-primary" id="metric-aov">--</div>
                        <div class="stat-sub">Per completed order</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-gauge-high"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-orders">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Units sold</div>
                        <div class="stat-value text-primary" id="metric-units">--</div>
                        <div class="stat-sub">Items across orders</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-cubes"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-orders">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Delivered orders</div>
                        <div class="stat-value text-primary" id="metric-ok-orders">--</div>
                        <div class="stat-sub">Marked delivered</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-orders">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">COD revenue</div>
                        <div class="stat-value text-primary" id="metric-cod-rev">--</div>
                        <div class="stat-sub" id="metric-cod-count">COD orders</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-money-bill-wave"></i></div>
                </div>
            </div>

            <!-- Costs/Shipping Category -->
            <div class="stat-tile tile-costs">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">COGS</div>
                        <div class="stat-value text-warning" id="metric-cogs">--</div>
                        <div class="stat-sub">Cost of goods sold</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-box"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-costs">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Discount given</div>
                        <div class="stat-value text-warning" id="metric-discount">--</div>
                        <div class="stat-sub">Total discounts</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-percent"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-costs">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Shipping collected</div>
                        <div class="stat-value text-warning" id="metric-shipping">--</div>
                        <div class="stat-sub">Shipping charges</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-box-open"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-costs">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Avg shipping</div>
                        <div class="stat-value text-warning" id="metric-avg-shipping">--</div>
                        <div class="stat-sub">Per order</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-truck-fast"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-costs">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Combo orders</div>
                        <div class="stat-value text-warning" id="metric-combo">--</div>
                        <div class="stat-sub">Marked as combo</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-gift"></i></div>
                </div>
            </div>

            <!-- Returns/Exceptions Category -->
            <div class="stat-tile tile-returns">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Open revenue</div>
                        <div class="stat-value text-danger" id="metric-open">--</div>
                        <div class="stat-sub">Not delivered yet</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-hourglass-half"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-returns">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Cancelled</div>
                        <div class="stat-value text-danger" id="metric-cancelled">--</div>
                        <div class="stat-sub">Status: cancelled</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-ban"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-returns">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Refunded</div>
                        <div class="stat-value text-danger" id="metric-refunded">--</div>
                        <div class="stat-sub" id="metric-refund-amount">Refund total</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-rotate-left"></i></div>
                </div>
            </div>

            <div class="stat-tile tile-returns">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Returned</div>
                        <div class="stat-value text-danger" id="metric-returned">--</div>
                        <div class="stat-sub">Status: returned</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-undo-alt"></i></div>
                </div>
            </div>

            <!-- Customer Category -->
            <div class="stat-tile tile-customers">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Repeat customers</div>
                        <div class="stat-value" style="color: #8b5cf6;" id="metric-repeat-rate">--</div>
                        <div class="stat-sub" id="metric-repeat-count">Returning vs unique</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-user-check"></i></div>
                </div>
            </div>
        </div>

        {{-- === TRENDS SECTION === --}}
        <div class="d-flex align-items-center gap-2 mb-2 mt-4">
            <div style="width:3px;height:18px;background:linear-gradient(to bottom,#3b82f6,#1d4ed8);border-radius:2px;"></div>
            <span class="fw-bold text-dark" style="font-size:0.95rem;letter-spacing:0.01em;">Trends</span>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-lg-6">
                <div class="sparkline-card">
                    <div class="card-header-premium">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#eff6ff;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-chart-line text-primary" style="font-size:0.85rem;"></i>
                            </div>
                            <div>
                                <h6>Revenue Trend</h6>
                                <div class="text-muted" style="font-size:0.75rem;">Daily revenue over period</div>
                            </div>
                        </div>
                        <span class="badge" id="trend-revenue-last" style="background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;font-size:0.78rem;font-weight:600;padding:5px 10px;border-radius:8px;">--</span>
                    </div>
                    <div class="sparkline-body" style="height: 200px;">
                        <canvas id="revenueTrendChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="sparkline-card">
                    <div class="card-header-premium">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:#f0fdf4;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-shopping-cart text-success" style="font-size:0.85rem;"></i>
                            </div>
                            <div>
                                <h6>Orders Trend</h6>
                                <div class="text-muted" style="font-size:0.75rem;">Daily order count over period</div>
                            </div>
                        </div>
                        <span class="badge" id="trend-orders-last" style="background:#f0fdf4;color:#16a34a;border:1px solid #a7f3d0;font-size:0.78rem;font-weight:600;padding:5px 10px;border-radius:8px;">--</span>
                    </div>
                    <div class="sparkline-body" style="height: 200px;">
                        <canvas id="ordersTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- === ORDER TIMING === --}}
        <div class="d-flex align-items-center gap-2 mb-2 mt-3">
            <div style="width:3px;height:18px;background:linear-gradient(to bottom,#f59e0b,#d97706);border-radius:2px;"></div>
            <span class="fw-bold text-dark" style="font-size:0.95rem;letter-spacing:0.01em;">Order Timing</span>
        </div>
        <div class="card report-card mb-3">
            <div class="card-header-premium">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;background:#fffbeb;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-clock text-warning" style="font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <h6>Orders by Hour of Day</h6>
                        <div class="text-muted" style="font-size:0.75rem;">Spot peaks across 24h — orders & revenue</div>
                    </div>
                </div>
                <span class="badge" style="background:#fffbeb;color:#d97706;border:1px solid #fde68a;font-size:0.75rem;font-weight:600;padding:5px 10px;border-radius:8px;">
                    <i class="fas fa-info-circle me-1"></i> 24h view
                </span>
            </div>
            <div class="p-3" style="height: 380px;">
                <canvas id="orderTimeChart"></canvas>
            </div>
        </div>

        {{-- === COMBINED METRICS === --}}
        <div class="d-flex align-items-center gap-2 mb-2 mt-3">
            <div style="width:3px;height:18px;background:linear-gradient(to bottom,#8b5cf6,#6d28d9);border-radius:2px;"></div>
            <span class="fw-bold text-dark" style="font-size:0.95rem;letter-spacing:0.01em;">Combined Metrics</span>
        </div>
        <div class="card report-card mb-4">
            <div class="card-header-premium">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;background:#f5f3ff;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-layer-group" style="color:#8b5cf6;font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <h6>Orders, Revenue & Refunds</h6>
                        <div class="text-muted" style="font-size:0.75rem;">Multi-signal timeline view</div>
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <span class="d-flex align-items-center gap-1" style="font-size:0.75rem;color:#16a34a;"><span style="width:10px;height:3px;background:#16a34a;display:inline-block;border-radius:2px;"></span> Revenue</span>
                    <span class="d-flex align-items-center gap-1" style="font-size:0.75rem;color:#2563eb;"><span style="width:10px;height:10px;background:rgba(37,99,235,0.35);display:inline-block;border-radius:2px;"></span> Orders</span>
                    <span class="d-flex align-items-center gap-1" style="font-size:0.75rem;color:#e11d48;"><span style="width:10px;height:3px;background:#e11d48;display:inline-block;border-radius:2px;"></span> Refunds</span>
                </div>
            </div>
            <div class="p-3" style="height: 260px;">
                <canvas id="keyMetricsChart"></canvas>
            </div>
        </div>

        {{-- === BREAKDOWNS === --}}
        <div class="d-flex align-items-center gap-2 mb-2 mt-3">
            <div style="width:3px;height:18px;background:linear-gradient(to bottom,#10b981,#059669);border-radius:2px;"></div>
            <span class="fw-bold text-dark" style="font-size:0.95rem;letter-spacing:0.01em;">Breakdowns</span>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-header-premium">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:28px;height:28px;background:#eff6ff;border-radius:7px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-wallet text-primary" style="font-size:0.78rem;"></i>
                            </div>
                            <h6>Payment Breakdown</h6>
                        </div>
                    </div>
                    <div class="p-0">
                        <div class="table-responsive">
                            <table class="table table-premium table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Revenue</th>
                                        <th class="text-end">Avg order</th>
                                    </tr>
                                </thead>
                                <tbody id="payment-rows">
                                    <tr><td colspan="4" class="text-center text-muted py-4" style="font-size:0.82rem;"><i class="fas fa-circle-notch fa-spin me-1 text-secondary"></i> Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-header-premium">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:28px;height:28px;background:#f0fdf4;border-radius:7px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-flag text-success" style="font-size:0.78rem;"></i>
                            </div>
                            <h6>Status Mix</h6>
                        </div>
                    </div>
                    <div class="p-0">
                        <div class="table-responsive">
                            <table class="table table-premium table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody id="status-rows">
                                    <tr><td colspan="3" class="text-center text-muted py-4" style="font-size:0.82rem;"><i class="fas fa-circle-notch fa-spin me-1 text-secondary"></i> Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-header-premium">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:28px;height:28px;background:#fffbeb;border-radius:7px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-share-nodes text-warning" style="font-size:0.78rem;"></i>
                            </div>
                            <h6>Source Performance</h6>
                        </div>
                    </div>
                    <div class="p-0">
                        <div class="table-responsive">
                            <table class="table table-premium table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody id="source-rows">
                                    <tr><td colspan="3" class="text-center text-muted py-4" style="font-size:0.82rem;"><i class="fas fa-circle-notch fa-spin me-1 text-secondary"></i> Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-header-premium">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:28px;height:28px;background:#fee2e2;border-radius:7px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-shield-halved text-danger" style="font-size:0.78rem;"></i>
                            </div>
                            <h6>Payment Reliability</h6>
                        </div>
                    </div>
                    <div class="p-0">
                        <div class="table-responsive">
                            <table class="table table-premium table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Failed</th>
                                        <th class="text-end">Failed %</th>
                                    </tr>
                                </thead>
                                <tbody id="payment-reliability-rows">
                                    <tr><td colspan="4" class="text-center text-muted py-4" style="font-size:0.82rem;"><i class="fas fa-circle-notch fa-spin me-1 text-secondary"></i> Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-header-premium">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:28px;height:28px;background:#fffbeb;border-radius:7px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-triangle-exclamation text-warning" style="font-size:0.78rem;"></i>
                            </div>
                            <h6>Source Risk</h6>
                        </div>
                    </div>
                    <div class="p-0">
                        <div class="table-responsive">
                            <table class="table table-premium table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th class="text-end">Cancel %</th>
                                        <th class="text-end">Return %</th>
                                    </tr>
                                </thead>
                                <tbody id="source-risk-rows">
                                    <tr><td colspan="3" class="text-center text-muted py-4" style="font-size:0.82rem;"><i class="fas fa-circle-notch fa-spin me-1 text-secondary"></i> Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-header-premium">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:28px;height:28px;background:#eff6ff;border-radius:7px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-truck text-primary" style="font-size:0.78rem;"></i>
                            </div>
                            <h6>Courier Performance</h6>
                        </div>
                    </div>
                    <div class="p-0">
                        <div class="table-responsive">
                            <table class="table table-premium table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Courier</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Delivered</th>
                                        <th class="text-end">Exceptions</th>
                                    </tr>
                                </thead>
                                <tbody id="courier-rows">
                                    <tr><td colspan="4" class="text-center text-muted py-4" style="font-size:0.82rem;"><i class="fas fa-circle-notch fa-spin me-1 text-secondary"></i> Loading...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- === TOP PRODUCTS === --}}
        <div class="d-flex align-items-center gap-2 mb-2">
            <div style="width:3px;height:18px;background:linear-gradient(to bottom,#f59e0b,#d97706);border-radius:2px;"></div>
            <span class="fw-bold text-dark" style="font-size:0.95rem;letter-spacing:0.01em;">Top Products</span>
        </div>
        <div class="card report-card mb-4">
            <div class="card-header-premium">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;background:#fffbeb;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-star text-warning" style="font-size:0.85rem;"></i>
                    </div>
                    <div>
                        <h6>Top Products by Revenue</h6>
                        <div class="text-muted" style="font-size:0.75rem;">Sorted by revenue — top 8</div>
                    </div>
                </div>
                <span class="badge" style="background:#fffbeb;color:#d97706;border:1px solid #fde68a;font-size:0.75rem;font-weight:600;padding:5px 10px;border-radius:8px;">
                    Top 8
                </span>
            </div>
            <div class="p-0">
                <div class="table-responsive">
                    <table class="table table-premium mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th class="text-end">Units</th>
                                <th class="text-end">Revenue</th>
                                <th class="text-end">Avg price</th>
                            </tr>
                        </thead>
                        <tbody id="product-rows">
                            <tr><td colspan="5" class="text-center text-muted py-4" style="font-size:0.82rem;"><i class="fas fa-circle-notch fa-spin me-1 text-secondary"></i> Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="report-loader d-none" id="report-loader">
        <div class="spinner-border text-primary mb-2" role="status"></div>
        <div class="text-soft">Crunching numbers...</div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        (function() {
            let activeRange = '{{ $defaultRange }}';
            let revenueTrendChart = null;
            let ordersTrendChart = null;
            let orderTimeChart = null;
            let keyMetricsChart = null;
            const currency = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'BDT',
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });

            // Initialize Select2 dropdowns
            $('#status_filter, #payment_filter, #source_filter').select2({
                placeholder: "Select options",
                width: '100%'
            });

            $('.range-btn').each(function() {
                if ($(this).data('range') === activeRange) {
                    $(this).addClass('active');
                }
            });

            $('#refresh-report').on('click', fetchReport);

            $('.range-btn').on('click', function(e) {
                e.preventDefault();
                activeRange = $(this).data('range');
                $('.range-btn').removeClass('active');
                $(this).addClass('active');
                if (activeRange !== 'custom') {
                    fetchReport();
                }
            });

            $('#apply-filters').on('click', function() {
                if (activeRange === 'custom') {
                    const start = $('#start_date').val();
                    const end = $('#end_date').val();
                    if (!start || !end) {
                        alert('Please select both start and end dates for a custom range.');
                        return;
                    }
                    if (new Date(start) > new Date(end)) {
                        alert('Start date cannot be later than end date.');
                        return;
                    }
                }
                fetchReport();
            });

            fetchReport();

            function fetchReport() {
                toggleLoader(true);
                $.ajax({
                    url: '{{ route('admin.orders.reports.data') }}',
                    method: 'GET',
                    data: buildFilters(),
                    success: function(response) {
                        updateRange(response.range);
                        updateSummary(response.summary);
                        updateTrendCharts(response.trend);
                        renderPaymentBreakdown(response.payment_breakdown);
                        renderStatusBreakdown(response.status_breakdown);
                        renderSourceBreakdown(response.source_breakdown);
                        renderTopProducts(response.top_products);
                        renderPaymentReliability(response.payment_reliability);
                        renderSourceRisk(response.source_risk);
                        renderCourierPerformance(response.courier_performance);
                        renderOrderTiming(response.order_timing);
                        renderKeyMetrics(response.trend);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Failed to load report data. Please try again.');
                    },
                    complete: function() {
                        toggleLoader(false);
                    }
                });
            }

            function buildFilters() {
                return {
                    preset: activeRange,
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                    statuses: $('#status_filter').val() || [],
                    payment_methods: $('#payment_filter').val() || [],
                    order_sources: $('#source_filter').val() || [],
                };
            }

            function updateRange(range) {
                $('#range-label').text(range.label);
                $('#start_date').val(range.start);
                $('#end_date').val(range.end);
            }

            function updateSummary(summary) {
                $('#metric-gross-sales').text(formatMoney(summary.gross_sales));
                $('#metric-orders').text(summary.orders.toLocaleString());
                $('#metric-aov').text(formatMoney(summary.avg_order_value));
                $('#metric-net').text(formatMoney(summary.net_revenue));
                $('#metric-cogs').text(formatMoney(summary.cogs));
                $('#metric-profit').text(formatMoney(summary.profit));
                $('#metric-profit-margin').text(`Margin: ${summary.profit_margin}%`);
                $('#metric-units').text(summary.units_sold.toLocaleString());
                $('#metric-delivered').text(formatMoney(summary.delivered_revenue));
                $('#metric-open').text(formatMoney(summary.open_revenue));
                $('#metric-discount').text(formatMoney(summary.discount));
                $('#metric-shipping').text(formatMoney(summary.shipping));
                $('#metric-avg-shipping').text(formatMoney(summary.avg_shipping));
                $('#metric-paid').text(`${summary.paid_orders} paid / ${summary.unpaid_orders} unpaid`);
                $('#metric-ok-orders').text(summary.ok_orders.toLocaleString());
                $('#metric-cancelled').text(summary.cancelled_orders.toLocaleString());
                $('#metric-returned').text(summary.returned_orders.toLocaleString());
                $('#metric-refunded').text(summary.refunded_orders.toLocaleString());
                $('#metric-refund-amount').text(`Refund total: ${formatMoney(summary.refunded_revenue)}`);
                $('#metric-prepaid-rev').text(formatMoney(summary.prepaid_revenue));
                $('#metric-prepaid-count').text(`Orders: ${summary.prepaid_orders}`);
                $('#metric-cod-rev').text(formatMoney(summary.cod_revenue));
                $('#metric-cod-count').text(`Orders: ${summary.cod_orders}`);
                $('#metric-combo').text(summary.combo_orders.toLocaleString());
                $('#metric-repeat-rate').text(`${summary.repeat_rate}%`);
                $('#metric-repeat-count').text(`Repeat: ${summary.repeat_customers} / ${summary.unique_customers}`);
            }

            function updateTrendCharts(trend) {
                if (revenueTrendChart) revenueTrendChart.destroy();
                if (ordersTrendChart) ordersTrendChart.destroy();

                const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
                const ordersCtx = document.getElementById('ordersTrendChart').getContext('2d');

                const baseOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label === 'Revenue'
                                        ? formatMoney(context.parsed.y)
                                        : context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: false
                        },
                        y: {
                            display: false
                        }
                    }
                };

                revenueTrendChart = new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: trend.labels,
                        datasets: [{
                            label: 'Revenue',
                            data: trend.revenue,
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22,163,74,0.12)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 0,
                            pointHitRadius: 8,
                        }]
                    },
                    options: baseOptions
                });

                ordersTrendChart = new Chart(ordersCtx, {
                    type: 'line',
                    data: {
                        labels: trend.labels,
                        datasets: [{
                            label: 'Orders',
                            data: trend.orders,
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37,99,235,0.12)',
                            tension: 0.35,
                            fill: true,
                            pointRadius: 0,
                            pointHitRadius: 8,
                        }]
                    },
                    options: baseOptions
                });

                const lastIdx = trend.labels.length - 1;
                $('#trend-revenue-last').text(lastIdx >= 0 ? formatMoney(trend.revenue[lastIdx]) : '--');
                $('#trend-orders-last').text(lastIdx >= 0 ? trend.orders[lastIdx] : '--');
            }

            function emptyStateHtml(colspan, message, icon = 'fa-chart-pie') {
                return `
                    <tr>
                        <td colspan="${colspan}" class="text-center py-4 text-muted">
                            <div class="d-flex flex-column align-items-center gap-2">
                                <i class="fas ${icon} text-secondary opacity-50" style="font-size: 1.5rem;"></i>
                                <span class="small font-weight-medium text-secondary">${message}</span>
                            </div>
                        </td>
                    </tr>
                `;
            }

            function renderPaymentBreakdown(rows) {
                const tbody = $('#payment-rows');
                if (!rows || !rows.length) {
                    tbody.html(emptyStateHtml(4, 'No payment transactions recorded', 'fa-credit-card'));
                    return;
                }

                tbody.html(rows.map(row => `
                    <tr>
                        <td>${formatLabel(row.payment_method)}</td>
                        <td class="text-end">${row.orders.toLocaleString()}</td>
                        <td class="text-end">${formatMoney(row.revenue)}</td>
                        <td class="text-end">${formatMoney(row.avg_order_value)}</td>
                    </tr>
                `).join(''));
            }

            function renderStatusBreakdown(rows) {
                const tbody = $('#status-rows');
                if (!rows || !rows.length) {
                    tbody.html(emptyStateHtml(3, 'No order statuses to display', 'fa-info-circle'));
                    return;
                }

                tbody.html(rows.map(row => `
                    <tr>
                        <td>${formatStatus(row.status)}</td>
                        <td class="text-end">${row.orders.toLocaleString()}</td>
                        <td class="text-end">${formatMoney(row.revenue)}</td>
                    </tr>
                `).join(''));
            }

            function renderSourceBreakdown(rows) {
                const tbody = $('#source-rows');
                if (!rows || !rows.length) {
                    tbody.html(emptyStateHtml(3, 'No referral sources logged', 'fa-share-nodes'));
                    return;
                }

                tbody.html(rows.map(row => `
                    <tr>
                        <td>${formatLabel(row.order_source)}</td>
                        <td class="text-end">${row.orders.toLocaleString()}</td>
                        <td class="text-end">${formatMoney(row.revenue)}</td>
                    </tr>
                `).join(''));
            }

            function renderTopProducts(rows) {
                const tbody = $('#product-rows');
                if (!rows || !rows.length) {
                    tbody.html(emptyStateHtml(5, 'No product transactions recorded', 'fa-box'));
                    return;
                }

                tbody.html(rows.map((row, idx) => `
                    <tr>
                        <td><span style="width:22px;height:22px;background:#f1f5f9;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:700;color:#64748b;">${idx + 1}</span></td>
                        <td>${formatLabel(row.product_title)}</td>
                        <td class="text-end">${row.units.toLocaleString()}</td>
                        <td class="text-end">${formatMoney(row.revenue)}</td>
                        <td class="text-end">${formatMoney(row.avg_price)}</td>
                    </tr>
                `).join(''));
            }

            function renderPaymentReliability(rows) {
                const tbody = $('#payment-reliability-rows');
                if (!rows || !rows.length) {
                    tbody.html(emptyStateHtml(4, 'No payment reliability metrics', 'fa-shield-halved'));
                    return;
                }
                tbody.html(rows.map(row => `
                    <tr>
                        <td>${formatLabel(row.payment_method)}</td>
                        <td class="text-end">${row.orders.toLocaleString()}</td>
                        <td class="text-end">${row.failed.toLocaleString()}</td>
                        <td class="text-end">${row.failed_rate}%</td>
                    </tr>
                `).join(''));
            }

            function renderSourceRisk(rows) {
                const tbody = $('#source-risk-rows');
                if (!rows || !rows.length) {
                    tbody.html(emptyStateHtml(3, 'No source risk analysis', 'fa-triangle-exclamation'));
                    return;
                }
                tbody.html(rows.map(row => `
                    <tr>
                        <td>${formatLabel(row.order_source)}</td>
                        <td class="text-end">${row.cancel_rate}%</td>
                        <td class="text-end">${row.return_rate}%</td>
                    </tr>
                `).join(''));
            }

            function renderCourierPerformance(rows) {
                const tbody = $('#courier-rows');
                if (!rows || !rows.length) {
                    tbody.html(emptyStateHtml(4, 'No courier metrics found', 'fa-truck'));
                    return;
                }
                tbody.html(rows.map(row => `
                    <tr>
                        <td>${formatLabel(row.courier_provider)}</td>
                        <td class="text-end">${row.orders.toLocaleString()}</td>
                        <td class="text-end">${row.delivered.toLocaleString()}</td>
                        <td class="text-end">${row.exceptions.toLocaleString()}</td>
                    </tr>
                `).join(''));
            }

            function renderOrderTiming(data) {
                const ctx = document.getElementById('orderTimeChart').getContext('2d');
                if (orderTimeChart) {
                    orderTimeChart.destroy();
                }
                orderTimeChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'Orders',
                                data: data.orders,
                                borderColor: '#2563eb',
                                backgroundColor: 'rgba(37,99,235,0.12)',
                                tension: 0.35,
                                fill: true,
                                pointRadius: 0,
                                pointHitRadius: 8,
                            },
                            {
                                label: 'Revenue',
                                data: data.revenue,
                                borderColor: '#16a34a',
                                backgroundColor: 'rgba(22,163,74,0.12)',
                                tension: 0.35,
                                fill: true,
                                pointRadius: 0,
                                pointHitRadius: 8,
                                yAxisID: 'yRevenue'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            x: {
                                ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 12 },
                                grid: { display: false }
                            },
                            y: {
                                type: 'linear',
                                position: 'left',
                                ticks: { precision: 0 }
                            },
                            yRevenue: {
                                type: 'linear',
                                position: 'right',
                                ticks: {
                                    callback: (value) => formatMoney(value)
                                },
                                grid: { drawOnChartArea: false }
                            }
                        },
                        plugins: {
                            legend: { display: true },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        if (ctx.dataset.label === 'Revenue') {
                                            return `${ctx.dataset.label}: ${formatMoney(ctx.parsed.y)}`;
                                        }
                                        return `${ctx.dataset.label}: ${ctx.parsed.y}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function renderKeyMetrics(data) {
                const ctx = document.getElementById('keyMetricsChart').getContext('2d');
                if (keyMetricsChart) {
                    keyMetricsChart.destroy();
                }
                keyMetricsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                type: 'line',
                                label: 'Revenue',
                                data: data.revenue,
                                borderColor: '#16a34a',
                                backgroundColor: 'rgba(22,163,74,0.18)',
                                tension: 0.35,
                                fill: true,
                                pointRadius: 0,
                                pointHitRadius: 8,
                                yAxisID: 'yRevenue'
                            },
                            {
                                type: 'bar',
                                label: 'Orders',
                                data: data.orders,
                                backgroundColor: 'rgba(37,99,235,0.35)',
                                borderColor: '#2563eb',
                                borderWidth: 1,
                                maxBarThickness: 18,
                                yAxisID: 'yOrders'
                            },
                            {
                                type: 'line',
                                label: 'Refunded',
                                data: data.refunded_amount || [],
                                borderColor: '#e11d48',
                                backgroundColor: 'rgba(225,29,72,0.12)',
                                tension: 0.35,
                                fill: true,
                                pointRadius: 0,
                                pointHitRadius: 8,
                                yAxisID: 'yRevenue'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            x: {
                                ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 12 },
                                grid: { display: false }
                            },
                            yOrders: {
                                type: 'linear',
                                position: 'left',
                                ticks: { precision: 0, maxTicksLimit: 6 }
                            },
                            yRevenue: {
                                type: 'linear',
                                position: 'right',
                                ticks: {
                                    callback: (value) => formatMoney(value),
                                    maxTicksLimit: 6
                                },
                                grid: { drawOnChartArea: false }
                            }
                        },
                        plugins: {
                            legend: { display: true },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        if (ctx.dataset.label === 'Revenue' || ctx.dataset.label === 'Refunded') {
                                            return `${ctx.dataset.label}: ${formatMoney(ctx.parsed.y)}`;
                                        }
                                        return `${ctx.dataset.label}: ${ctx.parsed.y}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function formatMoney(amount) {
                const numeric = Number(amount) || 0;
                return currency.format(numeric);
            }

            function formatStatus(status) {
                if (!status) return 'Unknown';
                return status.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
            }

            function formatLabel(value) {
                if (!value) return 'Unknown';
                return value.toString().replace(/_/g, ' ');
            }

            function toggleLoader(show) {
                $('#report-loader').toggleClass('d-none', !show);
            }
        })();
    </script>
@endsection
