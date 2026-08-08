@extends('layouts.master')

@section('title', 'Payout Request Details #' . $withdrawal->id)

@section('styles')
<style>
    .vp-page-wrapper {
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
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #38bdf8;
    }
    .vp-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-bottom: 24px;
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
    .vp-page-wrapper .form-control, .vp-page-wrapper .form-select {
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 500;
        background-color: #f8fafc;
    }
    .badge-soft-success {
        background: #dcfce7;
        color: #15803d;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
    }
    .badge-soft-warning {
        background: #fef3c7;
        color: #b45309;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
    }
    .badge-soft-info {
        background: #e0f2fe;
        color: #0369a1;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
    }
    .badge-soft-danger {
        background: #fee2e2;
        color: #b91c1c;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
    }
    .timeline {
        position: relative;
        padding-left: 24px;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    .timeline-item:not(:last-child):before {
        content: '';
        position: absolute;
        left: -17px;
        top: 18px;
        height: calc(100% - 10px);
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #fff;
    }
</style>
@endsection

@section('content')
<div class="vp-page-wrapper">
    <!-- Header Banner -->
    <div class="vp-hero-card d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="vp-title-icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div>
                <h3 class="mb-0 text-white font-weight-bold">Payout Request #{{ $withdrawal->id }}</h3>
                <p class="mb-0 text-white-50 small">Partner: {{ $withdrawal->vendor->name }} ({{ $withdrawal->vendor->vendorSettings->business_name ?? 'N/A' }})</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.vendor-withdrawals.index') }}" class="btn btn-secondary rounded-3 px-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Payouts List
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column: Details -->
        <div class="col-lg-8">
            <!-- Amount & Status Card -->
            <div class="vp-card">
                <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <span class="text-xs font-weight-bold uppercase text-muted d-block mb-1">Payout Amount</span>
                        <h2 class="mb-0 font-weight-bold text-success">৳{{ number_format($withdrawal->amount, 2) }}</h2>
                    </div>
                    <div class="text-end">
                        <span class="text-xs font-weight-bold uppercase text-muted d-block mb-1">Status</span>
                        @switch($withdrawal->status)
                            @case('pending')
                                <span class="badge-soft-warning"><i class="fas fa-clock me-1"></i> Pending Approval</span>
                                @break
                            @case('approved')
                                <span class="badge-soft-info"><i class="fas fa-thumbs-up me-1"></i> Approved</span>
                                @break
                            @case('processing')
                                <span class="badge-soft-info"><i class="fas fa-cog me-1 fa-spin"></i> Processing</span>
                                @break
                            @case('completed')
                                <span class="badge-soft-success"><i class="fas fa-check-circle me-1"></i> Completed & Paid</span>
                                @break
                            @case('rejected')
                                <span class="badge-soft-danger"><i class="fas fa-times-circle me-1"></i> Rejected</span>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>

            <!-- Partner Info -->
            <div class="vp-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-user-circle text-primary"></i>
                    <h5>Partner Account Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tr>
                                <th width="30%" class="text-muted">Partner Name:</th>
                                <td class="font-weight-bold text-dark">
                                    <a href="{{ route('admin.vendors.show', $withdrawal->vendor) }}" class="text-dark text-decoration-none">
                                        {{ $withdrawal->vendor->name }} <i class="fas fa-arrow-up-right-from-square text-muted ms-1" style="font-size:11px;"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-muted">Business Name:</th>
                                <td>{{ $withdrawal->vendor->vendorSettings->business_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Email:</th>
                                <td>{{ $withdrawal->vendor->email }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Phone:</th>
                                <td>{{ $withdrawal->vendor->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Wallet Balance:</th>
                                <td class="font-weight-bold text-success">৳{{ number_format($vendorBalance, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="vp-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-credit-card text-primary"></i>
                    <h5>Bank & Destination Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tr>
                                <th width="30%" class="text-muted">Payment Method:</th>
                                <td><span class="badge bg-light text-dark border font-weight-bold px-3 py-1 text-uppercase">{{ $withdrawal->method }}</span></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Account Number:</th>
                                <td class="font-weight-bold text-dark fs-6"><code>{{ $withdrawal->account_details['account_number'] ?? 'N/A' }}</code></td>
                            </tr>
                            <tr>
                                <th class="text-muted">Account Name:</th>
                                <td>{{ $withdrawal->account_details['account_name'] ?? 'N/A' }}</td>
                            </tr>
                            @if($withdrawal->method === 'bank')
                                <tr>
                                    <th class="text-muted">Bank Name:</th>
                                    <td>{{ $withdrawal->account_details['bank_name'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Branch Name:</th>
                                    <td>{{ $withdrawal->account_details['branch_name'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Routing Number:</th>
                                    <td>{{ $withdrawal->account_details['routing_number'] ?? 'N/A' }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <!-- Request Timestamps & Notes -->
            <div class="vp-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle text-primary"></i>
                    <h5>Request Details & Notes</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless align-middle mb-0">
                        <tr>
                            <th width="30%" class="text-muted">Request Date:</th>
                            <td>{{ $withdrawal->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @if($withdrawal->processed_at)
                            <tr>
                                <th class="text-muted">Processed Date:</th>
                                <td>{{ $withdrawal->processed_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @endif
                        @if($withdrawal->processed_by)
                            <tr>
                                <th class="text-muted">Processed By:</th>
                                <td>{{ $withdrawal->processedBy->name ?? 'Admin' }}</td>
                            </tr>
                        @endif
                        @if($withdrawal->note)
                            <tr>
                                <th class="text-muted">Vendor Note:</th>
                                <td>
                                    <div class="p-3 bg-light rounded-3 text-dark small border">
                                        {{ $withdrawal->note }}
                                    </div>
                                </td>
                            </tr>
                        @endif
                        @if($withdrawal->admin_note)
                            <tr>
                                <th class="text-muted">Admin Note:</th>
                                <td>
                                    <div class="p-3 bg-warning bg-opacity-10 text-dark rounded-3 small border border-warning">
                                        {{ $withdrawal->admin_note }}
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Order Items Included in Withdrawal -->
            @if(isset($orderItems) && $orderItems->count() > 0)
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-box text-primary"></i>
                        <h5>Order Items Paid ({{ $orderItems->count() }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table vp-table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Order #</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Commission</th>
                                        <th class="pe-4 text-end">Earning</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orderItems as $item)
                                        <tr>
                                            <td class="ps-4 font-weight-bold text-dark">{{ $item->order->order_number ?? '#' . $item->order_id }}</td>
                                            <td>{{ $item->product->title ?? 'N/A' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>৳{{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->vendor_commission_rate }}%</td>
                                            <td class="pe-4 text-end font-weight-bold text-success">৳{{ number_format($item->vendor_earning, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions, Timeline & Quick Stats -->
        <div class="col-lg-4">
            <!-- Pending Actions -->
            @if($withdrawal->status == 'pending')
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-bolt text-warning"></i>
                        <h5>Approval Actions</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.vendor-withdrawals.approve', $withdrawal) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="text-xs font-weight-bold uppercase text-muted mb-1 d-block">Admin Note (Optional)</label>
                                <textarea name="admin_note" class="form-control" rows="3" placeholder="Add notes for approval"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100 rounded-3 py-2 font-weight-bold mb-3" onclick="return confirm('Approve this withdrawal request?')">
                                <i class="fas fa-check-circle me-1"></i> Approve Withdrawal
                            </button>
                        </form>

                        <button type="button" class="btn btn-outline-danger w-100 rounded-3 py-2 font-weight-bold" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times-circle me-1"></i> Reject Withdrawal
                        </button>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.vendor-withdrawals.reject', $withdrawal) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title font-weight-bold text-dark">Reject Withdrawal</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Reason for Rejection *</label>
                                                <textarea name="reason" class="form-control" rows="3" required placeholder="Explain why this withdrawal is being rejected"></textarea>
                                            </div>
                                            <div class="alert alert-warning mb-0 small">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                This will notify the partner and return ৳{{ number_format($withdrawal->amount, 2) }} to their balance.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger font-weight-bold">Reject Withdrawal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Approved State Action -->
            @if($withdrawal->status == 'approved')
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-check-double text-primary"></i>
                        <h5>Complete Payment</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.vendor-withdrawals.complete', $withdrawal) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="text-xs font-weight-bold uppercase text-muted mb-1 d-block">Transaction ID (Optional)</label>
                                <input type="text" name="transaction_id" class="form-control" placeholder="Bank/Payment gateway transaction ID">
                            </div>
                            <div class="mb-3">
                                <label class="text-xs font-weight-bold uppercase text-muted mb-1 d-block">Admin Note (Optional)</label>
                                <textarea name="admin_note" class="form-control" rows="3" placeholder="Add notes about payment"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 font-weight-bold" onclick="return confirm('Mark this withdrawal as completed?')">
                                <i class="fas fa-check-circle me-1"></i> Mark as Completed
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Status Timeline -->
            <div class="vp-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-history text-primary"></i>
                    <h5>Status Timeline</h5>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <strong class="text-dark font-weight-bold">Request Created</strong>
                                <br><small class="text-muted">{{ $withdrawal->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        </div>

                        @if($withdrawal->approved_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <strong class="text-dark font-weight-bold">Approved</strong>
                                    <br><small class="text-muted">{{ $withdrawal->approved_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </div>
                        @endif

                        @if($withdrawal->status == 'completed' && $withdrawal->paid_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <strong class="text-dark font-weight-bold">Payment Completed</strong>
                                    <br><small class="text-muted">{{ $withdrawal->paid_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </div>
                        @endif

                        @if($withdrawal->status == 'rejected')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <strong class="text-dark font-weight-bold">Rejected</strong>
                                    <br><small class="text-muted">{{ $withdrawal->processed_at ? $withdrawal->processed_at->format('d M Y, h:i A') : '' }}</small>
                                    @if($withdrawal->admin_note)
                                        <br><small class="text-muted">Reason: {{ $withdrawal->admin_note }}</small>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Partner Stats -->
            <div class="vp-card">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-chart-line text-primary"></i>
                    <h5>Partner Earnings Stats</h5>
                </div>
                <div class="card-body p-4">
                    <table class="table table-borderless align-middle mb-0">
                        <tr>
                            <td class="text-muted">Total Earnings:</td>
                            <td class="text-end font-weight-bold text-dark">৳{{ number_format($vendorStats['total_earnings'], 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Current Balance:</td>
                            <td class="text-end font-weight-bold text-success">৳{{ number_format($vendorBalance, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Withdrawn:</td>
                            <td class="text-end font-weight-bold text-dark">৳{{ number_format($vendorStats['total_withdrawn'], 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Pending Payouts:</td>
                            <td class="text-end font-weight-bold text-warning">৳{{ number_format($vendorStats['pending_withdrawals'], 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

