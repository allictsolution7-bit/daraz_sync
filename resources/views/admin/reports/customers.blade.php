@extends('layouts.master')

@section('styles')
    <style>
        .sales-report {
            background: #f8fafc;
            border-radius: 18px;
            padding: 18px;
        }

        .report-hero {
            background: #1e293b;
            border-radius: 16px;
            padding: 20px;
            color: #e2e8f0;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.12);
        }

        .report-hero .eyebrow {
            text-transform: uppercase;
            letter-spacing: 0.14em;
            font-size: 11px;
            color: #93c5fd;
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
            background: rgba(255, 255, 255, 0.15);
            color: #f8fafc;
            border: 1px solid rgba(255, 255, 255, 0.25);
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
            color: #0f172a;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            font-size: 20px;
        }

        .report-card {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 6px 14px rgba(15, 23, 42, 0.05);
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
                    <div class="eyebrow">Customer intelligence</div>
                    <h4 class="mb-1">Customer Reports</h4>
                    <div class="text-white">Lifetime value, repeat behavior, sources, and payment signals.</div>
                    <div class="hero-chips">
                        <span class="hero-chip">
                            <i class="fas fa-calendar-alt text-primary"></i>
                            <span id="range-label">Loading...</span>
                        </span>
                        <span class="hero-chip">
                            <i class="fas fa-user text-warning"></i> Unique / repeat
                        </span>
                        <span class="hero-chip">
                            <i class="fas fa-chart-pie text-success"></i> Mix by city & source
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
                        <div class="stat-label">Unique customers</div>
                        <div class="stat-value" id="metric-unique">--</div>
                        <div class="stat-sub">Distinct phone numbers</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-users"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">New customers</div>
                        <div class="stat-value" id="metric-new">--</div>
                        <div class="stat-sub">First order in range</div>
                    </div>
                    <div class="stat-accent"><i class="fas fa-user-plus"></i></div>
                </div>
            </div>
            <div class="stat-tile">
                <div class="stat-top">
                    <div>
                        <div class="stat-label">Repeat customers</div>
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

        <div class="section-heading"><span class="dot"></span> District heatmap</div>
        <div class="row g-3 mb-3">
            <div class="col-lg-7">
                <div class="card report-card h-100">
                    <div class="card-body">
                        <h6 class="mb-2 d-flex align-items-center gap-2">
                            <i class="fas fa-map text-primary"></i> Bangladesh districts (bubble map)
                        </h6>
                        <div style="min-height:260px;">
                            <canvas id="districtMapChart" height="240"></canvas>
                        </div>
                        <div class="text-soft small mt-1">Sized by revenue, positioned by approximate district centroids.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card report-card h-100">
                    <div class="card-body">
                        <h6 class="mb-2 d-flex align-items-center gap-2">
                            <i class="fas fa-list text-success"></i> District breakdown
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

        <div class="section-heading"><span class="dot"></span> Segments</div>
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

        <div class="section-heading"><span class="dot"></span> Top customers</div>
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
