@extends('vendor.layouts.app')

@section('title', 'Store Profile & Settings')

@push('styles')
<style>
    :root {
        --v-primary: #6366f1;
        --v-primary-hover: #4f46e5;
        --v-success: #10b981;
        --v-warning: #f59e0b;
        --v-info: #06b6d4;
        --v-danger: #ef4444;
        --v-dark: #0f172a;
    }

    /* Header Styling */
    .profile-header-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        padding: 1.25rem 1.75rem;
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }

    .metric-icon-box {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        background: rgba(99, 102, 241, 0.12);
        color: #6366f1;
    }

    /* Custom Modern Nav Tabs */
    .profile-nav-tabs {
        border-bottom: 2px solid #e2e8f0;
        gap: 10px;
        padding-bottom: 0;
        margin-bottom: 24px;
    }

    .profile-nav-tabs .nav-link {
        border: none !important;
        background: transparent;
        color: #64748b;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 12px 20px;
        border-radius: 12px 12px 0 0;
        transition: all 0.25s ease-in-out;
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profile-nav-tabs .nav-link i {
        font-size: 1rem;
        transition: transform 0.2s;
    }

    .profile-nav-tabs .nav-link:hover {
        color: #6366f1;
        background: rgba(99, 102, 241, 0.05);
    }

    .profile-nav-tabs .nav-link.active {
        color: #6366f1;
        background: #ffffff;
        border-bottom: 3px solid #6366f1 !important;
        box-shadow: 0 -4px 12px rgba(99, 102, 241, 0.06);
    }

    .profile-nav-tabs .nav-link.active i {
        transform: scale(1.1);
    }

    .v-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.03);
    }

    .v-card .card-header {
        background: rgba(248, 250, 252, 0.8);
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 0.65rem 1rem;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    /* Badges */
    .badge-approved { background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.2); padding: 6px 12px; border-radius: 20px; font-weight: 700; }
    .badge-pending { background: rgba(245, 158, 11, 0.1); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.2); padding: 6px 12px; border-radius: 20px; font-weight: 700; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header Card -->
    <div class="d-flex justify-content-between align-items-center mb-4 profile-header-card">
        <div class="d-flex align-items-center gap-3">
            <div class="metric-icon-box">
                <i class="fas fa-store"></i>
            </div>
            <div>
                <h4 class="fw-800 mb-0 text-dark">Store Profile & Settings</h4>
                <p class="text-muted small mb-0">Manage merchant details, business contact info, payout preferences, and store status.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('vendor.dashboard') }}" class="btn btn-outline-secondary rounded-3 px-3 fw-bold btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs profile-nav-tabs" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="personal-tab-btn" data-bs-toggle="tab" data-bs-target="#personal-tab" type="button" role="tab" aria-controls="personal-tab" aria-selected="true">
                <i class="fas fa-store text-primary"></i> Personal & Store Details
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="payout-tab-btn" data-bs-toggle="tab" data-bs-target="#payout-tab" type="button" role="tab" aria-controls="payout-tab" aria-selected="false">
                <i class="fas fa-wallet text-success"></i> Payout & Bank Account
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="verification-tab-btn" data-bs-toggle="tab" data-bs-target="#verification-tab" type="button" role="tab" aria-controls="verification-tab" aria-selected="false">
                <i class="fas fa-shield-alt text-warning"></i> Verification & Policies
            </button>
        </li>
        @if($vendorSettings->is_consignment || auth()->user()?->hasRole('reseller'))
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="markup-tab-btn" data-bs-toggle="tab" data-bs-target="#markup-tab" type="button" role="tab" aria-controls="markup-tab" aria-selected="false">
                <i class="fas fa-magic text-danger"></i> Price Auto-Calculations
            </button>
        </li>
        @endif
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="courier-tab-btn" data-bs-toggle="tab" data-bs-target="#courier-tab" type="button" role="tab" aria-controls="courier-tab" aria-selected="false">
                <i class="fas fa-truck text-success"></i> Courier Integrations
            </button>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content" id="profileTabsContent">
        <!-- 1. Personal & Store Information Tab -->
        <div class="tab-pane fade show active" id="personal-tab" role="tabpanel" aria-labelledby="personal-tab-btn">
            <div class="row">
                <div class="col-lg-8">
                    <div class="v-card">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-store text-primary"></i> Personal & Store Details
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('vendor.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <h6 class="fw-bold text-muted mb-3 uppercase small"><i class="fas fa-user me-1"></i> Owner Details</h6>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark fs-8 uppercase">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $vendor->name) }}"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark fs-8 uppercase">Phone Number</label>
                                        <input type="text" 
                                               name="phone" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               value="{{ old('phone', $vendor->phone) }}"
                                               placeholder="e.g. 017XXXXXXXX">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark fs-8 uppercase">Login Email Address</label>
                                    <input type="email" 
                                           class="form-control bg-light" 
                                           value="{{ $vendor->email }}"
                                           disabled>
                                    <small class="text-muted fs-8">Primary login email address cannot be modified directly.</small>
                                </div>

                                <hr class="my-4">

                                <h6 class="fw-bold text-muted mb-3 uppercase small"><i class="fas fa-store-alt me-1"></i> Store Details</h6>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark fs-8 uppercase">Store / Business Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="business_name" 
                                           class="form-control @error('business_name') is-invalid @enderror" 
                                           value="{{ old('business_name', $vendorSettings->business_name) }}"
                                           required>
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark fs-8 uppercase">Business Contact Email <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               name="business_email" 
                                               class="form-control @error('business_email') is-invalid @enderror" 
                                               value="{{ old('business_email', $vendorSettings->business_email) }}"
                                               required>
                                        @error('business_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark fs-8 uppercase">Business Contact Phone <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="business_phone" 
                                               class="form-control @error('business_phone') is-invalid @enderror" 
                                               value="{{ old('business_phone', $vendorSettings->business_phone) }}"
                                               required>
                                        @error('business_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark fs-8 uppercase">Business Address</label>
                                    <textarea name="business_address" 
                                              class="form-control @error('business_address') is-invalid @enderror" 
                                              rows="3"
                                              placeholder="Full shop or warehouse address">{{ old('business_address', $vendorSettings->business_address) }}</textarea>
                                    @error('business_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark fs-8 uppercase">Trade License / Tax ID</label>
                                    <input type="text" 
                                           name="tax_id" 
                                           class="form-control @error('tax_id') is-invalid @enderror" 
                                           value="{{ old('tax_id', $vendorSettings->tax_id) }}"
                                           placeholder="Optional business identification number">
                                    @error('tax_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;">
                                        <i class="fas fa-save me-1"></i> Save Profile Details
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Payout Settings Tab -->
        <div class="tab-pane fade" id="payout-tab" role="tabpanel" aria-labelledby="payout-tab-btn">
            <div class="row">
                <div class="col-lg-8">
                    <div class="v-card">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-wallet text-primary"></i> Payout & Withdrawal Account
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('vendor.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark fs-8 uppercase">Payout Channel <span class="text-danger">*</span></label>
                                        <select name="payout_method" 
                                                class="form-select @error('payout_method') is-invalid @enderror">
                                            <option value="">Select Method</option>
                                            <option value="bank" {{ old('payout_method', $vendorSettings->payout_method) == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                                            <option value="bkash" {{ old('payout_method', $vendorSettings->payout_method) == 'bkash' ? 'selected' : '' }}>bKash Personal / Agent</option>
                                            <option value="nagad" {{ old('payout_method', $vendorSettings->payout_method) == 'nagad' ? 'selected' : '' }}>Nagad Personal</option>
                                            <option value="rocket" {{ old('payout_method', $vendorSettings->payout_method) == 'rocket' ? 'selected' : '' }}>Rocket Personal</option>
                                        </select>
                                        @error('payout_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark fs-8 uppercase">Account Number <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="payout_account_number" 
                                               class="form-control @error('payout_account_number') is-invalid @enderror" 
                                               value="{{ old('payout_account_number', $vendorSettings->payout_account_number) }}"
                                               placeholder="Bank acct or mobile wallet number">
                                        @error('payout_account_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark fs-8 uppercase">Account Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="payout_account_name" 
                                           class="form-control @error('payout_account_name') is-invalid @enderror" 
                                           value="{{ old('payout_account_name', $vendorSettings->payout_account_name) }}"
                                           placeholder="Full name as written on account">
                                    @error('payout_account_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div id="bankDetails" style="display: {{ old('payout_method', $vendorSettings->payout_method) == 'bank' ? 'block' : 'none' }};">
                                    <div class="p-3 bg-light rounded-3 mb-3 border">
                                        <h6 class="fw-bold text-dark mb-3">Bank Details</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold fs-7">Bank Name</label>
                                                <input type="text" 
                                                       name="payout_bank_name" 
                                                       class="form-control fs-7" 
                                                       value="{{ old('payout_bank_name', $vendorSettings->payout_bank_name) }}"
                                                       placeholder="e.g. Dutch Bangla Bank">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold fs-7">Branch Name</label>
                                                <input type="text" 
                                                       name="payout_branch_name" 
                                                       class="form-control fs-7" 
                                                       value="{{ old('payout_branch_name', $vendorSettings->payout_branch_name) }}"
                                                       placeholder="e.g. Gulshan Branch">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold fs-7">Routing Number</label>
                                                <input type="text" 
                                                       name="payout_routing_number" 
                                                       class="form-control fs-7" 
                                                       value="{{ old('payout_routing_number', $vendorSettings->payout_routing_number) }}"
                                                       placeholder="Routing code">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success rounded-3 px-4 fw-bold shadow-sm">
                                        <i class="fas fa-save me-1"></i> Update Payout Account
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Verification & Policy Overview Tab -->
        <div class="tab-pane fade" id="verification-tab" role="tabpanel" aria-labelledby="verification-tab-btn">
            <div class="row g-4">
                <div class="col-lg-6">
                    <!-- Account Status Card -->
                    <div class="v-card mb-4">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-shield-alt text-primary"></i> Verification & Status
                            </h5>
                        </div>
                        <div class="card-body p-4 fs-8">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Verification Status:</span>
                                @if($vendorSettings->is_verified)
                                    <span class="badge-approved fs-8">
                                        <i class="fas fa-check-circle me-1"></i> Verified
                                    </span>
                                @else
                                    <span class="badge-pending fs-8">
                                        <i class="fas fa-clock me-1"></i> Pending Review
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Account Status:</span>
                                @if($vendorSettings->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2.5 py-1 rounded-pill">Active</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-2.5 py-1 rounded-pill">Inactive</span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Merchant Since:</span>
                                <span class="fw-bold text-dark">{{ $vendor->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Merchant Support Card -->
                    <div class="v-card">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-headset text-info"></i> Support Assistance
                            </h5>
                        </div>
                        <div class="card-body p-4 fs-8">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="metric-icon-box" style="width: 40px; height: 40px; font-size: 1rem;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Support Email</small>
                                    <a href="mailto:{{ setting('general', 'site_email', 'contact@store.com') }}" class="fw-bold text-decoration-none text-dark">
                                        {{ setting('general', 'site_email', 'contact@store.com') }}
                                    </a>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <div class="metric-icon-box" style="width: 40px; height: 40px; font-size: 1rem; background: rgba(16, 185, 129, 0.12); color: #10b981;">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Support Phone</small>
                                    <a href="tel:{{ setting('general', 'site_phone', '+8801700000000') }}" class="fw-bold text-decoration-none text-dark">
                                        {{ setting('general', 'site_phone', '+8801700000000') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <!-- Commission Rates Summary Card -->
                    <div class="v-card mb-4">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-sliders text-warning"></i> Store Policy & Quotas
                            </h5>
                        </div>
                        <div class="card-body p-4 fs-8">
                            @php
                                $effectiveSettings = $vendorSettings->getEffectiveSettings();
                            @endphp

                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-muted">Default Commission:</span>
                                <span class="fw-800 text-primary fs-6">{{ $effectiveSettings['default_commission'] }}%</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-muted">Allowed Commission Range:</span>
                                <span class="fw-bold text-dark">{{ $effectiveSettings['min_commission'] }}% - {{ $effectiveSettings['max_commission'] }}%</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-muted">Min Withdrawal Amount:</span>
                                <span class="fw-bold text-success">৳{{ number_format($effectiveSettings['min_withdrawal'], 2) }}</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-muted">Product Limit:</span>
                                @if($effectiveSettings['product_limit'] == 0)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1 rounded-pill">Unlimited</span>
                                @else
                                    <span class="fw-bold text-dark">{{ $effectiveSettings['product_count'] }} / {{ $effectiveSettings['product_limit'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Store Custom Return Policy Card -->
                    <div class="v-card mt-4">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-undo-alt text-primary"></i> Custom Store Return Policy
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('vendor.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark fs-8 uppercase">Custom Return Policy Text</label>
                                    <textarea name="return_policy_text" class="form-control" rows="5" placeholder="Enter your store's custom return acceptance and money refund terms...">{{ old('return_policy_text', $vendorSettings->return_policy['policy_text'] ?? '') }}</textarea>
                                    <small class="text-muted fs-8">Specify custom terms. If empty, the website's default return policy will apply to your products.</small>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;">
                                        <i class="fas fa-save me-1"></i> Save Return Policy
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. Price Auto-Calculations Tab -->
        @if($vendorSettings->is_consignment || auth()->user()?->hasRole('reseller'))
        <div class="tab-pane fade" id="markup-tab" role="tabpanel" aria-labelledby="markup-tab-btn">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="v-card">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-percentage text-danger"></i> Product Price Auto-Calculation Percentages
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('vendor.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                @if(auth()->user()?->hasRole('reseller'))
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-12">
                                            <div class="alert alert-info border-0 rounded-3 mb-4">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Configure your markup percentage here. Your Reseller Price will be auto-calculated by adding this markup percentage on top of the base product price.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-7">Reseller Price Markup (%)</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   name="reseller_markup_pct" 
                                                   class="form-control fs-7" 
                                                   value="{{ old('reseller_markup_pct', $vendorSettings->reseller_markup_pct ?? 10.00) }}"
                                                   min="0"
                                                   required>
                                            <small class="text-muted fs-8">Markup added to product price for your Reseller selling price.</small>
                                        </div>
                                    </div>
                                @else
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-12">
                                            <div class="alert alert-info border-0 rounded-3 mb-4">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Configure markup percentages here. When copying products from the admin catalog, your retail, old, and wholesale prices will be auto-calculated by applying these percentages above the admin wholesale cost.
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-7">Sale Price Markup (%)</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   name="sale_price_markup_pct" 
                                                   class="form-control fs-7" 
                                                   value="{{ old('sale_price_markup_pct', $vendorSettings->sale_price_markup_pct ?? 10.00) }}"
                                                   min="0"
                                                   required>
                                            <small class="text-muted fs-8">Markup added to cost for Sale Price.</small>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-7">Old Price Markup (%)</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   name="old_price_markup_pct" 
                                                   class="form-control fs-7" 
                                                   value="{{ old('old_price_markup_pct', $vendorSettings->old_price_markup_pct ?? 25.00) }}"
                                                   min="0"
                                                   required>
                                            <small class="text-muted fs-8">Markup added to cost for Old Price.</small>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold fs-7">Wholesale Price Markup (%)</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   name="wholesale_price_markup_pct" 
                                                   class="form-control fs-7" 
                                                   value="{{ old('wholesale_price_markup_pct', $vendorSettings->wholesale_price_markup_pct ?? 5.00) }}"
                                                   min="0"
                                                   required>
                                            <small class="text-muted fs-8">Markup added to cost for Wholesale Price.</small>
                                        </div>
                                    </div>
                                @endif

                                <div class="text-end">
                                    <button type="submit" class="btn btn-success rounded-3 px-4 fw-bold shadow-sm">
                                        <i class="fas fa-save me-1"></i> Save Calculation Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- 6. Courier Integrations Tab -->
        <div class="tab-pane fade" id="courier-tab" role="tabpanel" aria-labelledby="courier-tab-btn">
            <div class="row g-4">
                <!-- Steadfast Settings -->
                <div class="col-lg-6">
                    <div class="v-card h-100">
                        <div class="card-header bg-light">
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-shipping-fast text-success"></i> Steadfast Credentials
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('vendor.profile.delivery-integration') }}" method="POST">
                                @csrf
                                <input type="hidden" name="provider" value="steadfast">

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark fs-8 uppercase">API Key <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="api_key" 
                                           class="form-control" 
                                           value="{{ old('api_key', $steadfastIntegration->credentials['api_key'] ?? '') }}"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark fs-8 uppercase">Secret Key <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="secret_key" 
                                           class="form-control" 
                                           value="{{ old('secret_key', $steadfastIntegration->credentials['secret_key'] ?? '') }}"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark fs-8 uppercase">Base URL <span class="text-danger">*</span></label>
                                    <input type="url" 
                                           name="base_url" 
                                           class="form-control" 
                                           value="{{ old('base_url', $steadfastIntegration->credentials['base_url'] ?? 'https://portal.packsend.com.bd/api/v1') }}"
                                           required>
                                </div>

                                <div class="form-check form-switch mb-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="steadfast_active" value="1" {{ ($steadfastIntegration->is_active ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark fs-7" for="steadfast_active">Enable Steadfast Integration</label>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                                        <i class="fas fa-save me-1"></i> Save Steadfast Details
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Pathao Settings -->
                <div class="col-lg-6">
                    <div class="v-card h-100">
                        <div class="card-header bg-light">
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                                <i class="fas fa-truck-ramp-box text-primary"></i> Pathao Credentials
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('vendor.profile.delivery-integration') }}" method="POST">
                                @csrf
                                <input type="hidden" name="provider" value="pathao">

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark fs-8 uppercase">Client ID <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="client_id" 
                                               class="form-control" 
                                               value="{{ old('client_id', $pathaoIntegration->credentials['client_id'] ?? '') }}"
                                               required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark fs-8 uppercase">Client Secret <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="client_secret" 
                                               class="form-control" 
                                               value="{{ old('client_secret', $pathaoIntegration->credentials['client_secret'] ?? '') }}"
                                               required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark fs-8 uppercase">Username <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               name="username" 
                                               class="form-control" 
                                               value="{{ old('username', $pathaoIntegration->credentials['username'] ?? '') }}"
                                               required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark fs-8 uppercase">Password <span class="text-danger">*</span></label>
                                        <input type="password" 
                                               name="password" 
                                               class="form-control" 
                                               value="{{ old('password', $pathaoIntegration->credentials['password'] ?? '') }}"
                                               required>
                                    </div>
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark fs-8 uppercase">Base URL <span class="text-danger">*</span></label>
                                        <input type="url" 
                                               name="base_url" 
                                               class="form-control" 
                                               value="{{ old('base_url', $pathaoIntegration->credentials['base_url'] ?? 'https://api.pathao.com') }}"
                                               required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-dark fs-8 uppercase">Store ID</label>
                                        <input type="text" 
                                               name="store_id" 
                                               class="form-control" 
                                               value="{{ old('store_id', $pathaoIntegration->credentials['store_id'] ?? '') }}">
                                    </div>
                                </div>

                                <div class="form-check form-switch mb-4">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="pathao_active" value="1" {{ ($pathaoIntegration->is_active ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark fs-7" for="pathao_active">Enable Pathao Integration</label>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;">
                                        <i class="fas fa-save me-1"></i> Save Pathao Details
                                    </button>
                                </div>
                            </form>
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
document.addEventListener('DOMContentLoaded', function() {
    const payoutMethod = document.querySelector('select[name="payout_method"]');
    const bankDetails = document.getElementById('bankDetails');
    
    if (payoutMethod) {
        payoutMethod.addEventListener('change', function() {
            if (this.value === 'bank') {
                bankDetails.style.display = 'block';
            } else {
                bankDetails.style.display = 'none';
            }
        });
    }
});
</script>
@endpush
