@extends('layouts.master')

@section('title', 'Add New Vendor')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-person-plus"></i> Add New Vendor</h4>
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Vendors
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.vendors.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <!-- Left Column - Account Information -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-person-circle"></i> Account Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number *</label>
                                    <input type="text" 
                                           name="phone" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone') }}"
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Password *</label>
                                    <input type="password" 
                                           name="password" 
                                           class="form-control @error('password') is-invalid @enderror"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Minimum 8 characters</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-building"></i> Business Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Business Name *</label>
                            <input type="text" 
                                   name="business_name" 
                                   class="form-control @error('business_name') is-invalid @enderror" 
                                   value="{{ old('business_name') }}"
                                   required>
                            @error('business_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Business Email *</label>
                                    <input type="email" 
                                           name="business_email" 
                                           class="form-control @error('business_email') is-invalid @enderror" 
                                           value="{{ old('business_email') }}"
                                           required>
                                    @error('business_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Business Phone *</label>
                                    <input type="text" 
                                           name="business_phone" 
                                           class="form-control @error('business_phone') is-invalid @enderror" 
                                           value="{{ old('business_phone') }}"
                                           required>
                                    @error('business_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Business Address</label>
                            <textarea name="business_address" 
                                      class="form-control @error('business_address') is-invalid @enderror" 
                                      rows="3">{{ old('business_address') }}</textarea>
                            @error('business_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tax ID / Business License</label>
                            <input type="text" 
                                   name="tax_id" 
                                   class="form-control @error('tax_id') is-invalid @enderror" 
                                   value="{{ old('tax_id') }}">
                            @error('tax_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Settings & Status -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-gear"></i> Account Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Account Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="is_active" 
                                       id="isActive"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
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
                                       {{ old('is_verified') ? 'checked' : '' }}>
                                <label class="form-check-label" for="isVerified">
                                    Verified
                                </label>
                            </div>
                            <small class="text-muted">Verified vendors can sell products</small>
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
                                   value="{{ old('default_commission_rate') }}"
                                   min="0"
                                   max="100"
                                   step="0.01"
                                   placeholder="Leave empty to use global default">
                            @error('default_commission_rate')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Override global commission rate for this vendor</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Min Commission (%)</label>
                            <input type="number" 
                                   name="custom_min_commission_rate" 
                                   class="form-control @error('custom_min_commission_rate') is-invalid @enderror" 
                                   value="{{ old('custom_min_commission_rate') }}"
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
                                   value="{{ old('custom_max_commission_rate') }}"
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
                                   value="{{ old('product_limit', 0) }}"
                                   min="0"
                                   placeholder="0 = Unlimited">
                            @error('product_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">0 = Unlimited products</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Auto-Approve Products</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="auto_approve_products" 
                                       id="autoApprove"
                                       value="1"
                                       {{ old('auto_approve_products') ? 'checked' : '' }}>
                                <label class="form-check-label" for="autoApprove">
                                    Enable
                                </label>
                            </div>
                            <small class="text-muted">Products will be published automatically</small>
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
                                   value="{{ old('custom_min_withdrawal_amount') }}"
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

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                                <i class="fas fa-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check-circle"></i> Create Vendor
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

