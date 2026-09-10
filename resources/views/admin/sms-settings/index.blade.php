@extends('layouts.master')

@section('title', 'SMS Gateway & Notification Settings')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #sms-settings-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
    }

    .premium-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
    }

    .premium-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
    }

    .premium-card-header {
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 18px;
        margin-bottom: 22px;
    }

    .premium-card-header .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .icon-box-primary { background-color: #e0f2fe; color: #0284c7; }
    .icon-box-success { background-color: #d1fae5; color: #059669; }
    .icon-box-warning { background-color: #fef3c7; color: #d97706; }
    .icon-box-indigo { background-color: #e0e7ff; color: #4338ca; }
    .icon-box-purple { background-color: #f3e8ff; color: #7e22ce; }

    .premium-card-header h5 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .premium-card-header small {
        color: #64748b;
        font-size: 0.825rem;
    }

    .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.875rem;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 0.925rem;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }

    /* Gateway Selection Cards */
    .gateway-option {
        cursor: pointer;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
        background: #fff;
    }

    .gateway-option:hover {
        border-color: #93c5fd;
        background: #f8fafc;
    }

    .gateway-option.selected {
        border-color: #0284c7;
        background: #f0f9ff;
    }

    .gateway-option input[type="radio"] {
        width: 20px;
        height: 20px;
        accent-color: #0284c7;
    }

    /* Switch toggle */
    .form-check-input {
        width: 44px;
        height: 24px;
        cursor: pointer;
        margin-right: 10px;
    }

    .form-check-input:checked {
        background-color: #0284c7;
        border-color: #0284c7;
    }

    .event-switch-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .variable-pill {
        display: inline-block;
        background: #e2e8f0;
        color: #1e293b;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.78rem;
        font-family: monospace;
        margin-right: 6px;
        margin-bottom: 6px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .variable-pill:hover {
        background: #cbd5e1;
    }

    .role-badge {
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4" id="sms-settings-page">
    <div class="row">
        <div class="col-12">
            <!-- Header section -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">
                        <i class="fas fa-comment-sms text-primary me-2"></i> SMS Gateway & Events Notification
                    </h3>
                    <p class="text-muted mb-0">Configure Multi-Gateway SMS integrations (BulkSMSBD & Awaj), choose active gateway, and toggle event notifications.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="role-badge bg-primary text-white">
                        <i class="fas fa-user-shield me-1"></i> {{ auth()->user()->roles->pluck('name')->first() ?? 'Admin' }}
                    </span>
                    @if($targetUserId)
                        <span class="role-badge bg-secondary text-white">Portal User ID: #{{ $targetUserId }}</span>
                    @else
                        <span class="role-badge bg-dark text-white"><i class="fas fa-globe me-1"></i> Central / Default System</span>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.sms-settings.update') }}" method="POST">
                @csrf
                @if($targetUserId)
                    <input type="hidden" name="target_user_id" value="{{ $targetUserId }}">
                @endif

                <!-- Master Service Toggle -->
                <div class="premium-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box icon-box-primary">
                                <i class="fas fa-power-off"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Global SMS Dispatch Service</h5>
                                <small class="text-muted">Master switch to enable or pause all outgoing SMS messages for this portal.</small>
                            </div>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_enabled" id="is_enabled" {{ old('is_enabled', $settings->is_enabled) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_enabled">Active</label>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Gateway Selection -->
                <div class="premium-card">
                    <div class="premium-card-header">
                        <div class="icon-box icon-box-indigo">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <div>
                            <h5>Select Default SMS Gateway</h5>
                            <small>Choose which gateway will be used to dispatch SMS from this account/portal.</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="gateway-option {{ old('default_gateway', $settings->default_gateway) === 'bulksmsbd' ? 'selected' : '' }}" for="gateway_bulksmsbd">
                                <input type="radio" name="default_gateway" id="gateway_bulksmsbd" value="bulksmsbd" {{ old('default_gateway', $settings->default_gateway) === 'bulksmsbd' ? 'checked' : '' }}>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-1">BulkSMSBD</h6>
                                        <span class="badge bg-info text-dark">Popular</span>
                                    </div>
                                    <p class="text-muted small mb-0">Direct API integration with bulksmsbd.net (API Key + Sender ID)</p>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label class="gateway-option {{ old('default_gateway', $settings->default_gateway) === 'awaj' ? 'selected' : '' }}" for="gateway_awaj">
                                <input type="radio" name="default_gateway" id="gateway_awaj" value="awaj" {{ old('default_gateway', $settings->default_gateway) === 'awaj' ? 'checked' : '' }}>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-1">Awaj (Awaaz) SMS</h6>
                                        <span class="badge bg-primary text-white">Awaj Gateway</span>
                                    </div>
                                    <p class="text-muted small mb-0">Integration with Awaj digital telecom messaging gateway</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Gateway Credentials -->
                <div class="row">
                    <!-- BulkSMSBD Credentials -->
                    <div class="col-lg-6">
                        <div class="premium-card h-100">
                            <div class="premium-card-header">
                                <div class="icon-box icon-box-info">
                                    <i class="fas fa-server"></i>
                                </div>
                                <div>
                                    <h5>BulkSMSBD Credentials</h5>
                                    <small>API configurations for BulkSMSBD</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="bulksmsbd_api_key">API Key</label>
                                <input type="text" class="form-control" name="bulksmsbd_api_key" id="bulksmsbd_api_key" value="{{ old('bulksmsbd_api_key', $settings->bulksmsbd_api_key) }}" placeholder="e.g. 7X8Y9Z...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="bulksmsbd_sender_id">Sender ID / Masking Name</label>
                                <input type="text" class="form-control" name="bulksmsbd_sender_id" id="bulksmsbd_sender_id" value="{{ old('bulksmsbd_sender_id', $settings->bulksmsbd_sender_id) }}" placeholder="e.g. 8809612...">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="bulksmsbd_url">API Endpoint URL</label>
                                <input type="url" class="form-control" name="bulksmsbd_url" id="bulksmsbd_url" value="{{ old('bulksmsbd_url', $settings->bulksmsbd_url ?: 'https://bulksmsbd.net/api/smsapi') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Awaj Credentials -->
                    <div class="col-lg-6">
                        <div class="premium-card h-100">
                            <div class="premium-card-header">
                                <div class="icon-box icon-box-purple">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div>
                                    <h5>Awaj (Awaaz) Credentials</h5>
                                    <small>API configurations for Awaj Gateway</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="awaj_api_key">API Key / Token</label>
                                    <input type="text" class="form-control" name="awaj_api_key" id="awaj_api_key" value="{{ old('awaj_api_key', $settings->awaj_api_key) }}" placeholder="Awaj API Key">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="awaj_sender_id">Sender ID</label>
                                    <input type="text" class="form-control" name="awaj_sender_id" id="awaj_sender_id" value="{{ old('awaj_sender_id', $settings->awaj_sender_id) }}" placeholder="e.g. AWAJ_SMS">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="awaj_client_id">Client ID (Optional)</label>
                                    <input type="text" class="form-control" name="awaj_client_id" id="awaj_client_id" value="{{ old('awaj_client_id', $settings->awaj_client_id) }}" placeholder="Client ID">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="awaj_secret_key">Secret Key (Optional)</label>
                                    <input type="password" class="form-control" name="awaj_secret_key" id="awaj_secret_key" value="{{ old('awaj_secret_key', $settings->awaj_secret_key) }}" placeholder="Secret Key">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="awaj_url">Endpoint URL</label>
                                <input type="url" class="form-control" name="awaj_url" id="awaj_url" value="{{ old('awaj_url', $settings->awaj_url ?: 'https://api.awajdigital.com/api') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Event Triggers & Notification Rules -->
                <div class="premium-card mt-2">
                    <div class="premium-card-header">
                        <div class="icon-box icon-box-warning">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div>
                            <h5>Event Notification Triggers & Templates</h5>
                            <small>Decide which events will send an SMS and customize the template text.</small>
                        </div>
                    </div>

                    <!-- Event 1: Product Sold / Order Placed -->
                    <div class="border rounded-3 p-3 mb-4 bg-light">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold mb-1 text-primary">
                                    <i class="fas fa-shopping-cart me-1"></i> Product Sold / Order Placed SMS
                                </h6>
                                <p class="text-muted small mb-0">Send an SMS notification to the customer when they successfully purchase a product.</p>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="notify_product_sold" id="notify_product_sold" {{ old('notify_product_sold', $settings->notify_product_sold) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="notify_product_sold">Enable</label>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="template_product_sold">Customer SMS Template:</label>
                            <textarea class="form-control" rows="2" name="template_product_sold" id="template_product_sold">{{ old('template_product_sold', $settings->template_product_sold ?: \App\Models\SmsSetting::defaultTemplate('product_sold')) }}</textarea>
                        </div>
                        <div class="small text-muted">
                            <span>Available Placeholders: </span>
                            <span class="variable-pill" onclick="insertTag('template_product_sold', '{customer_name}')">{customer_name}</span>
                            <span class="variable-pill" onclick="insertTag('template_product_sold', '{order_id}')">{order_id}</span>
                            <span class="variable-pill" onclick="insertTag('template_product_sold', '{total_amount}')">{total_amount}</span>
                            <span class="variable-pill" onclick="insertTag('template_product_sold', '{store_name}')">{store_name}</span>
                        </div>
                    </div>

                    <!-- Event 2: Admin Expiry Warning (3 days before / on expiry) -->
                    <div class="border rounded-3 p-3 mb-4 bg-light">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold mb-1 text-danger">
                                    <i class="fas fa-hourglass-half me-1"></i> Admin Portal Expiration Alert
                                </h6>
                                <p class="text-muted small mb-0">
                                    Dispatches SMS reminder to the Admin when their subscription/portal is near expiry (sent via <strong>Super Admin's gateway</strong>).
                                </p>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="notify_admin_expiry" id="notify_admin_expiry" {{ old('notify_admin_expiry', $settings->notify_admin_expiry) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="notify_admin_expiry">Enable</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label" for="admin_expiry_days_before">Days before expiration to send reminder:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="admin_expiry_days_before" id="admin_expiry_days_before" min="1" max="30" value="{{ old('admin_expiry_days_before', $settings->admin_expiry_days_before ?: 3) }}">
                                    <span class="input-group-text">Day(s)</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="template_admin_expiry">Admin Expiry SMS Template:</label>
                            <textarea class="form-control" rows="2" name="template_admin_expiry" id="template_admin_expiry">{{ old('template_admin_expiry', $settings->template_admin_expiry ?: \App\Models\SmsSetting::defaultTemplate('admin_expiry')) }}</textarea>
                        </div>
                        <div class="small text-muted">
                            <span>Available Placeholders: </span>
                            <span class="variable-pill" onclick="insertTag('template_admin_expiry', '{admin_name}')">{admin_name}</span>
                            <span class="variable-pill" onclick="insertTag('template_admin_expiry', '{days_left}')">{days_left}</span>
                            <span class="variable-pill" onclick="insertTag('template_admin_expiry', '{expiry_date}')">{expiry_date}</span>
                            <span class="variable-pill" onclick="insertTag('template_admin_expiry', '{store_name}')">{store_name}</span>
                        </div>
                    </div>

                    <!-- Event 3: User Account Creation -->
                    <div class="border rounded-3 p-3 mb-4 bg-light">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold mb-1 text-success">
                                    <i class="fas fa-user-plus me-1"></i> New User / Customer Creation SMS
                                </h6>
                                <p class="text-muted small mb-0">Send a welcome / account confirmation SMS when a new user signs up or is registered.</p>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="notify_user_created" id="notify_user_created" {{ old('notify_user_created', $settings->notify_user_created) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="notify_user_created">Enable</label>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="template_user_created">Welcome SMS Template:</label>
                            <textarea class="form-control" rows="2" name="template_user_created" id="template_user_created">{{ old('template_user_created', $settings->template_user_created ?: \App\Models\SmsSetting::defaultTemplate('user_created')) }}</textarea>
                        </div>
                        <div class="small text-muted">
                            <span>Available Placeholders: </span>
                            <span class="variable-pill" onclick="insertTag('template_user_created', '{user_name}')">{user_name}</span>
                            <span class="variable-pill" onclick="insertTag('template_user_created', '{phone}')">{phone}</span>
                            <span class="variable-pill" onclick="insertTag('template_user_created', '{store_name}')">{store_name}</span>
                        </div>
                    </div>

                    <!-- Event 4: Order Status Update -->
                    <div class="border rounded-3 p-3 bg-light">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="fw-bold mb-1 text-info">
                                    <i class="fas fa-truck-fast me-1"></i> Order Status Update SMS
                                </h6>
                                <p class="text-muted small mb-0">Send SMS when order status changes (e.g. Processing, Shipped, Delivered).</p>
                            </div>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" role="switch" name="notify_order_status_change" id="notify_order_status_change" {{ old('notify_order_status_change', $settings->notify_order_status_change) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="notify_order_status_change">Enable</label>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="template_order_status">Status Change Template:</label>
                            <textarea class="form-control" rows="2" name="template_order_status" id="template_order_status">{{ old('template_order_status', $settings->template_order_status ?: \App\Models\SmsSetting::defaultTemplate('order_status')) }}</textarea>
                        </div>
                        <div class="small text-muted">
                            <span>Available Placeholders: </span>
                            <span class="variable-pill" onclick="insertTag('template_order_status', '{customer_name}')">{customer_name}</span>
                            <span class="variable-pill" onclick="insertTag('template_order_status', '{order_id}')">{order_id}</span>
                            <span class="variable-pill" onclick="insertTag('template_order_status', '{status}')">{status}</span>
                            <span class="variable-pill" onclick="insertTag('template_order_status', '{store_name}')">{store_name}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end mb-4">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                        <i class="fas fa-save me-2"></i> Save SMS Gateway & Settings
                    </button>
                </div>
            </form>

            <!-- Step 4: Live Test Connection Card -->
            <div class="premium-card">
                <div class="premium-card-header">
                    <div class="icon-box icon-box-success">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        <h5>Test SMS Gateway Connectivity</h5>
                        <small>Send a quick test message to verify that your configured API credentials are working.</small>
                    </div>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Gateway to Test</label>
                        <select class="form-select" id="test_gateway">
                            <option value="bulksmsbd">BulkSMSBD</option>
                            <option value="awaj">Awaj (Awaaz)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Recipient Phone Number</label>
                        <input type="text" class="form-control" id="test_number" placeholder="017xxxxxxxx">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Test Message</label>
                        <input type="text" class="form-control" id="test_message" value="Testing SMS Gateway connection from Thikana Platform.">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-success w-100 py-2 fw-bold" id="btn-send-test" onclick="sendTestSms()">
                            <i class="fas fa-paper-plane me-1"></i> Send Test
                        </button>
                    </div>
                </div>

                <div class="mt-3 d-none" id="test-result-box">
                    <div class="alert mb-0" id="test-result-alert"></div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Radio selection visual toggle
    document.querySelectorAll('input[name="default_gateway"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.gateway-option').forEach(el => el.classList.remove('selected'));
            this.closest('.gateway-option').classList.add('selected');
            document.getElementById('test_gateway').value = this.value;
        });
    });

    // Helper to insert tag into textareas
    function insertTag(textareaId, tag) {
        const textarea = document.getElementById(textareaId);
        if (!textarea) return;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        textarea.value = text.substring(0, start) + tag + text.substring(end);
        textarea.focus();
        textarea.selectionEnd = start + tag.length;
    }

    // AJAX Test SMS function
    function sendTestSms() {
        const btn = document.getElementById('btn-send-test');
        const gateway = document.getElementById('test_gateway').value;
        const testNumber = document.getElementById('test_number').value.trim();
        const testMessage = document.getElementById('test_message').value.trim();
        const resultBox = document.getElementById('test-result-box');
        const alertEl = document.getElementById('test-result-alert');

        if (!testNumber) {
            alert('Please enter a valid phone number to test.');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sending...';
        resultBox.classList.remove('d-none');
        alertEl.className = 'alert alert-info';
        alertEl.innerText = 'Connecting to ' + gateway + ' gateway...';

        const payload = {
            _token: '{{ csrf_token() }}',
            gateway: gateway,
            test_number: testNumber,
            test_message: testMessage,
            bulksmsbd_url: document.getElementById('bulksmsbd_url')?.value || '',
            bulksmsbd_api_key: document.getElementById('bulksmsbd_api_key')?.value || '',
            bulksmsbd_sender_id: document.getElementById('bulksmsbd_sender_id')?.value || '',
            awaj_url: document.getElementById('awaj_url')?.value || '',
            awaj_api_key: document.getElementById('awaj_api_key')?.value || '',
            awaj_sender_id: document.getElementById('awaj_sender_id')?.value || '',
            awaj_client_id: document.getElementById('awaj_client_id')?.value || '',
            awaj_secret_key: document.getElementById('awaj_secret_key')?.value || ''
        };

        fetch('{{ route("admin.sms-settings.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Send Test';

            if (data.success) {
                alertEl.className = 'alert alert-success';
                alertEl.innerHTML = '<strong><i class="fas fa-check-circle me-1"></i> Success:</strong> ' + data.message + 
                    (data.raw_response ? '<br><small class="text-muted">Response: ' + data.raw_response + '</small>' : '');
            } else {
                alertEl.className = 'alert alert-danger';
                alertEl.innerHTML = '<strong><i class="fas fa-exclamation-triangle me-1"></i> Error:</strong> ' + (data.message || 'Unknown error occurred.');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Send Test';
            alertEl.className = 'alert alert-danger';
            alertEl.innerHTML = '<strong><i class="fas fa-exclamation-triangle me-1"></i> Request failed:</strong> ' + err.message;
        });
    }
</script>
@endpush
