@extends('layouts.master')

@section('content')
    @php
        $licenseService = app(\App\Services\LicenseService::class);
        $licenseStatus = $licenseService->getLicenseStatus();
        $supportStatus = $licenseService->getSupportStatus();
        $updateStatus = $licenseService->getUpdateStatus();
    @endphp

    @if ($licenseStatus['valid'])
    @php
        $updateService = app(\App\Services\UpdateService::class);
        $latestReleaseSummary = $updateService->fetchLatestReleaseSummary();
        $currentVersion = $updateService->currentVersion();

        // Only show the banner if there is a newer version than the one currently installed
        $shouldShowLatestBanner = !empty($latestReleaseSummary['version'] ?? null) &&
            version_compare($latestReleaseSummary['version'], $currentVersion, '>');

        $promoSnippet = null;
        if (!empty($latestReleaseSummary['promo_content'])) {
            $promoSnippet = \Illuminate\Support\Str::limit(strip_tags($latestReleaseSummary['promo_content']), 160);
        } elseif (!empty($latestReleaseSummary['notes'])) {
            $promoSnippet = \Illuminate\Support\Str::limit(strip_tags($latestReleaseSummary['notes']), 160);
        }
        $releaseNotesList = [];
        if (!empty($latestReleaseSummary['notes'])) {
            $releaseNotesLines = preg_split('/\r\n|\r|\n/', trim($latestReleaseSummary['notes']));
            $releaseNotesList = array_filter(array_map('trim', $releaseNotesLines));
        }
    @endphp

    @if ($shouldShowLatestBanner)
        <div class="container-fluid mb-3">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-primary shadow-sm d-flex align-items-start">
                        <div class="me-3 mt-1">
                            <i class="fas fa-bullhorn fa-lg text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div>
                                    <h6 class="mb-1 fw-bold">Latest Update: Version
                                        {{ $latestReleaseSummary['version'] ?? 'N/A' }}</h6>
                                    <span class="badge bg-light text-primary border">
                                        {{ ucfirst($latestReleaseSummary['release_channel'] ?? 'stable') }} channel
                                    </span>
                                    @if (!empty($latestReleaseSummary['published_at']))
                                        <small class="text-muted ms-2">
                                            Released
                                            {{ \Illuminate\Support\Carbon::parse($latestReleaseSummary['published_at'])->diffForHumans() }}
                                        </small>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('admin.updates.index') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                    <a href="{{ route('admin.updates.index') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-cloud-arrow-down me-1"></i>Apply Update
                                    </a>
                                </div>
                            </div>



                            @if (!empty($releaseNotesList))
                                <div class="mb-0">
                                    <ul class="mb-0 ps-3 text-muted small">
                                        @foreach ($releaseNotesList as $line)
                                            <li>{{ $line }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @endif

    <!-- License Warning Banner -->
    @if (
        !$licenseStatus['valid'] ||
            ($supportStatus['status'] ?? '') === 'expired' ||
            ($updateStatus['status'] ?? '') === 'expired')
        <div class="container-fluid mb-3">
            <div class="row">
                <div class="col-12">
                    @if (!$licenseStatus['valid'])
                        <div class="alert alert-danger" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-key fa-2x me-3 text-danger"></i>
                                <div class="flex-grow-1">
                                    <h6 class="alert-heading mb-1">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        License Required
                                    </h6>
                                    <p class="mb-2">
                                        <strong>Activate your license</strong> to unlock all premium features including
                                        POS system, landing page builder, and more.
                                    </p>
                                    <div class="d-flex gap-2 mb-3">
                                        <a href="{{ route('admin.license.index') }}" class="btn btn-danger btn-sm">
                                            <i class="fas fa-key me-1"></i> Activate License
                                        </a>
                                        <a href="{{ route('admin.license.index') }}"
                                            class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-info-circle me-1"></i> Learn More
                                        </a>
                                    </div>
                                    <div class="border-top pt-3">
                                        <p class="mb-2 text-muted">
                                            <i class="fas fa-phone me-1"></i>
                                            <strong>Need help?</strong> Call us:
                                            <a href="tel:+8801779542054" class="text-danger fw-bold">+880 1779 542
                                                054</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($licenseStatus['valid'] && ($supportStatus['status'] ?? '') === 'expired')
                        <div class="alert alert-warning" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-headset fa-2x me-3 text-warning"></i>
                                <div class="flex-grow-1">
                                    <h6 class="alert-heading mb-1">
                                        <i class="fas fa-clock me-2"></i>
                                        Support Period Expired
                                    </h6>
                                    <p class="mb-2">
                                        <strong>Your support period has ended.</strong> Premium features may be limited.
                                        Renew your support to get priority assistance and updates.
                                    </p>
                                    <div class="d-flex gap-2 mb-3">
                                        <a href="{{ route('admin.license.index') }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-headset me-1"></i> Renew Support
                                        </a>
                                        <a href="{{ route('admin.license.index') }}"
                                            class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-info-circle me-1"></i> View Details
                                        </a>
                                    </div>
                                    <div class="border-top pt-3">
                                        <p class="mb-2 text-muted">
                                            <i class="fas fa-phone me-1"></i>
                                            <strong>Need help?</strong> Call us:
                                            <a href="tel:+8801779542054" class="text-warning fw-bold">+880 1779 542
                                                054</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($licenseStatus['valid'] && ($updateStatus['status'] ?? '') === 'expired')
                        <div class="alert alert-info" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-download fa-2x me-3 text-info"></i>
                                <div class="flex-grow-1">
                                    <h6 class="alert-heading mb-1">
                                        <i class="fas fa-clock me-2"></i>
                                        Update Period Expired
                                    </h6>
                                    <p class="mb-2">
                                        <strong>Your update period has ended.</strong> You won't receive system updates.
                                        Renew to get the latest features and security patches.
                                    </p>
                                    <div class="d-flex gap-2 mb-3">
                                        <a href="{{ route('admin.license.index') }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-download me-1"></i> Renew Updates
                                        </a>
                                        <a href="{{ route('admin.license.index') }}"
                                            class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-info-circle me-1"></i> View Details
                                        </a>
                                    </div>
                                    <div class="border-top pt-3">
                                        <p class="mb-2 text-muted">
                                            <i class="fas fa-phone me-1"></i>
                                            <strong>Need help?</strong> Call us:
                                            <a href="tel:+8801779542054" class="text-info fw-bold">+880 1779 542 054</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (
                        $licenseStatus['valid'] &&
                            ($supportStatus['status'] ?? '') === 'active' &&
                            ($updateStatus['status'] ?? '') === 'active')
                        <div class="alert alert-success" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                                <div class="flex-grow-1">
                                    <h6 class="alert-heading mb-1">
                                        <i class="fas fa-star me-2"></i>
                                        License Active & Complete
                                    </h6>
                                    <p class="mb-2">
                                        <strong>Your license is fully active!</strong> All premium features are unlocked
                                        including POS system, landing page builder, and more.
                                    </p>
                                    <div class="d-flex gap-2 mb-3">
                                        <a href="{{ route('admin.license.index') }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-eye me-1"></i> View License
                                        </a>
                                        <a href="{{ route('admin.landing-pages.index') }}"
                                            class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-rocket me-1"></i> Landing Pages
                                        </a>
                                        <a href="{{ route('admin.pos.index') }}"
                                            class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-cash-register me-1"></i> POS System
                                        </a>
                                    </div>
                                    <div class="border-top pt-3">
                                        <p class="mb-2 text-muted">
                                            <i class="fas fa-phone me-1"></i>
                                            <strong>Need help?</strong> Call us:
                                            <a href="tel:+8801779542054" class="text-success fw-bold">+880 1779 542
                                                054</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
    @if (!$licenseStatus['valid'])
        {{-- License inactive: show only the banner above --}}
    @else
        <!-- Date Filter Section -->
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="date-filter-card">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                            <h6 class="mb-0 me-3 fw-bold">Dashboard Analytics</h6>
                            <span class="badge bg-light text-dark" id="current-date-range">
                                {{ $selectedDateRange ? ucfirst(str_replace('_', ' ', $selectedDateRange)) : 'Last 30 Days' }}
                            </span>
                        </div>

                        <div class="date-filter-controls d-flex flex-wrap align-items-center gap-2">
                            <!-- Quick Date Range Buttons -->
                            <div class="btn-group me-3" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm date-range-btn"
                                    data-range="today">Today</button>
                                <button type="button" class="btn btn-outline-primary btn-sm date-range-btn"
                                    data-range="yesterday">Yesterday</button>
                                <button type="button" class="btn btn-outline-primary btn-sm date-range-btn"
                                    data-range="last_7_days">7 Days</button>
                                <button type="button" class="btn btn-outline-primary btn-sm date-range-btn"
                                    data-range="last_15_days">15 Days</button>
                                <button type="button" class="btn btn-outline-primary btn-sm date-range-btn active"
                                    data-range="last_30_days">30 Days</button>
                            </div>

                            <!-- Dropdown for More Options -->
                            <div class="dropdown me-3">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-filter me-1"></i> More Filters
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item date-range-btn" href="#"
                                            data-range="this_week">This Week</a></li>
                                    <li><a class="dropdown-item date-range-btn" href="#"
                                            data-range="this_month">This Month</a></li>
                                    <li><a class="dropdown-item date-range-btn" href="#"
                                            data-range="last_month">Last Month</a></li>
                                    <li><a class="dropdown-item date-range-btn" href="#"
                                            data-range="this_year">This Year</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#customDateModal">Custom Date Range</a></li>
                                </ul>
                            </div>

                            <!-- Refresh Button -->
                            <button type="button" class="btn btn-success btn-sm" id="refresh-dashboard">
                                <i class="fas fa-sync-alt me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Date Range Modal -->
    <div class="modal fade" id="customDateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Custom Date Range</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="custom-start-date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="custom-start-date"
                                value="{{ $customStartDate }}">
                        </div>
                        <div class="col-md-6">
                            <label for="custom-end-date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="custom-end-date"
                                value="{{ $customEndDate }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="apply-custom-date">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="dashboard-loading" class="dashboard-loading" style="display: none;">
        <div class="loading-content">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Updating dashboard...</p>
        </div>
    </div>

    <div class="sm-chart-sec mb-2">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-6 my-2">
                    <a class="glowcard glowcard1" href="{{ url('admin/orders') }}">
                        <div class="glowcard-content">
                            <span class="icon"><span class="fas fa-chart-simple"></span></span>
                            <div>
                                <div class="label">New Orders</div>
                                <div class="count">
                                    <span class="new-orders-count">{{ $NewtotalOrders }}</span>
                                    <span class="currentupdown">
                                        <i class="fa-solid fa-arrow-up"></i>
                                        <span class="currentupdownvalue">Total Orders <span
                                                class="total-orders-count">{{ $totalOrders }}</span></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-6 my-2">
                    <div class="glowcard glowcard2">
                        <div class="glowcard-content">
                            <span class="icon"><span class="fas fa-book fs-4"></span></span></span>
                            <div>
                                <div class="label">SMS Balance</div>
                                <div class="count">
                                    {{ $balance['balance'] ?? '0.00' }}
                                    {{-- <span class="currentupdown">
                                        <i class="fa-solid fa-arrow-up"></i>
                                        <span class="currentupdownvalue">+{{ 33 }}%</span>
                                    </span> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-6 my-2">
                    <a class="glowcard glowcard3" href="{{ route('admin.users') }}">
                        <div class="glowcard-content">
                            <span class="icon"><span class="fas fa-user"></span></span>
                            <div>
                                <div class="label">Customers</div>
                                <div class="count">
                                    {{ $totalUsers }}
                                    {{-- <span class="currentupdown" style="color: red;">
                                        <i class="fa-solid fa-arrow-down"></i>
                                        <span class="currentupdownvalue">-{{ 10 }}%</span>
                                    </span> --}}
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-6 my-2">
                    <div class="glowcard glowcard4">
                        <div class="glowcard-content">
                            <span class="icon"><span class="fa-solid fa-chart-simple"></span></span>
                            <div>
                                <div class="label">Total Sale</div>
                                <div class="count">
                                    {{ $totalSales }}
                                    {{-- <span class="currentupdown">
                                        <i class="fa-solid fa-arrow-up"></i>
                                        <span class="currentupdownvalue">+{{ 133 }}%</span>
                                    </span> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <style>
        /* Date Filter Styles */
        .btn-group.me-3 button {
            margin-right: 10px;
        }

        .date-filter-card {
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            border: 1px solid #e3e6f0;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 7px;
        }

        .date-range-btn {
            transition: all 0.3s ease;
            border-radius: 20px !important;
            font-weight: 500;
            font-size: 12px;
        }

        .date-range-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .date-range-btn.active {
            background-color: #197A94 !important;
            border-color: #197A94 !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .dashboard-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loading-content {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .loading-content p {
            margin: 0;
            color: #6c757d;
            font-weight: 500;
        }

        #current-date-range {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 12px;
        }

        .btn-group .btn {
            border-radius: 20px !important;
        }

        .btn-group .btn:first-child {
            border-top-left-radius: 20px !important;
            border-bottom-left-radius: 20px !important;
        }

        .btn-group .btn:last-child {
            border-top-right-radius: 20px !important;
            border-bottom-right-radius: 20px !important;
        }

        .dropdown-menu {
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: none;
        }

        .dropdown-item {
            padding: 8px 16px;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 2px 8px;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #197A94, #0056b3);
            color: white;
            transform: translateX(4px);
        }

        @media (max-width: 768px) {
            .date-filter-controls {
                width: 100%;
                justify-content: center;
            }

            .btn-group {
                flex-wrap: wrap;
                margin-bottom: 10px;
            }
        }

        .stats-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }

        .dashboard-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header p {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 0;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--card-accent-color);
            border-radius: 16px 16px 0 0;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            color: white;
            font-size: 24px;
            background: var(--icon-gradient);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 6px;
            line-height: 1;
        }

        .stat-label {
            font-size: 14px;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .stat-trend {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            position: absolute;
            top: 20px;
            right: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .trend-positive {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
        }

        .trend-negative {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        .trend-neutral {
            background: linear-gradient(135deg, #e2e3e5, #d6d8db);
            color: #495057;
        }

        .stat-chart {
            height: 50px;
            display: flex;
            align-items: end;
            gap: 3px;
            margin-top: 16px;
            padding: 0 4px;
        }

        .chart-bar {
            flex: 1;
            border-radius: 4px 4px 0 0;
            min-height: 8px;
            transition: all 0.3s ease;
            position: relative;
            background: var(--chart-gradient);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover .chart-bar {
            transform: scaleY(1.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .trend-summary {
            font-size: 11px;
            margin-top: 12px;
            padding: 8px;
            background: rgba(0, 0, 0, 0.02);
            border-radius: 8px;
            border-left: 3px solid var(--card-accent-color);
        }

        .trend-summary span {
            margin-right: 8px;
            display: inline-block;
            margin-bottom: 2px;
        }

        .trend-summary b {
            color: #495057;
        }

        /* Status-specific color schemes */
        .status-pending {
            --card-accent-color: #ffc107;
            --icon-gradient: linear-gradient(135deg, #ffc107, #ffb300);
            --chart-gradient: linear-gradient(135deg, #ffc107, #ffb300);
        }

        .status-phone-not-rcv {
            --card-accent-color: #ff9800;
            --icon-gradient: linear-gradient(135deg, #ff9800, #f57c00);
            --chart-gradient: linear-gradient(135deg, #ff9800, #f57c00);
        }

        .status-follow-up {
            --card-accent-color: #2196f3;
            --icon-gradient: linear-gradient(135deg, #2196f3, #1976d2);
            --chart-gradient: linear-gradient(135deg, #2196f3, #1976d2);
        }

        .status-processing {
            --card-accent-color: #9c27b0;
            --icon-gradient: linear-gradient(135deg, #9c27b0, #7b1fa2);
            --chart-gradient: linear-gradient(135deg, #9c27b0, #7b1fa2);
        }

        .status-ready-for-delivery {
            --card-accent-color: #4caf50;
            --icon-gradient: linear-gradient(135deg, #4caf50, #388e3c);
            --chart-gradient: linear-gradient(135deg, #4caf50, #388e3c);
        }

        .status-delivered {
            --card-accent-color: #28a745;
            --icon-gradient: linear-gradient(135deg, #28a745, #1e7e34);
            --chart-gradient: linear-gradient(135deg, #28a745, #1e7e34);
        }

        .status-shipped {
            --card-accent-color: #17a2b8;
            --icon-gradient: linear-gradient(135deg, #17a2b8, #138496);
            --chart-gradient: linear-gradient(135deg, #17a2b8, #138496);
        }

        .status-on-hold {
            --card-accent-color: #6c757d;
            --icon-gradient: linear-gradient(135deg, #6c757d, #5a6268);
            --chart-gradient: linear-gradient(135deg, #6c757d, #5a6268);
        }

        .status-cancelled {
            --card-accent-color: #dc3545;
            --icon-gradient: linear-gradient(135deg, #dc3545, #c82333);
            --chart-gradient: linear-gradient(135deg, #dc3545, #c82333);
        }

        /* Custom grid for responsive layout */
        .col-7-grid {
            flex: 0 0 auto;
            max-width: 20%;
            padding: 0 8px;
            margin-bottom: 20px;
        }

        @media (max-width: 1200px) {
            .col-7-grid {
                flex: 0 0 25%;
                max-width: 25%;
            }
        }

        @media (max-width: 992px) {
            .col-7-grid {
                flex: 0 0 33.333%;
                max-width: 33.333%;
            }
        }

        @media (max-width: 768px) {
            .col-7-grid {
                flex: 0 0 49%;
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .col-7-grid {
                flex: 0 0 49%;
                max-width: 100%;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .stat-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .stat-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .stat-card:nth-child(6) {
            animation-delay: 0.6s;
        }

        .stat-card:nth-child(7) {
            animation-delay: 0.7s;
        }

        .stat-card:nth-child(8) {
            animation-delay: 0.8s;
        }

        .stat-card:nth-child(9) {
            animation-delay: 0.9s;
        }
    </style>
    <div class="container-fluid mb-0">
        <div class="stats-container">
            <div class="row">
                @foreach ($statuses as $status)
                    @php
                        $counts = $orderStatusMonthlyCounts[$status];
                        $trends = $orderStatusMonthlyTrends[$status];
                        $currentTrend = $trends[count($trends) - 1] ?? 0;
                        $currentCount = $counts[count($counts) - 1] ?? 0;
                        $maxCount = max($counts) ?: 1;

                        // Define status labels and metadata
                        $statusConfig = [
                            'pending' => ['label' => 'Pending', 'icon' => 'fas fa-clock', 'isNegative' => false],
                            'phone_not_rcv' => [
                                'label' => 'Call Not Received',
                                'icon' => 'fas fa-phone-slash',
                                'isNegative' => true,
                            ],
                            'follow_up' => ['label' => 'Follow Up', 'icon' => 'fas fa-redo-alt', 'isNegative' => false],
                            'processing' => ['label' => 'Processing', 'icon' => 'fas fa-cogs', 'isNegative' => false],
                            'ready_for_delivery' => [
                                'label' => 'Ready For Delivery',
                                'icon' => 'fas fa-box-open',
                                'isNegative' => false,
                            ],
                            'delivered' => [
                                'label' => 'Delivered',
                                'icon' => 'fas fa-check-circle',
                                'isNegative' => false,
                            ],
                            'on_hold' => ['label' => 'On Hold', 'icon' => 'fas fa-pause-circle', 'isNegative' => true],
                            'shipped' => [
                                'label' => 'Shipped',
                                'icon' => 'fas fa-shipping-fast',
                                'isNegative' => false,
                            ],
                            'cancelled' => [
                                'label' => 'Cancelled',
                                'icon' => 'fas fa-times-circle',
                                'isNegative' => true,
                            ],
                        ];

                        $config = $statusConfig[$status] ?? [
                            'label' => ucfirst($status),
                            'icon' => 'fas fa-circle',
                            'isNegative' => false,
                        ];
                        $isNegativeStatus = $config['isNegative'];

                        // Calculate trend class based on status type
                        $getTrendClass = function ($trend, $isNegative) {
                            if ($trend == 0) {
                                return 'trend-neutral';
                            }
                            if ($isNegative) {
                                return $trend > 0 ? 'trend-negative' : 'trend-positive';
                            } else {
                                return $trend > 0 ? 'trend-positive' : 'trend-negative';
                            }
                        };

                        $trendClass = $getTrendClass($currentTrend, $isNegativeStatus);
                        $statusClass = 'status-' . str_replace('_', '-', $status);
                    @endphp

                    <div class="col-7-grid">
                        <div class="stat-card {{ $statusClass }}">
                            <div class="stat-trend {{ $trendClass }}">
                                {{ $currentTrend >= 0 ? '+' : '' }}{{ $currentTrend }}%
                            </div>

                            <div class="stat-icon">
                                <i class="{{ $config['icon'] }}"></i>
                            </div>

                            <div class="stat-value">
                                {{ $currentCount }}
                            </div>

                            <div class="stat-label">
                                {{ $config['label'] }} Orders ({{ $monthLabels[count($monthLabels) - 1] }})
                            </div>

                            <div class="stat-chart">
                                @foreach ($counts as $i => $count)
                                    @php
                                        $height = ($count / $maxCount) * 42;
                                        $monthLabel = $monthLabels[$i] ?? '';
                                        $trend = $trends[$i - 1] ?? 0;
                                    @endphp
                                    <div class="chart-bar" style="height: {{ $height }}px;"
                                        title="{{ $monthLabel }}: {{ $count }} orders ({{ $trend >= 0 ? '+' : '' }}{{ $trend }}%)">
                                    </div>
                                @endforeach
                            </div>

                            <div class="trend-summary">
                                @foreach ($trends as $i => $trend)
                                    @php
                                        $trendColor = $getTrendClass($trend, $isNegativeStatus);
                                        $color =
                                            $trendColor === 'trend-positive'
                                                ? 'green'
                                                : ($trendColor === 'trend-negative'
                                                    ? 'red'
                                                    : '#6c757d');
                                    @endphp
                                    <span>
                                        <b>{{ $monthLabels[$i + 1] ?? '' }}</b>:
                                        <span style="color: {{ $color }}">
                                            {{ $trend >= 0 ? '+' : '' }}{{ $trend }}%
                                        </span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- <div class="container-fluid mb-2">
        <h5 class="mb-3">Admin Panel Statistics</h5>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Page Views</h5>
                        <canvas id="totalUsersChart" width="200" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <canvas id="totalOrdersChart" width="200" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Revenue</h5>
                        <canvas id="totalRevenueChart" width="200" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Date Filter JavaScript -->
    <script>
        // Global variables for charts
        let totalOrdersChart = null;
        let currentDateRange = '{{ $selectedDateRange ?? 'last_30_days' }}';

        // Initialize dashboard
        $(document).ready(function() {
            initializeDateFilters();
            initializeCharts();
        });

        function initializeDateFilters() {
            // Set active button based on current selection
            $('.date-range-btn').removeClass('active');
            $(`.date-range-btn[data-range="${currentDateRange}"]`).addClass('active');

            // Handle date range button clicks
            $('.date-range-btn').on('click', function(e) {
                e.preventDefault();
                const range = $(this).data('range');
                if (range !== currentDateRange) {
                    updateDashboard(range);
                }
            });

            // Handle custom date range
            $('#apply-custom-date').on('click', function() {
                const startDate = $('#custom-start-date').val();
                const endDate = $('#custom-end-date').val();

                if (!startDate || !endDate) {
                    alert('Please select both start and end dates.');
                    return;
                }

                if (new Date(startDate) > new Date(endDate)) {
                    alert('Start date cannot be later than end date.');
                    return;
                }

                updateDashboard('custom', startDate, endDate);
                $('#customDateModal').modal('hide');
            });

            // Handle refresh button
            $('#refresh-dashboard').on('click', function() {
                updateDashboard(currentDateRange);
            });
        }

        function updateDashboard(dateRange, startDate = null, endDate = null) {
            // Show loading overlay
            $('#dashboard-loading').show();

            // Update active button
            $('.date-range-btn').removeClass('active');
            if (dateRange !== 'custom') {
                $(`.date-range-btn[data-range="${dateRange}"]`).addClass('active');
            }

            // Prepare AJAX data
            const ajaxData = {
                date_range: dateRange,
                _token: '{{ csrf_token() }}'
            };

            if (dateRange === 'custom' && startDate && endDate) {
                ajaxData.start_date = startDate;
                ajaxData.end_date = endDate;
            }

            // Make AJAX request
            $.ajax({
                url: '{{ route('admin.dashboard') }}',
                type: 'GET',
                data: ajaxData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    updateDashboardContent(response);
                    currentDateRange = dateRange;

                    // Hide loading overlay
                    $('#dashboard-loading').fadeOut(300);
                },
                error: function(xhr, status, error) {
                    console.error('Error updating dashboard:', error);
                    alert('Error updating dashboard. Please try again.');
                    $('#dashboard-loading').fadeOut(300);
                }
            });
        }

        function updateDashboardContent(data) {
            // Update statistics cards
            updateStatisticsCards(data);

            // Update date range label
            $('#current-date-range').text(data.dateRange.label);

            // Update charts
            updateCharts(data);

            // Update status cards
            updateStatusCards(data);
        }

        function updateStatisticsCards(data) {
            // Update main statistics
            // Update the New Orders count
            $('.glowcard1 .new-orders-count').text(data.NewtotalOrders);

            // Update the Total Orders count
            $('.glowcard1 .total-orders-count').text(data.totalOrders);

            // Update other cards
            $('.glowcard3 .count').text(data.totalUsers);
            $('.glowcard4 .count').text(data.totalSales);
        }

        function updateCharts(data) {
            // Update total orders chart if it exists
            if (totalOrdersChart) {
                totalOrdersChart.data.labels = data.labels;
                totalOrdersChart.data.datasets[0].data = data.data;
                totalOrdersChart.update('active');
            }
        }

        function updateStatusCards(data) {
            // Update status cards with new data
            const statuses = ['pending', 'phone_not_rcv', 'follow_up', 'processing', 'ready_for_delivery', 'delivered',
                'on_hold', 'shipped', 'cancelled'
            ];

            statuses.forEach((status, index) => {
                const statusCard = $(`.status-${status.replace('_', '-')}`);
                if (statusCard.length) {
                    const counts = data.orderStatusMonthlyCounts[status] || [];
                    const trends = data.orderStatusMonthlyTrends[status] || [];
                    const currentCount = counts[counts.length - 1] || 0;
                    const currentTrend = trends[trends.length - 1] || 0;

                    // Update count
                    statusCard.find('.stat-value').text(currentCount);

                    // Update trend
                    const trendElement = statusCard.find('.stat-trend');
                    trendElement.text(`${currentTrend >= 0 ? '+' : ''}${currentTrend}%`);

                    // Update chart bars
                    const maxCount = Math.max(...counts) || 1;
                    statusCard.find('.chart-bar').each(function(i) {
                        if (i < counts.length) {
                            const height = (counts[i] / maxCount) * 42;
                            $(this).css('height', `${height}px`);

                            // Update tooltip
                            const monthLabel = data.monthLabels[i] || '';
                            const trend = trends[i - 1] || 0;
                            $(this).attr('title',
                                `${monthLabel}: ${counts[i]} orders (${trend >= 0 ? '+' : ''}${trend}%)`
                                );
                        }
                    });

                    // Update trend summary
                    let trendSummaryHtml = '';
                    trends.forEach((trend, i) => {
                        const monthLabel = data.monthLabels[i + 1] || '';
                        const color = trend > 0 ? 'green' : (trend < 0 ? 'red' : '#6c757d');
                        trendSummaryHtml +=
                            `<span><b>${monthLabel}</b>: <span style="color: ${color}">${trend >= 0 ? '+' : ''}${trend}%</span></span>`;
                    });
                    statusCard.find('.trend-summary').html(trendSummaryHtml);
                }
            });
        }

        function initializeCharts() {
            // Initialize charts with current data
            const labels = @json($labels ?? []);
            const data = @json($data ?? []);

            // Total Orders Chart
            if (document.getElementById('totalOrdersChart')) {
                totalOrdersChart = new Chart(document.getElementById('totalOrdersChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Orders',
                            data: data,
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        animation: {
                            duration: 1000,
                            easing: 'easeInOutQuart'
                        }
                    }
                });
            }
        }
    </script>

        document.addEventListener("DOMContentLoaded", function() {
            // Simulated data (replace with actual data fetching logic)
            const totalUsersData = [100, 150, 200, 250, 300, 350, 400];
            const totalOrdersData = [50, 75, 100, 125, 150, 175, 200];
            const totalRevenueData = [5000, 7500, 10000, 12500, 15000, 17500, 20000];

            // Total Users Chart
            const totalUsersCanvas = document.getElementById('totalUsersChart');
            if (totalUsersCanvas) {
                var totalUsersChart = new Chart(totalUsersCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                        datasets: [{
                            label: 'Total Users',
                            data: totalUsersData,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }

            // Data passed from Laravel controller to JavaScript
            var labels = @json($labels); // Labels for the months
            var data = @json($data); // Total orders data for each month

            // Total Orders Chart
            const totalOrdersCanvas = document.getElementById('totalOrdersChart');
            if (totalOrdersCanvas) {
                var totalOrdersChart = new Chart(totalOrdersCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels, // dynamically generated labels from the controller
                        datasets: [{
                            label: 'Total Orders',
                            data: data, // dynamically generated data from the controller
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            borderColor: 'rgba(255, 159, 64, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Total Revenue Chart
            const totalRevenueCanvas = document.getElementById('totalRevenueChart');
            if (totalRevenueCanvas) {
                var totalRevenueChart = new Chart(totalRevenueCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                        datasets: [{
                            label: 'Total Revenue',
                            data: totalRevenueData,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        }
                    }
                });
            }
        });

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#Products').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'pdf', 'csv', 'excel', 'print'
                ]
            });
        });
    </script>

    <style>
        /* License Alert Styling */
        .alert {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .alert-danger {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
            border-left: 4px solid #e53e3e;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-left: 4px solid #d97706;
        }

        .alert-info {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-left: 4px solid #2563eb;
        }

        .alert-success {
            background: linear-gradient(135deg, #f0fff4 0%, #dcfce7 100%);
            border-left: 4px solid #16a34a;
        }

        .alert-heading {
            color: #1a202c;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .alert .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 6px 16px;
            transition: all 0.2s ease;
        }

        .alert .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 1rem;
        }

        /* Help Contact Section Styling */
        .alert-primary {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-left: 4px solid #3b82f6;
        }

        .btn-lg {
            padding: 12px 24px;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .text-muted {
            color: #6b7280 !important;
            font-size: 0.9rem;
        }
    </style>



    <style>
        @media (max-width: 768px) {
            button.btn.btn-outline-primary.btn-sm.date-range-btn.active {
                margin-top: 10px;
            }
        }
    </style>
    @endif
@endsection
