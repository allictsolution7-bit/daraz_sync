@extends('layouts.master')

@section('title', 'Conversions Pipeline')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* Compact Pipeline Card Overhaul */
    #pending-events-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
    }

    .premium-panel-header {
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 20px;
        margin-bottom: 24px;
    }

    .premium-panel-header h1 {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .premium-panel-header h1 i {
        background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Tab Buttons Styling */
    .btn-premium {
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        text-decoration: none;
    }

    .btn-premium:hover {
        transform: translateY(-1px);
        text-decoration: none;
    }

    .btn-premium-outline {
        background: #ffffff;
        border: 2px solid #cbd5e1;
        color: #475569;
    }

    .btn-premium-outline:hover {
        border-color: #6366f1;
        color: #6366f1;
        background: #f8fafc;
    }

    /* Pipeline Cards Layout */
    .pipeline-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 24px;
    }

    .pipeline-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .pipeline-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, #f59e0b, #d97706);
    }

    .pipeline-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.05);
        border-color: #cbd5e1;
    }

    /* Client info block */
    .client-details-block {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 240px;
    }

    .client-avatar-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .client-info-text {
        display: flex;
        flex-direction: column;
    }

    .client-info-text a {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .client-info-text a:hover {
        color: #6366f1;
    }

    .client-name-meta {
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        margin-top: 1px;
    }

    .client-phone-meta {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 1px;
    }

    /* Middleware section */
    .event-meta-block {
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 150px;
    }

    .event-class-badge {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-family: monospace;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 4px;
        align-self: flex-start;
    }

    .event-id-meta {
        font-size: 0.725rem;
        color: #64748b;
        font-family: monospace;
    }

    .event-age-meta {
        font-size: 0.8rem;
        font-weight: 600;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Gateway badge */
    .gateway-pill {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        color: #475569;
        font-weight: 700;
        font-size: 0.7rem;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Value Display Bubble */
    .amount-value-bubble {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 6px 14px;
        border-radius: 8px;
        text-align: center;
        min-width: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .amount-val-title {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #059669;
        margin-bottom: 1px;
    }

    .amount-val-num {
        font-size: 1.15rem;
        font-weight: 800;
    }

    /* Custom Action Trigger */
    .btn-inspect-transaction {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        color: #334155;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 8px 16px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-inspect-transaction:hover {
        border-color: #6366f1;
        color: #6366f1;
        background: #f5f3ff;
        text-decoration: none;
    }

    /* Pipeline Status block */
    .pipeline-status-badge {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #d97706;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
    }
</style>
@endsection

@section('content')
<div id="pending-events-page" class="container-fluid px-4 py-4">
    <!-- Premium Header -->
    <div class="premium-panel-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1>
                    <i class="fas fa-clock"></i>
                    Buffered Conversion Pipeline
                </h1>
                <p class="text-muted mb-0">Transactions held in buffer queue waiting to dispatch to active analytics trackers.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.delayed-events.settings') }}" class="btn-premium btn-premium-outline">
                    <i class="fas fa-cog"></i> Config Settings
                </a>
                <a href="{{ route('admin.delayed-events.history') }}" class="btn-premium btn-premium-outline">
                    <i class="fas fa-history"></i> Dispatch Logs
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:12px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:12px;">
            <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Overhauled Cards Pipeline Container -->
    @if($events->count() > 0)
        <div class="pipeline-container">
            @foreach($events as $event)
                <div class="pipeline-card">
                    <!-- Client Details Block -->
                    <div class="client-details-block">
                        <div class="client-avatar-icon">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="client-info-text">
                            <a href="{{ route('admin.orders.edit', $event->order_id) }}">
                                Order #{{ $event->order_id }}
                            </a>
                            <span class="client-name-meta">{{ $event->order?->name ?? 'Unknown Customer' }}</span>
                            @if($event->order?->phone)
                                <span class="client-phone-meta"><i class="fas fa-phone-alt me-1"></i> {{ $event->order->phone }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Pipeline Age and Status -->
                    <div class="event-meta-block">
                        <div class="event-age-meta">
                            <i class="far fa-calendar-alt text-primary"></i>
                            <span>{{ $event->created_at?->diffForHumans() }}</span>
                        </div>
                        <span class="event-id-meta">Logged: {{ $event->created_at?->format('Y-m-d H:i') }}</span>
                        <div>
                            <span class="pipeline-status-badge">
                                <i class="fas fa-pause-circle"></i> Buffered
                            </span>
                        </div>
                    </div>

                    <!-- Event Metadata -->
                    <div class="event-meta-block">
                        <span class="event-class-badge">
                            {{ data_get($event->event_data, 'event', 'purchase') }}
                        </span>
                        <span class="event-id-meta">ID: {{ data_get($event->event_data, 'event_id') }}</span>
                    </div>

                    <!-- Gateway Badge -->
                    <div>
                        <span class="gateway-pill">
                            <i class="fas fa-credit-card"></i>
                            {{ $event->order?->payment_method ? strtoupper($event->order->payment_method) : 'N/A' }}
                        </span>
                    </div>

                    <!-- Value Display Bubble -->
                    <div class="amount-value-bubble">
                        <span class="amount-val-title">Conversion Value</span>
                        <span class="amount-val-num">
                            {{ number_format((float) data_get($event->event_data, 'value', 0), 2) }}
                            <span style="font-size: 0.7rem; font-weight: 700;">{{ data_get($event->event_data, 'currency', 'BDT') }}</span>
                        </span>
                    </div>

                    <!-- Operations -->
                    <div>
                        <a href="{{ route('admin.orders.edit', $event->order_id) }}" class="btn-inspect-transaction">
                            <span>Inspect Transaction</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($events->hasPages())
            <div class="mt-4">
                {{ $events->links() }}
            </div>
        @endif
    @else
        <div class="premium-card text-center text-muted py-5">
            <i class="fas fa-clock fa-4x mb-3 text-gray-300" style="color: #cbd5e1;"></i>
            <h4 class="font-weight-bold text-dark mb-1" style="font-size: 1.25rem;">Pipeline Queue is Empty</h4>
            <p class="mb-0 text-muted">No conversions are currently buffered in the dispatch pipeline queue.</p>
        </div>
    @endif
</div>
@endsection
