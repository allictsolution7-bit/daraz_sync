@extends('layouts.master')

@section('title', 'Withdrawal Details #' . $withdrawal->id)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-receipt"></i> Withdrawal Details #{{ $withdrawal->id }}</h2>
                <a href="{{ route('admin.vendor-withdrawals.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Withdrawal Details -->
        <div class="col-md-8">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">৳{{ number_format($withdrawal->amount, 2) }}</h3>
                            <p class="text-muted mb-0">Withdrawal Amount</p>
                        </div>
                        <div>
                            @switch($withdrawal->status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark fs-5">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                    @break
                                @case('approved')
                                    <span class="badge bg-info fs-5">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </span>
                                    @break
                                @case('processing')
                                    <span class="badge bg-primary fs-5">
                                        <i class="fas fa-gear"></i> Processing
                                    </span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success fs-5">
                                        <i class="fas fa-check-circle-fill"></i> Completed
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger fs-5">
                                        <i class="fas fa-x-circle"></i> Rejected
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vendor Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-person"></i> Vendor Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Vendor Name:</strong></td>
                                    <td>
                                        <a href="{{ route('admin.vendors.show', $withdrawal->vendor) }}">
                                            {{ $withdrawal->vendor->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Business Name:</strong></td>
                                    <td>{{ $withdrawal->vendor->vendorSettings->business_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $withdrawal->vendor->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $withdrawal->vendor->phone ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Current Balance:</strong></td>
                                    <td class="text-success">
                                        ৳{{ number_format($vendorBalance, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Earnings:</strong></td>
                                    <td>৳{{ number_format($vendorStats['total_earnings'], 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Products:</strong></td>
                                    <td>{{ $vendorStats['total_products'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Orders:</strong></td>
                                    <td>{{ $vendorStats['total_orders'] }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-credit-card"></i> Payment Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Payment Method:</strong></td>
                                    <td class="text-capitalize">
                                        <span class="badge bg-secondary">{{ $withdrawal->method }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Account Number:</strong></td>
                                    <td>{{ $withdrawal->account_details['account_number'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Account Name:</strong></td>
                                    <td>{{ $withdrawal->account_details['account_name'] ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        @if($withdrawal->method === 'bank')
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%"><strong>Bank Name:</strong></td>
                                        <td>{{ $withdrawal->account_details['bank_name'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Branch:</strong></td>
                                        <td>{{ $withdrawal->account_details['branch_name'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Routing Number:</strong></td>
                                        <td>{{ $withdrawal->account_details['routing_number'] ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Request Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Request Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="25%"><strong>Request Date:</strong></td>
                            <td>{{ $withdrawal->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @if($withdrawal->processed_at)
                            <tr>
                                <td><strong>Processed Date:</strong></td>
                                <td>{{ $withdrawal->processed_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @endif
                        @if($withdrawal->processed_by)
                            <tr>
                                <td><strong>Processed By:</strong></td>
                                <td>{{ $withdrawal->processedBy->name ?? 'Admin' }}</td>
                            </tr>
                        @endif
                        @if($withdrawal->note)
                            <tr>
                                <td><strong>Vendor Note:</strong></td>
                                <td>
                                    <div class="alert alert-info mb-0">
                                        {{ $withdrawal->note }}
                                    </div>
                                </td>
                            </tr>
                        @endif
                        @if($withdrawal->admin_note)
                            <tr>
                                <td><strong>Admin Note:</strong></td>
                                <td>
                                    <div class="alert alert-warning mb-0">
                                        {{ $withdrawal->admin_note }}
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Order Items Included in This Withdrawal -->
            @if($orderItems->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Order Items Paid ({{ $orderItems->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Commission</th>
                                        <th>Earning</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderItems as $item)
                                        <tr>
                                            <td>{{ $item->order->order_number ?? '#' . $item->order_id }}</td>
                                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>৳{{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->vendor_commission_rate }}%</td>
                                            <td>৳{{ number_format($item->vendor_earning, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <td colspan="5" class="text-end"><strong>Total:</strong></td>
                                        <td><strong>৳{{ number_format($orderItems->sum('vendor_earning'), 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Actions & Status -->
        <div class="col-md-4">
            <!-- Actions -->
            @if($withdrawal->status == 'pending')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-lightning"></i> Actions</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.vendor-withdrawals.approve', $withdrawal) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Admin Note (Optional)</label>
                                <textarea name="admin_note" class="form-control" rows="3" placeholder="Add notes for approval"></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Approve this withdrawal request?')">
                                    <i class="fas fa-check-circle"></i> Approve Withdrawal
                                </button>
                            </div>
                        </form>

                        <hr>

                        <button type="button" class="btn btn-danger btn-lg w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-x-circle"></i> Reject Withdrawal
                        </button>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.vendor-withdrawals.reject', $withdrawal) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Withdrawal</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Reason for Rejection *</label>
                                                <textarea name="reason" class="form-control" rows="4" required placeholder="Explain why this withdrawal is being rejected"></textarea>
                                            </div>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                This will notify the vendor and return ৳{{ number_format($withdrawal->amount, 2) }} to their balance.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject Withdrawal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($withdrawal->status == 'approved')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-lightning"></i> Complete Payment</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.vendor-withdrawals.complete', $withdrawal) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Transaction ID (Optional)</label>
                                <input type="text" name="transaction_id" class="form-control" placeholder="Bank/Payment gateway transaction ID">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Admin Note (Optional)</label>
                                <textarea name="admin_note" class="form-control" rows="3" placeholder="Add notes about payment"></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Mark this withdrawal as completed?')">
                                    <i class="fas fa-check2-all"></i> Mark as Completed
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Status History -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock-history"></i> Status Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <strong>Request Created</strong>
                                <br><small class="text-muted">{{ $withdrawal->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>

                        @if($withdrawal->approved_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <strong>Approved</strong>
                                    <br><small class="text-muted">{{ $withdrawal->approved_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </div>
                        @endif

                        @if($withdrawal->status == 'completed' && $withdrawal->paid_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <strong>Payment Completed</strong>
                                    <br><small class="text-muted">{{ $withdrawal->paid_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </div>
                        @endif

                        @if($withdrawal->status == 'rejected')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <strong>Rejected</strong>
                                    <br><small class="text-muted">{{ $withdrawal->processed_at->format('d M Y, h:i A') }}</small>
                                    @if($withdrawal->admin_note)
                                        <br><small class="text-muted">Reason: {{ $withdrawal->admin_note }}</small>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-graph-up"></i> Vendor Stats</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Total Earnings:</strong></td>
                            <td class="text-end">৳{{ number_format($vendorStats['total_earnings'], 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Current Balance:</strong></td>
                            <td class="text-end text-success">৳{{ number_format($vendorBalance, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Withdrawn:</strong></td>
                            <td class="text-end">৳{{ number_format($vendorStats['total_withdrawn'], 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Pending:</strong></td>
                            <td class="text-end text-warning">৳{{ number_format($vendorStats['pending_withdrawals'], 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -23px;
    top: 20px;
    height: 100%;
    width: 2px;
    background: #dee2e6;
}

.timeline-marker {
    position: absolute;
    left: -28px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content {
    padding-left: 10px;
}
</style>
@endpush
@endsection

