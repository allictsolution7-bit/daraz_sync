@extends('frontend.app')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --co-primary: #f97316;
        --co-primary-dark: #ea580c;
        --co-accent: #6366f1;
        --co-success: #10b981;
        --co-danger: #ef4444;
        --co-surface: #ffffff;
        --co-bg: #f1f5f9;
        --co-border: #e2e8f0;
        --co-text: #0f172a;
        --co-muted: #64748b;
        --co-shadow: 0 4px 24px rgba(0,0,0,0.07);
        --co-radius: 16px;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body { background: var(--co-bg); font-family: 'Inter', sans-serif; }

    /* ─── Page Shell ─── */
    .co-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 40%, #0f172a 100%);
        padding: 0 0 24px;
        display: flex;
        flex-direction: column;
        position: relative;
        z-index: 1;
    }

    /* ─── Top Bar ─── */
    .co-topbar {
        background: rgba(255,255,255,0.05);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255,255,255,0.08);
        padding: 6px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 100;
    }
    .co-topbar-brand {
        font-size: 16px;
        font-weight: 800;
        color: #fff;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        letter-spacing: -0.5px;
    }
    .co-topbar-brand span { color: var(--co-primary); }
    .co-topbar-secure {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: rgba(255,255,255,0.5);
        font-weight: 500;
    }
    .co-topbar-secure i { color: var(--co-success); font-size: 11px; }

    /* ─── Steps ─── */
    .co-steps-wrap {
        padding: 8px 16px 2px;
        max-width: 600px;
        margin: 0 auto;
        width: 100%;
    }
    .co-steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        position: relative;
    }
    .co-step {
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 2;
    }
    .co-step-num {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
        flex-shrink: 0;
        transition: all 0.3s;
    }
    .co-step.done .co-step-num {
        background: var(--co-success);
        color: #fff;
    }
    .co-step.active .co-step-num {
        background: var(--co-primary);
        color: #fff;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.25);
    }
    .co-step.pending .co-step-num {
        background: rgba(255,255,255,0.15);
        color: rgba(255,255,255,0.5);
    }
    .co-step-label {
        font-size: 11px;
        font-weight: 600;
        color: rgba(255,255,255,0.45);
        white-space: nowrap;
    }
    .co-step.active .co-step-label { color: #fff; font-weight: 700; }
    .co-step.done .co-step-label { color: var(--co-success); }
    .co-step-line {
        flex: 1;
        height: 1.5px;
        background: rgba(255,255,255,0.15);
        margin: 0 10px;
        border-radius: 2px;
        z-index: 1;
    }
    .co-step-line.done { background: var(--co-success); }

    /* ─── Grid ─── */
    .co-grid {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 12px;
        display: grid;
        grid-template-columns: 60% 40%;
        gap: 16px;
        align-items: stretch;
        width: 100%;
        padding-bottom: 8px;
    }

    /* ─── Cards ─── */
    .co-card {
        background: var(--co-surface);
        border-radius: 12px;
        padding: 10px 14px;
        box-shadow: var(--co-shadow);
        border: 1px solid var(--co-border);
        margin-bottom: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .co-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
    .co-card-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--co-text);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--co-border);
    }
    .co-card-title i {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        flex-shrink: 0;
    }
    .co-card-title .icon-orange { background: #fff7ed; color: var(--co-primary); }
    .co-card-title .icon-purple { background: #eef2ff; color: var(--co-accent); }
    .co-card-title .icon-green { background: #ecfdf5; color: var(--co-success); }
    .co-card-title .icon-blue { background: #eff6ff; color: #3b82f6; }

    /* ─── Form Fields ─── */
    .co-field-group { margin-bottom: 10px; }
    .co-field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .co-label {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: var(--co-muted);
        margin-bottom: 4px;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }
    .co-input {
        width: 100%;
        padding: 8px 12px;
        border: 1.5px solid var(--co-border);
        border-radius: 8px;
        font-size: 12px;
        font-family: 'Inter', sans-serif;
        background: #f8fafc;
        color: var(--co-text);
        transition: all 0.2s;
        outline: none;
    }
    .co-input:focus {
        border-color: var(--co-primary);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(249,115,22,0.1);
    }
    .co-input::placeholder { color: #94a3b8; }
    .co-input.error { border-color: var(--co-danger); background: #fff5f5; }
    .co-field-error {
        font-size: 12px;
        color: var(--co-danger);
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    textarea.co-input { resize: none; min-height: 48px; }

    /* ─── Shipping Options ─── */
    .co-shipping-opts { display: flex; flex-direction: column; gap: 6px; }
    .co-shipping-opt {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        border: 1.5px solid var(--co-border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8fafc;
    }
    .co-shipping-opt:hover { border-color: var(--co-primary); background: #fff7ed; }
    .co-shipping-opt.selected { border-color: var(--co-primary); background: #fff7ed; }
    .co-shipping-opt input[type="radio"] { display: none; }
    .co-shipping-opt-left { display: flex; align-items: center; gap: 8px; }
    .co-shipping-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .co-shipping-opt.selected .co-shipping-dot {
        border-color: var(--co-primary);
        background: var(--co-primary);
    }
    .co-shipping-dot-inner {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #fff;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .co-shipping-opt.selected .co-shipping-dot-inner { opacity: 1; }
    .co-shipping-name { font-size: 12px; font-weight: 600; color: var(--co-text); }
    .co-shipping-price {
        font-size: 12px;
        font-weight: 700;
        color: var(--co-primary);
    }
    .co-shipping-price.free { color: var(--co-success); }

    /* ─── Payment Methods ─── */
    .co-pay-section-label {
        font-size: 10px;
        font-weight: 700;
        color: var(--co-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin: 10px 0 6px;
    }
    .co-pay-options { display: flex; flex-direction: column; gap: 6px; }
    .co-pay-opt {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        border: 1.5px solid var(--co-border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background: #f8fafc;
        gap: 8px;
    }
    .co-pay-opt:hover { border-color: #94a3b8; background: #fff; }
    .co-pay-opt.selected { border-color: var(--co-primary); background: #fff7ed; }
    .co-pay-opt input[type="radio"] { display: none; }
    .co-pay-radio {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .co-pay-opt.selected .co-pay-radio {
        border-color: var(--co-primary);
        background: var(--co-primary);
    }
    .co-pay-radio-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #fff;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .co-pay-opt.selected .co-pay-radio-dot { opacity: 1; }
    .co-pay-label { font-size: 12px; font-weight: 600; color: var(--co-text); flex: 1; }
    .co-pay-badge {
        font-size: 9px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        background: #ecfdf5;
        color: var(--co-success);
    }
    .co-pay-logos { display: flex; gap: 6px; flex-wrap: wrap; }
    .co-pay-logo-img { height: 18px; width: auto; border-radius: 3px; }
    .co-pay-online-opts { display: flex; flex-wrap: wrap; gap: 8px; }

    /* ─── Order Summary (Right Column) ─── */
    .co-summary {
        height: 100%;
        overflow: hidden;
    }
    .co-product-row {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--co-border);
        margin-bottom: 8px;
    }
    .co-product-img {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid var(--co-border);
        flex-shrink: 0;
        background: #f1f5f9;
    }
    .co-product-info { flex: 1; min-width: 0; }
    .co-product-title {
        font-size: 12px;
        font-weight: 600;
        color: var(--co-text);
        line-height: 1.3;
        margin-bottom: 3px;
        text-decoration: none;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .co-product-title:hover { color: var(--co-primary); }
    .co-product-meta { display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 4px; }
    .co-badge {
        font-size: 9px;
        font-weight: 600;
        padding: 1px 6px;
        border-radius: 4px;
        background: #eff6ff;
        color: #3b82f6;
    }
    .co-badge-orange { background: #fff7ed; color: var(--co-primary); }
    .co-badge-combo { background: #f0fdf4; color: var(--co-success); }
    .co-qty-controls {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1.5px solid var(--co-border);
        border-radius: 6px;
        overflow: hidden;
        width: fit-content;
    }
    .co-qty-btn {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border: none;
        cursor: pointer;
        font-size: 12px;
        color: var(--co-muted);
        transition: all 0.2s;
        font-weight: 700;
    }
    .co-qty-btn:hover { background: var(--co-border); color: var(--co-text); }
    .co-qty-val {
        width: 28px;
        text-align: center;
        font-size: 12px;
        font-weight: 700;
        color: var(--co-text);
        background: #fff;
        border: none;
        border-left: 1.5px solid var(--co-border);
        border-right: 1.5px solid var(--co-border);
        padding: 0;
        outline: none;
        height: 24px;
        line-height: 24px;
    }
    .co-product-price {
        font-size: 14px;
        font-weight: 800;
        color: var(--co-primary);
        margin-top: 2px;
    }

    /* Combo items */
    .co-combo-items { margin-top: 4px; }
    .co-combo-item {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        color: var(--co-muted);
        padding: 1px 0;
    }
    .co-combo-item i { color: var(--co-success); font-size: 8px; }

    /* Summary Totals */
    .co-totals { display: flex; flex-direction: column; gap: 6px; }
    .co-total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: var(--co-muted);
    }
    .co-total-row.grand {
        padding-top: 8px;
        border-top: 1.5px solid var(--co-border);
        font-size: 15px;
        font-weight: 800;
        color: var(--co-text);
        margin-top: 4px;
    }
    .co-total-row.grand .co-total-val { color: var(--co-primary); }
    .co-total-label { font-weight: 500; }
    .co-total-val { font-weight: 700; color: var(--co-text); }
    .co-total-val.free { color: var(--co-success); font-weight: 600; }

    /* ─── Place Order Button ─── */
    .co-place-btn {
        width: 100%;
        padding: 10px;
        background: linear-gradient(135deg, #f97316 0%, #ef4444 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        letter-spacing: -0.2px;
        margin-top: 10px;
        box-shadow: 0 3px 12px rgba(249,115,22,0.3);
        position: relative;
        overflow: hidden;
    }
    .co-place-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .co-place-btn:hover::before { opacity: 1; }
    .co-place-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(249,115,22,0.5); }
    .co-place-btn:active { transform: translateY(0); }
    .co-place-btn span { position: relative; }
    .co-place-btn.loading { opacity: 0.8; pointer-events: none; }

    /* Trust badges */
    .co-trust {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 16px;
        flex-wrap: wrap;
    }
    .co-trust-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 11px;
        color: var(--co-muted);
        font-weight: 500;
    }
    .co-trust-item i { color: var(--co-success); }

    /* ─── Order Notification ─── */
    .order-notification {
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 14px;
        background: #ecfdf5;
        color: var(--co-success);
        border: 1px solid #a7f3d0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .order-notification.error-message {
        background: #fef2f2;
        color: var(--co-danger);
        border-color: #fca5a5;
    }
    .field-error {
        font-size: 12px;
        color: var(--co-danger);
        margin-top: 5px;
    }
    .form-error { border-color: var(--co-danger) !important; }

    /* ─── Modals ─── */
    .co-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,0.7);
        backdrop-filter: blur(8px);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }
    .co-modal-overlay.hidden { display: none; }
    .co-modal {
        background: #fff;
        border-radius: 20px;
        padding: 32px;
        width: 100%;
        max-width: 440px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        animation: modalSlideIn 0.3s ease;
    }
    @keyframes modalSlideIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .co-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--co-border);
    }
    .co-modal-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--co-text);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .co-modal-close {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        background: #f1f5f9;
        cursor: pointer;
        font-size: 16px;
        color: var(--co-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .co-modal-close:hover { background: #e2e8f0; color: var(--co-text); }

    /* bKash modal */
    .bkash-header-bg {
        background: linear-gradient(135deg, #e2136e, #c01263);
        border-radius: 12px;
        padding: 20px;
        color: #fff;
        margin-bottom: 20px;
        text-align: center;
    }
    .nagad-header-bg {
        background: linear-gradient(135deg, #f15b27, #d84315);
        border-radius: 12px;
        padding: 20px;
        color: #fff;
        margin-bottom: 20px;
        text-align: center;
    }
    .rocket-header-bg {
        background: linear-gradient(135deg, #8C3494, #5c1f63);
        border-radius: 12px;
        padding: 20px;
        color: #fff;
        margin-bottom: 20px;
        text-align: center;
    }
    .modal-amount-label { font-size: 12px; opacity: 0.8; margin-bottom: 4px; }
    .modal-amount-value { font-size: 28px; font-weight: 800; }
    .modal-number-box {
        background: rgba(255,255,255,0.15);
        border-radius: 8px;
        padding: 10px 16px;
        margin-top: 10px;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-copy-btn {
        background: rgba(255,255,255,0.25);
        border: none;
        border-radius: 6px;
        padding: 4px 8px;
        color: #fff;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        transition: background 0.2s;
    }
    .modal-copy-btn:hover { background: rgba(255,255,255,0.35); }
    .modal-steps { margin-bottom: 20px; }
    .modal-step {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px solid var(--co-border);
    }
    .modal-step:last-child { border-bottom: none; }
    .modal-step-num {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--co-primary);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .modal-step-text { font-size: 13px; color: var(--co-muted); line-height: 1.5; }
    .modal-step-text strong { color: var(--co-text); }
    .co-modal-input {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid var(--co-border);
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        background: #f8fafc;
        color: var(--co-text);
        outline: none;
        transition: all 0.2s;
        margin-bottom: 12px;
    }
    .co-modal-input:focus {
        border-color: var(--co-primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
    }
    .co-modal-submit {
        width: 100%;
        padding: 13px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
    }
    .co-modal-submit:hover { opacity: 0.92; transform: translateY(-1px); }
    .bkash-btn { background: linear-gradient(135deg, #e2136e, #c01263); }
    .nagad-btn { background: linear-gradient(135deg, #f15b27, #d84315); }
    .rocket-btn { background: linear-gradient(135deg, #8C3494, #5c1f63); }

    /* OTP Modal */
    .otp-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--co-primary), #ef4444);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        color: #fff;
        font-size: 24px;
    }
    .otp-title { text-align: center; font-size: 20px; font-weight: 800; color: var(--co-text); margin-bottom: 8px; }
    .otp-subtitle { text-align: center; font-size: 13px; color: var(--co-muted); margin-bottom: 24px; line-height: 1.5; }
    .otp-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--co-border);
        border-radius: 12px;
        font-size: 22px;
        font-weight: 700;
        text-align: center;
        letter-spacing: 8px;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: all 0.2s;
        margin-bottom: 14px;
    }
    .otp-input:focus { border-color: var(--co-primary); box-shadow: 0 0 0 4px rgba(249,115,22,0.1); }
    .otp-actions { display: flex; gap: 10px; }
    .otp-verify-btn {
        flex: 1;
        padding: 13px;
        background: linear-gradient(135deg, var(--co-primary), #ef4444);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
    }
    .otp-verify-btn:hover { opacity: 0.9; }
    .resendotp {
        padding: 13px 16px;
        background: #f1f5f9;
        color: var(--co-muted);
        border: 1.5px solid var(--co-border);
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
    }
    .resendotp:hover { background: var(--co-border); }

    /* ─── Copy Toast ─── */
    .copy-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #1e293b;
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        z-index: 99999;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* ─── Mobile ─── */
    @media (max-width: 768px) {
        .co-grid { grid-template-columns: 1fr; }
        .co-summary { position: relative; top: 0; }
        .co-topbar { padding: 12px 16px; }
        .co-steps-wrap { padding: 20px 16px 0; }
        .co-grid { padding: 0 16px; }
        .co-step-label { display: none; }
        .co-field-row { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
@php
    $codEnabled = setting('ecommerce', 'cod', '1') == '1';
    $bkashEnabled = setting('ecommerce', 'bkash', '1') == '1';
    $nagadEnabled = setting('ecommerce', 'nagad', '1') == '1';
    $rocketEnabled = setting('ecommerce', 'rocket', '1') == '1';
    $autoGateways = \App\Models\PaymentGateway::enabled()->orderBy('sort_order')->get();

    if (!$isComboPurchase) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $product->title)));
        $productImage = $product->thumb_image ?? null;
        $unitPrice = $pqty > 0 ? $price / $pqty : $price;
    }
@endphp

<div class="co-page">

    {{-- TOP BAR --}}
    <div class="co-topbar">
        <a href="{{ url('/') }}" class="co-topbar-brand">
            <i class="fa-solid fa-store" style="font-size:18px; color: var(--co-primary);"></i>
            @php
                $storeName = 'PurnoBD';
                if (isset($product) && $product->vendor_id) {
                    $vendor = \App\Models\User::find($product->vendor_id);
                    if ($vendor) {
                        $storeName = $vendor->shop_name ?? $vendor->name ?? ('Store #' . $vendor->id);
                    }
                }
            @endphp
            <span>{{ $storeName }}</span>
        </a>
        <div class="co-topbar-secure">
            <i class="fa-solid fa-shield-halved"></i> Secured Checkout
        </div>
    </div>

    {{-- STEPS --}}
    <div class="co-steps-wrap">
        <div class="co-steps">
            <div class="co-step done">
                <div class="co-step-num"><i class="fa-solid fa-check" style="font-size:12px;"></i></div>
                <div class="co-step-label">Cart</div>
            </div>
            <div class="co-step-line done"></div>
            <div class="co-step active">
                <div class="co-step-num">2</div>
                <div class="co-step-label">Checkout</div>
            </div>
            <div class="co-step-line"></div>
            <div class="co-step pending">
                <div class="co-step-num">3</div>
                <div class="co-step-label">Confirmation</div>
            </div>
        </div>
    </div>

    <div class="co-grid">

        {{-- ═══ COLUMN 1: SHIPPING DETAILS & DELIVERY AREA ═══ --}}
        <div class="co-left" style="grid-column: span 1; height: 100%;">
            <form id="buynow-order" style="height: 100%;">
                @csrf
                {{-- Hidden product fields --}}
                @if ($isComboPurchase)
                    <input type="hidden" name="is_combo_purchase" value="1">
                    <input type="hidden" name="combo_offer_id" value="{{ $comboData['combo_offer_id'] }}">
                    <input type="hidden" name="quantity" value="{{ $comboData['quantity'] }}">
                    <input type="hidden" name="price" value="{{ $comboData['combo_price'] }}">
                    @foreach ($comboData['selections'] as $index => $selection)
                        <input type="hidden" name="selections[{{ $index }}][product_id]" value="{{ $selection['product_id'] }}">
                        <input type="hidden" name="selections[{{ $index }}][variation_id]" value="{{ $selection['variation_id'] ?? '' }}">
                        <input type="hidden" name="selections[{{ $index }}][slot_index]" value="{{ $selection['slot_index'] }}">
                    @endforeach
                @else
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="pqty" value="{{ $pqty }}">
                    <input type="hidden" name="price" value="{{ $price }}">
                    @if (isset($combination))
                        <input type="hidden" name="combination_id" value="{{ $combination->id }}">
                    @endif
                    @if (isset($option_id))
                        <input type="hidden" name="option_id" value="{{ $option_id }}">
                    @endif
                @endif
                <input type="hidden" name="shipping" value="{{ $shipping }}">
                {{-- UTM Tracking --}}
                <input type="hidden" name="utm_source" class="utm_source" value="">
                <input type="hidden" name="utm_medium" class="utm_medium" value="">
                <input type="hidden" name="utm_campaign" class="utm_campaign" value="">
                <input type="hidden" name="utm_content" class="utm_content" value="">
                <input type="hidden" name="utm_term" class="utm_term" value="">
                <input type="hidden" name="fbclid" class="fbclid" value="">
                <input type="hidden" name="gclid" class="gclid" value="">
                <input type="hidden" name="ttclid" class="ttclid" value="">

                {{-- Order Message --}}
                <div id="order-message"></div>

                {{-- ── SHIPPING ADDRESS ── --}}
                <div class="co-card">
                    <div class="co-card-title">
                        <i class="fa-solid fa-location-dot icon-orange"></i>
                        Shipping Address
                    </div>

                    {{-- Saved Locations Selector --}}
                    @if(isset($locations) && $locations->count() > 0)
                        <div class="co-field-group" style="margin-bottom: 10px; padding: 6px; background: #f8fafc; border: 1.5px dashed var(--co-primary); border-radius: 8px;">
                            <label class="co-label" style="color: var(--co-primary); font-weight: 700; margin-bottom: 4px; display: flex; align-items: center; gap: 4px; font-size: 9px;">
                                <i class="fa-solid fa-map-location-dot"></i> Saved Locations:
                            </label>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @foreach($locations as $loc)
                                    <button type="button" class="co-loc-select-btn" 
                                            data-name="{{ auth()->user()->name }}"
                                            data-phone="{{ auth()->user()->phone }}"
                                            data-address="{{ $loc->address }}"
                                            data-division="{{ $loc->division }}"
                                            data-district="{{ $loc->district }}"
                                            data-upazila="{{ $loc->upazila }}"
                                            data-postcode="{{ $loc->post_code }}"
                                            style="padding: 4px 8px; background: #fff; border: 1.5px solid var(--co-border); border-radius: 6px; font-size: 11px; font-weight: 600; color: var(--co-text); cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-house-chimney" style="color: var(--co-primary); font-size: 10px;"></i>
                                        <span>{{ $loc->title }}</span>
                                        @if($loc->is_default)
                                            <span style="font-size: 8px; background: var(--co-primary); color: #fff; padding: 0px 4px; border-radius: 3px; font-weight: 700;">DEF</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="co-field-group">
                        <label class="co-label">Full Name *</label>
                        <input class="co-input" type="text" name="name" placeholder="Enter your full name"
                            value="{{ auth()->check() ? auth()->user()->name : old('name') }}">
                        @if ($errors->get('name'))
                            <div class="field-error">@foreach ($errors->get('name') as $e) {{ $e }} @endforeach</div>
                        @endif
                    </div>

                    <div class="co-field-group">
                        <label class="co-label">Phone Number *</label>
                        <input class="co-input" type="tel" name="phone" placeholder="01XXXXXXXXX"
                            value="{{ auth()->check() ? auth()->user()->phone : old('phone') }}">
                        @if ($errors->get('phone'))
                            <div class="field-error">@foreach ($errors->get('phone') as $e) {{ $e }} @endforeach</div>
                        @endif
                    </div>

                    <div class="co-field-group">
                        <label class="co-label">Delivery Address *</label>
                        <input class="co-input" type="text" name="address" placeholder="House No, Road, Village / Area, Bazar..."
                            value="{{ auth()->check() ? auth()->user()->address : old('address') }}">
                        @if ($errors->get('address'))
                            <div class="field-error">@foreach ($errors->get('address') as $e) {{ $e }} @endforeach</div>
                        @endif
                    </div>

                    <div class="co-field-group">
                        <label class="co-label">Order Note (Optional)</label>
                        <textarea class="co-input" name="message" rows="2" placeholder="Note for us..."></textarea>
                    </div>

                    {{-- Select Delivery Area inline --}}
                    <div style="margin-top: 10px; border-top: 1px solid var(--co-border); padding-top: 10px;">
                        <span class="co-label" style="margin-bottom: 6px; font-weight: 700; color: var(--co-text);"><i class="fa-solid fa-truck-fast"></i> Delivery Area</span>
                        <div style="display: flex; gap: 8px; width: 100%;">
                            @php
                                $firstActiveOption = null;
                                foreach ($activeShippingOptions as $key => $option) {
                                    if ($option['active'] ?? false) {
                                        $firstActiveOption = $key;
                                        break;
                                    }
                                }
                            @endphp
                            @foreach ($activeShippingOptions as $key => $option)
                                @php
                                    $isSelected = ($shipping == $option['cost']) || (!$shipping && $key === $firstActiveOption);
                                @endphp
                                <label class="co-shipping-opt {{ $isSelected ? 'selected' : '' }}" for="ship_{{ $key }}" style="flex: 1; padding: 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 2px; margin: 0; background: {{ $isSelected ? '#fff7ed' : '#f8fafc' }}; border-radius: 6px; border: 1.5px solid {{ $isSelected ? 'var(--co-primary)' : 'var(--co-border)' }}; cursor: pointer;">
                                    <input type="radio" name="shipping_area" id="ship_{{ $key }}" value="{{ $key }}" {{ $isSelected ? 'checked' : '' }} style="display:none;">
                                    <span class="co-shipping-name" style="font-size: 11px;">{{ $option['name'] }}</span>
                                    <span class="co-shipping-price" style="font-size: 12px; font-weight: 700;">
                                        {{ $option['cost'] == 0 ? 'FREE' : 'Tk '.number_format($option['cost'], 0) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

            </form>
        </div>

        {{-- ═══ COLUMN 2: ITEMS & PRICING & PAYMENT ═══ --}}
        <div class="co-mid" style="grid-column: span 1;">
            <div class="co-card">
                <div class="co-card-title">
                    <i class="fa-solid fa-basket-shopping icon-blue"></i>
                    Order Summary
                </div>

                {{-- Product Info --}}
                <div class="co-product-row">
                    @if ($isComboPurchase)
                        @php
                            $primaryComboProduct = isset($comboData['selections'][0]['product_id'])
                                ? \App\Models\Product::find($comboData['selections'][0]['product_id'])
                                : null;
                        @endphp
                        @if ($primaryComboProduct && $primaryComboProduct->thumb_image)
                            <img src="{{ asset('storage/'.$primaryComboProduct->thumb_image) }}" alt="{{ $comboData['combo_title'] }}" class="co-product-img">
                        @else
                            <div class="co-product-img" style="display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-box" style="color:#94a3b8;font-size:24px;"></i></div>
                        @endif
                        <div class="co-product-info">
                            <span class="co-product-title">{{ $comboData['combo_title'] }}</span>
                            <div class="co-product-meta">
                                <span class="co-badge co-badge-combo"><i class="fa-solid fa-layer-group" style="font-size:9px;"></i> Combo Offer</span>
                            </div>
                            <div class="co-combo-items">
                                @if(isset($comboData['selections']) && is_array($comboData['selections']))
                                    @foreach ($comboData['selections'] as $sel)
                                        @php $selProduct = \App\Models\Product::find($sel['product_id']); @endphp
                                        @if ($selProduct)
                                            <div class="co-combo-item">
                                                <i class="fa-solid fa-circle-check"></i>
                                                {{ $selProduct->title }}
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="co-qty-controls" style="margin-top:6px;">
                                <button type="button" class="co-qty-btn quantity-btn" data-context="combo" data-action="decrease">−</button>
                                <input type="text" class="co-qty-val quantity-input" data-context="combo" value="{{ $comboData['quantity'] }}" readonly>
                                <button type="button" class="co-qty-btn quantity-btn" data-context="combo" data-action="increase">+</button>
                            </div>
                            <div class="co-product-price" id="combo-price" style="margin-top:6px;">
                                {{ $comboData['combo_price'] * $comboData['quantity'] }}৳
                            </div>
                        </div>
                    @else
                        @if ($productImage)
                            <img src="{{ asset('storage/'.$productImage) }}" alt="{{ $product->title }}" class="co-product-img">
                        @else
                            <div class="co-product-img" style="display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-box" style="color:#94a3b8;font-size:20px;"></i></div>
                        @endif
                        <div class="co-product-info">
                            <a href="{{ route('product.single', ['id' => $product->id, 'slug' => $slug]) }}" class="co-product-title">{{ $product->title }}</a>
                            <div class="co-product-meta">
                                @if (isset($combination))
                                    <span class="co-badge"><i class="fa-solid fa-palette" style="font-size:8px;"></i> {{ $combination->display_name }}</span>
                                @elseif (isset($option_id) && !empty($option_id))
                                    @php
                                        $pOpt = \App\Models\ProductVariationOption::with('variationOption.variation')->find($option_id);
                                    @endphp
                                    @if ($pOpt)
                                        <span class="co-badge">{{ $pOpt->variationOption->variation->name ?? '' }}: {{ $pOpt->variationOption->name ?? '' }}</span>
                                    @endif
                                @endif
                                <span class="co-badge co-badge-orange">Qty: <span class="product-qty-label">{{ $pqty }}</span></span>
                            </div>
                            <div class="co-qty-controls" style="margin-top:4px;" id="single-order-row" data-unit-price="{{ $unitPrice }}">
                                <button type="button" class="co-qty-btn quantity-btn" data-context="single" data-action="decrease">−</button>
                                <input type="text" class="co-qty-val quantity-input" data-context="single" value="{{ $pqty }}" readonly>
                                <button type="button" class="co-qty-btn quantity-btn" data-context="single" data-action="increase">+</button>
                            </div>
                            <div class="co-product-price" id="single-price" style="margin-top:4px;">{{ $price }}৳</div>
                        </div>
                    @endif
                </div>

                {{-- Calculations & Pricing Totals --}}
                <div class="co-totals">
                    <div class="co-total-row">
                        <span class="co-total-label">Subtotal</span>
                        <span class="co-total-val" id="Subtotal">
                            @if ($isComboPurchase)
                                {{ $comboData['combo_price'] * $comboData['quantity'] }}৳
                            @else
                                {{ $sub_total }}৳
                            @endif
                        </span>
                    </div>

                    <div class="co-total-row">
                        <span class="co-total-label">Shipping</span>
                        <span class="co-total-val" id="flat-rate">
                            @php
                                $displaySubtotal = $isComboPurchase ? ($comboData['combo_price'] * $comboData['quantity']) : $sub_total;
                            @endphp
                            @if ($displaySubtotal >= 1500)
                                <span class="free">🎉 Free</span>
                            @else
                                {{ $shipping }}৳
                            @endif
                        </span>
                    </div>

                    <div class="co-total-row" id="bkashChargeRow" style="display:none;">
                        <span class="co-total-label">bKash Charge (1.8%)</span>
                        <span class="co-total-val"><span id="bkashChargeDisplay">0.00</span>৳</span>
                    </div>
                    <div class="co-total-row" id="nagadChargeRow" style="display:none;">
                        <span class="co-total-label">Nagad Charge (1.5%)</span>
                        <span class="co-total-val"><span id="nagadChargeDisplay">0.00</span>৳</span>
                    </div>
                    <div class="co-total-row" id="rocketChargeRow" style="display:none;">
                        <span class="co-total-label">Rocket Charge (1.8%)</span>
                        <span class="co-total-val"><span id="rocketChargeDisplay">0.00</span>৳</span>
                    </div>

                    <div class="co-total-row grand" style="margin-top: 4px; padding-top: 6px; border-top: 1.5px solid var(--co-border);">
                        <span class="co-total-label">Grand Total</span>
                        <span class="co-total-val order-total">
                            @if ($isComboPurchase)
                                {{ ($comboData['combo_price'] * $comboData['quantity']) + $shipping }}৳
                            @else
                                {{ $sub_total + $shipping }}৳
                            @endif
                        </span>
                    </div>
                </div>

                {{-- Compact Payment Selection (Icons Only) --}}
                <div style="margin-top: 10px; border-top: 1px solid var(--co-border); padding-top: 10px;">
                    <span class="co-label" style="margin-bottom: 6px; font-weight: 700; color: var(--co-text);"><i class="fa-solid fa-credit-card"></i> Payment Method</span>
                    <div class="co-pay-options" style="display: flex; flex-direction: row; gap: 8px; flex-wrap: wrap;">
                        @if ($codEnabled)
                            <label class="co-pay-opt selected" for="pay_cod" style="flex: 1; padding: 6px; display: flex; align-items: center; justify-content: center; border: 1.5px solid var(--co-border); border-radius: 6px; cursor: pointer; background: #fff; margin: 0; min-height: 32px;" title="Cash on Delivery">
                                <input type="radio" name="payment_method" id="pay_cod" value="cod" checked style="display:none;">
                                <i class="fa-solid fa-money-bill-wave" style="color:#10b981; font-size: 14px;"></i>
                            </label>
                        @endif

                        @if ($bkashEnabled)
                            <label class="co-pay-opt" for="pay_bkash" style="flex: 1; padding: 6px; display: flex; align-items: center; justify-content: center; border: 1.5px solid var(--co-border); border-radius: 6px; cursor: pointer; background: #fff; margin: 0; min-height: 32px;" title="bKash Wallet">
                                <input type="radio" name="payment_method" id="pay_bkash" value="bkash" style="display:none;">
                                <img src="{{ asset('payment-method/bkash.png') }}" alt="bKash" class="co-pay-logo-img" style="height: 16px;">
                            </label>
                        @endif

                        @if ($nagadEnabled)
                            <label class="co-pay-opt" for="pay_nagad" style="flex: 1; padding: 6px; display: flex; align-items: center; justify-content: center; border: 1.5px solid var(--co-border); border-radius: 6px; cursor: pointer; background: #fff; margin: 0; min-height: 32px;" title="Nagad Wallet">
                                <input type="radio" name="payment_method" id="pay_nagad" value="nagad" style="display:none;">
                                <img src="{{ asset('payment-method/nagad.png') }}" alt="Nagad" class="co-pay-logo-img" style="height: 16px;">
                            </label>
                        @endif

                        @if ($rocketEnabled)
                            <label class="co-pay-opt" for="pay_rocket" style="flex: 1; padding: 6px; display: flex; align-items: center; justify-content: center; border: 1.5px solid var(--co-border); border-radius: 6px; cursor: pointer; background: #fff; margin: 0; min-height: 32px;" title="Rocket Wallet">
                                <input type="radio" name="payment_method" id="pay_rocket" value="rocket" style="display:none;">
                                <img src="{{ asset('payment-method/rocket.png') }}" alt="Rocket" class="co-pay-logo-img" style="height: 16px;">
                            </label>
                        @endif

                        @if ($autoGateways->count() > 0)
                            @foreach($autoGateways as $gw)
                                <label class="co-pay-opt" for="pay_{{ $gw->provider }}_bn" style="flex: 1; padding: 6px; display: flex; align-items: center; justify-content: center; border: 1.5px solid var(--co-border); border-radius: 6px; cursor: pointer; background: #fff; margin: 0; min-height: 32px;" title="{{ $gw->name }}">
                                    <input type="radio" name="payment_method" id="pay_{{ $gw->provider }}_bn" value="{{ $gw->provider }}" style="display:none;">
                                    <i class="fa-solid fa-credit-card" style="color: #3b82f6; font-size: 14px;"></i>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Confirm Action Button inside Card --}}
                <div style="margin-top: 10px; border-top: 1.5px solid var(--co-border); padding-top: 10px;">
                    <button type="submit" form="buynow-order" class="co-place-btn" id="place-order-btn" style="margin-top: 0; width: 100%;">
                        <span><i class="fa-solid fa-lock" style="font-size:12px;"></i></span>
                        <span>Confirm Order Now</span>
                        <span><i class="fa-solid fa-arrow-right" style="font-size:12px;"></i></span>
                    </button>
                    
                    {{-- Trust Badges --}}
                    <div class="co-trust" style="margin-top: 6px; display: flex; justify-content: space-between;">
                        <div class="co-trust-item" style="font-size: 9px;"><i class="fa-solid fa-shield-halved" style="font-size: 9px;"></i> Secured Checkout</div>
                        <div class="co-trust-item" style="font-size: 9px;"><i class="fa-solid fa-truck-fast" style="font-size: 9px;"></i> Fast Delivery</div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- .co-grid --}}
</div>{{-- .co-page --}}

{{-- Copy Toast --}}
<div class="copy-toast" id="copy-toast"><i class="fa-solid fa-check"></i> Copied!</div>

{{-- ═══ BKASH MODAL ═══ --}}
<div id="bkashModal" class="co-modal-overlay hidden">
    <div class="co-modal">
        <div class="co-modal-header">
            <div class="co-modal-title" style="color:#e2136e;"><i class="fa-solid fa-mobile-screen-button"></i> bKash Payment</div>
            <button class="co-modal-close" onclick="closeBkashModal()">×</button>
        </div>
        <div class="bkash-header-bg">
            <div class="modal-amount-label">Amount to Send</div>
            <div class="modal-amount-value">৳<span id="bkashAmount">0</span></div>
            @php $bkashNumber = setting('ecommerce', 'bkash_number', ''); @endphp
            @if($bkashNumber)
            <div class="modal-number-box">
                <span class="bkash-payment-number">{{ $bkashNumber }}</span>
                <button class="modal-copy-btn" onclick="copyPaymentNumber('bkash-payment-number')"><i class="fa-solid fa-copy"></i> Copy</button>
            </div>
            @endif
        </div>
        <div class="modal-steps">
            <div class="modal-step">
                <div class="modal-step-num">1</div>
                <div class="modal-step-text">Open <strong>bKash App</strong> → Send Money</div>
            </div>
            <div class="modal-step">
                <div class="modal-step-num">2</div>
                <div class="modal-step-text">Send <strong>৳<span id="stepAmount">0</span></strong> to the number above</div>
            </div>
            <div class="modal-step">
                <div class="modal-step-num">3</div>
                <div class="modal-step-text">Enter <strong>bKash Number</strong> and <strong>Transaction ID</strong> below</div>
            </div>
        </div>
        <form id="bkash-form">
            <input class="co-modal-input" type="tel" id="bkash_number" name="bkash_number" placeholder="Your bKash number" required>
            <input class="co-modal-input" type="text" id="bkash_transaction_id" name="bkash_transaction_id" placeholder="Transaction ID (TrxID)" required>
            <button type="submit" class="co-modal-submit bkash-btn">
                <i class="fa-solid fa-check-circle"></i> Confirm bKash Payment
            </button>
        </form>
    </div>
</div>

{{-- ═══ NAGAD MODAL ═══ --}}
<div id="nagadModal" class="co-modal-overlay hidden">
    <div class="co-modal">
        <div class="co-modal-header">
            <div class="co-modal-title" style="color:#f15b27;"><i class="fa-solid fa-mobile-screen-button"></i> Nagad Payment</div>
            <button class="co-modal-close" onclick="closeNagadModal()">×</button>
        </div>
        <div class="nagad-header-bg">
            <div class="modal-amount-label">Amount to Send</div>
            <div class="modal-amount-value">৳<span id="nagadAmount">0</span></div>
            @php $nagadNumber = setting('ecommerce', 'nagad_number', ''); @endphp
            @if($nagadNumber)
            <div class="modal-number-box">
                <span class="nagad-payment-number">{{ $nagadNumber }}</span>
                <button class="modal-copy-btn" onclick="copyPaymentNumber('nagad-payment-number')"><i class="fa-solid fa-copy"></i> Copy</button>
            </div>
            @endif
        </div>
        <div class="modal-steps">
            <div class="modal-step">
                <div class="modal-step-num">1</div>
                <div class="modal-step-text">Open <strong>Nagad App</strong> → Send Money</div>
            </div>
            <div class="modal-step">
                <div class="modal-step-num">2</div>
                <div class="modal-step-text">Send <strong>৳<span id="nagadStepAmount">0</span></strong> to the number above</div>
            </div>
            <div class="modal-step">
                <div class="modal-step-num">3</div>
                <div class="modal-step-text">Enter <strong>Nagad Number</strong> and <strong>Transaction ID</strong> below</div>
            </div>
        </div>
        <form id="nagad-form">
            <input class="co-modal-input" type="tel" id="nagad_number" name="nagad_number" placeholder="Your Nagad number" required>
            <input class="co-modal-input" type="text" id="nagad_transaction_id" name="nagad_transaction_id" placeholder="Transaction ID (TrxID)" required>
            <button type="submit" class="co-modal-submit nagad-btn">
                <i class="fa-solid fa-check-circle"></i> Confirm Nagad Payment
            </button>
        </form>
    </div>
</div>

{{-- ═══ ROCKET MODAL ═══ --}}
<div id="rocketModal" class="co-modal-overlay hidden">
    <div class="co-modal">
        <div class="co-modal-header">
            <div class="co-modal-title" style="color:#8C3494;"><i class="fa-solid fa-mobile-screen-button"></i> Rocket Payment</div>
            <button class="co-modal-close" onclick="closeRocketModal()">×</button>
        </div>
        <div class="rocket-header-bg">
            <div class="modal-amount-label">Amount to Send</div>
            <div class="modal-amount-value">৳<span id="rocketAmount">0</span></div>
            @php $rocketNumber = setting('ecommerce', 'rocket_number', ''); @endphp
            @if($rocketNumber)
            <div class="modal-number-box">
                <span class="rocket-payment-number">{{ $rocketNumber }}</span>
                <button class="modal-copy-btn" onclick="copyPaymentNumber('rocket-payment-number')"><i class="fa-solid fa-copy"></i> Copy</button>
            </div>
            @endif
        </div>
        <div class="modal-steps">
            <div class="modal-step">
                <div class="modal-step-num">1</div>
                <div class="modal-step-text">Dial <strong>*322#</strong> or open <strong>Rocket App</strong> → Send Money</div>
            </div>
            <div class="modal-step">
                <div class="modal-step-num">2</div>
                <div class="modal-step-text">Send <strong>৳<span id="rocketStepAmount">0</span></strong> to the number above</div>
            </div>
            <div class="modal-step">
                <div class="modal-step-num">3</div>
                <div class="modal-step-text">Enter <strong>Rocket Number</strong> and <strong>Transaction ID</strong> below</div>
            </div>
        </div>
        <form id="rocket-form">
            <input class="co-modal-input" type="tel" id="rocket_number" name="rocket_number" placeholder="Your Rocket number" required>
            <input class="co-modal-input" type="text" id="rocket_transaction_id" name="rocket_transaction_id" placeholder="Transaction ID (TrxID)" required>
            <button type="submit" class="co-modal-submit rocket-btn">
                <i class="fa-solid fa-check-circle"></i> Confirm Rocket Payment
            </button>
        </form>
    </div>
</div>

{{-- ═══ OTP MODAL ═══ --}}
<div id="otpModal" class="co-modal-overlay hidden">
    <div class="co-modal">
        <div class="otp-icon"><i class="fa-solid fa-shield-halved"></i></div>
        <div class="otp-title">OTP Verification</div>
        <div class="otp-subtitle">We've sent a One-Time Password to your phone number.<br>Please enter it below to confirm your order.</div>
        <div id="otp-message"></div>
        <input type="number" class="otp-input" id="otp" placeholder="● ● ● ● ● ●" maxlength="6">
        <div class="otp-actions">
            <button type="button" class="resendotp" onclick="void(0)">Resend OTP</button>
            <button type="button" class="otp-verify-btn" onclick="submitOtp(event)">
                <i class="fa-solid fa-check"></i> Verify & Place Order
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    // ── UTM Capture ──
    (function() {
        const urlParams = new URLSearchParams(window.location.search);
        ['utm_source','utm_medium','utm_campaign','utm_content','utm_term','fbclid','gclid','ttclid'].forEach(function(f) {
            const v = urlParams.get(f);
            document.querySelectorAll('.'+f).forEach(function(el) { if(v) el.value = v; });
        });
    })();

    // ── Payment option UI ──
    document.querySelectorAll('.co-pay-opt').forEach(function(opt) {
        opt.addEventListener('click', function() {
            // Reset all payment options styles
            document.querySelectorAll('.co-pay-opt').forEach(o => {
                o.style.borderColor = 'var(--co-border)';
                o.style.background = '#fff';
                const radioDot = o.querySelector('.co-pay-radio');
                if (radioDot) {
                    radioDot.style.borderColor = '#cbd5e1';
                    radioDot.style.background = 'none';
                    const inner = radioDot.querySelector('.co-pay-radio-dot');
                    if (inner) inner.style.opacity = '0';
                }
            });
            // Apply selected styles
            opt.style.borderColor = 'var(--co-primary)';
            opt.style.background = '#fff7ed';
            const radioDot = opt.querySelector('.co-pay-radio');
            if (radioDot) {
                radioDot.style.borderColor = 'var(--co-primary)';
                radioDot.style.background = 'var(--co-primary)';
                const inner = radioDot.querySelector('.co-pay-radio-dot');
                if (inner) inner.style.opacity = '1';
            }
            const radio = opt.querySelector('input[type="radio"]');
            if(radio) radio.checked = true;
            updateOrderTotal();
        });
    });

    // ── Shipping option UI ──
    document.querySelectorAll('.co-shipping-opt').forEach(function(opt) {
        opt.addEventListener('click', function() {
            document.querySelectorAll('.co-shipping-opt').forEach(o => {
                o.classList.remove('selected');
                o.style.borderColor = 'var(--co-border)';
                o.style.background = '#f8fafc';
            });
            opt.classList.add('selected');
            opt.style.borderColor = 'var(--co-primary)';
            opt.style.background = '#fff7ed';
            const radio = opt.querySelector('input[type="radio"]');
            if(radio) radio.checked = true;
            updateOrderTotal();
        });
    });

    // ── Saved Location Selector Handler ──
    document.querySelectorAll('.co-loc-select-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Remove active styles from other buttons
            document.querySelectorAll('.co-loc-select-btn').forEach(b => {
                b.style.borderColor = 'var(--co-border)';
                b.style.background = '#fff';
            });
            // Highlight selected button
            btn.style.borderColor = 'var(--co-primary)';
            btn.style.background = '#fff7ed';

            // Auto-fill form fields
            const name = btn.getAttribute('data-name');
            const phone = btn.getAttribute('data-phone');
            const address = btn.getAttribute('data-address');
            const division = btn.getAttribute('data-division') || '';
            const district = btn.getAttribute('data-district') || '';
            const upazila = btn.getAttribute('data-upazila') || '';
            const postcode = btn.getAttribute('data-postcode') || '';

            if (name) document.querySelector('input[name="name"]').value = name;
            if (phone) document.querySelector('input[name="phone"]').value = phone;
            
            // Build a detailed formatted address string incorporating Division, District, etc.
            let fullAddress = address;
            let parts = [];
            if (upazila) parts.push(upazila);
            if (district) parts.push(district);
            if (division) parts.push(division);
            if (postcode) parts.push(postcode);
            
            if (parts.length > 0) {
                fullAddress += " (" + parts.join(', ') + ")";
            }
            
            document.querySelector('input[name="address"]').value = fullAddress;
        });
    });

    $(document).ready(function() {
        // Form submit
        $('#buynow-order').on('submit', function(e) {
            e.preventDefault();
            const paymentMethod = $('input[name="payment_method"]:checked').val();
            if (paymentMethod === 'bkash') { showBkashModal(); return; }
            else if (paymentMethod === 'nagad') { showNagadModal(); return; }
            else if (paymentMethod === 'rocket') { showRocketModal(); return; }
            else { placeOrder(); }
        });

        // bKash
        $('#bkash-form').on('submit', function(e) {
            e.preventDefault();
            const charge = parseFloat($('#bkashChargeDisplay').text()) || 0;
            $('#buynow-order').append(`
                <input type="hidden" name="bkash_number" value="${$('#bkash_number').val()}">
                <input type="hidden" name="bkash_transaction_id" value="${$('#bkash_transaction_id').val()}">
                <input type="hidden" name="bkash_charge" value="${charge}">
            `);
            closeBkashModal();
            placeOrder();
        });

        // Nagad
        $('#nagad-form').on('submit', function(e) {
            e.preventDefault();
            const charge = parseFloat($('#nagadChargeDisplay').text()) || 0;
            $('#buynow-order').append(`
                <input type="hidden" name="nagad_number" value="${$('#nagad_number').val()}">
                <input type="hidden" name="nagad_transaction_id" value="${$('#nagad_transaction_id').val()}">
                <input type="hidden" name="nagad_charge" value="${charge}">
            `);
            closeNagadModal();
            placeOrder();
        });

        // Rocket
        $('#rocket-form').on('submit', function(e) {
            e.preventDefault();
            const charge = parseFloat($('#rocketChargeDisplay').text()) || 0;
            $('#buynow-order').append(`
                <input type="hidden" name="rocket_number" value="${$('#rocket_number').val()}">
                <input type="hidden" name="rocket_transaction_id" value="${$('#rocket_transaction_id').val()}">
                <input type="hidden" name="rocket_charge" value="${charge}">
            `);
            closeRocketModal();
            placeOrder();
        });

        window.closeBkashModal = closeBkashModal;
        window.closeNagadModal = closeNagadModal;
        window.closeRocketModal = closeRocketModal;

        function showBkashModal() {
            const total = calculateTotalAmount();
            $('#bkashAmount, #stepAmount').text(total);
            $('#bkashModal').removeClass('hidden');
        }
        function closeBkashModal() { $('#bkashModal').addClass('hidden'); }

        function showNagadModal() {
            const total = calculateTotalAmount();
            $('#nagadAmount, #nagadStepAmount').text(total);
            $('#nagadModal').removeClass('hidden');
        }
        function closeNagadModal() { $('#nagadModal').addClass('hidden'); }

        function showRocketModal() {
            const total = calculateTotalAmount();
            $('#rocketAmount, #rocketStepAmount').text(total);
            $('#rocketModal').removeClass('hidden');
        }
        function closeRocketModal() { $('#rocketModal').addClass('hidden'); }

        function calculateTotalAmount() {
            return $('.order-total').text().replace('৳','').trim() || '0.00';
        }

        function placeOrder() {
            const btn = document.getElementById('place-order-btn');
            btn.classList.add('loading');
            btn.innerHTML = '<span><i class="fa-solid fa-spinner fa-spin"></i></span><span>Placing Order...</span>';

            $.ajax({
                url: '{{ route('buynow.order') }}',
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: $('#buynow-order').serialize(),
                success: function(data) {
                    btn.classList.remove('loading');
                    btn.innerHTML = '<span><i class="fa-solid fa-lock" style="font-size:14px;"></i></span><span>Confirm Order Now</span><span><i class="fa-solid fa-arrow-right" style="font-size:14px;"></i></span>';

                    if (data.status === 'otp_sent') {
                        openOtpModal();
                        showNotification(data.message, 'success');
                    } else if (data.success || data.status === 'success') {
                        showNotification(data.message || 'Order placed successfully!', 'success');
                        if (data.order_id) {
                            if (data.requires_redirect) {
                                showNotification('Redirecting to payment gateway...', 'success');
                                $.ajax({
                                    url: '{{ route("payment.initiate") }}',
                                    type: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    contentType: 'application/json',
                                    data: JSON.stringify({ order_id: data.order_id, provider: data.provider }),
                                    success: function(result) {
                                        if (result.redirect_url) { window.location.href = result.redirect_url; }
                                        else { window.location.href = "{{ url('thank-you') }}/" + data.order_id; }
                                    },
                                    error: function() { window.location.href = "{{ url('thank-you') }}/" + data.order_id; }
                                });
                            } else {
                                setTimeout(function() { window.location.href = "{{ url('thank-you') }}/" + data.order_id; }, 1000);
                            }
                        } else { $('#buynow-order')[0].reset(); }
                    } else {
                        const errs = Array.isArray(data.errors) ? data.errors.join('<br>') : (data.message || 'Unknown error');
                        showNotification(errs, 'error');
                    }
                },
                error: function(xhr) {
                    btn.classList.remove('loading');
                    btn.innerHTML = '<span><i class="fa-solid fa-lock" style="font-size:14px;"></i></span><span>Confirm Order Now</span><span><i class="fa-solid fa-arrow-right" style="font-size:14px;"></i></span>';
                    $('.form-error').removeClass('form-error');
                    $('.field-error').remove();

                    if (xhr.status === 422) {
                        const resp = xhr.responseJSON || {};
                        const payloadErrors = resp.errors;
                        let msg = resp.message || 'Please check the form fields.';
                        if (payloadErrors && typeof payloadErrors === 'object') {
                            const firstKey = Object.keys(payloadErrors)[0];
                            if (firstKey && Array.isArray(payloadErrors[firstKey])) msg = payloadErrors[firstKey][0];
                            Object.keys(payloadErrors).forEach(function(field) {
                                const msgs = Array.isArray(payloadErrors[field]) ? payloadErrors[field] : [String(payloadErrors[field] || '')];
                                const el = $(`[name="${field}"]`);
                                if (el && el.length) {
                                    el.addClass('form-error');
                                    if (!el.next('.field-error').length) el.after(`<div class="field-error">${msgs.join('<br>')}</div>`);
                                }
                            });
                        }
                        showNotification(msg, 'error');
                        if (window.pushFraudNotification) window.pushFraudNotification(msg, 'error');
                    } else {
                        showNotification('An unexpected error occurred. Please try again.', 'error');
                    }
                }
            });
        }

        // Initial total calculation
        updateOrderTotal();
    });

    function showNotification(msg, type) {
        const box = document.getElementById('order-message');
        const icon = type === 'success' ? '<i class="fa-solid fa-circle-check"></i>' : '<i class="fa-solid fa-circle-exclamation"></i>';
        box.innerHTML = `<div class="order-notification ${type === 'error' ? 'error-message' : ''}">${icon} ${msg}</div>`;
        box.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // ── OTP ──
    function openOtpModal() { document.getElementById('otpModal').classList.remove('hidden'); }
    function closeOtpModal() { document.getElementById('otpModal').classList.add('hidden'); }

    function submitOtp(e) {
        if (e) e.preventDefault();
        const otp = document.getElementById('otp').value;
        $.ajax({
            url: '{{ route('otp.verify.buynow') }}',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            data: { otp: otp },
            success: function(response) {
                if (response.status === 'success') {
                    $('#otp-message').html('<div class="order-notification"><i class="fa-solid fa-check-circle"></i> OTP verified!</div>');
                    closeOtpModal();
                    if (response.order_id) {
                        if (response.requires_redirect) {
                            $.ajax({
                                url: '{{ route("payment.initiate") }}',
                                type: 'POST',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                contentType: 'application/json',
                                data: JSON.stringify({ order_id: response.order_id, provider: response.provider }),
                                success: function(result) {
                                    window.location.href = result.redirect_url || ("{{ url('thank-you') }}/" + response.order_id);
                                },
                                error: function() { window.location.href = "{{ url('thank-you') }}/" + response.order_id; }
                            });
                        } else {
                            setTimeout(function() { window.location.href = "{{ url('thank-you') }}/" + response.order_id; }, 1000);
                        }
                    }
                } else {
                    $('#otp-message').html('<div class="order-notification error-message"><i class="fa-solid fa-circle-exclamation"></i> ' + (response.message || 'Invalid OTP') + '</div>');
                }
            },
            error: function(xhr) {
                $('#otp-message').html('<div class="order-notification error-message"><i class="fa-solid fa-circle-exclamation"></i> ' + ((xhr.responseJSON && xhr.responseJSON.message) || 'Invalid OTP, try again.') + '</div>');
            }
        });
    }

    $(document).on('click', '.resendotp', function() {
        const btn = $(this);
        btn.prop('disabled', true);
        let sec = 30;
        const iv = setInterval(function() {
            if (sec > 0) { btn.text('Resend in ' + sec + 's'); sec--; }
            else { clearInterval(iv); btn.prop('disabled', false); btn.text('Resend OTP'); }
        }, 1000);
        $.ajax({
            url: '{{ route('otp.resend') }}',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(r) {
                $('#otp-message').html('<div class="order-notification"><i class="fa-solid fa-check-circle"></i> ' + (r.message || 'OTP resent!') + '</div>');
            }
        });
    });

    // ── Quantity Controls ──
    function formatCurrency(value) {
        const num = parseFloat(value) || 0;
        return Number.isInteger(num) ? num.toString() : num.toFixed(2);
    }

    function syncQuantityTotals(context) {
        const unitPrice = context === 'combo'
            ? parseFloat($('[data-context="combo"]').closest('[data-unit-price]').data('unit-price') || 0)
            : parseFloat($('#single-order-row').data('unit-price')) || 0;
        const input = $(`.quantity-input[data-context="${context}"]`);
        if (!input.length) return;
        let qty = parseInt(input.val()) || 1;
        if (qty < 1) { qty = 1; input.val(qty); }
        if (context === 'combo') {
            $('input[name="quantity"]').val(qty);
            const total = unitPrice * qty;
            $('#combo-price').text(formatCurrency(total) + '৳');
            $('.combo-qty-label').text(qty);
        } else {
            $('input[name="pqty"]').val(qty);
            const total = unitPrice * qty;
            $('input[name="price"]').val(total);
            $('#single-price').text(formatCurrency(total) + '৳');
            $('.product-qty-label').text(qty);
        }
        updateOrderTotal();
    }

    $(document).on('click', '.quantity-btn', function() {
        const context = $(this).data('context');
        const action = $(this).data('action');
        const input = $(`.quantity-input[data-context="${context}"]`);
        if (!input.length) return;
        let qty = parseInt(input.val()) || 1;
        if (action === 'increase') qty++;
        else if (action === 'decrease' && qty > 1) qty--;
        input.val(qty);
        syncQuantityTotals(context);
    });

    // ── Shipping Settings ──
    window.shippingSettings = {
        flatRate: {{ $shippingSetting->flat_rate }},
        shippingOptions: @json($activeShippingOptions),
        freeShippingThreshold: {{ $shippingSetting->free_shipping_threshold }},
        specificRules: @json($specificShippingRules)
    };

    // ── Update Order Total ──
    function updateOrderTotal() {
        const isComboPurchase = $('input[name="is_combo_purchase"]').val() === '1';
        let price, qty, subtotal;

        if (isComboPurchase) {
            price = parseFloat($('input[name="price"]').val()) || 0;
            qty = parseInt($('input[name="quantity"]').val()) || 0;
            subtotal = price * qty;
        } else {
            price = parseFloat($('input[name="price"]').val()) || 0;
            subtotal = price;
        }

        $('#Subtotal').text(subtotal.toFixed(2) + '৳');

        const settings = window.shippingSettings;
        let shippingCost = settings.flatRate;
        const selectedArea = $('input[name="shipping_area"]:checked').val();

        if (selectedArea && settings.shippingOptions[selectedArea] && settings.shippingOptions[selectedArea].active) {
            shippingCost = parseFloat(settings.shippingOptions[selectedArea].cost);
        } else {
            const firstActive = Object.keys(settings.shippingOptions).find(k => settings.shippingOptions[k].active);
            if (firstActive) {
                shippingCost = parseFloat(settings.shippingOptions[firstActive].cost);
                $(`#ship_${firstActive}`).prop('checked', true);
                $(`label[for="ship_${firstActive}"]`).addClass('selected');
            }
        }

        let freeThreshold = settings.freeShippingThreshold;
        let fallbackCost = null;
        if (settings.specificRules) {
            settings.specificRules.forEach(function(rule) {
                if (rule.rule_type === 'free_shipping' && rule.free_shipping_threshold) {
                    freeThreshold = rule.free_shipping_threshold;
                    fallbackCost = rule.rule_value;
                }
            });
        }

        const shippingEl = document.getElementById('flat-rate');
        if (subtotal >= freeThreshold) {
            shippingCost = 0;
            shippingEl.innerHTML = '<span class="free">🎉 Free</span>';
        } else if (fallbackCost !== null) {
            shippingCost = parseFloat(fallbackCost);
            shippingEl.textContent = shippingCost.toFixed(2) + '৳';
        } else {
            shippingEl.textContent = shippingCost.toFixed(2) + '৳';
        }
        $('input[name="shipping"]').val(shippingCost);

        // Payment charges
        const paymentMethod = $('input[name="payment_method"]:checked').val();
        let bkash = 0, nagad = 0, rocket = 0;
        $('#bkashChargeRow, #nagadChargeRow, #rocketChargeRow').hide();

        const beforeCharge = subtotal + shippingCost;
        if (paymentMethod === 'bkash') {
            bkash = Math.ceil(beforeCharge / 100) * (100 * 0.018);
            $('#bkashChargeDisplay').text(bkash.toFixed(2));
            $('#bkashChargeRow').show();
            $('#bkashAmount, #stepAmount').text((beforeCharge + bkash).toFixed(2));
        } else if (paymentMethod === 'nagad') {
            nagad = Math.ceil(beforeCharge / 100) * (100 * 0.015);
            $('#nagadChargeDisplay').text(nagad.toFixed(2));
            $('#nagadChargeRow').show();
            $('#nagadAmount, #nagadStepAmount').text((beforeCharge + nagad).toFixed(2));
        } else if (paymentMethod === 'rocket') {
            rocket = Math.ceil(beforeCharge / 100) * (100 * 0.018);
            $('#rocketChargeDisplay').text(rocket.toFixed(2));
            $('#rocketChargeRow').show();
            $('#rocketAmount, #rocketStepAmount').text((beforeCharge + rocket).toFixed(2));
        }

        const total = subtotal + shippingCost + bkash + nagad + rocket;
        $('.order-total').text(total.toFixed(2) + '৳');
    }

    // ── Copy Number ──
    function copyPaymentNumber(className) {
        const number = document.querySelector('.' + className).textContent.trim();
        navigator.clipboard.writeText(number).then(function() {
            const toast = document.getElementById('copy-toast');
            toast.style.opacity = '1';
            setTimeout(function() { toast.style.opacity = '0'; }, 1800);
        });
    }
</script>

{{-- Incomplete Order & Fraud Protection --}}
<script src="{{ asset('js/incomplete-order.js') }}"></script>
<script src="{{ asset('js/fraud-protection.js') }}"></script>
<script>
    const fraudProtectionSettings = @json(app(\App\Services\FraudProtectionService::class)->getFrontendSettings());
    const fraudProtection = new FraudProtection(fraudProtectionSettings);
    window.fraudProtectionInstance = fraudProtection;
</script>
@endsection
