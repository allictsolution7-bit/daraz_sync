@extends('layouts.master')

@section('title', 'Member Profile Details - Admin Panel')

@section('styles')
<style>
    .show-hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 20px;
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
    }
    .profile-avatar-wrapper {
        position: relative;
        width: 90px;
        height: 90px;
    }
    .profile-avatar-img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 3.5px solid #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .avatar-status-dot {
        position: absolute;
        bottom: 3px;
        right: 3px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #10b981;
        border: 3.5px solid #0f172a;
    }
    .info-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }
    .info-card-header {
        background-color: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 20px;
        border-top-left-radius: 18px;
        border-top-right-radius: 18px;
    }
    .info-item {
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
    }
    .info-label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 13.5px;
        font-weight: 600;
        color: #0f172a;
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
    .stat-mini-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}" class="text-decoration-none text-muted"><i class="fas fa-home me-1"></i> Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}" class="text-decoration-none text-muted">Team Members</a></li>
                    <li class="breadcrumb-item active text-primary font-weight-semibold" aria-current="page">Member Profile Details</li>
                </ol>
            </nav>
            <h4 class="font-weight-bold text-dark mb-0"><i class="fas fa-user-id text-primary me-2"></i> Member Profile Overview</h4>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Directory
            </a>
            <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}" class="btn btn-primary rounded-pill px-4 shadow-sm btn-sm">
                <i class="fas fa-pen-to-square me-1"></i> Edit Profile
            </a>
        </div>
    </div>

    <!-- Hero Profile Card -->
    <div class="show-hero-card p-4 mb-4">
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
                            <span class="badge bg-success text-white rounded-pill px-2.5 py-1" style="font-size: 10px;"><i class="fas fa-shield-check me-1"></i> Verified Account</span>
                        @else
                            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1" style="font-size: 10px;"><i class="fas fa-triangle-exclamation me-1"></i> Unverified</span>
                        @endif
                    </div>
                    <p class="mb-0 text-white-50 small"><i class="fas fa-envelope me-1"></i> {{ $user->email }} &bull; <i class="fas fa-calendar-alt ms-2 me-1"></i> Registered: {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                @foreach($user->getRoleNames() as $role)
                    <span class="badge bg-primary text-white shadow-sm px-3 py-2 rounded-pill font-weight-bold" style="font-size: 12px;">
                        <i class="fas fa-user-shield me-1"></i> {{ ucfirst($role) }}
                    </span>
                @endforeach
                @if($user->getRoleNames()->isEmpty())
                    <span class="badge bg-secondary text-white shadow-sm px-3 py-2 rounded-pill font-weight-bold" style="font-size: 12px;">
                        <i class="fas fa-user me-1"></i> Standard Customer
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="stat-mini-card d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 text-primary bg-light">
                    <i class="fas fa-user-shield fs-4"></i>
                </div>
                <div>
                    <div class="info-label">Assigned Role</div>
                    <div class="info-value text-primary">{{ ucfirst($user->getRoleNames()->first() ?? $user->role ?? 'Customer') }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-mini-card d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 text-success bg-light">
                    <i class="fas fa-shield-check fs-4"></i>
                </div>
                <div>
                    <div class="info-label">Phone Status</div>
                    <div class="info-value text-success">{{ $user->otp_verified ? 'Verified' : 'Unverified' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-mini-card d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 text-warning bg-light">
                    <i class="fas fa-calendar-check fs-4"></i>
                </div>
                <div>
                    <div class="info-label">Joined Date</div>
                    <div class="info-value">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-mini-card d-flex align-items-center gap-3">
                <div class="rounded-3 p-3 text-info bg-light">
                    <i class="fas fa-key fs-4"></i>
                </div>
                <div>
                    <div class="info-label">Permissions</div>
                    <div class="info-value text-info">{{ method_exists($user, 'getAllPermissions') ? $user->getAllPermissions()->count() : 'System Default' }} items</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Profile Grid -->
    <div class="row g-4 mb-4">
        <!-- Personal & Contact Information -->
        <div class="col-lg-6 col-12">
            <div class="info-card h-100">
                <div class="info-card-header">
                    <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-address-card text-primary me-2"></i> Personal Details</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="info-item">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">{{ $user->name }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <div class="info-label">Primary Email Address</div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="info-item">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">{{ $user->phone ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="info-item">
                                <div class="info-label">Email Verification</div>
                                <div class="info-value">
                                    @if($user->email_verified_at)
                                        <span class="text-success"><i class="fas fa-check-circle me-1"></i> {{ $user->email_verified_at->format('M d, Y H:i') }}</span>
                                    @else
                                        <span class="text-muted"><i class="fas fa-clock me-1"></i> Pending Verification</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location & Security Audit -->
        <div class="col-lg-6 col-12">
            <div class="info-card h-100">
                <div class="info-card-header">
                    <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-map-location-dot text-danger me-2"></i> Address & Location</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="info-item">
                                <div class="info-label">Street / Detailed Address</div>
                                <div class="info-value">{{ $user->address ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="info-item">
                                <div class="info-label">Upazila / Sub-District</div>
                                <div class="info-value">{{ $user->upazila ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="info-item">
                                <div class="info-label">City / District</div>
                                <div class="info-value">{{ $user->city ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-item">
                                <div class="info-label">Last Updated Timestamp</div>
                                <div class="info-value">{{ $user->updated_at ? $user->updated_at->format('M d, Y H:i:s') : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Footer Bar -->
    <div class="card border-0 shadow-lg rounded-4 bg-white p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="fas fa-arrow-left me-1"></i> Back to Members Directory
            </a>
            <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}" class="btn btn-primary rounded-pill px-5 shadow font-weight-bold py-2">
                <i class="fas fa-pen-to-square me-2"></i> Edit Member Profile
            </a>
        </div>
    </div>
</div>
@endsection
