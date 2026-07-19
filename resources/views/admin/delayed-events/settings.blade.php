@extends('layouts.master')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* Complete Control Panel Overhaul */
    #delayed-events-settings-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
    }

    .premium-panel-header {
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 24px;
        margin-bottom: 32px;
    }

    .premium-panel-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .premium-panel-header h1 i {
        background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Tabs Layout Styling */
    .nav-tabs-overhaul {
        background: #e2e8f0;
        padding: 6px;
        border-radius: 14px;
        gap: 4px;
        border: none;
        margin-bottom: 32px;
    }

    .nav-tabs-overhaul .nav-item {
        flex: 1;
    }

    .nav-tabs-overhaul .nav-link {
        width: 100%;
        border-radius: 10px;
        color: #475569;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 14px 24px;
        text-align: center;
        transition: all 0.2s ease;
        border: none !important;
        background: transparent;
    }

    .nav-tabs-overhaul .nav-link:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.4);
    }

    .nav-tabs-overhaul .nav-link.active {
        background: #ffffff !important;
        color: #4f46e5 !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    /* Elegant Stats Grid */
    .stats-overhaul {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 32px;
    }

    @media (max-width: 992px) {
        .stats-overhaul {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .stats-overhaul {
            grid-template-columns: 1fr;
        }
    }

    .stat-box {
        position: relative;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .stat-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    .stat-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    .stat-box-total::before { background-color: #6366f1; }
    .stat-box-pending::before { background-color: #ec4899; }
    .stat-box-fired::before { background-color: #10b981; }
    .stat-box-failed::before { background-color: #f59e0b; }

    .stat-box .stat-meta h4 {
        margin: 0;
        font-size: 0.8rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .stat-box .stat-meta .stat-val {
        font-size: 1.85rem;
        font-weight: 800;
        color: #0f172a;
    }

    .stat-box .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-icon-total { background-color: #e0f2fe; color: #0284c7; }
    .stat-icon-pending { background-color: #fce7f3; color: #be185d; }
    .stat-icon-fired { background-color: #d1fae5; color: #059669; }
    .stat-icon-failed { background-color: #fef3c7; color: #d97706; }

    /* Custom Switch & Control Cards */
    .control-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .control-card h5 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-top: 0;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .control-card h5 i {
        color: #6366f1;
    }

    .toggle-bar {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Firing Option Selection */
    .firing-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .firing-grid {
            grid-template-columns: 1fr;
        }
    }

    .firing-card-option {
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 24px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #ffffff;
        position: relative;
        display: flex;
        gap: 16px;
        margin-bottom: 0;
    }

    .firing-card-option:hover {
        border-color: #6366f1;
        background: #f8fafc;
    }

    .firing-card-option.active {
        border-color: #6366f1;
        background: #f5f3ff;
    }

    .firing-card-option input[type="radio"] {
        width: 20px;
        height: 20px;
        accent-color: #6366f1;
        margin-top: 4px;
    }

    .firing-card-option strong {
        font-size: 1.05rem;
        color: #0f172a;
        display: block;
        margin-bottom: 6px;
    }

    .firing-card-option .option-desc {
        font-size: 0.825rem;
        color: #64748b;
        line-height: 1.5;
    }

    /* Checkbox list overhaul */
    .checkbox-panel-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 16px;
    }

    .checkbox-panel-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 0;
    }

    .checkbox-panel-item:hover {
        background: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .checkbox-panel-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #6366f1;
        margin-right: 14px;
    }

    .checkbox-panel-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: #334155;
    }

    .checkbox-panel-item input:checked + .checkbox-panel-label {
        color: #6366f1;
    }

    /* Inputs formatting */
    .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.875rem;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 12px 18px;
        font-size: 0.95rem;
        color: #0f172a;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background-color: #ffffff;
    }

    .form-help {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 6px;
    }

    /* Diagnostics connection box */
    .connection-status {
        margin-top: 20px;
        padding: 16px 20px;
        border-radius: 12px;
        display: none;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .connection-status.success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        display: block;
    }

    .connection-status.error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        display: block;
    }

    /* Premium buttons & Action bars */
    .btn-overhaul {
        font-weight: 700;
        padding: 14px 28px;
        border-radius: 12px;
        border: none;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
    }

    .btn-overhaul:hover {
        transform: translateY(-2px);
    }

    .btn-overhaul-primary {
        background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
    }

    .btn-overhaul-primary:hover {
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    .btn-overhaul-outline {
        background: #ffffff;
        border: 2px solid #cbd5e1;
        color: #475569;
    }

    .btn-overhaul-outline:hover {
        border-color: #6366f1;
        color: #6366f1;
        background: #fefeff;
    }

    .quick-links {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .quick-links a {
        padding: 14px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .quick-links a:hover {
        transform: translateY(-2px);
    }

    .quick-links .btn-pending {
        background-color: #fff1f2;
        color: #e11d48;
        border: 2px solid #fecdd3;
    }

    .quick-links .btn-pending:hover {
        background-color: #fecdd3;
        box-shadow: 0 6px 14px rgba(225, 29, 72, 0.15);
    }

    .quick-links .btn-history {
        background-color: #f0f9ff;
        color: #0284c7;
        border: 2px solid #bae6fd;
    }

    .quick-links .btn-history:hover {
        background-color: #bae6fd;
        box-shadow: 0 6px 14px rgba(2, 132, 199, 0.15);
    }
</style>
@endsection

@section('content')
<div id="delayed-events-settings-page" class="container-fluid px-4 py-4">
    <!-- Premium Header -->
    <div class="premium-panel-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1>
                    <i class="fas fa-clock"></i>
                    Delayed Event Dispatcher Engine
                </h1>
                <p class="text-muted mb-0">Manage outbound analytics delays, confirmation routes, and payment gateways filters.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:12px;">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Overhauled Stats Cards -->
    <div class="stats-overhaul">
        <div class="stat-box stat-box-total">
            <div class="stat-meta">
                <h4>Total Events</h4>
                <div class="stat-val">{{ $stats['total'] }}</div>
            </div>
            <div class="stat-icon stat-icon-total">
                <i class="fas fa-database"></i>
            </div>
        </div>
        <div class="stat-box stat-box-pending">
            <div class="stat-meta">
                <h4>Pending Queue</h4>
                <div class="stat-val">{{ $stats['pending'] }}</div>
            </div>
            <div class="stat-icon stat-icon-pending">
                <i class="fas fa-hourglass-half"></i>
            </div>
        </div>
        <div class="stat-box stat-box-fired">
            <div class="stat-meta">
                <h4>Fired Outbound</h4>
                <div class="stat-val">{{ $stats['fired'] }}</div>
            </div>
            <div class="stat-icon stat-icon-fired">
                <i class="fas fa-paper-plane"></i>
            </div>
        </div>
        <div class="stat-box stat-box-failed">
            <div class="stat-meta">
                <h4>Relay Errors</h4>
                <div class="stat-val">{{ $stats['failed'] }}</div>
            </div>
            <div class="stat-icon stat-icon-failed">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs nav-tabs-overhaul" id="settingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="dispatcher-core-tab" data-bs-toggle="tab" data-bs-target="#dispatcher-core" type="button" role="tab">
                <i class="fas fa-sliders-h me-2"></i> Dispatcher Settings
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="gateways-filters-tab" data-bs-toggle="tab" data-bs-target="#gateways-filters" type="button" role="tab">
                <i class="fas fa-filter me-2"></i> Filter Rules & Sources
            </button>
        </li>
    </ul>

    <form action="{{ route('admin.delayed-events.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="tab-content" id="settingsTabsContent">
            <!-- Tab 1: Dispatcher Settings -->
            <div class="tab-pane fade show active" id="dispatcher-core" role="tabpanel">
                
                <!-- Master Status Toggle -->
                <div class="control-card">
                    <h5><i class="fas fa-power-off text-danger"></i> System Status</h5>
                    <div class="toggle-bar">
                        <div>
                            <strong class="text-dark d-block">Enable Delayed Purchase Events Dispatching</strong>
                            <span class="form-help mb-0">Activating this setting routes purchase dispatches into a secure buffer queue, requiring manual authorization before transmit to analytics.</span>
                        </div>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_enabled" name="is_enabled" value="1" {{ $delayedSettings->is_enabled ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_enabled"></label>
                        </div>
                    </div>
                </div>

                <!-- Firing Method -->
                <div class="control-card">
                    <h5><i class="fas fa-network-wired text-primary"></i> Relay Protocol Selection</h5>
                    <p class="form-help mb-4">Select your data dispatcher channel to route queued conversions to analytics integrations.</p>

                    <div class="firing-grid">
                        <label class="firing-card-option {{ ($delayedSettings->firing_method ?? 'sgtm') === 'pixelfly' ? 'active' : '' }}">
                            <input type="radio" name="firing_method" value="pixelfly"
                                {{ ($delayedSettings->firing_method ?? 'sgtm') === 'pixelfly' ? 'checked' : '' }}
                                onchange="toggleFiringMethod()">
                            <div>
                                <strong>Cloud Proxy Engine</strong>
                                <span class="option-desc">Routes conversion payloads through the cloud proxy channel to Meta CAPI, TikTok, Google Analytics, and general tracking containers.</span>
                            </div>
                        </label>

                        <label class="firing-card-option {{ ($delayedSettings->firing_method ?? 'sgtm') === 'sgtm' ? 'active' : '' }}">
                            <input type="radio" name="firing_method" value="sgtm"
                                {{ ($delayedSettings->firing_method ?? 'sgtm') === 'sgtm' ? 'checked' : '' }}
                                onchange="toggleFiringMethod()">
                            <div>
                                <strong>Server-Side GTM Stream</strong>
                                <span class="option-desc">Relays buffered events using GA4 Measurement Protocol straight to your custom sGTM container endpoint for tag distributions (Ads, Meta, TikTok, etc.).</span>
                            </div>
                        </label>
                    </div>

                    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center gap-2 mt-3 mb-0" style="font-size: 13px; border-radius:12px;">
                        <i class="fas fa-exclamation-circle text-warning fa-lg"></i>
                        <div>
                            <strong>Attention Required:</strong> Match your relay channel to your container architecture. Incorrect routing will block analytics dispatches from reaching target trackers.
                        </div>
                    </div>
                </div>

                <!-- PixelFly Configuration -->
                <div class="control-card" id="pixelflyConfig" style="{{ ($delayedSettings->firing_method ?? 'sgtm') === 'sgtm' ? 'display:none' : '' }}">
                    <h5><i class="fas fa-sliders-h text-info"></i> Cloud Proxy Integrations</h5>

                    <div class="mb-4">
                        <label class="form-label">Access Credential Token</label>
                        <input type="text" name="pixelfly_api_key" class="form-control"
                            value="{{ $delayedSettings->pixelfly_api_key }}"
                            placeholder="Enter your PixelFly API key">
                        <div class="form-help">Retrieve your security token key from the cloud console portal.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Gateway Destination Link</label>
                        <input type="url" name="pixelfly_endpoint" class="form-control"
                            value="{{ $delayedSettings->pixelfly_endpoint }}"
                            placeholder="https://track.pixelfly.io/e">
                        <div class="form-help">Standard endpoint URL is set to https://track.pixelfly.io/e by default.</div>
                    </div>

                    <button type="button" class="btn-overhaul btn-overhaul-outline" onclick="testConnection()">
                        <i class="fas fa-plug"></i> Verify Connection Pipeline
                    </button>
                    <div id="connectionStatus" class="connection-status"></div>
                </div>

                <!-- sGTM Configuration -->
                <div class="control-card" id="sgtmConfig" style="{{ ($delayedSettings->firing_method ?? 'pixelfly') === 'pixelfly' ? 'display:none' : '' }}">
                    <h5><i class="fas fa-server text-info"></i> sGTM Container Integrations</h5>

                    <div class="mb-4">
                        <label class="form-label">Custom sGTM Server URL</label>
                        <input type="url" name="sgtm_endpoint" class="form-control"
                            value="{{ $delayedSettings->sgtm_endpoint }}"
                            placeholder="https://sgtm.yourdomain.com">
                        <div class="form-help">The base domain URL for your server GTM workspace (omit the /mp/collect path extension).</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Google Measurement ID (G-)</label>
                        <input type="text" name="sgtm_measurement_id" class="form-control"
                            value="{{ $delayedSettings->sgtm_measurement_id }}"
                            placeholder="G-XXXXXXXXXX">
                        <div class="form-help">The Google stream tracking ID sequence (prefixed with G-).</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Measurement Protocol API Secret</label>
                        <input type="text" name="sgtm_api_secret" class="form-control"
                            value="{{ $delayedSettings->sgtm_api_secret }}"
                            placeholder="Enter your GA4 API secret">
                        <div class="form-help">Generate a protocol authentication secret key inside GA4 Stream Admin configurations.</div>
                    </div>

                    <button type="button" class="btn-overhaul btn-overhaul-outline" onclick="testConnection()">
                        <i class="fas fa-plug"></i> Verify Connection Pipeline
                    </button>
                    <div id="sgtmConnectionStatus" class="connection-status"></div>
                </div>
            </div>

            <!-- Tab 2: Filter Rules & Sources -->
            <div class="tab-pane fade" id="gateways-filters" role="tabpanel">
                
                <!-- Payment Gateways Filter -->
                <div class="control-card">
                    <h5><i class="fas fa-credit-card text-warning"></i> Target Payment Gateways</h5>
                    <p class="form-help mb-4">Choose the billing channels that will trigger event buffering. Manual payment methods (COD, Cash) usually require delay routing.</p>

                    <div class="checkbox-panel-grid">
                        @foreach($availablePaymentMethods as $value => $label)
                        <label class="checkbox-panel-item">
                            <input type="checkbox" name="enabled_payment_methods[]" value="{{ $value }}"
                                {{ in_array($value, $delayedSettings->enabled_payment_methods ?? []) ? 'checked' : '' }}>
                            <span class="checkbox-panel-label">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Order Sources -->
                <div class="control-card">
                    <h5><i class="fas fa-store text-danger"></i> Offline Purchase Channels</h5>
                    <p class="form-help mb-4">Check the channel sources (POS registers, phone telesales, social channels) to dispatch as offline conversions to Meta trackers.</p>

                    <div class="checkbox-panel-grid">
                        @foreach($availableOrderSources as $value => $label)
                        <label class="checkbox-panel-item">
                            <input type="checkbox" name="enabled_order_sources[]" value="{{ $value }}"
                                {{ in_array($value, $delayedSettings->enabled_order_sources ?? []) ? 'checked' : '' }}>
                            <span class="checkbox-panel-label">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Master Actions bar (Always bottom static) -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-4 mt-4 mb-5">
            <div class="quick-links">
                <a href="{{ route('admin.delayed-events.pending') }}" class="btn-pending">
                    <i class="fas fa-clock"></i> Manage Pending Queue ({{ $stats['pending'] }})
                </a>
                <a href="{{ route('admin.delayed-events.history') }}" class="btn-history">
                    <i class="fas fa-history"></i> Dispatcher Log History
                </a>
            </div>
            <button type="submit" class="btn-overhaul btn-overhaul-primary px-5">
                <i class="fas fa-save"></i> Save Dispatch Parameters
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function toggleFiringMethod() {
    const selected = document.querySelector('input[name="firing_method"]:checked').value;
    const pixelflyConfig = document.getElementById('pixelflyConfig');
    const sgtmConfig = document.getElementById('sgtmConfig');

    // Toggle config sections
    pixelflyConfig.style.display = selected === 'pixelfly' ? '' : 'none';
    sgtmConfig.style.display = selected === 'sgtm' ? '' : 'none';

    // Update active class on radio options
    document.querySelectorAll('.firing-card-option').forEach(el => {
        el.classList.remove('active');
    });
    document.querySelector('input[name="firing_method"]:checked').closest('.firing-card-option').classList.add('active');
}

function testConnection() {
    const firingMethod = document.querySelector('input[name="firing_method"]:checked').value;

    if (firingMethod === 'sgtm') {
        testSgtmConnection();
    } else {
        testPixelflyConnection();
    }
}

function testPixelflyConnection() {
    const apiKey = document.querySelector('input[name="pixelfly_api_key"]').value;
    const endpoint = document.querySelector('input[name="pixelfly_endpoint"]').value;
    const statusDiv = document.getElementById('connectionStatus');

    showTestingStatus(statusDiv);

    fetch('{{ route("admin.delayed-events.test-connection") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ firing_method: 'pixelfly', api_key: apiKey, endpoint: endpoint })
    })
    .then(response => response.json())
    .then(data => showResult(statusDiv, data))
    .catch(error => showError(statusDiv, error.message));
}

function testSgtmConnection() {
    const endpoint = document.querySelector('input[name="sgtm_endpoint"]').value;
    const measurementId = document.querySelector('input[name="sgtm_measurement_id"]').value;
    const apiSecret = document.querySelector('input[name="sgtm_api_secret"]').value;
    const statusDiv = document.getElementById('sgtmConnectionStatus');

    showTestingStatus(statusDiv);

    fetch('{{ route("admin.delayed-events.test-connection") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            firing_method: 'sgtm',
            sgtm_endpoint: endpoint,
            sgtm_measurement_id: measurementId,
            sgtm_api_secret: apiSecret
        })
    })
    .then(response => response.json())
    .then(data => showResult(statusDiv, data))
    .catch(error => showError(statusDiv, error.message));
}

function showTestingStatus(el) {
    el.className = 'connection-status';
    el.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Testing connection bridge...';
    el.style.display = 'block';
    el.style.background = '#fffbeb';
    el.style.color = '#b45309';
    el.style.border = '1px solid #fde68a';
}

function showResult(el, data) {
    el.className = data.success ? 'connection-status success' : 'connection-status error';
    el.innerHTML = data.success 
        ? '<i class="fas fa-check-circle me-2"></i> ' + data.message 
        : '<i class="fas fa-times-circle me-2"></i> ' + data.message;
}

function showError(el, message) {
    el.className = 'connection-status error';
    el.innerHTML = '<i class="fas fa-times-circle me-2"></i> Connection failed: ' + message;
}
</script>
@endsection
