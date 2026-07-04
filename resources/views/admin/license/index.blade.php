@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">License Management</li>
                    </ol>
                </div>
                <h5 class="page-title mb-3">License Management</h5>
            </div>
        </div>
    </div>

    <!-- License Status Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key me-2"></i>License Information
                    </h5>
                </div>
                <div class="card-body">
                    @if($licenseStatus['valid'])
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>License Active!</strong> Your license is valid and active.
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="license-info">
                                    <h6><i class="fas fa-key me-1"></i> License Key</h6>
                                    <p class="text-muted">{{ $licenseStatus['license_key'] }}</p>
                                </div>
                                
                                <div class="license-info mt-3">
                                    <h6><i class="fas fa-globe me-1"></i> Domain</h6>
                                    <p class="text-muted mb-1">{{ $licenseStatus['domain'] ?? 'Not specified' }}</p>
                                    <small class="text-muted d-block">
                                        Expected: {{ $licenseStatus['expected_domain'] ?? 'Not set' }}
                                    </small>
                                </div>
                                
                                <div class="license-info mt-3">
                                    <h6><i class="fas fa-calendar me-1"></i> Expiry Date</h6>
                                    <p class="text-muted">
                                        @if($licenseStatus['expiry_date'])
                                            {{ $licenseStatus['expiry_date'] }}
                                            @php
                                                $expiryDate = \Carbon\Carbon::parse($licenseStatus['expiry_date']);
                                                $daysLeft = (int) $expiryDate->diffInDays(now());
                                            @endphp
                                            @if($expiryDate->isPast())
                                                <span class="badge bg-danger ms-2">Expired</span>
                                            @elseif($daysLeft <= 30)
                                                <span class="badge bg-warning ms-2">{{ $daysLeft }} days left</span>
                                            @else
                                                <span class="badge bg-success ms-2">{{ $daysLeft }} days left</span>
                                            @endif
                                        @else
                                            Lifetime
                                            <span class="badge bg-success ms-2">No Expiry</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="license-info">
                                    <h6><i class="fas fa-puzzle-piece me-1"></i> Enabled Modules</h6>
                                    <div class="modules-list">
                                        @if($licenseStatus['modules'])
                                            @foreach($licenseStatus['modules'] as $module)
                                                <span class="badge bg-primary me-1 mb-1">
                                                    @if($module === 'core')
                                                        <i class="fas fa-cog me-1"></i>Core
                                                    @elseif($module === 'pos')
                                                        <i class="fas fa-cash-register me-1"></i>POS
                                                    @elseif($module === 'landing_page')
                                                        <i class="fas fa-desktop me-1"></i>Landing Page
                                                    @else
                                                        <i class="fas fa-puzzle-piece me-1"></i>{{ ucfirst($module) }}
                                                    @endif
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No modules specified</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if(in_array('landing_page', $licenseStatus['modules'] ?? []))
                                    <div class="license-info mt-3">
                                        <h6><i class="fas fa-desktop me-1"></i> Landing Page Quota</h6>
                                        <div class="progress mb-2" style="height: 20px;">
                                            @php
                                                $percentage = $licenseStatus['landing_page_limit'] > 0 
                                                    ? ($licenseStatus['landing_page_used'] / $licenseStatus['landing_page_limit']) * 100 
                                                    : 0;
                                            @endphp
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%">
                                                {{ $licenseStatus['landing_page_used'] }}/{{ $licenseStatus['landing_page_limit'] }}
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            {{ $licenseStatus['landing_page_remaining'] }} remaining out of {{ $licenseStatus['landing_page_limit'] }} allowed
                                        </small>
                                    </div>
                                @endif
                                
                                <div class="license-info mt-3">
                                    <h6><i class="fas fa-sync me-1"></i> Last Synced</h6>
                                    <p class="text-muted">
                                        {{ $licenseStatus['last_synced'] ?? 'Never' }}
                                        @if($licenseStatus['needs_sync'])
                                            <span class="badge bg-warning ms-2">Needs Sync</span>
                                        @endif
                                        @if(!empty($licenseStatus['last_synced_at']))
                                            <br><small class="text-muted">at {{ $licenseStatus['last_synced_at'] }}</small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Support and Update Periods Section -->
                        @if($licenseStatus['valid'])
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0"><i class="fas fa-headset me-2"></i>Support Period</h6>
                                        </div>
                                        <div class="card-body">
                                            @php $supportStatus = $licenseStatus['support_status']; @endphp
                                            
                                            <div class="support-status mb-3">
                                                @if($supportStatus['active'])
                                                    <div class="alert alert-success py-2 mb-2">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        <strong>Active</strong> - {{ $supportStatus['remaining_days'] }} days remaining
                                                    </div>
                                                @elseif(($supportStatus['status'] ?? '') === 'expired')
                                                    <div class="alert alert-danger py-2 mb-2">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        <strong>Expired</strong> - Please renew support
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning py-2 mb-2">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <strong>Inactive</strong> - Not configured
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="support-details">
                                                <div class="row text-sm">
                                                    <div class="col-6">
                                                        <strong>Start Date:</strong><br>
                                                        <span class="text-muted">{{ $supportStatus['start_date'] ?? 'Not set' }}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>End Date:</strong><br>
                                                        <span class="text-muted">{{ $supportStatus['end_date'] ?? 'Not set' }}</span>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <strong>Duration:</strong> {{ $supportStatus['duration'] }} days<br>
                                                    <small class="text-muted">{{ ucfirst($supportStatus['status'] ?? 'unknown') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border-0 bg-light">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0"><i class="fas fa-download me-2"></i>Update Period</h6>
                                        </div>
                                        <div class="card-body">
                                            @php $updateStatus = $licenseStatus['update_status']; @endphp
                                            
                                            <div class="update-status mb-3">
                                                @if($updateStatus['active'])
                                                    <div class="alert alert-success py-2 mb-2">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        <strong>Active</strong> - {{ $updateStatus['remaining_days'] }} days remaining
                                                    </div>
                                                @elseif(($updateStatus['status'] ?? '') === 'expired')
                                                    <div class="alert alert-danger py-2 mb-2">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        <strong>Expired</strong> - Please renew updates
                                                    </div>
                                                @else
                                                    <div class="alert alert-warning py-2 mb-2">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <strong>Inactive</strong> - Not configured
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="update-details">
                                                <div class="row text-sm">
                                                    <div class="col-6">
                                                        <strong>Start Date:</strong><br>
                                                        <span class="text-muted">{{ $updateStatus['start_date'] ?? 'Not set' }}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>End Date:</strong><br>
                                                        <span class="text-muted">{{ $updateStatus['end_date'] ?? 'Not set' }}</span>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <strong>Duration:</strong> {{ $updateStatus['duration'] }} days<br>
                                                    <small class="text-muted">{{ ucfirst($updateStatus['status'] ?? 'unknown') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>License Issue:</strong> {{ $licenseStatus['message'] ?? 'No valid license found' }}
                            @if(!empty($licenseStatus['expected_domain']))
                                <br><small class="text-muted">Use licensed domain: {{ $licenseStatus['expected_domain'] }}</small>
                            @endif
                        </div>
                    @endif
                </div>
                
                @if(isset($licenseStatus['license_key']) && $licenseStatus['license_key'])
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-primary" onclick="revalidateLicense()">
                                <i class="fas fa-sync me-1"></i> Revalidate License
                            </button>
                            <a href="{{ route('admin.license.security.index') }}" class="btn btn-outline-info">
                                <i class="fas fa-shield-alt me-1"></i> Security Monitor
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>Activate License
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.license.activate') }}" method="POST" id="licenseForm">
                        @csrf
                        <div class="mb-3">
                            <label for="license_key" class="form-label">License Key</label>
                            <textarea class="form-control @error('license_key') is-invalid @enderror" 
                                      id="license_key" 
                                      name="license_key" 
                                      rows="4" 
                                      placeholder="Enter your license key here..."
                                      required>{{ old('license_key') }}</textarea>
                            @error('license_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Paste your license key exactly as provided
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100" id="activateBtn">
                            <i class="fas fa-key me-1"></i> Activate License
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Quick Info Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>License Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="license-help">
                        <h6><i class="fas fa-question-circle me-1"></i> Need Help?</h6>
                        <ul class="list-unstyled small">
                            <li><i class="fas fa-check text-success me-1"></i> Contact support for license issues</li>
                            <li><i class="fas fa-check text-success me-1"></i> Call +8801779542054 for any issues</li>
                            <li><i class="fas fa-check text-success me-1"></i> License syncs automatically every 24 hours</li>
                            <li><i class="fas fa-check text-success me-1"></i> Revalidate manually if needed</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function revalidateLicense() {
    const btn = event.target;
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
document.getElementById('licenseForm').addEventListener('submit', function() {
    const btn = document.getElementById('activateBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Activating...';
    btn.disabled = true;
});
</script>
@endpush

@push('styles')
<style>
.license-info h6 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 5px;
}

.modules-list .badge {
    font-size: 0.75rem;
}

.progress-bar {
    background-color: #28a745;
    color: white;
    font-size: 0.75rem;
    line-height: 20px;
}

.license-help ul li {
    margin-bottom: 5px;
}

.page-title-box {
    margin-bottom: 1.5rem;
}

.date-filter-card {
    background: #fff;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid #dee2e6;
}

.support-status .alert,
.update-status .alert {
    border: none;
    font-size: 0.85rem;
}

.support-details,
.update-details {
    font-size: 0.9rem;
}

.support-details .text-sm,
.update-details .text-sm {
    font-size: 0.8rem;
}

.card-header h6 {
    font-weight: 600;
}

.bg-light .card-body {
    background: #f8f9fa;
}
</style>
@endpush
@endsection
