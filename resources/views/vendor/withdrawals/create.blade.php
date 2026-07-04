@extends('vendor.layouts.app')

@section('title', 'Request Withdrawal')

@section('content')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vendor.withdrawals.index') }}">Withdrawals</a></li>
                <li class="breadcrumb-item active">Request Withdrawal</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Request Withdrawal</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('vendor.withdrawals.store') }}" method="POST" id="withdrawalForm">
                    @csrf

                    <!-- Amount -->
                    <div class="mb-4">
                        <label for="amount" class="form-label fw-bold">
                            <i class="fas fa-dollar-sign"></i> Withdrawal Amount *
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">৳</span>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control @error('amount') is-invalid @enderror" 
                                   id="amount" 
                                   name="amount" 
                                   value="{{ old('amount') }}"
                                   min="{{ $minWithdrawal }}"
                                   max="{{ $availableBalance }}"
                                   required>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Minimum: ৳{{ number_format($minWithdrawal, 2) }} | 
                            Available: ৳{{ number_format($availableBalance, 2) }}
                        </small>
                    </div>

                    <!-- Payout Method -->
                    <div class="mb-4">
                        <label for="payout_method" class="form-label fw-bold">
                            <i class="fas fa-credit-card"></i> Payout Method *
                        </label>
                        <select class="form-select @error('payout_method') is-invalid @enderror" 
                                id="payout_method" 
                                name="payout_method" 
                                required>
                            <option value="">Select payout method</option>
                            <option value="bank" {{ old('payout_method', $vendorSettings->payout_method) == 'bank' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="bkash" {{ old('payout_method', $vendorSettings->payout_method) == 'bkash' ? 'selected' : '' }}>bKash</option>
                            <option value="nagad" {{ old('payout_method', $vendorSettings->payout_method) == 'nagad' ? 'selected' : '' }}>Nagad</option>
                            <option value="rocket" {{ old('payout_method', $vendorSettings->payout_method) == 'rocket' ? 'selected' : '' }}>Rocket</option>
                        </select>
                        @error('payout_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Account Number -->
                    <div class="mb-3">
                        <label for="account_number" class="form-label fw-bold">
                            <i class="fas fa-hashtag"></i> Account Number *
                        </label>
                        <input type="text" 
                               class="form-control @error('account_number') is-invalid @enderror" 
                               id="account_number" 
                               name="account_number" 
                               value="{{ old('account_number', $vendorSettings->payout_account_number) }}"
                               required>
                        @error('account_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Account Name -->
                    <div class="mb-3">
                        <label for="account_name" class="form-label fw-bold">
                            <i class="fas fa-user"></i> Account Holder Name *
                        </label>
                        <input type="text" 
                               class="form-control @error('account_name') is-invalid @enderror" 
                               id="account_name" 
                               name="account_name" 
                               value="{{ old('account_name', $vendorSettings->payout_account_name) }}"
                               required>
                        @error('account_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bank-specific fields (shown when bank is selected) -->
                    <div id="bankFields" style="display: none;">
                        <div class="mb-3">
                            <label for="payout_bank_name" class="form-label fw-bold">
                                <i class="fas fa-university"></i> Bank Name *
                            </label>
                            <input type="text" 
                                   class="form-control @error('payout_bank_name') is-invalid @enderror" 
                                   id="payout_bank_name" 
                                   name="payout_bank_name" 
                                   value="{{ old('payout_bank_name', $vendorSettings->payout_bank_name) }}">
                            @error('payout_bank_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payout_branch_name" class="form-label">Branch Name</label>
                                <input type="text" 
                                       class="form-control @error('payout_branch_name') is-invalid @enderror" 
                                       id="payout_branch_name" 
                                       name="payout_branch_name" 
                                       value="{{ old('payout_branch_name', $vendorSettings->payout_branch_name) }}">
                                @error('payout_branch_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payout_routing_number" class="form-label">Routing Number</label>
                                <input type="text" 
                                       class="form-control @error('payout_routing_number') is-invalid @enderror" 
                                       id="payout_routing_number" 
                                       name="payout_routing_number" 
                                       value="{{ old('payout_routing_number', $vendorSettings->payout_routing_number) }}">
                                @error('payout_routing_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label for="notes" class="form-label">
                            <i class="fas fa-sticky-note"></i> Additional Notes (Optional)
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Any additional information for this withdrawal request</small>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('vendor.withdrawals.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="col-md-4">
        <div class="card bg-light sticky-top" style="top: 20px;">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Withdrawal Summary</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">Current Balance</small>
                    <h4 class="text-success mb-0">৳{{ number_format($currentBalance, 2) }}</h4>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Pending Withdrawals</small>
                    <h5 class="text-warning mb-0">৳{{ number_format($pendingWithdrawals, 2) }}</h5>
                </div>

                <hr>

                <div class="mb-3">
                    <small class="text-muted">Available for Withdrawal</small>
                    <h4 class="text-primary mb-0">৳{{ number_format($availableBalance, 2) }}</h4>
                </div>

                <div class="alert alert-info mb-0">
                    <small>
                        <i class="fas fa-clock"></i> 
                        <strong>Processing Time:</strong> 
                        Withdrawals are typically processed within 7 business days.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show/hide bank fields based on payment method
document.getElementById('payout_method').addEventListener('change', function() {
    const bankFields = document.getElementById('bankFields');
    const bankNameField = document.getElementById('payout_bank_name');
    
    if (this.value === 'bank') {
        bankFields.style.display = 'block';
        bankNameField.setAttribute('required', 'required');
    } else {
        bankFields.style.display = 'none';
        bankNameField.removeAttribute('required');
    }
});

// Trigger change event on page load to show bank fields if bank is selected
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('payout_method').dispatchEvent(new Event('change'));
});

// Validate withdrawal amount
document.getElementById('amount').addEventListener('input', function() {
    const amount = parseFloat(this.value);
    const min = parseFloat(this.getAttribute('min'));
    const max = parseFloat(this.getAttribute('max'));
    
    if (amount < min) {
        this.setCustomValidity('Amount must be at least ৳' + min.toFixed(2));
    } else if (amount > max) {
        this.setCustomValidity('Amount cannot exceed available balance of ৳' + max.toFixed(2));
    } else {
        this.setCustomValidity('');
    }
});
</script>
@endpush

