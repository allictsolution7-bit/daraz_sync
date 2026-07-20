@extends('layouts.master')

@section('styles')
<style>
    :root {
        --primary: #197A94;
        --primary-gradient: linear-gradient(135deg, #197A94 0%, #0d5c70 100%);
        --primary-hover: #135d71;
        --primary-light: rgba(25, 122, 148, 0.08);
        --success: #10b981;
        --info: #06b6d4;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark-slate: #1e293b;
        --text-main: #334155;
        --text-muted: #64748b;
        --bg-glass: rgba(255, 255, 255, 0.95);
        --border-glass: rgba(226, 232, 240, 0.9);
        --shadow-premium: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Page container styling */
    .container-fluid {
        padding: 30px;
        background-color: #f8fafc;
        min-height: 100vh;
    }

    /* Header card */
    .card.shadow.mb-4 {
        border: 1px solid var(--border-glass);
        border-radius: 16px;
        box-shadow: var(--shadow-premium) !important;
        background: var(--bg-glass);
        margin-bottom: 30px !important;
        overflow: hidden;
    }

    .card-header {
        background: transparent !important;
        border-bottom: 1px solid var(--border-glass) !important;
        padding: 20px 25px !important;
    }

    .card-header h6 {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark-slate) !important;
    }

    /* Save Button */
    .save-button {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 4px 15px rgba(25, 122, 148, 0.3);
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .save-button:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(25, 122, 148, 0.4);
        color: white;
        text-decoration: none;
    }

    .save-button:active {
        transform: translateY(0);
    }

    /* Payment Gateways Grid Layout */
    .payment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 30px;
        margin-top: 10px;
    }

    .gateway-card {
        background: var(--bg-glass);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 30px;
        box-shadow: var(--shadow-premium);
        transition: var(--transition-smooth);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .gateway-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.08);
        border-color: rgba(25, 122, 148, 0.3);
    }

    .gateway-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        border-bottom: 1.5px solid #f1f5f9;
        padding-bottom: 18px;
    }

    .gateway-title {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 18px;
        font-weight: 700;
        color: var(--dark-slate);
    }

    .gateway-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* Custom brand colors for icons */
    .icon-cod { background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%); }
    .icon-bkash { background: linear-gradient(135deg, #d81b60 0%, #ec4899 100%); }
    .icon-nagad { background: linear-gradient(135deg, #ea580c 0%, #f97316 100%); }
    .icon-rocket { background: linear-gradient(135deg, #6d28d9 0%, #8b5cf6 100%); }

    /* Gateway body and inputs */
    .gateway-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-slate);
        font-size: 14px;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 14px;
        color: var(--text-main);
        background-color: #ffffff;
        transition: var(--transition-smooth);
        width: 100%;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(25, 122, 148, 0.15);
    }

    .form-text.text-muted {
        font-size: 12px;
        color: var(--text-muted) !important;
        margin-top: 6px;
        line-height: 1.5;
    }

    /* Premium iOS Switch Toggle */
    .switch-toggle {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 24px;
    }

    .switch-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: .3s;
        border-radius: 24px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    input:checked + .slider {
        background-color: var(--primary);
    }

    input:checked + .slider:before {
        transform: translateX(24px);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 15px;
        }

        .card-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start !important;
        }

        .save-button {
            width: 100%;
            justify-content: center;
        }

        .payment-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Payment Settings</h6>
            <button type="submit" form="paymentSettingsForm" class="save-button">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form id="paymentSettingsForm" action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <div class="settings-container">
            <h5 class="mb-4 font-weight-bold text-muted" style="letter-spacing: 0.5px;">Manual Payment Gateways</h5>
            
            <div class="payment-grid">
                
                <!-- Cash on Delivery Gateway -->
                <div class="gateway-card">
                    <div>
                        <div class="gateway-header">
                            <div class="gateway-title">
                                <div class="gateway-icon icon-cod">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <span>Cash on Delivery</span>
                            </div>
                        </div>
                        <div class="gateway-body">
                            <div class="form-group d-flex align-items-center justify-content-between mb-4">
                                <span class="form-label mb-0">Gateway Status</span>
                                <label class="switch-toggle">
                                    <input type="hidden" name="ecommerce[cod]" value="0">
                                    <input type="checkbox" name="ecommerce[cod]" value="1" {{ setting('ecommerce', 'cod', '1') == '1' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <small class="form-text text-muted">
                                    Allow customers to pay with physical cash upon receiving the package at their doorstep.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- bKash Gateway -->
                <div class="gateway-card">
                    <div>
                        <div class="gateway-header">
                            <div class="gateway-title">
                                <div class="gateway-icon icon-bkash">
                                    <i class="fas fa-mobile-screen"></i>
                                </div>
                                <span>bKash Payment</span>
                            </div>
                        </div>
                        <div class="gateway-body">
                            <div class="form-group d-flex align-items-center justify-content-between mb-4">
                                <span class="form-label mb-0">Gateway Status</span>
                                <label class="switch-toggle">
                                    <input type="hidden" name="ecommerce[bkash]" value="0">
                                    <input type="checkbox" name="ecommerce[bkash]" value="1" {{ setting('ecommerce', 'bkash', '1') == '1' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">bKash Number</label>
                                <input type="text" name="ecommerce[bkash_number]" class="form-control"
                                    value="{{ setting('ecommerce', 'bkash_number', '') }}" placeholder="e.g. 017XXXXXXXX">
                                <small class="form-text text-muted">Manual payment destination number shown to customers.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nagad Gateway -->
                <div class="gateway-card">
                    <div>
                        <div class="gateway-header">
                            <div class="gateway-title">
                                <div class="gateway-icon icon-nagad">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <span>Nagad Payment</span>
                            </div>
                        </div>
                        <div class="gateway-body">
                            <div class="form-group d-flex align-items-center justify-content-between mb-4">
                                <span class="form-label mb-0">Gateway Status</span>
                                <label class="switch-toggle">
                                    <input type="hidden" name="ecommerce[nagad]" value="0">
                                    <input type="checkbox" name="ecommerce[nagad]" value="1" {{ setting('ecommerce', 'nagad', '1') == '1' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nagad Number</label>
                                <input type="text" name="ecommerce[nagad_number]" class="form-control"
                                    value="{{ setting('ecommerce', 'nagad_number', '') }}" placeholder="e.g. 017XXXXXXXX">
                                <small class="form-text text-muted">Manual payment destination number shown to customers.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rocket Gateway -->
                <div class="gateway-card">
                    <div>
                        <div class="gateway-header">
                            <div class="gateway-title">
                                <div class="gateway-icon icon-rocket">
                                    <i class="fas fa-piggy-bank"></i>
                                </div>
                                <span>Rocket Payment</span>
                            </div>
                        </div>
                        <div class="gateway-body">
                            <div class="form-group d-flex align-items-center justify-content-between mb-4">
                                <span class="form-label mb-0">Gateway Status</span>
                                <label class="switch-toggle">
                                    <input type="hidden" name="ecommerce[rocket]" value="0">
                                    <input type="checkbox" name="ecommerce[rocket]" value="1" {{ setting('ecommerce', 'rocket', '1') == '1' ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Rocket Number</label>
                                <input type="text" name="ecommerce[rocket_number]" class="form-control"
                                    value="{{ setting('ecommerce', 'rocket_number', '') }}" placeholder="e.g. 017XXXXXXXX">
                                <small class="form-text text-muted">Manual payment destination number shown to customers.</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection
