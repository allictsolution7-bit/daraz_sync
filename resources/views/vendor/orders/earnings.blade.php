@extends('vendor.layouts.app')

@section('title', 'Earnings & Financial Hub')

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
        --v-border: #e2e8f0;
    }

    /* Glassmorphic Container */
    .earnings-header-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        padding: 1.25rem 1.75rem;
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }

    /* Metric Cards */
    .metric-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 20px;
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.03);
    }

    .metric-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.07);
    }

    .metric-icon-box {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .metric-icon-box.sales {
        background: rgba(99, 102, 241, 0.12);
        color: #6366f1;
    }

    .metric-icon-box.earnings {
        background: rgba(16, 185, 129, 0.12);
        color: #10b981;
    }

    .metric-icon-box.commission {
        background: rgba(6, 182, 212, 0.12);
        color: #06b6d4;
    }

    .metric-icon-box.paid {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
    }

    .metric-icon-box.pending {
        background: rgba(245, 158, 11, 0.12);
        color: #d97706;
    }

    /* Section Cards */
    .v-section-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 24px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.03);
    }

    .v-section-card .card-header {
        background: rgba(248, 250, 252, 0.7);
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
        border-top-left-radius: 24px;
        border-top-right-radius: 24px;
    }

    /* Custom Form Inputs */
    .form-control, .form-select {
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        padding: 0.65rem 1rem;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }

    /* Custom Table Styling */
    .table-custom {
        margin-bottom: 0;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem 1.25rem;
    }

    .table-custom tbody td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .table-custom tbody tr:hover {
        background-color: rgba(248, 250, 252, 0.8);
    }

    .badge-paid {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.2);
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .badge-pending {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.2);
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header Card -->
    <div class="d-flex justify-content-between align-items-center mb-4 earnings-header-card">
        <div class="d-flex align-items-center gap-3">
            <div class="metric-icon-box sales">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div>
                <h4 class="fw-800 mb-0 text-dark">Earnings & Analytics</h4>
                <p class="text-muted small mb-0">Overview of sales performance, net earnings, and withdrawal status.</p>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('vendor.orders.index') }}" class="btn btn-outline-secondary rounded-3 px-3 fw-bold btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Orders
            </a>
            <a href="{{ route('vendor.withdrawals.create') }}" class="btn btn-primary rounded-3 px-3 fw-bold btn-sm shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;">
                <i class="fas fa-hand-holding-usd me-1"></i> Withdraw Funds
            </a>
        </div>
    </div>

    <!-- Main Statistics Overview -->
    <div class="row g-4 mb-4">
        <!-- Total Gross Sales -->
        <div class="col-xl-4 col-md-6">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted fw-bold text-uppercase fs-7 tracking-wider d-block mb-1">Total Sales</span>
                        <h2 class="fw-800 text-dark mb-1">৳{{ number_format($stats['total_sales'], 2) }}</h2>
                        <span class="badge bg-light text-secondary rounded-pill px-2.5 py-1 font-semibold fs-8">Gross revenue generated</span>
                    </div>
                    <div class="metric-icon-box sales">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Net Vendor Earnings -->
        <div class="col-xl-4 col-md-6">
            <div class="metric-card h-100" style="border-left: 4px solid #10b981;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted fw-bold text-uppercase fs-7 tracking-wider d-block mb-1">Net Earnings</span>
                        <h2 class="fw-800 text-success mb-1">৳{{ number_format($stats['total_earnings'], 2) }}</h2>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2.5 py-1 font-semibold fs-8">Your revenue after commission</span>
                    </div>
                    <div class="metric-icon-box earnings">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Platform Commission -->
        <div class="col-xl-4 col-md-12">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-muted fw-bold text-uppercase fs-7 tracking-wider d-block mb-1">Platform Commission</span>
                        <h2 class="fw-800 text-dark mb-1">৳{{ number_format($stats['total_commission'], 2) }}</h2>
                        <span class="badge bg-info-subtle text-info rounded-pill px-2.5 py-1 font-semibold fs-8">Platform service fee deduction</span>
                    </div>
                    <div class="metric-icon-box commission">
                        <i class="fas fa-percent"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Status Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="metric-card" style="background: linear-gradient(135deg, rgba(240, 253, 244, 0.9) 0%, rgba(220, 252, 231, 0.5) 100%); border: 1.5px solid rgba(16, 185, 129, 0.3);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fas fa-circle-check text-success"></i>
                            <span class="fw-bold text-success text-uppercase fs-7">Withdrawn / Paid Out</span>
                        </div>
                        <h2 class="fw-800 text-success mb-0">৳{{ number_format($stats['paid'], 2) }}</h2>
                        <small class="text-muted">Successfully transferred to your bank/account</small>
                    </div>
                    <div class="metric-icon-box paid">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="metric-card" style="background: linear-gradient(135deg, rgba(254, 243, 199, 0.6) 0%, rgba(254, 249, 195, 0.4) 100%); border: 1.5px solid rgba(245, 158, 11, 0.3);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fas fa-clock text-warning"></i>
                            <span class="fw-bold text-warning text-uppercase fs-7">Available Balance</span>
                        </div>
                        <h2 class="fw-800 text-warning mb-0">৳{{ number_format($stats['unpaid'], 2) }}</h2>
                        <small class="text-muted">Pending balance ready for withdrawal request</small>
                    </div>
                    <div>
                        <a href="{{ route('vendor.withdrawals.create') }}" class="btn btn-warning fw-bold text-dark rounded-3 px-3 py-2 shadow-sm fs-8">
                            <i class="fas fa-paper-plane me-1"></i> Request Payout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form Section -->
    <div class="v-section-card mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('vendor.orders.earnings') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark fs-8 uppercase mb-2">From Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark fs-8 uppercase mb-2">To Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 rounded-3 fw-bold" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;">
                            <i class="fas fa-filter me-1"></i> Apply Filter
                        </button>
                        <a href="{{ route('vendor.orders.earnings') }}" class="btn btn-outline-secondary rounded-3 fw-bold">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Detailed Ledger Table -->
    <div class="v-section-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="fas fa-receipt text-primary"></i> Earnings Ledger & Breakdown
            </h5>
            <span class="badge bg-secondary rounded-pill px-3 py-1 font-semibold fs-8">{{ $items->total() }} total transactions</span>
        </div>
        <div class="card-body p-0">
            @if($items->isEmpty())
                <div class="text-center py-5">
                    <div class="stat-icon primary mx-auto mb-3" style="width: 70px; height: 70px; font-size: 2rem;">
                        <i class="fas fa-coins text-muted"></i>
                    </div>
                    <h5 class="fw-bold text-dark">No Earnings Found</h5>
                    <p class="text-muted small max-w-sm mx-auto">There are no completed earnings recorded for the selected period.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-custom">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order Ref</th>
                                <th>Item Title</th>
                                <th class="text-center">Qty</th>
                                <th>Subtotal</th>
                                <th>Commission Rate / Fee</th>
                                <th>Your Earning</th>
                                <th class="text-center">Payout Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold text-dark">{{ $item->created_at->format('d M, Y') }}</span>
                                    <small class="d-block text-muted">{{ $item->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('vendor.orders.show', $item->order_id) }}" class="fw-bold text-primary text-decoration-none">
                                        #{{ $item->order->order_number ?? $item->order_id }}
                                    </a>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark d-block text-truncate" style="max-width: 250px;" title="{{ $item->product->title ?? 'N/A' }}">
                                        {{ $item->product->title ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                <td class="fw-bold">৳{{ number_format($item->sub_total, 2) }}</td>
                                <td>
                                    <span class="text-danger fw-bold">-৳{{ number_format($item->vendor_commission_amount, 2) }}</span>
                                    <small class="text-muted ms-1">({{ number_format($item->vendor_commission_rate, 1) }}%)</small>
                                </td>
                                <td>
                                    <span class="fw-800 text-success fs-6">৳{{ number_format($item->vendor_earning, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    @if($item->vendor_paid)
                                        <span class="badge-paid">
                                            <i class="fas fa-check-circle me-1"></i> Paid Out
                                        </span>
                                    @else
                                        <span class="badge-pending">
                                            <i class="fas fa-hourglass-half me-1"></i> Pending Payout
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td colspan="4" class="text-end text-dark">Page Summary Totals:</td>
                                <td class="text-dark">৳{{ number_format($items->sum('sub_total'), 2) }}</td>
                                <td class="text-danger">-৳{{ number_format($items->sum('vendor_commission_amount'), 2) }}</td>
                                <td class="text-success fs-6 fw-800">৳{{ number_format($items->sum('vendor_earning'), 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($items->hasPages())
                <div class="p-4 border-top">
                    {{ $items->links() }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
