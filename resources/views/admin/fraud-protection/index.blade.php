@extends('layouts.master')

@section('title', 'Fraud Protection Settings')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #fraud-shield-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        min-height: 100vh;
    }

    /* ── PAGE HEADER ──────────────────────────────── */
    .page-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
        border-radius: 20px;
        padding: 32px 36px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    .page-hero::after {
        content: '';
        position: absolute;
        bottom: -80px; right: 100px;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.03);
        border-radius: 50%;
    }
    .page-hero h1 {
        font-size: 1.9rem;
        font-weight: 800;
        color: #fff;
        margin: 0 0 6px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .page-hero h1 .hero-icon {
        width: 48px; height: 48px;
        background: rgba(255,255,255,0.15);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        backdrop-filter: blur(4px);
        flex-shrink: 0;
    }
    .page-hero p { color: rgba(255,255,255,0.7); font-size: 0.95rem; margin: 0; }

    /* ── NAV TABS ─────────────────────────────────── */
    .nav-tabs-overhaul {
        background: #ffffff;
        padding: 6px;
        border-radius: 16px;
        gap: 4px;
        border: 1px solid #e2e8f0;
        margin-bottom: 28px;
        flex-wrap: wrap;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .nav-tabs-overhaul .nav-item { flex: 1; }
    .nav-tabs-overhaul .nav-link {
        width: 100%;
        border-radius: 12px;
        color: #64748b;
        font-weight: 600;
        font-size: 0.88rem;
        padding: 11px 16px;
        text-align: center;
        transition: all 0.22s ease;
        border: none !important;
        background: transparent;
        white-space: nowrap;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
    }
    .nav-tabs-overhaul .nav-link:hover {
        color: #1e293b;
        background: #f8fafc;
    }
    .nav-tabs-overhaul .nav-link.active {
        background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(99,102,241,0.35);
    }
    .nav-tabs-overhaul .nav-link.active-duplicate { background: linear-gradient(135deg, #2563eb, #3b82f6) !important; box-shadow: 0 4px 14px rgba(59,130,246,0.35); }
    .nav-tabs-overhaul .nav-link.active-fake      { background: linear-gradient(135deg, #d97706, #f59e0b) !important; box-shadow: 0 4px 14px rgba(245,158,11,0.35); }
    .nav-tabs-overhaul .nav-link.active-fraud     { background: linear-gradient(135deg, #dc2626, #ef4444) !important; box-shadow: 0 4px 14px rgba(239,68,68,0.35); }
    .nav-tabs-overhaul .nav-link.active-alerts    { background: linear-gradient(135deg, #059669, #10b981) !important; box-shadow: 0 4px 14px rgba(16,185,129,0.35); }

    /* ── SECTION HEADER BANDS ──────────────────────── */
    .section-band {
        border-radius: 14px 14px 0 0;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .section-band .band-icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        color: #fff;
        flex-shrink: 0;
        backdrop-filter: blur(4px);
    }
    .section-band h2 {
        font-size: 1.05rem;
        font-weight: 800;
        color: #fff;
        margin: 0 0 3px;
        line-height: 1;
    }
    .section-band span {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.75);
        font-weight: 500;
    }
    .band-blue   { background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%); }
    .band-amber  { background: linear-gradient(135deg, #b45309 0%, #f59e0b 100%); }
    .band-red    { background: linear-gradient(135deg, #b91c1c 0%, #ef4444 100%); }
    .band-green  { background: linear-gradient(135deg, #047857 0%, #10b981 100%); }
    .band-indigo { background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%); }
    .band-slate  { background: linear-gradient(135deg, #334155 0%, #64748b 100%); }

    /* ── MODULE WRAPPER ──────────────────────────── */
    .module-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        transition: box-shadow 0.2s ease;
    }
    .module-wrap:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.07); }
    .module-body { padding: 24px; }

    /* ── MASTER TOGGLE ROW ──────────────────────── */
    .master-toggle-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px 22px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
    }
    .master-toggle-row .mtl { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
    .master-toggle-row .mts { font-size: 0.82rem; color: #64748b; line-height: 1.55; }

    /* ── CUSTOM TOGGLE ──────────────────────────── */
    .toggle-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .toggle-wrap input[type=checkbox].premium-toggle {
        appearance: none;
        -webkit-appearance: none;
        width: 46px;
        height: 24px;
        border-radius: 99px;
        background: #cbd5e1;
        cursor: pointer;
        position: relative;
        transition: background 0.22s ease;
        flex-shrink: 0;
        border: none;
        outline: none;
    }
    .toggle-wrap input[type=checkbox].premium-toggle::after {
        content: '';
        position: absolute;
        top: 3px; left: 3px;
        width: 18px; height: 18px;
        background: #fff;
        border-radius: 50%;
        transition: left 0.22s ease;
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }
    .toggle-wrap input[type=checkbox].premium-toggle:checked { background: #6366f1; }
    .toggle-wrap input[type=checkbox].premium-toggle:checked::after { left: 25px; }
    .toggle-wrap label { font-size: 0.875rem; font-weight: 600; color: #334155; cursor: pointer; }

    /* ── DIVIDER ────────────────────────────────── */
    .premium-divider {
        border: none;
        border-top: 1px solid #f1f5f9;
        margin: 20px 0;
    }

    /* ── OPTION GRID ─────────────────────────────── */
    .option-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .option-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }
    @media (max-width: 768px) {
        .option-grid-2, .option-grid-3 { grid-template-columns: 1fr; }
        .nav-tabs-overhaul .nav-item { flex: none; width: 100%; }
    }

    /* ── OPTION CARD ─────────────────────────────── */
    .option-card {
        background: #fafafa;
        border: 1.5px solid #e8edf2;
        border-radius: 14px;
        padding: 20px;
        transition: all 0.2s ease;
        position: relative;
    }
    .option-card:hover {
        border-color: #c7d2fe;
        background: #fdfcff;
        transform: translateY(-1px);
    }
    .option-card-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .option-card-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    .icon-blue   { background: #eff6ff; color: #2563eb; }
    .icon-amber  { background: #fffbeb; color: #d97706; }
    .icon-red    { background: #fef2f2; color: #dc2626; }
    .icon-green  { background: #f0fdf4; color: #16a34a; }
    .icon-purple { background: #f5f3ff; color: #7c3aed; }
    .icon-slate  { background: #f1f5f9; color: #475569; }
    .icon-cyan   { background: #ecfeff; color: #0891b2; }

    /* ── FORM ELEMENTS ───────────────────────────── */
    .field-group { margin-bottom: 14px; }
    .field-group:last-child { margin-bottom: 0; }
    .field-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 6px;
        display: block;
    }
    .field-input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.9rem;
        color: #0f172a;
        background: #ffffff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: all 0.2s ease;
        outline: none;
    }
    .field-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    textarea.field-input { resize: vertical; }
    .field-hint {
        font-size: 0.76rem;
        color: #94a3b8;
        margin-top: 5px;
        line-height: 1.5;
    }

    /* ── CHECKBOX (for multi-check, e.g. phone lengths) ── */
    .check-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.82rem;
        font-weight: 600;
        color: #334155;
    }
    .check-pill input { display: none; }
    .check-pill:has(input:checked) {
        background: #ede9fe;
        border-color: #a78bfa;
        color: #5b21b6;
    }
    .check-pill .dot {
        width: 14px; height: 14px;
        border-radius: 4px;
        border: 2px solid #94a3b8;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .check-pill:has(input:checked) .dot {
        background: #7c3aed;
        border-color: #7c3aed;
    }
    .check-pill:has(input:checked) .dot::after {
        content: '✓';
        font-size: 9px;
        color: white;
        font-weight: 900;
    }

    /* ── INFO BOX ─────────────────────────────────── */
    .info-box {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #bae6fd;
        border-radius: 12px;
        padding: 16px 18px;
        margin-top: 14px;
    }
    .info-box-title {
        font-size: 0.8rem;
        font-weight: 700;
        color: #0369a1;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .pattern-col strong { font-size: 0.77rem; color: #0369a1; }
    .pattern-col ul { margin: 4px 0 0; padding-left: 14px; list-style: none; }
    .pattern-col ul li {
        font-size: 0.75rem;
        color: #0c4a6e;
        padding: 1px 0;
        font-family: 'Courier New', monospace;
        font-weight: 600;
    }
    .pattern-col ul li::before { content: '→ '; color: #38bdf8; }

    /* ── STATUS CHIP ─────────────────────────────── */
    .count-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #ede9fe;
        color: #5b21b6;
        border-radius: 99px;
        padding: 3px 10px;
        font-size: 0.73rem;
        font-weight: 700;
        letter-spacing: 0.03em;
    }

    /* ── MESSAGE AREA ─────────────────────────────── */
    .message-area {
        background: linear-gradient(135deg, #fafafa 0%, #f8fafc 100%);
        border: 1.5px solid #e8edf2;
        border-radius: 14px;
        padding: 20px;
    }
    .message-area-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ── SAVE FOOTER ─────────────────────────────── */
    .save-footer {
        position: sticky;
        bottom: 0;
        background: rgba(241, 245, 249, 0.95);
        backdrop-filter: blur(12px);
        border-top: 1.5px solid #e2e8f0;
        padding: 18px 0;
        margin-top: 32px;
        z-index: 10;
    }
    .btn-save {
        background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
        color: #fff;
        font-weight: 800;
        font-size: 0.95rem;
        padding: 14px 32px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 18px rgba(99,102,241,0.3);
        transition: all 0.22s ease;
    }
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(99,102,241,0.4);
    }
    .btn-logs {
        font-weight: 700;
        font-size: 0.9rem;
        padding: 13px 24px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        background: #fff;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-logs:hover {
        border-color: #a78bfa;
        color: #5b21b6;
        background: #f5f3ff;
        text-decoration: none;
    }

    /* ── ALERT OVERRIDE ───────────────────────────── */
    .alert-success-premium {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 1px solid #86efac;
        color: #15803d;
        border-radius: 12px;
        padding: 14px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
</style>
@endsection

@section('content')
<div id="fraud-shield-page" class="container-fluid px-4 py-4">



    @if(session('success'))
        <div class="alert-success-premium">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.fraud-protection.update') }}" method="POST">
        @csrf

        <!-- ── TAB NAVIGATION ───────────────────────────────── -->
        <ul class="nav d-flex nav-tabs-overhaul" id="shieldTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-dup" data-bs-toggle="tab" data-bs-target="#pane-dup" type="button" role="tab"
                    onclick="setTabActive(this,'#2563eb','#3b82f6')">
                    <i class="fas fa-copy"></i> Duplicate Orders
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-fake" data-bs-toggle="tab" data-bs-target="#pane-fake" type="button" role="tab"
                    onclick="setTabActive(this,'#b45309','#f59e0b')">
                    <i class="fas fa-user-secret"></i> Fake Detection
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-fraud" data-bs-toggle="tab" data-bs-target="#pane-fraud" type="button" role="tab"
                    onclick="setTabActive(this,'#b91c1c','#ef4444')">
                    <i class="fas fa-exclamation-triangle"></i> Fraud & Scam
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-alerts" data-bs-toggle="tab" data-bs-target="#pane-alerts" type="button" role="tab"
                    onclick="setTabActive(this,'#047857','#10b981')">
                    <i class="fas fa-bell"></i> Alerts & Logs
                </button>
            </li>
        </ul>

        <div class="tab-content" id="shieldTabsContent">

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- TAB 1: DUPLICATE ORDER PROTECTION --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <div class="tab-pane fade show active" id="pane-dup" role="tabpanel">

                <!-- Master Control -->
                <div class="module-wrap">
                    <div class="section-band band-blue">
                        <div class="band-icon"><i class="fas fa-copy"></i></div>
                        <div>
                            <h2>Duplicate Order Protection</h2>
                            <span>Smart interval and pending order controls to stop repeat submissions</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="master-toggle-row">
                            <div>
                                <div class="mtl">Enable Duplicate Order Protection</div>
                                <div class="mts">Master switch. When disabled, all order interval and pending order restrictions are bypassed regardless of individual settings.</div>
                            </div>
                            <div class="toggle-wrap" style="flex-shrink:0; margin-top:2px;">
                                <input class="premium-toggle" type="checkbox" id="duplicate_protection_enabled"
                                       name="duplicate_protection_enabled" value="1"
                                       {{ $fraudSettings->duplicate_protection_enabled ? 'checked' : '' }}>
                                <label for="duplicate_protection_enabled">Active</label>
                            </div>
                        </div>

                        <hr class="premium-divider">

                        <div class="option-grid-2">
                            <!-- Interval Restriction -->
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-blue"><i class="fas fa-clock"></i></span>
                                    Order Interval Restriction
                                </div>
                                <div class="toggle-wrap mb-3">
                                    <input class="premium-toggle" type="checkbox" id="order_interval_enabled"
                                           name="order_interval_enabled" value="1"
                                           {{ $fraudSettings->order_interval_enabled ? 'checked' : '' }}>
                                    <label for="order_interval_enabled">Enable interval restriction</label>
                                </div>
                                <p class="field-hint mb-3">Prevents new orders from a customer within the specified window after their last order.</p>
                                <div class="field-group">
                                    <span class="field-label">Wait Time (Minutes)</span>
                                    <input type="number" class="field-input" name="order_interval_minutes"
                                           value="{{ $fraudSettings->order_interval_minutes }}" min="1" placeholder="60">
                                    <div class="field-hint">Recommended: 60 mins. Higher values reduce risk but may frustrate genuine customers.</div>
                                </div>
                            </div>

                            <!-- Pending Order Block -->
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-amber"><i class="fas fa-hourglass-half"></i></span>
                                    Pending Order Block
                                </div>
                                <div class="toggle-wrap mb-3">
                                    <input class="premium-toggle" type="checkbox" id="pending_order_restriction_enabled"
                                           name="pending_order_restriction_enabled" value="1"
                                           {{ $fraudSettings->pending_order_restriction_enabled ? 'checked' : '' }}>
                                    <label for="pending_order_restriction_enabled">Block new orders while pending</label>
                                </div>
                                <p class="field-hint">Stops customers from placing new orders while they have unprocessed orders in the queue — reduces duplicate confusion and support load.</p>
                            </div>
                        </div>

                        <hr class="premium-divider">

                        <!-- Customer Message -->
                        <div class="message-area">
                            <div class="message-area-label">
                                <i class="fas fa-comment-dots" style="color:#6366f1;"></i> Customer-Facing Block Message
                            </div>
                            <textarea class="field-input" name="duplicate_order_message" rows="2"
                                      placeholder="আপনি সম্প্রতি একটি অর্ডার করেছেন। অনুগ্রহ করে কিছুক্ষণ পরে আবার চেষ্টা করুন।">{{ $fraudSettings->duplicate_order_message }}</textarea>
                            <div class="field-hint">Shown when an order is blocked by duplicate protection. Keep it polite and clear.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- TAB 2: FAKE ORDER PROTECTION --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="pane-fake" role="tabpanel">

                <!-- Master -->
                <div class="module-wrap">
                    <div class="section-band band-amber">
                        <div class="band-icon"><i class="fas fa-user-secret"></i></div>
                        <div>
                            <h2>Fake Order Detection</h2>
                            <span>AI-powered pattern recognition to identify and block suspicious data</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="master-toggle-row">
                            <div>
                                <div class="mtl">Enable Fake Order Detection</div>
                                <div class="mts">Master switch for all fake data detection features. Uses heuristics and pattern analysis to catch fake phone numbers, names, and addresses before orders go through.</div>
                            </div>
                            <div class="toggle-wrap" style="flex-shrink:0; margin-top:2px;">
                                <input class="premium-toggle" type="checkbox" id="fake_protection_enabled"
                                       name="fake_protection_enabled" value="1"
                                       {{ $fraudSettings->fake_protection_enabled ? 'checked' : '' }}>
                                <label for="fake_protection_enabled">Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Phone + Name -->
                <div class="module-wrap">
                    <div class="section-band band-amber" style="background: linear-gradient(135deg,#92400e 0%,#d97706 100%);">
                        <div class="band-icon"><i class="fas fa-id-card"></i></div>
                        <div>
                            <h2>Contact & Identity Validation</h2>
                            <span>Phone number and customer name rules</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="option-grid-2">
                            <!-- Phone -->
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-green"><i class="fas fa-phone"></i></span>
                                    Phone Number Validation
                                </div>
                                <div class="toggle-wrap mb-3">
                                    <input class="premium-toggle" type="checkbox" id="phone_validation_enabled"
                                           name="phone_validation_enabled" value="1"
                                           {{ $fraudSettings->phone_validation_enabled ? 'checked' : '' }}>
                                    <label for="phone_validation_enabled">Enable phone validation</label>
                                </div>
                                <p class="field-hint mb-3">Validates against standard length and format requirements to block obviously fake numbers.</p>

                                <div class="field-group">
                                    <span class="field-label">Allowed Phone Lengths</span>
                                    @php $allowedLengths = $fraudSettings->allowed_phone_lengths ?? [11, 12, 14]; @endphp
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach([11,12,14] as $len)
                                            <label class="check-pill">
                                                <input type="checkbox" name="allowed_phone_lengths[]" value="{{ $len }}"
                                                       {{ in_array($len, $allowedLengths) ? 'checked' : '' }}>
                                                <span class="dot"></span>
                                                {{ $len }} digits
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <hr class="premium-divider">
                                <div class="toggle-wrap mb-2">
                                    <input class="premium-toggle" type="checkbox" id="phone_whitelist_enabled"
                                           name="phone_whitelist_enabled" value="1"
                                           {{ $fraudSettings->phone_whitelist_enabled ? 'checked' : '' }}>
                                    <label for="phone_whitelist_enabled"><strong>Enable Phone Whitelist</strong></label>
                                </div>
                                <div class="field-hint mb-3">Only accept numbers matching pre-approved BD mobile operator patterns.</div>

                                <div class="info-box">
                                    <div class="info-box-title"><i class="fas fa-info-circle"></i> Approved Pattern Groups</div>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <div class="pattern-col">
                                            <strong>Local</strong>
                                            <ul>
                                                <li>011/013/014/015xxxxxxxx</li>
                                                <li>016/017/018/019xxxxxxxx</li>
                                            </ul>
                                        </div>
                                        <div class="pattern-col">
                                            <strong>Intl. (88)</strong>
                                            <ul>
                                                <li>88013–88019xxxxxxxx</li>
                                            </ul>
                                        </div>
                                        <div class="pattern-col">
                                            <strong>Plus (+88)</strong>
                                            <ul>
                                                <li>+88013–+88019xxxxxxxx</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Name -->
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-cyan"><i class="fas fa-id-badge"></i></span>
                                    Customer Name Validation
                                </div>
                                <div class="toggle-wrap mb-3">
                                    <input class="premium-toggle" type="checkbox" id="name_validation_enabled"
                                           name="name_validation_enabled" value="1"
                                           {{ $fraudSettings->name_validation_enabled ? 'checked' : '' }}>
                                    <label for="name_validation_enabled">Enable name validation</label>
                                </div>
                                <p class="field-hint mb-3">Checks that customer names are legitimate — not gibberish, pure numbers, or too short to be real.</p>
                                <div class="d-flex gap-2 mb-3">
                                    <div class="field-group" style="flex:1;">
                                        <span class="field-label">Min Length</span>
                                        <input type="number" class="field-input" name="name_min_length"
                                               value="{{ $fraudSettings->name_min_length }}" min="1">
                                        <div class="field-hint">Min characters</div>
                                    </div>
                                    <div class="field-group" style="flex:1;">
                                        <span class="field-label">Max Length</span>
                                        <input type="number" class="field-input" name="name_max_length"
                                               value="{{ $fraudSettings->name_max_length }}" min="1">
                                        <div class="field-hint">Max characters</div>
                                    </div>
                                </div>
                                <div class="toggle-wrap">
                                    <input class="premium-toggle" type="checkbox" id="name_disallow_numeric"
                                           name="name_disallow_numeric" value="1"
                                           {{ $fraudSettings->name_disallow_numeric ? 'checked' : '' }}>
                                    <label for="name_disallow_numeric">Disallow numbers in name</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pattern Block Rules -->
                <div class="module-wrap">
                    <div class="section-band" style="background:linear-gradient(135deg,#7c2d12 0%,#ea580c 100%);">
                        <div class="band-icon"><i class="fas fa-ban"></i></div>
                        <div>
                            <h2>Automatic Pattern Blocking</h2>
                            <span>Block well-known fake data patterns without custom regex</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="option-grid-3">
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-red"><i class="fas fa-ban"></i></span>
                                    Sequential Numbers
                                </div>
                                <div class="toggle-wrap mb-2">
                                    <input class="premium-toggle" type="checkbox" id="block_sequential_numbers"
                                           name="block_sequential_numbers" value="1"
                                           {{ $fraudSettings->block_sequential_numbers ? 'checked' : '' }}>
                                    <label for="block_sequential_numbers">Enable</label>
                                </div>
                                <div class="field-hint">Blocks repeated-digit phones like <code style="background:#f1f5f9;border-radius:4px;padding:1px 5px;font-size:0.75rem;">0000000000</code> or <code style="background:#f1f5f9;border-radius:4px;padding:1px 5px;font-size:0.75rem;">1111111111</code></div>
                            </div>
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-amber"><i class="fas fa-user-slash"></i></span>
                                    Repeated Names
                                </div>
                                <div class="toggle-wrap mb-2">
                                    <input class="premium-toggle" type="checkbox" id="block_repeated_names"
                                           name="block_repeated_names" value="1"
                                           {{ $fraudSettings->block_repeated_names ? 'checked' : '' }}>
                                    <label for="block_repeated_names">Enable</label>
                                </div>
                                <div class="field-hint">Blocks names with repeated characters like <code style="background:#f1f5f9;border-radius:4px;padding:1px 5px;font-size:0.75rem;">aaaaa</code> or <code style="background:#f1f5f9;border-radius:4px;padding:1px 5px;font-size:0.75rem;">bbbbb</code></div>
                            </div>
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-slate"><i class="fas fa-keyboard"></i></span>
                                    Gibberish Names
                                </div>
                                <div class="toggle-wrap mb-2">
                                    <input class="premium-toggle" type="checkbox" id="block_gibberish_names"
                                           name="block_gibberish_names" value="1"
                                           {{ $fraudSettings->block_gibberish_names ? 'checked' : '' }}>
                                    <label for="block_gibberish_names">Enable</label>
                                </div>
                                <div class="field-hint">Blocks keyboard mash entries like <code style="background:#f1f5f9;border-radius:4px;padding:1px 5px;font-size:0.75rem;">asdfgh</code> or <code style="background:#f1f5f9;border-radius:4px;padding:1px 5px;font-size:0.75rem;">qwerty</code></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Custom patterns & address -->
                <div class="module-wrap">
                    <div class="section-band" style="background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 100%);">
                        <div class="band-icon"><i class="fas fa-code"></i></div>
                        <div>
                            <h2>Custom Regex Patterns & Address Rules</h2>
                            <span>Define advanced rules for phone, name, and delivery address filtering</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="option-grid-2">
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-purple"><i class="fas fa-code"></i></span>
                                    Custom Pattern Blacklist
                                </div>
                                <div class="toggle-wrap mb-3">
                                    <input class="premium-toggle" type="checkbox" id="custom_pattern_enabled"
                                           name="custom_pattern_enabled" value="1"
                                           {{ $fraudSettings->custom_pattern_enabled ? 'checked' : '' }}>
                                    <label for="custom_pattern_enabled">Enable custom patterns</label>
                                </div>
                                <p class="field-hint mb-3">Define custom regex to block specific phone number formats or name patterns that aren't covered automatically.</p>
                                <div class="field-group">
                                    <span class="field-label">Blocked Phone Patterns</span>
                                    <textarea class="field-input" name="blocked_phone_patterns_text" rows="4"
                                              placeholder="^0170.*&#10;^0180.*">{{ $fraudSettings->blocked_phone_patterns ? implode("\n", $fraudSettings->blocked_phone_patterns) : '' }}</textarea>
                                    <div class="field-hint">One regex per line. e.g. <code style="background:#f1f5f9;border-radius:4px;padding:1px 5px;">^0170.*</code> blocks all numbers starting with 0170</div>
                                </div>
                            </div>
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-green"><i class="fas fa-map-marker-alt"></i></span>
                                    Address & Name Filters
                                </div>
                                <div class="toggle-wrap mb-3">
                                    <input class="premium-toggle" type="checkbox" id="address_validation_enabled"
                                           name="address_validation_enabled" value="1"
                                           {{ $fraudSettings->address_validation_enabled ? 'checked' : '' }}>
                                    <label for="address_validation_enabled">Enable address validation</label>
                                </div>
                                <div class="field-group">
                                    <span class="field-label">Blocked Name Patterns</span>
                                    <textarea class="field-input" name="blocked_name_patterns_text" rows="2"
                                              placeholder="test&#10;demo&#10;fake">{{ $fraudSettings->blocked_name_patterns ? implode("\n", $fraudSettings->blocked_name_patterns) : '' }}</textarea>
                                </div>
                                <div class="field-group">
                                    <span class="field-label">Blocked Address Patterns</span>
                                    <textarea class="field-input" name="blocked_address_patterns_text" rows="2"
                                              placeholder="test address&#10;fake location">{{ $fraudSettings->blocked_address_patterns ? implode("\n", $fraudSettings->blocked_address_patterns) : '' }}</textarea>
                                </div>
                                <div class="field-group">
                                    <span class="field-label">Minimum Address Length</span>
                                    <input type="number" class="field-input" name="address_min_length"
                                           value="{{ $fraudSettings->address_min_length }}" min="1">
                                    <div class="field-hint">Filter out incomplete or too-short address strings.</div>
                                </div>
                            </div>
                        </div>

                        <hr class="premium-divider">

                        <div class="message-area">
                            <div class="message-area-label">
                                <i class="fas fa-comment-dots" style="color:#d97706;"></i> Customer-Facing Block Message (Fake Data)
                            </div>
                            <textarea class="field-input" name="fake_data_message" rows="2"
                                      placeholder="অবৈধ তথ্য সনাক্ত করা হয়েছে। অনুগ্রহ করে সঠিক তথ্য প্রদান করুন।">{{ $fraudSettings->fake_data_message }}</textarea>
                            <div class="field-hint">Shown when a fake data pattern is detected. Keep it polite and helpful.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- TAB 3: FRAUD & SCAM PROTECTION --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="pane-fraud" role="tabpanel">

                <!-- Master -->
                <div class="module-wrap">
                    <div class="section-band band-red">
                        <div class="band-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <div>
                            <h2>Fraud & Scam Shield</h2>
                            <span>Advanced fraud prevention with blacklisting and courier history analysis</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="master-toggle-row">
                            <div>
                                <div class="mtl">Enable Fraud & Scam Shield</div>
                                <div class="mts">Activates blacklists, IP rate limiting, and courier delivery history analysis to catch and block suspicious orders before they process.</div>
                            </div>
                            <div class="toggle-wrap" style="flex-shrink:0; margin-top:2px;">
                                <input class="premium-toggle" type="checkbox" id="fraud_protection_enabled"
                                       name="fraud_protection_enabled" value="1"
                                       {{ $fraudSettings->fraud_protection_enabled ? 'checked' : '' }}>
                                <label for="fraud_protection_enabled">Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Blacklist -->
                <div class="module-wrap">
                    <div class="section-band" style="background:linear-gradient(135deg,#7f1d1d 0%,#dc2626 100%);">
                        <div class="band-icon"><i class="fas fa-ban"></i></div>
                        <div>
                            <h2>Blacklist Management</h2>
                            <span>Permanently block phones & IP addresses flagged as fraudulent</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="toggle-wrap mb-3">
                            <input class="premium-toggle" type="checkbox" id="blacklist_enabled"
                                   name="blacklist_enabled" value="1"
                                   {{ $fraudSettings->blacklist_enabled ? 'checked' : '' }}>
                            <label for="blacklist_enabled"><strong>Enable Blacklist</strong></label>
                        </div>
                        <p class="field-hint mb-4">Phone numbers and IP addresses in the lists below will be permanently blocked from placing any order.</p>
                        <div class="option-grid-2">
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-red"><i class="fas fa-phone-slash"></i></span>
                                    Blacklisted Phone Numbers
                                    <span class="count-chip ms-auto">{{ count($fraudSettings->blacklisted_phones ?? []) }} listed</span>
                                </div>
                                <textarea class="field-input" name="blacklisted_phones_text" rows="6"
                                          placeholder="01700000000&#10;01711111111">{{ $fraudSettings->blacklisted_phones ? implode("\n", $fraudSettings->blacklisted_phones) : '' }}</textarea>
                                <div class="field-hint">One number per line. Include country code if needed.</div>
                            </div>
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-red"><i class="fas fa-globe"></i></span>
                                    Blacklisted IP Addresses
                                    <span class="count-chip ms-auto">{{ count($fraudSettings->blacklisted_ips ?? []) }} listed</span>
                                </div>
                                <textarea class="field-input" name="blacklisted_ips_text" rows="6"
                                          placeholder="192.168.1.1&#10;10.0.0.1">{{ $fraudSettings->blacklisted_ips ? implode("\n", $fraudSettings->blacklisted_ips) : '' }}</textarea>
                                <div class="field-hint">One IP per line. Use with caution — may block legitimate users on shared connections.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rate Limiting & Courier -->
                <div class="module-wrap">
                    <div class="section-band" style="background:linear-gradient(135deg,#1e3a8a 0%,#3b82f6 100%);">
                        <div class="band-icon"><i class="fas fa-tachometer-alt"></i></div>
                        <div>
                            <h2>Rate Limiting & Courier Analysis</h2>
                            <span>IP-based throttling and historical delivery performance screening</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="option-grid-2">
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-blue"><i class="fas fa-network-wired"></i></span>
                                    IP Rate Limiting
                                </div>
                                <div class="toggle-wrap mb-3">
                                    <input class="premium-toggle" type="checkbox" id="ip_rate_limiting_enabled"
                                           name="ip_rate_limiting_enabled" value="1"
                                           {{ $fraudSettings->ip_rate_limiting_enabled ? 'checked' : '' }}>
                                    <label for="ip_rate_limiting_enabled">Enable IP rate limiting</label>
                                </div>
                                <p class="field-hint mb-3">Limits the number of orders placed from the same IP address within one hour to prevent coordinated bulk fraud.</p>
                                <div class="field-group">
                                    <span class="field-label">Max Orders Per IP Per Hour</span>
                                    <input type="number" class="field-input" name="ip_max_orders_per_hour"
                                           value="{{ $fraudSettings->ip_max_orders_per_hour }}" min="1" placeholder="5">
                                    <div class="field-hint">Recommended: 5 orders/hr. Higher values allow more throughput but reduce fraud catch rate.</div>
                                </div>
                            </div>
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-amber"><i class="fas fa-truck"></i></span>
                                    Courier Success Rate Check
                                </div>
                                <div class="toggle-wrap mb-3">
                                    <input class="premium-toggle" type="checkbox" id="courier_success_check_enabled"
                                           name="courier_success_check_enabled" value="1"
                                           {{ $fraudSettings->courier_success_check_enabled ? 'checked' : '' }}>
                                    <label for="courier_success_check_enabled">Enable delivery history check</label>
                                </div>
                                <p class="field-hint mb-3">Screens customer delivery track records — block anyone with a history of failed or returned parcels beyond your threshold.</p>
                                <div class="d-flex gap-2">
                                    <div class="field-group" style="flex:1;">
                                        <span class="field-label">Min Success Rate (%)</span>
                                        <input type="number" class="field-input" name="min_success_rate"
                                               value="{{ $fraudSettings->min_success_rate }}" min="0" max="100" step="0.01" placeholder="20.00">
                                        <div class="field-hint">Block if success is below this</div>
                                    </div>
                                    <div class="field-group" style="flex:1;">
                                        <span class="field-label">Max Bad Rate (%)</span>
                                        <input type="number" class="field-input" name="max_bad_history_rate"
                                               value="{{ $fraudSettings->max_bad_history_rate }}" min="0" max="100" step="0.01" placeholder="80.00">
                                        <div class="field-hint">Block if bad history exceeds</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fraud messages -->
                <div class="module-wrap">
                    <div class="section-band band-slate">
                        <div class="band-icon"><i class="fas fa-comment-exclamation"></i></div>
                        <div>
                            <h2>Customer Block Messages</h2>
                            <span>What customers see when their order is declined by this module</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="option-grid-2">
                            <div class="message-area">
                                <div class="message-area-label"><i class="fas fa-comment-dots" style="color:#ef4444;"></i> Fraud Detected Message</div>
                                <textarea class="field-input" name="fraud_detected_message" rows="3"
                                          placeholder="নিরাপত্তা কারণে আপনার অর্ডার ব্লক করা হয়েছে। সহায়তার জন্য আমাদের সাথে যোগাযোগ করুন।">{{ $fraudSettings->fraud_detected_message }}</textarea>
                                <div class="field-hint">Shown when blocked via rate limiting or courier analysis.</div>
                            </div>
                            <div class="message-area">
                                <div class="message-area-label"><i class="fas fa-comment-dots" style="color:#ef4444;"></i> Blacklist Block Message</div>
                                <textarea class="field-input" name="blacklist_message" rows="3"
                                          placeholder="আপনার অ্যাকাউন্ট সাময়িকভাবে স্থগিত করা হয়েছে। সহায়তার জন্য আমাদের সাথে যোগাযোগ করুন।">{{ $fraudSettings->blacklist_message }}</textarea>
                                <div class="field-hint">Shown when a phone or IP is on the blacklist.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- TAB 4: ALERTS & LOGGING --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <div class="tab-pane fade" id="pane-alerts" role="tabpanel">

                <div class="module-wrap">
                    <div class="section-band band-green">
                        <div class="band-icon"><i class="fas fa-bell"></i></div>
                        <div>
                            <h2>Alerts & Logging</h2>
                            <span>Configure audit logging and real-time admin notifications</span>
                        </div>
                    </div>
                    <div class="module-body">
                        <div class="option-grid-2">
                            <!-- Logging -->
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-blue"><i class="fas fa-file-alt"></i></span>
                                    Activity Logging
                                </div>
                                <div class="master-toggle-row">
                                    <div>
                                        <div class="mtl" style="font-size:0.9rem;">Log Blocked Attempts</div>
                                        <div class="mts">Save every blocked order attempt to the database for analysis, monitoring, and security audits.</div>
                                    </div>
                                    <div class="toggle-wrap" style="flex-shrink:0;">
                                        <input class="premium-toggle" type="checkbox" id="log_blocked_attempts"
                                               name="log_blocked_attempts" value="1"
                                               {{ $fraudSettings->log_blocked_attempts ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Alerts -->
                            <div class="option-card">
                                <div class="option-card-title">
                                    <span class="option-card-icon icon-green"><i class="fas fa-envelope"></i></span>
                                    Email Alerts
                                </div>
                                <div class="toggle-wrap mb-3">
                                    <input class="premium-toggle" type="checkbox" id="send_admin_alerts"
                                           name="send_admin_alerts" value="1"
                                           {{ $fraudSettings->send_admin_alerts ? 'checked' : '' }}>
                                    <label for="send_admin_alerts"><strong>Send admin email alerts</strong></label>
                                </div>
                                <p class="field-hint mb-3">Receive real-time email notifications whenever an order is blocked by any protection rule.</p>
                                <div class="field-group">
                                    <span class="field-label">Admin Alert Email</span>
                                    <input type="email" class="field-input" name="admin_alert_email"
                                           value="{{ $fraudSettings->admin_alert_email }}"
                                           placeholder="admin@example.com">
                                    <div class="field-hint">Fraud alert emails are sent to this address.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── SAVE FOOTER ──────────────────────────────── -->
        <div class="save-footer">
            <div class="d-flex align-items-center gap-3">
                <button type="submit" class="btn-save">
                    <i class="fas fa-shield-check"></i> Save All Protection Settings
                </button>
                <span class="field-hint m-0">Changes apply immediately to all new orders.</span>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Active tab color per section
    function setTabActive(el, c1, c2) {
        document.querySelectorAll('.nav-tabs-overhaul .nav-link').forEach(l => {
            l.style.background = '';
            l.style.boxShadow = '';
            l.style.color = '';
        });
    }
    document.addEventListener('shown.bs.tab', function(e) {
        const tab = e.target;
        const colors = {
            'tab-dup':    ['#1d4ed8','#3b82f6'],
            'tab-fake':   ['#b45309','#f59e0b'],
            'tab-fraud':  ['#b91c1c','#ef4444'],
            'tab-alerts': ['#047857','#10b981'],
        };
        const id = tab.getAttribute('id');
        if (colors[id]) {
            tab.style.background = `linear-gradient(135deg, ${colors[id][0]} 0%, ${colors[id][1]} 100%)`;
            tab.style.color = '#fff';
            tab.style.boxShadow = `0 4px 14px ${colors[id][1]}55`;
        }
    });

    // Trigger color on initial active tab
    document.addEventListener('DOMContentLoaded', function() {
        const firstTab = document.getElementById('tab-dup');
        if (firstTab) {
            firstTab.style.background = 'linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%)';
            firstTab.style.color = '#fff';
            firstTab.style.boxShadow = '0 4px 14px #3b82f655';
        }
    });
</script>
@endsection
