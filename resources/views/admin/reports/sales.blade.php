@extends('layouts.master')

@section('styles')
    <style>
        .sales-report {
            background: #f8fafc;
            border-radius: 18px;
            padding: 18px;
        }

        .report-hero {
            background: #2C5F64;
            border-radius: 16px;
            padding: 20px;
            color: #f8fafc;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
        }

        .report-hero .eyebrow {
            text-transform: uppercase;
            letter-spacing: 0.14em;
            font-size: 11px;
            color: #dcfce7;
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
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .hero-actions .btn {
            border-radius: 10px;
            font-weight: 600;
        }

        .filter-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(15, 23, 42, 0.06);
            background: #fff;
        }

        .sales-report .filter-chip {
            border-radius: 30px;
            padding: 6px 12px;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .stat-tile {
            position: relative;
            border-radius: 12px;
            padding: 12px 14px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.05);
            overflow: hidden;
        }

        .stat-tile .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .stat-tile .stat-label {
            font-size: 12px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-tile .stat-value {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .stat-tile .stat-sub {
            color: #6b7280;
            font-size: 12px;
        }

        .stat-accent {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #065f46;
            background: #ecfdf3;
            border: 1px solid #bbf7d0;
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

        .report-card h6 {
            font-weight: 700;
        }

        .report-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 6px 14px rgba(15, 23, 42, 0.05);
        }

        .sparkline-card {
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            background: #fff;
            box-shadow: 0 6px 14px rgba(15, 23, 42, 0.05);
            height: 100%;
        }

        .sparkline-header {
            padding: 12px 14px 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sparkline-body {
            padding: 0 10px 10px;
        }

        .table thead th {
            text-transform: uppercase;
            font-size: 12px;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
            background: #f8fafc;
        }

        .table td {
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .range-btn.active {
            color: #fff !important;
            background: #2563eb !important;
            border-color: #2563eb !important;
        }

        .text-soft {
            color: #6b7280;
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 10px 0 8px;
            font-weight: 700;
            color: #0f172a;
        }

        .section-heading .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #22c55e);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
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
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Total revenue</div>
                        <div class="stat-value" id="metric-gross-sales">--</div>
                        <div class="stat-sub">Gross sales</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-sack-dollar"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Orders</div>
                        <div class="stat-value" id="metric-orders">--</div>
                        <div class="stat-sub" id="metric-paid">Paid vs unpaid</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-shopping-basket"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Average order value</div>
                        <div class="stat-value" id="metric-aov">--</div>
                        <div class="stat-sub">Per completed order</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-gauge-high"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Net revenue</div>
                        <div class="stat-value" id="metric-net">--</div>
                        <div class="stat-sub">Revenue - discount + shipping</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-wallet"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">COGS</div>
                        <div class="stat-value" id="metric-cogs">--</div>
                        <div class="stat-sub">Cost of goods sold</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-box"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Profit</div>
                        <div class="stat-value" id="metric-profit">--</div>
                        <div class="stat-sub" id="metric-profit-margin">Margin %</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-chart-pie"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Units sold</div>
                        <div class="stat-value" id="metric-units">--</div>
                        <div class="stat-sub">Items across orders</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-cubes"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Delivered revenue</div>
                        <div class="stat-value" id="metric-delivered">--</div>
                        <div class="stat-sub">Marked delivered</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-truck"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Open revenue</div>
                        <div class="stat-value" id="metric-open">--</div>
                        <div class="stat-sub">Not delivered yet</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-hourglass-half"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Discount given</div>
                        <div class="stat-value" id="metric-discount">--</div>
                        <div class="stat-sub">Total discounts</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-percent"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Shipping collected</div>
                        <div class="stat-value" id="metric-shipping">--</div>
                        <div class="stat-sub">Shipping charges</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-box-open"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Avg shipping</div>
                        <div class="stat-value" id="metric-avg-shipping">--</div>
                        <div class="stat-sub">Per order</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-truck-fast"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Delivered orders</div>
                        <div class="stat-value" id="metric-ok-orders">--</div>
                        <div class="stat-sub">Marked delivered</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-check-circle"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Cancelled</div>
                        <div class="stat-value" id="metric-cancelled">--</div>
                        <div class="stat-sub">Status: cancelled</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-ban"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Refunded</div>
                        <div class="stat-value" id="metric-refunded">--</div>
                        <div class="stat-sub" id="metric-refund-amount">Refund total</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-rotate-left"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Returned</div>
                        <div class="stat-value" id="metric-returned">--</div>
                        <div class="stat-sub">Status: returned</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-undo-alt"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Prepaid revenue</div>
                        <div class="stat-value" id="metric-prepaid-rev">--</div>
                        <div class="stat-sub" id="metric-prepaid-count">Prepaid orders</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-credit-card"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">COD revenue</div>
                        <div class="stat-value" id="metric-cod-rev">--</div>
                        <div class="stat-sub" id="metric-cod-count">COD orders</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-money-bill-wave"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Combo orders</div>
                        <div class="stat-value" id="metric-combo">--</div>
                        <div class="stat-sub">Marked as combo</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-gift"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Repeat customers</div>
                        <div class="stat-value" id="metric-repeat-rate">--</div>
                        <div class="stat-sub" id="metric-repeat-count">Returning vs unique</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-user-check"></i></div>
                </div>
            </div>
        </div>

        <div class="section-heading mt-3"><span class="dot"></span> Trends</div>
        <div class="row g-3 mb-3">
            <div class="col-lg-6">
                <div class="sparkline-card">
                    <div class="sparkline-header">
                        <div>
                            <div class="fw-semibold">Revenue trend</div>
                            <div class="text-soft small">Compact view</div>
                        </div>
                        <span class="badge bg-light text-dark border" id="trend-revenue-last">--</span>
                    </div>
                    <div class="sparkline-body" style="height: 180px;">
                        <canvas id="revenueTrendChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="sparkline-card">
                    <div class="sparkline-header">
                        <div>
                            <div class="fw-semibold">Orders trend</div>
                            <div class="text-soft small">Compact view</div>
                        </div>
                        <span class="badge bg-light text-dark border" id="trend-orders-last">--</span>
                    </div>
                    <div class="sparkline-body" style="height: 180px;">
                        <canvas id="ordersTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-heading"><span class="dot"></span> Order timing</div>
        <div class="card report-card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="mb-0">Orders by hour of day</h6>
                        <div class="text-soft small">Spot peaks across 24h (orders & revenue)</div>
                    </div>
                </div>
                <div style="height: 380px;">
                    <canvas id="orderTimeChart"></canvas>
                </div>
            </div>
        </div>

        <div class="section-heading"><span class="dot"></span> Combined metrics</div>
        <div class="card report-card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="mb-0">Orders, revenue & refunds (timeline)</h6>
                        <div class="text-soft small">Compact view with multiple signals</div>
                    </div>
                </div>
                <div style="height: 220px;">
                    <canvas id="keyMetricsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="section-heading"><span class="dot"></span> Breakdowns</div>
        <div class="row g-3 mb-3">
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-body">
                        <h6 class="mb-3 d-flex align-items-center gap-2">
                            <i class="fas fa-wallet text-primary"></i> Payment breakdown
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Revenue</th>
                                        <th class="text-end">Avg order</th>
                                    </tr>
                                </thead>
                                <tbody id="payment-rows">
                                    <tr>
                                        <td colspan="4" class="text-center text-soft py-3">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-body">
                        <h6 class="mb-3 d-flex align-items-center gap-2">
                            <i class="fas fa-flag text-success"></i> Status mix
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody id="status-rows">
                                    <tr>
                                        <td colspan="3" class="text-center text-soft py-3">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-body">
                        <h6 class="mb-3 d-flex align-items-center gap-2">
                            <i class="fas fa-share-nodes text-warning"></i> Source performance
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody id="source-rows">
                                    <tr>
                                        <td colspan="3" class="text-center text-soft py-3">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-body">
                        <h6 class="mb-3 d-flex align-items-center gap-2">
                            <i class="fas fa-shield-halved text-danger"></i> Payment reliability
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Failed</th>
                                        <th class="text-end">Failed %</th>
                                    </tr>
                                </thead>
                                <tbody id="payment-reliability-rows">
                                    <tr>
                                        <td colspan="4" class="text-center text-soft py-3">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-body">
                        <h6 class="mb-3 d-flex align-items-center gap-2">
                            <i class="fas fa-chart-area text-warning"></i> Source risk
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th class="text-end">Cancel %</th>
                                        <th class="text-end">Return %</th>
                                    </tr>
                                </thead>
                                <tbody id="source-risk-rows">
                                    <tr>
                                        <td colspan="3" class="text-center text-soft py-3">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-body">
                        <h6 class="mb-3 d-flex align-items-center gap-2">
                            <i class="fas fa-truck text-info"></i> Courier performance
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Courier</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Delivered</th>
                                        <th class="text-end">Exceptions</th>
                                    </tr>
                                </thead>
                                <tbody id="courier-rows">
                                    <tr>
                                        <td colspan="4" class="text-center text-soft py-3">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-heading"><span class="dot"></span> Top products</div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 d-flex align-items-center gap-2">
                        <i class="fas fa-star text-primary"></i> Top products
                    </h6>
                    <div class="text-soft small">Sorted by revenue (top 8)</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Units</th>
                                <th class="text-end">Revenue</th>
                                <th class="text-end">Avg price</th>
                            </tr>
                        </thead>
                        <tbody id="product-rows">
                            <tr>
                                <td colspan="4" class="text-center text-soft py-3">Loading...</td>
                            </tr>
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

            function renderPaymentBreakdown(rows) {
                const tbody = $('#payment-rows');
                if (!rows || !rows.length) {
                    tbody.html('<tr><td colspan="4" class="text-center text-soft py-3">No payment data.</td></tr>');
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
                    tbody.html('<tr><td colspan="3" class="text-center text-soft py-3">No status data.</td></tr>');
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
                    tbody.html('<tr><td colspan="3" class="text-center text-soft py-3">No source data.</td></tr>');
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
                    tbody.html('<tr><td colspan="4" class="text-center text-soft py-3">No product sales in this range.</td></tr>');
                    return;
                }

                tbody.html(rows.map(row => `
                    <tr>
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
                    tbody.html('<tr><td colspan="4" class="text-center text-soft py-3">No payment data.</td></tr>');
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
                    tbody.html('<tr><td colspan="3" class="text-center text-soft py-3">No source data.</td></tr>');
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
                    tbody.html('<tr><td colspan="4" class="text-center text-soft py-3">No courier data.</td></tr>');
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
