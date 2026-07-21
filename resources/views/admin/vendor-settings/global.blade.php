@extends('layouts.master')

@section('title', 'Partner Global Configuration')

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
    .vp-page-wrapper .form-control, .vp-page-wrapper .form-select, .vp-page-wrapper textarea {
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 500;
        background-color: #f8fafc;
    }
    .vp-page-wrapper .form-control:focus, .vp-page-wrapper .form-select:focus, .vp-page-wrapper textarea:focus {
        background-color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }
    .vp-page-wrapper label {
        font-size: 12px;
        font-weight: 800;
        color: #1e293b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }
    .btn-save-vp {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 15px;
        box-shadow: 0 4px 14px rgba(16,185,129,0.35);
    }
    .btn-save-vp:hover {
        transform: translateY(-2px);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="vp-page-wrapper">
    <!-- Header Banner -->
    <div class="vp-hero-card d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="vp-title-icon">
                <i class="fas fa-sliders font-weight-bold"></i>
            </div>
            <div>
                <h3 class="mb-0 text-white font-weight-bold">Partner Global Configuration</h3>
                <p class="mb-0 text-white-50 small">Configure platform-wide multi-vendor rules, default commissions & payout policies</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary rounded-3 px-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Partners
            </a>
        </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 shadow-sm d-flex align-items-center justify-content-between mb-4" style="background:#dcfce7; color:#15803d;">
            <div>
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- System Status Toggle Banner -->
    <div class="vp-card">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h5 class="font-weight-bold text-dark mb-1">
                    <i class="fas fa-power-off text-primary me-2"></i> Multi-Vendor Module Engine Status
                </h5>
                <p class="text-muted small mb-0">Enable or disable multi-partner vendor store functionality across the website platform</p>
            </div>
            <form action="{{ route('admin.vendor-settings.toggle-system') }}" method="POST">
                @csrf
                <div class="form-check form-switch d-flex align-items-center gap-2">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        name="enabled" 
                        value="1"
                        {{ $vendorSettings['system']['vendor_system_enabled']['value'] ? 'checked' : '' }}
                        onchange="if(confirm('Are you sure you want to toggle the multi-seller engine?')) this.form.submit(); else this.checked = !this.checked;"
                        style="width: 3.2rem; height: 1.7rem;"
                    >
                    <span class="badge bg-{{ $vendorSettings['system']['vendor_system_enabled']['value'] ? 'success' : 'danger' }} rounded-pill px-3 py-2 font-weight-bold">
                        {{ $vendorSettings['system']['vendor_system_enabled']['value'] ? 'MODULE ACTIVE' : 'MODULE DISABLED' }}
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Configuration Form -->
    <form action="{{ route('admin.vendor-settings.global.update') }}" method="POST">
        @csrf

        <div class="row g-4">
            <!-- Left Column: Financial & Product Rules -->
            <div class="col-lg-7">
                <!-- Commission Rules -->
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-percent text-primary"></i>
                        <h5>Global Commission Settings</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @foreach($vendorSettings['commission'] ?? [] as $setting)
                                <div class="col-md-6">
                                    <label>{{ $setting['label'] }}</label>
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        name="settings[{{ $setting['key'] }}]" 
                                        class="form-control" 
                                        value="{{ $setting['raw_value'] }}"
                                    >
                                    <small class="text-muted d-block mt-1">{{ $setting['description'] }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Withdrawal Settings -->
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-hand-holding-dollar text-primary"></i>
                        <h5>Withdrawal & Payout Thresholds</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @foreach($vendorSettings['withdrawal'] ?? [] as $setting)
                                <div class="col-md-6">
                                    <label>{{ $setting['label'] }}</label>
                                    <input 
                                        type="number" 
                                        step="{{ $setting['type'] === 'decimal' ? '0.01' : '1' }}" 
                                        name="settings[{{ $setting['key'] }}]" 
                                        class="form-control" 
                                        value="{{ $setting['raw_value'] }}"
                                    >
                                    <small class="text-muted d-block mt-1">{{ $setting['description'] }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Payout Methods Formatter -->
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-credit-card text-primary"></i>
                        <h5>Allowed Payout Gateways (JSON Config)</h5>
                    </div>
                    <div class="card-body p-4">
                        @foreach($vendorSettings['payout'] ?? [] as $setting)
                            <div class="mb-3">
                                <label>{{ $setting['label'] }}</label>
                                <textarea 
                                    name="settings[{{ $setting['key'] }}]" 
                                    class="form-control font-monospace" 
                                    rows="4"
                                >{{ $setting['raw_value'] }}</textarea>
                                <small class="text-muted d-block mt-1">{{ $setting['description'] }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Registration, Notifications & Limits -->
            <div class="col-lg-5">
                <!-- Product Approval Settings -->
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-box text-primary"></i>
                        <h5>Product Listing Controls</h5>
                    </div>
                    <div class="card-body p-4">
                        @foreach($vendorSettings['product'] ?? [] as $setting)
                            <div class="mb-3">
                                @if($setting['type'] === 'boolean')
                                    <div class="form-check form-switch pt-1">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="settings[{{ $setting['key'] }}]" 
                                            value="1"
                                            {{ $setting['value'] ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label font-weight-bold" style="text-transform:none; font-size:13px;">
                                            {{ $setting['label'] }}
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">{{ $setting['description'] }}</small>
                                @else
                                    <label>{{ $setting['label'] }}</label>
                                    <input 
                                        type="number" 
                                        name="settings[{{ $setting['key'] }}]" 
                                        class="form-control" 
                                        value="{{ $setting['raw_value'] }}"
                                    >
                                    <small class="text-muted d-block mt-1">{{ $setting['description'] }}</small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Partner Registration Policy -->
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-user-plus text-primary"></i>
                        <h5>Partner Registration Policy</h5>
                    </div>
                    <div class="card-body p-4">
                        @foreach($vendorSettings['registration'] ?? [] as $setting)
                            <div class="mb-3">
                                @if($setting['type'] === 'boolean')
                                    <div class="form-check form-switch pt-1">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="settings[{{ $setting['key'] }}]" 
                                            value="1"
                                            {{ $setting['value'] ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label font-weight-bold" style="text-transform:none; font-size:13px;">
                                            {{ $setting['label'] }}
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">{{ $setting['description'] }}</small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Notifications & Dashboard -->
                <div class="vp-card">
                    <div class="card-header d-flex align-items-center gap-2">
                        <i class="fas fa-bell text-primary"></i>
                        <h5>Notifications & Dashboard</h5>
                    </div>
                    <div class="card-body p-4">
                        @foreach($vendorSettings['notification'] ?? [] as $setting)
                            <div class="mb-3 border-bottom pb-3">
                                <div class="form-check form-switch pt-1">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="settings[{{ $setting['key'] }}]" 
                                        value="1"
                                        {{ $setting['value'] ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label font-weight-bold" style="text-transform:none; font-size:13px;">
                                        {{ $setting['label'] }}
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">{{ $setting['description'] }}</small>
                            </div>
                        @endforeach

                        @foreach($vendorSettings['dashboard'] ?? [] as $setting)
                            <div class="mb-3">
                                <label>{{ $setting['label'] }}</label>
                                <input 
                                    type="number" 
                                    name="settings[{{ $setting['key'] }}]" 
                                    class="form-control" 
                                    value="{{ $setting['raw_value'] }}"
                                >
                                <small class="text-muted d-block mt-1">{{ $setting['description'] }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-2 mb-4">
                    <button type="submit" class="btn btn-save-vp w-100 shadow">
                        <i class="fas fa-save me-1"></i> Save Global Configurations
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

