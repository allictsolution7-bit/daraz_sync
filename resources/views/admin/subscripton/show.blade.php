@extends('layouts.master')

@section('title', 'Subscription Details')

@push('styles')
<style>
    :root {
        --primary: #4f46e5;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --dark-slate: #1e293b;
        --text-main: #334155;
        --text-muted: #64748b;
        --bg-glass: rgba(255,255,255,0.9);
        --border-glass: rgba(226,232,240,0.8);
        --shadow-premium: 0 10px 30px -5px rgba(0,0,0,0.05);
        --transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    }
    .workspace-card {
        background: var(--bg-glass);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        box-shadow: var(--shadow-premium);
        padding: 30px;
        margin-bottom: 30px;
    }
    .info-pane {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        padding: 22px;
        height: 100%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .pane-header {
        font-size: 14px;
        font-weight: 700;
        color: var(--dark-slate);
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 12px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 9px 0;
        border-bottom: 1px solid #f8fafc;
        font-size: 13.5px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .label { color: var(--text-muted); font-weight: 500; }
    .info-row .value { color: var(--dark-slate); font-weight: 600; text-align: right; word-break: break-all; max-width: 60%; }
    .badge-active   { background:rgba(16,185,129,.1); color:#059669; padding:5px 12px; border-radius:8px; font-size:12px; font-weight:600; }
    .badge-inactive { background:rgba(239,68,68,.1);  color:#dc2626; padding:5px 12px; border-radius:8px; font-size:12px; font-weight:600; }
    .useragent-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px;
        font-size: 12.5px;
        font-family: monospace;
        color: var(--text-muted);
        line-height: 1.6;
        word-break: break-all;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 pt-3">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.subscriptions.index') }}" class="text-decoration-none text-muted">Subscriptions</a></li>
            <li class="breadcrumb-item active font-weight-bold" aria-current="page">Subscriber Details</li>
        </ol>
    </nav>

    <div class="workspace-card">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h4 class="mb-1 fw-bold" style="color:var(--dark-slate);">Subscriber Profile</h4>
                <p class="text-muted mb-0">
                    <i class="fas fa-envelope me-1"></i> {{ $subscription->email }}
                    &bull; Joined {{ $subscription->created_at->format('M d, Y') }}
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <!-- Toggle status -->
                <form action="{{ route('admin.subscriptions.toggle-status', $subscription) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm rounded-pill px-4 {{ $subscription->status ? 'btn-outline-warning' : 'btn-outline-success' }}">
                        <i class="fas {{ $subscription->status ? 'fa-toggle-off' : 'fa-toggle-on' }} me-1"></i>
                        {{ $subscription->status ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                <!-- Delete -->
                <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this subscription?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-4">
                        <i class="fas fa-trash-alt me-1"></i> Delete
                    </button>
                </form>
                <!-- Back -->
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <!-- Info Grid -->
        <div class="row g-4 mb-4">
            <!-- Subscriber Info -->
            <div class="col-md-6">
                <div class="info-pane">
                    <div class="pane-header text-primary">
                        <i class="fas fa-user-circle"></i> Subscriber Information
                    </div>
                    <div class="info-row">
                        <span class="label">Email</span>
                        <span class="value"><a href="mailto:{{ $subscription->email }}" class="text-decoration-none text-primary">{{ $subscription->email }}</a></span>
                    </div>
                    <div class="info-row">
                        <span class="label">Status</span>
                        <span class="value">
                            <span class="{{ $subscription->status ? 'badge-active' : 'badge-inactive' }}">
                                <i class="fas {{ $subscription->status ? 'fa-toggle-on' : 'fa-toggle-off' }} me-1"></i>
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

            <!-- System Metadata -->
            <div class="col-md-6">
                <div class="info-pane">
                    <div class="pane-header text-info">
                        <i class="fas fa-network-wired"></i> Device & System Info
                    </div>
                    <div class="info-row">
                        <span class="label">Device</span>
                        <span class="value">{{ $subscription->device ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Browser</span>
                        <span class="value">{{ Str::limit($subscription->browser ?? '—', 40) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Platform/OS</span>
                        <span class="value">{{ $subscription->platform ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Subscribed On</span>
                        <span class="value">{{ $subscription->created_at->format('M d, Y \a\t h:i A') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Last Updated</span>
                        <span class="value">{{ $subscription->updated_at->format('M d, Y \a\t h:i A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Agent Box -->
        @if($subscription->user_agent)
        <div class="info-pane">
            <div class="pane-header">
                <i class="fas fa-code text-warning"></i> Raw User Agent String
            </div>
            <div class="useragent-box">{{ $subscription->user_agent }}</div>
        </div>
        @endif

    </div>
</div>
@endsection