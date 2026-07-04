@extends('frontend.checkout.base')

@section('version-styles')
<style>
    /* Old Header Styles */
    .checkout-header {
        font-size: 21px;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 15px;
        position: relative;
        padding-left: 15px;
        padding-top: 2px;
    }

    .checkout-header:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 5px;
        background-color: var(--primary-color);
    }

    .divider {
        height: 1px;
        background-color: var(--border-color);
        margin: 0px 0 15px;
    }

    /* Progress Stepper */
    .checkout-progress {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 30px;
        padding: 20px 0;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .progress-step {
        display: flex;
        align-items: center;
        position: relative;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #9ca3af;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        z-index: 2;
        position: relative;
    }

    .step-circle.completed {
        background: #10b981;
        color: white;
    }

    .step-circle.active {
        background: var(--primary-color);
        color: white;
        box-shadow: 0 0 0 4px rgba(var(--primary-color-rgb), 0.2);
    }

    .step-label {
        margin-top: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
        white-space: nowrap;
    }

    .step-label.completed {
        color: #10b981;
    }

    .step-label.active {
        color: var(--primary-color);
        font-weight: 600;
    }

    .step-line {
        width: 80px;
        height: 3px;
        background: #e5e7eb;
        margin: 0 10px;
        position: relative;
        top: -18px;
    }

    .step-line.completed {
        background: #10b981;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .checkout-progress {
            padding: 15px 10px;
            margin-bottom: 20px;
        }

        .step-circle {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }

        .step-label {
            font-size: 12px;
        }

        .step-line {
            width: 50px;
            top: -16px;
        }
    }

    @media (max-width: 480px) {
        .step-circle {
            width: 30px;
            height: 30px;
            font-size: 12px;
        }

        .step-label {
            font-size: 11px;
        }

        .step-line {
            width: 30px;
            margin: 0 5px;
            top: -14px;
        }
    }

    .order-inner {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 25px;
    }

    .billing-info {
        background-color: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: var(--shadow-sm);
    }

    .billing-header {
        font-size: 20px;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 15px;
    }

    .billing-name,
    .billing-address,
    .billing-upozila,
    .billing-city,
    .billing-phone {
        margin-bottom: 15px;
    }

    .billing-name-input,
    .billing-address-input,
    .billing-upozila-input,
    .billing-city-input,
    .billing-phone-input {
        width: 100%;
        padding: 14px 15px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 15px;
        transition: border-color 0.3s;
    }

    .billing-name-input:focus,
    .billing-address-input:focus,
    .billing-upozila-input:focus,
    .billing-city-input:focus,
    .billing-phone-input:focus {
        border-color: var(--primary-color);
        outline: none;
    }

    .form-error small {
        color: var(--primary-color);
        font-size: 13px;
        display: block;
        margin-top: 5px;
    }

    .customer-note {
        font-size: 15px;
        font-weight: 600;
        color: var(--secondary-color);
        margin: 25px 0 15px;
    }

    .note-label {
        display: block;
        font-size: 14px;
        color: var(--text-color);
        margin-bottom: 10px;
    }

    .note-textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        resize: vertical;
        min-height: 80px;
        font-size: 15px;
        transition: border-color 0.3s;
    }

    .note-textarea:focus {
        border-color: var(--primary-color);
        outline: none;
    }

    .checkout-order-info {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: var(--shadow-sm);
    }

    .product-items {
        padding: 10px 20px;
        background: #fff;
        border-radius: 8px 8px 0 0;
    }

    .product-items .checkout-sub-title-div {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e5e7eb;
    }

    .checkout-sub-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .checkout-sub-title-two {
        font-size: 16px;
        color: #6b7280;
    }

    .checkout-cart-table {
        padding: 20px;
        padding-top: 0px;
    }

    .cct-header {
        font-size: 20px;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 0px;
    }

    .cct-table {
        width: 100%;
        border-collapse: collapse;
    }

    .cct-table-head th {
        text-align: left;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
        font-weight: 600;
        color: var(--secondary-color);
    }

    .cct-table td {
        padding: 10px 0;
        border-bottom: 1px solid var(--border-color);
    }

    .cct-product {
        width: 60%;
    }

    .cct-subtotal {
        width: 40%;
        text-align: right;
    }

    .cct-title a {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }

    .cct-title a:hover {
        color: var(--primary-color);
    }

    .cct-price {
        text-align: right;
        font-weight: 500;
    }

    .cct-shipping-head {
        font-weight: 500;
    }

    .cct-flat {
        text-align: right;
    }

    /* OTP Modal Styles */
    .otp-modal-area {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .hidden {
        display: none;
    }

    .otp-modal-inner {
        background-color: white;
        border-radius: 8px;
        width: 90%;
        max-width: 400px;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
    }

    .otp-modal-inner>div:first-child {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background-color: var(--secondary-color);
        color: white;
    }

    .otp-modal-inner>div:first-child h5 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .otp-modal-inner>div:first-child button {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
    }

    .otp-modal-inner>div:last-child {
        padding: 20px;
    }

    #otp-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    #otp-form div:first-child {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    #otp-form label {
        font-weight: 500;
        color: var(--secondary-color);
    }

    #otp-form input {
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 18px;
        letter-spacing: 5px;
        text-align: center;
    }

    #otp-form div:nth-child(2) {
        display: flex;
        gap: 10px;
    }

    #otp-form button {
        padding: 12px 15px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        flex: 1;
    }

    #otp-form button[type="submit"] {
        background-color: var(--secondary-color);
        color: white;
    }

    #otp-form button.resendotp {
        background-color: var(--light-color);
        color: var(--secondary-color);
    }

    #otp-message {
        margin-top: 15px;
    }

    /* Order Message Styles */
    #order-message {
        margin-top: 20px;
    }

    /* Payment Method Section */
    .payment-method-section {
        background-color: #f5f5f5;
        padding: 20px;
        border-radius: 8px;
        margin-top: 0px;
        padding-bottom: 25px;
    }

    .payment-method-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 10px;
    }

    .payment-option {
        margin-bottom: 12px;
        display: flex;
        align-items: center;
    }

    .mobile-wallet-payment-option {
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .mobile-wallet-option {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        border: 1px solid #b1b1b1ff;
        padding: 5px 8px;
        border-radius: 7px;
        max-width: 200px;
        height: 40px;
    }

    .payment-option input[type="radio"] {
        margin-right: 10px;
    }

    .payment-option label {
        font-size: 15px;
        color: var(--text-color);
    }

    .payment-divider {
        height: 7px;
        background-color: var(--border-color);
        margin: 5px 0;
    }

    .payment-policy {
        font-size: 13px;
        color: var(--text-color);
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .order-button {
        display: block;
        width: 100%;
        padding: 14px;
        background-color: var(--secondary-color);
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .order-button:hover {
        background-color: var(--primary-color);
    }

    /* OTP Modal Styles */
    .otp-modal-area {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .otp-modal-area.hidden {
        display: none;
    }

    .otp-modal-inner {
        background-color: white;
        border-radius: 8px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .otp-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background-color: var(--secondary-color);
        color: white;
    }

    .otp-modal-title {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .otp-close-button {
        background: none;
        border: none;
        color: white;
        cursor: pointer;
    }

    .otp-modal-body {
        padding: 20px;
    }

    .otp-form-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 20px;
    }

    .otp-label {
        font-weight: 500;
        color: var(--secondary-color);
    }

    .otp-input {
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 18px;
        letter-spacing: 5px;
        text-align: center;
    }

    .otp-buttons {
        display: flex;
        gap: 10px;
    }

    .otp-verify-button {
        padding: 12px 15px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        flex: 1;
        background-color: var(--secondary-color);
        color: white;
    }

    .otp-resend-button {
        padding: 12px 15px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        flex: 1;
        background-color: var(--light-color);
        color: var(--secondary-color);
    }

    .otp-verify-button:disabled,
    .otp-resend-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    #otp-message {
        margin-top: 15px;
    }

    /* Order Message Styles */
    #order-message {
        margin-top: 20px;
    }


    /* Responsive Styles */
    @media (max-width: 992px) {
        .checkout-container {
            padding: 10px 5px;
            margin: 0px auto;
        }

        .checkout-header {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .product-items {
            padding: 15px;
        }

        .order-inner {
            display: flex;
            gap: 15px;
            flex-direction: column-reverse;
        }

        .billing-name,
        .billing-address,
        .billing-upozila,
        .billing-city,
        .billing-phone {
            margin-bottom: 10px;
        }

        .customer-note {
            margin: 11px 0 8px;
        }

        .shipping-area {
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .payment-method-section {
            padding: 10px;
            margin-bottom: 10px;
        }

        .mobile-wallet-payment-option {
            margin-bottom: 0px;
        }

        .checkout-order-info {
            order: -1;
        }
    }

    @media (max-width: 576px) {
        .product-items {
            padding: 10px;
        }

        .order-inner {
            gap: 10px;
        }

        .checkout-header {
            margin-bottom: 8px;
        }

        .billing-header,
        .cct-header {
            font-size: 15px;
        }

        .billing-header {
            margin-bottom: 10px;
        }

        .billing-info,
        .checkout-cart-table {
            padding: 10px;
        }
    }
</style>
<style>
    /* ... existing styles ... */

    .product-variations {
        margin-top: 4px;
        font-size: 13px;
        color: #666;
    }

    .product-variations small {
        font-size: 15px;
        border-left: 2px solid #ED1A25;
        padding-left: 6px;
    }

    .product-variations small {
        display: block;
        line-height: 1.4;
    }

    /* ... rest of your styles ... */
</style>
<style>
    /* Order Notification Styles */
    .order-notification {
        background-color: #008ecb;
        color: #f9f9f9;
        padding: 15px 15px;
        border-radius: 10px;
    }
</style>
<style>
    /* Select dropdown styling */
    select.billing-city-input {
        width: 100%;
        padding: 14px 15px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 15px;
        transition: border-color 0.3s;
        background-color: #fff;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
    }

    select.billing-city-input:focus {
        border-color: var(--primary-color);
        outline: none;
    }
</style>
<style>
    /* Shipping Area Styles */
    .shipping-area {
        background-color: #fff;
        border-radius: 8px;
        padding: 0px;
    }

    .shipping-area-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 15px;
    }

    .shipping-option {
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f1f1f1ff;
        padding: 10px;
        border-radius: 5px;
        transition: background 0.2s ease;
        cursor: pointer;
    }

    .shipping-option:has(input[type="radio"]:checked) {
        background: #E4FFE9;

    }

    .shipping-option-left {
        display: flex;
        align-items: center;
    }

    .shipping-option input[type="radio"] {
        margin-right: 10px;
    }

    .shipping-option label {
        font-size: 15px;
        color: var(--text-color);
    }

    .shipping-price {
        font-weight: 500;
    }
</style>
<style>
    /* bKash Modal Styles */
    .bkash-modal-area {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .bkash-modal-inner {
        background: white;
        border-radius: 16px;
        width: 90%;
        height: auto;
        max-width: 480px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .bkash-header {
        background: linear-gradient(135deg, #E2136E, #C8005F);
        padding: 15px 20px;
        color: white;
        position: relative;
    }

    .bkash-logo-section {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2px;
    }

    .bkash-logo {
        font-size: 28px;
        font-weight: bold;
        letter-spacing: -1px;
    }

    .bkash-tagline {
        text-align: center;
        font-size: 14px;
        opacity: 0.9;
    }

    .close-button {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .close-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .payment-info {
        background: #f8fafc;
        padding: 10px;
        border-left: 4px solid #E2136E;
        margin: 8px 20px;
        border-radius: 8px;
    }

    .payment-amount {
        font-size: 24px;
        font-weight: bold;
        color: #E2136E;
        text-align: center;
        margin-bottom: 0px;
    }

    .merchant-info {
        text-align: center;
        color: #64748b;
        font-size: 14px;
    }

    .payment-steps {
        padding: 8px 20px;
    }

    .steps-title {
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .step-icon {
        width: 20px;
        height: 20px;
        background: #E2136E;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
    }

    .steps-list {
        list-style: none;
        counter-reset: step-counter;
    }

    .steps-list li {
        counter-increment: step-counter;
        margin-bottom: 10px;
        padding-left: 35px;
        position: relative;
        color: #475569;
        line-height: 1.4;
    }

    .steps-list li::before {
        content: counter(step-counter);
        position: absolute;
        left: 0;
        top: 0;
        background: #E2136E;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }

    .bkash-number {
        background: #E2136E;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .payment-form {
        padding: 0 20px 0px;
    }

    .form-group {
        margin-bottom: 8px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .form-input:focus {
        outline: none;
        border-color: #E2136E;
        background: white;
        box-shadow: 0 0 0 3px rgba(226, 19, 110, 0.1);
    }

    .confirm-button {
        width: 100%;
        background: linear-gradient(135deg, #E2136E, #C8005F);
        color: white;
        padding: 16px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(226, 19, 110, 0.3);
    }

    .confirm-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(226, 19, 110, 0.4);
    }

    .confirm-button:active {
        transform: translateY(0);
    }

    .security-note {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 12px;
        margin: 10px 20px;
        font-size: 13px;
        color: #0369a1;
        text-align: center;
        display: flex;
    }

    .hidden {
        display: none;
    }

    .success-animation {
        text-align: center;
        padding: 40px 20px;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: #10b981;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 40px;
    }

    tr#bkashChargeRow td:last-child {
        text-align: right;
    }

    @media (max-width: 768px) {
        .bkash-modal-inner {
            width: 97%;
            margin: 5px;
        }

        .bkash-header {
            padding: 10px;
        }

        .payment-info,
        .payment-steps,
        .payment-form {
            padding: 10px;
        }

        .payment-info {
            margin: 8px 10px;
        }
    }
</style>
<style>
    /* Nagad Modal Styles */
    .nagad-modal-area {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .nagad-modal-inner {
        background: white;
        border-radius: 16px;
        width: 90%;
        height: auto;
        max-width: 480px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease-out;
    }

    .nagad-header {
        background-color: #F60;
        color: white;
        padding: 20px;
        position: relative;
        text-align: center;
    }

    .nagad-logo-section {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 5px;
    }

    .nagad-number {
        background: #F60;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .nagad-logo {
        font-size: 28px;
        font-weight: bold;
        color: white;
        letter-spacing: 1px;
    }

    .nagad-tagline {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.9);
        text-align: center;
    }

    /* Custom Button Styles */
    .nagad-confirm-button {
        background-color: #F60;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 16px;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .nagad-confirm-button:hover {
        background-color: #E55A00;
    }

    .hidden {
        display: none;
    }

    tr#nagadChargeRow td:last-child {
        text-align: right;
    }

    @media (max-width: 768px) {
        .nagad-modal-inner {
            width: 97%;
            margin: 5px;
        }

        .nagad-header {
            padding: 10px;
        }

        .payment-info,
        .payment-steps,
        .payment-form {
            padding: 10px;
        }

        .payment-info {
            margin: 8px 10px;
        }
    }
</style>
<style>
    /* Rocket Modal Styles */
    .rocket-modal-area {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .rocket-modal-inner {
        background: white;
        border-radius: 16px;
        width: 90%;
        height: auto;
        max-width: 480px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease-out;
    }

    .rocket-header {
        background: linear-gradient(135deg, #8C3494, #6A2971);
        padding: 15px 20px;
        color: white;
        position: relative;
        text-align: center;
    }

    .rocket-logo-section {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 5px;
    }

    .rocket-logo {
        font-size: 28px;
        font-weight: bold;
        color: white;
        letter-spacing: 1px;
    }

    .rocket-tagline {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.9);
        text-align: center;
    }

    .rocket-close-button {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .rocket-close-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(90deg);
    }

    .rocket-payment-info {
        background: #f8fafc;
        padding: 10px;
        border-left: 4px solid #8C3494;
        margin: 8px;
        border-radius: 8px;
    }

    .rocket-payment-amount {
        font-size: 24px;
        font-weight: bold;
        color: #8C3494;
        text-align: center;
        margin-bottom: 0px;
    }

    .rocket-merchant-info {
        text-align: center;
        color: #64748b;
        font-size: 14px;
    }

    .rocket-payment-steps {
        padding: 8px 20px;
    }

    .rocket-steps-title {
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .rocket-step-icon {
        width: 20px;
        height: 20px;
        background: #8C3494;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
    }

    .rocket-steps-list {
        list-style: none;
        counter-reset: step-counter;
    }

    .rocket-steps-list li {
        counter-increment: step-counter;
        margin-bottom: 10px;
        padding-left: 35px;
        position: relative;
        color: #475569;
        line-height: 1.4;
    }

    .rocket-steps-list li::before {
        content: counter(step-counter);
        position: absolute;
        left: 0;
        top: 0;
        background: #8C3494;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }

    .rocket-number {
        background: #8C3494;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
    }

    .rocket-payment-form {
        padding: 0 20px 20px;
    }

    .rocket-form-group {
        margin-bottom: 15px;
    }

    .rocket-form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
    }

    .rocket-form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .rocket-form-input:focus {
        outline: none;
        border-color: #8C3494;
        background: white;
        box-shadow: 0 0 0 3px rgba(140, 52, 148, 0.1);
    }

    .rocket-confirm-button {
        width: 100%;
        background: linear-gradient(135deg, #8C3494, #6A2971);
        color: white;
        padding: 16px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(140, 52, 148, 0.3);
    }

    .rocket-confirm-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(140, 52, 148, 0.4);
    }

    .rocket-confirm-button:active {
        transform: translateY(0);
    }

    .rocket-security-note {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 12px;
        margin: 10px 20px;
        font-size: 13px;
        color: #0369a1;
        text-align: center;
        display: flex;
    }

    .hidden {
        display: none;
    }

    tr#rocketChargeRow td:last-child {
        text-align: right;
    }

    @media (max-width: 768px) {
        .rocket-modal-inner {
            width: 97%;
            margin: 5px;
        }

        .rocket-header {
            padding: 10px;
        }

        .rocket-payment-info,
        .rocket-payment-steps,
        .rocket-payment-form {
            padding: 10px;
        }
    }
</style>
<style>
    .copy-icon {
        cursor: pointer;
        margin-left: 8px;
        vertical-align: middle;
        color: #C8005F;
        transition: color 0.3s ease;
        width: 21px;
        height: 21px;
        stroke-width: 2.5;
        margin-top: -4px;
    }

    .copy-icon:hover {
        color: #333;
    }

    .payment-number {
        display: inline-flex;
        align-items: center;
    }

    .copy-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #22c55e;
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
        z-index: 9999;
    }
</style>
<style>
    .form-error input,
    .form-error select,
    .form-error textarea {
        border-color: #dc3545;
        !important;
    }

    .error-message {
        color: #dc3545;
        font-size: 13px;
        margin-top: 5px;
    }
</style>
@endsection

@section('checkout-form')
@if(false)
<!-- Progress Stepper -->
<div class="checkout-progress">
    <div class="progress-step">
        <div class="step-item">
            <div class="step-circle completed">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div class="step-label completed">শপিং ব্যাগ</div>
        </div>
    </div>
    <div class="step-line completed"></div>
    <div class="progress-step">
        <div class="step-item">
            <div class="step-circle active">2</div>
            <div class="step-label active">পেমেন্ট</div>
        </div>
    </div>
    <div class="step-line"></div>
    <div class="progress-step">
        <div class="step-item">
            <div class="step-circle">3</div>
            <div class="step-label">ডেলিভারি</div>
        </div>
    </div>
</div>
@else
<h1 class="checkout-header">Checkout</h1>
<div class="divider"></div>
@endif

<form id="order-form">
    @csrf
    <div class="order-inner">

        <div class="checkout-order-info">

            {{-- Product items --}}
            <div id="product-items" class="product-items">
                <div class="checkout-sub-title-div">
                    <h2 class="checkout-sub-title">শপিং ব্যাগ ({{ $groupedCarts->count() }} আইটেম)</h2>
                    <div class="checkout-sub-title-two">মোট ৳{{ $sub_total }}</div>
                </div>

                @foreach ($groupedCarts as $productId => $items)
                @php
                $product = $items->first()->product;
                $firstItem = $items->first();
                $totalQty = $items->sum('qunt');
                $itemPrice = $items->sum(function ($item) {return $item->calculated_subtotal ?? ($item->price * $item->qunt);});

                // Get product image
                if ($product->product_type === 'variable' && $firstItem->variationCombination && $firstItem->variationCombination->featured_image) {
                $image = 'storage/' . $firstItem->variationCombination->featured_image;
                } else {
                $image = 'storage/' . $product->thumb_image;
                }
                @endphp

                <div style="display: flex; align-items: flex-start; gap: 10px; padding: 10px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 10px; background: #fff;">
                    <!-- Product Image -->
                    <img src="{{ asset($image) }}" alt="{{ $product->title }}"
                        style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; flex-shrink: 0;"
                        onerror="this.src='{{ asset('assets/img/product-placeholder.png') }}'">

                    <!-- Product Details -->
                    <div style="flex: 1; min-width: 0;">
                        <h3 style="font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 5px 0; line-height: 1.4;">{{ $product->title }}</h3>

                        <!-- Variation Details -->
                        <div class="product-variations" style="margin-bottom: 0px;">
                            @foreach ($items as $item)
                            @if ($item->product->product_type === 'variable')
                            @php
                            $combinationDetails = null;
                            if ($item->combination_id && $item->variationCombination) {
                            $combination = $item->variationCombination;
                            $combinationDetails = [
                            'display_name' => $combination->display_name,
                            'short_description' => $combination->short_description,
                            'qty' => $item->qunt,
                            'effective_price' => $combination->offer_price ?? $combination->regular_price ?? $combination->price,
                            'has_offer' => $combination->hasOffer(),
                            'regular_price' => $combination->regular_price ?? $combination->price,
                            'offer_price' => $combination->offer_price,
                            ];
                            }
                            @endphp

                            @if ($combinationDetails)
                            <div class="variation-item" style="margin-bottom: 8px;">
                                <div style="font-size: 13px; color: #666;">
                                    <span style="font-weight: 500;">{{ $combinationDetails['display_name'] }}</span>
                                    <span style="color: #999;"> (x{{ $combinationDetails['qty'] }})</span>
                                </div>
                                <div style="margin-top: 4px;">
                                    @if ($combinationDetails['has_offer'])
                                    <span style="font-size: 13px; text-decoration: line-through; color: #999;">{{ $combinationDetails['regular_price'] }}৳</span>
                                    <span style="font-size: 14px; color: #e74c3c; font-weight: 600; margin-left: 6px;">{{ $combinationDetails['offer_price'] }}৳</span>
                                    @else
                                    <span style="font-size: 14px; color: #666;">{{ $combinationDetails['effective_price'] }}৳</span>
                                    @endif
                                </div>
                                @if ($combinationDetails['short_description'])
                                <div style="font-size: 12px; color: #666; margin-top: 4px;">
                                    {{ $combinationDetails['short_description'] }}
                                </div>
                                @endif
                            </div>
                            @endif
                            @else
                            <!-- Simple product details -->
                            <div class="simple-item" style="font-size: 13px; color: #666; margin-bottom: 0px;">
                                <span>Quantity: {{ $item->qunt }}</span>
                                <span style="color: #999;"> • Unit Price: {{ $item->price }}৳ each</span>
                            </div>
                            @endif
                            @endforeach
                        </div>

                        {{-- Display combo selections if this is a combo item --}}
                        @if ($firstItem->combo_offer_id)
                        @php
                        $comboSelections = $firstItem->combo_selections;
                        if (is_string($comboSelections)) {
                        $comboSelections = json_decode($comboSelections, true);
                        }
                        @endphp
                        @if ($comboSelections && is_array($comboSelections))
                        <div style="margin-top: 10px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; margin-bottom: 12px;">
                            <div style="font-weight: 600; color: #333; margin-bottom: 8px; font-size: 13px;">Combo Selections:</div>
                            @foreach ($comboSelections as $selection)
                            @php
                            $selectedProduct = \App\Models\Product::find($selection['product_id']);
                            $selectedVariation = \App\Models\VariationCombination::find($selection['variation_id']);
                            @endphp
                            @if ($selectedProduct)
                            <div style="margin-bottom: 5px; font-size: 12px;">
                                <span style="font-weight: 500;">{{ $selectedProduct->title }}</span>
                                @if ($selectedVariation)
                                <span style="color: #666;"> • {{ $selectedVariation->display_name }}</span>
                                @endif
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @endif
                        @endif

                        <!-- Price Display -->
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 1px;">
                            <span class="item-total-price" style="font-size: 18px; font-weight: 700; color: #E2136E;">৳{{ $itemPrice }}</span>
                            @if($product->old_price && $product->old_price > 0)
                            <span style="font-size: 14px; color: #9ca3af; text-decoration: line-through;">৳{{ $product->old_price * $totalQty }}</span>
                            @php
                            $discount = round((($product->old_price - ($itemPrice / $totalQty)) / $product->old_price) * 100);
                            @endphp
                            <span style="font-size: 12px; color: #f97316; font-weight: 600;">({{ $discount }}% অফ)</span>
                            @endif
                        </div>

                        <!-- Quantity Controls -->
                        <!-- Quantity Controls -->
                        <div class="quantity-controls" data-product-id="{{ $productId }}" style="display: inline-flex; align-items: center; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden;">
                            <button type="button" onclick="updateCartQuantity('{{ $items->first()->id }}', 'decrease', this)" style="background: #fff; border: none; padding: 6px 12px; cursor: pointer; color: #6b7280; font-size: 16px; line-height: 1;">−</button>
                            <input type="text" class="quantity-input" value="{{ $totalQty }}" readonly style="width: 40px; padding: 6px 0; font-size: 14px; font-weight: 500; border: none; border-left: 1px solid #e5e7eb; border-right: 1px solid #e5e7eb; text-align: center; outline: none;">
                            <button type="button" onclick="updateCartQuantity('{{ $items->first()->id }}', 'increase', this)" style="background: #fff; border: none; padding: 6px 12px; cursor: pointer; color: #6b7280; font-size: 16px; line-height: 1;">+</button>
                        </div>
                    </div>

                    <!-- Delete Button -->
                    <a href="{{ route('cart.destroy', $productId) }}?remove_all=1" style="background: #fee2e2; border: none; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: background 0.2s; text-decoration: none;" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                    </a>
                </div>
                @endforeach
            </div>

            {{-- Order Details Table --}}
            <div id="cart-table" class="checkout-cart-table">
                <h2 class="cct-header">চেকআউট সামারি</h2>
                <table class="cct-table">

                    <tr class="cct-table-head">
                        <th class="cct-product">পণ্য</th>
                        <th style="text-align: right;" class="cct-subtotal">মোট মূল্য</th>
                    </tr>

                    @foreach ($groupedCarts as $productId => $items)
                    @php
                    $product = $items->first()->product;
                    $firstItem = $items->first();
                    @endphp
                    <tr data-product-id="{{ $productId }}" style="display: none;"
                        data-category="{{ $product->category->name ?? '' }}"
                        @if ($firstItem->combo_offer_id) data-combo-quantity="{{ $items->sum('qunt') }}" @endif>
                        <td class="cct-title">
                            <a href="">
                                {{ $product->title }}
                            </a>
                            <div class="product-variations">
                                @foreach ($items as $item)
                                @if ($item->product->product_type === 'variable')
                                @php
                                $combinationDetails = null;
                                if ($item->combination_id && $item->variationCombination) {
                                $combination = $item->variationCombination;
                                $combinationDetails = [
                                'display_name' => $combination->display_name,
                                'short_description' => $combination->short_description,
                                'qty' => $item->qunt,
                                'effective_price' => $combination->offer_price ?? $combination->regular_price ?? $combination->price,
                                'has_offer' => $combination->hasOffer(),
                                'regular_price' => $combination->regular_price ?? $combination->price,
                                'offer_price' => $combination->offer_price,
                                ];
                                }
                                @endphp

                                @if ($combinationDetails)
                                <div class="variation-item">
                                    <span class="variation-combination">{{ $combinationDetails['display_name'] }}</span>
                                    <span class="variation-qty">(x{{ $combinationDetails['qty'] }})</span>
                                    <div class="combination-price">
                                        @if ($combinationDetails['has_offer'])
                                        <span class="original-price" style="text-decoration: line-through; color: #999;">{{ $combinationDetails['regular_price'] }}৳</span>
                                        <span class="offer-price" style="color: #e74c3c; font-weight: bold;">{{ $combinationDetails['offer_price'] }}৳</span>
                                        @else
                                        <span class="regular-price">{{ $combinationDetails['effective_price'] }}৳</span>
                                        @endif
                                    </div>
                                    @if ($combinationDetails['short_description'])
                                    <div class="combination-description" style="font-size: 12px; color: #666; margin-top: 5px;">
                                        {{ $combinationDetails['short_description'] }}
                                    </div>
                                    @endif
                                </div>
                                @endif
                                @else
                                <!-- Simple product details -->
                                <div class="simple-item" style="font-size: 14px; color: #666; margin-top: 5px;">
                                    <span class="simple-qty">Quantity: {{ $item->qunt }}</span>
                                    <span class="simple-price"> • Unit Price: {{ $item->price }}৳ each</span>
                                </div>
                                @endif
                                @endforeach
                            </div>

                            {{-- Display combo selections if this is a combo item --}}
                            @if ($firstItem->combo_offer_id)
                            @php
                            $comboSelections = $firstItem->combo_selections;
                            if (is_string($comboSelections)) {
                            $comboSelections = json_decode($comboSelections, true);
                            }
                            @endphp
                            @if ($comboSelections && is_array($comboSelections))
                            <div class="combo-selections" style="margin-top: 10px; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                                <div style="font-weight: 600; color: #333; margin-bottom: 8px;">Combo Selections:</div>
                                @foreach ($comboSelections as $selection)
                                @php
                                $selectedProduct = \App\Models\Product::find($selection['product_id']);
                                $selectedVariation = \App\Models\VariationCombination::find($selection['variation_id']);
                                @endphp
                                @if ($selectedProduct)
                                <div class="combo-selection-item" style="margin-bottom: 5px; font-size: 13px;">
                                    <span class="product-name" style="font-weight: 500;">{{ $selectedProduct->title }}</span>
                                    @if ($selectedVariation)
                                    <span class="variation-name" style="color: #666;"> • {{ $selectedVariation->display_name }}</span>
                                    @endif
                                </div>
                                @endif
                                @endforeach
                            </div>
                            @endif
                            @endif
                        </td>
                        <td class="cct-price">
                            {{ $items->sum(function ($item) {return $item->calculated_subtotal ?? ($item->price * $item->qunt);}) }}৳
                        </td>
                    </tr>
                    @endforeach

                    <tr>
                        <td class="">মোট {{ $groupedCarts->count() }} টি পণ্যের দাম</td>
                        <td class="cct-price" id="Subtotal">{{ $sub_total }}৳</td>
                    </tr>

                    <tr>
                        <td class="cct-shipping-head">শিপিং চার্জ</td>
                        <td class="cct-flat" id="flat-rate">
                            @if ($sub_total >= 1500)
                            <span class="free-shipping">Free shipping</span>
                            @else
                            Flat rate: {{ $shipping }}৳
                            @endif
                        </td>
                        <style>
                            /* Free Shipping Style */
                            .free-shipping {
                                color: #10b981;
                                font-weight: 600;
                            }
                        </style>
                    </tr>

                    <tr id="bkashChargeRow" style="display: none;">
                        <td>Bkash Charge (1.8%)</td>
                        <td><span id="bkashChargeDisplay">0.00</span>৳</td>
                    </tr>
                    <tr id="nagadChargeRow" style="display: none;">
                        <td>Nagad Charge (1.5%)</td>
                        <td><span id="nagadChargeDisplay">0.00</span>৳</td>
                    </tr>
                    <tr id="rocketChargeRow" style="display: none;">
                        <td>Rocket Charge (1.8%)</td>
                        <td><span id="rocketChargeDisplay">0.00</span>৳</td>
                    </tr>

                    <tr>
                        <td class="">সর্বমোট</td>
                        <td class="cct-price order-total" id="flat-rate">
                            {{ $sub_total + $shipping }}৳
                        </td>
                    </tr>

                </table>
            </div>


            <!-- Payment Options -->
            <div class="payment-method-section">
                <h5 class="payment-method-title" style="font-weight: 600;border-bottom: 1px solid #b1b1b1ff;margin-bottom: 10px;">পে করুন</h5>
                <div>
                    @php
                    $codEnabled = setting('ecommerce', 'cod', '1') == '1';
                    $bkashEnabled = setting('ecommerce', 'bkash', '1') == '1';
                    $nagadEnabled = setting('ecommerce', 'nagad', '1') == '1';
                    $rocketEnabled = setting('ecommerce', 'rocket', '1') == '1';
                    // Find the first enabled method for default checked
                    $methods = [];
                    if ($codEnabled) {
                    $methods[] = 'cod';
                    }
                    if ($bkashEnabled) {
                    $methods[] = 'bkash';
                    }
                    if ($nagadEnabled) {
                    $methods[] = 'nagad';
                    }
                    if ($rocketEnabled) {
                    $methods[] = 'rocket';
                    }
                    $defaultMethod = $methods[0] ?? null;
                    @endphp

                    @if ($codEnabled)
                    <!-- Cash on Delivery -->
                    <div class="payment-option mobile-wallet-option">
                        <input type="radio" name="payment_method" id="cod" value="cod" checked>
                        <label for="cod" class="text-sm">ক্যাশ অন ডেলিভারি</label>
                    </div>
                    @endif

                    <h5 class="payment-method-title">মোবাইল ওয়ালেট</h5>
                    <div class="mobile-wallet-payment-option" style="display: flex;">
                        @if ($bkashEnabled)
                        <!-- Bkash -->
                        <div class="payment-option mobile-wallet-option">
                            <input type="radio" name="payment_method" id="bkash" value="bkash">
                            <label for="bkash" class="text-sm">
                                <span><img src="{{ asset('payment-method/bkash.png') }}" alt="Bkash" width="60"><span style="display: none;">Bkash</span></span>
                            </label>
                        </div>
                        @endif
                        @if ($nagadEnabled)
                        <!-- Nagad -->
                        <div class="payment-option mobile-wallet-option">
                            <input type="radio" name="payment_method" id="nagad" value="nagad">
                            <label for="nagad" class="text-sm">
                                <span><img src="{{ asset('payment-method/nagad.png') }}" alt="Nagad" width="60"><span style="display: none;">Nagad</span></span>
                            </label>
                        </div>
                        @endif
                        @if ($rocketEnabled)
                        <!-- Rocket -->
                        <div class="payment-option mobile-wallet-option">
                            <input type="radio" name="payment_method" id="rocket" value="rocket">
                            <label for="rocket" class="text-sm">
                                <span><img src="{{ asset('payment-method/rocket.png') }}" alt="Rocket" width="60"><span style="display: none;">Rocket</span></span>
                            </label>
                        </div>
                        @endif
                    </div>

                    {{-- Automated Payment Gateways --}}
                    @php
                        $autoGateways = \App\Models\PaymentGateway::enabled()->orderBy('sort_order')->get();
                    @endphp
                    @if($autoGateways->count() > 0)
                    <h5 class="payment-method-title" style="margin-top: 10px;">অনলাইন পেমেন্ট</h5>
                    <div class="mobile-wallet-payment-option" style="display: flex; flex-wrap: wrap;">
                        @foreach($autoGateways as $gw)
                        <div class="payment-option mobile-wallet-option">
                            <input type="radio" name="payment_method" id="{{ $gw->provider }}" value="{{ $gw->provider }}">
                            <label for="{{ $gw->provider }}" class="text-sm">
                                <span style="display: inline-flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-credit-card" style="font-size: 18px; color: #2e7d32;"></i>
                                    {{ $gw->name }}
                                </span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="payment-divider"></div>
                <p class="payment-policy">
                    Your personal data will be used to process your order, support your experience throughout this
                    website, and for other purposes described in our privacy policy.
                </p>
                <button type="submit" class="order-button">
                    আপনার অর্ডার নিশ্চিত করুন
                </button>
            </div>


        </div>

        <div class="billing-info">
            <h2 class="billing-header">শিপিং ঠিকানা</h2>

            {{-- Name --}}
            <div class="billing-name">
                <input class="billing-name-input" type="text" name="name" placeholder="আপনার নাম লিখুন"
                    id="" value="{{ auth()->check() ? auth()->user()->name : old('name') }}">
                @if ($errors->get('name'))
                <div class="form-error">
                    @foreach ($errors->get('name') as $error)
                    <small>{{ $error }}</small>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Address --}}
            <div class="billing-address">
                <input class="billing-address-input" type="text" name="address"
                    placeholder="যেখানে ডেলিভারি নিবেন তা লিখুন যেমনঃ হোল্ডিং/গ্রাম/বাজার" id=""
                    value="{{ auth()->check() ? auth()->user()->address : old('address') }}">
                @if ($errors->get('address'))
                <div class="form-error">
                    @foreach ($errors->get('address') as $error)
                    <small>{{ $error }}</small>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Upazila --}}
            {{-- <div class="billing-upozila">
                        <input class="billing-upozila-input" type="text" name="upazila"
                            placeholder="আপনার থানা (পুলিশ স্টেশন) লিখুন" id=""
                            value="{{ auth()->check() ? auth()->user()->upazila : old('upazila') }}">
            @if ($errors->get('upazila'))
            <div class="form-error">
                @foreach ($errors->get('upazila') as $error)
                <small>{{ $error }}</small>
                @endforeach
            </div>
            @endif
        </div> --}}

        {{-- City --}}
        <!-- <div class="billing-city">
                        <select class="billing-city-input" name="city" id="city">
                            <option value="">Select your district</option>
                            <option value="Bagerhat"
                                {{ (auth()->check() && auth()->user()->city == 'Bagerhat') || old('city') == 'Bagerhat' ? 'selected' : '' }}>
                                Bagerhat</option>
                            <option value="Bandarban"
                                {{ (auth()->check() && auth()->user()->city == 'Bandarban') || old('city') == 'Bandarban' ? 'selected' : '' }}>
                                Bandarban</option>
                            <option value="Barguna"
                                {{ (auth()->check() && auth()->user()->city == 'Barguna') || old('city') == 'Barguna' ? 'selected' : '' }}>
                                Barguna</option>
                            <option value="Barishal"
                                {{ (auth()->check() && auth()->user()->city == 'Barishal') || old('city') == 'Barishal' ? 'selected' : '' }}>
                                Barishal</option>
                            <option value="Bhola"
                                {{ (auth()->check() && auth()->user()->city == 'Bhola') || old('city') == 'Bhola' ? 'selected' : '' }}>
                                Bhola</option>
                            <option value="Bogura"
                                {{ (auth()->check() && auth()->user()->city == 'Bogura') || old('city') == 'Bogura' ? 'selected' : '' }}>
                                Bogura</option>
                            <option value="Brahmanbaria"
                                {{ (auth()->check() && auth()->user()->city == 'Brahmanbaria') || old('city') == 'Brahmanbaria' ? 'selected' : '' }}>
                                Brahmanbaria</option>
                            <option value="Chandpur"
                                {{ (auth()->check() && auth()->user()->city == 'Chandpur') || old('city') == 'Chandpur' ? 'selected' : '' }}>
                                Chandpur</option>
                            <option value="Chapainawabganj"
                                {{ (auth()->check() && auth()->user()->city == 'Chapainawabganj') || old('city') == 'Chapainawabganj' ? 'selected' : '' }}>
                                Chapainawabganj</option>
                            <option value="Chattogram"
                                {{ (auth()->check() && auth()->user()->city == 'Chattogram') || old('city') == 'Chattogram' ? 'selected' : '' }}>
                                Chattogram</option>
                            <option value="Chuadanga"
                                {{ (auth()->check() && auth()->user()->city == 'Chuadanga') || old('city') == 'Chuadanga' ? 'selected' : '' }}>
                                Chuadanga</option>
                            <option value="Coxs Bazar"
                                {{ (auth()->check() && auth()->user()->city == 'Coxs Bazar') || old('city') == 'Coxs Bazar' ? 'selected' : '' }}>
                                Cox's Bazar</option>
                            <option value="Cumilla"
                                {{ (auth()->check() && auth()->user()->city == 'Cumilla') || old('city') == 'Cumilla' ? 'selected' : '' }}>
                                Cumilla</option>
                            <option value="Dhaka"
                                {{ (auth()->check() && auth()->user()->city == 'Dhaka') || old('city') == 'Dhaka' ? 'selected' : '' }}>
                                Dhaka</option>
                            <option value="Dinajpur"
                                {{ (auth()->check() && auth()->user()->city == 'Dinajpur') || old('city') == 'Dinajpur' ? 'selected' : '' }}>
                                Dinajpur</option>
                            <option value="Faridpur"
                                {{ (auth()->check() && auth()->user()->city == 'Faridpur') || old('city') == 'Faridpur' ? 'selected' : '' }}>
                                Faridpur</option>
                            <option value="Feni"
                                {{ (auth()->check() && auth()->user()->city == 'Feni') || old('city') == 'Feni' ? 'selected' : '' }}>
                                Feni</option>
                            <option value="Gaibandha"
                                {{ (auth()->check() && auth()->user()->city == 'Gaibandha') || old('city') == 'Gaibandha' ? 'selected' : '' }}>
                                Gaibandha</option>
                            <option value="Gazipur"
                                {{ (auth()->check() && auth()->user()->city == 'Gazipur') || old('city') == 'Gazipur' ? 'selected' : '' }}>
                                Gazipur</option>
                            <option value="Gopalganj"
                                {{ (auth()->check() && auth()->user()->city == 'Gopalganj') || old('city') == 'Gopalganj' ? 'selected' : '' }}>
                                Gopalganj</option>
                            <option value="Habiganj"
                                {{ (auth()->check() && auth()->user()->city == 'Habiganj') || old('city') == 'Habiganj' ? 'selected' : '' }}>
                                Habiganj</option>
                            <option value="Jamalpur"
                                {{ (auth()->check() && auth()->user()->city == 'Jamalpur') || old('city') == 'Jamalpur' ? 'selected' : '' }}>
                                Jamalpur</option>
                            <option value="Jashore"
                                {{ (auth()->check() && auth()->user()->city == 'Jashore') || old('city') == 'Jashore' ? 'selected' : '' }}>
                                Jashore</option>
                            <option value="Jhalokati"
                                {{ (auth()->check() && auth()->user()->city == 'Jhalokati') || old('city') == 'Jhalokati' ? 'selected' : '' }}>
                                Jhalokati</option>
                            <option value="Jhenaidah"
                                {{ (auth()->check() && auth()->user()->city == 'Jhenaidah') || old('city') == 'Jhenaidah' ? 'selected' : '' }}>
                                Jhenaidah</option>
                            <option value="Joypurhat"
                                {{ (auth()->check() && auth()->user()->city == 'Joypurhat') || old('city') == 'Joypurhat' ? 'selected' : '' }}>
                                Joypurhat</option>
                            <option value="Khagrachhari"
                                {{ (auth()->check() && auth()->user()->city == 'Khagrachhari') || old('city') == 'Khagrachhari' ? 'selected' : '' }}>
                                Khagrachhari</option>
                            <option value="Khulna"
                                {{ (auth()->check() && auth()->user()->city == 'Khulna') || old('city') == 'Khulna' ? 'selected' : '' }}>
                                Khulna</option>
                            <option value="Kishoreganj"
                                {{ (auth()->check() && auth()->user()->city == 'Kishoreganj') || old('city') == 'Kishoreganj' ? 'selected' : '' }}>
                                Kishoreganj</option>
                            <option value="Kurigram"
                                {{ (auth()->check() && auth()->user()->city == 'Kurigram') || old('city') == 'Kurigram' ? 'selected' : '' }}>
                                Kurigram</option>
                            <option value="Kushtia"
                                {{ (auth()->check() && auth()->user()->city == 'Kushtia') || old('city') == 'Kushtia' ? 'selected' : '' }}>
                                Kushtia</option>
                            <option value="Lakshmipur"
                                {{ (auth()->check() && auth()->user()->city == 'Lakshmipur') || old('city') == 'Lakshmipur' ? 'selected' : '' }}>
                                Lakshmipur</option>
                            <option value="Lalmonirhat"
                                {{ (auth()->check() && auth()->user()->city == 'Lalmonirhat') || old('city') == 'Lalmonirhat' ? 'selected' : '' }}>
                                Lalmonirhat</option>
                            <option value="Madaripur"
                                {{ (auth()->check() && auth()->user()->city == 'Madaripur') || old('city') == 'Madaripur' ? 'selected' : '' }}>
                                Madaripur</option>
                            <option value="Magura"
                                {{ (auth()->check() && auth()->user()->city == 'Magura') || old('city') == 'Magura' ? 'selected' : '' }}>
                                Magura</option>
                            <option value="Manikganj"
                                {{ (auth()->check() && auth()->user()->city == 'Manikganj') || old('city') == 'Manikganj' ? 'selected' : '' }}>
                                Manikganj</option>
                            <option value="Meherpur"
                                {{ (auth()->check() && auth()->user()->city == 'Meherpur') || old('city') == 'Meherpur' ? 'selected' : '' }}>
                                Meherpur</option>
                            <option value="Moulvibazar"
                                {{ (auth()->check() && auth()->user()->city == 'Moulvibazar') || old('city') == 'Moulvibazar' ? 'selected' : '' }}>
                                Moulvibazar</option>
                            <option value="Munshiganj"
                                {{ (auth()->check() && auth()->user()->city == 'Munshiganj') || old('city') == 'Munshiganj' ? 'selected' : '' }}>
                                Munshiganj</option>
                            <option value="Mymensingh"
                                {{ (auth()->check() && auth()->user()->city == 'Mymensingh') || old('city') == 'Mymensingh' ? 'selected' : '' }}>
                                Mymensingh</option>
                            <option value="Naogaon"
                                {{ (auth()->check() && auth()->user()->city == 'Naogaon') || old('city') == 'Naogaon' ? 'selected' : '' }}>
                                Naogaon</option>
                            <option value="Narail"
                                {{ (auth()->check() && auth()->user()->city == 'Narail') || old('city') == 'Narail' ? 'selected' : '' }}>
                                Narail</option>
                            <option value="Narayanganj"
                                {{ (auth()->check() && auth()->user()->city == 'Narayanganj') || old('city') == 'Narayanganj' ? 'selected' : '' }}>
                                Narayanganj</option>
                            <option value="Narsingdi"
                                {{ (auth()->check() && auth()->user()->city == 'Narsingdi') || old('city') == 'Narsingdi' ? 'selected' : '' }}>
                                Narsingdi</option>
                            <option value="Natore"
                                {{ (auth()->check() && auth()->user()->city == 'Natore') || old('city') == 'Natore' ? 'selected' : '' }}>
                                Natore</option>
                            <option value="Netrokona"
                                {{ (auth()->check() && auth()->user()->city == 'Netrokona') || old('city') == 'Netrokona' ? 'selected' : '' }}>
                                Netrokona</option>
                            <option value="Nilphamari"
                                {{ (auth()->check() && auth()->user()->city == 'Nilphamari') || old('city') == 'Nilphamari' ? 'selected' : '' }}>
                                Nilphamari</option>
                            <option value="Noakhali"
                                {{ (auth()->check() && auth()->user()->city == 'Noakhali') || old('city') == 'Noakhali' ? 'selected' : '' }}>
                                Noakhali</option>
                            <option value="Pabna"
                                {{ (auth()->check() && auth()->user()->city == 'Pabna') || old('city') == 'Pabna' ? 'selected' : '' }}>
                                Pabna</option>
                            <option value="Panchagarh"
                                {{ (auth()->check() && auth()->user()->city == 'Panchagarh') || old('city') == 'Panchagarh' ? 'selected' : '' }}>
                                Panchagarh</option>
                            <option value="Patuakhali"
                                {{ (auth()->check() && auth()->user()->city == 'Patuakhali') || old('city') == 'Patuakhali' ? 'selected' : '' }}>
                                Patuakhali</option>
                            <option value="Pirojpur"
                                {{ (auth()->check() && auth()->user()->city == 'Pirojpur') || old('city') == 'Pirojpur' ? 'selected' : '' }}>
                                Pirojpur</option>
                            <option value="Rajbari"
                                {{ (auth()->check() && auth()->user()->city == 'Rajbari') || old('city') == 'Rajbari' ? 'selected' : '' }}>
                                Rajbari</option>
                            <option value="Rajshahi"
                                {{ (auth()->check() && auth()->user()->city == 'Rajshahi') || old('city') == 'Rajshahi' ? 'selected' : '' }}>
                                Rajshahi</option>
                            <option value="Rangamati"
                                {{ (auth()->check() && auth()->user()->city == 'Rangamati') || old('city') == 'Rangamati' ? 'selected' : '' }}>
                                Rangamati</option>
                            <option value="Rangpur"
                                {{ (auth()->check() && auth()->user()->city == 'Rangpur') || old('city') == 'Rangpur' ? 'selected' : '' }}>
                                Rangpur</option>
                            <option value="Satkhira"
                                {{ (auth()->check() && auth()->user()->city == 'Satkhira') || old('city') == 'Satkhira' ? 'selected' : '' }}>
                                Satkhira</option>
                            <option value="Shariatpur"
                                {{ (auth()->check() && auth()->user()->city == 'Shariatpur') || old('city') == 'Shariatpur' ? 'selected' : '' }}>
                                Shariatpur</option>
                            <option value="Sherpur"
                                {{ (auth()->check() && auth()->user()->city == 'Sherpur') || old('city') == 'Sherpur' ? 'selected' : '' }}>
                                Sherpur</option>
                            <option value="Sirajganj"
                                {{ (auth()->check() && auth()->user()->city == 'Sirajganj') || old('city') == 'Sirajganj' ? 'selected' : '' }}>
                                Sirajganj</option>
                            <option value="Sunamganj"
                                {{ (auth()->check() && auth()->user()->city == 'Sunamganj') || old('city') == 'Sunamganj' ? 'selected' : '' }}>
                                Sunamganj</option>
                            <option value="Sylhet"
                                {{ (auth()->check() && auth()->user()->city == 'Sylhet') || old('city') == 'Sylhet' ? 'selected' : '' }}>
                                Sylhet</option>
                            <option value="Tangail"
                                {{ (auth()->check() && auth()->user()->city == 'Tangail') || old('city') == 'Tangail' ? 'selected' : '' }}>
                                Tangail</option>
                            <option value="Thakurgaon"
                                {{ (auth()->check() && auth()->user()->city == 'Thakurgaon') || old('city') == 'Thakurgaon' ? 'selected' : '' }}>
                                Thakurgaon</option>
                        </select>
                        @if ($errors->get('city'))
                            <div class="form-error">
                                @foreach ($errors->get('city') as $error)
                                    <small>{{ $error }}</small>
                                @endforeach
                            </div>
                        @endif
                    </div> -->

        {{-- Phone --}}
        <div class="billing-phone">
            <input class="billing-phone-input" type="tel" name="phone"
                placeholder="আপনার মোবাইল নাম্বার লিখুন" id=""
                value="{{ auth()->check() ? auth()->user()->phone : old('phone') }}">
            @if ($errors->get('phone'))
            <div class="form-error">
                @foreach ($errors->get('phone') as $error)
                <small>{{ $error }}</small>
                @endforeach
            </div>
            @endif
        </div>

        <h5 class="customer-note">প্রিয়জন কে চিরকুট দিতে আপনার Message এখানে লিখুন</h5>

        <label class="note-label" for="">Order notes (optional)</label>
        <textarea class="note-textarea" name="message" id="" cols="30" rows="3"></textarea>

        <!-- Shipping Area Selection -->
        <div class="shipping-area">
            <h5 class="shipping-area-title">এরিয়া সিলেক্ট করুন</h5>
            @php
            $firstOption = reset($activeShippingOptions);
            $isDeliveryArea = isset($firstOption['rule_type']) && $firstOption['rule_type'] === 'delivery_area';
            @endphp
            <div>
                @foreach ($activeShippingOptions as $key => $option)
                <div class="shipping-option">
                    <div class="shipping-option-left">
                        <input type="radio" name="shipping_area" id="{{ $key }}"
                            value="{{ $key }}"
                            {{ $shipping == $option['cost'] ? 'checked' : '' }}>
                        <label for="{{ $key }}">{{ $option['name'] }}</label>
                    </div>
                    <div class="shipping-price">Tk {{ number_format($option['cost'], 2) }}</div>
                </div>
                @endforeach
            </div>
        </div>


    </div>

    </div>

    <input type="hidden" name="shipping" value="{{ $shipping }}">

    <!-- UTM & Click ID tracking (populated by JavaScript) -->
    <input type="hidden" name="utm_source" id="utm_source" value="">
    <input type="hidden" name="utm_medium" id="utm_medium" value="">
    <input type="hidden" name="utm_campaign" id="utm_campaign" value="">
    <input type="hidden" name="utm_content" id="utm_content" value="">
    <input type="hidden" name="utm_term" id="utm_term" value="">
    <input type="hidden" name="fbclid" id="fbclid" value="">
    <input type="hidden" name="gclid" id="gclid" value="">
    <input type="hidden" name="ttclid" id="ttclid" value="">

</form>
@endsection


@section('version-scripts')
<script>
    function updateCartQuantity(cartId, action, btn) {
        const button = $(btn);
        const container = button.closest('.quantity-controls');
        const productId = container.data('product-id');

        // Disable buttons during request
        container.find('button').prop('disabled', true);

        $.ajax({
            url: '{{ route("cart.update.quantity") }}',
            type: 'POST',
            data: {
                cart_id: cartId,
                action: action,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update input value
                    container.find('.quantity-input').val(response.new_quantity);

                    // Update item total price in the visible card
                    const card = button.closest('.product-item-card'); // Assuming this class exists on the wrapper
                    // If not, we can traverse up. Let's try to find the price element relative to the button
                    // The structure is: .product-item-card -> ... -> .quantity-controls
                    // The price is in a sibling div above quantity controls

                    // Using closest common ancestor approach if class not found
                    const priceElement = button.closest('.product-details').find('.item-total-price');
                    if (priceElement.length) {
                        priceElement.text('৳' + response.new_total);
                    }

                    // Update the hidden row in the summary table
                    // The hidden row has data-product-id attribute
                    const hiddenRow = $('.cct-table tr[data-product-id="' + productId + '"]');
                    if (hiddenRow.length) {
                        hiddenRow.find('.cct-price').text(response.new_total + '৳');
                    }

                    // Recalculate totals using the base function
                    if (typeof updateOrderTotal === 'function') {
                        updateOrderTotal();
                    }

                    // Reload if cart is empty
                    if (response.cart_count === 0) {
                        window.location.reload();
                    }
                } else {
                    // Show error
                    alert(response.message || 'Error updating quantity');
                }
            },
            error: function(xhr) {
                alert('Error updating quantity');
                console.error(xhr);
            },
            complete: function() {
                // Re-enable buttons
                container.find('button').prop('disabled', false);
            }
        });
    }

    // Make the entire shipping option clickable, not just the radio/label
    $(document).on('click', '.shipping-option', function(event) {
        const radio = $(this).find('input[type="radio"]');

        // Avoid double-trigger when the radio itself is clicked
        if (!$(event.target).is('input[type="radio"]')) {
            radio.prop('checked', true).trigger('change');
        }
    });
</script>
@endsection
