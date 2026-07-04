@extends('vendor.layouts.app')

@section('title', 'Withdrawal Requests')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2><i class="fas fa-money-bill-wave"></i> Withdrawal Requests</h2>
    </div>
</div>

<!-- Balance Summary -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">৳{{ number_format($vendorService->getCurrentBalance(auth()->id()), 2) }}</h4>
                        <p class="mb-0">Current Balance</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-wallet fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">৳{{ number_format($vendorService->getPendingEarnings(auth()->id()), 2) }}</h4>
                        <p class="mb-0">Pending Earnings</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">৳{{ number_format($vendorService->getTotalEarnings(auth()->id()), 2) }}</h4>
                        <p class="mb-0">Total Earnings</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-graph-up fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column - Request Withdrawal -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Request Withdrawal</h5>
            </div>
            <div class="card-body">
                @php
                    $currentBalance = $vendorService->getCurrentBalance(auth()->id());
                    $minWithdrawal = $vendorSettings->getMinWithdrawalAmount();
                    $canWithdraw = $currentBalance >= $minWithdrawal;
                @endphp

                @if($canWithdraw)
                    <form action="{{ route('vendor.withdrawals.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Withdrawal Amount *</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" 
                                       name="amount" 
                                       class="form-control @error('amount') is-invalid @enderror" 
                                       value="{{ old('amount') }}"
                                       min="{{ $minWithdrawal }}"
                                       max="{{ $currentBalance }}"
                                       step="0.01"
                                       required>
                            </div>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Minimum: ৳{{ number_format($minWithdrawal, 2) }} | 
                                Available: ৳{{ number_format($currentBalance, 2) }}
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payout Method</label>
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
                            <label class="form-label">Account Number</label>
                            <input type="text" 
                                   name="account_number" 
                                   class="form-control @error('account_number') is-invalid @enderror" 
                                   value="{{ old('account_number', $vendorSettings->payout_account_number) }}"
                                   placeholder="Account number or mobile number">
                            @error('account_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Account Name</label>
                            <input type="text" 
                                   name="account_name" 
                                   class="form-control @error('account_name') is-invalid @enderror" 
                                   value="{{ old('account_name', $vendorSettings->payout_account_name) }}"
                                   placeholder="Name as per account">
                            @error('account_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      rows="3"
                                      placeholder="Any additional notes for this withdrawal">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Processing Time:</strong> 2-5 business days<br>
                            <strong>Fee:</strong> No processing fees
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-send"></i> Request Withdrawal
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-warning fs-1"></i>
                        <h5 class="mt-3">Insufficient Balance</h5>
                        <p class="text-muted">
                            You need at least ৳{{ number_format($minWithdrawal, 2) }} to request a withdrawal.
                        </p>
                        <p class="text-muted">
                            Current balance: ৳{{ number_format($currentBalance, 2) }}
                        </p>
                        <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-box"></i> View Orders
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Payout Information -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-gear"></i> Payout Settings</h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Method:</strong> 
                    <span class="text-capitalize">{{ $vendorSettings->payout_method ?? 'Not set' }}</span>
                </div>
                <div class="mb-2">
                    <strong>Account:</strong> 
                    {{ $vendorSettings->payout_account_number ?? 'Not set' }}
                </div>
                <div class="mb-2">
                    <strong>Name:</strong> 
                    {{ $vendorSettings->payout_account_name ?? 'Not set' }}
                </div>
                <hr>
                <a href="{{ route('vendor.profile') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-pencil"></i> Update Payout Info
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column - Withdrawal History -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list-ul"></i> Withdrawal History</h5>
            </div>
            <div class="card-body">
                @if($withdrawals->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Processed</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($withdrawals as $withdrawal)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $withdrawal->created_at->format('d M Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="text-primary">৳{{ number_format($withdrawal->amount, 2) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary text-capitalize">
                                                {{ $withdrawal->payout_method }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $withdrawal->account_number }}</small>
                                        </td>
                                        <td>
                                            @switch($withdrawal->status)
                                                @case('pending')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock"></i> Pending
                                                    </span>
                                                    @break
                                                @case('approved')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-check-circle"></i> Approved
                                                    </span>
                                                    @break
                                                @case('processing')
                                                    <span class="badge bg-primary">
                                                        <i class="fas fa-gear"></i> Processing
                                                    </span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle-fill"></i> Completed
                                                    </span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle"></i> Rejected
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $withdrawal->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($withdrawal->processed_at)
                                                <div>
                                                    <strong>{{ $withdrawal->processed_at->format('d M Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $withdrawal->processed_at->format('h:i A') }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-outline-primary btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#withdrawalModal{{ $withdrawal->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($withdrawal->status == 'pending')
                                                    <form action="{{ route('vendor.withdrawals.cancel', $withdrawal) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to cancel this withdrawal request?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="fas fa-times-circle"></i>
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

                    <!-- Pagination -->
                    @if($withdrawals->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $withdrawals->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-money-bill-wave text-muted fs-1"></i>
                        <h5 class="mt-3 text-muted">No Withdrawals Yet</h5>
                        <p class="text-muted">You haven't made any withdrawal requests yet.</p>
                        @if($canWithdraw)
                            <p class="text-muted">Start by requesting your first withdrawal!</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Withdrawal Detail Modals -->
@foreach($withdrawals as $withdrawal)
    <div class="modal fade" id="withdrawalModal{{ $withdrawal->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Withdrawal Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Request Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Request ID:</strong></td>
                                    <td>#{{ $withdrawal->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td class="text-primary">৳{{ number_format($withdrawal->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Method:</strong></td>
                                    <td class="text-capitalize">{{ $withdrawal->payout_method }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Account:</strong></td>
                                    <td>{{ $withdrawal->account_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Account Name:</strong></td>
                                    <td>{{ $withdrawal->account_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Request Date:</strong></td>
                                    <td>{{ $withdrawal->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Processing Information</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @switch($withdrawal->status)
                                            @case('pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-info">Approved</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-primary">Processing</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">Completed</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                @if($withdrawal->processed_at)
                                    <tr>
                                        <td><strong>Processed:</strong></td>
                                        <td>{{ $withdrawal->processed_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                @endif
                                @if($withdrawal->processed_by)
                                    <tr>
                                        <td><strong>Processed By:</strong></td>
                                        <td>{{ $withdrawal->processedBy->name ?? 'Admin' }}</td>
                                    </tr>
                                @endif
                                @if($withdrawal->rejection_reason)
                                    <tr>
                                        <td><strong>Rejection Reason:</strong></td>
                                        <td class="text-danger">{{ $withdrawal->rejection_reason }}</td>
                                    </tr>
                                @endif
                                @if($withdrawal->admin_notes)
                                    <tr>
                                        <td><strong>Admin Notes:</strong></td>
                                        <td>{{ $withdrawal->admin_notes }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    @if($withdrawal->notes)
                        <hr>
                        <h6>Your Notes</h6>
                        <p class="text-muted">{{ $withdrawal->notes }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill amount field with current balance
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