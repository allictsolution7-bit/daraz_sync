@extends('layouts.master')

@section('styles')
<style>
    .settings-card {
        background: white;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .settings-card h5 {
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
        color: #333;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 8px;
        padding: 20px;
        color: white;
        text-align: center;
    }

    .stat-card.pending {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-card.fired {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-card.failed {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .stat-card .stat-value {
        font-size: 32px;
        font-weight: bold;
    }

    .stat-card .stat-label {
        font-size: 14px;
        opacity: 0.9;
    }

    .toggle-switch {
        position: relative;
        width: 60px;
        height: 30px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 30px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 22px;
        width: 22px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: #197A94;
    }

    input:checked + .toggle-slider:before {
        transform: translateX(30px);
    }

    .payment-method-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .payment-method-item {
        display: flex;
        align-items: center;
        padding: 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .payment-method-item:hover {
        border-color: #197A94;
        background: #f8f9fa;
    }

    .payment-method-item input:checked + .payment-method-label {
        color: #197A94;
        font-weight: 500;
    }

    .payment-method-item input {
        margin-right: 10px;
    }

    .api-key-input {
        font-family: monospace;
        letter-spacing: 1px;
    }

    .test-connection-btn {
        margin-top: 10px;
    }

    .connection-status {
        margin-top: 10px;
        padding: 10px;
        border-radius: 4px;
        display: none;
    }

    .connection-status.success {
        background: #d4edda;
        color: #155724;
        display: block;
    }

    .connection-status.error {
        background: #f8d7da;
        color: #721c24;
        display: block;
    }

    .form-help {
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
    }

    .quick-links {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .quick-links a {
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        color: white;
        font-size: 14px;
    }

    .quick-links .btn-pending {
        background: #f5576c;
    }

    .quick-links .btn-history {
        background: #4facfe;
    }

    .firing-method-option {
        display: flex;
        align-items: flex-start;
        padding: 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        flex: 1;
    }

    .firing-method-option:hover {
        border-color: #197A94;
        background: #f8f9fa;
    }

    .firing-method-option.active {
        border-color: #197A94;
        background: #eef7fa;
    }

    .firing-method-option input[type="radio"] {
        margin-top: 3px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h5 class="mb-3">Delayed Purchase Events Settings</h5>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Events</div>
        </div>
        <div class="stat-card pending">
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card fired">
            <div class="stat-value">{{ $stats['fired'] }}</div>
            <div class="stat-label">Fired</div>
        </div>
        <div class="stat-card failed">
            <div class="stat-value">{{ $stats['failed'] }}</div>
            <div class="stat-label">Failed</div>
        </div>
    </div>

    <form action="{{ route('admin.delayed-events.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Master Toggle -->
        <div class="settings-card">
            <h5>Feature Status</h5>
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <strong>Enable Delayed Purchase Events</strong>
                    <p class="form-help mb-0">When enabled, purchase events for selected payment methods will be stored and fired only after admin confirmation.</p>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="is_enabled" value="1" {{ $delayedSettings->is_enabled ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>

        <!-- Firing Method Selection -->
        <div class="settings-card">
            <h5>Firing Method</h5>
            <p class="form-help">Choose how delayed purchase events are sent to your analytics providers.</p>

            <div class="d-flex gap-4 mb-3">
                <label class="firing-method-option {{ ($delayedSettings->firing_method ?? 'sgtm') === 'pixelfly' ? 'active' : '' }}">
                    <input type="radio" name="firing_method" value="pixelfly"
                        {{ ($delayedSettings->firing_method ?? 'sgtm') === 'pixelfly' ? 'checked' : '' }}
                        onchange="toggleFiringMethod()">
                    <div class="ms-2">
                        <strong>PixelFly (Proxy)</strong>
                        <div class="form-help mb-0">Use this if your PixelFly container is a Proxy container. Delayed events are sent through the PixelFly proxy to Meta CAPI, GA4, TikTok, etc.</div>
                    </div>
                </label>
                <label class="firing-method-option {{ ($delayedSettings->firing_method ?? 'sgtm') === 'sgtm' ? 'active' : '' }}">
                    <input type="radio" name="firing_method" value="sgtm"
                        {{ ($delayedSettings->firing_method ?? 'sgtm') === 'sgtm' ? 'checked' : '' }}
                        onchange="toggleFiringMethod()">
                    <div class="ms-2">
                        <strong>sGTM</strong>
                        <div class="form-help mb-0">Use this if your PixelFly container is an sGTM container. Delayed events are sent via GA4 Measurement Protocol to your server-side GTM, which distributes to all configured tags (Google Ads, Meta, etc.).</div>
                    </div>
                </label>
            </div>
            <div class="alert alert-warning mb-0" style="font-size: 13px;">
                <strong>Important:</strong> Select the method that matches your PixelFly container type. Mismatching may cause delayed purchase events to not reach all your analytics providers.
            </div>
        </div>

        <!-- PixelFly Configuration -->
        <div class="settings-card" id="pixelflyConfig" style="{{ ($delayedSettings->firing_method ?? 'sgtm') === 'sgtm' ? 'display:none' : '' }}">
            <h5>PixelFly Configuration</h5>

            <div class="mb-3">
                <label class="form-label">API Key</label>
                <input type="text" name="pixelfly_api_key" class="form-control api-key-input"
                    value="{{ $delayedSettings->pixelfly_api_key }}"
                    placeholder="Enter your PixelFly API key">
                <div class="form-help">Get your API key from the PixelFly dashboard.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Endpoint URL</label>
                <input type="url" name="pixelfly_endpoint" class="form-control"
                    value="{{ $delayedSettings->pixelfly_endpoint }}"
                    placeholder="https://track.pixelfly.io/e">
                <div class="form-help">Default: https://track.pixelfly.io/e</div>
            </div>

            <button type="button" class="btn btn-outline-primary test-connection-btn" onclick="testConnection()">
                <i class="fas fa-plug"></i> Test Connection
            </button>
            <div id="connectionStatus" class="connection-status"></div>
        </div>

        <!-- sGTM Configuration -->
        <div class="settings-card" id="sgtmConfig" style="{{ ($delayedSettings->firing_method ?? 'sgtm') === 'pixelfly' ? 'display:none' : '' }}">
            <h5>sGTM Configuration</h5>

            <div class="mb-3">
                <label class="form-label">sGTM Endpoint URL</label>
                <input type="url" name="sgtm_endpoint" class="form-control"
                    value="{{ $delayedSettings->sgtm_endpoint }}"
                    placeholder="https://sgtm.yourdomain.com">
                <div class="form-help">Your server-side GTM container URL (without /mp/collect path).</div>
            </div>

            <div class="mb-3">
                <label class="form-label">GA4 Measurement ID</label>
                <input type="text" name="sgtm_measurement_id" class="form-control api-key-input"
                    value="{{ $delayedSettings->sgtm_measurement_id }}"
                    placeholder="G-XXXXXXXXXX">
                <div class="form-help">Your GA4 Measurement ID (starts with G-).</div>
            </div>

            <div class="mb-3">
                <label class="form-label">API Secret</label>
                <input type="text" name="sgtm_api_secret" class="form-control api-key-input"
                    value="{{ $delayedSettings->sgtm_api_secret }}"
                    placeholder="Enter your GA4 API secret">
                <div class="form-help">Create an API secret in GA4 Admin > Data Streams > Measurement Protocol API secrets.</div>
            </div>

            <button type="button" class="btn btn-outline-primary test-connection-btn" onclick="testConnection()">
                <i class="fas fa-plug"></i> Test Connection
            </button>
            <div id="sgtmConnectionStatus" class="connection-status"></div>
        </div>

        <!-- Payment Methods -->
        <div class="settings-card">
            <h5>Enabled Payment Methods</h5>
            <p class="form-help">Select which payment methods should use delayed purchase events. Typically, these are manual/offline payment methods where orders need confirmation.</p>

            <div class="payment-method-grid">
                @foreach($availablePaymentMethods as $value => $label)
                <label class="payment-method-item">
                    <input type="checkbox" name="enabled_payment_methods[]" value="{{ $value }}"
                        {{ in_array($value, $delayedSettings->enabled_payment_methods ?? []) ? 'checked' : '' }}>
                    <span class="payment-method-label">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Order Sources (Offline/POS Orders) -->
        <div class="settings-card">
            <h5>Enabled Order Sources (Offline Orders)</h5>
            <p class="form-help">Select which offline order sources should fire purchase events to Facebook as offline conversions. These orders come from POS, phone calls, social media, etc.</p>

            <div class="payment-method-grid">
                @foreach($availableOrderSources as $value => $label)
                <label class="payment-method-item">
                    <input type="checkbox" name="enabled_order_sources[]" value="{{ $value }}"
                        {{ in_array($value, $delayedSettings->enabled_order_sources ?? []) ? 'checked' : '' }}>
                    <span class="payment-method-label">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex justify-content-between align-items-center">
            <div class="quick-links">
                <a href="{{ route('admin.delayed-events.pending') }}" class="btn-pending">
                    <i class="fas fa-clock"></i> View Pending Events ({{ $stats['pending'] }})
                </a>
                <a href="{{ route('admin.delayed-events.history') }}" class="btn-history">
                    <i class="fas fa-history"></i> Event History
                </a>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Save Settings
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
    document.querySelectorAll('.firing-method-option').forEach(el => {
        el.classList.remove('active');
    });
    document.querySelector('input[name="firing_method"]:checked').closest('.firing-method-option').classList.add('active');
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
    el.textContent = 'Testing connection...';
    el.style.display = 'block';
    el.style.background = '#fff3cd';
    el.style.color = '#856404';
}

function showResult(el, data) {
    el.className = data.success ? 'connection-status success' : 'connection-status error';
    el.textContent = data.message;
}

function showError(el, message) {
    el.className = 'connection-status error';
    el.textContent = 'Connection failed: ' + message;
}
</script>
@endsection
