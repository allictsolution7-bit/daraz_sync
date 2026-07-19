@extends('layouts.master')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* Complete Control Panel Overhaul */
    #fraud-checker-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
    }

    .premium-panel-header {
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 24px;
        margin-bottom: 32px;
    }

    .premium-panel-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .premium-panel-header h1 i {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .premium-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.025);
        transition: all 0.3s ease;
    }

    .premium-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
    }

    .premium-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-top: 0;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 14px;
    }

    .premium-card-title i {
        color: #ef4444;
    }

    /* Tabs Layout Styling */
    .nav-tabs-overhaul {
        background: #e2e8f0;
        padding: 6px;
        border-radius: 14px;
        gap: 4px;
        border: none;
        margin-bottom: 32px;
    }

    .nav-tabs-overhaul .nav-item {
        flex: 1;
    }

    .nav-tabs-overhaul .nav-link {
        width: 100%;
        border-radius: 10px;
        color: #475569;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 14px 24px;
        text-align: center;
        transition: all 0.2s ease;
        border: none !important;
        background: transparent;
    }

    .nav-tabs-overhaul .nav-link:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.4);
    }

    .nav-tabs-overhaul .nav-link.active {
        background: #ffffff !important;
        color: #ef4444 !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    /* Elegant Stats Grid */
    .stats-overhaul {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }

    @media (max-width: 1200px) {
        .stats-overhaul {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-overhaul {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .stats-overhaul {
            grid-template-columns: 1fr;
        }
    }

    .stat-box {
        position: relative;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .stat-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    .stat-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    .stat-box-total::before { background-color: #6366f1; }
    .stat-box-high::before { background-color: #ef4444; }
    .stat-box-recent::before { background-color: #3b82f6; }
    .stat-box-active::before { background-color: #10b981; }
    .stat-box-new::before { background-color: #f59e0b; }

    .stat-box .stat-meta h4 {
        margin: 0;
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .stat-box .stat-meta .stat-val {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
    }

    .stat-box .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .stat-icon-total { background-color: #eef2ff; color: #6366f1; }
    .stat-icon-high { background-color: #fef2f2; color: #ef4444; }
    .stat-icon-recent { background-color: #eff6ff; color: #3b82f6; }
    .stat-icon-active { background-color: #ecfdf5; color: #10b981; }
    .stat-icon-new { background-color: #fffbeb; color: #f59e0b; }

    /* Scanner Widget */
    .scanner-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 24px;
    }

    .scanner-input-group {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .scanner-input {
        flex: 1;
        min-width: 280px;
    }

    /* Table Overhauls */
    .table-premium {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .table-premium th {
        font-weight: 700;
        color: #475569;
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 16px 20px;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .table-premium td {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        color: #0f172a;
        font-size: 0.95rem;
        vertical-align: middle;
    }

    .table-premium tbody tr {
        transition: all 0.2s ease;
    }

    .table-premium tbody tr:hover {
        background-color: #f1f5f9;
    }

    /* Badges */
    .badge-glow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 9999px;
        font-size: 0.775rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .badge-glow-high { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .badge-glow-medium { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .badge-glow-low { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .badge-glow-very-low { background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-glow-inactive { background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

    /* Premium Buttons */
    .btn-premium {
        font-weight: 700;
        padding: 12px 24px;
        border-radius: 10px;
        border: none;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        text-decoration: none;
    }

    .btn-premium:hover {
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-premium-primary {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 14px rgba(239, 68, 68, 0.2);
    }

    .btn-premium-primary:hover {
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
        color: white;
    }

    .btn-premium-outline {
        background: #ffffff;
        border: 2px solid #cbd5e1;
        color: #475569;
    }

    .btn-premium-outline:hover {
        border-color: #ef4444;
        color: #ef4444;
        background: #fff5f5;
    }

    .btn-action-sm {
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.775rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-action-sm:hover {
        text-decoration: none;
    }

    .btn-action-edit { background-color: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; }
    .btn-action-edit:hover { background-color: #cbd5e1; color: #0f172a; }

    .btn-action-test { background-color: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .btn-action-test:hover { background-color: #bae6fd; color: #0284c7; }

    .btn-action-refresh { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .btn-action-refresh:hover { background-color: #fde68a; color: #92400e; }

    .btn-action-delete { background-color: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .btn-action-delete:hover { background-color: #fecaca; color: #b91c1c; }
</style>
@endsection

@section('content')
<div id="fraud-checker-page" class="container-fluid px-4 py-4">
    <!-- Premium Header -->
    <div class="premium-panel-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1>
                    <i class="fas fa-shield-alt"></i>
                    Fraud Guard Center
                </h1>
                <p class="text-muted mb-0">Evaluate risk ratios, analyze verification results, and manage scanning providers.</p>
            </div>
            <div>
                <a href="{{ route('admin.fraud-checker.integration') }}" class="btn-premium btn-premium-primary">
                    <i class="fas fa-plus"></i> Add Integration Provider
                </a>
            </div>
        </div>
    </div>

    <!-- Overhauled Stats Cards -->
    <div class="stats-overhaul">
        <div class="stat-box stat-box-total">
            <div class="stat-meta">
                <h4>Total Scans</h4>
                <div class="stat-val">{{ $stats['total_checks'] }}</div>
            </div>
            <div class="stat-icon stat-icon-total">
                <i class="fas fa-search-plus"></i>
            </div>
        </div>
        <div class="stat-box stat-box-high">
            <div class="stat-meta">
                <h4>High Risk Level</h4>
                <div class="stat-val">{{ $stats['high_risk_count'] }}</div>
            </div>
            <div class="stat-icon stat-icon-high">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="stat-box stat-box-recent">
            <div class="stat-meta">
                <h4>This Week</h4>
                <div class="stat-val">{{ $stats['recent_checks'] }}</div>
            </div>
            <div class="stat-icon stat-icon-recent">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
        <div class="stat-box stat-box-active">
            <div class="stat-meta">
                <h4>Active Channels</h4>
                <div class="stat-val">{{ $stats['active_providers'] }}</div>
            </div>
            <div class="stat-icon stat-icon-active">
                <i class="fas fa-plug"></i>
            </div>
        </div>
        <div class="stat-box stat-box-new">
            <div class="stat-meta">
                <h4>New Customer checks</h4>
                <div class="stat-val">{{ $stats['new_customers'] }}</div>
            </div>
            <div class="stat-icon stat-icon-new">
                <i class="fas fa-user-shield"></i>
            </div>
        </div>
    </div>

    <!-- Quick Phone Check Tool -->
    <div class="premium-card">
        <h5 class="premium-card-title">
            <i class="fas fa-phone-volume"></i> Real-time Phone Verification Scanner
        </h5>
        <div class="scanner-box">
            <div class="scanner-input-group">
                <input type="text" id="phone-check-input" class="form-control scanner-input" placeholder="Enter phone number to scan (e.g. 01712345678)">
                <div class="d-flex gap-2">
                    <button id="check-phone-btn" class="btn-premium btn-premium-primary">
                        <i class="fas fa-bolt"></i> Run Scan
                    </button>
                    <button id="force-refresh-btn" class="btn-premium btn-premium-outline">
                        <i class="fas fa-sync-alt"></i> Re-Scan API
                    </button>
                </div>
            </div>
            <div id="phone-check-result" class="mt-3"></div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs nav-tabs-overhaul" id="fraudTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="integrations-tab" data-bs-toggle="tab" data-bs-target="#integrations-pane" type="button" role="tab">
                <i class="fas fa-network-wired me-2"></i> Integration Channels
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="recent-tab" data-bs-toggle="tab" data-bs-target="#recent-pane" type="button" role="tab">
                <i class="fas fa-search me-2"></i> Verification Logs
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="flagged-tab" data-bs-toggle="tab" data-bs-target="#flagged-pane" type="button" role="tab">
                <i class="fas fa-exclamation-triangle me-2"></i> Flagged High Risk Accounts
            </button>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content" id="fraudTabsContent">
        <!-- Tab 1: Integrations -->
        <div class="tab-pane fade show active" id="integrations-pane" role="tabpanel">
            <div class="premium-card p-0" style="overflow: hidden;">
                <div class="table-responsive">
                    <table class="table table-premium">
                        <thead>
                            <tr>
                                <th>Provider</th>
                                <th>Status</th>
                                <th class="text-end">Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($integrations as $integration)
                                <tr>
                                    <td>
                                        <strong style="color: #0f172a;">{{ $integration->provider_name }}</strong>
                                        <div style="font-size: 0.775rem; color: #64748b; margin-top: 4px;">{{ $integration->provider_description }}</div>
                                    </td>
                                    <td>
                                        @if($integration->is_active)
                                            <span class="badge-glow badge-glow-low">Active</span>
                                        @else
                                            <span class="badge-glow badge-glow-inactive">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('admin.fraud-checker.integration', $integration->id) }}" class="btn-action-sm btn-action-edit">Edit</a>
                                            <button class="btn-action-sm btn-action-test test-connection" data-id="{{ $integration->id }}">Test</button>
                                            <form action="{{ route('admin.fraud-checker.destroy', $integration->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-sm btn-action-delete" onclick="return confirm('Confirm deletion of this integration provider?')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No verification integrations configured.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 2: Recent Results -->
        <div class="tab-pane fade" id="recent-pane" role="tabpanel">
            <div class="premium-card p-0" style="overflow: hidden;">
                <div class="px-4 pt-4 d-flex align-items-center justify-content-between">
                    <h5 class="premium-card-title m-0 pb-3" style="border-bottom:none; flex: 1;">
                        Recent Verifications
                    </h5>
                    <a href="{{ route('admin.fraud-checker.results') }}" class="btn-action-sm btn-action-test pb-3" style="border:none; background:transparent; font-weight:700;">View All logs</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-premium">
                        <thead>
                            <tr>
                                <th>Phone</th>
                                <th>Risk Category</th>
                                <th>Risk Score</th>
                                <th>Log Age</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentResults as $result)
                                <tr>
                                    <td style="font-weight: 600;">{{ $result->phone }}</td>
                                    <td>
                                        @if($result->risk_level === 'high')
                                            <span class="badge-glow badge-glow-high">High Risk</span>
                                        @elseif($result->risk_level === 'medium')
                                            <span class="badge-glow badge-glow-medium">Medium Risk</span>
                                        @elseif($result->risk_level === 'low')
                                            <span class="badge-glow badge-glow-low">Low Risk</span>
                                        @else
                                            <span class="badge-glow badge-glow-very-low">Very Low Risk</span>
                                        @endif
                                    </td>
                                    <td style="font-weight: 700; color: #0f172a;">{{ $result->risk_score }}/100</td>
                                    <td style="font-size: 0.825rem; color: #64748b;">{{ $result->formatted_last_checked }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No recent verification logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab 3: High Risk Results -->
        <div class="tab-pane fade" id="flagged-pane" role="tabpanel">
            <div class="premium-card p-0" style="overflow: hidden;">
                <div class="table-responsive">
                    <table class="table table-premium">
                        <thead>
                            <tr>
                                <th>Phone</th>
                                <th>Risk Category</th>
                                <th>Risk Score</th>
                                <th>Delivery Success Rate</th>
                                <th>Total Parcels</th>
                                <th>Verified Age</th>
                                <th class="text-end">Operations</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($highRiskResults as $result)
                                <tr>
                                    <td style="font-weight: 600;">{{ $result->phone }}</td>
                                    <td>
                                        <span class="badge-glow badge-glow-high">High Risk</span>
                                    </td>
                                    <td style="font-weight: 700; color: #dc2626;">{{ $result->risk_score }}/100</td>
                                    <td style="font-weight: 700; color: #0f172a;">{{ $result->delivery_success_rate }}%</td>
                                    <td style="font-weight: 600; color: #475569;">{{ $result->total_parcels }}</td>
                                    <td style="font-size: 0.825rem; color: #64748b;">{{ $result->formatted_last_checked }}</td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('admin.fraud-checker.result-details', $result->id) }}" class="btn-action-sm btn-action-edit">Details</a>
                                            <button class="btn-action-sm btn-action-refresh refresh-result" data-id="{{ $result->id }}">Refresh</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No flagged high-risk records in database.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Phone check functionality
        $('#check-phone-btn').click(function() {
            checkPhone(false);
        });

        $('#force-refresh-btn').click(function() {
            checkPhone(true);
        });

        $('#phone-check-input').keypress(function(e) {
            if (e.which === 13) {
                checkPhone(false);
            }
        });

        function checkPhone(forceRefresh) {
            const phone = $('#phone-check-input').val().trim();
            if (!phone) {
                alert('Please enter a phone number');
                return;
            }

            $('#check-phone-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Scanning...');
            $('#force-refresh-btn').prop('disabled', true);

            $.ajax({
                url: '{{ route("admin.fraud-checker.check-phone") }}',
                method: 'POST',
                data: {
                    phone: phone,
                    force_refresh: forceRefresh,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        displayPhoneCheckResult(response.result);
                    } else {
                        $('#phone-check-result').html(`
                            <div class="alert alert-danger border-0 shadow-sm" style="border-radius:10px;">
                                <i class="fas fa-exclamation-triangle me-2"></i> ${response.message}
                            </div>
                        `);
                    }
                },
                error: function() {
                    $('#phone-check-result').html(`
                        <div class="alert alert-danger border-0 shadow-sm" style="border-radius:10px;">
                            <i class="fas fa-exclamation-triangle me-2"></i> An error occurred while checking the phone number.
                        </div>
                    `);
                },
                complete: function() {
                    $('#check-phone-btn').prop('disabled', false).html('<i class="fas fa-bolt"></i> Run Scan');
                    $('#force-refresh-btn').prop('disabled', false);
                }
            });
        }

        function displayPhoneCheckResult(result) {
            const riskLevelClass = getRiskLevelClass(result.risk_level);
            const riskLevelDisplay = getRiskLevelDisplay(result.risk_level);

            $('#phone-check-result').html(`
                <div class="alert alert-info border-0 shadow-sm" style="border-radius:12px; background:#f0f9ff; border:1px solid #e0f2fe; color:#0369a1; padding:20px;">
                    <h6 class="font-weight-bold mb-3" style="font-size:1.05rem;"><i class="fas fa-phone me-1"></i> Scan Target: ${result.phone}</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <span class="text-muted d-block small mb-1" style="font-weight:600;">Risk Category:</span>
                            <span class="badge ${riskLevelClass} px-3 py-2" style="border-radius:6px; font-weight:700;">${riskLevelDisplay}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted d-block small mb-1" style="font-weight:600;">Risk Score:</span>
                            <strong style="font-size:1.1rem; color:#0f172a;">${result.risk_score}/100</strong>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted d-block small mb-1" style="font-weight:600;">Success Rate:</span>
                            <strong style="font-size:1.1rem; color:#0f172a;">${result.delivery_success_rate}%</strong>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted d-block small mb-1" style="font-weight:600;">Total Parcels:</span>
                            <strong style="font-size:1.1rem; color:#0f172a;">${result.total_parcels}</strong>
                        </div>
                    </div>
                    ${result.recommendation ? `<div class="mt-3 pt-3 border-top" style="border-color:rgba(0,0,0,0.05);"><strong style="color:#0f172a;">AI Guard Recommendation:</strong> ${result.recommendation}</div>` : ''}
                </div>
            `);
        }

        function getRiskLevelClass(riskLevel) {
            switch(riskLevel) {
                case 'high': return 'bg-danger text-white';
                case 'medium': return 'bg-warning text-dark';
                case 'low': return 'bg-info text-white';
                case 'very_low': return 'bg-success text-white';
                default: return 'bg-secondary text-white';
            }
        }

        // Test connection functionality
        $('.test-connection').click(function() {
            const id = $(this).data('id');
            const btn = $(this);
            
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Testing...');
            
            $.get(`/admin/fraud-checker/${id}/test-connection`, function(response) {
                if (response.success) {
                    alert('Connection successful!');
                } else {
                    alert('Connection failed: ' + response.message);
                }
            }).fail(function() {
                alert('Connection test failed');
            }).always(function() {
                btn.prop('disabled', false).html('Test');
            });
        });

        // Refresh result functionality
        $('.refresh-result').click(function() {
            const id = $(this).data('id');
            const btn = $(this);
            
            if (confirm('Are you sure you want to refresh this result? This will call the APIs again.')) {
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Refreshing...');
                
                $.get(`/admin/fraud-checker/results/${id}/refresh`, function(response) {
                    if (response.success) {
                        alert('Result refreshed successfully!');
                        location.reload();
                    } else {
                        alert('Refresh failed: ' + response.message);
                    }
                }).fail(function() {
                    alert('Refresh failed');
                }).always(function() {
                    btn.prop('disabled', false).html('Refresh');
                });
            }
        });
    });
</script>
@endsection
