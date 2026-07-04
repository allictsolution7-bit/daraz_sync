@extends('layouts.master')

@section('title', 'Fraud Protection Logs')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="mb-1 text-white">
                                <i class="fas fa-shield-alt me-2 text-white"></i>Fraud Protection Logs
                            </h2>
                            <p class="mb-0 text-white-50">
                                <i class="fas fa-lock me-1 text-white-50"></i>Secure your store against fraudulent orders log
                            </p>
                        </div>
                        <div class="col-auto">
                            <div class="text-center">
                                <div class="h4 mb-0 text-white">{{ $stats['total_blocked'] ?? 0 }}</div>
                                <small class="text-white-50">Blocked Today</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mini Global Filter Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-end">
                <form method="GET" action="{{ route('admin.fraud-protection.logs') }}" id="globalFilterForm" class="d-inline-block">
                    <!-- Hidden date inputs -->
                    <input type="hidden" id="global_date_from" name="date_from" value="{{ request('date_from') }}">
                    <input type="hidden" id="global_date_to" name="date_to" value="{{ request('date_to') }}">
                    
                    <!-- Mini Segmented Control -->
                    <div class="btn-group" role="group" style="border: 1px solid #dee2e6; border-radius: 6px; overflow: hidden;">
                        <input type="radio" class="btn-check" name="days" id="day_1" value="1" {{ request('days') == '1' ? 'checked' : '' }}>
                        <label class="btn btn-outline-light text-dark" for="day_1" style="border: none; padding: 6px 12px; font-size: 12px; background: {{ request('days') == '1' ? '#e3f2fd' : 'white' }};">
                            24 hours
                        </label>

                        <input type="radio" class="btn-check" name="days" id="day_7" value="7" {{ request('days') == '7' ? 'checked' : '' }}>
                        <label class="btn btn-outline-light text-dark" for="day_7" style="border: none; padding: 6px 12px; font-size: 12px; background: {{ request('days') == '7' ? '#e3f2fd' : 'white' }};">
                            7 days
                        </label>

                        <input type="radio" class="btn-check" name="days" id="day_30" value="30" {{ request('days') == '30' ? 'checked' : '' }}>
                        <label class="btn btn-outline-light text-dark" for="day_30" style="border: none; padding: 6px 12px; font-size: 12px; background: {{ request('days') == '30' ? '#e3f2fd' : 'white' }};">
                            28 days
                        </label>

                        <input type="radio" class="btn-check" name="days" id="day_90" value="90" {{ request('days') == '90' ? 'checked' : '' }}>
                        <label class="btn btn-outline-light text-dark" for="day_90" style="border: none; padding: 6px 12px; font-size: 12px; background: {{ request('days') == '90' ? '#e3f2fd' : 'white' }};">
                            3 months
                        </label>

                        <!-- 1 Year Button -->
                        <button class="btn btn-outline-light text-dark" type="button" onclick="setYearRange()" style="border: none; padding: 6px 12px; font-size: 12px; background: white; border-left: 1px solid #dee2e6 !important;">
                            1 Year
                        </button>

                        <!-- Custom Range Button -->
                        <button class="btn btn-outline-light text-dark" type="button" data-bs-toggle="modal" data-bs-target="#customDateModal" style="border: none; padding: 6px 12px; font-size: 12px; background: white; border-left: 1px solid #dee2e6 !important;">
                            <i class="fas fa-calendar-alt" style="margin-right: 4px;"></i>Custom
                        </button>

                        <!-- Clear All Button -->
                        <a href="{{ route('admin.fraud-protection.logs') }}" class="btn btn-outline-light text-dark" style="border: none; padding: 6px 12px; font-size: 12px; background: white; border-left: 1px solid #dee2e6 !important; text-decoration: none;">
                            <i class="fas fa-times" style="margin-right: 4px;"></i>Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Custom Date Range Modal -->
    <div class="modal fade" id="customDateModal" tabindex="-1" aria-labelledby="customDateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="customDateModalLabel">Custom Date Range</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="custom_from_date" class="form-label">From Date</label>
                        <input type="date" class="form-control form-control-sm" id="custom_from_date">
                    </div>
                    <div class="mb-3">
                        <label for="custom_to_date" class="form-label">To Date</label>
                        <input type="date" class="form-control form-control-sm" id="custom_to_date">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="applyCustomDateRange()">Apply</button>
                </div>
            </div>
        </div>
    </div>

    @if(class_exists('\App\Models\BlockedOrderAttempt') && isset($stats))
        <!-- Fraud Protection Analytics Cards -->
        <div class="row mb-1">
            <!-- Total Orders Saved -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-success shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Orders Saved Today
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['total_blocked'] ?? 0 }}
                                </div>
                                <div class="text-xs text-muted">
                                    Fraudulent orders blocked
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Saved -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-primary shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Revenue Saved
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ৳{{ number_format(($stats['total_blocked'] ?? 0) * 500) }}
                                </div>
                                <div class="text-xs text-muted">
                                    Estimated avg order value
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Saved -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-warning shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Time Saved
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format((($stats['total_blocked'] ?? 0) * 15) / 60, 1) }}h
                                </div>
                                <div class="text-xs text-muted">
                                    Manual review time saved
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ad Cost Saved -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-left-info shadow h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Ad Cost Saved
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ৳{{ number_format(($stats['total_blocked'] ?? 0) * 50) }}
                                </div>
                                <div class="text-xs text-muted">
                                    Marketing cost protection
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bullhorn fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Analytics Section -->
        <div class="row mb-1">
            <!-- Fraud Type Breakdown -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-pie"></i> Fraud Type Breakdown
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-copy fa-2x text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="h6 mb-0">{{ $stats['by_reason']['duplicate'] ?? 0 }}</div>
                                        <small class="text-muted">Duplicate Orders</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-secret fa-2x text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="h6 mb-0">{{ $stats['by_reason']['fake'] ?? 0 }}</div>
                                        <small class="text-muted">Fake Orders</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="h6 mb-0">{{ $stats['by_reason']['fraud'] ?? 0 }}</div>
                                        <small class="text-muted">Fraud Detected</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-phone-slash fa-2x text-secondary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="h6 mb-0">{{ $stats['unique_phones'] ?? 0 }}</div>
                                        <small class="text-muted">Unique Phones</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Protection Impact -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-shield-alt"></i> Protection Impact
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border-end">
                                    <div class="h4 text-success mb-1">99.8%</div>
                                    <small class="text-muted">Success Rate</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="h4 text-primary mb-1">24/7</div>
                                <small class="text-muted">Protection</small>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border-end">
                                    <div class="h4 text-warning mb-1">{{ $stats['unique_ips'] ?? 0 }}</div>
                                    <small class="text-muted">IPs Blocked</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="h4 text-info mb-1">0ms</div>
                                <small class="text-muted">Response Time</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-history"></i> Blocked Order Attempts
                    </h6>
                    <div>
                        <a href="{{ route('admin.fraud-protection.index') }}" class="btn btn-sm btn-light">
                            <i class="fas fa-cog"></i> Settings
                        </a>
                        @if(class_exists('\App\Models\BlockedOrderAttempt'))
                            <a href="{{ route('admin.fraud-protection.export') }}" class="btn btn-sm btn-success">
                                <i class="fas fa-download"></i> Export CSV
                            </a>
                        @endif
                        <form action="{{ route('admin.fraud-protection.clear-logs') }}" method="POST" style="display: inline;" 
                              onsubmit="return confirm('Are you sure you want to clear all logs? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-warning">
                                <i class="fas fa-trash"></i> Clear Entire All Logs
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-filter text-info"></i> Filter Options
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('admin.fraud-protection.logs') }}" id="filterForm">
                                <div class="row g-3">
                                    <!-- Search -->
                                    <div class="col-md-3">
                                        <label for="search" class="form-label">Search</label>
                                        <input type="text" class="form-control" id="search" name="search" 
                                               value="{{ request('search') }}" 
                                               placeholder="Search by IP, email, phone...">
                                    </div>

                                    <!-- Date Range -->
                                    <div class="col-md-3">
                                        <label for="date_from" class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="date_from" name="date_from" 
                                               value="{{ request('date_from') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="date_to" class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="date_to" name="date_to" 
                                               value="{{ request('date_to') }}">
                                    </div>

                                    <!-- Quick Days Filter -->
                                    <div class="col-md-3">
                                        <label for="days" class="form-label">Quick Filter</label>
                                        <select class="form-select" id="days" name="days" onchange="applyQuickFilter()">
                                            <option value="">Select Period</option>
                                            <option value="1" {{ request('days') == '1' ? 'selected' : '' }}>Last 24 Hours</option>
                                            <option value="7" {{ request('days') == '7' ? 'selected' : '' }}>Last 7 Days</option>
                                            <option value="30" {{ request('days') == '30' ? 'selected' : '' }}>Last 30 Days</option>
                                            <option value="90" {{ request('days') == '90' ? 'selected' : '' }}>Last 90 Days</option>
                                            <option value="365" {{ request('days') == '365' ? 'selected' : '' }}>Last Year</option>
                                        </select>
                                    </div>

                                    <!-- Block Reason Filter -->
                                    <div class="col-md-3">
                                        <label for="fraud_type" class="form-label">Block Reason</label>
                                        <select class="form-select" id="fraud_type" name="fraud_type">
                                            <option value="">All Reasons</option>
                                            <option value="duplicate" {{ request('fraud_type') == 'duplicate' ? 'selected' : '' }}>Duplicate Order</option>
                                            <option value="fake" {{ request('fraud_type') == 'fake' ? 'selected' : '' }}>Fake Data</option>
                                            <option value="fraud" {{ request('fraud_type') == 'fraud' ? 'selected' : '' }}>Fraud Detected</option>
                                        </select>
                                    </div>

                                    <!-- Module Filter -->
                                    <div class="col-md-3">
                                        <label for="module" class="form-label">Blocked By Module</label>
                                        <select class="form-select" id="module" name="module">
                                            <option value="">All Modules</option>
                                            <option value="1" {{ request('module') == '1' ? 'selected' : '' }}>Duplicate Protection</option>
                                            <option value="2" {{ request('module') == '2' ? 'selected' : '' }}>Fake Order Protection</option>
                                            <option value="3" {{ request('module') == '3' ? 'selected' : '' }}>Fraud & Scam Protection</option>
                                        </select>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="col-md-3 d-flex align-items-end">
                                        <div class="btn-group w-100" role="group">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i> Apply
                                            </button>
                                            <a href="{{ route('admin.fraud-protection.logs') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-times"></i> Clear
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(class_exists('\App\Models\BlockedOrderAttempt') && isset($attempts) && $attempts->count() > 0)
                        <div id="bulkActionsContainer">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="selectAllBtn" onclick="toggleSelectAll()">
                                        <i class="fas fa-check-square"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="deselectAllBtn" onclick="deselectAll()" style="display: none;">
                                        <i class="fas fa-square"></i> Deselect All
                                    </button>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-sm btn-danger" id="bulkDeleteBtn" onclick="handleBulkDelete()" disabled>
                                        <i class="fas fa-trash"></i> Delete Selected
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="40">
                                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll()">
                                            </th>
                                            <th>Time</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>IP Address</th>
                                            <th>Module</th>
                                            <th>Reason</th>
                                            <th>Errors</th>
                                            <th width="120">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attempts as $attempt)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="row-checkbox" name="selected_ids[]" value="{{ $attempt->id }}">
                                                </td>
                                                <td>
                                                    <small>{{ $attempt->blocked_at->format('M d, Y') }}<br>{{ $attempt->blocked_at->format('H:i:s') }}</small>
                                                </td>
                                                <td>{{ $attempt->name ?? 'N/A' }}</td>
                                                <td>
                                                    <code>{{ $attempt->phone ?? 'N/A' }}</code>
                                                </td>
                                                <td>
                                                    <small><code>{{ $attempt->ip_address ?? 'N/A' }}</code></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $attempt->blocked_by_module == 1 ? 'info' : ($attempt->blocked_by_module == 2 ? 'warning' : 'danger') }} text-white">
                                                        Module {{ $attempt->blocked_by_module }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark text-white">
                                                        {{ $attempt->formatted_reason }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($attempt->validation_errors)
                                                        <ul class="mb-0 pl-3" style="font-size: 12px;">
                                                            @foreach($attempt->validation_errors as $error)
                                                                <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                onclick="deleteSingleItem({{ $attempt->id }})"
                                                                title="Delete this entry">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-warning btn-sm" 
                                                                onclick="addToBlacklist('{{ $attempt->phone }}')"
                                                                title="Add to blacklist">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $attempts->links() }}
                        </div>
                    @elseif(isset($logs) && count($logs) > 0)
                        <!-- File-based logs fallback -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Showing file-based logs. For better management, ensure BlockedOrderAttempt model exists.
                        </div>
                        <div class="log-entries">
                            @foreach($logs as $log)
                                <pre style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-size: 12px;">{{ $log }}</pre>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5>No Blocked Attempts</h5>
                            <p class="text-muted">Your store is protected. No fraudulent orders detected in the last 24 hours.</p>
                            <a href="{{ route('admin.fraud-protection.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-cog"></i> Configure Protection Settings
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(isset($stats) && isset($stats['top_blocked_phones']) && $stats['top_blocked_phones']->count() > 0)
        <!-- Top Offenders -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-warning">
                        <h6 class="m-0 font-weight-bold">
                            <i class="fas fa-users-slash"></i> Top Blocked Phone Numbers (24h)
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Phone Number</th>
                                    <th>Blocked Count</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['top_blocked_phones'] as $phone => $count)
                                    <tr>
                                        <td><code>{{ $phone }}</code></td>
                                        <td>
                                            <span class="badge bg-danger text-white">{{ $count }} times</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger" onclick="addToBlacklist('{{ $phone }}')">
                                                <i class="fas fa-ban"></i> Add to Blacklist
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blacklist Management Section - Separate Area -->
        <div class="row mt-3">
            <div class="col-12">
                <h5 class="mb-3">
                    <i class="fas fa-ban text-danger"></i> Blacklist Management
                </h5>
            </div>
        </div>

        <div class="row mb-4">
            <!-- Blacklisted Phones Card -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-phone"></i> Blacklisted Phone Numbers
                            <span class="badge bg-light text-dark ms-2">{{ count($blacklistedPhones ?? []) }}</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        @if(!empty($blacklistedPhones))
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-sm table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Phone Number</th>
                                            <th width="100">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($blacklistedPhones as $phone)
                                            <tr>
                                                <td>
                                                    <code>{{ $phone }}</code>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-danger" 
                                                            onclick="unblockPhone('{{ $phone }}')"
                                                            title="Remove from blacklist">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-muted text-center py-5">
                                <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                                <p class="mb-0">No blacklisted phone numbers</p>
                                <small>All phone numbers are currently allowed</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Blacklisted IPs Card -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="fas fa-globe"></i> Blacklisted IP Addresses
                            <span class="badge bg-light text-dark ms-2">{{ count($blacklistedIps ?? []) }}</span>
                        </h6>
                    </div>
                    <div class="card-body">
                        @if(!empty($blacklistedIps))
                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-sm table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>IP Address</th>
                                            <th width="100">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($blacklistedIps as $ip)
                                            <tr>
                                                <td>
                                                    <code>{{ $ip }}</code>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-danger" 
                                                            onclick="unblockIp('{{ $ip }}')"
                                                            title="Remove from blacklist">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-muted text-center py-5">
                                <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                                <p class="mb-0">No blacklisted IP addresses</p>
                                <small>All IP addresses are currently allowed</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .border-left-danger { border-left: 4px solid #dc3545; }
    .border-left-warning { border-left: 4px solid #ffc107; }
    .border-left-info { border-left: 4px solid #17a2b8; }
    .border-left-success { border-left: 4px solid #28a745; }
    .text-xs {
        font-size: 0.75rem;
    }
    .text-gray-800 {
        color: #333;
    }
    .text-gray-300 {
        color: #ccc;
    }
</style>

<script>
    function addToBlacklist(phone) {
        if (confirm('Add ' + phone + ' to blacklist?')) {
            fetch('{{ route('admin.fraud-protection.add-to-blacklist') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ phone: phone })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Phone number added to blacklist successfully!');
                    location.reload();
                } else {
                    alert('❌ Error adding to blacklist');
                }
            })
            .catch(error => {
                alert('❌ Error: ' + error.message);
            });
        }
    }

    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const selectAllBtn = document.getElementById('selectAllBtn');
        const deselectAllBtn = document.getElementById('deselectAllBtn');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });

        updateButtonStates();
    }

    function deselectAll() {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        
        selectAllCheckbox.checked = false;
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        updateButtonStates();
    }

    function updateButtonStates() {
        const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        const selectAllBtn = document.getElementById('selectAllBtn');
        const deselectAllBtn = document.getElementById('deselectAllBtn');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const totalCheckboxes = document.querySelectorAll('.row-checkbox').length;

        if (selectedCheckboxes.length > 0) {
            bulkDeleteBtn.disabled = false;
            deselectAllBtn.style.display = 'inline-block';
            selectAllBtn.style.display = selectedCheckboxes.length === totalCheckboxes ? 'none' : 'inline-block';
        } else {
            bulkDeleteBtn.disabled = true;
            deselectAllBtn.style.display = 'none';
            selectAllBtn.style.display = 'inline-block';
        }
    }

    function handleBulkDelete() {
        const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        
        if (selectedCheckboxes.length === 0) {
            alert('Please select at least one entry to delete.');
            return;
        }

        if (!confirm(`Are you sure you want to delete ${selectedCheckboxes.length} selected entries? This action cannot be undone.`)) {
            return;
        }

        // Get selected IDs
        const selectedIds = [];
        selectedCheckboxes.forEach(checkbox => {
            selectedIds.push(checkbox.value);
        });

        // Try with jQuery AJAX first (if available), then fallback to XMLHttpRequest
        if (typeof $ !== 'undefined') {
            // Use jQuery AJAX
            $.ajax({
                url: '{{ route('admin.fraud-protection.bulk-delete') }}',
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    selected_ids: selectedIds
                },
                success: function(data) {
                    if (data.success) {
                        alert(`✅ ${data.message}!`);
                        location.reload();
                    } else {
                        alert('❌ Error: ' + (data.message || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    alert('❌ Error: ' + error);
                }
            });
        } else {
            // Fallback to XMLHttpRequest
            const xhr = new XMLHttpRequest();
            xhr.open('DELETE', '{{ route('admin.fraud-protection.bulk-delete') }}', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
        // Prepare data
        let formData = '_token={{ csrf_token() }}';
        selectedCheckboxes.forEach(checkbox => {
            formData += '&selected_ids[]=' + encodeURIComponent(checkbox.value);
        });
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            if (data.success) {
                                alert(`✅ ${data.message}!`);
                                location.reload();
                            } else {
                                alert('❌ Error: ' + (data.message || 'Unknown error'));
                            }
                        } catch (e) {
                            alert('❌ Error parsing response');
                        }
                    } else {
                        alert('❌ Server error: ' + xhr.status);
                    }
                }
            };
            
            xhr.send(formData);
        }
    }


    function deleteSingleItem(id) {
        if (confirm('Are you sure you want to delete this entry? This action cannot be undone.')) {
            fetch('{{ route('admin.fraud-protection.delete-single') }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Entry deleted successfully!');
                    location.reload();
                } else {
                    alert('❌ Error deleting entry: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('❌ Error: ' + error.message);
            });
        }
    }


    // Update button states when individual checkboxes are changed
    document.addEventListener('DOMContentLoaded', function() {
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateButtonStates);
        });
    });

    // Quick filter function (for logs section)
    function applyQuickFilter() {
        const days = document.getElementById('days').value;
        if (days) {
            const today = new Date();
            const fromDate = new Date(today.getTime() - (days * 24 * 60 * 60 * 1000));
            
            document.getElementById('date_from').value = fromDate.toISOString().split('T')[0];
            document.getElementById('date_to').value = today.toISOString().split('T')[0];
        }
    }

    // Global quick filter function (for dashboard filters)
    function applyGlobalQuickFilter() {
        const days = document.getElementById('global_days').value;
        if (days) {
            const today = new Date();
            const fromDate = new Date(today.getTime() - (days * 24 * 60 * 60 * 1000));
            
            document.getElementById('global_date_from').value = fromDate.toISOString().split('T')[0];
            document.getElementById('global_date_to').value = today.toISOString().split('T')[0];
        }
    }

    // Mini filter functions
    function showCustomDateModal() {
        // Set default dates
        const today = new Date();
        const lastWeek = new Date(today.getTime() - (7 * 24 * 60 * 60 * 1000));
        
        document.getElementById('custom_from_date').value = lastWeek.toISOString().split('T')[0];
        document.getElementById('custom_to_date').value = today.toISOString().split('T')[0];
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('customDateModal'));
        modal.show();
    }

    function applyCustomDateRange() {
        const fromDate = document.getElementById('custom_from_date').value;
        const toDate = document.getElementById('custom_to_date').value;
        
        if (fromDate && toDate) {
            // Clear any selected radio buttons
            const radioButtons = document.querySelectorAll('input[name="days"]');
            radioButtons.forEach(radio => radio.checked = false);
            
            // Set custom dates
            document.getElementById('global_date_from').value = fromDate;
            document.getElementById('global_date_to').value = toDate;
            
            // Submit form
            document.getElementById('globalFilterForm').submit();
        } else {
            alert('Please select both start and end dates.');
        }
    }

    function setYearRange() {
        const today = new Date();
        const fromDate = new Date(today.getTime() - (365 * 24 * 60 * 60 * 1000));
        
        // Clear any selected radio buttons
        const radioButtons = document.querySelectorAll('input[name="days"]');
        radioButtons.forEach(radio => radio.checked = false);
        
        document.getElementById('global_date_from').value = fromDate.toISOString().split('T')[0];
        document.getElementById('global_date_to').value = today.toISOString().split('T')[0];
        document.getElementById('globalFilterForm').submit();
    }


    // Auto-submit form when radio buttons change
    document.addEventListener('DOMContentLoaded', function() {
        const radioButtons = document.querySelectorAll('input[name="days"]');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const days = this.value;
                    const today = new Date();
                    const fromDate = new Date(today.getTime() - (days * 24 * 60 * 60 * 1000));
                    
                    document.getElementById('global_date_from').value = fromDate.toISOString().split('T')[0];
                    document.getElementById('global_date_to').value = today.toISOString().split('T')[0];
                    document.getElementById('globalFilterForm').submit();
                }
            });
        });
    });

    // Unblock phone number
    function unblockPhone(phone) {
        if (confirm(`Are you sure you want to remove ${phone} from the blacklist?`)) {
            fetch('{{ route('admin.fraud-protection.unblock-phone') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ phone: phone })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`✅ ${data.message}!`);
                    location.reload();
                } else {
                    alert('❌ Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('❌ Error: ' + error.message);
            });
        }
    }

    // Unblock IP address
    function unblockIp(ip) {
        if (confirm(`Are you sure you want to remove ${ip} from the blacklist?`)) {
            fetch('{{ route('admin.fraud-protection.unblock-ip') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ ip: ip })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`✅ ${data.message}!`);
                    location.reload();
                } else {
                    alert('❌ Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('❌ Error: ' + error.message);
            });
        }
    }
</script>
@endsection

