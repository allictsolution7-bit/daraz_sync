@extends('layouts.master')

@section('title', 'Partner Payouts & Withdrawals')

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
    .stat-card-glass {
        background: white;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .stat-card-glass .icon-box {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
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
    .vp-page-wrapper .form-control:focus, .vp-page-wrapper .form-select:focus {
        background-color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }
    .vp-table th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 800;
        color: #64748b;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 16px;
    }
    .vp-table td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
    }
    .badge-soft-success {
        background: #dcfce7;
        color: #15803d;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
    .badge-soft-warning {
        background: #fef3c7;
        color: #b45309;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
    .badge-soft-info {
        background: #e0f2fe;
        color: #0369a1;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
    .badge-soft-danger {
        background: #fee2e2;
        color: #b91c1c;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
    .btn-action-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-action-icon:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="vp-page-wrapper">
    <!-- Header Banner -->
    <div class="vp-hero-card d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="vp-title-icon">
                <i class="fas fa-hand-holding-dollar"></i>
            </div>
            <div>
                <h3 class="mb-0 text-white font-weight-bold">Partner Payouts & Withdrawals</h3>
                <p class="mb-0 text-white-50 small">Manage partner payout requests, verify bank accounts & process payments</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary rounded-3 px-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Partners
            </a>
        </div>
    </div>

    <!-- Metric Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card-glass d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small uppercase font-weight-bold">Pending Requests</span>
                    <h3 class="mb-0 font-weight-bold text-dark">{{ $stats['pending_count'] }}</h3>
                    <small class="text-warning font-weight-bold">৳{{ number_format($stats['pending_amount'], 2) }}</small>
                </div>
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-glass d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small uppercase font-weight-bold">Processing</span>
                    <h3 class="mb-0 font-weight-bold text-dark">{{ $stats['processing_count'] }}</h3>
                    <small class="text-info font-weight-bold">৳{{ number_format($stats['processing_amount'], 2) }}</small>
                </div>
                <div class="icon-box bg-info bg-opacity-10 text-info">
                    <i class="fas fa-sync-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-glass d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small uppercase font-weight-bold">Completed Payouts</span>
                    <h3 class="mb-0 font-weight-bold text-dark">{{ $stats['completed_count'] }}</h3>
                    <small class="text-success font-weight-bold">৳{{ number_format($stats['completed_amount'], 2) }}</small>
                </div>
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card-glass d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small uppercase font-weight-bold">Rejected</span>
                    <h3 class="mb-0 font-weight-bold text-dark">{{ $stats['rejected_count'] }}</h3>
                    <small class="text-danger font-weight-bold">৳{{ number_format($stats['rejected_amount'], 2) }}</small>
                </div>
                <div class="icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="vp-card">
        <div class="card-body p-4">
            <form action="{{ route('admin.vendor-withdrawals.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="text-xs font-weight-bold uppercase text-muted mb-1 d-block">Payout Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="text-xs font-weight-bold uppercase text-muted mb-1 d-block">Partner Search</label>
                    <input type="text" name="vendor" class="form-control" value="{{ request('vendor') }}" placeholder="Partner name or business...">
                </div>
                <div class="col-md-2">
                    <label class="text-xs font-weight-bold uppercase text-muted mb-1 d-block">From Date</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <label class="text-xs font-weight-bold uppercase text-muted mb-1 d-block">To Date</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 font-weight-bold">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.vendor-withdrawals.index') }}" class="btn btn-secondary rounded-3 py-2">
                        <i class="fas fa-rotate-right"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdrawals Table Card -->
    <div class="vp-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-money-bill-transfer text-primary me-2"></i> Partner Payout Requests</h5>
            <span class="badge bg-light text-dark font-weight-bold px-3 py-2 border">Total: {{ $withdrawals->total() }}</span>
        </div>
        <div class="card-body p-0">
            @if($withdrawals->count() > 0)
                <div class="table-responsive">
                    <table class="table vp-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Request Ref</th>
                                <th>Partner</th>
                                <th>Requested Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Request Date</th>
                                <th>Processed Date</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $withdrawal)
                                <tr>
                                    <td class="ps-4 font-weight-bold text-dark">#{{ $withdrawal->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.vendors.show', $withdrawal->vendor) }}" class="font-weight-bold text-dark text-decoration-none d-block">
                                            {{ $withdrawal->vendor->name }}
                                        </a>
                                        <small class="text-muted">{{ $withdrawal->vendor->vendorSettings->business_name ?? '' }}</small>
                                    </td>
                                    <td class="font-weight-bold text-success fs-6">৳{{ number_format($withdrawal->amount, 2) }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-weight-bold px-3 py-1 text-uppercase">
                                            {{ $withdrawal->method }}
                                        </span>
                                    </td>
                                    <td>
                                        @switch($withdrawal->status)
                                            @case('pending')
                                                <span class="badge-soft-warning"><i class="fas fa-clock me-1"></i> Pending</span>
                                                @break
                                            @case('approved')
                                                <span class="badge-soft-info"><i class="fas fa-thumbs-up me-1"></i> Approved</span>
                                                @break
                                            @case('processing')
                                                <span class="badge-soft-info"><i class="fas fa-cog me-1 fa-spin"></i> Processing</span>
                                                @break
                                            @case('completed')
                                                <span class="badge-soft-success"><i class="fas fa-check-circle me-1"></i> Completed</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge-soft-danger"><i class="fas fa-times-circle me-1"></i> Rejected</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <span class="small font-weight-bold text-dark d-block">{{ $withdrawal->created_at->format('d M Y') }}</span>
                                        <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($withdrawal->processed_at)
                                            <span class="small font-weight-bold text-dark d-block">{{ $withdrawal->processed_at->format('d M Y') }}</span>
                                            <small class="text-muted">{{ $withdrawal->processed_at->format('h:i A') }}</small>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.vendor-withdrawals.show', $withdrawal) }}" 
                                               class="btn btn-sm btn-info"
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($withdrawal->status == 'pending')
                                                <form action="{{ route('admin.vendor-withdrawals.approve', $withdrawal) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success"
                                                            title="Approve"
                                                            onclick="return confirm('Approve this withdrawal request?')">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                </form>
                                                
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger"
                                                        title="Reject"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal{{ $withdrawal->id }}">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            @endif

                                            @if($withdrawal->status == 'approved')
                                                <form action="{{ route('admin.vendor-withdrawals.complete', $withdrawal) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-primary"
                                                            title="Mark as Completed"
                                                            onclick="return confirm('Mark this withdrawal as completed?')">
                                                        <i class="fas fa-check-double"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $withdrawal->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.vendor-withdrawals.reject', $withdrawal) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Withdrawal #{{ $withdrawal->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Reason for Rejection *</label>
                                                        <textarea name="reason" 
                                                                  class="form-control" 
                                                                  rows="4" 
                                                                  required
                                                                  placeholder="Explain why this withdrawal is being rejected"></textarea>
                                                    </div>
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        This will notify the vendor and return the amount to their balance.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-times-circle"></i> Reject Withdrawal
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
                    <i class="fas fa-inbox fs-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">No Withdrawal Requests</h5>
                    <p class="text-muted">
                        @if(request()->has('status') || request()->has('vendor'))
                            No withdrawals match your filters.
                        @else
                            Vendors haven't requested any withdrawals yet.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

