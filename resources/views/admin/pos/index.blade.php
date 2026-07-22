@extends('layouts.master')

@section('title', 'Point of Sale')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">
<style>
    /* Premium Modern POS Custom Styles */
    :root {
        --pos-primary: #4f46e5;
        --pos-primary-hover: #4338ca;
        --pos-secondary: #06b6d4;
        --pos-accent: #10b981;
        --pos-dark: #0f172a;
        --pos-light-bg: #f8fafc;
        --pos-card-bg: #ffffff;
        --pos-border: #e2e8f0;
        --pos-shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
        --pos-shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --pos-shadow-lg: 0 10px 25px -5px rgba(79,70,229,0.15);
        --pos-radius: 14px;
    }

    .pos-page-wrapper {
        background: #f1f5f9;
        margin: -15px -15px 0 -15px;
        padding: 20px;
        min-height: calc(100vh - 60px);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* Header Styling */
    .pos-header-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
        color: white;
        border-radius: var(--pos-radius);
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: var(--pos-shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .pos-header-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(6,182,212,0.25) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .pos-title-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 22px;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #ffffff;
    }

    .pos-title-icon {
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #38bdf8;
    }

    /* Stats Section */
    .pos-stats-grid {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .pos-stat-pill {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 12px;
        padding: 8px 14px;
        flex: 1 1 auto;
        min-width: 110px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .pos-stat-pill:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }

    .pos-stat-val {
        font-size: 17px;
        font-weight: 800;
        color: #ffffff;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        line-height: 1.2;
    }

    .pos-stat-lbl {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 700;
        margin-top: 2px;
    }

    /* Buttons & Actions */
    .btn-pos-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(16,185,129,0.3);
        transition: all 0.25s ease;
    }

    .btn-pos-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(16,185,129,0.4);
        color: white;
    }

    .btn-pos-header {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(8px);
        padding: 8px 14px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 12px;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .btn-pos-header:hover {
        background: rgba(255, 255, 255, 0.28);
        color: white;
        transform: translateY(-1px);
    }

    /* Left Product Panel */
    .product-search-box {
        background: white;
        border-radius: var(--pos-radius);
        padding: 18px;
        margin-bottom: 16px;
        box-shadow: var(--pos-shadow-sm);
        border: 1px solid var(--pos-border);
    }

    .pos-search-input-group {
        position: relative;
    }

    .pos-search-input-group .input-group-text {
        background: #f8fafc;
        border-color: #cbd5e1;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        color: #64748b;
    }

    .pos-search-input-group input {
        border-color: #cbd5e1;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        font-size: 14px;
        padding: 11px 14px;
        font-weight: 500;
    }

    .pos-search-input-group input:focus {
        border-color: var(--pos-primary);
        box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
    }

    /* Filter Panel Styling */
    .filters-panel {
        background: #f8fafc;
        border-radius: 12px;
        padding: 14px;
        margin-top: 14px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .filters-panel.collapsed {
        display: none !important;
    }

    .filters-panel select, .filters-panel input {
        font-size: 12px;
        border-radius: 8px;
        border-color: #cbd5e1;
        padding: 8px 10px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    /* Product Grid & Skeleton Loading */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(175px, 1fr));
        gap: 16px !important;
        padding: 2px !important;
        min-height: 850px;
        max-height: calc(100vh - 180px);
        overflow-y: auto;
    }

    @keyframes posPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    .pos-skeleton-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: var(--pos-radius);
        padding: 12px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        animation: posPulse 1.5s infinite ease-in-out;
    }

    .pos-skeleton-img {
        width: 100%;
        height: 105px;
        background: #e2e8f0;
        border-radius: 10px;
    }

    .pos-skeleton-line {
        height: 14px;
        background: #e2e8f0;
        border-radius: 4px;
    }

    .pos-skeleton-line.short {
        width: 60%;
    }

    /* Category Pills (Matching Reference Image) */
    .pos-category-pills-wrap {
        margin-bottom: 16px;
        position: relative;
    }
    
    .pos-category-pills {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding: 4px 0 8px 0;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    .pos-category-pills::-webkit-scrollbar {
        height: 4px;
    }
    .pos-category-pills::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .pos-pill-btn {
        background: #ffffff;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    .pos-pill-btn:hover {
        background: #f1f5f9;
        color: #0f172a;
        transform: translateY(-1px);
    }

    .pos-pill-btn.active {
        background: #0f172a !important;
        color: #ffffff !important;
        border-color: #0f172a !important;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
    }

    /* Product Grid & Cards (Matching Reference Image) */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 16px;
    }

    .pos-product-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 12px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .pos-product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }

    .pos-product-card.out-of-stock {
        opacity: 0.55;
        filter: grayscale(80%);
        cursor: not-allowed;
    }

    .pos-card-image-wrap {
        width: 100%;
        height: 135px;
        border-radius: 12px;
        overflow: hidden;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .pos-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .pos-product-card:hover .pos-card-img {
        transform: scale(1.04);
    }

    .pos-card-body {
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
    }

    .pos-card-title {
        font-size: 0.925rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 8px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.4em;
        font-family: 'Outfit', -apple-system, sans-serif;
    }

    .pos-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 4px;
    }

    .pos-card-price {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
    }

    .pos-card-stock {
        font-size: 11px;
        font-weight: 600;
        margin-top: 3px;
    }

    .pos-card-add-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #0f172a;
        color: #ffffff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);
    }

    .pos-card-add-btn:hover {
        background: #4f46e5;
        transform: scale(1.1);
        box-shadow: 0 6px 14px rgba(79, 70, 229, 0.3);
    }

    /* Legacy compatibility classes */
    .product-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 12px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
    }

    .product-image {
        width: 100%;
        height: 135px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 10px;
        background: #f8fafc;
    }    background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    /* Right Checkout Panel */
    .pos-right-card {
        background: white;
        border-radius: var(--pos-radius);
        border: 1px solid var(--pos-border);
        box-shadow: var(--pos-shadow-md);
        margin-bottom: 16px;
        overflow: hidden;
    }

    .pos-section-header {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: white;
        padding: 14px 18px;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pos-section-header i {
        color: #38bdf8;
    }

    /* Cart Items & Empty State */
    .cart-items {
        min-height: 200px;
        max-height: 320px;
        overflow-y: auto;
        padding: 8px;
    }

    .empty-cart-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        text-align: center;
        background: #f8fafc;
        border-radius: 12px;
        margin: 12px;
        border: 2px dashed #cbd5e1;
    }

    .empty-cart-icon {
        width: 60px;
        height: 60px;
        background: #e0e7ff;
        color: #4f46e5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 12px;
    }

    .cart-item {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 12px;
        background: #ffffff;
        border-radius: 10px;
        margin-bottom: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    .cart-item:hover {
        border-color: #cbd5e1;
        box-shadow: var(--pos-shadow-sm);
    }

    .cart-item-image {
        width: 44px;
        height: 44px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .cart-item-name {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .cart-item-variation {
        font-size: 11px;
        color: #64748b;
    }

    .quantity-control {
        display: inline-flex;
        align-items: center;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }

    .quantity-btn {
        background: #f1f5f9;
        border: none;
        padding: 4px 10px;
        cursor: pointer;
        font-weight: 800;
        color: #334155;
        transition: background 0.15s;
    }

    .quantity-btn:hover {
        background: #e2e8f0;
        color: var(--pos-primary);
    }

    .quantity-input {
        border: none;
        width: 40px;
        text-align: center;
        font-weight: 700;
        font-size: 13px;
    }

    /* Cart Summary */
    .cart-summary {
        padding: 16px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        font-size: 13px;
        color: #475569;
        font-weight: 600;
    }

    .summary-total {
        font-size: 19px;
        font-weight: 800;
        color: var(--pos-primary);
        border-top: 2px dashed #cbd5e1;
        padding-top: 10px;
        margin-top: 8px;
    }

    .customer-form, .payment-form {
        padding: 18px;
        background: #ffffff;
    }

    .customer-form label, .payment-form label {
        font-size: 12px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .customer-form input, .payment-form input, .payment-form select, .payment-form textarea {
        border-radius: 10px;
        font-size: 13px;
        border: 1.5px solid #cbd5e1;
        padding: 10px 14px;
        font-weight: 500;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }

    .customer-form input:focus, .payment-form input:focus, .payment-form select:focus, .payment-form textarea:focus {
        background-color: #ffffff;
        border-color: var(--pos-primary);
        box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
    }

    .pos-section-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        color: white;
        padding: 14px 20px;
        font-weight: 700;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    /* Checkout Button */
    .btn-checkout {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: white;
        border: none;
        padding: 16px 24px;
        font-size: 16px;
        font-weight: 800;
        border-radius: var(--pos-radius);
        width: 100%;
        cursor: pointer;
        box-shadow: var(--pos-shadow-lg);
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px -5px rgba(79,70,229,0.4);
    }

    .btn-checkout:disabled {
        background: #94a3b8;
        box-shadow: none;
        cursor: not-allowed;
        transform: none;
    }

    .scanner-section {
        background: #f8fafc;
        border: 1px dashed var(--pos-primary);
        border-radius: 12px;
        padding: 14px;
        margin-top: 12px;
        text-align: center;
        display: none;
    }

    .scanner-section.active {
        display: block;
    }

    a.pos-menu-item {
        display: none !important;
    }

    /* Variation Modal Styling */
    #variationModal .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    #variationModal .modal-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        color: #ffffff;
        padding: 16px 20px;
        border-bottom: none;
    }

    #variationModal .modal-header .modal-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: #ffffff;
    }

    #variationModal .modal-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    #variationModal .modal-body {
        padding: 20px;
        background-color: #f8fafc;
        max-height: 420px;
        overflow-y: auto;
    }

    .variation-item-card {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: space-between;
        user-select: none;
    }

    .variation-item-card:hover:not(.disabled) {
        border-color: #818cf8;
        background-color: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.08);
    }

    .variation-item-card.selected {
        border-color: #4f46e5 !important;
        background-color: #eef2ff !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }

    .variation-item-card.disabled {
        opacity: 0.55;
        background-color: #f1f5f9;
        cursor: not-allowed;
        border-color: #e2e8f0;
    }

    .variation-radio-indicator {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .variation-item-card.selected .variation-radio-indicator {
        border-color: #4f46e5;
        background-color: #4f46e5;
    }

    .variation-radio-indicator::after {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #ffffff;
        display: none;
    }

    .variation-item-card.selected .variation-radio-indicator::after {
        display: block;
    }

    #orderSuccessModal .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    #orderSuccessModal .modal-header.bg-primary {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%) !important;
        color: #ffffff !important;
        padding: 16px 20px;
        border-bottom: none;
    }

    #orderSuccessModal .modal-header.bg-success {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%) !important;
        color: #ffffff !important;
        padding: 16px 20px;
        border-bottom: none;
    }

    #orderSuccessModal .modal-header .modal-title {
        font-weight: 700;
        font-size: 1.15rem;
        color: #ffffff;
    }

    #orderSuccessModal .modal-header .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    #orderSuccessModal .modal-body {
        padding: 24px;
        background-color: #f8fafc;
        max-height: 480px;
        overflow-y: auto;
    }

    .pos-preview-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .pos-preview-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pos-preview-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        margin-bottom: 6px;
    }

    .pos-preview-row:last-child {
        margin-bottom: 0;
    }

    .pos-preview-label {
        color: #64748b;
        font-weight: 500;
    }

    .pos-preview-value {
        color: #0f172a;
        font-weight: 600;
    }

    .pos-preview-table {
        margin-bottom: 0;
    }

    .pos-preview-table th {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        background: #f1f5f9;
        border-bottom: 1px solid #e2e8f0;
        padding: 8px 12px;
    }

    .pos-preview-table td {
        font-size: 13px;
        padding: 10px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .pos-preview-table tfoot th {
        background: transparent;
        padding: 6px 12px;
        font-size: 13px;
    }

    .pos-preview-table tfoot .table-primary th {
        background: #eef2ff;
        color: #312e81;
        font-size: 15px;
        font-weight: 800;
        border-radius: 8px;
    }

    #orderSuccessModal .modal-footer {
        background-color: #ffffff;
        border-top: 1px solid #e2e8f0;
        padding: 14px 20px;
    }
</style>
@endsection

@section('content')
<div class="pos-page-wrapper">
    <!-- Premium Header -->
    <div class="pos-header-card d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="pos-title-icon">
                <i class="fas fa-cash-register"></i>
            </div>
            <div>
                <h3 class="pos-title-badge mb-0">Smart POS System</h3>
                <p class="mb-0 text-white-50 small">Fast checkout & inventory management</p>
            </div>
        </div>

        <!-- Real-time Stats Cards -->
        <div class="pos-stats-grid" id="statsCards">
            <div class="pos-stat-pill">
                <div class="pos-stat-val" id="todayOrders">0</div>
                <div class="pos-stat-lbl">Today's Orders</div>
            </div>
            <div class="pos-stat-pill">
                <div class="pos-stat-val" id="todayRevenue">৳0</div>
                <div class="pos-stat-lbl">Today's Revenue</div>
            </div>
            <div class="pos-stat-pill">
                <div class="pos-stat-val" id="weekOrders">0</div>
                <div class="pos-stat-lbl">This Week</div>
            </div>
            <div class="pos-stat-pill">
                <div class="pos-stat-val" id="monthRevenue">৳0</div>
                <div class="pos-stat-lbl">Monthly Sales</div>
            </div>
        </div>

        <!-- Header Actions -->
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-pos-header" onclick="startScanner()">
                <i class="fas fa-qrcode"></i> Scan Code
            </button>
            <button class="btn btn-pos-header" onclick="loadStats()">
                <i class="fas fa-rotate"></i> Refresh
            </button>
            <button class="btn btn-pos-header" style="background: rgba(239,68,68,0.25);" onclick="clearCart()">
                <i class="fas fa-trash-can"></i> Clear Cart
            </button>
        </div>
    </div>



    <div class="row g-3 pos-container">
        <!-- Left Panel - Products Catalog -->
        <div class="col-lg-8 col-md-7 pos-left-panel">
            <!-- Search and Filter Header Bar -->
            <div class="product-search-box">
                <div class="row g-2 align-items-center">
                    <div class="col-md-7 col-12">
                        <div class="input-group pos-search-input-group">
                            <span class="input-group-text">
                                <i class="fas fa-magnifying-glass"></i>
                            </span>
                            <input type="text" class="form-control" id="productSearch" 
                                   placeholder="Search products by title, SKU, or barcode...">
                        </div>
                    </div>
                    <div class="col-md-5 col-12 text-end d-flex align-items-center justify-content-end gap-1">
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="filtersToggleBtn">
                            <i class="fas fa-filter me-1"></i> Filters
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="clearFiltersBtn">
                            <i class="fas fa-xmark me-1"></i> Clear
                        </button>
                    </div>
                </div>

                <div id="filtersPanel" class="filters-panel collapsed mt-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select id="primary-category-filter" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="subcategory-filter" class="form-select" disabled>
                                <option value="">Select primary category first</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="third-category-filter" class="form-select" disabled>
                                <option value="">Select subcategory first</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2 mt-2">
                        <div class="col-md-4">
                            <select id="stock-status-filter" class="form-select">
                                <option value="">All Stock Status</option>
                                <option value="in_stock">In Stock</option>
                                <option value="low_stock">Low Stock</option>
                                <option value="out_of_stock">Out of Stock</option>
                                <option value="on_backorder">On Backorder</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="product-type-filter" class="form-select">
                                <option value="">All Product Types</option>
                                <option value="simple">Simple</option>
                                <option value="variable">Variable</option>
                                <option value="digital">Digital</option>
                                <option value="affiliate">Affiliate</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-center pt-1">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="low-stock-filter">
                                <label class="form-check-label fw-semibold" for="low-stock-filter">
                                    Low Stock Only
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- QR/Barcode Scanner -->
                <div class="scanner-section">
                    <button class="btn btn-sm btn-pos-primary" type="button" onclick="startScanner()">
                        <i class="fas fa-qrcode"></i> Start Scanner
                    </button>
                    <div id="qr-reader" class="mt-2" style="display: none;"></div>
                </div>
            </div>

            <!-- Category Filter Pills (Matching Reference Image) -->
            <div class="pos-category-pills-wrap">
                <div class="pos-category-pills" id="categoryPills">
                    <button type="button" class="pos-pill-btn active" data-cat-id="">All Products</button>
                    @foreach($categories as $category)
                        <button type="button" class="pos-pill-btn" data-cat-id="{{ $category->id }}">{{ $category->name }}</button>
                    @endforeach
                </div>
            </div>

            <!-- Products Grid -->
            <div class="product-grid" id="productsGrid">
                <div class="text-center py-5 w-100 bg-white rounded-4 border">
                    <i class="fas fa-box-open fa-3x text-muted opacity-50 mb-3"></i>
                    <p class="text-muted fw-semibold">Search or select a category to display products</p>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3 bg-white p-3 border rounded-3" id="productsPagination" style="display: none;">
                <div class="text-muted small fw-semibold" id="paginationInfo"></div>
                <div class="btn-group">
                    <button class="btn btn-outline-indigo btn-sm" id="prevPageBtn">Previous</button>
                    <button class="btn btn-outline-indigo btn-sm" id="nextPageBtn">Next</button>
                </div>
            </div>
        </div>

        <!-- Right Panel - Cart & Checkout -->
        <div class="col-lg-4 col-md-5 pos-right-panel">
            <!-- Cart Section -->
            <div class="pos-right-card">
                <div class="pos-section-header">
                    <span><i class="fas fa-basket-shopping me-2"></i> Current Cart</span>
                    <span class="badge bg-indigo text-white px-2 py-1" style="background: var(--pos-primary);"><span id="cartItemCount">0</span> Items</span>
                </div>
                <div class="cart-items" id="cartItems">
                    <div class="empty-cart-state">
                        <div class="empty-cart-icon">
                            <i class="fas fa-basket-shopping"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Your cart is empty</h6>
                        <p class="text-muted small mb-0">Select products from the catalog to add to cart</p>
                    </div>
                </div>
                <div class="cart-summary" id="cartSummary" style="display: none;">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotal">৳0.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Discount:</span>
                        <span>
                            <input type="number" id="discountAmount" class="form-control form-control-sm d-inline-block" 
                                   style="width: 80px;" min="0" step="0.01" value="0" onchange="updateTotals()">
                        </span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>
                            <input type="number" id="shippingAmount" class="form-control form-control-sm d-inline-block" 
                                   style="width: 80px;" min="0" step="0.01" value="0" onchange="updateTotals()">
                        </span>
                    </div>
                    <div class="summary-row" id="paymentChargeRow" style="display: none;">
                        <span id="paymentChargeLabel">Payment Charge:</span>
                        <span id="paymentChargeAmount">৳0.00</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total:</span>
                        <span id="total">৳0.00</span>
                    </div>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="pos-right-card mt-3">
                <div class="pos-section-header">
                    <span><i class="fas fa-credit-card me-2"></i> Payment & Order Details</span>
                </div>
                <div class="payment-form">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Order Source *</label>
                            <select class="form-select" id="orderSource" required>
                                @foreach($orderSources as $value => $label)
                                    <option value="{{ $value }}" {{ $value === 'Physical Store' ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Payment Method *</label>
                            <select class="form-select" id="paymentMethod" required onchange="togglePaymentFields()">
                                <option value="cod">Cash on Delivery (COD)</option>
                                <option value="cash" selected>Cash</option>
                                <option value="card">Card</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="rocket">Rocket</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                        
                        <!-- Payment Gateway Fields -->
                        <!-- bKash Fields -->
                        <div id="bkashFields" class="payment-gateway-fields" style="display: none;">
                            <div class="col-12"><hr><h6 class="text-primary fw-bold">bKash Payment Details</h6></div>
                            <div class="col-md-4">
                                <label class="form-label">bKash Number *</label>
                                <input type="tel" class="form-control" id="bkashNumber" placeholder="01XXXXXXXXX">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Transaction ID (TrxID) *</label>
                                <input type="text" class="form-control" id="bkashTrxId" placeholder="Enter TrxID">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">bKash Charge</label>
                                <input type="number" class="form-control" id="bkashCharge" placeholder="0.00" min="0" step="0.01" value="0" onchange="updateTotals()">
                            </div>
                        </div>
                        
                        <!-- Nagad Fields -->
                        <div id="nagadFields" class="payment-gateway-fields" style="display: none;">
                            <div class="col-12"><hr><h6 class="text-success fw-bold">Nagad Payment Details</h6></div>
                            <div class="col-md-4">
                                <label class="form-label">Nagad Number *</label>
                                <input type="tel" class="form-control" id="nagadNumber" placeholder="01XXXXXXXXX">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Transaction ID (TrxID) *</label>
                                <input type="text" class="form-control" id="nagadTrxId" placeholder="Enter TrxID">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nagad Charge</label>
                                <input type="number" class="form-control" id="nagadCharge" placeholder="0.00" min="0" step="0.01" value="0" onchange="updateTotals()">
                            </div>
                        </div>
                        
                        <!-- Rocket Fields -->
                        <div id="rocketFields" class="payment-gateway-fields" style="display: none;">
                            <div class="col-12"><hr><h6 class="text-warning fw-bold">Rocket Payment Details</h6></div>
                            <div class="col-md-4">
                                <label class="form-label">Rocket Number *</label>
                                <input type="tel" class="form-control" id="rocketNumber" placeholder="01XXXXXXXXX">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Transaction ID (TrxID) *</label>
                                <input type="text" class="form-control" id="rocketTrxId" placeholder="Enter TrxID">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Rocket Charge</label>
                                <input type="number" class="form-control" id="rocketCharge" placeholder="0.00" min="0" step="0.01" value="0" onchange="updateTotals()">
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <textarea class="form-control" id="orderNotes" rows="2" 
                                      placeholder="Order notes (optional)"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Section -->
            <div class="pos-right-card mt-3">
                <div class="pos-section-header">
                    <span><i class="fas fa-user me-2"></i> Customer Information</span>
                </div>
                <div class="customer-form">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="customerSearch" 
                               placeholder="Search existing customer...">
                        <div id="customerSuggestions" class="mt-2"></div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="customerName" 
                                   placeholder="Customer Name *" required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="customerPhone" 
                                   placeholder="Phone Number *" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control" id="customerEmail" 
                                   placeholder="Email (optional)">
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="customerCity" 
                                   placeholder="City">
                        </div>
                        <div class="col-12">
                            <textarea class="form-control" id="customerAddress" rows="2" 
                                      placeholder="Address (optional)"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Button -->
            <button class="btn-checkout" id="checkoutBtn" onclick="processOrder()" disabled>
                <i class="fas fa-eye"></i>
                Review Order
            </button>
        </div>
    </div>
</div>

<!-- Variation Selection Modal -->
<div class="modal fade" id="variationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-tags text-warning"></i>
                    <span>Select Product Variation</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="variationModalBody">
                <!-- Variation options will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border px-4 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary px-4 fw-bold" onclick="addSelectedVariation()">
                    <i class="fas fa-cart-plus me-1"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Order Success / Preview Modal -->
<div class="modal fade" id="orderSuccessModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fas fa-eye"></i>
                    <span>Order Preview</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderSuccessBody">
                <!-- Order details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border px-4 fw-semibold" data-bs-dismiss="modal">
                    <i class="fas fa-xmark me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-success px-4 fw-bold" onclick="startNewOrder()">
                    <i class="fas fa-paper-plane me-1"></i> Place Order
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
// Global variables
let cart = [];
let selectedCustomer = null;
let currentProduct = null;
let html5QrCode = null;
let pendingOrder = null; // Store order data before final placement
const primaryCategoryFilter = $('#primary-category-filter');
const subcategoryFilter = $('#subcategory-filter');
const thirdCategoryFilter = $('#third-category-filter');
const stockStatusFilter = $('#stock-status-filter');
const productTypeFilter = $('#product-type-filter');
const lowStockFilter = $('#low-stock-filter');
let currentPage = 1;
let lastPage = 1;
let totalItems = 0;
let perPage = 24;
let filtersCollapsed = true;

// Initialize POS
$(document).ready(function() {
    // Set CSRF token for all AJAX requests FIRST
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    setSubcategoryOptions(null, 'Select a primary category first', true);
    setThirdCategoryOptions(null, 'Select a subcategory first', true);
    loadStats();
    setupEventListeners();
    
    // Load initial products instantly
    searchProducts();
    
    // Handle modal cancellation
    $('#orderSuccessModal').on('hidden.bs.modal', function() {
        // If there's a pending order and modal is closed without placing, clear it
        if (pendingOrder) {
            pendingOrder = null;
            toastr.info('Order preview cancelled');
        }
    });
});

function setupEventListeners() {
    const debouncedSearch = debounce(() => searchProducts(1), 300);

    // Product search
    $('#productSearch').on('input', debouncedSearch);
    // Category pill click handler
    $(document).on('click', '.pos-pill-btn', function() {
        $('.pos-pill-btn').removeClass('active');
        $(this).addClass('active');
        const catId = $(this).attr('data-cat-id');
        primaryCategoryFilter.val(catId).trigger('change');
    });

    // Debounced search on typing
    let searchDebounceTimer = null;
    $('#productSearch').on('input', function() {
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(function() {
            searchProducts(1);
        }, 200);
    });

    primaryCategoryFilter.on('change', function() {
        const catId = $(this).val();
        $('.pos-pill-btn').removeClass('active');
        $(`.pos-pill-btn[data-cat-id="${catId}"]`).addClass('active');
        loadSubcategories(catId);
        searchProducts(1);
    });
    subcategoryFilter.on('change', function() {
        const subcategoryId = $(this).val();
        thirdCategoryFilter.val('');
        loadThirdCategories(subcategoryId);
        searchProducts(1);
    });
    thirdCategoryFilter.on('change', () => searchProducts(1));
    stockStatusFilter.on('change', () => searchProducts(1));
    productTypeFilter.on('change', () => searchProducts(1));
    lowStockFilter.on('change', () => searchProducts(1));
    $('#clearFiltersBtn').on('click', function() {
        clearFilters();
        searchProducts(1);
    });
    $('#filtersToggleBtn').on('click', function() {
        filtersCollapsed = !filtersCollapsed;
        updateFiltersVisibility();
    });
    $('#prevPageBtn').on('click', function() {
        if (currentPage > 1) {
            searchProducts(currentPage - 1);
        }
    });
    $('#nextPageBtn').on('click', function() {
        if (currentPage < lastPage) {
            searchProducts(currentPage + 1);
        }
    });
    
    // Customer search
    $('#customerSearch').on('input', debounce(searchCustomers, 300));
    
    // Customer form validation
    $('#customerName, #customerPhone').on('input', validateForm);
    
    // Enter key handling
    $('#productSearch').on('keypress', function(e) {
        if (e.which === 13) {
            searchProducts(1);
        }
    });

    if (window.innerWidth < 768) {
        filtersCollapsed = true;
        updateFiltersVisibility();
    }
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function setSubcategoryOptions(options, placeholder, disabled) {
    subcategoryFilter.empty().append(`<option value="">${placeholder}</option>`);
    if (Array.isArray(options)) {
        options.forEach(option => {
            subcategoryFilter.append(`<option value="${option.id}">${option.name}</option>`);
        });
    }
    subcategoryFilter.prop('disabled', disabled);
}

function setThirdCategoryOptions(options, placeholder, disabled) {
    thirdCategoryFilter.empty().append(`<option value="">${placeholder}</option>`);
    if (Array.isArray(options)) {
        options.forEach(option => {
            thirdCategoryFilter.append(`<option value="${option.id}">${option.name}</option>`);
        });
    }
    thirdCategoryFilter.prop('disabled', disabled);
}

function loadSubcategories(categoryId) {
    if (!categoryId) {
        setSubcategoryOptions(null, 'Select a primary category first', true);
        setThirdCategoryOptions(null, 'Select a subcategory first', true);
        return;
    }

    setSubcategoryOptions(null, 'Loading...', true);
    setThirdCategoryOptions(null, 'Select a subcategory first', true);

    const url = '{{ route("admin.get-product-subcategories", ':id') }}'.replace(':id', categoryId);

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data) && data.length) {
                setSubcategoryOptions(data, 'All Subcategories', false);
            } else {
                setSubcategoryOptions(null, 'No subcategories available', true);
            }
        })
        .catch(() => {
            setSubcategoryOptions(null, 'Failed to load subcategories', true);
        });
}

function loadThirdCategories(subcategoryId) {
    if (!subcategoryId) {
        setThirdCategoryOptions(null, 'Select a subcategory first', true);
        return;
    }

    setThirdCategoryOptions(null, 'Loading...', true);

    fetch('{{ route('admin.third-categories.by-subcategories') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ sub_category_ids: [subcategoryId] })
    })
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data) && data.length) {
                setThirdCategoryOptions(data, 'All Third Categories', false);
            } else {
                setThirdCategoryOptions(null, 'No third categories available', true);
            }
        })
        .catch(() => {
            setThirdCategoryOptions(null, 'Failed to load third categories', true);
        });
}

function clearFilters() {
    $('#productSearch').val('');
    primaryCategoryFilter.val('');
    setSubcategoryOptions(null, 'Select a primary category first', true);
    setThirdCategoryOptions(null, 'Select a subcategory first', true);
    stockStatusFilter.val('');
    productTypeFilter.val('');
    lowStockFilter.prop('checked', false);
    currentPage = 1;
    lastPage = 1;
    totalItems = 0;
}

function updateFiltersVisibility() {
    const panel = $('#filtersPanel');
    const toggleBtn = $('#filtersToggleBtn');
    if (filtersCollapsed) {
        panel.addClass('collapsed');
        toggleBtn.html('<i class="fas fa-filter me-1"></i> Filters');
    } else {
        panel.removeClass('collapsed');
        toggleBtn.html('<i class="fas fa-filter-circle-xmark me-1"></i> Hide Filters');
    }
}

function updatePagination(meta, count) {
    const container = $('#productsPagination');
    if (!meta || !meta.total) {
        container.hide();
        return;
    }

    currentPage = meta.current_page || 1;
    lastPage = meta.last_page || 1;
    perPage = meta.per_page || perPage;
    totalItems = meta.total || 0;

    const start = (currentPage - 1) * perPage + 1;
    const itemsOnPage = count || 0;
    const end = itemsOnPage > 0 ? Math.min(totalItems, start + itemsOnPage - 1) : Math.min(totalItems, start + perPage - 1);

    $('#paginationInfo').text(`Showing ${start}-${end} of ${totalItems}`);
    $('#prevPageBtn').prop('disabled', currentPage <= 1);
    $('#nextPageBtn').prop('disabled', currentPage >= lastPage);
    container.show();
}

// Product search and display
function searchProducts(page = 1) {
    currentPage = page;
    const search = $('#productSearch').val();
    const primaryCategoryId = primaryCategoryFilter.val();
    const subCategoryId = subcategoryFilter.val();
    const thirdCategoryId = thirdCategoryFilter.val();
    const stockStatus = stockStatusFilter.val();
    const productType = productTypeFilter.val();
    const lowStockOnly = lowStockFilter.is(':checked') ? '1' : '';
    
    // Render animated skeleton cards during data fetching
    const grid = $('#productsGrid');
    let skeletonHtml = '';
    for (let i = 0; i < 12; i++) {
        skeletonHtml += `
            <div class="pos-skeleton-card">
                <div class="pos-skeleton-img"></div>
                <div class="pos-skeleton-line"></div>
                <div class="pos-skeleton-line short"></div>
            </div>
        `;
    }
    grid.html(skeletonHtml);
    
    $.get('{{ route("admin.pos.search-products") }}', {
        search: search,
        primary_category_id: primaryCategoryId,
        subcategory_id: subCategoryId,
        third_category_id: thirdCategoryId,
        stock_status: stockStatus,
        product_type: productType,
        low_stock_only: lowStockOnly,
        per_page: perPage,
        page: page
    })
    .done(function(response) {
        if (response.error) {
            toastr.error(response.message);
            displayProducts([]);
            updatePagination(null, 0);
        } else {
            displayProducts(response.products || []);
            updatePagination(response.meta, (response.products || []).length);
        }
    })
    .fail(function(xhr) {
        const response = xhr.responseJSON;
        toastr.error(response?.message || 'Failed to search products');
        displayProducts([]);
        updatePagination(null, 0);
    });
}

function displayProducts(products) {
    const grid = $('#productsGrid');
    
    if (!products || products.length === 0) {
        grid.html(`
            <div class="text-center py-5 col-12 bg-white rounded-4 border">
                <i class="fas fa-box-open fa-3x text-muted opacity-50 mb-3"></i>
                <h6 class="fw-bold text-dark">No products found</h6>
                <p class="text-muted small">Try searching for products or check filters</p>
            </div>
        `);
        return;
    }
    
    let html = '';
    products.forEach(product => {
        const status = product.computed_stock_status || (product.in_stock ? 'in_stock' : 'out_of_stock');
        const inStock = status !== 'out_of_stock';
        const stockClass = inStock ? '' : 'out-of-stock';
        
        let stockText = 'In Stock';
        if (status === 'on_backorder') {
            stockText = 'On backorder';
        } else if (product.manage_stock) {
            if (product.product_type === 'variable') {
                const inStockVariations = (product.variations || []).filter(v => v.in_stock);
                stockText = `${inStockVariations.length} variations`;
                if (product.stock_quantity !== undefined && product.stock_quantity !== null) {
                    stockText += ` • ${product.stock_quantity} in stock`;
                }
            } else {
                stockText = `${product.stock_quantity || 0} in stock`;
            }
        }
        if (status === 'low_stock') {
            stockText += ' (Low stock)';
        }
        
        const stockTextClass = status === 'low_stock' ? 'text-warning'
            : status === 'out_of_stock' ? 'text-danger'
            : status === 'on_backorder' ? 'text-info'
            : 'text-success';
        
        const imgHtml = product.image 
            ? `<img src="${product.image}" alt="${product.title}" class="pos-card-img" loading="lazy">` 
            : `<div class="d-flex align-items-center justify-content-center h-100 text-muted"><i class="fas fa-image fs-2 opacity-25"></i></div>`;

        const productJson = JSON.stringify(product).replace(/'/g, "&apos;");
        
        html += `
            <div class="pos-product-card ${stockClass}" onclick="selectProduct(${product.id})" data-product='${productJson}'>
                <div class="pos-card-image-wrap">
                    ${imgHtml}
                    ${product.product_type === 'variable' ? '<span class="badge bg-dark position-absolute top-0 end-0 m-2 rounded-pill px-2 py-1" style="font-size:10px;">Options</span>' : ''}
                </div>
                <div class="pos-card-body">
                    <h6 class="pos-card-title">${product.title}</h6>
                    <div class="pos-card-footer">
                        <div>
                            <div class="pos-card-price">৳${parseFloat(product.price || 0).toFixed(2)}</div>
                            <div class="pos-card-stock ${stockTextClass}">${stockText}</div>
                        </div>
                        <button type="button" class="pos-card-add-btn" title="Add to Cart" onclick="event.stopPropagation(); selectProduct(${product.id});">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    grid.html(html);
}

function selectProduct(productId) {
    const productCard = $(`.pos-product-card[onclick*="selectProduct(${productId})"], .product-card[onclick*="selectProduct(${productId})"]`).first();
    if (!productCard.length) return;
    
    let product;
    try {
        product = JSON.parse(productCard.attr('data-product'));
    } catch(e) {
        console.error("Error parsing product JSON", e);
        return;
    }
    
    if (product.computed_stock_status === 'out_of_stock') {
        toastr.warning('This product is out of stock');
        return;
    }
    
    if (product.product_type === 'variable') {
        showVariationModal(product);
    } else {
        addToCart(product);
    }
}

function showVariationModal(product) {
    currentProduct = product;
    selectedVariation = null;
    const modal = $('#variationModal');
    const modalBody = $('#variationModalBody');
    
    let html = `
        <div class="p-2 mb-3 bg-light rounded-3 d-flex align-items-center gap-3 border">
            ${product.image ? `<img src="${product.image}" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">` : '<div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width:50px;height:50px;"><i class="fas fa-box"></i></div>'}
            <div>
                <h6 class="fw-bold mb-1 text-dark">${product.title}</h6>
                <span class="badge bg-primary-subtle text-primary fw-semibold">${product.variations ? product.variations.length : 0} Variations available</span>
            </div>
        </div>
        <p class="text-muted small fw-semibold mb-2">Select a variation option to add to cart:</p>
        <div class="variation-list">
    `;
    
    if (product.variations && product.variations.length > 0) {
        product.variations.forEach((variation, idx) => {
            const inStock = variation.in_stock && variation.stock_quantity > 0;
            const disabled = !inStock ? 'disabled' : '';
            const stockBadge = inStock 
                ? `<span class="badge bg-success-subtle text-success px-2 py-1"><i class="fas fa-check-circle me-1"></i>Stock: ${variation.stock_quantity}</span>`
                : `<span class="badge bg-danger-subtle text-danger px-2 py-1"><i class="fas fa-times-circle me-1"></i>Out of Stock</span>`;
            
            // Escape double quotes safely for data attributes
            const variationData = JSON.stringify(variation).replace(/'/g, "&apos;");
            
            html += `
                <div class="variation-item-card ${disabled}" data-variation-json='${variationData}' onclick="selectVariation(this)">
                    <div class="d-flex align-items-center">
                        <div class="variation-radio-indicator"></div>
                        <div>
                            <div class="fw-bold text-dark fs-6">${variation.display_name}</div>
                            <div class="mt-1">${stockBadge}</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-success fs-5">৳${parseFloat(variation.price || 0).toFixed(2)}</div>
                        ${variation.offer_price && variation.regular_price ? `<small class="text-muted text-decoration-line-through">৳${parseFloat(variation.regular_price).toFixed(2)}</small>` : ''}
                    </div>
                </div>
            `;
        });
    } else {
        html += '<p class="text-muted text-center py-4">No variations available for this product</p>';
    }
    
    html += `</div>`;
    
    modalBody.html(html);
    modal.modal('show');
}

let selectedVariation = null;

function selectVariation(element) {
    const $el = $(element);
    if ($el.hasClass('disabled')) return;
    
    $('.variation-item-card').removeClass('selected');
    $el.addClass('selected');
    
    const rawJson = $el.attr('data-variation-json');
    try {
        selectedVariation = JSON.parse(rawJson);
    } catch(e) {
        console.error("Error parsing variation JSON:", e);
    }
}

function addSelectedVariation() {
    if (!selectedVariation) {
        toastr.warning('Please select a variation');
        return;
    }
    
    const productWithVariation = {
        ...currentProduct, // Keep the original product data
        price: selectedVariation.price, // Use variation price
        variation_id: selectedVariation.id, // Add variation ID
        display_name: selectedVariation.display_name, // Add variation display name
        stock_quantity: selectedVariation.stock_quantity // Use variation stock
    };
    
    addToCart(productWithVariation);
    $('#variationModal').modal('hide');
    selectedVariation = null;
}

function addToCart(product) {
    const existingItemIndex = cart.findIndex(item => 
        item.id === product.id && 
        (item.variation_id || null) === (product.variation_id || null)
    );
    
    if (existingItemIndex !== -1) {
        // Increase quantity
        cart[existingItemIndex].quantity += 1;
        cart[existingItemIndex].subtotal = cart[existingItemIndex].quantity * cart[existingItemIndex].price;
    } else {
        // Add new item
        cart.push({
            id: product.id, // This should always be the product ID
            product_id: product.id, // Explicitly store product ID
            title: product.title,
            price: parseFloat(product.price || 0),
            quantity: 1,
            subtotal: parseFloat(product.price || 0),
            image: product.image,
            variation_id: product.variation_id || null,
            display_name: product.display_name || null,
            stock_quantity: product.stock_quantity || 0,
            manage_stock: product.manage_stock
        });
    }
    
    updateCartDisplay();
    toastr.success(`${product.title} added to cart`);
}

function updateCartDisplay() {
    const cartItems = $('#cartItems');
    const cartItemCount = $('#cartItemCount');
    const cartSummary = $('#cartSummary');
    const checkoutBtn = $('#checkoutBtn');
    
    cartItemCount.text(cart.length);
    
    if (cart.length === 0) {
        cartItems.html(`
            <div class="empty-cart-state">
                <div class="empty-cart-icon">
                    <i class="fas fa-basket-shopping"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Your cart is empty</h6>
                <p class="text-muted small mb-0">Select products from the catalog to add to cart</p>
            </div>
        `);
        cartSummary.hide();
        checkoutBtn.prop('disabled', true);
        return;
    }
    
    let html = '';
    cart.forEach((item, index) => {
        html += `
            <div class="cart-item">
                ${item.image ? `<img src="${item.image}" alt="${item.title}" class="cart-item-image">` : '<div class="cart-item-image bg-light d-flex align-items-center justify-content-center"><i class="fas fa-image text-muted"></i></div>'}
                <div class="cart-item-details">
                    <div class="cart-item-name">${item.title}</div>
                    ${item.display_name ? `<div class="cart-item-variation">${item.display_name}</div>` : ''}
                    <div class="text-success">৳${item.price.toFixed(2)} × ${item.quantity} = ৳${item.subtotal.toFixed(2)}</div>
                </div>
                <div class="cart-item-controls">
                    <div class="quantity-control">
                        <button class="quantity-btn" onclick="updateQuantity(${index}, -1)">-</button>
                        <input type="number" class="quantity-input" value="${item.quantity}" min="1" 
                               onchange="setQuantity(${index}, this.value)">
                        <button class="quantity-btn" onclick="updateQuantity(${index}, 1)">+</button>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    cartItems.html(html);
    cartSummary.show();
    updateTotals();
    validateForm();
}

function updateQuantity(index, change) {
    const item = cart[index];
    const newQuantity = item.quantity + change;
    
    if (newQuantity <= 0) {
        removeFromCart(index);
        return;
    }
    
    // Check stock availability
    if (item.manage_stock && newQuantity > item.stock_quantity) {
        toastr.warning(`Only ${item.stock_quantity} items available in stock`);
        return;
    }
    
    item.quantity = newQuantity;
    item.subtotal = item.quantity * item.price;
    updateCartDisplay();
}

function setQuantity(index, quantity) {
    const item = cart[index];
    const newQuantity = parseInt(quantity) || 1;
    
    if (newQuantity <= 0) {
        removeFromCart(index);
        return;
    }
    
    // Check stock availability
    if (item.manage_stock && newQuantity > item.stock_quantity) {
        toastr.warning(`Only ${item.stock_quantity} items available in stock`);
        $(`.quantity-input`).eq(index).val(item.quantity);
        return;
    }
    
    item.quantity = newQuantity;
    item.subtotal = item.quantity * item.price;
    updateCartDisplay();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
    toastr.info('Item removed from cart');
}

function updateTotals() {
    const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
    const discount = parseFloat($('#discountAmount').val()) || 0;
    const shipping = parseFloat($('#shippingAmount').val()) || 0;
    
    // Add payment gateway charges
    let paymentCharge = 0;
    const paymentMethod = $('#paymentMethod').val();
    if (paymentMethod === 'bkash') {
        paymentCharge = parseFloat($('#bkashCharge').val()) || 0;
    } else if (paymentMethod === 'nagad') {
        paymentCharge = parseFloat($('#nagadCharge').val()) || 0;
    } else if (paymentMethod === 'rocket') {
        paymentCharge = parseFloat($('#rocketCharge').val()) || 0;
    }
    
    const total = subtotal - discount + shipping + paymentCharge;
    
    $('#subtotal').text(`৳${subtotal.toFixed(2)}`);
    $('#total').text(`৳${total.toFixed(2)}`);
    
    // Show/hide payment charge row
    if (paymentCharge > 0) {
        let chargeLabel = 'Payment Charge:';
        if (paymentMethod === 'bkash') chargeLabel = 'bKash Charge:';
        else if (paymentMethod === 'nagad') chargeLabel = 'Nagad Charge:';
        else if (paymentMethod === 'rocket') chargeLabel = 'Rocket Charge:';
        
        $('#paymentChargeLabel').text(chargeLabel);
        $('#paymentChargeAmount').text(`৳${paymentCharge.toFixed(2)}`);
        $('#paymentChargeRow').show();
    } else {
        $('#paymentChargeRow').hide();
    }
}

function togglePaymentFields() {
    const paymentMethod = $('#paymentMethod').val();
    
    // Hide all payment gateway fields
    $('.payment-gateway-fields').hide();
    
    // Show relevant fields based on payment method
    if (paymentMethod === 'bkash') {
        $('#bkashFields').show();
    } else if (paymentMethod === 'nagad') {
        $('#nagadFields').show();
    } else if (paymentMethod === 'rocket') {
        $('#rocketFields').show();
    }
    
    // Update totals when payment method changes
    updateTotals();
}

function clearCart() {
    if (cart.length === 0) return;
    
    if (confirm('Are you sure you want to clear the cart?')) {
        cart = [];
        updateCartDisplay();
        toastr.info('Cart cleared');
    }
}

// Customer management
function searchCustomers() {
    const search = $('#customerSearch').val();
    if (search.length < 2) {
        $('#customerSuggestions').empty();
        return;
    }
    
    $.get('{{ route("admin.pos.search-customers") }}', { search: search })
    .done(function(response) {
        displayCustomerSuggestions(response.customers);
    });
}

function displayCustomerSuggestions(customers) {
    const suggestions = $('#customerSuggestions');
    
    if (customers.length === 0) {
        suggestions.empty();
        return;
    }
    
    let html = '<div class="list-group">';
    customers.forEach(customer => {
        html += `
            <button type="button" class="list-group-item list-group-item-action" 
                    onclick="selectCustomer(${customer.id}, '${customer.name}', '${customer.phone}', '${customer.email || ''}', '${customer.address || ''}', '${customer.city || ''}')">
                <div class="d-flex justify-content-between">
                    <div>
                        <strong>${customer.name}</strong>
                        <br><small class="text-muted">${customer.phone}</small>
                    </div>
                    <small class="text-muted">${customer.email || ''}</small>
                </div>
            </button>
        `;
    });
    html += '</div>';
    
    suggestions.html(html);
}

function selectCustomer(id, name, phone, email, address, city) {
    selectedCustomer = { id, name, phone, email, address, city };
    
    $('#customerName').val(name);
    $('#customerPhone').val(phone);
    $('#customerEmail').val(email);
    $('#customerAddress').val(address);
    $('#customerCity').val(city);
    
    $('#customerSuggestions').empty();
    $('#customerSearch').val('');
    
    validateForm();
    toastr.success('Customer selected');
}

// Form validation
function validateForm() {
    const hasItems = cart.length > 0;
    const hasName = $('#customerName').val().trim() !== '';
    const hasPhone = $('#customerPhone').val().trim() !== '';
    
    $('#checkoutBtn').prop('disabled', !(hasItems && hasName && hasPhone));
}

// Order processing - Prepare order for review
function processOrder() {
    if (cart.length === 0) {
        toastr.error('Cart is empty');
        return;
    }
    
    const customerName = $('#customerName').val().trim();
    const customerPhone = $('#customerPhone').val().trim();
    
    if (!customerName || !customerPhone) {
        toastr.error('Customer name and phone are required');
        return;
    }
    
    // Validate payment gateway fields
    const paymentMethod = $('#paymentMethod').val();
    if (paymentMethod === 'bkash') {
        if (!$('#bkashNumber').val() || !$('#bkashTrxId').val()) {
            toastr.error('bKash number and transaction ID are required');
            return;
        }
    } else if (paymentMethod === 'nagad') {
        if (!$('#nagadNumber').val() || !$('#nagadTrxId').val()) {
            toastr.error('Nagad number and transaction ID are required');
            return;
        }
    } else if (paymentMethod === 'rocket') {
        if (!$('#rocketNumber').val() || !$('#rocketTrxId').val()) {
            toastr.error('Rocket number and transaction ID are required');
            return;
        }
    }
    
    // Prepare order data for review
    pendingOrder = {
        customer_id: selectedCustomer ? selectedCustomer.id : null,
        customer_name: customerName,
        customer_phone: customerPhone,
        customer_email: $('#customerEmail').val(),
        customer_address: $('#customerAddress').val(),
        customer_city: $('#customerCity').val(),
        order_source: $('#orderSource').val(),
        payment_method: paymentMethod,
        items: cart.map(item => ({
            product_id: item.product_id || item.id,
            combination_id: item.variation_id,
            quantity: item.quantity,
            price: item.price,
            name: item.title,
            display_name: item.display_name
        })),
        discount: parseFloat($('#discountAmount').val()) || 0,
        shipping: parseFloat($('#shippingAmount').val()) || 0,
        total: parseFloat($('#total').text().replace('৳', '')),
        notes: $('#orderNotes').val(),
        
        // Payment gateway fields
        bkash_number: paymentMethod === 'bkash' ? $('#bkashNumber').val() : null,
        bkash_transaction_id: paymentMethod === 'bkash' ? $('#bkashTrxId').val() : null,
        bkash_charge: paymentMethod === 'bkash' ? (parseFloat($('#bkashCharge').val()) || 0) : 0,
        
        nagad_number: paymentMethod === 'nagad' ? $('#nagadNumber').val() : null,
        nagad_transaction_id: paymentMethod === 'nagad' ? $('#nagadTrxId').val() : null,
        nagad_charge: paymentMethod === 'nagad' ? (parseFloat($('#nagadCharge').val()) || 0) : 0,
        
        rocket_number: paymentMethod === 'rocket' ? $('#rocketNumber').val() : null,
        rocket_transaction_id: paymentMethod === 'rocket' ? $('#rocketTrxId').val() : null,
        rocket_charge: paymentMethod === 'rocket' ? (parseFloat($('#rocketCharge').val()) || 0) : 0
    };
    
    // Show order preview modal
    showOrderPreview(pendingOrder);
}

function showOrderPreview(orderData) {
    const modal = $('#orderSuccessModal');
    const body = $('#orderSuccessBody');
    
    // Update modal header for preview
    modal.find('.modal-title').html('<i class="fas fa-receipt text-warning me-2"></i><span>Order Confirmation & Preview</span>');
    modal.find('.modal-header').removeClass('bg-success').addClass('bg-primary');
    
    let itemsHtml = '';
    orderData.items.forEach(item => {
        itemsHtml += `
            <tr>
                <td class="fw-semibold text-dark">
                    ${item.name}
                    ${item.display_name ? `<br><small class="badge bg-light text-secondary border fw-normal mt-1">${item.display_name}</small>` : ''}
                </td>
                <td class="text-center fw-bold">${item.quantity}</td>
                <td class="text-end">৳${item.price.toFixed(2)}</td>
                <td class="text-end fw-bold text-dark">৳${(item.price * item.quantity).toFixed(2)}</td>
            </tr>
        `;
    });
    
    const subtotal = orderData.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    body.html(`
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <div class="pos-preview-card h-100 mb-0">
                    <div class="pos-preview-title">
                        <i class="fas fa-user-circle text-primary"></i> Customer Info
                    </div>
                    <div class="pos-preview-row">
                        <span class="pos-preview-label">Name:</span>
                        <span class="pos-preview-value">${orderData.customer_name}</span>
                    </div>
                    <div class="pos-preview-row">
                        <span class="pos-preview-label">Phone:</span>
                        <span class="pos-preview-value">${orderData.customer_phone}</span>
                    </div>
                    ${orderData.customer_email ? `
                    <div class="pos-preview-row">
                        <span class="pos-preview-label">Email:</span>
                        <span class="pos-preview-value">${orderData.customer_email}</span>
                    </div>` : ''}
                    ${orderData.customer_address ? `
                    <div class="pos-preview-row">
                        <span class="pos-preview-label">Address:</span>
                        <span class="pos-preview-value">${orderData.customer_address}</span>
                    </div>` : ''}
                </div>
            </div>

            <div class="col-md-6">
                <div class="pos-preview-card h-100 mb-0">
                    <div class="pos-preview-title">
                        <i class="fas fa-info-circle text-info"></i> Order Details
                    </div>
                    <div class="pos-preview-row">
                        <span class="pos-preview-label">Payment Method:</span>
                        <span class="pos-preview-value text-uppercase badge bg-light text-dark border">${orderData.payment_method}</span>
                    </div>
                    <div class="pos-preview-row">
                        <span class="pos-preview-label">Order Source:</span>
                        <span class="pos-preview-value">${orderData.order_source}</span>
                    </div>
                    ${orderData.notes ? `
                    <div class="pos-preview-row">
                        <span class="pos-preview-label">Notes:</span>
                        <span class="pos-preview-value text-muted">${orderData.notes}</span>
                    </div>` : ''}

                    ${orderData.payment_method === 'bkash' && orderData.bkash_number ? `
                        <div class="border-top pt-2 mt-2">
                            <div class="small fw-bold text-primary mb-1"><i class="fas fa-mobile-screen me-1"></i> bKash Details</div>
                            <div class="pos-preview-row"><span class="pos-preview-label">Number:</span><span class="pos-preview-value">${orderData.bkash_number}</span></div>
                            <div class="pos-preview-row"><span class="pos-preview-label">Trx ID:</span><span class="pos-preview-value">${orderData.bkash_transaction_id}</span></div>
                        </div>
                    ` : ''}

                    ${orderData.payment_method === 'nagad' && orderData.nagad_number ? `
                        <div class="border-top pt-2 mt-2">
                            <div class="small fw-bold text-danger mb-1"><i class="fas fa-mobile-screen me-1"></i> Nagad Details</div>
                            <div class="pos-preview-row"><span class="pos-preview-label">Number:</span><span class="pos-preview-value">${orderData.nagad_number}</span></div>
                            <div class="pos-preview-row"><span class="pos-preview-label">Trx ID:</span><span class="pos-preview-value">${orderData.nagad_transaction_id}</span></div>
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
        
        <div class="pos-preview-card mb-0">
            <div class="pos-preview-title">
                <i class="fas fa-shopping-bag text-success"></i> Ordered Items (${orderData.items.length})
            </div>
            <div class="table-responsive">
                <table class="table pos-preview-table align-middle">
                    <thead>
                        <tr>
                            <th>Product Details</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsHtml}
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end text-muted">Subtotal:</th>
                            <th class="text-end fw-bold">৳${subtotal.toFixed(2)}</th>
                        </tr>
                        ${orderData.discount > 0 ? `
                        <tr>
                            <th colspan="3" class="text-end text-muted">Discount:</th>
                            <th class="text-end text-danger fw-bold">-৳${orderData.discount.toFixed(2)}</th>
                        </tr>
                        ` : ''}
                        ${orderData.shipping > 0 ? `
                        <tr>
                            <th colspan="3" class="text-end text-muted">Shipping:</th>
                            <th class="text-end fw-bold">৳${orderData.shipping.toFixed(2)}</th>
                        </tr>
                        ` : ''}
                        <tr class="table-primary">
                            <th colspan="3" class="text-end fs-6">Grand Total:</th>
                            <th class="text-end fs-5 text-primary fw-bolder">৳${orderData.total.toFixed(2)}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    `);
    
    modal.modal('show');
}

function showOrderSuccess(order) {
    const modal = $('#orderSuccessModal');
    const body = $('#orderSuccessBody');
    
    // Update modal header for success
    modal.find('.modal-title').html('<i class="fas fa-circle-check text-white me-2"></i><span>Order Completed Successfully!</span>');
    modal.find('.modal-header').removeClass('bg-primary').addClass('bg-success');
    
    // Update footer buttons with clean, modern layout
    modal.find('.modal-footer').html(`
        <div class="d-flex flex-wrap justify-content-between align-items-center w-100 gap-2">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary px-3 fw-bold" onclick="printReceipt(${order.id})">
                    <i class="fas fa-receipt me-1"></i> Print Receipt
                </button>
                <button type="button" class="btn btn-info text-white px-3 fw-bold" onclick="printInvoice(${order.id})">
                    <i class="fas fa-file-invoice me-1"></i> Print Invoice
                </button>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary px-3 fw-semibold" onclick="downloadReceipt(${order.id})">
                    <i class="fas fa-download me-1"></i> Receipt PDF
                </button>
                <button type="button" class="btn btn-outline-secondary px-3 fw-semibold" onclick="downloadInvoice(${order.id})">
                    <i class="fas fa-file-pdf me-1"></i> Invoice PDF
                </button>
                <button type="button" class="btn btn-success px-4 fw-bold" onclick="clearOrderForNewOne(); $('#orderSuccessModal').modal('hide');">
                    <i class="fas fa-plus me-1"></i> New Order
                </button>
            </div>
        </div>
    `);
    
    body.html(`
        <div class="text-center py-2">
            <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-3" style="width: 70px; height: 70px;">
                <i class="fas fa-check fa-2x"></i>
            </div>
            <h4 class="fw-bolder text-dark mb-1">Order #${order.id}</h4>
            <p class="text-muted small mb-4">Transaction has been processed & saved into inventory.</p>

            <div class="row g-3 text-start">
                <div class="col-6">
                    <div class="p-3 bg-white rounded-3 border">
                        <div class="small text-muted fw-semibold">CUSTOMER</div>
                        <div class="fw-bold text-dark fs-6 mt-1">${order.customer_name}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-white rounded-3 border">
                        <div class="small text-muted fw-semibold">TOTAL AMOUNT</div>
                        <div class="fw-bold text-success fs-5 mt-1">৳${parseFloat(order.total || 0).toFixed(2)}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-white rounded-3 border">
                        <div class="small text-muted fw-semibold">PAYMENT METHOD</div>
                        <div class="fw-bold text-uppercase text-dark mt-1">
                            <span class="badge bg-light text-dark border px-2 py-1">${order.payment_method}</span>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-white rounded-3 border">
                        <div class="small text-muted fw-semibold">CHANNEL SOURCE</div>
                        <div class="fw-bold text-dark mt-1">${order.order_source}</div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 pt-2 border-top text-muted small d-flex justify-content-between align-items-center px-1">
                <span><i class="far fa-clock me-1"></i> Processed: ${order.created_at}</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="fas fa-shield-check me-1"></i> Completed</span>
            </div>
        </div>
    `);
    
    modal.modal('show');
}

function startNewOrder() {
    // If there's a pending order, place it first
    if (pendingOrder) {
        placeOrder();
    } else {
        // Just clear and hide modal if no pending order
        clearOrderForNewOne();
        $('#orderSuccessModal').modal('hide');
    }
}

function placeOrder() {
    if (!pendingOrder) {
        toastr.error('No order to place');
        return;
    }
    
    // Show loading state
    const modal = $('#orderSuccessModal');
    const originalFooter = modal.find('.modal-footer').html();
    modal.find('.modal-footer').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Placing order...</div>');
    
    $.post('{{ route("admin.pos.create-order") }}', pendingOrder)
    .done(function(response) {
        if (response.success) {
            toastr.success('Order #' + response.order.id + ' placed successfully!');
            clearOrderForNewOne();
            showOrderSuccess(response.order);
            loadStats();
            pendingOrder = null; // Clear pending order
        } else {
            toastr.error(response.message || 'Failed to place order');
            // Restore original footer on error
            modal.find('.modal-footer').html(originalFooter);
        }
    })
    .fail(function(xhr) {
        const response = xhr.responseJSON;
        toastr.error(response?.message || 'Failed to place order');
        // Restore original footer on error
        modal.find('.modal-footer').html(originalFooter);
    });
}

function clearOrderForNewOne() {
    // This function is called automatically after order creation
    // It clears the cart and forms but doesn't hide the success modal
    cart = [];
    selectedCustomer = null;
    
    // Clear forms
    $('#customerName, #customerPhone, #customerEmail, #customerAddress, #customerCity, #orderNotes').val('');
    $('#customerSearch').val('');
    $('#discountAmount, #shippingAmount').val('0');
    $('#orderSource').val('Physical Store');
    $('#paymentMethod').val('cash');
    
    // Clear payment gateway fields
    $('#bkashNumber, #bkashTrxId, #nagadNumber, #nagadTrxId, #rocketNumber, #rocketTrxId').val('');
    $('#bkashCharge, #nagadCharge, #rocketCharge').val('0');
    $('.payment-gateway-fields').hide();
    
    updateCartDisplay();
}



function printReceipt(orderId) {
    if (!orderId) {
        toastr.error('Order ID is required for printing');
        return;
    }
    
    // Open receipt PDF in new window for printing
    const printUrl = `{{ route('admin.pos.print-receipt', ':orderId') }}`.replace(':orderId', orderId);
    const printWindow = window.open(printUrl, '_blank', 'width=800,height=600');
    
    if (printWindow) {
        printWindow.focus();
        toastr.success('Receipt PDF opened for printing');
    } else {
        toastr.error('Please allow pop-ups to print receipt');
    }
}

function printInvoice(orderId) {
    if (!orderId) {
        toastr.error('Order ID is required for printing');
        return;
    }
    
    // Open invoice PDF in new window for printing
    const printUrl = `{{ route('admin.pos.print-invoice', ':orderId') }}`.replace(':orderId', orderId);
    const printWindow = window.open(printUrl, '_blank', 'width=1200,height=800');
    
    if (printWindow) {
        printWindow.focus();
        toastr.success('Invoice PDF opened for printing');
    } else {
        toastr.error('Please allow pop-ups to print invoice');
    }
}

function downloadReceipt(orderId) {
    if (!orderId) {
        toastr.error('Order ID is required for download');
        return;
    }
    
    // Download receipt as PDF file
    const downloadUrl = `{{ route('admin.pos.download-receipt', ':orderId') }}`.replace(':orderId', orderId);
    window.open(downloadUrl, '_blank');
    
    toastr.success('Receipt PDF download started');
}

function downloadInvoice(orderId) {
    if (!orderId) {
        toastr.error('Order ID is required for download');
        return;
    }
    
    // Download invoice as PDF file
    const downloadUrl = `{{ route('admin.pos.download-invoice', ':orderId') }}`.replace(':orderId', orderId);
    window.open(downloadUrl, '_blank');
    
    toastr.success('Invoice PDF download started');
}



// QR/Barcode Scanner
function setScannerButtons(running) {
    const buttons = $('.scanner-toggle-btn');
    if (running) {
        buttons.html('<i class="fas fa-stop"></i> Stop QR/Barcode Scanner').addClass('btn-danger');
    } else {
        buttons.html('<i class="fas fa-qrcode"></i> Start QR/Barcode Scanner').removeClass('btn-danger');
    }
}

function startScanner() {
    const qrReader = $('#qr-reader');
    
    if (qrReader.is(':visible')) {
        stopScanner();
        return;
    }
    
    qrReader.closest('.scanner-section').addClass('active');
    qrReader.show();
    
    html5QrCode = new Html5Qrcode("qr-reader");
    setScannerButtons(true);
    
    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: { width: 250, height: 250 }
        },
        (decodedText, decodedResult) => {
            // Handle successful scan
            $('#productSearch').val(decodedText);
            searchProducts();
            stopScanner();
            toastr.success('Barcode scanned successfully');
        },
        (errorMessage) => {
            // Handle scan errors silently
        }
    ).catch(err => {
        toastr.error('Camera access denied or not available');
        qrReader.hide();
        setScannerButtons(false);
    });
    
}

function stopScanner() {
    const qrReader = $('#qr-reader');
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            html5QrCode.clear();
            qrReader.hide();
            qrReader.closest('.scanner-section').removeClass('active');
            setScannerButtons(false);
        }).catch(err => {
            // Scanner stop error handled silently
        });
    }
}

// Stats loading
function loadStats() {
    $.get('{{ route("admin.pos.stats") }}')
    .done(function(response) {
        $('#todayOrders').text(response.today.orders);
        $('#todayRevenue').text('৳' + response.today.revenue);
        $('#weekOrders').text(response.week.orders);
        $('#monthRevenue').text('৳' + response.month.revenue);
    })
    .fail(function() {
        // Stats loading failed silently
    });
}

// Keyboard shortcuts
$(document).keydown(function(e) {
    // Ctrl/Cmd + F for search focus
    if ((e.ctrlKey || e.metaKey) && e.keyCode === 70) {
        e.preventDefault();
        $('#productSearch').focus();
    }
    
    // Ctrl/Cmd + Enter for checkout
    if ((e.ctrlKey || e.metaKey) && e.keyCode === 13) {
        if (!$('#checkoutBtn').prop('disabled')) {
            processOrder();
        }
    }
    
    // Escape to clear search
    if (e.keyCode === 27) {
        $('#productSearch').val('').focus();
        searchProducts();
    }
});

// Configure toastr
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: 'toast-top-right',
    timeOut: 3000
};
</script>
@endsection
