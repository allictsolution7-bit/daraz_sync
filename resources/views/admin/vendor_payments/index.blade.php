@extends('layouts.master')

@section('title', 'Vendor Payments')

@section('content')
<div class="container-fluid px-3 py-3">
    <!-- Header Block -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-wallet text-primary me-1"></i> Vendor Payments</h5>
            <small class="text-muted">Manage vendor wallet recharge requests & grants.</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.vendor-payments.export-csv') }}" class="btn btn-outline-secondary btn-sm fw-bold px-3 rounded-pill shadow-sm" title="Export CSV">
                <i class="fas fa-file-csv text-success me-1"></i> CSV Export
            </a>
            <button class="btn btn-success btn-sm fw-bold px-3 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#grantFundModal">
                <i class="fas fa-plus-circle me-1"></i> Direct Fund
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 py-2 px-3 mb-3 small">
            <i class="fas fa-circle-check me-1"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 py-2 px-3 mb-3 small">
            <i class="fas fa-circle-exclamation me-1"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Stat Cards -->
    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-2 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Pending</span>
                        <h5 class="fw-extrabold text-warning mb-0">{{ $pendingCount }}</h5>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning px-2 py-1 rounded-3">
                        <i class="fas fa-clock fs-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 p-2 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase;">Approved</span>
                        <h5 class="fw-extrabold text-success mb-0">৳{{ number_format($totalRecharged, 2) }}</h5>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success px-2 py-1 rounded-3">
                        <i class="fas fa-circle-check fs-6"></i>
                    </div>
                </div>
            </div>
        </div>
    <!-- Search & Filter Card -->
    <div class="card border-0 shadow-sm rounded-3 bg-white mb-3">
        <div class="card-body p-3">
            <form action="{{ route('admin.vendor-payments.index') }}" method="GET" class="row g-2 align-items-center">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0 ps-0" placeholder="Search Vendor, Email, TRX ID, Method..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="vendor_id" class="form-select form-select-sm">
                        <option value="">All Vendors</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>
                                {{ $v->name }} ({{ $v->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All Transaction Types</option>
                        <option value="recharge_request" {{ request('type') === 'recharge_request' ? 'selected' : '' }}>Recharge Request</option>
                        <option value="admin_grant" {{ request('type') === 'admin_grant' ? 'selected' : '' }}>Direct Admin Grant</option>
                        <option value="transfer_sent" {{ request('type') === 'transfer_sent' ? 'selected' : '' }}>Transfer Sent</option>
                        <option value="transfer_received" {{ request('type') === 'transfer_received' ? 'selected' : '' }}>Transfer Received</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold rounded-2">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    @if(request('search') || request('vendor_id') || request('type'))
                        <a href="{{ route('admin.vendor-payments.index') }}" class="btn btn-light btn-sm fw-bold border" title="Reset Filters"><i class="fas fa-rotate-left"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Filter & Table -->
    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="card-header bg-transparent border-0 p-3 d-flex align-items-center justify-content-between">
            <div class="btn-group rounded-pill p-1 bg-light border">
                <a href="{{ route('admin.vendor-payments.index', ['status' => 'all']) }}" class="btn btn-xs rounded-pill fw-semibold py-1 px-3 style-badge {{ $statusFilter === 'all' ? 'btn-primary' : 'btn-light text-dark' }}" style="font-size: 0.75rem;">All</a>
                <a href="{{ route('admin.vendor-payments.index', ['status' => 'pending']) }}" class="btn btn-xs rounded-pill fw-semibold py-1 px-3 style-badge {{ $statusFilter === 'pending' ? 'btn-warning' : 'btn-light text-dark' }}" style="font-size: 0.75rem;">Pending ({{ $pendingCount }})</a>
                <a href="{{ route('admin.vendor-payments.index', ['status' => 'approved']) }}" class="btn btn-xs rounded-pill fw-semibold py-1 px-3 style-badge {{ $statusFilter === 'approved' ? 'btn-success' : 'btn-light text-dark' }}" style="font-size: 0.75rem;">Approved</a>
                <a href="{{ route('admin.vendor-payments.index', ['status' => 'rejected']) }}" class="btn btn-xs rounded-pill fw-semibold py-1 px-3 style-badge {{ $statusFilter === 'rejected' ? 'btn-danger' : 'btn-light text-dark' }}" style="font-size: 0.75rem;">Rejected</a>
            </div>

            <div>
                <button type="button" class="btn btn-xs btn-danger rounded-pill px-3 py-1 fw-bold d-none" id="bulkDeleteBtn" onclick="submitBulkDelete()" style="font-size: 0.75rem;">
                    <i class="fas fa-trash me-1"></i> Delete Selected (<span id="selectedCount">0</span>)
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.825rem;">
                    <thead class="bg-light text-muted" style="font-size: 0.7rem; text-transform: uppercase;">
                        <tr>
                            <th class="ps-3 py-2" width="30">
                                <input type="checkbox" class="form-check-input" id="selectAllCheckbox">
                            </th>
                            <th class="py-2">Vendor</th>
                            <th class="py-2">Type</th>
                            <th class="py-2">Amount</th>
                            <th class="py-2">Payment Info</th>
                            <th class="py-2">Proof</th>
                            <th class="py-2">Status</th>
                            <th class="pe-3 py-2 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr id="row-{{ $trx->id }}">
                                <td class="ps-3 py-2">
                                    <input type="checkbox" class="form-check-input row-checkbox" value="{{ $trx->id }}">
                                </td>
                                <td class="py-2">
                                    <div class="fw-bold text-dark">{{ $trx->vendor->name ?? 'Deleted' }}</div>
                                    <span class="text-muted" style="font-size: 0.725rem;">{{ $trx->vendor->email ?? '-' }} (৳{{ number_format($trx->vendor->wallet_balance ?? 0, 2) }})</span>
                                </td>
                                <td class="py-2">
                                    @if($trx->type === 'recharge_request')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info px-2 py-1 rounded-pill" style="font-size: 0.7rem;">Recharge</span>
                                    @elseif($trx->type === 'admin_grant')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded-pill" style="font-size: 0.7rem;">Grant</span>
                                    @else
                                        <span class="badge bg-secondary px-2 py-1 rounded-pill" style="font-size: 0.7rem;">{{ strtoupper($trx->type) }}</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    <span class="fw-bold text-success" style="font-size: 0.85rem;">৳{{ number_format($trx->amount, 2) }}</span>
                                </td>
                                <td class="py-2">
                                    <div class="fw-semibold text-dark">{{ $trx->payment_method ?? '-' }}</div>
                                    @if($trx->transaction_id)
                                        <span class="text-muted font-monospace" style="font-size: 0.7rem;">TRX: {{ $trx->transaction_id }}</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @if($trx->proof_file)
                                        <a href="{{ asset($trx->proof_file) }}" target="_blank" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-0" style="font-size: 0.7rem;">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted" style="font-size: 0.7rem;">-</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @if($trx->status === 'approved')
                                        <span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 0.7rem;"><i class="fas fa-check me-1"></i> Approved</span>
                                    @elseif($trx->status === 'pending')
                                        <span class="badge bg-warning text-dark px-2 py-1 rounded-pill" style="font-size: 0.7rem;"><i class="fas fa-clock me-1"></i> Pending</span>
                                    @else
                                        <span class="badge bg-danger text-white px-2 py-1 rounded-pill" style="font-size: 0.7rem;"><i class="fas fa-times me-1"></i> Rejected</span>
                                    @endif
                                </td>
                                <td class="pe-3 py-2 text-end">
                                    <a href="{{ route('admin.vendor-payments.download-pdf', $trx->id) }}" class="btn btn-xs btn-outline-secondary rounded-pill px-2 py-1 me-1" style="font-size: 0.725rem;" title="Download PDF Receipt">
                                        <i class="fas fa-file-pdf text-danger"></i> PDF
                                    </a>

                                    @if($trx->status === 'pending')
                                        <form action="{{ route('admin.vendor-payments.approve', $trx->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-success rounded-pill px-2 py-1 fw-bold" style="font-size: 0.725rem;">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-xs btn-outline-danger rounded-pill px-2 py-1 fw-bold ms-1" style="font-size: 0.725rem;" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $trx->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $trx->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 rounded-4 shadow">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold text-dark">Reject Payment Request</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.vendor-payments.reject', $trx->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body text-start p-4">
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold text-dark">Reason / Remarks for Rejection</label>
                                                                <textarea name="admin_note" class="form-control rounded-3" rows="3" placeholder="Invalid TRX ID, payment not received..." required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0 pt-0 px-4 pb-4">
                                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger fw-bold rounded-pill px-4">Confirm Rejection</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <small class="text-muted">{{ $trx->admin_note ?? 'Processed' }}</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-receipt fs-1 text-muted opacity-50 mb-2"></i>
                                    <p class="mb-0">No vendor payment requests found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Direct Fund Grant Modal -->
<div class="modal fade" id="grantFundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-dark fs-6"><i class="fas fa-coins text-success me-2"></i> Grant Direct Fund to Vendor</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.vendor-payments.grant-fund') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Select Vendor</label>
                        <select name="vendor_id" class="form-select rounded-3 fs-7" required>
                            <option value="">-- Choose Vendor Account --</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $vendor->email }}) - Balance: ৳{{ number_format($vendor->wallet_balance ?? 0, 2) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Amount to Credit (৳)</label>
                        <input type="number" step="0.01" name="amount" class="form-control rounded-3 fs-7" placeholder="e.g. 2000" required min="1">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Reason / Admin Note</label>
                        <textarea name="admin_note" class="form-control rounded-3 fs-7" rows="2" placeholder="e.g. Promotional bonus, manual cash collection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-bold rounded-pill px-4 btn-sm">Credit Fund Instantly</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCountSpan = document.getElementById('selectedCount');

    function updateBulkState() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const count = checked.length;
        if (selectedCountSpan) selectedCountSpan.innerText = count;

        if (count > 0) {
            bulkDeleteBtn.classList.remove('d-none');
        } else {
            bulkDeleteBtn.classList.add('d-none');
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            updateBulkState();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            if (!this.checked && selectAllCheckbox) selectAllCheckbox.checked = false;
            updateBulkState();
        });
    });
});

function submitBulkDelete() {
    const checked = document.querySelectorAll('.row-checkbox:checked');
    const ids = Array.from(checked).map(cb => cb.value);

    if (ids.length === 0) return;

    if (!confirm(`Are you sure you want to delete ${ids.length} selected transaction log(s)?`)) {
        return;
    }

    fetch("{{ route('admin.vendor-payments.bulk-delete') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            ids.forEach(id => {
                const row = document.getElementById(`row-${id}`);
                if (row) row.remove();
            });
            alert(data.message);
            location.reload();
        } else {
            alert('Failed to delete selected items.');
        }
    })
    .catch(err => alert('Error deleting selected items: ' + err));
}
</script>
@endpush
@endsection
