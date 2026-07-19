@extends('layouts.master')

@section('title', 'Telegram Alert System')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #telegram-settings-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
    }

    .premium-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.025);
        transition: all 0.3s ease;
    }

    .premium-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
    }

    .premium-card-header {
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 20px;
        margin-bottom: 24px;
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
    .icon-box-info { background-color: #e0e7ff; color: #4f46e5; }

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

    /* Form Styles */
    .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.875rem;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 10px 16px;
        font-size: 0.95rem;
        color: #0f172a;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.1);
        background-color: #ffffff;
    }

    .form-text.text-muted, .text-muted {
        font-size: 0.775rem;
        color: #64748b !important;
        margin-top: 6px;
    }

    /* Custom Switch Container */
    .switch-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.2s ease;
    }

    .switch-box:hover {
        border-color: #cbd5e1;
        background: #f1f5f9;
    }

    /* Custom Nav Tab Pills */
    .nav-pills-premium {
        background: #e2e8f0;
        padding: 6px;
        border-radius: 14px;
        gap: 4px;
        margin-bottom: 28px;
    }

    .nav-pills-premium .nav-link {
        border-radius: 10px;
        color: #475569;
        font-weight: 700;
        font-size: 0.95rem;
        padding: 14px 24px;
        transition: all 0.2s ease;
        border: none !important;
        background: transparent;
    }

    .nav-pills-premium .nav-link:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.5);
    }

    .nav-pills-premium .nav-link.active {
        background: #ffffff !important;
        color: #0284c7 !important;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    /* Guide Methods cards */
    .guide-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        transition: all 0.2s ease;
    }

    .guide-card:hover {
        border-color: #0284c7;
        transform: translateY(-2px);
    }

    .qr-container {
        display: inline-block;
        padding: 12px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
    }

    /* Alert / Placeholders box */
    .placeholders-box {
        background: #f0f9ff;
        border: 1px solid #e0f2fe;
        border-radius: 12px;
        padding: 24px;
    }

    /* Buttons override */
    .btn-premium {
        font-weight: 600;
        padding: 12px 24px;
        border-radius: 10px;
        border: none;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-premium:hover {
        transform: translateY(-1px);
    }

    .btn-premium-primary {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: white;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.2);
    }

    .btn-premium-primary:hover {
        box-shadow: 0 6px 20px rgba(2, 132, 199, 0.3);
    }

    .btn-premium-outline {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #334155;
    }

    .btn-premium-outline:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    /* Accordion Custom */
    .accordion-premium {
        border: none;
    }

    .accordion-premium .accordion-item {
        border: 1px solid #e2e8f0;
        border-radius: 12px !important;
        margin-bottom: 12px;
        overflow: hidden;
    }

    .accordion-premium .accordion-button {
        font-weight: 600;
        color: #334155;
        padding: 18px 24px;
        background: #f8fafc;
    }

    .accordion-premium .accordion-button:not(.collapsed) {
        background: #f0f9ff;
        color: #0284c7;
        box-shadow: none;
    }

    /* Main layouts */
    .content-header h1 {
        font-weight: 800;
        color: #0f172a;
        font-size: 1.6rem;
    }
</style>
@endsection

@section('content')
<div id="telegram-settings-page" class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h1 class="h3 mb-1 text-gray-800">
                        <i class="fab fa-telegram text-info me-2"></i>
                        Telegram Alert Dispatch
                    </h1>
                    <p class="text-muted mb-0">Configure instant Telegram automated dispatcher rules for merchant orders</p>
                </div>
                <div>
                    <button type="button" class="btn-premium btn-premium-outline" id="testTelegram">
                        <i class="fas fa-paper-plane"></i> Send Test Dispatch
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" style="border-radius:12px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Main Navigation Tabs -->
    <ul class="nav nav-pills nav-fill nav-pills-premium" id="mainSettingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="core-tab" data-bs-toggle="tab" data-bs-target="#core-pane" type="button" role="tab">
                <i class="fas fa-plug me-2"></i> Connection & Triggers
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="template-main-tab" data-bs-toggle="tab" data-bs-target="#template-pane" type="button" role="tab">
                <i class="fas fa-envelope-open-text me-2"></i> Message Blueprint
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="setup-main-tab" data-bs-toggle="tab" data-bs-target="#setup-pane" type="button" role="tab">
                <i class="fas fa-info-circle me-2"></i> Bot Setup Guide
            </button>
        </li>
    </ul>

    <form action="{{ route('admin.telegram-settings.update') }}" method="POST">
        @csrf

        <div class="tab-content" id="mainSettingsTabsContent">
            <!-- Pane 1: Connection & Triggers -->
            <div class="tab-pane fade show active" id="core-pane" role="tabpanel">
                <!-- Basic Configuration -->
                <div class="premium-card">
                    <div class="premium-card-header">
                        <div class="icon-box icon-box-primary">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div>
                            <h5>Core Connectivity</h5>
                            <small>Enable Telegram relay mechanism and configure bot authorizations</small>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="custom-control custom-switch switch-box">
                            <input class="custom-control-input" type="checkbox" id="enabled" 
                                   name="enabled" value="1"
                                   {{ $telegramSettings->enabled ? 'checked' : '' }}>
                            <label class="custom-control-label fw-bold text-dark cursor-pointer" for="enabled">
                                Activate Telegram Alert Dispatcher
                            </label>
                            <div class="text-muted mt-1">Master relay toggle. If disabled, all outbound notification queues are paused.</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="bot_token" class="form-label">
                                <i class="fas fa-key text-primary me-1"></i> Telegram Bot Token
                            </label>
                            <input type="text" class="form-control" id="bot_token" name="bot_token" 
                                   value="{{ old('bot_token', $telegramSettings->bot_token) }}"
                                   placeholder="123456789:ABCdefGHIjklMNOpqrsTUVwxyz">
                            <small class="form-text text-muted">Generate yours by initiating bot handshake with <a href="https://t.me/BotFather" target="_blank" class="text-decoration-none">@BotFather</a></small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="chat_id" class="form-label">
                                <i class="fas fa-comments text-success me-1"></i> Target Chat ID / Channel ID
                            </label>
                            <input type="text" class="form-control" id="chat_id" name="chat_id" 
                                   value="{{ old('chat_id', $telegramSettings->chat_id) }}"
                                   placeholder="-1001234567890">
                            <small class="form-text text-muted">Numeric identifier of personal chat session or group channel hook</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="timeout" class="form-label">
                                <i class="fas fa-hourglass-half text-warning me-1"></i> API Timeout threshold (seconds)
                            </label>
                            <input type="number" class="form-control" id="timeout" name="timeout" 
                                   value="{{ old('timeout', $telegramSettings->timeout) }}"
                                   min="1" max="30">
                            <small class="form-text text-muted">Connection timeout safeguard. Set to 3 seconds for optimized relaying.</small>
                        </div>
                    </div>
                </div>

                <!-- Notification Triggers -->
                <div class="premium-card">
                    <div class="premium-card-header">
                        <div class="icon-box icon-box-success">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div>
                            <h5>Active Event Triggers</h5>
                            <small>Specify exactly which store events should invoke automated Telegram dispatches</small>
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="switch-box d-flex align-items-start gap-3 h-100">
                                <div class="custom-control custom-switch pt-1">
                                    <input class="custom-control-input" type="checkbox" id="notify_new_order" 
                                           name="notify_new_order" value="1"
                                           {{ $telegramSettings->notify_new_order ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="notify_new_order"></label>
                                </div>
                                <div>
                                    <strong class="text-dark d-block mb-1">Standard Shop Orders</strong>
                                    <span class="text-muted">Generate instant alerts for any standard customer purchases.</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="switch-box d-flex align-items-start gap-3 h-100">
                                <div class="custom-control custom-switch pt-1">
                                    <input class="custom-control-input" type="checkbox" id="notify_landing_page_order" 
                                           name="notify_landing_page_order" value="1"
                                           {{ $telegramSettings->notify_landing_page_order ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="notify_landing_page_order"></label>
                                </div>
                                <div>
                                    <strong class="text-dark d-block mb-1">Landing Page Submissions</strong>
                                    <span class="text-muted">Dispatch alerts when lead captures occur on promotion layouts.</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="switch-box d-flex align-items-start gap-3 h-100">
                                <div class="custom-control custom-switch pt-1">
                                    <input class="custom-control-input" type="checkbox" id="notify_cart_order" 
                                           name="notify_cart_order" value="1"
                                           {{ $telegramSettings->notify_cart_order ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="notify_cart_order"></label>
                                </div>
                                <div>
                                    <strong class="text-dark d-block mb-1">Shopping Cart Orders</strong>
                                    <span class="text-muted">Alert when customers successfully finalize cart-driven checkouts.</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="switch-box d-flex align-items-start gap-3 h-100">
                                <div class="custom-control custom-switch pt-1">
                                    <input class="custom-control-input" type="checkbox" id="notify_order_status_change" 
                                           name="notify_order_status_change" value="1"
                                           {{ $telegramSettings->notify_order_status_change ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="notify_order_status_change"></label>
                                </div>
                                <div>
                                    <strong class="text-dark d-block mb-1">Order Status Transitions</strong>
                                    <span class="text-muted">Notify team when merchant adjusts status parameters (Pending -> Processing, etc).</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pane 2: Message Template -->
            <div class="tab-pane fade" id="template-pane" role="tabpanel">
                <div class="premium-card">
                    <div class="premium-card-header">
                        <div class="icon-box icon-box-warning">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <h5>Outbound Template Layout</h5>
                            <small>Design a personalized structure for your dispatch alerts using markdown formats</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="form-group mb-0">
                                <label for="order_message_template" class="form-label">
                                    <i class="fas fa-comment-dots text-primary me-1"></i> Message Body Blueprint
                                </label>
                                <textarea class="form-control font-monospace" id="order_message_template" name="order_message_template" 
                                          rows="11" style="background:#0f172a; color:#38bdf8; border-color:#1e293b; line-height:1.6;" 
                                          placeholder="Leave blank to invoke fallback blueprint">{{ old('order_message_template', $telegramSettings->order_message_template) }}</textarea>
                                <small class="form-text text-muted mt-2">Optional setup. Standard HTML structure and basic tag entities are accepted.</small>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="placeholders-box h-100">
                                <h6 class="font-weight-bold text-dark mb-3">
                                    <i class="fas fa-info-circle text-primary me-1"></i> Available Data Placeholders
                                </h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <ul class="mb-0 small ps-3">
                                            <li class="mb-1"><code>{order_id}</code> - Transaction ID</li>
                                            <li class="mb-1"><code>{customer_name}</code> - Buyer full name</li>
                                            <li class="mb-1"><code>{customer_phone}</code> - Contact mobile</li>
                                            <li class="mb-1"><code>{customer_address}</code> - Shipping location</li>
                                            <li class="mb-1"><code>{product_title}</code> - Purchased item</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="mb-0 small ps-3">
                                            <li class="mb-1"><code>{quantity}</code> - Unit count</li>
                                            <li class="mb-1"><code>{price}</code> - Unit price tag</li>
                                            <li class="mb-1"><code>{shipping}</code> - Delivery tariff</li>
                                            <li class="mb-1"><code>{total}</code> - Invoice aggregate</li>
                                            <li class="mb-1"><code>{payment_method}</code> - Selected billing</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <span class="d-block small text-muted font-weight-bold mb-2">DEFAULT RELAY BLUEPRINT:</span>
                                    <pre class="mb-0 small bg-dark p-3 rounded text-light" style="border: 1px solid #1e293b;"><code>🚀 &lt;b&gt;New {order_source} Order&lt;/b&gt;
🧾 Order ID: &lt;b&gt;{order_id}&lt;/b&gt;
👤 {customer_name}
📞 {customer_phone}
📦 Product: &lt;b&gt;{product_title}&lt;/b&gt;
🔢 Qty: {quantity}
🚚 Shipping: {shipping}
💰 Total: {total}
💳 Payment: &lt;b&gt;{payment_method}&lt;/b&gt;
🔗 &lt;a href="{order_url}"&gt;View Order&lt;/a&gt;</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pane 3: Setup Guide -->
            <div class="tab-pane fade" id="setup-pane" role="tabpanel">
                <div class="premium-card">
                    <div class="premium-card-header">
                        <div class="icon-box icon-box-info">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <div>
                            <h5>Bot Initialization Walkthrough</h5>
                            <small>Select from our three interactive pathways to complete bot creation and chat handshakes</small>
                        </div>
                    </div>

                    <!-- Method Selection Tabs -->
                    <ul class="nav nav-pills nav-fill nav-pills-premium mb-4" id="setupTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="oneclick-tab" data-bs-toggle="tab" 
                                    data-bs-target="#oneclick" type="button" role="tab">
                                <i class="fas fa-mouse-pointer me-2"></i> One-Click Link Setup
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="qrcode-tab" data-bs-toggle="tab" 
                                    data-bs-target="#qrcode" type="button" role="tab">
                                <i class="fas fa-qrcode me-2"></i> QR Code Scanning
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="manual-tab" data-bs-toggle="tab" 
                                    data-bs-target="#manual" type="button" role="tab">
                                <i class="fas fa-book me-2"></i> Custom Manual Flow
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="setupTabsContent">
                        <!-- One-Click Method -->
                        <div class="tab-pane fade show active" id="oneclick" role="tabpanel">
                            <div class="alert alert-info border-0 mb-4" style="border-radius:12px; background:#f0f9ff; color:#0369a1;">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Quick Start Action:</strong> Click the launcher hooks below to engage Telegram APIs directly on your current device.
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <div class="card guide-card h-100 p-4 text-center">
                                        <div class="mb-3 text-primary">
                                            <i class="fas fa-robot fa-3x"></i>
                                        </div>
                                        <h5 class="font-weight-bold mb-2">1. Initialize Bot Node</h5>
                                        <p class="text-muted small mb-3">Spawn a new bot handler within Telegram instantly.</p>
                                        <a href="https://t.me/BotFather?start=newbot" 
                                           target="_blank" 
                                           class="btn-premium btn-premium-primary justify-content-center w-100">
                                            <i class="fab fa-telegram"></i> Launch BotFather API
                                        </a>
                                        <hr class="my-3" style="border-top:1px dashed #e2e8f0;">
                                        <div class="text-start small text-muted">
                                            <strong>Instruction sequence:</strong><br>
                                            1. Press <strong>"START"</strong> inside chat session<br>
                                            2. Send: <code>/newbot</code> command<br>
                                            3. Assign custom client display names<br>
                                            4. Grab the generated credential token
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="card guide-card h-100 p-4 text-center">
                                        <div class="mb-3 text-success">
                                            <i class="fas fa-id-card fa-3x"></i>
                                        </div>
                                        <h5 class="font-weight-bold mb-2">2. Extract Chat ID Identifier</h5>
                                        <p class="text-muted small mb-3">Retrieve your primary Telegram user/chat ID payload.</p>
                                        <a href="https://t.me/userinfobot?start=start" 
                                           target="_blank" 
                                           class="btn-premium btn-premium-primary justify-content-center w-100" style="background:linear-gradient(135deg, #10b981 0%, #047857 100%); box-shadow:0 4px 14px rgba(16, 185, 129, 0.2);">
                                            <i class="fab fa-telegram"></i> Query Chat ID Bot
                                        </a>
                                        <hr class="my-3" style="border-top:1px dashed #e2e8f0;">
                                        <div class="text-start small text-muted">
                                            <strong>Instruction sequence:</strong><br>
                                            1. Tap <strong>"START"</strong> to wake up interface bot<br>
                                            2. Capture the displayed target numeric value<br>
                                            3. Paste the numeric code directly in Core Connectivity above
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- QR Code Method -->
                        <div class="tab-pane fade" id="qrcode" role="tabpanel">
                            <div class="alert alert-info border-0 mb-4" style="border-radius:12px; background:#f0f9ff; color:#0369a1;">
                                <i class="fas fa-mobile-alt me-2"></i>
                                <strong>Mobile Companion Routing:</strong> Scan the generated graphics using your mobile phone camera application to handshake bots.
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card guide-card h-100 text-center p-4">
                                        <h5 class="font-weight-bold mb-3">Scan 1: Launch BotFather</h5>
                                        <div class="qr-container mb-3">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://t.me/BotFather" 
                                                 alt="BotFather QR Code"
                                                 class="img-fluid"
                                                 style="max-width: 180px;">
                                        </div>
                                        <div class="text-start small text-muted bg-light p-3 rounded" style="border-radius: 10px;">
                                            <strong>Steps:</strong> Scan, click <strong>"START"</strong>, send <code>/newbot</code>, configure names, and keep your token handy.
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card guide-card h-100 text-center p-4">
                                        <h5 class="font-weight-bold mb-3">Scan 2: Lookup Client ID</h5>
                                        <div class="qr-container mb-3">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://t.me/userinfobot" 
                                                 alt="UserInfoBot QR Code"
                                                 class="img-fluid"
                                                 style="max-width: 180px;">
                                        </div>
                                        <div class="text-start small text-muted bg-light p-3 rounded" style="border-radius: 10px;">
                                            <strong>Steps:</strong> Scan, click <strong>"START"</strong>, and retrieve your profile Chat ID sequence.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manual Method -->
                        <div class="tab-pane fade" id="manual" role="tabpanel">
                            <div class="accordion accordion-premium" id="manualSetupAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                                data-bs-target="#collapseOne">
                                            <i class="fas fa-robot text-primary me-2"></i>
                                            Step 1: Spawn Bot Nodes manually
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show" 
                                         data-bs-parent="#manualSetupAccordion">
                                        <div class="accordion-body text-dark">
                                            <ol class="mb-0 ps-3">
                                                <li class="mb-2">Access the search window of your Telegram app client.</li>
                                                <li class="mb-2">Lookup user identity hook: <code>@BotFather</code></li>
                                                <li class="mb-2">Initiate handshake: Click <strong>"START"</strong>.</li>
                                                <li class="mb-2">Issue dispatch code: Send <code>/newbot</code> text.</li>
                                                <li class="mb-2">Enter custom titles for bot identification (e.g. <code>Store Notifications</code>).</li>
                                                <li class="mb-2">Assign system handles. Must conclude in 'bot' (e.g. <code>mystore_alerts_bot</code>).</li>
                                                <li class="mb-2">Record the authorization key string provided by BotFather (looks like <code>1234567:ABCdef...</code>).</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                                data-bs-target="#collapseTwo">
                                            <i class="fas fa-id-card text-success me-2"></i>
                                            Step 2: Grab Target Chat ID identifiers
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse" 
                                         data-bs-parent="#manualSetupAccordion">
                                        <div class="accordion-body text-dark">
                                            <h6 class="font-weight-bold mb-2">Direct User Dispatches:</h6>
                                            <ol class="mb-3 ps-3">
                                                <li class="mb-1">Navigate to query handler bot: <code>@userinfobot</code></li>
                                                <li class="mb-1">Click <strong>"START"</strong> to dump settings schema.</li>
                                                <li class="mb-1">Duplicate the numeric ID string displayed on screen.</li>
                                            </ol>
                                            <h6 class="font-weight-bold mb-2">Shared Channels / Community Groups:</h6>
                                            <ol class="mb-0 ps-3">
                                                <li class="mb-1">Add your newly created bot to the target channel.</li>
                                                <li class="mb-1">Invite lookup helper: <code>@getidsbot</code> into group space.</li>
                                                <li class="mb-1">Record the group ID prefix string (begins with negative symbol, e.g. <code>-9876543</code>).</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                                data-bs-target="#collapseThree">
                                            <i class="fas fa-play text-info me-2"></i>
                                            Step 3: Bot handshake activation
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse" 
                                         data-bs-parent="#manualSetupAccordion">
                                        <div class="accordion-body text-dark">
                                            <p class="mb-0">
                                                Open a chat directly with your newly registered bot (search by the username you specified) and click <strong>"START"</strong>. 
                                                This grants structural permission for the bot to dispatch system notifications to your account feed.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button (Always visible at the bottom) -->
        <div class="row mt-4">
            <div class="col-12 text-end">
                <button type="submit" class="btn-premium btn-premium-primary px-5">
                    <i class="fas fa-save"></i> Save Notification Parameters
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const testBtn = document.getElementById('testTelegram');
    if (testBtn) {
        testBtn.addEventListener('click', function() {
            const btn = this;
            const originalHtml = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Testing Relay...';
            
            fetch('{{ route('admin.telegram-settings.test') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message);
                } else {
                    alert('❌ ' + data.message);
                }
            })
            .catch(error => {
                alert('❌ Error: ' + error.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
        });
    }
});
</script>
@endsection
