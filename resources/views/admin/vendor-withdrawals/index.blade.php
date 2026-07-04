@extends('layouts.master')

@section('title', 'Vendor Withdrawals')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h4><i class="fas fa-cash-stack"></i> Vendor Withdrawal Requests</h4>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['pending_count'] }}</h4>
                            <p class="mb-0">Pending Requests</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fs-1"></i>
                        </div>
                    </div>
                    <small class="text-muted">৳{{ number_format($stats['pending_amount'], 2) }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['processing_count'] }}</h4>
                            <p class="mb-0">Processing</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cog fs-1"></i>
                        </div>
                    </div>
                    <small>৳{{ number_format($stats['processing_amount'], 2) }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['completed_count'] }}</h4>
                            <p class="mb-0">Completed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fs-1"></i>
                        </div>
                    </div>
                    <small>৳{{ number_format($stats['completed_amount'], 2) }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $stats['rejected_count'] }}</h4>
                            <p class="mb-0">Rejected</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fs-1"></i>
                        </div>
                    </div>
                    <small>৳{{ number_format($stats['rejected_amount'], 2) }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.vendor-withdrawals.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
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
                    <label class="form-label">Vendor</label>
                    <input type="text" name="vendor" class="form-control" value="{{ request('vendor') }}" placeholder="Search vendor name">
                </div>
                <div class="col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.vendor-withdrawals.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Withdrawal Requests ({{ $withdrawals->total() }})</h5>
        </div>
        <div class="card-body">
            @if($withdrawals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vendor</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Request Date</th>
                                <th>Processed Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $withdrawal)
                                <tr>
                                    <td>#{{ $withdrawal->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.vendors.show', $withdrawal->vendor) }}">
                                            {{ $withdrawal->vendor->name }}
                                        </a>
                                        <br>
                                        <small class="text-muted">{{ $withdrawal->vendor->vendorSettings->business_name ?? '' }}</small>
                                    </td>
                                    <td>
                                        <strong class="text-primary">৳{{ number_format($withdrawal->amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary text-capitalize">{{ $withdrawal->method }}</span>
                                        <br>
                                        <small class="text-muted">{{ $withdrawal->account_details['account_number'] ?? '' }}</small>
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
                                                    <i class="fas fa-cog fa-spin"></i> Processing
                                                </span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-double"></i> Completed
                                                </span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times-circle"></i> Rejected
                                                </span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        {{ $withdrawal->created_at->format('d M Y') }}
                                        <br>
                                        <small class="text-muted">{{ $withdrawal->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        @if($withdrawal->processed_at)
                                            {{ $withdrawal->processed_at->format('d M Y') }}
                                            <br>
                                            <small class="text-muted">{{ $withdrawal->processed_at->format('h:i A') }}</small>
                                        @else
                                            <span class="text-muted">-</span>
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

