@extends('layouts.master')

@section('title', 'Telegram Settings')

@section('content')
<div class="container-fluid px-2">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h3 mb-1 text-gray-800">
                        <i class="fab fa-telegram text-info me-2"></i>
                        Telegram Notification Settings
                    </h1>
                    <p class="text-muted mb-0">Configure Telegram bot for order notifications</p>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-info" id="testTelegram">
                        <i class="fas fa-paper-plane me-1"></i> Test Connection
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.telegram-settings.update') }}" method="POST">
        @csrf

        <!-- Basic Configuration -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="p-2 me-3">
                                <i class="fas fa-cog fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Basic Configuration</h5>
                                <small>Enable and configure your Telegram bot</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="enabled" 
                                       name="enabled" value="1"
                                       {{ $telegramSettings->enabled ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="enabled">
                                    Enable Telegram Notifications
                                </label>
                            </div>
                            <small class="text-muted">Master switch to enable/disable all Telegram notifications</small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="bot_token" class="form-label fw-semibold">
                                    <i class="fas fa-key text-primary me-1"></i> Bot Token
                                </label>
                                <input type="text" class="form-control" id="bot_token" name="bot_token" 
                                       value="{{ old('bot_token', $telegramSettings->bot_token) }}"
                                       placeholder="123456789:ABCdefGHIjklMNOpqrsTUVwxyz">
                                <small class="text-muted">Get your bot token from <a href="https://t.me/BotFather" target="_blank">@BotFather</a></small>
                            </div>

                            <div class="col-md-6">
                                <label for="chat_id" class="form-label fw-semibold">
                                    <i class="fas fa-comments text-success me-1"></i> Chat ID
                                </label>
                                <input type="text" class="form-control" id="chat_id" name="chat_id" 
                                       value="{{ old('chat_id', $telegramSettings->chat_id) }}"
                                       placeholder="-1001234567890">
                                <small class="text-muted">Your chat/channel ID where notifications will be sent</small>
                            </div>

                            <div class="col-md-6">
                                <label for="timeout" class="form-label fw-semibold">
                                    <i class="fas fa-hourglass-half text-warning me-1"></i> HTTP Timeout (seconds)
                                </label>
                                <input type="number" class="form-control" id="timeout" name="timeout" 
                                       value="{{ old('timeout', $telegramSettings->timeout) }}"
                                       min="1" max="30">
                                <small class="text-muted">Timeout for API requests (recommended: 3 seconds for shared hosting)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Triggers -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="p-2 me-3">
                                <i class="fas fa-bell fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Notification Triggers</h5>
                                <small>Choose when to send Telegram notifications</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="notify_new_order" 
                                               name="notify_new_order" value="1"
                                               {{ $telegramSettings->notify_new_order ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="notify_new_order">
                                            All New Orders
                                        </label>
                                    </div>
                                    <small class="text-muted">Notify for all new orders from any source</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="notify_landing_page_order" 
                                               name="notify_landing_page_order" value="1"
                                               {{ $telegramSettings->notify_landing_page_order ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="notify_landing_page_order">
                                            Landing Page Orders
                                        </label>
                                    </div>
                                    <small class="text-muted">Notify for orders from landing pages</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="notify_cart_order" 
                                               name="notify_cart_order" value="1"
                                               {{ $telegramSettings->notify_cart_order ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="notify_cart_order">
                                            Cart Orders
                                        </label>
                                    </div>
                                    <small class="text-muted">Notify for orders from shopping cart</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="notify_order_status_change" 
                                               name="notify_order_status_change" value="1"
                                               {{ $telegramSettings->notify_order_status_change ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="notify_order_status_change">
                                            Order Status Changes
                                        </label>
                                    </div>
                                    <small class="text-muted">Notify when order status is updated</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Template -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-dark border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="p-2 me-3">
                                <i class="fas fa-edit fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Message Template (Optional)</h5>
                                <small>Customize the notification message format</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="order_message_template" class="form-label fw-semibold">
                                <i class="fas fa-comment-dots text-primary me-1"></i> Order Message Template
                            </label>
                            <textarea class="form-control font-monospace" id="order_message_template" name="order_message_template" 
                                      rows="10" placeholder="Leave empty to use default template">{{ old('order_message_template', $telegramSettings->order_message_template) }}</textarea>
                            <small class="text-muted d-block mt-2">Leave empty to use the default template. HTML formatting supported.</small>
                        </div>

                        <div class="alert alert-info mb-0">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-info-circle me-1"></i> Available Placeholders
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li><code>{order_id}</code> - Order ID</li>
                                        <li><code>{customer_name}</code> - Customer name</li>
                                        <li><code>{customer_phone}</code> - Phone number</li>
                                        <li><code>{customer_address}</code> - Delivery address</li>
                                        <li><code>{product_title}</code> - Product name</li>
                                        <li><code>{quantity}</code> - Order quantity</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li><code>{price}</code> - Product price</li>
                                        <li><code>{shipping}</code> - Shipping cost</li>
                                        <li><code>{total}</code> - Total amount</li>
                                        <li><code>{payment_method}</code> - Payment method</li>
                                        <li><code>{order_url}</code> - Order details link</li>
                                        <li><code>{order_source}</code> - Order source (Landing Page/Cart)</li>
                                    </ul>
                                </div>
                            </div>
                            <hr class="my-2">
                            <p class="mb-2 small"><strong>Default Template:</strong></p>
                            <pre class="mb-0 small bg-light p-2 rounded"><code>🚀 &lt;b&gt;New {order_source} Order&lt;/b&gt;
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

        <!-- Setup Guide - 3 Ways -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white border-bottom">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-rocket me-2"></i>
                                    Quick Setup Guide - Choose Your Method
                                </h5>
                                <small>Select the easiest way for you to set up Telegram notifications</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Method Selection Tabs -->
                        <ul class="nav nav-pills nav-fill mb-4" id="setupTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="oneclick-tab" data-bs-toggle="tab" 
                                        data-bs-target="#oneclick" type="button" role="tab">
                                    <i class="fas fa-mouse-pointer fa-lg mb-2 d-block"></i>
                                    <strong>One-Click Setup</strong>
                                    <small class="d-block">Easiest & Fastest</small>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="qrcode-tab" data-bs-toggle="tab" 
                                        data-bs-target="#qrcode" type="button" role="tab">
                                    <i class="fas fa-qrcode fa-lg mb-2 d-block"></i>
                                    <strong>QR Code</strong>
                                    <small class="d-block">Scan with Phone</small>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="manual-tab" data-bs-toggle="tab" 
                                        data-bs-target="#manual" type="button" role="tab">
                                    <i class="fas fa-book fa-lg mb-2 d-block"></i>
                                    <strong>Manual Setup</strong>
                                    <small class="d-block">Step-by-Step</small>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="setupTabsContent">
                            <!-- One-Click Method -->
                            <div class="tab-pane fade show active" id="oneclick" role="tabpanel">
                                <div class="alert alert-success border-0 mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Recommended!</strong> Click the buttons below and follow simple instructions.
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card border-primary h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="mb-3">
                                                    <i class="fas fa-robot fa-3x text-primary"></i>
                                                </div>
                                                <h5 class="card-title">Step 1: Create Bot</h5>
                                                <p class="text-muted small mb-3">
                                                    Opens Telegram to create your bot instantly
                                                </p>
                                                <a href="https://t.me/BotFather?start=newbot" 
                                                   target="_blank" 
                                                   class="btn btn-primary btn-lg w-100">
                                                    <i class="fab fa-telegram me-2"></i>
                                                    Create Bot Now
                                                </a>
                                                <hr class="my-3">
                                                <small class="text-muted">
                                                    <strong>After opening:</strong><br>
                                                    1. Click "START"<br>
                                                    2. Send: <code>/newbot</code><br>
                                                    3. Follow prompts<br>
                                                    4. Copy your token
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card border-success h-100">
                                            <div class="card-body text-center p-4">
                                                <div class="mb-3">
                                                    <i class="fas fa-id-card fa-3x text-success"></i>
                                                </div>
                                                <h5 class="card-title">Step 2: Get Chat ID</h5>
                                                <p class="text-muted small mb-3">
                                                    Instantly get your Telegram Chat ID
                                                </p>
                                                <a href="https://t.me/userinfobot?start=start" 
                                                   target="_blank" 
                                                   class="btn btn-success btn-lg w-100">
                                                    <i class="fab fa-telegram me-2"></i>
                                                    Get Chat ID
                                                </a>
                                                <hr class="my-3">
                                                <small class="text-muted">
                                                    <strong>After opening:</strong><br>
                                                    1. Click "START"<br>
                                                    2. Copy the number shown<br>
                                                    3. Paste in Chat ID field above<br>
                                                    <br>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info border-0 mt-4 mb-0">
                                    <i class="fas fa-lightbulb me-2"></i>
                                    <strong>Don't forget!</strong> After getting your token and Chat ID:
                                    <ol class="mb-0 mt-2">
                                        <li>Paste them in the fields above</li>
                                        <li>Click "Test Connection" to verify</li>
                                        <li>Save settings</li>
                                    </ol>
                                </div>
                            </div>

                            <!-- QR Code Method -->
                            <div class="tab-pane fade" id="qrcode" role="tabpanel">
                                <div class="alert alert-info border-0 mb-4">
                                    <i class="fas fa-mobile-alt me-2"></i>
                                    <strong>Perfect for mobile users!</strong> Scan QR codes with your phone's camera.
                                </div>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="card h-100 text-center p-4">
                                            <h5 class="mb-3">
                                                <i class="fas fa-robot text-primary me-2"></i>
                                                Step 1: Create Bot
                                            </h5>
                                            <div class="qr-code-container mb-3 p-3 bg-light rounded">
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://t.me/BotFather" 
                                                     alt="BotFather QR Code"
                                                     class="img-fluid"
                                                     style="max-width: 200px;">
                                            </div>
                                            <p class="small text-muted mb-2">
                                                <strong>Scan to open BotFather</strong>
                                            </p>
                                            <div class="text-start small text-muted bg-light p-3 rounded">
                                                After scanning:<br>
                                                1️⃣ Send: <code>/newbot</code><br>
                                                2️⃣ Enter bot name<br>
                                                3️⃣ Enter bot username<br>
                                                4️⃣ Copy the token
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card h-100 text-center p-4">
                                            <h5 class="mb-3">
                                                <i class="fas fa-id-card text-success me-2"></i>
                                                Step 2: Get Chat ID
                                            </h5>
                                            <div class="qr-code-container mb-3 p-3 bg-light rounded">
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://t.me/userinfobot" 
                                                     alt="UserInfoBot QR Code"
                                                     class="img-fluid"
                                                     style="max-width: 200px;">
                                            </div>
                                            <p class="small text-muted mb-2">
                                                <strong>Scan to open UserInfoBot</strong>
                                            </p>
                                            <div class="text-start small text-muted bg-light p-3 rounded">
                                                After scanning:<br>
                                                1️⃣ Click "START"<br>
                                                2️⃣ Your Chat ID will appear<br>
                                                3️⃣ Copy the number<br>
                                                4️⃣ Paste above
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-warning border-0 mt-4 mb-0">
                                    <i class="fas fa-camera me-2"></i>
                                    <strong>How to scan QR codes:</strong> Open your phone's camera app and point it at the QR code. 
                                    A notification will appear to open the link in Telegram.
                                </div>
                            </div>

                            <!-- Manual Method -->
                            <div class="tab-pane fade" id="manual" role="tabpanel">
                                <div class="alert alert-secondary border-0 mb-4">
                                    <i class="fas fa-graduation-cap me-2"></i>
                                    <strong>Detailed instructions</strong> for those who prefer step-by-step guidance.
                                </div>

                                <div class="accordion" id="manualSetupAccordion">
                                    <!-- Step 1 -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                                    data-bs-target="#collapseOne">
                                                <i class="fas fa-robot text-primary me-2"></i>
                                                <strong>Step 1: Create Your Telegram Bot</strong>
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show" 
                                             data-bs-parent="#manualSetupAccordion">
                                            <div class="accordion-body">
                                                <ol class="mb-3">
                                                    <li class="mb-2">Open Telegram app on your phone or computer</li>
                                                    <li class="mb-2">
                                                        In the search bar, type: <code>@BotFather</code>
                                                        <br><small class="text-muted">Or click: 
                                                        <a href="https://t.me/BotFather" target="_blank">@BotFather</a></small>
                                                    </li>
                                                    <li class="mb-2">Click the "START" button</li>
                                                    <li class="mb-2">Send this message: <code>/newbot</code></li>
                                                    <li class="mb-2">BotFather will ask for a name. Enter something like: 
                                                        <code>My Shop Notifications</code>
                                                    </li>
                                                    <li class="mb-2">Then it will ask for a username (must end with 'bot'). 
                                                        Enter: <code>myshop_notify_bot</code>
                                                    </li>
                                                    <li class="mb-2">
                                                        <strong>Important!</strong> BotFather will send you a token that looks like:<br>
                                                        <code class="text-danger">123456789:ABCdefGHIjklMNOpqrsTUVwxyz</code><br>
                                                        <strong>Copy this entire token!</strong>
                                                    </li>
                                                </ol>
                                                <div class="alert alert-success border-0 mb-0">
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    Paste the token in the <strong>"Bot Token"</strong> field above
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 2 -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                                    data-bs-target="#collapseTwo">
                                                <i class="fas fa-id-card text-success me-2"></i>
                                                <strong>Step 2: Get Your Chat ID</strong>
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse" 
                                             data-bs-parent="#manualSetupAccordion">
                                            <div class="accordion-body">
                                                <h6 class="mb-3">For Personal Notifications:</h6>
                                                <ol class="mb-3">
                                                    <li class="mb-2">In Telegram, search for: <code>@userinfobot</code>
                                                        <br><small class="text-muted">Or click: 
                                                        <a href="https://t.me/userinfobot" target="_blank">@userinfobot</a></small>
                                                    </li>
                                                    <li class="mb-2">Click "START"</li>
                                                    <li class="mb-2">The bot will display your ID, something like: 
                                                        <code>123456789</code>
                                                    </li>
                                                    <li class="mb-2"><strong>Copy this number!</strong></li>
                                                </ol>

                                                <hr>

                                                <h6 class="mb-3">For Group/Channel Notifications:</h6>
                                                <ol class="mb-3">
                                                    <li class="mb-2">Add your bot to the group/channel</li>
                                                    <li class="mb-2">Search for: <code>@getidsbot</code>
                                                        <br><small class="text-muted">Or click: 
                                                        <a href="https://t.me/getidsbot" target="_blank">@getidsbot</a></small>
                                                    </li>
                                                    <li class="mb-2">Add this bot to your group</li>
                                                    <li class="mb-2">It will show the group ID (starts with minus, like: 
                                                        <code>-987654321</code>)
                                                    </li>
                                                    <li class="mb-2"><strong>Copy this number (with the minus sign)!</strong></li>
                                                </ol>

                                                <div class="alert alert-success border-0 mb-0">
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    Paste the Chat ID in the <strong>"Chat ID"</strong> field above
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 3 -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                                    data-bs-target="#collapseThree">
                                                <i class="fas fa-comment text-info me-2"></i>
                                                <strong>Step 3: Start Your Bot</strong>
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse" 
                                             data-bs-parent="#manualSetupAccordion">
                                            <div class="accordion-body">
                                                <ol class="mb-3">
                                                    <li class="mb-2">Go back to your bot (search for the username you created)</li>
                                                    <li class="mb-2">Click the "START" button in the chat</li>
                                                    <li class="mb-2">This allows your bot to send you messages</li>
                                                </ol>
                                                <div class="alert alert-warning border-0">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    <strong>Important!</strong> You must start the bot before it can send you messages.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 4 -->
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingFour">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                                    data-bs-target="#collapseFour">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <strong>Step 4: Test & Save</strong>
                                            </button>
                                        </h2>
                                        <div id="collapseFour" class="accordion-collapse collapse" 
                                             data-bs-parent="#manualSetupAccordion">
                                            <div class="accordion-body">
                                                <ol class="mb-3">
                                                    <li class="mb-2">Enable notifications using the toggle at the top</li>
                                                    <li class="mb-2">Click the <strong>"Test Connection"</strong> button</li>
                                                    <li class="mb-2">Check your Telegram - you should receive a test message</li>
                                                    <li class="mb-2">If it works, click <strong>"Save Settings"</strong></li>
                                                </ol>
                                                <div class="alert alert-success border-0 mb-0">
                                                    <i class="fas fa-trophy me-2"></i>
                                                    <strong>Congratulations!</strong> You'll now receive order notifications on Telegram!
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Save Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('testTelegram').addEventListener('click', function() {
    const btn = this;
    const originalHtml = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sending...';
    
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
</script>
@endpush
@endsection

