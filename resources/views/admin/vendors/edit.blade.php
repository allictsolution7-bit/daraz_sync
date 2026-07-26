@extends('layouts.master')

@section('title', 'Edit Partner - ' . $vendor->name)

@section('styles')
<style>
    .vp-builder {
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
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #38bdf8;
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
    .vp-builder .form-control, .vp-builder .form-select, .vp-builder textarea {
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 500;
        background-color: #f8fafc;
    }
    .vp-builder .form-control:focus, .vp-builder .form-select:focus, .vp-builder textarea:focus {
        background-color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }
    .vp-builder label {
        font-size: 12px;
        font-weight: 800;
        color: #1e293b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .btn-save-vp {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 14px;
        box-shadow: 0 4px 14px rgba(16,185,129,0.35);
    }
    .btn-save-vp:hover {
        transform: translateY(-2px);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="vp-builder">
    <!-- Header Banner -->
    <div class="vp-hero-card d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="vp-title-icon">
                <i class="fas fa-user-pen"></i>
            </div>
            <div>
                <h3 class="mb-0 text-white font-weight-bold">Edit Partner: {{ $vendor->name }}</h3>
                <p class="mb-0 text-white-50 small">Configure profile, business details, commissions & limits</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.vendors.show', $vendor) }}" class="btn btn-info rounded-3 px-3 text-white">
                <i class="fas fa-eye me-1"></i> View Details
            </a>
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary rounded-3 px-3">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <form action="{{ route('admin.vendors.update', $vendor) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <!-- Left Column: Primary Information -->
            <div class="col-lg-7">
                <!-- Card 1: Account Credentials -->
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-user-circle text-primary"></i>
                        <h5>Account Information</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label>Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $vendor->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label>Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $vendor->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label>Phone Number</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $vendor->phone) }}">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label>New Password (Optional)</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Leave blank to keep current">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <small class="text-muted"><i class="fas fa-calendar-check me-1 text-primary"></i> <strong>Joined:</strong> {{ $vendor->created_at->format('d M Y') }}</small>
                            <small class="text-muted"><i class="fas fa-clock me-1 text-info"></i> <strong>Last Login:</strong> {{ $vendor->last_login_at ? $vendor->last_login_at->format('d M Y, h:i A') : 'Never' }}</small>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Business Profile -->
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-building text-primary"></i>
                        <h5>Business & Store Profile</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label>Business Name <span class="text-danger">*</span></label>
                            <input type="text" name="business_name" class="form-control @error('business_name') is-invalid @enderror" value="{{ old('business_name', $vendorSettings->business_name) }}" required>
                            @error('business_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label>Business Email <span class="text-danger">*</span></label>
                                <input type="email" name="business_email" class="form-control @error('business_email') is-invalid @enderror" value="{{ old('business_email', $vendorSettings->business_email) }}" required>
                                @error('business_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label>Business Phone <span class="text-danger">*</span></label>
                                <input type="text" name="business_phone" class="form-control @error('business_phone') is-invalid @enderror" value="{{ old('business_phone', $vendorSettings->business_phone) }}" required>
                                @error('business_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Business Address</label>
                            <textarea name="business_address" class="form-control" rows="3">{{ old('business_address', $vendorSettings->business_address) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label>Tax ID / Trade License</label>
                            <input type="text" name="tax_id" class="form-control" value="{{ old('tax_id', $vendorSettings->tax_id) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Commissions -->
            <div class="col-lg-5">
                <!-- Card 3: Account Status -->
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-shield-alt text-primary"></i>
                        <h5>Account Status & Verification</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="form-check form-switch mb-3 pt-1">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $vendorSettings->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label font-weight-bold" for="is_active">Active Account</label>
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="is_verified" name="is_verified" value="1" {{ old('is_verified', $vendorSettings->is_verified) ? 'checked' : '' }}>
                            <label class="form-check-label font-weight-bold" for="is_verified">Verified Partner Badge</label>
                        </div>
                        @if($vendorSettings->verified_at)
                            <small class="text-muted d-block mb-3">Verified on: {{ $vendorSettings->verified_at->format('d M Y') }}</small>
                        @endif
                    </div>
                </div>

                <!-- Card 4: Commission Settings -->
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-percent text-primary"></i>
                        <h5>Commission Rules</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label>Default Commission Rate (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="default_commission_rate" class="form-control" value="{{ old('default_commission_rate', $vendorSettings->default_commission_rate) }}" placeholder="Global default">
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label>Min Rate (%)</label>
                                <input type="number" step="0.01" min="0" max="100" name="custom_min_commission_rate" class="form-control" value="{{ old('custom_min_commission_rate', $vendorSettings->custom_min_commission_rate) }}">
                            </div>
                            <div class="col-6">
                                <label>Max Rate (%)</label>
                                <input type="number" step="0.01" min="0" max="100" name="custom_max_commission_rate" class="form-control" value="{{ old('custom_max_commission_rate', $vendorSettings->custom_max_commission_rate) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 5: Product & Payout Settings -->
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-sliders text-primary"></i>
                        <h5>Limits & Withdrawals</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label>Catalog Product Limit</label>
                            <input type="number" name="product_limit" class="form-control" value="{{ old('product_limit', $vendorSettings->product_limit ?? 0) }}" min="0" placeholder="0 = Unlimited">
                            <small class="text-muted">Current catalog: {{ $vendor->products()->count() }} items</small>
                        </div>

                        <div class="form-check form-switch mb-4 pt-2 d-flex align-items-center gap-2">
                            <input class="form-check-input me-2" type="checkbox" id="autoApprove" name="auto_approve_products" value="1" style="width: 3.2em; height: 1.6em; cursor: pointer;" {{ old('auto_approve_products', $vendorSettings->auto_approve_products) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-dark fs-6" for="autoApprove" style="cursor: pointer; user-select: none;">Auto-Approve Products</label>
                        </div>

                        <div class="mb-3">
                            <label>Min Withdrawal Amount (৳)</label>
                            <input type="number" step="0.01" min="0" name="custom_min_withdrawal_amount" class="form-control" value="{{ old('custom_min_withdrawal_amount', $vendorSettings->custom_min_withdrawal_amount) }}" placeholder="Global default">
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-2 mb-4">
                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary px-3">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-save-vp w-100 shadow">
                        <i class="fas fa-check-circle me-1"></i> Save Partner Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

