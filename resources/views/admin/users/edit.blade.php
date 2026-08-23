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
    .template-choice-card {
        display: flex !important;
        flex-direction: column !important;
        justify-content: space-between !important;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        min-height: 175px;
    }
    .template-choice-card:hover {
        border-color: #93c5fd !important;
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.09);
    }
    .template-choice-card.selected-template {
        border-color: #2563eb !important;
        background: #f8faff !important;
        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.14) !important;
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

        @if($isSuperAdmin)
        <!-- Website Homepage Template Assignment Card (Super Admin Exclusive) -->
        <div class="form-card mt-4">
            <div class="form-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-layer-group text-primary me-2"></i> Website Homepage Template Architecture</h6>
                    <small class="text-muted">Select and assign the default homepage layout theme for this administrator / store.</small>
                </div>
                <span class="badge bg-primary text-white rounded-pill px-3 py-1.5 font-weight-semibold" id="activeTemplateBadge" style="font-size: 11px;">
                    Assigned: Template {{ $userTemplateId ?? '1' }}
                </span>
            </div>
            <div class="card-body p-4">
                <input type="hidden" name="template_id" id="selectedUserTemplate" value="{{ $userTemplateId ?? '1' }}">

                <style>
                    .user-template-grid {
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 16px;
                    }
                    @media (max-width: 768px) {
                        .user-template-grid {
                            grid-template-columns: 1fr;
                        }
                    }
                    .user-template-card {
                        background: #ffffff;
                        border-radius: 14px;
                        border: 2px solid #e2e8f0;
                        padding: 14px 16px;
                        cursor: pointer;
                        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
                        position: relative;
                        display: flex;
                        flex-direction: column;
                        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
                    }
                    .user-template-card:hover {
                        transform: translateY(-2px);
                        border-color: #93c5fd;
                        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.1);
                    }
                    .user-template-card.active-template-card {
                        border-color: #2563eb !important;
                        background: #ffffff;
                        box-shadow: 0 8px 24px rgba(37, 99, 235, 0.16) !important;
                    }
                    .user-template-card.active-template-card::before {
                        content: 'ACTIVE';
                        position: absolute;
                        top: -9px;
                        right: 14px;
                        background: linear-gradient(135deg, #2563eb, #1d4ed8);
                        color: #ffffff;
                        font-size: 8px;
                        font-weight: 800;
                        letter-spacing: 0.8px;
                        padding: 2px 8px;
                        border-radius: 20px;
                        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
                    }
                    .tpl-preview-box {
                        height: 95px;
                        border-radius: 8px;
                        overflow: hidden;
                        position: relative;
                        margin-bottom: 10px;
                        border: 1px solid #e2e8f0;
                        box-shadow: inset 0 1px 4px rgba(0,0,0,0.03);
                        display: flex;
                        flex-direction: column;
                        justify-content: space-between;
                        padding: 6px;
                    }
                </style>

                <div class="user-template-grid">
                    <!-- Template 1 -->
                    <div class="user-template-card {{ ($userTemplateId ?? '1') == '1' ? 'active-template-card' : '' }}" onclick="selectUserTemplate('1', this)">
                        <div class="tpl-preview-box" style="background: #f8fafc; border-color: #fed7aa;">
                            <div style="height: 10px; background: #ea580c; border-radius: 3px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px;">
                                <div style="width: 18px; height: 3px; background: #ffffff; border-radius: 1px;"></div>
                                <div style="display: flex; gap: 3px;">
                                    <div style="width: 14px; height: 3px; background: rgba(255,255,255,0.8); border-radius: 1px;"></div>
                                    <div style="width: 14px; height: 3px; background: rgba(255,255,255,0.8); border-radius: 1px;"></div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 4px; flex: 1; margin: 4px 0;">
                                <div style="width: 26%; background: #ffffff; border: 1px solid #fed7aa; border-radius: 4px; padding: 2px; display: flex; flex-direction: column; justify-content: space-around;">
                                    <div style="height: 3px; background: #fdba74; border-radius: 1px;"></div>
                                    <div style="height: 3px; background: #e2e8f0; border-radius: 1px;"></div>
                                    <div style="height: 3px; background: #e2e8f0; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; background: linear-gradient(135deg, #ea580c, #f97316); border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff; padding: 2px;">
                                    <span style="font-size: 7.5px; font-weight: 800; letter-spacing: 0.3px;">MARKETPLACE</span>
                                    <span style="font-size: 6px; opacity: 0.9;">Drawer + Slider</span>
                                </div>
                            </div>
                            <div style="display: flex; gap: 4px;">
                                <div style="flex: 1; height: 20px; background: #fff; border: 1px solid #fed7aa; border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 8px; background: #ffedd5; border-radius: 1px;"></div>
                                    <div style="height: 3px; width: 60%; background: #ea580c; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 20px; background: #fff; border: 1px solid #fed7aa; border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 8px; background: #ffedd5; border-radius: 1px;"></div>
                                    <div style="height: 3px; width: 60%; background: #ea580c; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 20px; background: #fff; border: 1px solid #fed7aa; border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 8px; background: #ffedd5; border-radius: 1px;"></div>
                                    <div style="height: 3px; width: 60%; background: #ea580c; border-radius: 1px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 1: Classic Marketplace</h6>
                            <span class="badge bg-warning text-dark font-weight-bold px-2 py-0.5" style="font-size: 9px; border-radius: 4px;">Default</span>
                        </div>
                        <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                            Daraz-style multi-category architecture with category menu drawer & wide carousel.
                        </p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <button type="button" class="btn btn-sm {{ ($userTemplateId ?? '1') == '1' ? 'btn-success text-white' : 'btn-light text-secondary border' }} px-2.5 py-1 rounded-pill template-status-btn" style="font-size: 11px; font-weight: 600;">
                                {{ ($userTemplateId ?? '1') == '1' ? '✓ Currently Active' : 'Select Template' }}
                            </button>
                            <a href="{{ url('/') }}?preview_template=1" target="_blank" onclick="event.stopPropagation();"
                               class="btn btn-sm btn-outline-primary px-3 py-1 rounded-pill font-weight-semibold" style="font-size: 11px;">
                                <i class="fas fa-external-link-alt me-1"></i> Preview
                            </a>
                        </div>
                    </div>

                    <!-- Template 2 -->
                    <div class="user-template-card {{ ($userTemplateId ?? '1') == '2' ? 'active-template-card' : '' }}" onclick="selectUserTemplate('2', this)">
                        <div class="tpl-preview-box" style="background: #0a0a0a; border-color: #d4af37;">
                            <div style="height: 10px; background: #171717; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px; border-bottom: 1px solid rgba(212,175,55,0.4);">
                                <div style="width: 18px; height: 3px; background: #d4af37; border-radius: 1px;"></div>
                                <div style="display: flex; gap: 3px;">
                                    <div style="width: 10px; height: 2px; background: rgba(255,255,255,0.6); border-radius: 1px;"></div>
                                </div>
                            </div>
                            <div style="flex: 1; background: linear-gradient(135deg, #1e1b18, #2a2012); border-radius: 4px; margin: 4px 0; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #d4af37; border: 1px solid rgba(212,175,55,0.35);">
                                <span style="font-size: 7.5px; font-weight: 800; letter-spacing: 0.8px;">MODERN BOUTIQUE</span>
                                <span style="font-size: 6px; color: rgba(255,255,255,0.7);">Full-Width Canvas • Top Trust Bar</span>
                            </div>
                            <div style="display: flex; gap: 4px;">
                                <div style="flex: 1; height: 20px; background: #141414; border: 1px solid rgba(212,175,55,0.25); border-radius: 3px; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 5.5px; color: #d4af37;">🚚 Free Delivery</span>
                                </div>
                                <div style="flex: 1; height: 20px; background: #141414; border: 1px solid rgba(212,175,55,0.25); border-radius: 3px; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 5.5px; color: #d4af37;">🔒 Secure</span>
                                </div>
                                <div style="flex: 1; height: 20px; background: #141414; border: 1px solid rgba(212,175,55,0.25); border-radius: 3px; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 5.5px; color: #d4af37;">⭐ Premium</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 2: Modern Minimal</h6>
                            <span class="badge bg-dark text-warning font-weight-bold px-2 py-0.5" style="font-size: 9px; border-radius: 4px; border: 1px solid #d4af37;">Luxury</span>
                        </div>
                        <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                            High-end boutique look with gold accents, edge-to-edge slider & top trust strip.
                        </p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <button type="button" class="btn btn-sm {{ ($userTemplateId ?? '1') == '2' ? 'btn-success text-white' : 'btn-light text-secondary border' }} px-2.5 py-1 rounded-pill template-status-btn" style="font-size: 11px; font-weight: 600;">
                                {{ ($userTemplateId ?? '1') == '2' ? '✓ Currently Active' : 'Select Template' }}
                            </button>
                            <a href="{{ url('/') }}?preview_template=2" target="_blank" onclick="event.stopPropagation();"
                               class="btn btn-sm btn-outline-dark px-3 py-1 rounded-pill font-weight-semibold" style="font-size: 11px;">
                                <i class="fas fa-external-link-alt me-1"></i> Preview
                            </a>
                        </div>
                    </div>

                    <!-- Template 3 -->
                    <div class="user-template-card {{ ($userTemplateId ?? '1') == '3' ? 'active-template-card' : '' }}" onclick="selectUserTemplate('3', this)">
                        <div class="tpl-preview-box" style="background: #080c14; border-color: #38bdf8;">
                            <div style="height: 10px; background: #0d1322; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px; border-bottom: 1px solid rgba(56, 189, 248, 0.4);">
                                <div style="width: 18px; height: 3px; background: #38bdf8; border-radius: 1px;"></div>
                                <div style="display: flex; gap: 3px;">
                                    <div style="width: 10px; height: 2px; background: #64748b; border-radius: 1px;"></div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 4px; flex: 1; margin: 4px 0;">
                                <div style="flex: 2; background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; border: 1px solid #38bdf8;">
                                    <span style="color: #38bdf8; font-size: 7.5px; font-weight: 800;">⚡ TECH HUB</span>
                                    <span style="color: #94a3b8; font-size: 5.5px;">Gadget Spec Grids</span>
                                </div>
                                <div style="flex: 1; display: flex; flex-direction: column; gap: 2px;">
                                    <div style="flex: 1; background: #0d1322; border-radius: 2px; border: 1px solid rgba(56,189,248,0.3); display: flex; align-items: center; justify-content: center;">
                                        <span style="font-size: 5px; color: #38bdf8;">Side 1</span>
                                    </div>
                                    <div style="flex: 1; background: #0d1322; border-radius: 2px; border: 1px solid rgba(56,189,248,0.3); display: flex; align-items: center; justify-content: center;">
                                        <span style="font-size: 5px; color: #38bdf8;">Side 2</span>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 4px;">
                                <div style="flex: 1; height: 20px; background: #0d1322; border: 1px solid rgba(56, 189, 248, 0.25); border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 8px; background: #1e293b; border-radius: 1px;"></div>
                                    <div style="height: 3px; width: 60%; background: #38bdf8; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 20px; background: #0d1322; border: 1px solid rgba(56, 189, 248, 0.25); border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 8px; background: #1e293b; border-radius: 1px;"></div>
                                    <div style="height: 3px; width: 60%; background: #38bdf8; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 20px; background: #0d1322; border: 1px solid rgba(56, 189, 248, 0.25); border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 8px; background: #1e293b; border-radius: 1px;"></div>
                                    <div style="height: 3px; width: 60%; background: #38bdf8; border-radius: 1px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 3: Electronic & Tech Hub</h6>
                            <span class="badge bg-info text-white font-weight-bold px-2 py-0.5" style="font-size: 9px; border-radius: 4px; background: #0284c7 !important;">Cyber Tech</span>
                        </div>
                        <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                            Cyberpunk dark theme, cyan glow, 2.5:1 hero with 2-row rotating promo deals.
                        </p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <button type="button" class="btn btn-sm {{ ($userTemplateId ?? '1') == '3' ? 'btn-success text-white' : 'btn-light text-secondary border' }} px-2.5 py-1 rounded-pill template-status-btn" style="font-size: 11px; font-weight: 600;">
                                {{ ($userTemplateId ?? '1') == '3' ? '✓ Currently Active' : 'Select Template' }}
                            </button>
                            <a href="{{ url('/') }}?preview_template=3" target="_blank" onclick="event.stopPropagation();"
                               class="btn btn-sm btn-outline-info px-3 py-1 rounded-pill font-weight-semibold" style="font-size: 11px;">
                                <i class="fas fa-external-link-alt me-1"></i> Preview
                            </a>
                        </div>
                    </div>

                    <!-- Template 4 -->
                    <div class="user-template-card {{ ($userTemplateId ?? '1') == '4' ? 'active-template-card' : '' }}" onclick="selectUserTemplate('4', this)">
                        <div class="tpl-preview-box" style="background: #0f1115; border-color: #e11d48;">
                            <div style="height: 10px; background: #1e1117; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px; border-bottom: 1px solid rgba(225, 29, 72, 0.4);">
                                <div style="width: 20px; height: 3px; background: #e11d48; border-radius: 1px;"></div>
                                <div style="display: flex; gap: 2px;">
                                    <div style="padding: 1px 3px; background: #e11d48; color: #fff; font-size: 5px; border-radius: 1px; font-weight: 800;">SALE</div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 4px; flex: 1; margin: 4px 0;">
                                <div style="flex: 2; background: linear-gradient(135deg, #881337, #be123c); border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff; border: 1px solid #f43f5e;">
                                    <span style="font-size: 7.5px; font-weight: 900;">🔥 FLASH SALE</span>
                                    <span style="font-size: 5.5px; color: #fecdd3;">Live Deal Timers</span>
                                </div>
                                <div style="flex: 1; display: flex; flex-direction: column; gap: 2px;">
                                    <div style="flex: 1; background: #1e1117; border-radius: 2px; border: 1px solid rgba(244,63,94,0.3); display: flex; align-items: center; justify-content: center;">
                                        <span style="font-size: 5px; color: #fb7185;">Deal 1</span>
                                    </div>
                                    <div style="flex: 1; background: #1e1117; border-radius: 2px; border: 1px solid rgba(244,63,94,0.3); display: flex; align-items: center; justify-content: center;">
                                        <span style="font-size: 5px; color: #fb7185;">Deal 2</span>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 4px;">
                                <div style="flex: 1; height: 20px; background: #1e1117; border: 1px solid rgba(225, 29, 72, 0.25); border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 8px; background: #33131d; border-radius: 1px;"></div>
                                    <div style="height: 3px; width: 60%; background: #e11d48; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 20px; background: #1e1117; border: 1px solid rgba(225, 29, 72, 0.25); border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 8px; background: #33131d; border-radius: 1px;"></div>
                                    <div style="height: 3px; width: 60%; background: #e11d48; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 20px; background: #1e1117; border: 1px solid rgba(225, 29, 72, 0.25); border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 8px; background: #33131d; border-radius: 1px;"></div>
                                    <div style="height: 3px; width: 60%; background: #e11d48; border-radius: 1px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 4: Flash Sale</h6>
                            <span class="badge bg-danger text-white font-weight-bold px-2 py-0.5" style="font-size: 9px; border-radius: 4px;">Conversion</span>
                        </div>
                        <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                            High-conversion urgency architecture with animated sale marquee & flash deal rows.
                        </p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <button type="button" class="btn btn-sm {{ ($userTemplateId ?? '1') == '4' ? 'btn-success text-white' : 'btn-light text-secondary border' }} px-2.5 py-1 rounded-pill template-status-btn" style="font-size: 11px; font-weight: 600;">
                                {{ ($userTemplateId ?? '1') == '4' ? '✓ Currently Active' : 'Select Template' }}
                            </button>
                            <a href="{{ url('/') }}?preview_template=4" target="_blank" onclick="event.stopPropagation();"
                               class="btn btn-sm btn-outline-danger px-3 py-1 rounded-pill font-weight-semibold" style="font-size: 11px;">
                                <i class="fas fa-external-link-alt me-1"></i> Preview
                            </a>
                        </div>
                    </div>

                    <!-- Template 5 -->
                    <div class="user-template-card {{ ($userTemplateId ?? '1') == '5' ? 'active-template-card' : '' }}" onclick="selectUserTemplate('5', this)">
                        <div class="tpl-preview-box" style="background: #f0fdf4; border-color: #86efac;">
                            <div style="height: 10px; background: #15803d; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px;">
                                <div style="width: 18px; height: 3px; background: #ffffff; border-radius: 1px;"></div>
                                <div style="display: flex; gap: 3px;">
                                    <div style="width: 10px; height: 2px; background: rgba(255,255,255,0.7); border-radius: 1px;"></div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 3px; margin: 3px 0;">
                                <div style="flex: 1; height: 9px; background: #dcfce7; border-radius: 6px; border: 1px solid #86efac; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 4.5px; color: #15803d; font-weight: 700;">Organic</span>
                                </div>
                                <div style="flex: 1; height: 9px; background: #dcfce7; border-radius: 6px; border: 1px solid #86efac; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 4.5px; color: #15803d; font-weight: 700;">Fruits</span>
                                </div>
                                <div style="flex: 1; height: 9px; background: #dcfce7; border-radius: 6px; border: 1px solid #86efac; display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 4.5px; color: #15803d; font-weight: 700;">Dairy</span>
                                </div>
                            </div>
                            <div style="flex: 1; background: linear-gradient(135deg, #16a34a, #15803d); border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff; margin-bottom: 3px;">
                                <span style="font-size: 7.5px; font-weight: 800;">🌿 FRESH GROCERY</span>
                                <span style="font-size: 5.5px; opacity: 0.9;">Farm Fresh Produce</span>
                            </div>
                            <div style="display: flex; gap: 4px;">
                                <div style="flex: 1; height: 20px; background: #fff; border: 1px solid #86efac; border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 8px; background: #dcfce7; border-radius: 1px;"></div>
                                    <div style="height: 3px; width: 60%; background: #16a34a; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 20px; background: #fff; border: 1px solid #86efac; border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 8px; background: #dcfce7; border-radius: 1px;"></div>
                                    <div style="height: 3px; width: 60%; background: #16a34a; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 20px; background: #fff; border: 1px solid #86efac; border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 8px; background: #dcfce7; border-radius: 1px;"></div>
                                    <div style="height: 3px; width: 60%; background: #16a34a; border-radius: 1px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 5: Grocery & Fresh Express</h6>
                            <span class="badge bg-success text-white font-weight-bold px-2 py-0.5" style="font-size: 9px; border-radius: 4px;">Organic</span>
                        </div>
                        <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                            Fresh eco-friendly grocery layout with category pill tabs & full-width carousel.
                        </p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <button type="button" class="btn btn-sm {{ ($userTemplateId ?? '1') == '5' ? 'btn-success text-white' : 'btn-light text-secondary border' }} px-2.5 py-1 rounded-pill template-status-btn" style="font-size: 11px; font-weight: 600;">
                                {{ ($userTemplateId ?? '1') == '5' ? '✓ Currently Active' : 'Select Template' }}
                            </button>
                            <a href="{{ url('/') }}?preview_template=5" target="_blank" onclick="event.stopPropagation();"
                               class="btn btn-sm btn-outline-success px-3 py-1 rounded-pill font-weight-semibold" style="font-size: 11px;">
                                <i class="fas fa-external-link-alt me-1"></i> Preview
                            </a>
                        </div>
                    </div>

                    <!-- Template 6: Fashion & Apparel Studio -->
                    <div class="user-template-card {{ ($userTemplateId ?? '1') == '6' ? 'active-template-card' : '' }}" onclick="selectUserTemplate('6', this)">
                        <div class="tpl-preview-box" style="background: #fff1f2; border-color: #fecdd3;">
                            <div style="height: 10px; background: #be123c; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px;">
                                <div style="width: 22px; height: 3px; background: #ffffff; border-radius: 1px;"></div>
                                <div style="width: 14px; height: 2px; background: #fde047; border-radius: 1px;"></div>
                            </div>
                            <div style="display: flex; gap: 3px; margin: 3px 0;">
                                <div style="flex: 2; height: 24px; background: linear-gradient(135deg, #e11d48, #9f1239); border-radius: 3px; padding: 2px 4px; display: flex; flex-direction: column; justify-content: center; color: #fff;">
                                    <span style="font-size: 6px; font-weight: 800;">👗 COUTURE LOOKBOOK</span>
                                    <span style="font-size: 4px; opacity: 0.85;">Men & Women Atelier</span>
                                </div>
                                <div style="flex: 1; height: 24px; display: flex; flex-direction: column; gap: 2px;">
                                    <div style="flex: 1; background: #ffe4e6; border: 1px solid #fecdd3; border-radius: 2px;"></div>
                                    <div style="flex: 1; background: #ffe4e6; border: 1px solid #fecdd3; border-radius: 2px;"></div>
                                </div>
                            </div>
                            <div style="display: flex; gap: 3px;">
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #fecdd3; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #ffe4e6; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 50%; background: #be123c; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #fecdd3; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #ffe4e6; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 50%; background: #be123c; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #fecdd3; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #ffe4e6; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 50%; background: #be123c; border-radius: 1px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 6: Fashion & Apparel Studio</h6>
                            <span class="badge bg-danger text-white font-weight-bold px-2 py-0.5" style="font-size: 9px; border-radius: 4px; background: #e11d48 !important;">Couture</span>
                        </div>
                        <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                            High-fashion clothing lookbook with Men/Women split cards & shoppable Instagram gallery.
                        </p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <button type="button" class="btn btn-sm {{ ($userTemplateId ?? '1') == '6' ? 'btn-success text-white' : 'btn-light text-secondary border' }} px-2.5 py-1 rounded-pill template-status-btn" style="font-size: 11px; font-weight: 600;">
                                {{ ($userTemplateId ?? '1') == '6' ? '✓ Currently Active' : 'Select Template' }}
                            </button>
                            <a href="{{ url('/') }}?preview_template=6" target="_blank" onclick="event.stopPropagation();"
                               class="btn btn-sm btn-outline-danger px-3 py-1 rounded-pill font-weight-semibold" style="font-size: 11px;">
                                <i class="fas fa-external-link-alt me-1"></i> Preview
                            </a>
                        </div>
                    </div>

                    <!-- Template 7: Beauty & Cosmetics Glow -->
                    <div class="user-template-card {{ ($userTemplateId ?? '1') == '7' ? 'active-template-card' : '' }}" onclick="selectUserTemplate('7', this)">
                        <div class="tpl-preview-box" style="background: #fdf2f8; border-color: #fbcfe8;">
                            <div style="height: 10px; background: linear-gradient(135deg, #f43f5e, #ec4899); border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px;">
                                <div style="width: 20px; height: 3px; background: #ffffff; border-radius: 1px;"></div>
                                <div style="width: 12px; height: 2px; background: #ffffff; border-radius: 1px;"></div>
                            </div>
                            <div style="flex: 1; background: #fff; border-radius: 3px; margin: 3px 0; border: 1px solid #fbcfe8; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #be123c;">
                                <span style="font-size: 6.5px; font-weight: 800;">🌸 GLOW COSMETICS</span>
                                <span style="font-size: 4.5px; color: #db2777;">Skincare • Vegan Badges</span>
                            </div>
                            <div style="display: flex; gap: 3px;">
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #fbcfe8; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #fce7f3; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 60%; background: #f43f5e; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #fbcfe8; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #fce7f3; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 60%; background: #f43f5e; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #fbcfe8; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #fce7f3; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 60%; background: #f43f5e; border-radius: 1px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 7: Beauty & Cosmetics</h6>
                            <span class="badge bg-danger text-white font-weight-bold px-2 py-0.5" style="font-size: 9px; border-radius: 4px; background: #ec4899 !important;">Glow</span>
                        </div>
                        <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                            Pastel luxury cosmetic store with vegan trust badges & skincare category routines.
                        </p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <button type="button" class="btn btn-sm {{ ($userTemplateId ?? '1') == '7' ? 'btn-success text-white' : 'btn-light text-secondary border' }} px-2.5 py-1 rounded-pill template-status-btn" style="font-size: 11px; font-weight: 600;">
                                {{ ($userTemplateId ?? '1') == '7' ? '✓ Currently Active' : 'Select Template' }}
                            </button>
                            <a href="{{ url('/') }}?preview_template=7" target="_blank" onclick="event.stopPropagation();"
                               class="btn btn-sm btn-outline-danger px-3 py-1 rounded-pill font-weight-semibold" style="font-size: 11px;">
                                <i class="fas fa-external-link-alt me-1"></i> Preview
                            </a>
                        </div>
                    </div>

                    <!-- Template 8: Mega Supermarket & Daily Essentials -->
                    <div class="user-template-card {{ ($userTemplateId ?? '1') == '8' ? 'active-template-card' : '' }}" onclick="selectUserTemplate('8', this)">
                        <div class="tpl-preview-box" style="background: #f0fdf4; border-color: #bbf7d0;">
                            <div style="height: 10px; background: #15803d; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px;">
                                <div style="width: 20px; height: 3px; background: #ffffff; border-radius: 1px;"></div>
                                <div style="width: 12px; height: 2px; background: #fef08a; border-radius: 1px;"></div>
                            </div>
                            <div style="flex: 1; background: #fff; border-radius: 3px; margin: 3px 0; border: 1px solid #bbf7d0; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #15803d;">
                                <span style="font-size: 6.5px; font-weight: 800;">🥬 MEGA SUPERMARKET</span>
                                <span style="font-size: 4.5px; color: #16a34a;">45-Min Fast Delivery • Aisle Pills</span>
                            </div>
                            <div style="display: flex; gap: 3px;">
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #bbf7d0; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #dcfce7; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 60%; background: #16a34a; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #bbf7d0; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #dcfce7; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 60%; background: #16a34a; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #bbf7d0; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #dcfce7; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 60%; background: #16a34a; border-radius: 1px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 8: Mega Supermarket</h6>
                            <span class="badge bg-success text-white font-weight-bold px-2 py-0.5" style="font-size: 9px; border-radius: 4px;">Express</span>
                        </div>
                        <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                            Comprehensive supermarket & grocery store layout with aisle categories & quick checkout triggers.
                        </p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <button type="button" class="btn btn-sm {{ ($userTemplateId ?? '1') == '8' ? 'btn-success text-white' : 'btn-light text-secondary border' }} px-2.5 py-1 rounded-pill template-status-btn" style="font-size: 11px; font-weight: 600;">
                                {{ ($userTemplateId ?? '1') == '8' ? '✓ Currently Active' : 'Select Template' }}
                            </button>
                            <a href="{{ url('/') }}?preview_template=8" target="_blank" onclick="event.stopPropagation();"
                               class="btn btn-sm btn-outline-success px-3 py-1 rounded-pill font-weight-semibold" style="font-size: 11px;">
                                <i class="fas fa-external-link-alt me-1"></i> Preview
                            </a>
                        </div>
                    </div>

                    <!-- Template 9: Books & Heritage Store -->
                    <div class="user-template-card {{ ($userTemplateId ?? '1') == '9' ? 'active-template-card' : '' }}" onclick="selectUserTemplate('9', this)">
                        <div class="tpl-preview-box" style="background: #fdfaf4; border-color: #fde68a;">
                            <div style="height: 10px; background: #0f172a; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px;">
                                <div style="width: 20px; height: 3px; background: #fbbf24; border-radius: 1px;"></div>
                                <div style="width: 12px; height: 2px; background: #ffffff; border-radius: 1px;"></div>
                            </div>
                            <div style="flex: 1; background: #1e293b; border-radius: 3px; margin: 3px 0; border: 1px solid #fde68a; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fbbf24;">
                                <span style="font-size: 6.5px; font-weight: 800;">📖 BOOKSTORE & ACADEMY</span>
                                <span style="font-size: 4.5px; color: #fde68a;">Original Prints • Genre Shelves</span>
                            </div>
                            <div style="display: flex; gap: 3px;">
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #fde68a; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #fef3c7; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 60%; background: #d97706; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #fde68a; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #fef3c7; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 60%; background: #d97706; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #fde68a; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #fef3c7; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 60%; background: #d97706; border-radius: 1px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 9: Books & Heritage Store</h6>
                            <span class="badge bg-warning text-dark font-weight-bold px-2 py-0.5" style="font-size: 9px; border-radius: 4px;">Library</span>
                        </div>
                        <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                            Classic bookstore and academic library theme with genre book shelves & protective packaging guarantees.
                        </p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <button type="button" class="btn btn-sm {{ ($userTemplateId ?? '1') == '9' ? 'btn-success text-white' : 'btn-light text-secondary border' }} px-2.5 py-1 rounded-pill template-status-btn" style="font-size: 11px; font-weight: 600;">
                                {{ ($userTemplateId ?? '1') == '9' ? '✓ Currently Active' : 'Select Template' }}
                            </button>
                            <a href="{{ url('/') }}?preview_template=9" target="_blank" onclick="event.stopPropagation();"
                               class="btn btn-sm btn-outline-warning px-3 py-1 rounded-pill font-weight-semibold" style="font-size: 11px; color: #d97706; border-color: #d97706;">
                                <i class="fas fa-external-link-alt me-1"></i> Preview
                            </a>
                        </div>
                    </div>

                    <!-- Template 10: Home Living & Furniture -->
                    <div class="user-template-card {{ ($userTemplateId ?? '1') == '10' ? 'active-template-card' : '' }}" onclick="selectUserTemplate('10', this)">
                        <div class="tpl-preview-box" style="background: #fafaf9; border-color: #fed7aa;">
                            <div style="height: 10px; background: #44403c; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px;">
                                <div style="width: 20px; height: 3px; background: #fdba74; border-radius: 1px;"></div>
                                <div style="width: 12px; height: 2px; background: #ffffff; border-radius: 1px;"></div>
                            </div>
                            <div style="flex: 1; background: #292524; border-radius: 3px; margin: 3px 0; border: 1px solid #fed7aa; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fdba74;">
                                <span style="font-size: 6.5px; font-weight: 800;">🛋️ HOME & FURNITURE</span>
                                <span style="font-size: 4.5px; color: #fed7aa;">Shop by Room • 10-Yr Warranty</span>
                            </div>
                            <div style="display: flex; gap: 3px;">
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #fed7aa; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #ffedd5; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 60%; background: #ea580c; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #fed7aa; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #ffedd5; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 60%; background: #ea580c; border-radius: 1px;"></div>
                                </div>
                                <div style="flex: 1; height: 16px; background: #fff; border: 1px solid #fed7aa; border-radius: 2px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div style="height: 6px; background: #ffedd5; border-radius: 1px;"></div>
                                    <div style="height: 2px; width: 60%; background: #ea580c; border-radius: 1px;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 10: Home Living & Furniture</h6>
                            <span class="badge bg-warning text-dark font-weight-bold px-2 py-0.5" style="font-size: 9px; border-radius: 4px; background: #ea580c !important; color: #fff !important;">Living</span>
                        </div>
                        <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                            Warm artisan furniture & decor layout with interactive Shop-by-Room grid & material warranty badges.
                        </p>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <button type="button" class="btn btn-sm {{ ($userTemplateId ?? '1') == '10' ? 'btn-success text-white' : 'btn-light text-secondary border' }} px-2.5 py-1 rounded-pill template-status-btn" style="font-size: 11px; font-weight: 600;">
                                {{ ($userTemplateId ?? '1') == '10' ? '✓ Currently Active' : 'Select Template' }}
                            </button>
                            <a href="{{ url('/') }}?preview_template=10" target="_blank" onclick="event.stopPropagation();"
                               class="btn btn-sm btn-outline-warning px-3 py-1 rounded-pill font-weight-semibold" style="font-size: 11px; color: #ea580c; border-color: #ea580c;">
                                <i class="fas fa-external-link-alt me-1"></i> Preview
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Footer Bar -->
        <div class="card border-0 shadow-lg rounded-4 mt-4 bg-white p-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary rounded-pill px-5 shadow font-weight-bold py-2">
                    <i class="fas fa-save me-2"></i> Update Member Profile & Template
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

    function selectUserTemplate(templateId, cardEl) {
        const hiddenInput = document.getElementById('selectedUserTemplate');
        if (hiddenInput) {
            hiddenInput.value = templateId;
        }

        const badge = document.getElementById('activeTemplateBadge');
        if (badge) {
            badge.innerText = 'Assigned: Template ' + templateId;
        }

        document.querySelectorAll('.user-template-card').forEach(card => {
            card.classList.remove('active-template-card');
            const statusBtn = card.querySelector('.template-status-btn');
            if (statusBtn) {
                statusBtn.className = 'btn btn-sm btn-light text-secondary border px-2.5 py-1 rounded-pill template-status-btn';
                statusBtn.innerText = 'Select Template';
            }
        });

        if (cardEl) {
            cardEl.classList.add('active-template-card');
            const statusBtn = cardEl.querySelector('.template-status-btn');
            if (statusBtn) {
                statusBtn.className = 'btn btn-sm btn-success text-white px-2.5 py-1 rounded-pill template-status-btn';
                statusBtn.innerText = '✓ Currently Active';
            }
        }
    }
</script>
@endsection