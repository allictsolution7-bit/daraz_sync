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
        background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 14px;
        box-shadow: 0 4px 14px rgba(79,70,229,0.35);
    }
    .btn-save-vp:hover {
        transform: translateY(-2px);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="vp-builder">
    <!-- Banner -->
    <div class="vp-hero-card d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="vp-title-icon">
                <i class="fas fa-user-pen"></i>
            </div>
            <div>
                <h3 class="mb-0 text-white font-weight-bold">Edit Partner: {{ $vendor->name }}</h3>
                <p class="mb-0 text-white-50 small">Update account details, business address & commission settings</p>
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
        
                                       {{ old('is_active', $vendorSettings->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Verification Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_verified" 
                                       id="isVerified"
                                       value="1"
                                       {{ old('is_verified', $vendorSettings->is_verified) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isVerified">
                                    Verified
                                </label>
                            </div>
                            @if($vendorSettings->verified_at)
                                <small class="text-muted">Verified on: {{ $vendorSettings->verified_at->format('d M Y') }}</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-percent"></i> Commission Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Default Commission Rate (%)</label>
                            <input type="number" 
                                   name="default_commission_rate" 
                                   class="form-control @error('default_commission_rate') is-invalid @enderror" 
                                   value="{{ old('default_commission_rate', $vendorSettings->default_commission_rate) }}"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                   placeholder="Leave empty to use global default">
                            @error('default_commission_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Min Commission (%)</label>
                            <input type="number" 
                                   name="custom_min_commission_rate" 
                                   class="form-control @error('custom_min_commission_rate') is-invalid @enderror" 
                                   value="{{ old('custom_min_commission_rate', $vendorSettings->custom_min_commission_rate) }}"
                                   min="0"
                                   max="100"
                                   step="0.01">
                            @error('custom_min_commission_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Max Commission (%)</label>
                            <input type="number" 
                                   name="custom_max_commission_rate" 
                                   class="form-control @error('custom_max_commission_rate') is-invalid @enderror" 
                                   value="{{ old('custom_max_commission_rate', $vendorSettings->custom_max_commission_rate) }}"
                                   min="0"
                                   max="100"
                                   step="0.01">
                            @error('custom_max_commission_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Product Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Product Limit</label>
                            <input type="number" 
                                   name="product_limit" 
                                   class="form-control @error('product_limit') is-invalid @enderror" 
                                   value="{{ old('product_limit', $vendorSettings->product_limit ?? 0) }}"
                                   min="0"
                                   placeholder="0 = Unlimited">
                            @error('product_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Current: {{ $vendor->products()->count() }} products
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Auto-Approve Products</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="auto_approve_products" 
                                       id="autoApprove"
                                       value="1"
                                       {{ old('auto_approve_products', $vendorSettings->auto_approve_products) ? 'checked' : '' }}>
                                <label class="form-check-label" for="autoApprove">
                                    Enable
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-cash"></i> Withdrawal Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Min Withdrawal Amount (৳)</label>
                            <input type="number" 
                                   name="custom_min_withdrawal_amount" 
                                   class="form-control @error('custom_min_withdrawal_amount') is-invalid @enderror" 
                                   value="{{ old('custom_min_withdrawal_amount', $vendorSettings->custom_min_withdrawal_amount) }}"
                                   min="0"
                                   step="0.01"
                                   placeholder="Leave empty to use global default">
                            @error('custom_min_withdrawal_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-x-circle"></i> Cancel
                                </a>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check-circle"></i> Update Vendor
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Form (Separate) -->
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">Deleting this vendor is permanent and cannot be undone. This will also delete all vendor settings and products.</p>
                    <form action="{{ route('admin.vendors.destroy', $vendor) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this vendor? This action cannot be undone!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Vendor
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

