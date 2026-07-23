@extends('vendor.layouts.app')

@section('title', 'Withdrawal Management')

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

    /* Glassmorphic Cards & Layout */
    .withdrawal-header-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        padding: 0.85rem 1.25rem;
        border-radius: 14px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .metric-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 14px;
        padding: 1rem 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
    }

    .metric-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }

    .metric-icon-box.balance {
        background: rgba(99, 102, 241, 0.12);
        color: #6366f1;
    }

    .metric-icon-box.pending {
        background: rgba(245, 158, 11, 0.12);
        color: #d97706;
    }

    .metric-icon-box.total {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
    }

    .v-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
    }

    .v-card .card-header {
        background: rgba(248, 250, 252, 0.7);
        border-bottom: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        padding: 0.45rem 0.85rem;
        font-weight: 500;
        font-size: 0.85rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
    }

    .table-custom {
        margin-bottom: 0;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e2e8f0;
        padding: 0.65rem 0.85rem;
    }

    .table-custom tbody td {
        padding: 0.65rem 0.85rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.825rem;
    }

    .table-custom tbody tr:hover {
        background-color: rgba(248, 250, 252, 0.8);
    }

    /* Status Badges */
    .badge-status {
        padding: 4px 10px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-status.pending { background: rgba(245, 158, 11, 0.1); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.2); }
    .badge-status.approved { background: rgba(6, 182, 212, 0.1); color: #0891b2; border: 1px solid rgba(6, 182, 212, 0.2); }
    .badge-status.processing { background: rgba(99, 102, 241, 0.1); color: #4f46e5; border: 1px solid rgba(99, 102, 241, 0.2); }
    .badge-status.completed { background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.2); }
    .badge-status.rejected { background: rgba(239, 68, 68, 0.1); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.2); }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">
    <!-- Header Card -->
    <div class="d-flex justify-content-between align-items-center mb-3 withdrawal-header-card">
        <div class="d-flex align-items-center gap-3">
            <div class="metric-icon-box balance">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div>
                <h5 class="fw-800 mb-0 text-dark">Payout & Withdrawal Management</h5>
                <p class="text-muted small mb-0 fs-8">Request payout funds and view your withdrawal transaction history.</p>
            </div>
        </div>
        <div>
            <a href="{{ route('vendor.orders.earnings') }}" class="btn btn-outline-secondary rounded-3 px-3 fw-bold btn-sm fs-8">
                <i class="fas fa-chart-line me-1"></i> Earnings Summary
            </a>
        </div>
    </div>

    <!-- Balance Summary Metrics -->
    <div class="row g-3 mb-3">
        <!-- Current Balance -->
        <div class="col-md-4">
            <div class="metric-card h-100" style="border-left: 3px solid #6366f1;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted fw-bold text-uppercase fs-8 tracking-wider d-block mb-1">Available Balance</span>
                        <h4 class="fw-800 text-dark mb-0 fs-5">৳{{ number_format($vendorService->getCurrentBalance(auth()->id()), 2) }}</h4>
                        <small class="text-muted fs-8">Ready for withdrawal</small>
                    </div>
                    <div class="metric-icon-box balance">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Earnings -->
        <div class="col-md-4">
            <div class="metric-card h-100" style="border-left: 3px solid #f59e0b;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted fw-bold text-uppercase fs-8 tracking-wider d-block mb-1">Pending Earnings</span>
                        <h4 class="fw-800 text-warning mb-0 fs-5">৳{{ number_format($vendorService->getPendingEarnings(auth()->id()), 2) }}</h4>
                        <small class="text-muted fs-8">Uncleared / pending order funds</small>
                    </div>
                    <div class="metric-icon-box pending">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="col-md-4">
            <div class="metric-card h-100" style="border-left: 3px solid #10b981;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted fw-bold text-uppercase fs-8 tracking-wider d-block mb-1">Lifetime Earnings</span>
                        <h4 class="fw-800 text-success mb-0 fs-5">৳{{ number_format($vendorService->getTotalEarnings(auth()->id()), 2) }}</h4>
                        <small class="text-muted fs-8">Total earned to date</small>
                    </div>
                    <div class="metric-icon-box total">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-4">
        <!-- Left Column: Payout Request Form & Settings -->
        <div class="col-lg-4">
            <div class="v-card mb-4">
                <div class="card-header">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-paper-plane text-primary"></i> Request Withdrawal
                    </h5>
                </div>
                <div class="card-body p-4">
                    @php
                        $currentBalance = $vendorService->getCurrentBalance(auth()->id());
                        $minWithdrawal = $vendorSettings->getMinWithdrawalAmount();
                        $canWithdraw = $currentBalance >= $minWithdrawal;
                    @endphp

                    @if($canWithdraw)
                        <form action="{{ route('vendor.withdrawals.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark fs-8 uppercase">Amount (৳) *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light fw-bold">৳</span>
                                    <input type="number" 
                                           name="amount" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           value="{{ old('amount') }}"
                                           min="{{ $minWithdrawal }}"
                                           max="{{ $currentBalance }}"
                                           step="0.01"
                                           placeholder="0.00"
                                           required>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">
                                    Min: ৳{{ number_format($minWithdrawal, 2) }} | Max: ৳{{ number_format($currentBalance, 2) }}
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark fs-8 uppercase">Payout Method *</label>
                                <select name="payout_method" 
                                        class="form-select @error('payout_method') is-invalid @enderror" required>
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
                                <label class="form-label fw-bold text-dark fs-8 uppercase">Account / Mobile Number *</label>
                                <input type="text" 
                                       name="account_number" 
                                       class="form-control @error('account_number') is-invalid @enderror" 
                                       value="{{ old('account_number', $vendorSettings->payout_account_number) }}"
                                       placeholder="Account number or wallet number" required>
                                @error('account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark fs-8 uppercase">Account Holder Name *</label>
                                <input type="text" 
                                       name="account_name" 
                                       class="form-control @error('account_name') is-invalid @enderror" 
                                       value="{{ old('account_name', $vendorSettings->payout_account_name) }}"
                                       placeholder="Name on account" required>
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark fs-8 uppercase">Notes (Optional)</label>
                                <textarea name="notes" 
                                          class="form-control @error('notes') is-invalid @enderror" 
                                          rows="2"
                                          placeholder="Additional payout instructions">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info border-0 shadow-sm rounded-3 p-3 mb-3" style="background: rgba(6, 182, 212, 0.1); color: #0891b2;">
                                <div class="d-flex gap-2">
                                    <i class="fas fa-info-circle fs-5 mt-1"></i>
                                    <div class="fs-8">
                                        <strong>Processing Time:</strong> 2-5 business days.<br>
                                        <strong>Processing Fee:</strong> ৳0 (No charge)
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 rounded-3 py-2.5 fw-bold shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;">
                                <i class="fas fa-paper-plane me-1"></i> Submit Request
                            </button>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <div class="metric-icon-box pending mx-auto mb-3" style="width: 64px; height: 64px; font-size: 1.75rem;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Insufficient Balance</h5>
                            <p class="text-muted small mb-3">
                                Minimum withdrawal threshold is <strong>৳{{ number_format($minWithdrawal, 2) }}</strong>.<br>
                                Your current balance: <strong>৳{{ number_format($currentBalance, 2) }}</strong>
                            </p>
                            <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-primary rounded-3 btn-sm fw-bold">
                                <i class="fas fa-box me-1"></i> View Orders
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Saved Payout Credentials -->
            <div class="v-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-cog text-primary"></i> Payout Settings
                    </h5>
                    <a href="{{ route('vendor.profile') }}" class="btn btn-outline-primary btn-sm rounded-3 fw-bold fs-8">
                        Edit
                    </a>
                </div>
                <div class="card-body p-4 fs-8">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Default Method:</span>
                        <span class="fw-bold text-dark text-capitalize">{{ $vendorSettings->payout_method ?? 'Not set' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Account Number:</span>
                        <span class="fw-bold text-dark">{{ $vendorSettings->payout_account_number ?? 'Not set' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Account Name:</span>
                        <span class="fw-bold text-dark">{{ $vendorSettings->payout_account_name ?? 'Not set' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Withdrawal History Table -->
        <div class="col-lg-8">
            <div class="v-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                        <i class="fas fa-history text-primary"></i> Withdrawal Request History
                    </h5>
                    <span class="badge bg-secondary rounded-pill px-3 py-1 font-semibold fs-8">{{ $withdrawals->total() }} requests</span>
                </div>
                <div class="card-body p-0">
                    @if($withdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Method & Account</th>
                                        <th class="text-center">Status</th>
                                        <th>Processed Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($withdrawals as $withdrawal)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-dark">{{ $withdrawal->created_at->format('d M, Y') }}</span>
                                                <small class="d-block text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-800 text-primary fs-6">৳{{ number_format($withdrawal->amount, 2) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark text-capitalize font-semibold border px-2 py-1 mb-1">
                                                    {{ $withdrawal->payout_method }}
                                                </span>
                                                <small class="d-block text-muted">{{ $withdrawal->account_number }}</small>
                                            </td>
                                            <td class="text-center">
                                                @switch($withdrawal->status)
                                                    @case('pending')
                                                        <span class="badge-status pending">
                                                            <i class="fas fa-clock"></i> Pending
                                                        </span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge-status approved">
                                                            <i class="fas fa-check-circle"></i> Approved
                                                        </span>
                                                        @break
                                                    @case('processing')
                                                        <span class="badge-status processing">
                                                            <i class="fas fa-spinner fa-spin"></i> Processing
                                                        </span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge-status completed">
                                                            <i class="fas fa-check-double"></i> Completed
                                                        </span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge-status rejected">
                                                            <i class="fas fa-times-circle"></i> Rejected
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $withdrawal->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($withdrawal->processed_at)
                                                    <span class="fw-bold text-dark">{{ $withdrawal->processed_at->format('d M, Y') }}</span>
                                                    <small class="d-block text-muted">{{ $withdrawal->processed_at->format('h:i A') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <button type="button" 
                                                            class="btn btn-outline-primary btn-sm rounded-2" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#withdrawalModal{{ $withdrawal->id }}"
                                                            title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @if($withdrawal->status == 'pending')
                                                        <form action="{{ route('vendor.withdrawals.cancel', $withdrawal) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Are you sure you want to cancel this withdrawal request?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-2" title="Cancel Request">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($withdrawals->hasPages())
                            <div class="p-4 border-top">
                                {{ $withdrawals->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="metric-icon-box balance mx-auto mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                                <i class="fas fa-inbox text-muted"></i>
                            </div>
                            <h5 class="fw-bold text-dark">No Withdrawal Records</h5>
                            <p class="text-muted small max-w-sm mx-auto">You haven't submitted any withdrawal requests yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Withdrawal Modals -->
@foreach($withdrawals as $withdrawal)
    <div class="modal fade" id="withdrawalModal{{ $withdrawal->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-light rounded-top-4">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fas fa-file-invoice-dollar text-primary me-2"></i> Withdrawal Request Details #{{ $withdrawal->id }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Request Summary</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Amount:</span>
                                    <span class="fw-800 text-primary fs-6">৳{{ number_format($withdrawal->amount, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Method:</span>
                                    <span class="fw-bold text-dark text-capitalize">{{ $withdrawal->payout_method }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Account Number:</span>
                                    <span class="fw-bold text-dark">{{ $withdrawal->account_number }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Account Holder:</span>
                                    <span class="fw-bold text-dark">{{ $withdrawal->account_name }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Submitted Date:</span>
                                    <span class="fw-bold text-dark">{{ $withdrawal->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Processing Status</h6>
                                <div class="d-flex justify-content-between mb-2 align-items-center">
                                    <span class="text-muted">Status:</span>
                                    <span>
                                        @switch($withdrawal->status)
                                            @case('pending')
                                                <span class="badge-status pending"><i class="fas fa-clock"></i> Pending</span>
                                                @break
                                            @case('approved')
                                                <span class="badge-status approved"><i class="fas fa-check-circle"></i> Approved</span>
                                                @break
                                            @case('processing')
                                                <span class="badge-status processing"><i class="fas fa-spinner fa-spin"></i> Processing</span>
                                                @break
                                            @case('completed')
                                                <span class="badge-status completed"><i class="fas fa-check-double"></i> Completed</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge-status rejected"><i class="fas fa-times-circle"></i> Rejected</span>
                                                @break
                                        @endswitch
                                    </span>
                                </div>
                                @if($withdrawal->processed_at)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Processed Date:</span>
                                        <span class="fw-bold text-dark">{{ $withdrawal->processed_at->format('d M Y, h:i A') }}</span>
                                    </div>
                                @endif
                                @if($withdrawal->rejection_reason)
                                    <div class="mt-2 text-danger">
                                        <strong>Rejection Reason:</strong>
                                        <p class="mb-0 small">{{ $withdrawal->rejection_reason }}</p>
                                    </div>
                                @endif
                                @if($withdrawal->admin_notes)
                                    <div class="mt-2">
                                        <strong>Admin Notes:</strong>
                                        <p class="mb-0 small text-muted">{{ $withdrawal->admin_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($withdrawal->notes)
                        <div class="mt-3 p-3 border rounded-3">
                            <strong class="text-dark">Your Notes:</strong>
                            <p class="text-muted mb-0 small">{{ $withdrawal->notes }}</p>
                        </div>
                    @endif
                </div>
                <div class="modal-footer rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-3 px-4 fw-bold" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.querySelector('input[name="amount"]');
    const currentBalance = {{ $vendorService->getCurrentBalance(auth()->id()) }};
    
    if (amountInput && currentBalance > 0) {
        amountInput.addEventListener('focus', function() {
            if (!this.value) {
                this.value = currentBalance;
            }
        });
    }
});
</script>
@endpush