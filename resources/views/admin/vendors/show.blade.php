@extends('layouts.master')

@section('title', 'Partner Details - ' . $vendor->name)

@section('styles')
<style>
    .vp-page-wrapper {
        background: #f1f5f9;
        min-height: calc(100vh - 60px);
        margin: -15px -15px 0 -15px;
        padding: 24px;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }
    .vp-hero-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
        color: #ffffff;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.15);
        margin-bottom: 24px;
    }
    .vp-title-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #38bdf8;
    }
    .stat-card-glass {
        background: white;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .stat-card-glass .icon-box {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .vp-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        overflow: hidden;
    }
    .vp-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 20px;
    }
    .vp-card .card-header h5 {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
        font-size: 15px;
    }
    .badge-soft-success {
        background: #dcfce7;
        color: #15803d;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
    .badge-soft-warning {
        background: #fef3c7;
        color: #b45309;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
</style>
@endsection

@section('content')
<div class="vp-page-wrapper">
    <!-- Banner Header -->
    <div class="vp-hero-card d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="vp-title-icon">
                <i class="fas fa-store"></i>
            </div>
            <div>
                <h3 class="mb-0 text-white font-weight-bold">{{ $vendor->name }}</h3>
                <p class="mb-0 text-white-50 small">{{ $vendorSettings->business_name ?? 'Partner Account Overview' }}</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.vendors.edit', $vendor) }}" class="btn btn-warning rounded-3 px-3 text-dark font-weight-bold">
                <i class="fas fa-edit me-1"></i> Edit Partner
            </a>
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary rounded-3 px-3">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card-glass d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small uppercase font-weight-bold">Total Catalog</span>
                    <h3 class="mb-0 font-weight-bold text-dark">{{ $stats['total_products'] }}</h3>
                </div>
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-glass d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small uppercase font-weight-bold">Total Orders</span>
                    <h3 class="mb-0 font-weight-bold text-dark">{{ $stats['total_orders'] }}</h3>
                </div>
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-glass d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small uppercase font-weight-bold">Total Earnings</span>
                    <h3 class="mb-0 font-weight-bold text-dark">৳{{ number_format($stats['total_earnings'], 2) }}</h3>
                </div>
                <div class="icon-box bg-info bg-opacity-10 text-info">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-glass d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small uppercase font-weight-bold">Current Balance</span>
                    <h3 class="mb-0 font-weight-bold text-dark">৳{{ number_format($stats['current_balance'], 2) }}</h3>
                </div>
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column: Details & Tables -->
        <div class="col-lg-8">
            <!-- Card 1: Account Credentials -->
            <div class="vp-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-user-circle text-primary"></i>
                    <h5>Account Credentials</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tr>
                                <th width="30%" class="text-muted">Full Name:</th>
                                <td class="font-weight-bold text-dark">{{ $vendor->name }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Email Address:</th>
                                <td class="font-weight-bold text-dark">{{ $vendor->email }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Phone Number:</th>
                                <td>{{ $vendor->phone ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Registered Date:</th>
                                <td>{{ $vendor->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Account Status:</th>
                                <td>
                                    @if($vendorSettings->is_active)
                                        <span class="badge bg-success rounded-pill px-3">Active</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Verification Badge:</th>
                                <td>
                                    @if($vendorSettings->is_verified)
                                        <span class="badge-soft-success">
                                            <i class="fas fa-check-circle me-1"></i> Verified
                                        </span>
                                    @else
                                        <span class="badge-soft-warning me-2">
                                            <i class="fas fa-clock me-1"></i> Pending Verification
                                        </span>
                                        <form action="{{ route('admin.vendors.verify', $vendor) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-3 px-3">
                                                <i class="fas fa-check me-1"></i> Verify Now
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card 2: Business Profile -->
            <div class="vp-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-building text-primary"></i>
                    <h5>Business Profile</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tr>
                                <th width="30%" class="text-muted">Business Name:</th>
                                <td class="font-weight-bold text-dark">{{ $vendorSettings->business_name }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Business Email:</th>
                                <td>{{ $vendorSettings->business_email }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Business Phone:</th>
                                <td>{{ $vendorSettings->business_phone }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Business Address:</th>
                                <td>{{ $vendorSettings->business_address ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Default Commission:</th>
                                <td><span class="badge bg-primary rounded-pill px-3">{{ number_format($vendorSettings->getDefaultCommissionRate(), 2) }}%</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card 3: KYC & Verification Documents Review -->
            <div class="vp-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-id-card text-success"></i>
                        <h5>KYC & Identity Verification Details</h5>
                    </div>
                    @if($vendorSettings->is_verified)
                        <span class="badge-soft-success">
                            <i class="fas fa-check-circle me-1"></i> Account Verified
                        </span>
                    @elseif(!empty($vendorSettings->additional_config['verification_submitted']) || $vendorSettings->business_license_document || $vendorSettings->business_license)
                        <span class="badge-soft-warning">
                            <i class="fas fa-hourglass-half me-1"></i> Verification Pending Review
                        </span>
                    @else
                        <span class="badge bg-secondary bg-opacity-25 text-secondary px-3 py-1 rounded-pill small fw-bold">
                            <i class="fas fa-exclamation-circle me-1"></i> No Documents Submitted
                        </span>
                    @endif
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive mb-3">
                        <table class="table table-borderless align-middle mb-0">
                            <tr>
                                <th width="32%" class="text-muted">Phone Verification:</th>
                                <td>
                                    @php
                                        $phoneVerified = !empty($vendorSettings->additional_config['phone_verified']) || !empty($vendorSettings->additional_config['verified_phone_number']);
                                        $verifiedPhone = $vendorSettings->additional_config['verified_phone_number'] ?? $vendor->phone;
                                    @endphp
                                    @if($phoneVerified)
                                        <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-1 small fw-bold">
                                            <i class="fas fa-check-circle me-1"></i> OTP Verified
                                        </span>
                                        <span class="ms-2 font-weight-bold text-dark">{{ $verifiedPhone }}</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-15 text-warning rounded-pill px-2.5 py-1 small fw-bold">
                                            <i class="fas fa-times-circle me-1"></i> Not Verified
                                        </span>
                                        <span class="ms-2 text-muted">{{ $vendor->phone ?? 'No phone' }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Trade License No:</th>
                                <td>
                                    @if(!empty($vendorSettings->business_license))
                                        <span class="font-monospace fw-bold text-dark px-2 py-1 bg-light rounded border">{{ $vendorSettings->business_license }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Owner NID / Tax ID:</th>
                                <td>
                                    @if(!empty($vendorSettings->tax_id))
                                        <span class="font-monospace fw-bold text-dark px-2 py-1 bg-light rounded border">{{ $vendorSettings->tax_id }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            @if($vendorSettings->is_verified)
                                <tr>
                                    <th class="text-muted">Verified Date & Admin:</th>
                                    <td>
                                        <span class="text-dark fw-bold">{{ $vendorSettings->verified_at ? $vendorSettings->verified_at->format('d M Y, h:i A') : 'N/A' }}</span>
                                        @if($vendorSettings->verifiedBy)
                                            <span class="text-muted small ms-1">(by {{ $vendorSettings->verifiedBy->name }})</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>

                    <!-- Document Inspection Section -->
                    <div class="border rounded-3 p-3 bg-light">
                        <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2" style="font-size: 13px;">
                            <i class="fas fa-file-invoice text-primary"></i> Uploaded Trade License / Legal Document
                        </h6>
                        @if($vendorSettings->business_license_document)
                            @php
                                $docPath = $vendorSettings->business_license_document;
                                $ext = strtolower(pathinfo($docPath, PATHINFO_EXTENSION));
                                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
                                $docUrl = asset('storage/' . $docPath);
                            @endphp
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 bg-white p-3 rounded-2 border">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-2 p-2 d-flex align-items-center justify-content-center {{ $isImg ? 'bg-info bg-opacity-10 text-info' : 'bg-danger bg-opacity-10 text-danger' }}" style="width: 46px; height: 46px; font-size: 22px;">
                                        <i class="fas {{ $isImg ? 'fa-file-image' : 'fa-file-pdf' }}"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 250px;">{{ basename($docPath) }}</div>
                                        <span class="badge bg-secondary bg-opacity-15 text-secondary text-uppercase px-2 py-0.5" style="font-size: 10px;">{{ $ext }} DOCUMENT</span>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ $docUrl }}" target="_blank" class="btn btn-sm btn-primary rounded-3 px-3 fw-bold">
                                        <i class="fas fa-external-link-alt me-1"></i> View / Inspect Document
                                    </a>
                                    <a href="{{ $docUrl }}" download class="btn btn-sm btn-outline-secondary rounded-3 px-3">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                </div>
                            </div>

                            @if($isImg)
                                <div class="mt-3 text-center bg-white p-2 rounded-2 border">
                                    <a href="{{ $docUrl }}" target="_blank">
                                        <img src="{{ $docUrl }}" alt="Trade License Document" class="img-fluid rounded border" style="max-height: 250px; object-fit: contain;">
                                    </a>
                                    <div class="small text-muted mt-1 fst-italic">Click image to inspect full size</div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-3">
                                <i class="fas fa-file-excel text-warning fa-2x mb-2 d-block"></i>
                                <span class="text-muted small">No Trade License / Registration document uploaded yet.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card 4: Payout & Settlement Information -->
            <div class="vp-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-money-check-dollar text-primary"></i>
                    <h5>Payout & Settlement Bank / MFS Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tr>
                                <th width="32%" class="text-muted">Payout Method:</th>
                                <td class="font-weight-bold text-dark">
                                    <span class="badge bg-info bg-opacity-15 text-info text-uppercase px-2.5 py-1">
                                        {{ $vendorSettings->payout_method ?? 'Not Configured' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Account Holder Name:</th>
                                <td class="font-weight-bold text-dark">{{ $vendorSettings->payout_account_name ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Account / Wallet No:</th>
                                <td>
                                    @if($vendorSettings->payout_account_number)
                                        <code class="fs-6 fw-bold text-primary">{{ $vendorSettings->payout_account_number }}</code>
                                    @else
                                        <span class="text-muted fst-italic">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            @if(!empty($vendorSettings->payout_bank_name))
                                <tr>
                                    <th class="text-muted">Bank Name:</th>
                                    <td class="font-weight-bold text-dark">{{ $vendorSettings->payout_bank_name }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Branch Name:</th>
                                    <td>{{ $vendorSettings->payout_branch_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Routing Number:</th>
                                    <td><code>{{ $vendorSettings->payout_routing_number ?? 'N/A' }}</code></td>
                                </tr>
                            @endif
                            <tr>
                                <th class="text-muted">Min Payout Threshold:</th>
                                <td><span class="font-weight-bold text-dark">৳{{ number_format($vendorSettings->getMinWithdrawalAmount(), 2) }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card 3: Recent Products Table -->
            <div class="vp-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-boxes-stacked text-primary"></i>
                        <h5>Recent Partner Products</h5>
                    </div>
                    <a href="{{ route('admin.vendor-products.index', ['vendor' => $vendor->id]) }}" class="btn btn-sm btn-outline-primary rounded-3">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Product Name</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>Commission</th>
                                        <th>Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentProducts as $product)
                                        <tr>
                                            <td class="ps-4 font-weight-bold">
                                                <a href="{{ route('admin.vendor-products.show', $product) }}" class="text-dark text-decoration-none">
                                                    {{ $product->title }}
                                                </a>
                                            </td>
                                            <td>
                                                @switch($product->approval_status)
                                                    @case('approved')
                                                        <span class="badge bg-success rounded-pill px-2">Approved</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge bg-warning text-dark rounded-pill px-2">Pending</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger rounded-pill px-2">Rejected</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="font-weight-bold">৳{{ number_format($product->offer ?? $product->old_price, 2) }}</td>
                                            <td>{{ $product->vendor_commission_rate ?? 'N/A' }}%</td>
                                            <td class="small text-muted">{{ $product->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4 mb-0">No products added yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Settings & Quick Actions -->
        <div class="col-lg-4">
            <!-- Card: Quick Actions -->
            <div class="vp-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-bolt text-primary"></i>
                    <h5>Quick Actions</h5>
                </div>
                <div class="card-body p-4 d-flex flex-column gap-2">
                    @if(!$vendorSettings->is_verified)
                        <form action="{{ route('admin.vendors.verify', $vendor) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve and verify this partner account?');">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 rounded-3 py-2 font-weight-bold">
                                <i class="fas fa-check-circle me-1"></i> Verify & Approve Partner
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.vendors.unverify', $vendor) }}" method="POST" onsubmit="return confirm('Are you sure you want to revoke verification for this partner? They will need to be re-verified.');">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning w-100 rounded-3 py-2 font-weight-bold">
                                <i class="fas fa-undo-alt me-1"></i> Revoke Verification
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.vendors.toggle-status', $vendor) }}" method="POST">
                        @csrf
                        @if($vendorSettings->is_active)
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-3 py-2 font-weight-bold">
                                <i class="fas fa-pause-circle me-1"></i> Deactivate Account
                            </button>
                        @else
                            <button type="submit" class="btn btn-outline-success w-100 rounded-3 py-2 font-weight-bold">
                                <i class="fas fa-play-circle me-1"></i> Activate Account
                            </button>
                        @endif
                    </form>

                    <a href="{{ route('admin.vendors.edit', $vendor) }}" class="btn btn-primary w-100 rounded-3 py-2 font-weight-bold">
                        <i class="fas fa-pen me-1"></i> Edit Partner & KYC
                    </a>
                </div>
            </div>

            <!-- Card: Commission & Product Policy -->
            <div class="vp-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-sliders text-primary"></i>
                    <h5>Platform Policy</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3 pb-3 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Default Commission:</span>
                        <span class="font-weight-bold text-dark">{{ $vendorSettings->getDefaultCommissionRate() }}%</span>
                    </div>
                    <div class="mb-3 pb-3 border-bottom d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Catalog Limit:</span>
                        <span class="font-weight-bold text-dark">
                            @if($vendorSettings->product_limit == 0)
                                Unlimited
                            @else
                                {{ $stats['total_products'] }} / {{ $vendorSettings->product_limit }}
                            @endif
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Auto-Approve Products:</span>
                        @if($vendorSettings->auto_approve_products)
                            <span class="badge bg-success rounded-pill px-2">Enabled</span>
                        @else
                            <span class="badge bg-secondary rounded-pill px-2">Disabled</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

