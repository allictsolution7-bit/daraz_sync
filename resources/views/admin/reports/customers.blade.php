@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 Custom Styles */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #cbd5e1 !important;
            border-radius: 10px !important;
            padding: 2px 10px !important;
            min-height: 42px !important;
            transition: all 0.2s ease;
            background-color: #fff !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding: 0 !important;
            margin: 0 !important;
            float: left;
            width: auto;
        }
        .select2-container--default .select2-selection--multiple .select2-search--inline {
            float: left;
            margin: 0 !important;
            height: 36px;
            display: inline-flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--multiple .select2-search__field {
            margin: 0 !important;
            font-family: inherit !important;
            font-size: 0.88rem !important;
            color: #1e293b !important;
            text-align: left !important;
            padding: 0 !important;
            height: 100% !important;
        }
        .select2-container--default .select2-selection--multiple .select2-search--inline:first-child,
        .select2-container--default .select2-selection--multiple .select2-search--inline:first-child .select2-search__field {
            width: 100% !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #eff6ff !important;
            border: 1px solid #bfdbfe !important;
            color: #2563eb !important;
            border-radius: 0.375rem !important;
            padding: 2px 8px !important;
            font-size: 0.8rem !important;
            font-weight: 500 !important;
            margin: 4px 4px 4px 0 !important;
            float: left;
            display: inline-flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #3b82f6 !important;
            margin-right: 5px !important;
            border: none !important;
            background: transparent !important;
            display: inline-flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #1d4ed8 !important;
        }
        .select2-dropdown {
            border: 1px solid #cbd5e1 !important;
            border-radius: 10px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
        
        .sales-report {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            border-radius: 18px;
            padding: 18px;
        }

        .filter-card {
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            background: #fff;
            padding: 24px !important;
        }

        .sales-report .filter-chip {
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            font-size: 0.82rem;
            color: #475569;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            transition: all 0.2s ease;
        }
        .sales-report .filter-chip:hover {
            color: #0f172a;
            border-color: #cbd5e1;
            background: #f1f5f9;
            transform: translateY(-1px);
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
            border-left: 4px solid #8b5cf6;
        }
        .stat-tile:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08);
        }

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
            background: #f5f3ff;
            color: #8b5cf6;
            border: 1px solid #ddd6fe;
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
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%) !important;
            border-color: transparent !important;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
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
            background: linear-gradient(135deg, #4f46e5, #10b981);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
        }
        .form-label {
            font-weight: 600;
            color: #475569;
            font-size: 0.8rem;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }
        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.65rem 1rem;
            font-size: 0.88rem;
            color: #1e293b;
            background-color: #fff;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
            outline: 0;
        }
        #apply-filters {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            border: none;
            color: #ffffff;
            border-radius: 10px;
            padding: 0.68rem 1.2rem;
            font-weight: 600;
            font-size: 0.88rem;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
            transition: all 0.2s ease;
        }
        #apply-filters:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.3);
            filter: brightness(1.05);
        }
        #apply-filters:active {
            transform: translateY(0);
        }

        /* Dark Theme Analytics Card Styles */
        .card-dark-analytics {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            border-radius: 20px !important;
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.25) !important;
            overflow: hidden;
            color: #f8fafc;
        }
        .card-dark-analytics .card-header-premium {
            background: rgba(255, 255, 255, 0.02) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
            padding: 1rem 1.25rem;
        }
        .card-dark-analytics .card-header-premium h6 {
            color: #f8fafc !important;
            font-size: 0.9rem;
            font-weight: 700;
        }
        .card-dark-analytics .card-header-premium .text-muted {
            color: #94a3b8 !important;
            font-size: 0.75rem;
        }
        .card-dark-analytics .badge {
            background: rgba(255, 255, 255, 0.08) !important;
            color: #cbd5e1 !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            font-size: 0.72rem;
            padding: 5px 10px;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid sales-report">
        <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
            <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.25rem; font-family: 'Outfit', sans-serif;">
                Client Base & Retention Overview 
                <span class="badge bg-light text-secondary border ms-2" id="range-label" style="font-size: 0.75rem; font-weight: 500;">Loading...</span>
            </h4>
            <button class="btn btn-sm btn-outline-secondary" id="refresh-report" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-rotate"></i> Refresh
            </button>
        </div>

        <div class="filter-card mb-4">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-4 pb-3 border-bottom" style="border-color: #f1f5f9 !important;">
                    <span class="text-soft small fw-bold text-uppercase me-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Period Quick-Select:</span>
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

                <div class="row g-3 align-items-end">
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <label class="form-label mb-1">Start date</label>
                        <input type="date" class="form-control" id="start_date">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <label class="form-label mb-1">End date</label>
                        <input type="date" class="form-control" id="end_date">
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <label class="form-label mb-1">Order status</label>
                        <select class="form-control" id="status_filter" name="statuses[]" multiple>
                            @foreach ($statusOptions as $status)
                                <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <label class="form-label mb-1">Payment method</label>
                        <select class="form-control" id="payment_filter" name="payment_methods[]" multiple>
                            @foreach ($paymentMethods as $method)
                                <option value="{{ $method }}">{{ strtoupper($method) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <label class="form-label mb-1">Order source</label>
                        <select class="form-control" id="source_filter" name="order_sources[]" multiple>
                            @foreach ($orderSources as $source)
                                <option value="{{ $source }}">{{ $source }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6">
                        <button class="btn btn-primary w-100" id="apply-filters" style="height: 42px;">
                            <i class="fas fa-filter"></i> Apply filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-heading"><span class="dot"></span> Overview Metrics</div>
        <div class="stat-grid mb-3">
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Individual Clients</div>
                        <div class="stat-value" id="metric-unique">--</div>
                        <div class="stat-sub">Distinct phone numbers</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-users"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">First-Time Buyers</div>
                        <div class="stat-value" id="metric-new">--</div>
                        <div class="stat-sub">First order in range</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-user-plus"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Recurring Buyers</div>
                        <div class="stat-value" id="metric-repeat">--</div>
                        <div class="stat-sub" id="metric-repeat-rate">Repeat rate</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-user-check"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Orders</div>
                        <div class="stat-value" id="metric-orders">--</div>
                        <div class="stat-sub" id="metric-orders-per-customer">Avg per customer</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-shopping-bag"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Revenue</div>
                        <div class="stat-value" id="metric-revenue">--</div>
                        <div class="stat-sub" id="metric-revenue-per-customer">Avg per customer</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-sack-dollar"></i></div>
                </div>
            </div>
        </div>

        <div class="section-heading"><span class="dot"></span> Mix & segments</div>
        <div class="row g-3 mb-3">
            <div class="col-lg-4">
                <div class="card report-card h-100">
                    <div class="card-body">
                        <h6 class="mb-3 d-flex align-items-center gap-2">
                            <i class="fas fa-city text-primary"></i> Cities
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>City</th>
                                        <th class="text-end">Customers</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody id="city-rows">
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
                            <i class="fas fa-diagram-project text-warning"></i> Sources
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th class="text-end">Customers</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody id="source-rows">
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
                            <i class="fas fa-credit-card text-success"></i> Payment mix
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th class="text-end">Customers</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Revenue</th>
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
        </div>

        <div class="section-heading"><span class="dot"></span> Geographic Heatmap</div>
        <div class="row g-3 mb-3">
            <div class="col-lg-7">
                <div class="card card-dark-analytics h-100">
                    <div class="card-header-premium">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width:32px;height:32px;background:rgba(255,255,255,0.05);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-map text-primary" style="font-size:0.85rem;"></i>
                            </div>
                            <div>
                                <h6>Regional Distribution Hubs (Density Mapping)</h6>
                                <div class="text-muted" style="font-size:0.75rem;">Sized by revenue, positioned by approximate district centroids.</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3" style="min-height:260px;">
                        <canvas id="districtMapChart" height="240"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card report-card h-100">
                    <div class="card-body">
                        <h6 class="mb-2 d-flex align-items-center gap-2">
                            <i class="fas fa-list text-success"></i> Territory Distributions
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>District</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Customers</th>
                                        <th class="text-end">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody id="district-rows">
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

        <div class="section-heading"><span class="dot"></span> Buyer Cohorts</div>
        <div class="card report-card mb-3">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-sm-3">
                        <div class="stat-label">1 order</div>
                        <div class="stat-value" id="seg-1">--</div>
                    </div>
                    <div class="col-sm-3">
                        <div class="stat-label">2-3 orders</div>
                        <div class="stat-value" id="seg-2-3">--</div>
                    </div>
                    <div class="col-sm-3">
                        <div class="stat-label">4-5 orders</div>
                        <div class="stat-value" id="seg-4-5">--</div>
                    </div>
                    <div class="col-sm-3">
                        <div class="stat-label">6+ orders</div>
                        <div class="stat-value" id="seg-6">--</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-heading"><span class="dot"></span> Premier Clients Ledger</div>
        <div class="card report-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th class="text-end">Orders</th>
                                <th class="text-end">Revenue</th>
                                <th class="text-end">Last order</th>
                            </tr>
                        </thead>
                        <tbody id="customer-rows">
                            <tr>
                                <td colspan="5" class="text-center text-soft py-3">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="report-loader d-none" id="report-loader">
        <div class="spinner-border text-primary mb-2" role="status"></div>
        <div class="text-soft">Loading customer insights...</div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        (function() {
            let activeRange = '{{ $defaultRange }}';
            let districtMapChart = null;
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
                    url: '{{ route('admin.customers.reports.data') }}',
                    method: 'GET',
                    data: buildFilters(),
                    success: function(response) {
                        updateRange(response.range);
                        updateSummary(response.summary);
                        renderTopCustomers(response.top_customers);
                        renderCityMix(response.city_mix);
                        renderSourceMix(response.source_mix);
                        renderPaymentMix(response.payment_mix);
                        renderSegments(response.segments);
                        renderDistrictHeatmap(response.districts);
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
                $('#metric-unique').text(summary.unique_customers.toLocaleString());
                $('#metric-new').text(summary.new_customers.toLocaleString());
                $('#metric-repeat').text(summary.repeat_customers.toLocaleString());
                $('#metric-repeat-rate').text(`Repeat rate: ${summary.repeat_rate}%`);
                $('#metric-orders').text(summary.orders.toLocaleString());
                $('#metric-orders-per-customer').text(`Avg: ${summary.avg_orders_per_customer}`);
                $('#metric-revenue').text(formatMoney(summary.revenue));
                $('#metric-revenue-per-customer').text(`Avg: ${formatMoney(summary.avg_revenue_per_customer)}`);
            }

            function renderTopCustomers(rows) {
                const tbody = $('#customer-rows');
                if (!rows || !rows.length) {
                    tbody.html('<tr><td colspan="5" class="text-center text-soft py-3">No customer data.</td></tr>');
                    return;
                }
                tbody.html(rows.map(row => `
                    <tr>
                        <td>${formatLabel(row.name)}</td>
                        <td>${row.phone || ''}</td>
                        <td class="text-end">${row.orders.toLocaleString()}</td>
                        <td class="text-end">${formatMoney(row.revenue)}</td>
                        <td class="text-end">${row.last_order}</td>
                    </tr>
                `).join(''));
            }

            function renderCityMix(rows) {
                const tbody = $('#city-rows');
                if (!rows || !rows.length) {
                    tbody.html('<tr><td colspan="4" class="text-center text-soft py-3">No city data.</td></tr>');
                    return;
                }
                tbody.html(rows.map(row => `
                    <tr>
                        <td>${formatLabel(row.city)}</td>
                        <td class="text-end">${row.customers.toLocaleString()}</td>
                        <td class="text-end">${row.orders.toLocaleString()}</td>
                        <td class="text-end">${formatMoney(row.revenue)}</td>
                    </tr>
                `).join(''));
            }

            function renderSourceMix(rows) {
                const tbody = $('#source-rows');
                if (!rows || !rows.length) {
                    tbody.html('<tr><td colspan="4" class="text-center text-soft py-3">No source data.</td></tr>');
                    return;
                }
                tbody.html(rows.map(row => `
                    <tr>
                        <td>${formatLabel(row.order_source)}</td>
                        <td class="text-end">${row.customers.toLocaleString()}</td>
                        <td class="text-end">${row.orders.toLocaleString()}</td>
                        <td class="text-end">${formatMoney(row.revenue)}</td>
                    </tr>
                `).join(''));
            }

            function renderPaymentMix(rows) {
                const tbody = $('#payment-rows');
                if (!rows || !rows.length) {
                    tbody.html('<tr><td colspan="4" class="text-center text-soft py-3">No payment data.</td></tr>');
                    return;
                }
                tbody.html(rows.map(row => `
                    <tr>
                        <td>${formatLabel(row.payment_method)}</td>
                        <td class="text-end">${row.customers.toLocaleString()}</td>
                        <td class="text-end">${row.orders.toLocaleString()}</td>
                        <td class="text-end">${formatMoney(row.revenue)}</td>
                    </tr>
                `).join(''));
            }

            function renderSegments(buckets) {
                $('#seg-1').text((buckets['1'] || 0).toLocaleString());
                $('#seg-2-3').text((buckets['2-3'] || 0).toLocaleString());
                $('#seg-4-5').text((buckets['4-5'] || 0).toLocaleString());
                $('#seg-6').text((buckets['6+'] || 0).toLocaleString());
            }

            function renderDistrictHeatmap(rows) {
                const tbody = $('#district-rows');
                if (!rows || !rows.length) {
                    tbody.html('<tr><td colspan="4" class="text-center text-soft py-3">No district data.</td></tr>');
                } else {
                    tbody.html(rows.map(row => `
                        <tr>
                            <td>${formatLabel(row.district)}</td>
                            <td class="text-end">${row.orders.toLocaleString()}</td>
                            <td class="text-end">${row.customers.toLocaleString()}</td>
                            <td class="text-end">${formatMoney(row.revenue)}</td>
                        </tr>
                    `).join(''));
                }

                const coords = {
                    dhaka: { lat: 23.81, lon: 90.41 },
                    chattogram: { lat: 22.36, lon: 91.78 },
                    chittagong: { lat: 22.36, lon: 91.78 },
                    khulna: { lat: 22.85, lon: 89.54 },
                    rajshahi: { lat: 24.37, lon: 88.60 },
                    rangpur: { lat: 25.74, lon: 89.27 },
                    sylhet: { lat: 24.89, lon: 91.86 },
                    barishal: { lat: 22.70, lon: 90.35 },
                    comilla: { lat: 23.46, lon: 91.18 },
                    noakhali: { lat: 22.87, lon: 91.10 },
                    gazipur: { lat: 24.00, lon: 90.42 },
                    narayanganj: { lat: 23.62, lon: 90.50 },
                    tangail: { lat: 24.25, lon: 89.92 },
                    mymensingh: { lat: 24.75, lon: 90.42 },
                    brahmanbaria: { lat: 23.96, lon: 91.11 },
                    bogura: { lat: 24.85, lon: 89.37 },
                    dinajpur: { lat: 25.63, lon: 88.64 },
                    pabna: { lat: 24.00, lon: 89.25 },
                    jashore: { lat: 23.17, lon: 89.22 },
                    kushtia: { lat: 23.90, lon: 89.12 },
                    coxsbazar: { lat: 21.43, lon: 92.01 },
                };

                const lonMin = 88.0, lonMax = 92.5;
                const latMin = 20.5, latMax = 26.5;

                const mapped = rows
                    .map(row => {
                        const key = (row.district || '').toLowerCase().replace(/\s+/g, '');
                        const c = coords[key];
                        if (!c) return null;
                        return {
                            x: c.lon,
                            y: c.lat,
                            r: Math.min(35, Math.max(8, Math.sqrt(row.revenue) / 40)),
                            label: row.district,
                            revenue: row.revenue,
                            orders: row.orders
                        };
                    })
                    .filter(Boolean);

                if (!mapped.length) {
                    if (districtMapChart) {
                        districtMapChart.destroy();
                        districtMapChart = null;
                    }
                    return;
                }

                const ctx = document.getElementById('districtMapChart').getContext('2d');
                if (districtMapChart) {
                    districtMapChart.destroy();
                }
                districtMapChart = new Chart(ctx, {
                    type: 'bubble',
                    data: {
                        datasets: [{
                            label: 'Districts',
                            data: mapped.map(m => ({ x: m.x, y: m.y, r: m.r, label: m.label, revenue: m.revenue, orders: m.orders })),
                            backgroundColor: 'rgba(37,99,235,0.35)',
                            borderColor: '#2563eb'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                type: 'linear',
                                min: lonMin,
                                max: lonMax,
                                display: false,
                            },
                            y: {
                                type: 'linear',
                                min: latMin,
                                max: latMax,
                                reverse: true,
                                display: false,
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        const d = ctx.raw;
                                        return `${d.label}: ${formatMoney(d.revenue)} | Orders: ${d.orders}`;
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
