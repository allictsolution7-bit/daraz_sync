@extends('vendor.layouts.app')

@section('title', 'My Wallet & Financial Transactions')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Banner -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, #312e81 0%, #4338ca 60%, #6366f1 100%);">
        <div class="card-body p-4 text-white">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="bg-white bg-opacity-20 p-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px;">
                            <i class="fas fa-wallet fs-2 text-warning"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">Vendor Wallet Hub</h3>
                            <p class="text-white-50 mb-0">Recharge funds, transfer money to peers, and manage payment requests.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <button class="btn btn-warning fw-bold px-4 py-2 rounded-pill me-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#rechargeWalletModal">
                        <i class="fas fa-plus-circle me-1"></i> Recharge Wallet
                    </button>
                    <button class="btn btn-light text-dark fw-bold px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#sendMoneyModal">
                        <i class="fas fa-paper-plane me-1 text-primary"></i> Send Money
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-2.5 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between p-2">
                    <div>
                        <span class="text-muted fw-bold fs-8 text-uppercase">Current Balance</span>
                        <h4 class="fw-extrabold text-primary mb-0 mt-1 fs-5">৳ {{ number_format($user->wallet_balance ?? 0, 2) }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary p-2.5 rounded-3">
                        <i class="fas fa-coins fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-2.5 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between p-2">
                    <div>
                        <span class="text-muted fw-bold fs-8 text-uppercase">Pending Recharge Requests</span>
                        <h4 class="fw-extrabold text-warning mb-0 mt-1 fs-5">
                            ৳ {{ number_format($transactions->where('status', 'pending')->where('type', 'recharge_request')->sum('amount'), 2) }}
                        </h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning p-2.5 rounded-3">
                        <i class="fas fa-clock fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-2.5 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between p-2">
                    <div>
                        <span class="text-muted fw-bold fs-8 text-uppercase">Total Approved Recharges</span>
                        <h4 class="fw-extrabold text-success mb-0 mt-1 fs-5">
                            ৳ {{ number_format($transactions->where('status', 'approved')->whereIn('type', ['recharge_request', 'admin_grant'])->sum('amount'), 2) }}
                        </h4>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success p-2.5 rounded-3">
                        <i class="fas fa-shield-check fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Transaction History Table -->
    <div class="card border-0 shadow-sm rounded-3 bg-white">
        <div class="card-header bg-transparent border-0 p-3 d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-list-check text-primary me-2"></i> Transaction Logs</h6>
            <span class="badge bg-light text-dark border px-2 py-1 rounded-pill" style="font-size: 0.7rem;">{{ $transactions->total() }} entries</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.825rem;">
                    <thead class="bg-light text-muted" style="font-size: 0.7rem; text-transform: uppercase;">
                        <tr>
                            <th class="ps-3 py-2">Date & Time</th>
                            <th class="py-2">Type</th>
                            <th class="py-2">Amount</th>
                            <th class="py-2">Method / Ref</th>
                            <th class="py-2">Status</th>
                            <th class="pe-3 py-2 text-end">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                            <tr>
                                <td class="ps-3 py-2">
                                    <div class="fw-bold text-dark">{{ $trx->created_at->format('d M, Y') }}</div>
                                    <span class="text-muted" style="font-size: 0.7rem;">{{ $trx->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="py-2">
                                    @if($trx->type === 'recharge_request')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info px-2 py-1 rounded-pill" style="font-size: 0.7rem;">Recharge</span>
                                    @elseif($trx->type === 'admin_grant')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded-pill" style="font-size: 0.7rem;">Grant</span>
                                    @elseif($trx->type === 'transfer_sent')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 rounded-pill" style="font-size: 0.7rem;">Sent</span>
                                    @elseif($trx->type === 'transfer_received')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-1 rounded-pill" style="font-size: 0.7rem;">Received</span>
                                    @elseif(in_array(strtolower($trx->type), ['stock_purchase_refund', 'refund', 'admin_refund']))
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded-pill" style="font-size: 0.7rem;">Refund</span>
                                    @elseif(in_array(strtolower($trx->type), ['stock_purchase']))
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1 rounded-pill" style="font-size: 0.7rem;">Stock Purchase</span>
                                    @else
                                        <span class="badge bg-secondary px-2 py-1 rounded-pill" style="font-size: 0.7rem;">{{ strtoupper($trx->type) }}</span>
                                    @endif
                                </td>
                                <td class="py-2">
                                    @php
                                        $isCredit = in_array(strtolower($trx->type), ['recharge_request', 'admin_grant', 'transfer_received', 'stock_purchase_refund', 'refund', 'admin_refund']);
                                    @endphp
                                    <span class="fw-bold {{ $isCredit ? 'text-success' : 'text-danger' }}" style="font-size: 0.85rem;">
                                        {{ $isCredit ? '+' : '-' }} ৳{{ number_format($trx->amount, 2) }}
                                    </span>
                                </td>
                                <td class="py-2">
                                    <div class="fw-semibold text-dark">{{ $trx->payment_method ?? '-' }}</div>
                                    @if($trx->transaction_id)
                                        <span class="text-muted font-monospace" style="font-size: 0.7rem;">TRX: {{ $trx->transaction_id }}</span>
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
                                    <small class="text-muted" style="font-size: 0.725rem;">{{ $trx->admin_note ?? '-' }}</small>
                                    @if($trx->proof_file)
                                        <div class="mt-1">
                                            <a href="{{ asset($trx->proof_file) }}" target="_blank" class="btn btn-xs btn-outline-primary rounded-pill py-0 px-2" style="font-size: 0.7rem;">
                                                <i class="fas fa-paperclip me-1"></i> Proof
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-receipt fs-1 text-muted opacity-50 mb-2"></i>
                                    <p class="mb-0">No wallet transactions found.</p>
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

<!-- Recharge Wallet Modal -->
<div class="modal fade" id="rechargeWalletModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-dark fs-6"><i class="fas fa-plus-circle text-warning me-2"></i> Recharge Wallet Request</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('vendor.wallet.recharge') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Amount (৳)</label>
                        <input type="number" step="0.01" name="amount" class="form-control rounded-3 fs-7" placeholder="e.g. 1000" required min="10">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Payment Method Paid To Admin</label>
                        <select name="payment_method" id="gatewaySelect" class="form-select rounded-3 fs-7" required>
                            <option value="">-- Select Payment Method --</option>
                            <option value="bKash" data-number="{{ setting('ecommerce', 'bkash_number', setting('general', 'phone_number', '01700000000')) }}" data-type="Personal / Send Money" data-instructions="Send Money to the bKash Personal number below and enter the TRX ID.">bKash</option>
                            <option value="Nagad" data-number="{{ setting('ecommerce', 'nagad_number', setting('general', 'phone_number', '01700000000')) }}" data-type="Personal / Send Money" data-instructions="Send Money to the Nagad Personal number below and enter the TRX ID.">Nagad</option>
                            <option value="Rocket" data-number="{{ setting('ecommerce', 'rocket_number', setting('general', 'phone_number', '01700000000')) }}" data-type="Personal / Send Money" data-instructions="Send Money to the Rocket Personal number below and enter the TRX ID.">Rocket</option>
                            <option value="Bank Transfer" data-number="{{ setting('ecommerce', 'bank_account_no', '1234567890') }}" data-type="{{ setting('ecommerce', 'bank_name', 'DBBL / City Bank') }} (Branch: {{ setting('ecommerce', 'bank_branch', 'Dhaka') }})" data-instructions="Transfer funds to Admin Bank Account and upload the deposit receipt.">Bank Transfer</option>
                            <option value="Cash Payment" data-number="{{ setting('general', 'phone_number', 'Direct Admin Hand Cash') }}" data-type="Hand Cash to Admin" data-instructions="Pay cash directly to your Admin / Creator and submit reference note.">Cash Payment</option>
                        </select>
                    </div>

                    <!-- Admin Gateway Information Card (Dynamic) -->
                    <div id="gatewayInfoBox" class="card border-0 bg-light rounded-3 p-3 mb-3 d-none">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary text-white px-2 py-1" id="gatewayNameBadge">Gateway</span>
                            <small class="text-muted fw-bold" id="gatewayTypeSpan">Personal</small>
                        </div>
                        <div class="d-flex align-items-center justify-content-between bg-white border p-2 rounded-3 mb-2">
                            <div>
                                <small class="text-muted d-block" style="font-size: 0.7rem;">ADMIN PAYMENT NUMBER / ACCOUNT</small>
                                <span class="fw-extrabold text-dark fs-6 font-monospace" id="gatewayNumberText">01700000000</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1" style="font-size: 0.75rem;" onclick="navigator.clipboard.writeText(document.getElementById('gatewayNumberText').innerText); alert('Copied to clipboard!');">
                                <i class="fas fa-copy me-1"></i> Copy
                            </button>
                        </div>
                        <small class="text-muted d-block" id="gatewayInstructionsText" style="font-size: 0.8rem;"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Transaction ID / Reference Number</label>
                        <input type="text" name="transaction_id" class="form-control rounded-3 fs-7" placeholder="e.g. TRX982347923" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Upload Payment Proof (Optional)</label>
                        <input type="file" name="proof_file" class="form-control rounded-3 fs-7" accept="image/*,.pdf">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Notes / Message to Admin (Optional)</label>
                        <textarea name="admin_note" class="form-control rounded-3 fs-7" rows="2" placeholder="Any additional payment info..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold rounded-pill px-4 btn-sm">Submit Payment Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send Money Modal -->
<div class="modal fade" id="sendMoneyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-dark fs-6"><i class="fas fa-paper-plane text-primary me-2"></i> Send Money / Peer Transfer</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('vendor.wallet.transfer') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Recipient Email Address</label>
                        <input type="email" name="recipient_email" class="form-control rounded-3 fs-7" placeholder="recipient@example.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Amount (৳)</label>
                        <input type="number" step="0.01" name="amount" class="form-control rounded-3 fs-7" placeholder="e.g. 500" required min="1" max="{{ $user->wallet_balance ?? 0 }}">
                        <small class="text-muted" style="font-size: 0.75rem;">Available balance: ৳ {{ number_format($user->wallet_balance ?? 0, 2) }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark small">Transfer Note / Remark</label>
                        <input type="text" name="note" class="form-control rounded-3 fs-7" placeholder="Optional remark...">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4 btn-sm">Transfer Funds</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const gatewaySelect = document.getElementById('gatewaySelect');
    const gatewayInfoBox = document.getElementById('gatewayInfoBox');
    const gatewayNameBadge = document.getElementById('gatewayNameBadge');
    const gatewayTypeSpan = document.getElementById('gatewayTypeSpan');
    const gatewayNumberText = document.getElementById('gatewayNumberText');
    const gatewayInstructionsText = document.getElementById('gatewayInstructionsText');

    if (gatewaySelect) {
        gatewaySelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const gatewayName = selectedOption.value;

            if (gatewayName) {
                const number = selectedOption.getAttribute('data-number') || 'N/A';
                const type = selectedOption.getAttribute('data-type') || 'Personal';
                const instructions = selectedOption.getAttribute('data-instructions') || '';

                gatewayNameBadge.innerText = gatewayName;
                gatewayTypeSpan.innerText = type;
                gatewayNumberText.innerText = number;
                gatewayInstructionsText.innerText = instructions;

                gatewayInfoBox.classList.remove('d-none');
            } else {
                gatewayInfoBox.classList.add('d-none');
            }
        });
    }
});
</script>
@endpush
@endsection
