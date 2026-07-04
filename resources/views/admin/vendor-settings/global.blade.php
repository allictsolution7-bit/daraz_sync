@extends('layouts.master')

@section('title', 'Vendor Global Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4>Vendor Global Settings</h4>
                    <p class="text-muted">Platform-wide settings for the multi-seller system</p>
                </div>
                <div>
                    <a href="" class="btn btn-outline-secondary">
                        <i class="fas fa-download"></i> Export
                    </a>
                </div>
            </div>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- System Status Card --}}
            <div class="card mb-4 border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-power-off"></i> Multi-Seller System Status
                            </h5>
                            <p class="text-muted mb-0">Enable or disable the entire vendor/multi-seller system</p>
                        </div>
                        <form action="{{ route('admin.vendor-settings.toggle-system') }}" method="POST">
                            @csrf
                            <div class="form-check form-switch">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    name="enabled" 
                                    value="1"
                                    {{ $vendorSettings['system']['vendor_system_enabled']['value'] ? 'checked' : '' }}
                                    onchange="this.form.submit()"
                                    style="width: 3rem; height: 1.5rem;"
                                >
                                <label class="form-check-label ms-2">
                                    <strong>
                                        {{ $vendorSettings['system']['vendor_system_enabled']['value'] ? 'ENABLED' : 'DISABLED' }}
                                    </strong>
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Settings Form --}}
            <form action="{{ route('admin.vendor-settings.global.update') }}" method="POST">
                @csrf

                {{-- Commission Settings --}}
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-percent"></i> Commission Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($vendorSettings['commission'] ?? [] as $setting)
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">{{ $setting['label'] }}</label>
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        name="settings[{{ $setting['key'] }}]" 
                                        class="form-control" 
                                        value="{{ $setting['raw_value'] }}"
                                    >
                                    <small class="text-muted">{{ $setting['description'] }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Withdrawal Settings --}}
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Withdrawal Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($vendorSettings['withdrawal'] ?? [] as $setting)
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">{{ $setting['label'] }}</label>
                                    <input 
                                        type="number" 
                                        step="{{ $setting['type'] === 'decimal' ? '0.01' : '1' }}" 
                                        name="settings[{{ $setting['key'] }}]" 
                                        class="form-control" 
                                        value="{{ $setting['raw_value'] }}"
                                    >
                                    <small class="text-muted">{{ $setting['description'] }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Product Approval Settings --}}
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Product Approval Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($vendorSettings['product'] ?? [] as $setting)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ $setting['label'] }}</label>
                                    
                                    @if($setting['type'] === 'boolean')
                                        <div class="form-check form-switch">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                name="settings[{{ $setting['key'] }}]" 
                                                value="1"
                                                {{ $setting['value'] ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label">
                                                {{ $setting['description'] }}
                                            </label>
                                        </div>
                                    @else
                                        <input 
                                            type="number" 
                                            name="settings[{{ $setting['key'] }}]" 
                                            class="form-control" 
                                            value="{{ $setting['raw_value'] }}"
                                        >
                                        <small class="text-muted">{{ $setting['description'] }}</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Registration Settings --}}
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fas fa-user-plus"></i> Registration Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($vendorSettings['registration'] ?? [] as $setting)
                                <div class="col-md-6 mb-3">
                                    @if($setting['type'] === 'boolean')
                                        <div class="form-check form-switch">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                name="settings[{{ $setting['key'] }}]" 
                                                value="1"
                                                {{ $setting['value'] ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label fw-bold">
                                                {{ $setting['label'] }}
                                            </label>
                                        </div>
                                        <small class="text-muted d-block ms-4">{{ $setting['description'] }}</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Notification Settings --}}
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-bell"></i> Notification Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($vendorSettings['notification'] ?? [] as $setting)
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            name="settings[{{ $setting['key'] }}]" 
                                            value="1"
                                            {{ $setting['value'] ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label fw-bold">
                                            {{ $setting['label'] }}
                                        </label>
                                    </div>
                                    <small class="text-muted d-block ms-4">{{ $setting['description'] }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Dashboard Settings --}}
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-tachometer-alt"></i> Dashboard Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($vendorSettings['dashboard'] ?? [] as $setting)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">{{ $setting['label'] }}</label>
                                    <input 
                                        type="number" 
                                        name="settings[{{ $setting['key'] }}]" 
                                        class="form-control" 
                                        value="{{ $setting['raw_value'] }}"
                                    >
                                    <small class="text-muted">{{ $setting['description'] }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Payout Methods --}}
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-credit-card"></i> Payout Methods</h5>
                    </div>
                    <div class="card-body">
                        @foreach($vendorSettings['payout'] ?? [] as $setting)
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ $setting['label'] }}</label>
                                <textarea 
                                    name="settings[{{ $setting['key'] }}]" 
                                    class="form-control" 
                                    rows="4"
                                >{{ $setting['raw_value'] }}</textarea>
                                <small class="text-muted">{{ $setting['description'] }} (JSON format)</small>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Save Button --}}
                <div class="text-end mb-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-save"></i> Save All Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-submit system toggle
document.querySelector('input[name="enabled"]')?.addEventListener('change', function() {
    if (confirm('Are you sure you want to ' + (this.checked ? 'ENABLE' : 'DISABLE') + ' the vendor system?')) {
        this.form.submit();
    } else {
        this.checked = !this.checked;
    }
});
</script>
@endpush

