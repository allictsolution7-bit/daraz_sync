@extends('layouts.master')

@section('title', 'Edit Member Profile - Admin Panel')

@section('styles')
<style>
    .edit-hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 20px;
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
    }
    .profile-avatar-wrapper {
        position: relative;
        width: 84px;
        height: 84px;
    }
    .profile-avatar-img {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .avatar-status-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: #10b981;
        border: 3px solid #0f172a;
    }
    .form-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .form-card-header {
        background-color: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 20px;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
    }
    .input-icon-group {
        position: relative;
    }
    .input-icon-group i.input-icon {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        z-index: 5;
    }
    .input-icon-group .form-control,
    .input-icon-group .form-select {
        padding-left: 42px;
        border-radius: 10px;
        border-color: #cbd5e1;
        font-size: 13.5px;
    }
    .input-icon-group .form-control:focus,
    .input-icon-group .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3.5px rgba(37, 99, 235, 0.12);
    }
    .badge-uid {
        background: rgba(255, 255, 255, 0.15);
        color: #38bdf8;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        backdrop-filter: blur(4px);
    }
    .role-radio-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
    }
    .role-radio-card:hover {
        border-color: #3b82f6;
        background: #f8fafc;
    }
    .role-radio-card.selected {
        border-color: #2563eb;
        background: #eff6ff;
    }
    .password-strength-bar {
        height: 4px;
        border-radius: 2px;
        background: #e2e8f0;
        transition: all 0.3s ease;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Navigation Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}" class="text-decoration-none text-muted"><i class="fas fa-home me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}" class="text-decoration-none text-muted">Team Members</a></li>
                    <li class="breadcrumb-item active text-primary font-weight-semibold" aria-current="page">Edit Member Profile</li>
                </ol>
            </nav>
            <h4 class="font-weight-bold text-dark mb-0"><i class="fas fa-user-gear text-primary me-2"></i> Edit Account Profile</h4>
        </div>
        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Members Directory
        </a>
    </div>

    <!-- Alert Notifications -->
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 p-3 d-flex align-items-center gap-3" style="background-color: #fef2f2; color: #991b1b;">
            <i class="fas fa-triangle-exclamation text-danger fs-4"></i>
            <div>
                <strong class="d-block">Please correct the highlighted errors:</strong>
                <ul class="mb-0 ps-3 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 p-3 d-flex align-items-center gap-3" style="background-color: #ecfdf5; color: #065f46;">
            <i class="fas fa-check-circle text-success fs-4"></i>
            <div>
                <strong>Success!</strong> {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Profile Hero Banner Card -->
    <div class="edit-hero-card p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-avatar-wrapper">
                    @php
                        $avatarUrl = !empty($user->profile_photo_path) 
                            ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path) 
                            : asset('clientside/images/profile.png');
                    @endphp
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="profile-avatar-img">
                    <span class="avatar-status-dot" title="Account Active"></span>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <h4 class="mb-0 font-weight-bold text-white">{{ $user->name }}</h4>
                        <span class="badge-uid"><i class="fas fa-hashtag me-1"></i>UID: {{ $user->id }}</span>
                        @if($user->otp_verified)
                            <span class="badge bg-success text-white rounded-pill px-2.5 py-1" style="font-size: 10px;"><i class="fas fa-shield-check me-1"></i> Verified</span>
                        @else
                            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1" style="font-size: 10px;"><i class="fas fa-triangle-exclamation me-1"></i> Unverified</span>
                        @endif
                    </div>
                    <p class="mb-0 text-white-50 small"><i class="fas fa-envelope me-1"></i> {{ $user->email }} &bull; <i class="fas fa-calendar-alt ms-2 me-1"></i> Registered: {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-white text-dark shadow-sm px-3 py-2 rounded-pill font-weight-bold" style="font-size: 12px;">
                    <i class="fas fa-user-shield text-primary me-1"></i> Role: {{ ucfirst($user->getRoleNames()->first() ?? $user->role ?? 'User') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Edit Form -->
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Left Column: Personal & Authentication Information -->
            <div class="col-lg-7 col-12">
                <!-- Personal Information Card -->
                <div class="form-card mb-4">
                    <div class="form-card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-id-card text-primary me-2"></i> Personal & Account Information</h6>
                        <span class="text-muted small">* Required fields</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label for="name" class="form-label font-weight-semibold text-dark small">Full Name <span class="text-danger">*</span></label>
                                <div class="input-icon-group">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required placeholder="e.g. John Doe">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="email" class="form-label font-weight-semibold text-dark small">Email Address <span class="text-danger">*</span></label>
                                <div class="input-icon-group">
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="name@example.com">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="phone" class="form-label font-weight-semibold text-dark small">Phone Number</label>
                                <div class="input-icon-group">
                                    <i class="fas fa-phone input-icon"></i>
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+8801700000000">
                                </div>
                            </div>
                            <div class="col-md-6 col-12 d-flex align-items-end">
                                <div class="form-check form-switch p-3 bg-light rounded-3 border w-100 d-flex align-items-center justify-content-between m-0">
                                    <div>
                                        <label class="form-check-label font-weight-bold text-dark small mb-0" for="otp_verified">OTP Verification Status</label>
                                        <div class="text-muted" style="font-size: 10px;">Enable to mark mobile phone as verified</div>
                                    </div>
                                    <input class="form-check-input ms-3" type="checkbox" role="switch" id="otp_verified" name="otp_verified" value="1" {{ $user->otp_verified ? 'checked' : '' }} style="cursor: pointer; width: 40px; height: 20px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security & Password Reset Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-lock text-warning me-2"></i> Security & Password Management</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert bg-light border text-muted small p-2.5 rounded-3 mb-3 d-flex align-items-center gap-2">
                            <i class="fas fa-info-circle text-info"></i>
                            <span>Leave password fields empty if you do not wish to update the current password.</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label for="password" class="form-label font-weight-semibold text-dark small">New Password</label>
                                <div class="input-icon-group position-relative">
                                    <i class="fas fa-key input-icon"></i>
                                    <input type="password" class="form-control pe-5" id="password" name="password" autocomplete="new-password" placeholder="••••••••" oninput="checkPassStrength(this.value)">
                                    <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3 text-muted" id="togglePasswordBtn" style="cursor: pointer;" onclick="togglePasswordVisibility('password', this)"></i>
                                </div>
                                <div class="mt-2">
                                    <div class="password-strength-bar" id="passStrengthBar"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="password_confirmation" class="form-label font-weight-semibold text-dark small">Confirm New Password</label>
                                <div class="input-icon-group position-relative">
                                    <i class="fas fa-shield-alt input-icon"></i>
                                    <input type="password" class="form-control pe-5" id="password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="••••••••">
                                    <i class="fas fa-eye position-absolute top-50 end-0 translate-middle-y me-3 text-muted" onclick="togglePasswordVisibility('password_confirmation', this)"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Role & Location Details -->
            <div class="col-lg-5 col-12">
                <!-- Role Assignment Card -->
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-user-shield text-info me-2"></i> Role & Access Permission Level</h6>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $isSuperAdmin = $isSuperAdmin ?? (auth()->check() && auth()->user()->isSuperAdmin());
                            $allDbRoles = isset($allRoles) ? $allRoles : \Spatie\Permission\Models\Role::all();
                            if (!$isSuperAdmin) {
                                $allDbRoles = $allDbRoles->filter(function($role) {
                                    $r = strtolower($role->name);
                                    return !str_contains($r, 'super') && !str_contains($r, 'admin');
                                });
                            }
                            $userAssignedRoles = $user->getRoleNames()->toArray();
                            if (empty($userAssignedRoles) && !empty($user->role)) {
                                $userAssignedRoles = [$user->role];
                            }
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label font-weight-semibold text-dark small mb-0">Select Account Roles</label>
                            <span class="badge bg-primary text-white rounded-pill px-2.5 py-1" style="font-size: 10px;">{{ count($allDbRoles) }} roles available</span>
                        </div>
                        
                        <div class="row g-2 mb-3" style="max-height: 320px; overflow-y: auto; padding-right: 4px;">
                            @foreach($allDbRoles as $roleObj)
                                @php
                                    $rName = $roleObj->name;
                                    $isSelected = in_array($rName, $userAssignedRoles);
                                    
                                    $iconClass = 'fas fa-user-shield text-primary';
                                    if (str_contains(strtolower($rName), 'super')) $iconClass = 'fas fa-crown text-warning';
                                    elseif (str_contains(strtolower($rName), 'admin')) $iconClass = 'fas fa-user-gear text-primary';
                                    elseif (str_contains(strtolower($rName), 'vendor') || str_contains(strtolower($rName), 'shop')) $iconClass = 'fas fa-store text-success';
                                    elseif (str_contains(strtolower($rName), 'staff') || str_contains(strtolower($rName), 'manager')) $iconClass = 'fas fa-user-tie text-info';
                                    elseif (str_contains(strtolower($rName), 'customer')) $iconClass = 'fas fa-user text-secondary';
                                @endphp
                                <div class="col-6">
                                    <label class="role-radio-card d-flex align-items-center gap-2 {{ $isSelected ? 'selected' : '' }}" onclick="toggleRoleCheckbox('{{ $rName }}', this, event)" style="min-height: 54px; cursor: pointer;">
                                        <input type="checkbox" name="roles[]" value="{{ $rName }}" class="form-check-input role-cb flex-shrink-0" {{ $isSelected ? 'checked' : '' }} style="cursor: pointer; width: 18px; height: 18px; margin: 0;">
                                        <i class="{{ $iconClass }} fs-5 flex-shrink-0"></i>
                                        <div style="min-width: 0;">
                                            <div class="font-weight-bold text-dark text-truncate" style="font-size: 12px;" title="{{ $rName }}">{{ ucfirst(str_replace(['_', '-'], ' ', $rName)) }}</div>
                                            <div class="text-muted text-truncate" style="font-size: 9.5px;">{{ $rName }}</div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="p-2.5 bg-light rounded-3 border">
                            <div class="small font-weight-semibold text-dark"><i class="fas fa-key text-muted me-1"></i> Permissions Scope</div>
                            <p class="text-muted mb-0" style="font-size: 11px;">Select one or multiple Spatie roles to assign all corresponding permissions to this member account.</p>
                        </div>
                    </div>
                </div>

                <!-- Location & Address Information Card -->
                <div class="form-card">
                    <div class="form-card-header">
                        <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-location-dot text-danger me-2"></i> Address & Regional Location</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="address" class="form-label font-weight-semibold text-dark small">Street / Detailed Address</label>
                            <div class="input-icon-group">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $user->address) }}" placeholder="House #, Street, Area">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <label for="upazila" class="form-label font-weight-semibold text-dark small">Upazila / Sub-District</label>
                                <div class="input-icon-group">
                                    <i class="fas fa-building-flag input-icon"></i>
                                    <input type="text" class="form-control" id="upazila" name="upazila" value="{{ old('upazila', $user->upazila) }}" placeholder="e.g. Mirpur">
                                </div>
                            </div>
                            <div class="col-6">
                                <label for="city" class="form-label font-weight-semibold text-dark small">City / District</label>
                                <div class="input-icon-group">
                                    <i class="fas fa-city input-icon"></i>
                                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $user->city) }}" placeholder="e.g. Dhaka">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Footer Bar -->
        <div class="card border-0 shadow-lg rounded-4 mt-4 bg-white p-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary rounded-pill px-5 shadow font-weight-bold py-2">
                    <i class="fas fa-save me-2"></i> Update Member Profile
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function togglePasswordVisibility(fieldId, iconEl) {
        const input = document.getElementById(fieldId);
        if (input.type === 'password') {
            input.type = 'text';
            iconEl.classList.remove('fa-eye');
            iconEl.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            iconEl.classList.remove('fa-eye-slash');
            iconEl.classList.add('fa-eye');
        }
    }

    function toggleRoleCheckbox(roleName, cardEl, evt) {
        const cb = cardEl.querySelector('.role-cb');
        if (evt.target !== cb) {
            cb.checked = !cb.checked;
        }
        if (cb.checked) {
            cardEl.classList.add('selected');
        } else {
            cardEl.classList.remove('selected');
        }
    }

    function checkPassStrength(val) {
        const bar = document.getElementById('passStrengthBar');
        if (!bar) return;
        if (!val) {
            bar.style.width = '0%';
            bar.style.backgroundColor = '#e2e8f0';
        } else if (val.length < 6) {
            bar.style.width = '30%';
            bar.style.backgroundColor = '#ef4444';
        } else if (val.length < 10) {
            bar.style.width = '65%';
            bar.style.backgroundColor = '#f59e0b';
        } else {
            bar.style.width = '100%';
            bar.style.backgroundColor = '#10b981';
        }
    }
</script>
@endsection