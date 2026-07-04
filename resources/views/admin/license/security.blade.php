@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.license.index') }}">License</a></li>
                        <li class="breadcrumb-item active">Security Monitor</li>
                    </ol>
                </div>
                <h5 class="page-title mb-3">License Security Monitor</h5>
            </div>
        </div>
    </div>

    <!-- Security Overview Cards -->
    <div class="row">
        <div class="col-lg-3">
            <div class="card border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-danger">Tamper Attempts</h6>
                            <h3 class="text-danger">{{ $securityData['tamper_attempts']['total_attempts'] }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shield-alt fa-2x text-danger"></i>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="clearTamperAttempts()">
                        <i class="fas fa-trash"></i> Clear
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="card {{ $securityData['heartbeat_status']['is_healthy'] ? 'border-success' : 'border-warning' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title {{ $securityData['heartbeat_status']['is_healthy'] ? 'text-success' : 'text-warning' }}">
                                Heartbeat Status
                            </h6>
                            <h5 class="{{ $securityData['heartbeat_status']['is_healthy'] ? 'text-success' : 'text-warning' }}">
                                {{ $securityData['heartbeat_status']['is_healthy'] ? 'Healthy' : 'Issues' }}
                            </h5>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-heartbeat fa-2x {{ $securityData['heartbeat_status']['is_healthy'] ? 'text-success' : 'text-warning' }}"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        Last: {{ $securityData['heartbeat_status']['last_heartbeat'] }}
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="card {{ $securityData['integrity_checks']['system_integrity'] ? 'border-success' : 'border-danger' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title {{ $securityData['integrity_checks']['system_integrity'] ? 'text-success' : 'text-danger' }}">
                                System Integrity
                            </h6>
                            <h5 class="{{ $securityData['integrity_checks']['system_integrity'] ? 'text-success' : 'text-danger' }}">
                                {{ $securityData['integrity_checks']['system_integrity'] ? 'OK' : 'Failed' }}
                            </h5>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x {{ $securityData['integrity_checks']['system_integrity'] ? 'text-success' : 'text-danger' }}"></i>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" onclick="forceIntegrityCheck()">
                        <i class="fas fa-sync"></i> Check
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-info">Security Score</h6>
                            <h3 class="text-info" id="security-score">--</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x text-info"></i>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-outline-info" onclick="refreshSecurityStats()">
                        <i class="fas fa-refresh"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Security Information -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>Recent Tamper Attempts
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($securityData['tamper_attempts']['recent_attempts']) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>IP Address</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($securityData['tamper_attempts']['recent_attempts'] as $attempt)
                                    <tr class="table-danger">
                                        <td>{{ $attempt['timestamp'] ?? 'Unknown' }}</td>
                                        <td>{{ $attempt['ip'] ?? 'Unknown' }}</td>
                                        <td>{{ $attempt['reason'] ?? 'Unknown' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            No tamper attempts detected in recent history.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shield-alt me-2"></i>System Health
                    </h5>
                </div>
                <div class="card-body">
                    <div class="health-checks">
                        <div class="health-check-item">
                            <i class="fas fa-database me-2 {{ $securityData['system_health']['database_status'] ? 'text-success' : 'text-danger' }}"></i>
                            <span>Database Status:</span>
                            <strong class="{{ $securityData['system_health']['database_status'] ? 'text-success' : 'text-danger' }}">
                                {{ $securityData['system_health']['database_status'] ? 'Connected' : 'Disconnected' }}
                            </strong>
                        </div>
                        
                        <div class="health-check-item">
                            <i class="fas fa-file-alt me-2 {{ $securityData['system_health']['file_system_status'] ? 'text-success' : 'text-danger' }}"></i>
                            <span>File System:</span>
                            <strong class="{{ $securityData['system_health']['file_system_status'] ? 'text-success' : 'text-danger' }}">
                                {{ $securityData['system_health']['file_system_status'] ? 'OK' : 'Issues' }}
                            </strong>
                        </div>
                        
                        <div class="health-check-item">
                            <i class="fas fa-network-wired me-2 {{ $securityData['system_health']['network_status'] ? 'text-success' : 'text-danger' }}"></i>
                            <span>Network:</span>
                            <strong class="{{ $securityData['system_health']['network_status'] ? 'text-success' : 'text-danger' }}">
                                {{ $securityData['system_health']['network_status'] ? 'Connected' : 'Disconnected' }}
                            </strong>
                        </div>
                        
                        <div class="health-check-item">
                            <i class="fas fa-key me-2 {{ $securityData['system_health']['license_status']['valid'] ? 'text-success' : 'text-danger' }}"></i>
                            <span>License:</span>
                            <strong class="{{ $securityData['system_health']['license_status']['valid'] ? 'text-success' : 'text-danger' }}">
                                {{ $securityData['system_health']['license_status']['valid'] ? 'Valid' : 'Invalid' }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tools me-2"></i>Security Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100" onclick="forceIntegrityCheck()">
                                <i class="fas fa-sync me-2"></i>Force Integrity Check
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning w-100" onclick="clearTamperAttempts()">
                                <i class="fas fa-trash me-2"></i>Clear Tamper Attempts
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info w-100" onclick="refreshSecurityStats()">
                                <i class="fas fa-chart-bar me-2"></i>Refresh Statistics
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success w-100" onclick="exportSecurityLogs()">
                                <i class="fas fa-download me-2"></i>Export Logs
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function clearTamperAttempts() {
    if (confirm('Are you sure you want to clear all tamper attempts?')) {
        fetch('{{ route("admin.license.security.clear-tamper") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

function forceIntegrityCheck() {
    fetch('{{ route("admin.license.security.integrity-check") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}

function refreshSecurityStats() {
    fetch('{{ route("admin.license.security.stats") }}')
    .then(response => response.json())
    .then(data => {
        document.getElementById('security-score').textContent = data.security_score;
    });
}

function exportSecurityLogs() {
    window.open('{{ route("admin.license.security.export") }}', '_blank');
}

// Auto-refresh security stats every 30 seconds
setInterval(refreshSecurityStats, 30000);
</script>
@endpush

@push('styles')
<style>
.health-check-item {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.health-check-item:last-child {
    border-bottom: none;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.border-danger {
    border-left: 4px solid #dc3545 !important;
}

.border-warning {
    border-left: 4px solid #ffc107 !important;
}

.border-success {
    border-left: 4px solid #28a745 !important;
}

.border-info {
    border-left: 4px solid #17a2b8 !important;
}
</style>
@endpush
