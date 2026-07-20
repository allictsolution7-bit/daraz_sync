@extends('layouts.master')

@section('title', 'Subscription Details')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* Typography & Animation */
    .subscriber-details-wrapper {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #1e293b;
        padding: 1.5rem 0;
        animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Glassmorphism Card */
    .premium-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 20px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 2rem;
    }

    .premium-card:hover {
        box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.25);
    }

    /* Gradient Header */
    .gradient-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 50%, #4f46e5 100%);
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .gradient-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }

    .gradient-header-title {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.025em;
        margin: 0;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .gradient-header-subtitle {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.85);
        font-weight: 500;
        margin-top: 0.35rem;
        margin-bottom: 0;
    }

    .premium-card-body {
        padding: 2.25rem;
    }

    /* Inner Panes */
    .info-pane {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        height: 100%;
        transition: all 0.2s ease;
    }

    .info-pane:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
    }

    .pane-header {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1e293b;
        border-bottom: 1.5px solid #e2e8f0;
        padding-bottom: 10px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-row .label {
        color: #64748b;
        font-weight: 600;
    }

    .info-row .value {
        color: #0f172a;
        font-weight: 700;
        text-align: right;
        word-break: break-all;
        max-width: 60%;
    }

    /* Status Badges */
    .badge-active {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* User Agent Codebox */
    .useragent-box {
        background: #0f172a;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 0.8rem;
        font-family: 'Fira Code', Consolas, Monaco, monospace;
        color: #38bdf8;
        line-height: 1.6;
        word-break: break-all;
    }

    /* Action Buttons */
    .premium-btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        border-top: 1.5px solid #f1f5f9;
        padding-top: 1.75rem;
        flex-wrap: wrap;
    }

    .btn-premium {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-premium:hover {
        transform: translateY(-1px);
    }

    .btn-premium-primary {
        background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
        color: white !important;
        box-shadow: 0 8px 20px -6px rgba(37, 99, 235, 0.4);
    }

    .btn-premium-primary:hover {
        background: linear-gradient(135deg, #0284c7 0%, #1d4ed8 100%);
        box-shadow: 0 10px 24px -6px rgba(37, 99, 235, 0.5);
    }

    .btn-premium-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706 !important;
        border: 1.5px solid rgba(245, 158, 11, 0.3);
    }

    .btn-premium-warning:hover {
        background: rgba(245, 158, 11, 0.2);
    }

    .btn-premium-success {
        background: rgba(16, 185, 129, 0.1);
        color: #059669 !important;
        border: 1.5px solid rgba(16, 185, 129, 0.3);
    }

    .btn-premium-success:hover {
        background: rgba(16, 185, 129, 0.2);
    }

    .btn-premium-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626 !important;
        border: 1.5px solid rgba(239, 68, 68, 0.3);
    }

    .btn-premium-danger:hover {
        background: rgba(239, 68, 68, 0.2);
    }

    .btn-premium-default {
        background-color: #f1f5f9;
        color: #475569 !important;
        border: 1px solid #e2e8f0;
    }

    .btn-premium-default:hover {
        background-color: #e2e8f0;
        color: #1e293b !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid subscriber-details-wrapper">

    <div class="premium-card">
        <!-- Gradient Header -->
        <div class="gradient-header">
            <h1 class="gradient-header-title">Subscriber Details</h1>
            <p class="gradient-header-subtitle">
                <i class="fas fa-envelope me-1"></i> {{ $subscription->email }} &bull; Subscribed on {{ $subscription->created_at->format('M d, Y') }}
            </p>
        </div>

        <div class="premium-card-body">
            <!-- Action Buttons at Top -->
            <div class="premium-btn-group mt-0 mb-4 pb-4 pt-0 border-0 border-bottom">
                <!-- Status Toggle -->
                <form action="{{ route('admin.subscriptions.toggle-status', $subscription) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    @if($subscription->status)
                        <button type="submit" class="btn-premium btn-premium-warning">
                            <i class="fas fa-toggle-off me-1"></i> Deactivate Subscriber
                        </button>
                    @else
                        <button type="submit" class="btn-premium btn-premium-success">
                            <i class="fas fa-toggle-on me-1"></i> Activate Subscriber
                        </button>
                    @endif
                </form>

                <!-- Delete -->
                <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this subscription?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-premium btn-premium-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete Record
                    </button>
                </form>

                <!-- Back to list -->
                <a href="{{ route('admin.subscriptions.index') }}" class="btn-premium btn-premium-default ms-auto">
                    <i class="fas fa-arrow-left me-1"></i> Back to Directory
                </a>
            </div>

            <!-- Info Grid -->
            <div class="row g-4 mb-4">
                <!-- Subscriber Info Card -->
                <div class="col-md-6">
                    <div class="info-pane">
                        <div class="pane-header text-primary">
                            <i class="fas fa-id-card"></i> Subscriber Profile
                        </div>
                        <div class="info-row">
                            <span class="label">Email Address</span>
                            <span class="value">
                                <a href="mailto:{{ $subscription->email }}" class="text-decoration-none text-primary">{{ $subscription->email }}</a>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Status</span>
                            <span class="value">
                                <span class="{{ $subscription->status ? 'badge-active' : 'badge-inactive' }}">
                                    <i class="fas {{ $subscription->status ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                    {{ $subscription->status ? 'Active' : 'Inactive' }}
                                </span>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="label">Country</span>
                            <span class="value">{{ $subscription->country ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">City</span>
                            <span class="value">{{ $subscription->city ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Region</span>
                            <span class="value">{{ $subscription->region ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">IP Address</span>
                            <span class="value">{{ $subscription->ip_address ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Device Info Card -->
                <div class="col-md-6">
                    <div class="info-pane">
                        <div class="pane-header text-info">
                            <i class="fas fa-laptop-code"></i> Technical Details
                        </div>
                        <div class="info-row">
                            <span class="label">Device Class</span>
                            <span class="value">{{ $subscription->device ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Browser Client</span>
                            <span class="value">{{ Str::limit($subscription->browser ?? '—', 35) }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Operating System</span>
                            <span class="value">{{ $subscription->platform ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Created Date</span>
                            <span class="value">{{ $subscription->created_at->format('M d, Y H:i:s') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Last Updated</span>
                            <span class="value">{{ $subscription->updated_at->format('M d, Y H:i:s') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Agent Codebox -->
            @if($subscription->user_agent)
            <div class="mb-4">
                <div class="pane-header text-warning border-0 pb-0 mb-3">
                    <i class="fas fa-fingerprint me-2"></i> Raw User Agent String
                </div>
                <div class="useragent-box">
                    {{ $subscription->user_agent }}
                </div>
            </div>
            @endif


        </div>
    </div>
</div>
@endsection