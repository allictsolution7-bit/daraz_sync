@extends('vendor.layouts.app')

@section('title', 'Reseller Hub')

@section('content')
<!-- Hero Welcome Banner -->
<div class="hero-banner mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 position-relative style-z-1">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge text-white rounded-pill px-3 py-1 fw-bold fs-7" style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.3); backdrop-filter: blur(5px);">
                    <i class="fas fa-handshake me-1 text-warning"></i> Reseller Partner Hub
                </span>
                <span class="badge text-white rounded-pill px-3 py-1 fw-semibold fs-7" style="background: #10b981;">
                    <i class="fas fa-circle me-1" style="font-size: 0.5rem; vertical-align: middle;"></i> Authorized Reseller
                </span>
            </div>
            <h2 class="fw-extrabold text-white mb-1">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-white opacity-75 mb-0">Here is your portal to browse shared admin products, copy content, and download media assets.</p>
        </div>
        <div>
            <a href="{{ route('vendor.products.index') }}" class="btn btn-warning fw-bold rounded-pill px-4 py-2 text-dark shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-boxes"></i> Browse Product Catalog
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Total Shared Products -->
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="text-muted fw-semibold fs-7 text-uppercase tracking-wider">Shared Products</span>
                    <h2 class="fw-extrabold text-dark mt-2 mb-0">{{ $totalAdminProducts }}</h2>
                    <p class="text-muted small mt-2 mb-0">Products available for you to sell.</p>
                </div>
                <div class="stat-icon primary bg-primary bg-opacity-10 text-primary" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-cubes fs-4"></i>
                </div>
            </div>
            <div class="mt-4 pt-3 border-top">
                <a href="{{ route('vendor.products.index') }}" class="text-primary fw-bold text-decoration-none fs-7">
                    View Catalog <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Assigned Admin Details -->
    <div class="col-12 col-md-6 col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
            <h5 class="fw-extrabold text-dark mb-3"><i class="fas fa-user-tie text-primary me-2"></i>My Reseller Admin</h5>
            @php
                $adminUser = \App\Models\User::find(auth()->user()->created_by);
            @endphp
            @if($adminUser)
                <div class="d-flex align-items-center gap-3 mt-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 50px; height: 50px; font-size: 1.2rem;">
                        {{ strtoupper(substr($adminUser->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ $adminUser->name }}</h6>
                        <span class="text-muted small d-block">{{ $adminUser->email }}</span>
                        @if($adminUser->phone)
                            <span class="text-muted small d-block"><i class="fas fa-phone me-1.5 fs-8"></i>{{ $adminUser->phone }}</span>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-muted mb-0">No specific Admin is assigned to your account. Contact system administrator.</p>
            @endif
        </div>
    </div>
</div>
@endsection
