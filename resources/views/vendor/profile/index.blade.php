@extends('vendor.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2><i class="fas fa-user-circle"></i> My Profile & Settings</h2>
    </div>
</div>

<div class="row">
    <!-- Left Column - Account Info -->
    <div class="col-md-8">
        <!-- Personal Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user"></i> Personal Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('vendor.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
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

                    <div class="mb-3">
                        <label class="form-label">Email Address *</label>
                        <input type="email" 
                               class="form-control" 
                               value="{{ $vendor->email }}"
                               disabled>
                        <small class="text-muted">Contact admin to change email</small>
                    </div>

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

                    <hr>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle"></i> Update Personal Info
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Business Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-building"></i> Business Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('vendor.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
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

                    <hr>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle"></i> Update Business Info
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Payout Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Payout Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('vendor.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Payout Method *</label>
                        <select name="payout_method" 
                                class="form-select @error('payout_method') is-invalid @enderror">
                            <option value="">Select Method</option>
                            <option value="bank" {{ old('payout_method', $vendorSettings->payout_method) == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="bkash" {{ old('payout_method', $vendorSettings->payout_method) == 'bkash' ? 'selected' : '' }}>bKash</option>
                            <option value="nagad" {{ old('payout_method', $vendorSettings->payout_method) == 'nagad' ? 'selected' : '' }}>Nagad</option>
                            <option value="rocket" {{ old('payout_method', $vendorSettings->payout_method) == 'rocket' ? 'selected' : '' }}>Rocket</option>
                        </select>
                        @error('payout_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Number *</label>
                        <input type="text" 
                               name="payout_account_number" 
                               class="form-control @error('payout_account_number') is-invalid @enderror" 
                               value="{{ old('payout_account_number', $vendorSettings->payout_account_number) }}"
                               placeholder="Account number or mobile number">
                        @error('payout_account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Name *</label>
                        <input type="text" 
                               name="payout_account_name" 
                               class="form-control @error('payout_account_name') is-invalid @enderror" 
                               value="{{ old('payout_account_name', $vendorSettings->payout_account_name) }}"
                               placeholder="Name as per account">
                        @error('payout_account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="bankDetails" style="display: {{ old('payout_method', $vendorSettings->payout_method) == 'bank' ? 'block' : 'none' }};">
                        <div class="mb-3">
                            <label class="form-label">Bank Name</label>
                            <input type="text" 
                                   name="payout_bank_name" 
                                   class="form-control @error('payout_bank_name') is-invalid @enderror" 
                                   value="{{ old('payout_bank_name', $vendorSettings->payout_bank_name) }}">
                            @error('payout_bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Branch Name</label>
                            <input type="text" 
                                   name="payout_branch_name" 
                                   class="form-control @error('payout_branch_name') is-invalid @enderror" 
                                   value="{{ old('payout_branch_name', $vendorSettings->payout_branch_name) }}">
                            @error('payout_branch_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Routing Number</label>
                            <input type="text" 
                                   name="payout_routing_number" 
                                   class="form-control @error('payout_routing_number') is-invalid @enderror" 
                                   value="{{ old('payout_routing_number', $vendorSettings->payout_routing_number) }}">
                            @error('payout_routing_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Important:</strong> Make sure your payout information is accurate. This is where we'll send your earnings.
                    </div>

                    <hr>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle"></i> Update Payout Info
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column - Account Status -->
    <div class="col-md-4">
        <!-- Account Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shield-check"></i> Account Status</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Verification Status:</strong><br>
                    @if($vendorSettings->is_verified)
                        <span class="badge bg-success mt-1">
                            <i class="fas fa-patch-check"></i> Verified
                        </span>
                        <br><small class="text-muted">Verified on {{ $vendorSettings->verified_at->format('d M Y') }}</small>
                    @else
                        <span class="badge bg-warning text-dark mt-1">
                            <i class="fas fa-clock"></i> Pending Verification
                        </span>
                        <br><small class="text-muted">Your account is awaiting admin verification</small>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Account Status:</strong><br>
                    @if($vendorSettings->is_active)
                        <span class="badge bg-success mt-1">Active</span>
                    @else
                        <span class="badge bg-danger mt-1">Inactive</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Member Since:</strong><br>
                    <span class="text-muted">{{ $vendor->created_at->format('d M Y') }}</span>
                </div>

                <hr>

                <div class="mb-3">
                    <strong>Total Products:</strong><br>
                    <h4 class="mb-0">{{ $vendor->products->count() }}</h4>
                </div>

                <div class="mb-3">
                    <strong>Total Orders:</strong><br>
                    <h4 class="mb-0">{{ $vendor->vendorOrderItems()->distinct('order_id')->count('order_id') }}</h4>
                </div>
            </div>
        </div>

        <!-- Commission Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-percent"></i> Commission Settings</h5>
            </div>
            <div class="card-body">
                @php
                    $effectiveSettings = $vendorSettings->getEffectiveSettings();
                @endphp

                <div class="mb-3">
                    <strong>Default Commission:</strong><br>
                    <h4 class="text-primary mb-0">{{ $effectiveSettings['default_commission'] }}%</h4>
                </div>

                <div class="mb-3">
                    <strong>Allowed Range:</strong><br>
                    <span class="text-muted">{{ $effectiveSettings['min_commission'] }}% - {{ $effectiveSettings['max_commission'] }}%</span>
                </div>

                <div class="mb-3">
                    <strong>Min Withdrawal:</strong><br>
                    <span class="text-muted">৳{{ number_format($effectiveSettings['min_withdrawal'], 2) }}</span>
                </div>

                <div class="mb-3">
                    <strong>Product Limit:</strong><br>
                    @if($effectiveSettings['product_limit'] == 0)
                        <span class="text-success">Unlimited</span>
                    @else
                        <span class="text-muted">{{ $effectiveSettings['product_count'] }} / {{ $effectiveSettings['product_limit'] }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Help & Support -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-question-circle"></i> Need Help?</h5>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <i class="fas fa-envelope"></i> Email Support<br>
                    <a href="mailto:support@yoursite.com">support@yoursite.com</a>
                </p>
                <p class="mb-2">
                    <i class="fas fa-telephone"></i> Phone Support<br>
                    <a href="tel:+8801700000000">+880 1700-000000</a>
                </p>
                <hr>
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i>
                    Contact us if you need to update your email or have account issues.
                </small>
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

