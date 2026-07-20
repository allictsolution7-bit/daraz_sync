@extends('layouts.master')

@section('styles')
<style>
    :root {
        --primary: #197A94;
        --primary-gradient: linear-gradient(135deg, #197A94 0%, #0d5c70 100%);
        --primary-hover: #135d71;
        --primary-light: rgba(25, 122, 148, 0.08);
        --success: #10b981;
        --info: #06b6d4;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark-slate: #1e293b;
        --text-main: #334155;
        --text-muted: #64748b;
        --bg-glass: rgba(255, 255, 255, 0.95);
        --border-glass: rgba(226, 232, 240, 0.9);
        --shadow-premium: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Container adjustments */
    .container-fluid {
        padding: 30px;
        background-color: #f8fafc;
        min-height: 100vh;
    }

    .breadcrumb-item a {
        color: var(--primary);
        font-weight: 500;
    }

    .page-title {
        color: var(--dark-slate);
        font-weight: 800;
        font-size: 24px;
        letter-spacing: -0.5px;
    }

    /* Premium Cards */
    .card {
        background: var(--bg-glass);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        box-shadow: var(--shadow-premium);
        transition: var(--transition-smooth);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        background: transparent !important;
        border-bottom: 1.5px solid #f1f5f9 !important;
        padding: 20px 25px !important;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark-slate);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 25px 30px;
    }

    /* Overview metric cards */
    .metric-card {
        border-left: 4px solid var(--border-glass);
    }
    .metric-card.border-danger { border-left: 4px solid var(--danger) !important; }
    .metric-card.border-success { border-left: 4px solid var(--success) !important; }
    .metric-card.border-warning { border-left: 4px solid var(--warning) !important; }
    .metric-card.border-info { border-left: 4px solid var(--info) !important; }

    .metric-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .metric-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--dark-slate);
        margin: 10px 0;
    }

    /* Buttons */
    .btn-action-primary {
        background: var(--primary-gradient);
        color: white;
        border: none;
        font-weight: 700;
        border-radius: 12px;
        padding: 12px 24px;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
    }
    .btn-action-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(25, 122, 148, 0.3);
        color: white;
    }

    .btn-action-danger {
        background: linear-gradient(135deg, var(--danger) 0%, #b91c1c 100%);
        color: white;
        border: none;
        font-weight: 700;
        border-radius: 12px;
        padding: 12px 24px;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-action-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(239, 68, 68, 0.3);
        color: white;
    }

    .btn-action-warning {
        background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);
        color: white;
        border: none;
        font-weight: 700;
        border-radius: 12px;
        padding: 12px 24px;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-action-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(245, 158, 11, 0.3);
        color: white;
    }

    .btn-action-info {
        background: linear-gradient(135deg, var(--info) 0%, #0891b2 100%);
        color: white;
        border: none;
        font-weight: 700;
        border-radius: 12px;
        padding: 12px 24px;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-action-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(6, 182, 212, 0.3);
        color: white;
    }

    .btn-action-success {
        background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
        color: white;
        border: none;
        font-weight: 700;
        border-radius: 12px;
        padding: 12px 24px;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-action-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .btn-outline-custom {
        border: 1.5px solid #cbd5e1;
        background: transparent;
        color: var(--text-main);
        font-weight: 600;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 12px;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-outline-custom:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }
    .btn-outline-custom-danger {
        border: 1.5px solid rgba(239, 68, 68, 0.4);
        background: transparent;
        color: var(--danger);
        font-weight: 600;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 12px;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-outline-custom-danger:hover {
        border-color: var(--danger);
        color: white;
        background: var(--danger);
    }

    /* Table styles */
    .table {
        margin-bottom: 0;
    }
    .table th {
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 12px;
        border-bottom: 1.5px solid #f1f5f9;
        padding: 12px 16px;
    }
    .table td {
        padding: 12px 16px;
        font-size: 13px;
        color: var(--text-main);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .table-danger-custom {
        background-color: rgba(239, 68, 68, 0.05);
    }

    /* System Health List */
    .health-check-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .health-check-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 18px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: var(--transition-smooth);
    }
    .health-check-row:hover {
        background: #ffffff;
        border-color: var(--primary);
    }

    .health-check-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: var(--text-main);
        font-size: 14px;
    }

    .health-check-row i.status-icon {
        font-size: 18px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb Header -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="page-title mb-0">Security Monitor</h5>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.verification.index') }}">License</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Security Monitor</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Security Metrics Grid -->
    <div class="row">
        <!-- Tamper Attempts -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card metric-card border-danger m-0 h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="metric-title text-danger">Tamper Attempts</span>
                            <i class="fas fa-shield-alt text-danger fs-4"></i>
                        </div>
                        <h3 class="metric-value text-danger">{{ $securityData['tamper_attempts']['total_attempts'] ?? 0 }}</h3>
                    </div>
                    <div>
                        <button class="btn-outline-custom-danger w-100 justify-content-center" onclick="clearTamperAttempts()">
                            <i class="fas fa-trash"></i> Clear Logs
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Heartbeat Status -->
        @php $isHealthy = $securityData['heartbeat_status']['is_healthy'] ?? false; @endphp
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card metric-card {{ $isHealthy ? 'border-success' : 'border-warning' }} m-0 h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="metric-title {{ $isHealthy ? 'text-success' : 'text-warning' }}">Heartbeat Status</span>
                            <i class="fas fa-heartbeat {{ $isHealthy ? 'text-success' : 'text-warning' }} fs-4"></i>
                        </div>
                        <h3 class="metric-value {{ $isHealthy ? 'text-success' : 'text-warning' }}">
                            {{ $isHealthy ? 'Healthy' : 'Warning' }}
                        </h3>
                    </div>
                    <div>
                        <small class="text-muted d-block text-truncate">
                            Last Sync: {{ $securityData['heartbeat_status']['last_heartbeat'] ?? 'Never' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Integrity -->
        @php $integrityOk = $securityData['integrity_checks']['system_integrity'] ?? false; @endphp
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card metric-card {{ $integrityOk ? 'border-success' : 'border-danger' }} m-0 h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="metric-title {{ $integrityOk ? 'text-success' : 'text-danger' }}">System Integrity</span>
                            <i class="fas fa-check-circle {{ $integrityOk ? 'text-success' : 'text-danger' }} fs-4"></i>
                        </div>
                        <h3 class="metric-value {{ $integrityOk ? 'text-success' : 'text-danger' }}">
                            {{ $integrityOk ? 'Secure' : 'Corrupted' }}
                        </h3>
                    </div>
                    <div>
                        <button class="btn-outline-custom w-100 justify-content-center" onclick="forceIntegrityCheck()">
                            <i class="fas fa-sync"></i> Verify Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Score -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card metric-card border-info m-0 h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="metric-title text-info">Security Score</span>
                            <i class="fas fa-chart-line text-info fs-4"></i>
                        </div>
                        <h3 class="metric-value text-info" id="security-score">--</h3>
                    </div>
                    <div>
                        <button class="btn-outline-custom w-100 justify-content-center" onclick="refreshSecurityStats()">
                            <i class="fas fa-arrows-rotate"></i> Refresh Score
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Security Information -->
    <div class="row">
        <!-- Recent Tamper Attempts Log -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle text-danger"></i> Recent Security Log
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($securityData['tamper_attempts']['recent_attempts'] ?? []) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>IP Location</th>
                                        <th>Violation Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($securityData['tamper_attempts']['recent_attempts'] as $attempt)
                                    <tr class="table-danger-custom">
                                        <td class="font-weight-bold">{{ $attempt['timestamp'] ?? 'Unknown' }}</td>
                                        <td><code class="text-dark bg-light px-2 py-1 rounded">{{ $attempt['ip'] ?? 'Unknown' }}</code></td>
                                        <td class="text-danger font-weight-semibold">{{ $attempt['reason'] ?? 'Unknown' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-success border-0 rounded-4 py-3 mb-0 d-flex align-items-center gap-3">
                            <i class="fas fa-check-circle fs-4"></i>
                            <div>
                                <h6 class="alert-heading mb-1 font-weight-bold">System Clean!</h6>
                                <p class="mb-0 text-sm opacity-90">No tampering attempts or unauthorized access violations detected.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- System health metrics -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-heartbeat text-primary"></i> System Diagnostics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="health-check-list">
                        <!-- Database status -->
                        @php $dbOk = $securityData['system_health']['database_status'] ?? false; @endphp
                        <div class="health-check-row">
                            <span class="health-check-label">
                                <i class="fas fa-database status-icon {{ $dbOk ? 'text-success' : 'text-danger' }}"></i>
                                Database Connection
                            </span>
                            <span class="badge {{ $dbOk ? 'bg-success' : 'bg-danger' }} py-2 px-3 rounded-pill">
                                {{ $dbOk ? 'Connected' : 'Disconnected' }}
                            </span>
                        </div>

                        <!-- File System -->
                        @php $fsOk = $securityData['system_health']['file_system_status'] ?? false; @endphp
                        <div class="health-check-row">
                            <span class="health-check-label">
                                <i class="fas fa-folder-open status-icon {{ $fsOk ? 'text-success' : 'text-danger' }}"></i>
                                File Integrity
                            </span>
                            <span class="badge {{ $fsOk ? 'bg-success' : 'bg-danger' }} py-2 px-3 rounded-pill">
                                {{ $fsOk ? 'Secure' : 'Vulnerable' }}
                            </span>
                        </div>

                        <!-- Network status -->
                        @php $netOk = $securityData['system_health']['network_status'] ?? false; @endphp
                        <div class="health-check-row">
                            <span class="health-check-label">
                                <i class="fas fa-globe status-icon {{ $netOk ? 'text-success' : 'text-danger' }}"></i>
                                Licensing Network API
                            </span>
                            <span class="badge {{ $netOk ? 'bg-success' : 'bg-danger' }} py-2 px-3 rounded-pill">
                                {{ $netOk ? 'Online' : 'Offline' }}
                            </span>
                        </div>

                        <!-- License Status -->
                        @php $licOk = $securityData['system_health']['license_status']['valid'] ?? false; @endphp
                        <div class="health-check-row">
                            <span class="health-check-label">
                                <i class="fas fa-key status-icon {{ $licOk ? 'text-success' : 'text-danger' }}"></i>
                                License Status
                            </span>
                            <span class="badge {{ $licOk ? 'bg-success' : 'bg-danger' }} py-2 px-3 rounded-pill">
                                {{ $licOk ? 'Authenticated' : 'Unlicensed' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Action Dashboard -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-screwdriver-wrench text-primary"></i> Security Control Panel
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <button class="btn-action-primary w-100" onclick="forceIntegrityCheck()">
                                <i class="fas fa-sync"></i> Force Verification
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <button class="btn-action-warning w-100" onclick="clearTamperAttempts()">
                                <i class="fas fa-trash-can"></i> Clear Tamper Logs
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <button class="btn-action-info w-100" onclick="refreshSecurityStats()">
                                <i class="fas fa-chart-pie"></i> Refresh Statistics
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <button class="btn-action-success w-100" onclick="exportSecurityLogs()">
                                <i class="fas fa-download"></i> Export Data Logs
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
        fetch('{{ route("admin.verification.health-checks.clear-tamper") }}', {
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
    fetch('{{ route("admin.verification.health-checks.integrity-check") }}', {
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
    fetch('{{ route("admin.verification.health-checks.stats") }}')
    .then(response => response.json())
    .then(data => {
        document.getElementById('security-score').textContent = data.security_score;
    });
}

function exportSecurityLogs() {
    window.open('{{ route("admin.verification.health-checks.export") }}', '_blank');
}

// Initial fetch & auto-refresh security stats every 30 seconds
document.addEventListener('DOMContentLoaded', function() {
    refreshSecurityStats();
});
setInterval(refreshSecurityStats, 30000);
</script>
@endpush
