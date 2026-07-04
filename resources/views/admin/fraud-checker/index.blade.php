@extends('layouts.master')

@section('styles')
<style>
    
.glowcard i {
    font-size: 24px;
}
.glowcard .icon {
    color: #0064ff;
}
</style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mt-2">Fraud Checker Dashboard</h4>
            <a href="{{ route('admin.fraud-checker.integration') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Integration
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="glowcard glowcard1">
                    <div class="glowcard-content">
                        <div class="icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <div class="count">{{ $stats['total_checks'] }}</div>
                            <div class="label">Total Checks</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glowcard glowcard2">
                    <div class="glowcard-content">
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <div class="count">{{ $stats['high_risk_count'] }}</div>
                            <div class="label">High Risk</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glowcard glowcard3">
                    <div class="glowcard-content">
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div class="count">{{ $stats['recent_checks'] }}</div>
                            <div class="label">This Week</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glowcard glowcard4">
                    <div class="glowcard-content">
                        <div class="icon">
                            <i class="fas fa-plug"></i>
                        </div>
                        <div>
                            <div class="count">{{ $stats['active_providers'] }}</div>
                            <div class="label">Active Providers</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="glowcard glowcard5">
                    <div class="glowcard-content">
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <div class="count">{{ $stats['new_customers'] }}</div>
                            <div class="label">New Customers</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Phone Check -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quick Phone Check</h5>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" id="phone-check-input" class="form-control" placeholder="Enter phone number (e.g., 01712345678)">
                            </div>
                            <div class="col-md-4">
                                <button id="check-phone-btn" class="btn btn-primary me-2">
                                    <i class="fas fa-search"></i> Check
                                </button>
                                <button id="force-refresh-btn" class="btn btn-warning">
                                    <i class="fas fa-sync"></i> Force Refresh
                                </button>
                            </div>
                        </div>
                        <div id="phone-check-result" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Integrations -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Fraud Checker Integrations</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Provider</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($integrations as $integration)
                                        <tr>
                                            <td>
                                                <strong>{{ $integration->provider_name }}</strong>
                                                <br><small class="text-muted">{{ $integration->provider_description }}</small>
                                            </td>
                                            <td>
                                                @if($integration->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.fraud-checker.integration', $integration->id) }}" class="btn btn-sm btn-info">Edit</a>
                                                <button class="btn btn-sm btn-success test-connection" data-id="{{ $integration->id }}">Test</button>
                                                <form action="{{ route('admin.fraud-checker.destroy', $integration->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No integrations found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Results -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Fraud Checks</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Phone</th>
                                        <th>Risk Level</th>
                                        <th>Score</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentResults as $result)
                                        <tr>
                                            <td>{{ $result->phone }}</td>
                                            <td>
                                                <span class="{{ $result->risk_level_badge_class }}">
                                                    {{ $result->risk_level_display }}
                                                </span>
                                            </td>
                                            <td>{{ $result->risk_score }}/100</td>
                                            <td>{{ $result->formatted_last_checked }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No recent checks</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.fraud-checker.results') }}" class="btn btn-outline-primary">View All Results</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- High Risk Results -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">High Risk Results</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Phone</th>
                                        <th>Risk Level</th>
                                        <th>Score</th>
                                        <th>Success Rate</th>
                                        <th>Total Parcels</th>
                                        <th>Last Checked</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($highRiskResults as $result)
                                        <tr>
                                            <td>{{ $result->phone }}</td>
                                            <td>
                                                <span class="{{ $result->risk_level_badge_class }}">
                                                    {{ $result->risk_level_display }}
                                                </span>
                                            </td>
                                            <td>{{ $result->risk_score }}/100</td>
                                            <td>{{ $result->delivery_success_rate }}%</td>
                                            <td>{{ $result->total_parcels }}</td>
                                            <td>{{ $result->formatted_last_checked }}</td>
                                            <td>
                                                <a href="{{ route('admin.fraud-checker.result-details', $result->id) }}" class="btn btn-sm btn-info">Details</a>
                                                <button class="btn btn-sm btn-warning refresh-result" data-id="{{ $result->id }}">Refresh</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No high risk results found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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

                $('#check-phone-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Checking...');
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
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i> ${response.message}
                                </div>
                            `);
                        }
                    },
                    error: function() {
                        $('#phone-check-result').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> An error occurred while checking the phone number.
                            </div>
                        `);
                    },
                    complete: function() {
                        $('#check-phone-btn').prop('disabled', false).html('<i class="fas fa-search"></i> Check');
                        $('#force-refresh-btn').prop('disabled', false);
                    }
                });
            }

            function displayPhoneCheckResult(result) {
                const riskLevelClass = getRiskLevelClass(result.risk_level);
                const riskLevelDisplay = getRiskLevelDisplay(result.risk_level);

                $('#phone-check-result').html(`
                    <div class="alert alert-info">
                        <h6><i class="fas fa-phone"></i> ${result.phone}</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Risk Level:</strong><br>
                                <span class="badge ${riskLevelClass}">${riskLevelDisplay}</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Risk Score:</strong><br>
                                ${result.risk_score}/100
                            </div>
                            <div class="col-md-3">
                                <strong>Success Rate:</strong><br>
                                ${result.delivery_success_rate}%
                            </div>
                            <div class="col-md-3">
                                <strong>Total Parcels:</strong><br>
                                ${result.total_parcels}
                            </div>
                        </div>
                        ${result.recommendation ? `<div class="mt-2"><strong>Recommendation:</strong> ${result.recommendation}</div>` : ''}
                    </div>
                `);
            }

            function getRiskLevelClass(riskLevel) {
                switch(riskLevel) {
                    case 'high': return 'bg-danger';
                    case 'medium': return 'bg-warning';
                    case 'low': return 'bg-info';
                    case 'very_low': return 'bg-success';
                    default: return 'bg-secondary';
                }
            }

            function getRiskLevelDisplay(riskLevel) {
                switch(riskLevel) {
                    case 'high': return 'High Risk';
                    case 'medium': return 'Medium Risk';
                    case 'low': return 'Low Risk';
                    case 'very_low': return 'Very Low Risk';
                    default: return 'Unknown';
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
