@extends('layouts.master')

@section('title', 'Edit Vendor - ' . $vendor->name)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-pencil-square"></i> Edit Vendor: {{ $vendor->name }}</h2>
                <div>
                    <a href="{{ route('admin.vendors.show', $vendor) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.vendors.update', $vendor) }}" method="POST">
        @csrf
        @method('PUT')
        
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
                                           value="{{ old('name', $vendor->name) }}"
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
                                           value="{{ old('email', $vendor->email) }}"
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
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" 
                                           name="phone" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $vendor->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" 
                                           name="password" 
                                           class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Leave empty to keep current password</small>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Member Since:</strong> {{ $vendor->created_at->format('d M Y') }}
                            <br>
                            <strong>Last Login:</strong> {{ $vendor->last_login_at ? $vendor->last_login_at->format('d M Y, h:i A') : 'Never' }}
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
                                   value="{{ old('business_name', $vendorSettings->business_name) }}"
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
                                           value="{{ old('business_email', $vendorSettings->business_email) }}"
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
                                           value="{{ old('business_phone', $vendorSettings->business_phone) }}"
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
                                      rows="3">{{ old('business_address', $vendorSettings->business_address) }}</textarea>
                            @error('business_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tax ID / Business License</label>
                            <input type="text" 
                                   name="tax_id" 
                                   class="form-control @error('tax_id') is-invalid @enderror" 
                                   value="{{ old('tax_id', $vendorSettings->tax_id) }}">
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

