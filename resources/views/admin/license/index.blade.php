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

    /* License info elements */
    .license-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
    }

    .license-info-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px;
        transition: var(--transition-smooth);
    }

    .license-info-card:hover {
        background: #ffffff;
        border-color: var(--primary);
    }

    .license-info-card h6 {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .license-info-card p {
        font-size: 15px;
        font-weight: 700;
        color: var(--dark-slate);
        margin: 0;
    }

    /* Module badges */
    .badge-module {
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 8px;
        margin-right: 6px;
        margin-bottom: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary-light);
        color: var(--primary);
        border: 1.5px solid rgba(25, 122, 148, 0.2);
    }

    /* Progress bar quota styling */
    .quota-container {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 25px;
        margin-top: 25px;
    }

    .progress {
        background-color: #cbd5e1;
        border-radius: 12px;
        height: 14px !important;
        overflow: hidden;
        margin-bottom: 8px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }

    .progress-bar {
        background: var(--primary-gradient);
        border-radius: 12px;
    }

    /* Period Cards */
    .period-card {
        border-radius: 16px;
        border: 1px solid var(--border-glass);
        transition: var(--transition-smooth);
        overflow: hidden;
    }

    .period-header {
        padding: 15px 20px;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
    }

    .period-header.bg-support {
        background: var(--primary-gradient);
    }

    .period-header.bg-update {
        background: linear-gradient(135deg, #0d9488 0%, #115e59 100%);
    }

    .period-body {
        padding: 20px;
        background: #f8fafc;
    }

    .period-alert {
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 15px;
        border: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Activate Form Elements */
    .form-label {
        font-weight: 600;
        color: var(--dark-slate);
        font-size: 14px;
        margin-bottom: 8px;
    }

    .form-control {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        color: var(--text-main);
        background-color: #ffffff;
        transition: var(--transition-smooth);
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(25, 122, 148, 0.15);
    }

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

    .btn-action-outline {
        border: 1.5px solid #cbd5e1;
        background: transparent;
        color: var(--text-main);
        font-weight: 600;
        border-radius: 12px;
        padding: 10px 20px;
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-action-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }

    .help-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 13px;
        color: var(--text-main);
        line-height: 1.5;
    }

    .help-list li i {
        color: var(--success);
        margin-top: 3px;
    }

    /* Card Footer Buttons */
    .card-footer {
        background: transparent !important;
        border-top: 1.5px solid #f1f5f9 !important;
        padding: 20px 25px !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb Header -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="page-title mb-0">License Management</h5>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">License Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- License Status Information -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key text-primary"></i> License Information
                    </h5>
                    @if($licenseStatus['valid'] ?? false)
                        <span class="badge bg-success py-2 px-3 rounded-pill">Active</span>
                    @else
                        <span class="badge bg-danger py-2 px-3 rounded-pill">Inactive</span>
                    @endif
                </div>
                
                <div class="card-body">
                    @if($licenseStatus['valid'] ?? false)
                        <div class="alert alert-success border-0 rounded-4 py-3 mb-4 d-flex align-items-center gap-3">
                            <i class="fas fa-check-circle fs-4"></i>
                            <div>
                                <h6 class="alert-heading mb-1 font-weight-bold">License Activated Successfully!</h6>
                                <p class="mb-0 text-sm opacity-90">Your application license is authenticated and active on this domain.</p>
                            </div>
                        </div>

                        <!-- License Grid Info cards -->
                        <div class="license-info-grid">
                            <div class="license-info-card">
                                <h6><i class="fas fa-key"></i> License Key</h6>
                                <p class="text-truncate">{{ $licenseStatus['license_key'] ?? 'N/A' }}</p>
                            </div>

                            <div class="license-info-card">
                                <h6><i class="fas fa-globe"></i> Domain</h6>
                                <p class="text-truncate" title="Licensed Domain: {{ $licenseStatus['expected_domain'] ?? 'Not set' }}">
                                    {{ $licenseStatus['domain'] ?? 'Not specified' }}
                                </p>
                            </div>

                            <div class="license-info-card">
                                <h6><i class="fas fa-calendar"></i> Expiry Date</h6>
                                <p>
                                    @if($licenseStatus['expiry_date'] ?? null)
                                        {{ $licenseStatus['expiry_date'] }}
                                        @php
                                            $expiryDate = \Carbon\Carbon::parse($licenseStatus['expiry_date']);
                                            $daysLeft = (int) $expiryDate->diffInDays(now());
                                        @endphp
                                        <br>
                                        @if($expiryDate->isPast())
                                            <span class="badge bg-danger mt-1">Expired</span>
                                        @elseif($daysLeft <= 30)
                                            <span class="badge bg-warning mt-1">{{ $daysLeft }} days left</span>
                                        @else
                                            <span class="badge bg-success mt-1">{{ $daysLeft }} days remaining</span>
                                        @endif
                                    @else
                                        Lifetime Expiry
                                        <br><span class="badge bg-success mt-1">No Expiry Limit</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Enabled Modules -->
                        <div class="mt-4">
                            <h6 class="font-weight-bold text-muted mb-3" style="font-size: 13px; letter-spacing: 0.5px; text-transform: uppercase;">Enabled Modules</h6>
                            <div class="modules-list">
                                @if($licenseStatus['modules'] ?? null)
                                    @foreach($licenseStatus['modules'] as $module)
                                        <span class="badge-module">
                                            @if($module === 'core')
                                                <i class="fas fa-cog"></i> Core
                                            @elseif($module === 'pos')
                                                <i class="fas fa-cash-register"></i> POS
                                            @elseif($module === 'landing_page')
                                                <i class="fas fa-desktop"></i> Landing Page
                                            @else
                                                <i class="fas fa-puzzle-piece"></i> {{ ucfirst($module) }}
                                            @endif
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No modules assigned to this license.</span>
                                @endif
                            </div>
                        </div>

                        <!-- Quota Meter (if Landing Page is enabled) -->
                        @if(in_array('landing_page', $licenseStatus['modules'] ?? []))
                            <div class="quota-container">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="font-weight-bold text-dark" style="font-size: 14px;"><i class="fas fa-desktop me-1 text-primary"></i> Landing Page Quota</span>
                                    <span class="font-weight-bold text-primary" style="font-size: 14px;">
                                        {{ $licenseStatus['landing_page_used'] ?? 0 }} / {{ $licenseStatus['landing_page_limit'] ?? 0 }} Used
                                    </span>
                                </div>
                                @php
                                    $percentage = ($licenseStatus['landing_page_limit'] ?? 0) > 0 
                                        ? (($licenseStatus['landing_page_used'] ?? 0) / ($licenseStatus['landing_page_limit'] ?? 1)) * 100 
                                        : 0;
                                @endphp
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">{{ $licenseStatus['landing_page_remaining'] ?? 0 }} slots remaining</small>
                                    <small class="text-muted">{{ $percentage }}% Usage</small>
                                </div>
                            </div>
                        @endif

                        <!-- Periods (Support & Updates) -->
                        <div class="row mt-4">
                            <!-- Support Card -->
                            <div class="col-md-6 mb-3">
                                <div class="period-card">
                                    <div class="period-header bg-support">
                                        <i class="fas fa-headset"></i> Support Period
                                    </div>
                                    <div class="period-body">
                                        @php $supportStatus = $licenseStatus['support_status'] ?? []; @endphp
                                        <div class="period-alert">
                                            @if($supportStatus['active'] ?? false)
                                                <span class="text-success"><i class="fas fa-check-circle me-1"></i> Active Support</span>
                                                <span class="badge bg-success-light text-success ms-auto">{{ $supportStatus['remaining_days'] ?? 0 }} days left</span>
                                            @elseif(($supportStatus['status'] ?? '') === 'expired')
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Support Expired</span>
                                            @else
                                                <span class="text-warning"><i class="fas fa-clock me-1"></i> Not Configured</span>
                                            @endif
                                        </div>
                                        <div class="row text-sm">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Start Date</small>
                                                <strong class="text-dark">{{ $supportStatus['start_date'] ?? 'Not set' }}</strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">End Date</small>
                                                <strong class="text-dark">{{ $supportStatus['end_date'] ?? 'Not set' }}</strong>
                                            </div>
                                        </div>
                                        <hr class="my-3" style="opacity: 0.1;">
                                        <div class="d-flex justify-content-between text-sm">
                                            <span class="text-muted">Total Duration:</span>
                                            <strong class="text-dark">{{ $supportStatus['duration'] ?? 'N/A' }} Days</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Update Card -->
                            <div class="col-md-6 mb-3">
                                <div class="period-card">
                                    <div class="period-header bg-update">
                                        <i class="fas fa-download"></i> Update Period
                                    </div>
                                    <div class="period-body">
                                        @php $updateStatus = $licenseStatus['update_status'] ?? []; @endphp
                                        <div class="period-alert">
                                            @if($updateStatus['active'] ?? false)
                                                <span class="text-success"><i class="fas fa-check-circle me-1"></i> Active Updates</span>
                                                <span class="badge bg-success-light text-success ms-auto">{{ $updateStatus['remaining_days'] ?? 0 }} days left</span>
                                            @elseif(($updateStatus['status'] ?? '') === 'expired')
                                                <span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Updates Expired</span>
                                            @else
                                                <span class="text-warning"><i class="fas fa-clock me-1"></i> Not Configured</span>
                                            @endif
                                        </div>
                                        <div class="row text-sm">
                                            <div class="col-6">
                                                <small class="text-muted d-block">Start Date</small>
                                                <strong class="text-dark">{{ $updateStatus['start_date'] ?? 'Not set' }}</strong>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block">End Date</small>
                                                <strong class="text-dark">{{ $updateStatus['end_date'] ?? 'Not set' }}</strong>
                                            </div>
                                        </div>
                                        <hr class="my-3" style="opacity: 0.1;">
                                        <div class="d-flex justify-content-between text-sm">
                                            <span class="text-muted">Total Duration:</span>
                                            <strong class="text-dark">{{ $updateStatus['duration'] ?? 'N/A' }} Days</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sync/Sync metadata -->
                        <div class="mt-2 text-sm text-muted">
                            <i class="fas fa-history me-1"></i> Last Synced: <strong>{{ $licenseStatus['last_synced'] ?? 'Never' }}</strong>
                            @if($licenseStatus['needs_sync'] ?? false)
                                <span class="badge bg-warning text-dark ms-2">Sync Required</span>
                            @endif
                        </div>
                    @else
                        <!-- Inactive alert -->
                        <div class="alert alert-warning border-0 rounded-4 py-3 mb-0 d-flex align-items-center gap-3">
                            <i class="fas fa-exclamation-triangle fs-4 text-warning"></i>
                            <div>
                                <h6 class="alert-heading mb-1 font-weight-bold text-dark">No Active License Found</h6>
                                <p class="mb-0 text-sm opacity-90 text-dark">
                                    {{ $licenseStatus['message'] ?? 'Please paste a valid license key on the right-hand panel to unlock full functionality.' }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                @if(isset($licenseStatus['license_key']) && $licenseStatus['license_key'])
                    <div class="card-footer d-flex justify-content-between flex-wrap gap-2">
                        <button type="button" class="btn-action-outline" onclick="revalidateLicense()">
                            <i class="fas fa-sync"></i> Revalidate License
                        </button>
                        <a href="{{ route('admin.license.security.index') }}" class="btn-action-outline">
                            <i class="fas fa-shield-alt"></i> Security Monitor
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Side Panel: Activate License & Help -->
        <div class="col-lg-4">
            <!-- Activate Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus text-primary"></i> Activate License
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.license.activate') }}" method="POST" id="licenseForm">
                        @csrf
                        <div class="form-group">
                            <label for="license_key" class="form-label">License Key String</label>
                            <textarea class="form-control @error('license_key') is-invalid @enderror" 
                                      id="license_key" 
                                      name="license_key" 
                                      rows="5" 
                                      placeholder="Paste your license key here..."
                                      required>{{ old('license_key') }}</textarea>
                            @error('license_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i> Make sure to paste the exact license text provided.
                            </small>
                        </div>
                        
                        <button type="submit" class="btn-action-primary w-100" id="activateBtn">
                            <i class="fas fa-key"></i> Activate System
                        </button>
                    </form>
                </div>
            </div>

            <!-- Help Info Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-primary"></i> Help & Resources
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="help-list list-unstyled m-0">
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <span>License validation occurs automatically every 24 hours in the background.</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <span>If you change domains or update features, use the "Revalidate License" button.</span>
                        </li>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <span>For quick support, call <strong>+8801779542054</strong> or contact our helpdesk.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function revalidateLicense() {
    const btn = event.currentTarget || event.target;
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Revalidating...';
    btn.disabled = true;
    
    fetch('{{ route("admin.license.revalidate") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to revalidate license'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while revalidating the license');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Form submission with loading state
document.getElementById('licenseForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('activateBtn');
    if (btn) {
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Activating...';
        btn.disabled = true;
    }
});
</script>
@endpush
