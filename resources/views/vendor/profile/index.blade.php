@extends('vendor.layouts.app')

@section('title', 'My Store Profile & Settings')

@section('content')
<!-- Page Title Header -->
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h2 class="fw-extrabold text-dark mb-1">Store Profile & Settings</h2>
        <p class="text-muted mb-0">Manage your merchant details, business contact info, and payout preferences.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('vendor.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Dashboard
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Main Column - Profile & Settings Forms -->
    <div class="col-12 col-lg-8">
        <!-- Personal Information Card -->
        <div class="v-card p-4 mb-4">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div class="stat-icon primary" style="width: 44px; height: 44px; font-size: 1.2rem;">
                    <i class="fas fa-user-gear"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Personal Information</h5>
                    <small class="text-muted">Account owner profile details</small>
                </div>
            </div>

            <form action="{{ route('vendor.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold text-dark fs-7">Full Name <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="name" 
                               class="form-control form-control-lg rounded-3 fs-7 @error('name') is-invalid @enderror" 
                               value="{{ old('name', $vendor->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold text-dark fs-7">Phone Number</label>
                        <input type="text" 
                               name="phone" 
                               class="form-control form-control-lg rounded-3 fs-7 @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $vendor->phone) }}"
                               placeholder="e.g. 017XXXXXXXX">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark fs-7">Login Email Address</label>
                    <input type="email" 
                           class="form-control form-control-lg rounded-3 fs-7 bg-light" 
                           value="{{ $vendor->email }}"
                           disabled>
                    <small class="text-muted fs-8">Primary login email address cannot be changed directly.</small>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fas fa-save me-1"></i> Save Personal Details
                    </button>
                </div>
            </form>
        </div>

        <!-- Business Information Card -->
        <div class="v-card p-4 mb-4">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div class="stat-icon info" style="width: 44px; height: 44px; font-size: 1.2rem;">
                    <i class="fas fa-building-store"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Business & Store Details</h5>
                    <small class="text-muted">Public merchant store details displayed on products</small>
                </div>
            </div>

            <form action="{{ route('vendor.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark fs-7">Store / Business Name <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="business_name" 
                           class="form-control form-control-lg rounded-3 fs-7 @error('business_name') is-invalid @enderror" 
                           value="{{ old('business_name', $vendorSettings->business_name) }}"
                           required>
                    @error('business_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold text-dark fs-7">Business Contact Email <span class="text-danger">*</span></label>
                        <input type="email" 
                               name="business_email" 
                               class="form-control form-control-lg rounded-3 fs-7 @error('business_email') is-invalid @enderror" 
                               value="{{ old('business_email', $vendorSettings->business_email) }}"
                               required>
                        @error('business_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold text-dark fs-7">Business Contact Phone <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="business_phone" 
                               class="form-control form-control-lg rounded-3 fs-7 @error('business_phone') is-invalid @enderror" 
                               value="{{ old('business_phone', $vendorSettings->business_phone) }}"
                               required>
                        @error('business_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark fs-7">Business Address</label>
                    <textarea name="business_address" 
                              class="form-control rounded-3 fs-7 @error('business_address') is-invalid @enderror" 
                              rows="3"
                              placeholder="Full shop or warehouse address">{{ old('business_address', $vendorSettings->business_address) }}</textarea>
                    @error('business_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark fs-7">Trade License / Tax ID</label>
                    <input type="text" 
                           name="tax_id" 
                           class="form-control form-control-lg rounded-3 fs-7 @error('tax_id') is-invalid @enderror" 
                           value="{{ old('tax_id', $vendorSettings->tax_id) }}"
                           placeholder="Optional business identification number">
                    @error('tax_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fas fa-save me-1"></i> Save Business Details
                    </button>
                </div>
            </form>
        </div>

        <!-- Payout Settings Card -->
        <div class="v-card p-4">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                <div class="stat-icon success" style="width: 44px; height: 44px; font-size: 1.2rem;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Payout & Withdrawal Account</h5>
                    <small class="text-muted">Where your earnings will be transferred</small>
                </div>
            </div>

            <form action="{{ route('vendor.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold text-dark fs-7">Payout Channel <span class="text-danger">*</span></label>
                        <select name="payout_method" 
                                class="form-select form-select-lg rounded-3 fs-7 @error('payout_method') is-invalid @enderror">
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

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold text-dark fs-7">Account Number <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="payout_account_number" 
                               class="form-control form-control-lg rounded-3 fs-7 @error('payout_account_number') is-invalid @enderror" 
                               value="{{ old('payout_account_number', $vendorSettings->payout_account_number) }}"
                               placeholder="Bank acct or mobile wallet number">
                        @error('payout_account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-dark fs-7">Account Name <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="payout_account_name" 
                           class="form-control form-control-lg rounded-3 fs-7 @error('payout_account_name') is-invalid @enderror" 
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
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold fs-7">Bank Name</label>
                                <input type="text" 
                                       name="payout_bank_name" 
                                       class="form-control fs-7" 
                                       value="{{ old('payout_bank_name', $vendorSettings->payout_bank_name) }}"
                                       placeholder="e.g. Dutch Bangla Bank">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold fs-7">Branch Name</label>
                                <input type="text" 
                                       name="payout_branch_name" 
                                       class="form-control fs-7" 
                                       value="{{ old('payout_branch_name', $vendorSettings->payout_branch_name) }}"
                                       placeholder="e.g. Gulshan Branch">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold fs-7">Routing Number</label>
                                <input type="text" 
                                       name="payout_routing_number" 
                                       class="form-control fs-7" 
                                       value="{{ old('payout_routing_number', $vendorSettings->payout_routing_number) }}"
                                       placeholder="Optional routing code">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info border-0 shadow-sm rounded-3 d-flex align-items-center gap-2 mb-4">
                    <i class="fas fa-circle-info fs-5 text-info"></i>
                    <span class="fs-7">Ensure all account details are valid to prevent delay in processing withdrawal payouts.</span>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">
                        <i class="fas fa-save me-1"></i> Update Payout Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Sidebar Column - Account Summary & Support -->
    <div class="col-12 col-lg-4">
        <!-- Account Status Card -->
        <div class="v-card p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fas fa-shield-check text-primary me-2"></i> Account Verification
            </h6>

            <div class="mb-3">
                <small class="text-muted fw-semibold d-block mb-1">Status Badge</small>
                @if($vendorSettings->is_verified)
                    <span class="badge badge-approved fs-7">
                        <i class="fas fa-check-circle me-1"></i> Verified Merchant
                    </span>
                    @if($vendorSettings->verified_at)
                        <small class="text-muted d-block mt-1">Verified on {{ $vendorSettings->verified_at->format('d M Y') }}</small>
                    @endif
                @else
                    <span class="badge badge-pending fs-7">
                        <i class="fas fa-clock me-1"></i> Pending Verification
                    </span>
                    <small class="text-muted d-block mt-1">Admin review is currently in progress.</small>
                @endif
            </div>

            <div class="mb-3">
                <small class="text-muted fw-semibold d-block mb-1">Account State</small>
                @if($vendorSettings->is_active)
                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1 rounded-pill fs-7">Active</span>
                @else
                    <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-3 py-1 rounded-pill fs-7">Inactive</span>
                @endif
            </div>

            <div class="mb-3">
                <small class="text-muted fw-semibold d-block mb-1">Merchant Registration Date</small>
                <span class="fw-bold text-dark fs-7">{{ $vendor->created_at->format('d M Y') }}</span>
            </div>
        </div>

        <!-- Commission Rates Summary Card -->
        <div class="v-card p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fas fa-percent text-warning me-2"></i> Store Policy & Quotas
            </h6>

            @php
                $effectiveSettings = $vendorSettings->getEffectiveSettings();
            @endphp

            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fs-7">Default Commission:</span>
                <span class="fw-extrabold text-primary fs-6">{{ $effectiveSettings['default_commission'] }}%</span>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fs-7">Commission Range:</span>
                <span class="fw-bold text-dark fs-7">{{ $effectiveSettings['min_commission'] }}% - {{ $effectiveSettings['max_commission'] }}%</span>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fs-7">Min Withdrawal Threshold:</span>
                <span class="fw-bold text-success fs-7">৳{{ number_format($effectiveSettings['min_withdrawal'], 2) }}</span>
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted fs-7">Product Limit:</span>
                @if($effectiveSettings['product_limit'] == 0)
                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2 py-1 rounded-pill fs-8">Unlimited</span>
                @else
                    <span class="fw-bold text-dark fs-7">{{ $effectiveSettings['product_count'] }} / {{ $effectiveSettings['product_limit'] }}</span>
                @endif
            </div>
        </div>

        <!-- Merchant Support Card -->
        <div class="v-card p-4">
            <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                <i class="fas fa-headset text-info me-2"></i> Need Assistance?
            </h6>

            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="stat-icon info" style="width: 38px; height: 38px; font-size: 1rem;">
                    <i class="fas fa-envelope"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Support Email</small>
                    <a href="mailto:{{ setting('general', 'site_email', 'contact@store.com') }}" class="fw-bold text-decoration-none text-dark fs-7">
                        {{ setting('general', 'site_email', 'contact@store.com') }}
                    </a>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon success" style="width: 38px; height: 38px; font-size: 1rem;">
                    <i class="fas fa-phone"></i>
                </div>
                <div>
                    <small class="text-muted d-block">Support Phone</small>
                    <a href="tel:{{ setting('general', 'site_phone', '+8801700000000') }}" class="fw-bold text-decoration-none text-dark fs-7">
                        {{ setting('general', 'site_phone', '+8801700000000') }}
                    </a>
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
