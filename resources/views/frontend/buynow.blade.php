@extends('frontend.app')

@php
    $checkout_version = 'v1';
@endphp

@section('styles')
    {{-- Checkout Page Base Styles --}}
    <style>
@section('styles')
    {{-- Modern Redesigned Checkout Page Base Styles --}}
    <style>
        .checkout-container {
            max-width: 1280px;
            margin: 25px auto;
            padding: 0 20px;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .checkout-header {
            font-size: 26px;
            font-weight: 800;
            background: linear-gradient(135deg, #0f172a, #334155);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.5px;
        }

        .checkout-header i {
            -webkit-text-fill-color: #ff6a00;
        }

        .order-inner {
            display: grid;
            grid-template-columns: 1.25fr 0.75fr;
            gap: 30px;
            align-items: start;
        }

        .billing-info, .checkout-order-info {
            background: #ffffff;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .billing-info:hover, .checkout-order-info:hover {
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.08);
        }

        .billing-header, .cct-header {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f8fafc;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .billing-name-input,
        .billing-address-input,
        .billing-upozila-input,
        .billing-city-input,
        .billing-phone-input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            background-color: #f8fafc;
            transition: all 0.2s ease-in-out;
            color: #1e293b;
        }

        .billing-name-input:focus,
        .billing-address-input:focus,
        .billing-upozila-input:focus,
        .billing-city-input:focus,
        .billing-phone-input:focus {
            border-color: #ff6a00;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(255, 106, 0, 0.1);
            outline: none;
        }

        .place-order-btn {
            background: linear-gradient(135deg, #ff6a00 0%, #ee0979 100%);
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            padding: 16px;
            border-radius: 12px;
            border: none;
            width: 100%;
            cursor: pointer;
            box-shadow: 0 10px 25px -5px rgba(255, 106, 0, 0.4);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            letter-spacing: 0.5px;
        }

        .place-order-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(255, 106, 0, 0.6);
        }

        .place-order-btn:active {
            transform: translateY(0);
        }
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
                padding-bottom: 55px;
            }

            .payment-method-title {
                font-size: 16px;
                font-weight: 600;
                color: var(--secondary-color);
                margin-bottom: 15px;
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
                .order-inner {
                    display: flex;
                    flex-direction: column-reverse;
                }

                .checkout-order-info {
                    order: -1;
                }
            }

            @media (max-width: 576px) {
                .checkout-header {
                    font-size: 24px;
                }

                .billing-header,
                .cct-header {
                    font-size: 15px;
                }

                .billing-info,
                .checkout-cart-table {
                    padding: 10px;
                }
            }
        </style>
        <style>
            /* Order Notification Styles */
            .order-notification {
                background-color: #008ecb;
                color: #f9f9f9;
                padding: 15px 15px;
                border-radius: 10px;
                margin-bottom: 10px;
            }
        </style>
        <style>
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

            .quantity-controls {
                display: inline-flex;
                align-items: center;
                border: 1px solid #e5e7eb;
                border-radius: 4px;
                overflow: hidden;
                margin-top: 6px;
            }

            .quantity-controls button {
                background: #f3f4f6;
                border: none;
                padding: 4px 10px;
                cursor: pointer;
                color: #6b7280;
                font-size: 14px;
                line-height: 1;
            }

            .quantity-controls .quantity-input {
                width: 36px;
                padding: 4px 0;
                font-size: 13px;
                font-weight: 600;
                border: none;
                border-left: 1px solid #e5e7eb;
                border-right: 1px solid #e5e7eb;
                text-align: center;
                outline: none;
                background: #fff;
            }
        </style>
        <style>
            /* Combo Offer Styles for Buy Now */

            .combo-badge {
                background-color: #059669;
                color: white;
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
            }

            .combo-selections {
                margin-top: 8px;
            }

            .combo-selection-item {
                font-size: 12px;
                color: #666;
                margin-bottom: 4px;
                padding-left: 15px;
                position: relative;
            }

            .combo-selection-item:before {
                content: "•";
                position: absolute;
                left: 0;
                color: #059669;
                font-weight: bold;
            }

            .product-name {
                font-weight: 500;
            }

            .variation-name {
                color: #888;
            }

            .combo-quantity {
                font-size: 12px;
                color: #666;
                margin-top: 8px;
                font-weight: 500;
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
            .form-error {
                border-color: #dc3545 !important;
            }

            .field-error {
                color: #dc3545;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }

            .order-notification.error-message {
                background-color: #f8d7da;
                border: 1px solid #f5c6cb;
                color: #721c24;
                padding: 1rem;
                margin-bottom: 1rem;
                border-radius: 0.25rem;
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
    @endif

    {{-- Checkout Page V2 Styles --}}
    @if($checkout_version == 'v2')
        <style>
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
                /* display: none; */
            }

            .order-inner {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
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

            .checkout-cart-table {
                padding: 20px;
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
                padding-bottom: 55px;
            }

            .payment-method-title {
                font-size: 16px;
                font-weight: 600;
                color: var(--secondary-color);
                margin-bottom: 15px;
            }

            .payment-option {
                margin-bottom: 12px;
                display: flex;
                align-items: center;
            }

            .payment-option input[type="radio"] {
                margin-right: 10px;
            }

            .payment-option label {
                font-size: 15px;
                color: var(--text-color);
            }

            .payment-divider {
                height: 1px;
                background-color: var(--border-color);
                margin: 15px 0;
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
                .order-inner {
                    display: flex;
                    flex-direction: column-reverse;
                }

                .checkout-order-info {
                    order: -1;
                }
            }

            @media (max-width: 576px) {
                .checkout-header {
                    font-size: 24px;
                }

                .billing-header,
                .cct-header {
                    font-size: 15px;
                }

                .billing-info,
                .checkout-cart-table {
                    padding: 5px;
                }
            }
        </style>
        <style>
            /* Order Notification Styles */
            .order-notification {
                background-color: #008ecb;
                color: #f9f9f9;
                padding: 15px 15px;
                border-radius: 10px;
                margin-bottom: 10px;
            }
        </style>
        <style>
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

            .quantity-controls {
                display: inline-flex;
                align-items: center;
                border: 1px solid #e5e7eb;
                border-radius: 4px;
                overflow: hidden;
                margin-top: 6px;
            }

            .quantity-controls button {
                background: #f3f4f6;
                border: none;
                padding: 4px 10px;
                cursor: pointer;
                color: #6b7280;
                font-size: 14px;
                line-height: 1;
            }

            .quantity-controls .quantity-input {
                width: 36px;
                padding: 4px 0;
                font-size: 13px;
                font-weight: 600;
                border: none;
                border-left: 1px solid #e5e7eb;
                border-right: 1px solid #e5e7eb;
                text-align: center;
                outline: none;
                background: #fff;
            }
        </style>
        <style>
            /* Combo Offer Styles for Buy Now */

            .combo-badge {
                background-color: #059669;
                color: white;
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
            }

            .combo-selections {
                margin-top: 8px;
            }

            .combo-selection-item {
                font-size: 12px;
                color: #666;
                margin-bottom: 4px;
                padding-left: 15px;
                position: relative;
            }

            .combo-selection-item:before {
                content: "•";
                position: absolute;
                left: 0;
                color: #059669;
                font-weight: bold;
            }

            .product-name {
                font-weight: 500;
            }

            .variation-name {
                color: #888;
            }

            .combo-quantity {
                font-size: 12px;
                color: #666;
                margin-top: 8px;
                font-weight: 500;
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
                padding: 5px 20px;
                margin-bottom: 10px;
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
            .form-error {
                border-color: #dc3545 !important;
            }

            .field-error {
                color: #dc3545;
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }

            .order-notification.error-message {
                background-color: #f8d7da;
                border: 1px solid #f5c6cb;
                color: #721c24;
                padding: 1rem;
                margin-bottom: 1rem;
                border-radius: 0.25rem;
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
    @endif

@endsection

@section('content')
    <div class="base-container checkout-container">
        {{-- Checkout Page V1 Content --}}
        @if($checkout_version == 'v1')
            {{-- Checkout Page V2 Content --}}
            <h1 class="checkout-header">Checkout</h1>
            <div class="divider"></div>
            <form id="buynow-order">
                @csrf
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
                    {{-- Hidden fields for regular product --}}
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
                <div class="order-inner">

                
                    <div class="checkout-order-info">
                        {{-- Order Details Table --}}
                        <div id="cart-table" class="checkout-cart-table">
                            <h2 class="cct-header">শপিং ব্যাগ (1 আইটেম)</h2>
                            <table class="cct-table">

                                <tr class="cct-table-head">
                                    <th class="cct-product">Product</th>
                                    <th class="cct-subtotal checkout-sub-title-two" style="text-align: right;">মোট ৳{{ $sub_total }}</th>
                                </tr>
                                
                                @if ($isComboPurchase)
                                    {{-- Display Combo Offer --}}
                                    @php
                                        $primaryComboProduct = null;
                                        if (isset($comboData['selections'][0]['product_id'])) {
                                            $primaryComboProduct = \App\Models\Product::find($comboData['selections'][0]['product_id']);
                                        }
                                    @endphp
                                    <tr data-combo-id="{{ $comboData['combo_offer_id'] }}" id="combo-order-row" data-unit-price="{{ $comboData['combo_price'] }}">
                                        <td class="cct-title" id="p-title">
                                            <div style="display: flex; align-items: flex-start; gap: 15px;">
                                                @if ($primaryComboProduct && $primaryComboProduct->thumb_image)
                                                    <div class="product-image" style="width: 60px; height: 60px; flex-shrink: 0; border-radius: 6px; overflow: hidden; border: 1px solid #e5e7eb;">
                                                        <img src="{{ asset('storage/' . $primaryComboProduct->thumb_image) }}" alt="{{ $primaryComboProduct->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                @endif
                                                <div class="product-info" style="flex: 1;">
                                                    <div class="combo-title">
                                                        <strong>{{ $comboData['combo_title'] }}</strong>
                                                    </div>
                                                    <span class="combo-badge">Combo Offer</span>
                                                    <div class="combo-selections">
                                                        @if(isset($comboData['selections']) && is_array($comboData['selections']) && count($comboData['selections']) > 0)
                                                            @foreach ($comboData['selections'] as $selection)
                                                            @php
                                                                $product = \App\Models\Product::find($selection['product_id']);
                                                                $variation = null;
                                                                if ($selection['variation_id']) {
                                                                    $variation = \App\Models\VariationCombination::find($selection['variation_id']);
                                                                }
                                                            @endphp
                                                            @if ($product)
                                                                <div class="combo-selection-item">
                                                                    <span class="product-name">{{ $product->title }}</span>
                                                                    @if ($variation)
                                                                        <span class="variation-name">({{ $variation->display_name }})</span>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            @endforeach
                                                        @else
                                                            <div class="combo-selection-item">
                                                                <small>No selections available</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="combo-quantity">
                                                        Quantity: <span class="combo-qty-label">{{ $comboData['quantity'] }}</span>
                                                    </div>
                                                    <div class="quantity-controls" data-context="combo">
                                                        <button type="button" class="quantity-btn" data-context="combo" data-action="decrease">−</button>
                                                        <input type="text" class="quantity-input" data-context="combo" value="{{ $comboData['quantity'] }}" readonly>
                                                        <button type="button" class="quantity-btn" data-context="combo" data-action="increase">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="cct-price" id="combo-price">
                                            {{ $comboData['combo_price'] * $comboData['quantity'] }}৳
                                        </td>
                                    </tr>
                                @else
                                    {{-- Display Single Product --}}
                                    @php
                                        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $product->title)));
                                        $productImage = $product->thumb_image ?? null;
                                        $unitPrice = $pqty > 0 ? $price / $pqty : $price;
                                    @endphp
                                    <tr data-product-id="{{ $product->id }}" data-category="{{ $product->category->name ?? '' }}" id="single-order-row" data-unit-price="{{ $unitPrice }}">
                                        <td class="cct-title" id="p-title">
                                            <div style="display: flex; align-items: flex-start; gap: 15px;">
                                                @if ($productImage)
                                                    <div class="product-image" style="width: 60px; height: 60px; flex-shrink: 0; border-radius: 6px; overflow: hidden; border: 1px solid #e5e7eb;">
                                                        <img src="{{ asset('storage/' . $productImage) }}" alt="{{ $product->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                @endif
                                                <div class="product-info">
                                                    <a href="{{ route('product.single', ['id' => $product->id, 'slug' => $slug]) }}">
                                                        {{ $product->title }} <span class="product-qty-label">X {{ $pqty }}</span>
                                                    </a>

                                                    @if (isset($combination))
                                                        {{-- Display combination details --}}
                                                        <div class="product-variations">
                                                            <small>
                                                                {{ $combination->display_name }}
                                                                @if($combination->short_description)
                                                                    <br>{{ $combination->short_description }}
                                                                @endif
                                                            </small>
                                                        </div>
                                                    @elseif (isset($combination) && $combination)
                                                        {{-- New variation combination system --}}
                                                        <div class="product-variations">
                                                            @php
                                                                $combinationOptions = is_array($combination->variation_options) 
                                                                    ? $combination->variation_options 
                                                                    : json_decode($combination->variation_options, true);
                                                                
                                                                foreach ($combinationOptions as $optionId) {
                                                                    $option = \App\Models\VariationOption::find($optionId);
                                                                    if ($option && $option->variation) {
                                                                        $optionName = $option->name ?? '';
                                                                        $variationName = $option->variation->name ?? '';
                                                                        echo "<small>{$variationName} : {$optionName}</small><br>";
                                                                    }
                                                                }
                                                            @endphp
                                                        </div>
                                                    @elseif(isset($option_id) && !empty($option_id))
                                                        {{-- Legacy single option support --}}
                                                        <div class="product-variations">
                                                            @php
                                                                $productOption = \App\Models\ProductVariationOption::with(
                                                                    'variationOption.variation',
                                                                )->find($option_id);
                                                                if ($productOption) {
                                                                    $optionName = $productOption->variationOption->name ?? '';
                                                                    $variationName =
                                                                        $productOption->variationOption->variation->name ?? '';
                                                                }
                                                            @endphp
                                                            @if (isset($variationName) && isset($optionName))
                                                                <small>{{ $variationName }}: {{ $optionName }}</small>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    <div class="quantity-controls" data-context="single">
                                                        <button type="button" class="quantity-btn" data-context="single" data-action="decrease">−</button>
                                                        <input type="text" class="quantity-input" data-context="single" value="{{ $pqty }}" readonly>
                                                        <button type="button" class="quantity-btn" data-context="single" data-action="increase">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="cct-price" id="single-price">
                                            {{ $price }}৳
                                        </td>
                                    </tr>
                                @endif
                                
                                <tr>
                                    <td class="">Subtotal</td>
                                    <td class="cct-price" id="Subtotal">
                                        @if ($isComboPurchase)
                                            {{ $comboData['combo_price'] * $comboData['quantity'] }}৳
                                        @else
                                            {{ $sub_total }}৳
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="cct-shipping-head">Shipping</td>
                                    <td class="cct-flat" id="flat-rate">
                                        @if ($isComboPurchase)
                                            @php
                                                $comboSubtotal = $comboData['combo_price'] * $comboData['quantity'];
                                            @endphp
                                            @if ($comboSubtotal >= 1500)
                                                <span class="free-shipping">Free shipping </span>
                                            @else
                                                Flat rate: {{ $shipping }}৳
                                            @endif
                                        @else
                                            @if ($sub_total >= 1500)
                                                <span class="free-shipping">Free shipping </span>
                                            @else
                                                Flat rate: {{ $shipping }}৳
                                            @endif
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
                                    <td class=""> Total</td>
                                    <td class="cct-price order-total" id="flat-rate">
                                        @if ($isComboPurchase)
                                            @php
                                                $comboSubtotal = $comboData['combo_price'] * $comboData['quantity'];
                                                $comboTotal = $comboSubtotal + $shipping;
                                            @endphp
                                            {{ $comboTotal }}৳
                                        @else
                                            {{ $sub_total + $shipping }}৳
                                        @endif
                                    </td>
                                </tr>

                            </table>
                        </div>

                        @if ($isComboPurchase)
                            {{-- Hidden inputs for combo purchase --}}
                            <input type="hidden" name="combo_offer_id" value="{{ $comboData['combo_offer_id'] }}">
                            <input type="hidden" name="quantity" value="{{ $comboData['quantity'] }}">
                            <input type="hidden" name="price" value="{{ $comboData['combo_price'] }}">
                            <input type="hidden" name="shipping" value="{{ $shipping }}">
                            <input type="hidden" name="is_combo_purchase" value="1">
                            
                            {{-- Add selections as hidden inputs --}}
                            @if(isset($comboData['selections']) && is_array($comboData['selections']) && count($comboData['selections']) > 0)
                                @foreach ($comboData['selections'] as $index => $selection)
                                <input type="hidden" name="selections[{{ $index }}][product_id]" value="{{ $selection['product_id'] }}">
                                <input type="hidden" name="selections[{{ $index }}][variation_id]" value="{{ $selection['variation_id'] ?? '' }}">
                                <input type="hidden" name="selections[{{ $index }}][slot_index]" value="{{ $selection['slot_index'] }}">
                            @endforeach
                        @endif
                        @else
                            {{-- Hidden inputs for single product --}}
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="pqty" value="{{ $pqty }}">
                            <input type="hidden" name="price" value="{{ $sub_total }}">
                            <input type="hidden" name="shipping" value="{{ $shipping }}">

                            {{-- Handle combination for variable products --}}
                            @if (isset($combination))
                                <input type="hidden" name="combination_id" value="{{ $combination->id }}">
                            @elseif (isset($option_id) && !empty($option_id))
                                {{-- Legacy support for old variation system --}}
                                <input type="hidden" name="option_id" value="{{ $option_id }}">
                            @endif

                            {{-- Support for variation combinations --}}
                            @if (isset($combination) && $combination)
                                <input type="hidden" name="combination_id" value="{{ $combination->id }}">
                            @endif
                        @endif

                        

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
                                        <label for="cod" class="text-sm">Cash on Delivery</label>
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

                                @php
                                    $autoGateways = \App\Models\PaymentGateway::enabled()->orderBy('sort_order')->get();
                                @endphp
                                @if($autoGateways->count() > 0)
                                <h5 class="payment-method-title" style="margin-top: 10px;">অনলাইন পেমেন্ট</h5>
                                <div class="mobile-wallet-payment-option" style="display: flex; flex-wrap: wrap;">
                                    @foreach($autoGateways as $gw)
                                    <div class="payment-option mobile-wallet-option">
                                        <input type="radio" name="payment_method" id="{{ $gw->provider }}_bn" value="{{ $gw->provider }}">
                                        <label for="{{ $gw->provider }}_bn" class="text-sm">
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
                        <!-- Validation Message -->
                        <div id="order-message" class=""></div>
                        
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

                        <h5 class="customer-note">প্রিয়জন কে চিরকুট দিতে আপনার Message এখানে লিখুন</h5>

                        <label class="note-label" for="">Order notes (optional)</label>
                        <textarea class="note-textarea" name="message" id="" cols="30" rows="3"></textarea>

                        <!-- Shipping Area Selection -->
                        <div class="shipping-area">
                            <h5 class="shipping-area-title">এরিয়া সিলেক্ট করুন</h5>
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

                    <!-- UTM & Click ID tracking (populated by JavaScript) -->
                    <input type="hidden" name="utm_source" class="utm_source" value="">
                    <input type="hidden" name="utm_medium" class="utm_medium" value="">
                    <input type="hidden" name="utm_campaign" class="utm_campaign" value="">
                    <input type="hidden" name="utm_content" class="utm_content" value="">
                    <input type="hidden" name="utm_term" class="utm_term" value="">
                    <input type="hidden" name="fbclid" class="fbclid" value="">
                    <input type="hidden" name="gclid" class="gclid" value="">
                    <input type="hidden" name="ttclid" class="ttclid" value="">

                </div>

            </form>
        @endif

        @if($checkout_version == 'v2')
            {{-- Checkout Page V2 Content --}}
            <h1 class="checkout-header">Checkout</h1>
            <div class="divider"></div>
            <form id="buynow-order">
                @csrf
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
                    {{-- Hidden fields for regular product --}}
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
                <div class="order-inner">

                    <div class="billing-info">
                        <h2 class="billing-header">Billing details</h2>
                        <!-- Validation Message -->
                        <div id="order-message" class=""></div>
                        
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

                        <h5 class="customer-note">প্রিয়জন কে চিরকুট দিতে আপনার Message এখানে লিখুন</h5>

                        <label class="note-label" for="">Order notes (optional)</label>
                        <textarea class="note-textarea" name="message" id="" cols="30" rows="5"></textarea>

                    </div>

                    <div class="checkout-order-info">
                        {{-- Order Details Table --}}
                        <div id="cart-table" class="checkout-cart-table">
                            <h2 class="cct-header">Your order</h2>
                            <table class="cct-table">

                                <tr class="cct-table-head">
                                    <th class="cct-product">Product</th>
                                    <th class="cct-subtotal" style="text-align: right;">Subtotal</th>
                                </tr>
                                
                                @if ($isComboPurchase)
                                    {{-- Display Combo Offer --}}
                                    @php
                                        $primaryComboProduct = null;
                                        if (isset($comboData['selections'][0]['product_id'])) {
                                            $primaryComboProduct = \App\Models\Product::find($comboData['selections'][0]['product_id']);
                                        }
                                    @endphp
                                    <tr data-combo-id="{{ $comboData['combo_offer_id'] }}" id="combo-order-row" data-unit-price="{{ $comboData['combo_price'] }}">
                                        <td class="cct-title" id="p-title">
                                            <div style="display: flex; align-items: flex-start; gap: 15px;">
                                                @if ($primaryComboProduct && $primaryComboProduct->thumb_image)
                                                    <div class="product-image" style="width: 60px; height: 60px; flex-shrink: 0; border-radius: 6px; overflow: hidden; border: 1px solid #e5e7eb;">
                                                        <img src="{{ asset('storage/' . $primaryComboProduct->thumb_image) }}" alt="{{ $primaryComboProduct->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                @endif
                                                <div class="product-info" style="flex: 1;">
                                                    <div class="combo-title">
                                                        <strong>{{ $comboData['combo_title'] }}</strong>
                                                    </div>
                                                    <span class="combo-badge">Combo Offer</span>
                                                    <div class="combo-selections">
                                                        @if(isset($comboData['selections']) && is_array($comboData['selections']) && count($comboData['selections']) > 0)
                                                            @foreach ($comboData['selections'] as $selection)
                                                            @php
                                                                $product = \App\Models\Product::find($selection['product_id']);
                                                                $variation = null;
                                                                if ($selection['variation_id']) {
                                                                    $variation = \App\Models\VariationCombination::find($selection['variation_id']);
                                                                }
                                                            @endphp
                                                            @if ($product)
                                                                <div class="combo-selection-item">
                                                                    <span class="product-name">{{ $product->title }}</span>
                                                                    @if ($variation)
                                                                        <span class="variation-name">({{ $variation->display_name }})</span>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            @endforeach
                                                        @else
                                                            <div class="combo-selection-item">
                                                                <small>No selections available</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="combo-quantity">
                                                        Quantity: <span class="combo-qty-label">{{ $comboData['quantity'] }}</span>
                                                    </div>
                                                    <div class="quantity-controls" data-context="combo">
                                                        <button type="button" class="quantity-btn" data-context="combo" data-action="decrease">−</button>
                                                        <input type="text" class="quantity-input" data-context="combo" value="{{ $comboData['quantity'] }}" readonly>
                                                        <button type="button" class="quantity-btn" data-context="combo" data-action="increase">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="cct-price" id="combo-price">
                                            {{ $comboData['combo_price'] * $comboData['quantity'] }}৳
                                        </td>
                                    </tr>
                                @else
                                    {{-- Display Single Product --}}
                                    @php
                                        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $product->title)));
                                        $productImage = $product->thumb_image ?? null;
                                        $unitPrice = $pqty > 0 ? $price / $pqty : $price;
                                    @endphp
                                    <tr data-product-id="{{ $product->id }}" data-category="{{ $product->category->name ?? '' }}" id="single-order-row" data-unit-price="{{ $unitPrice }}">
                                        <td class="cct-title" id="p-title">
                                            <div style="display: flex; align-items: flex-start; gap: 15px;">
                                                @if ($productImage)
                                                    <div class="product-image" style="width: 60px; height: 60px; flex-shrink: 0; border-radius: 6px; overflow: hidden; border: 1px solid #e5e7eb;">
                                                        <img src="{{ asset('storage/' . $productImage) }}" alt="{{ $product->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                    </div>
                                                @endif
                                                <div class="product-info">
                                                    <a href="{{ route('product.single', ['id' => $product->id, 'slug' => $slug]) }}">
                                                        {{ $product->title }} <span class="product-qty-label">X {{ $pqty }}</span>
                                                    </a>

                                                    @if (isset($combination))
                                                        {{-- Display combination details --}}
                                                        <div class="product-variations">
                                                            <small>
                                                                {{ $combination->display_name }}
                                                                @if($combination->short_description)
                                                                    <br>{{ $combination->short_description }}
                                                                @endif
                                                            </small>
                                                        </div>
                                                    @elseif (isset($combination) && $combination)
                                                        {{-- New variation combination system --}}
                                                        <div class="product-variations">
                                                            @php
                                                                $combinationOptions = is_array($combination->variation_options) 
                                                                    ? $combination->variation_options 
                                                                    : json_decode($combination->variation_options, true);
                                                                
                                                                foreach ($combinationOptions as $optionId) {
                                                                    $option = \App\Models\VariationOption::find($optionId);
                                                                    if ($option && $option->variation) {
                                                                        $optionName = $option->name ?? '';
                                                                        $variationName = $option->variation->name ?? '';
                                                                        echo "<small>{$variationName} : {$optionName}</small><br>";
                                                                    }
                                                                }
                                                            @endphp
                                                        </div>
                                                    @elseif(isset($option_id) && !empty($option_id))
                                                        {{-- Legacy single option support --}}
                                                        <div class="product-variations">
                                                            @php
                                                                $productOption = \App\Models\ProductVariationOption::with(
                                                                    'variationOption.variation',
                                                                )->find($option_id);
                                                                if ($productOption) {
                                                                    $optionName = $productOption->variationOption->name ?? '';
                                                                    $variationName =
                                                                        $productOption->variationOption->variation->name ?? '';
                                                                }
                                                            @endphp
                                                            @if (isset($variationName) && isset($optionName))
                                                                <small>{{ $variationName }}: {{ $optionName }}</small>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    <div class="quantity-controls" data-context="single">
                                                        <button type="button" class="quantity-btn" data-context="single" data-action="decrease">−</button>
                                                        <input type="text" class="quantity-input" data-context="single" value="{{ $pqty }}" readonly>
                                                        <button type="button" class="quantity-btn" data-context="single" data-action="increase">+</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="cct-price" id="single-price">
                                            {{ $price }}৳
                                        </td>
                                    </tr>
                                @endif
                                
                                <tr>
                                    <td class="">Subtotal</td>
                                    <td class="cct-price" id="Subtotal">
                                        @if ($isComboPurchase)
                                            {{ $comboData['combo_price'] * $comboData['quantity'] }}৳
                                        @else
                                            {{ $sub_total }}৳
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="cct-shipping-head">Shipping</td>
                                    <td class="cct-flat" id="flat-rate">
                                        @if ($isComboPurchase)
                                            @php
                                                $comboSubtotal = $comboData['combo_price'] * $comboData['quantity'];
                                            @endphp
                                            @if ($comboSubtotal >= 1500)
                                                <span class="free-shipping">Free shipping </span>
                                            @else
                                                Flat rate: {{ $shipping }}৳
                                            @endif
                                        @else
                                            @if ($sub_total >= 1500)
                                                <span class="free-shipping">Free shipping </span>
                                            @else
                                                Flat rate: {{ $shipping }}৳
                                            @endif
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
                                    <td class=""> Total</td>
                                    <td class="cct-price order-total" id="flat-rate">
                                        @if ($isComboPurchase)
                                            @php
                                                $comboSubtotal = $comboData['combo_price'] * $comboData['quantity'];
                                                $comboTotal = $comboSubtotal + $shipping;
                                            @endphp
                                            {{ $comboTotal }}৳
                                        @else
                                            {{ $sub_total + $shipping }}৳
                                        @endif
                                    </td>
                                </tr>

                            </table>
                        </div>

                        @if ($isComboPurchase)
                            {{-- Hidden inputs for combo purchase --}}
                            <input type="hidden" name="combo_offer_id" value="{{ $comboData['combo_offer_id'] }}">
                            <input type="hidden" name="quantity" value="{{ $comboData['quantity'] }}">
                            <input type="hidden" name="price" value="{{ $comboData['combo_price'] }}">
                            <input type="hidden" name="shipping" value="{{ $shipping }}">
                            <input type="hidden" name="is_combo_purchase" value="1">
                            
                            {{-- Add selections as hidden inputs --}}
                            @if(isset($comboData['selections']) && is_array($comboData['selections']) && count($comboData['selections']) > 0)
                                @foreach ($comboData['selections'] as $index => $selection)
                                <input type="hidden" name="selections[{{ $index }}][product_id]" value="{{ $selection['product_id'] }}">
                                <input type="hidden" name="selections[{{ $index }}][variation_id]" value="{{ $selection['variation_id'] ?? '' }}">
                                <input type="hidden" name="selections[{{ $index }}][slot_index]" value="{{ $selection['slot_index'] }}">
                            @endforeach
                        @endif
                        @else
                            {{-- Hidden inputs for single product --}}
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="pqty" value="{{ $pqty }}">
                            <input type="hidden" name="price" value="{{ $sub_total }}">
                            <input type="hidden" name="shipping" value="{{ $shipping }}">

                            {{-- Handle combination for variable products --}}
                            @if (isset($combination))
                                <input type="hidden" name="combination_id" value="{{ $combination->id }}">
                            @elseif (isset($option_id) && !empty($option_id))
                                {{-- Legacy support for old variation system --}}
                                <input type="hidden" name="option_id" value="{{ $option_id }}">
                            @endif

                            {{-- Support for variation combinations --}}
                            @if (isset($combination) && $combination)
                                <input type="hidden" name="combination_id" value="{{ $combination->id }}">
                            @endif
                        @endif

                        <!-- Shipping Area Selection -->
                        <div class="shipping-area">
                            <h5 class="shipping-area-title">Shipping Area</h5>
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

                        <!-- Payment Options -->
                        <div class="payment-method-section">
                            <h5 class="payment-method-title">Payment Method</h5>
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
                                    <div class="payment-option">
                                        <input type="radio" name="payment_method" id="cod" value="cod" checked>
                                        <label for="cod" class="text-sm">Cash on Delivery</label>
                                    </div>
                                @endif
                                @if ($bkashEnabled)
                                    <!-- Bkash -->
                                    <div class="payment-option">
                                        <input type="radio" name="payment_method" id="bkash" value="bkash">
                                        <label for="bkash" class="text-sm">Bkash</label>
                                    </div>
                                @endif
                                @if ($nagadEnabled)
                                    <!-- Nagad -->
                                    <div class="payment-option">
                                        <input type="radio" name="payment_method" id="nagad" value="nagad">
                                        <label for="nagad" class="text-sm">Nagad</label>
                                    </div>
                                @endif
                                @if ($rocketEnabled)
                                    <!-- Rocket -->
                                    <div class="payment-option">
                                        <input type="radio" name="payment_method" id="rocket" value="rocket">
                                        <label for="rocket" class="text-sm">Rocket</label>
                                    </div>
                                @endif

                                @php
                                    $autoGateways = \App\Models\PaymentGateway::enabled()->orderBy('sort_order')->get();
                                @endphp
                                @if($autoGateways->count() > 0)
                                    @foreach($autoGateways as $gw)
                                    <div class="payment-option">
                                        <input type="radio" name="payment_method" id="{{ $gw->provider }}_en" value="{{ $gw->provider }}">
                                        <label for="{{ $gw->provider }}_en" class="text-sm">
                                            <i class="fas fa-credit-card" style="color: #2e7d32; margin-right: 4px;"></i>
                                            {{ $gw->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="payment-divider"></div>
                            <p class="payment-policy">
                                Your personal data will be used to process your order, support your experience throughout this
                                website, and for other purposes described in our privacy policy.
                            </p>
                            <button type="submit" class="order-button">
                                অর্ডার Confirm করুন
                            </button>
                        </div>


                    </div>

                    <!-- UTM & Click ID tracking (populated by JavaScript) -->
                    <input type="hidden" name="utm_source" class="utm_source" value="">
                    <input type="hidden" name="utm_medium" class="utm_medium" value="">
                    <input type="hidden" name="utm_campaign" class="utm_campaign" value="">
                    <input type="hidden" name="utm_content" class="utm_content" value="">
                    <input type="hidden" name="utm_term" class="utm_term" value="">
                    <input type="hidden" name="fbclid" class="fbclid" value="">
                    <input type="hidden" name="gclid" class="gclid" value="">
                    <input type="hidden" name="ttclid" class="ttclid" value="">

                </div>

            </form>
        @endif

        <!-- OTP Verification Modal -->
        <div id="otpModal" class="otp-modal-area hidden">
            <div class="otp-modal-inner">
                <div class="otp-modal-header">
                    <h5 class="otp-modal-title">Mobile Verification</h5>
                    <button type="button" class="text-gray-600 hover:text-gray-800" onclick="closeOtpModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <div class="otp-modal-body">
                    <form id="otp-form">
                        <div class="otp-form-group">
                            <label for="otp" class="otp-label">Enter OTP</label>
                            <input type="text" class="otp-input" id="otp" name="otp" maxlength="4"
                                required>
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="otp-verify-button">Verify
                                OTP</button>
                            <button type="button" class="otp-resend-button resendotp">Resend
                                OTP</button>
                        </div>
                        <div id="otp-message"></div>
                    </form>
                </div>
            </div>
        </div>

        <!-- bKash Payment Modal -->
        <div id="bkashModal" class="bkash-modal-area hidden">
            <div class="bkash-modal-inner">
                <div class="bkash-header">
                    <button type="button" class="close-button" onclick="closeBkashModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                    <div class="bkash-logo-section">
                        <div class="bkash-logo">bKash</div>
                    </div>
                    <div class="bkash-tagline">Your trusted mobile financial service</div>
                </div>

                <div class="payment-info">
                    <div class="payment-amount">৳ <span id="bkashAmount">0</span></div>
                    <div class="merchant-info">Payment to Personal</div>
                </div>

                <div class="payment-steps">
                    <div class="steps-title">
                        <div class="step-icon">i</div>
                        Payment Instructions
                    </div>
                    <ol class="steps-list">
                        <li>Open your bKash app or dial *247#</li>
                        <li>Select "Send Money" option</li>
                        <li>Send money to: <span class="bkash-number">{{ setting('ecommerce', 'bkash_number', '01712-345678') }}</span>
                            <span onclick="copyPaymentNumber('bkash-number')">
                                <svg class="copy-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2">
                                    </rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg> Copy
                            </span>
                        </li>
                        <li>Enter amount: ৳<span id="stepAmount">0</span></li>
                        <li>Complete the transaction with your PIN</li>
                        <li>Enter transaction details below to confirm</li>
                    </ol>
                </div>

                <form class="payment-form" id="bkash-form">
                    <div class="form-group">
                        <label for="bkash_number" class="form-label">Your bKash Number</label>
                        <input type="tel" class="form-input" id="bkash_number" name="bkash_number"
                            placeholder="01XXXXXXXXX" required>
                    </div>
                    <div class="form-group">
                        <label for="bkash_transaction_id" class="form-label">Transaction ID (TrxID)</label>
                        <input type="text" class="form-input" id="bkash_transaction_id" name="bkash_transaction_id"
                            placeholder="8N67MA4KO2" required>
                    </div>

                    <button type="submit" class="confirm-button">Confirm Payment</button>
                </form>

                <div class="security-note">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"
                        style="display: inline; margin-right: 5px;">
                        <path d="M12 2L3 7V12C3 16.55 6.84 20.74 9 21C11.16 20.74 21 16.55 21 12V7L12 2Z"
                            stroke="currentColor" stroke-width="2" fill="none" />
                    </svg>
                    <p>Your transaction is secured with 256-bit SSL encryption</p>
                </div>
            </div>
        </div>

        <!-- Nagad Payment Modal -->
        <div id="nagadModal" class="nagad-modal-area hidden">
            <div class="nagad-modal-inner">
                <div class="nagad-header">
                    <button type="button" class="close-button" onclick="closeNagadModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                    <div class="nagad-logo-section">
                        <div class="nagad-logo">Nagad</div>
                    </div>
                    <div class="nagad-tagline">Financial service for all</div>
                </div>

                <div class="payment-info">
                    <div class="payment-amount">৳ <span id="nagadAmount">0</span></div>
                    <div class="merchant-info">Payment to Personal</div>
                </div>

                <div class="payment-steps">
                    <div class="steps-title">
                        <div class="step-icon">i</div>
                        Payment Instructions
                    </div>
                    <ol class="steps-list">
                        <li>Open your Nagad app or dial *167#</li>
                        <li>Select "Send Money" option</li>
                        <li>Send money to: <span class="nagad-number">{{ setting('ecommerce', 'nagad_number', '01712-345678') }}</span>
                            <span onclick="copyPaymentNumber('nagad-number')">
                                <svg class="copy-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2">
                                    </rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg> Copy
                            </span>
                        </li>
                        <li>Enter amount: ৳<span id="nagadStepAmount">0</span></li>
                        <li>Complete the transaction with your PIN</li>
                        <li>Enter transaction details below to confirm</li>
                    </ol>
                </div>

                <form class="payment-form" id="nagad-form">
                    <div class="form-group">
                        <label for="nagad_number" class="form-label">Your Nagad Number</label>
                        <input type="tel" class="form-input" id="nagad_number" name="nagad_number"
                            placeholder="01XXXXXXXXX" required>
                    </div>
                    <div class="form-group">
                        <label for="nagad_transaction_id" class="form-label">Transaction ID (TrxID)</label>
                        <input type="text" class="form-input" id="nagad_transaction_id" name="nagad_transaction_id"
                            placeholder="8N67MA4KO2" required>
                    </div>

                    <button type="submit" class="nagad-confirm-button">Confirm Payment</button>
                </form>

                <div class="security-note">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"
                        style="display: inline; margin-right: 5px;">
                        <path d="M12 2L3 7V12C3 16.55 6.84 20.74 9 21C11.16 20.74 21 16.55 21 12V7L12 2Z"
                            stroke="currentColor" stroke-width="2" fill="none" />
                    </svg>
                    <p>Your transaction is secured with 256-bit SSL encryption</p>
                </div>
            </div>
        </div>

        <!-- Rocket Payment Modal -->
        <div id="rocketModal" class="rocket-modal-area hidden">
            <div class="rocket-modal-inner">
                <div class="rocket-header">
                    <button type="button" class="close-button" onclick="closeRocketModal()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" />
                        </svg>
                    </button>
                    <div class="rocket-logo-section">
                        <div class="rocket-logo">Rocket</div>
                    </div>
                    <div class="rocket-tagline">Dutch-Bangla Bank Mobile Banking</div>
                </div>

                <div class="payment-info">
                    <div class="payment-amount">৳ <span id="rocketAmount">0</span></div>
                    <div class="merchant-info">Payment to Merchant</div>
                </div>

                <div class="payment-steps">
                    <div class="steps-title">
                        <div class="step-icon">i</div>
                        Payment Instructions
                    </div>
                    <ol class="steps-list">
                        <li>Open your Rocket app or dial *322#</li>
                        <li>Select "Send Money" option</li>
                        <li>Send money to: <span class="rocket-number">{{ setting('ecommerce', 'rocket_number', '01712-345678') }}</span>
                            <span onclick="copyPaymentNumber('rocket-number')">
                                <svg class="copy-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2">
                                    </rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg> Copy
                            </span>
                        </li>
                        <li>Enter amount: ৳<span id="rocketStepAmount">0</span></li>
                        <li>Complete the transaction with your PIN</li>
                        <li>Enter transaction details below to confirm</li>
                    </ol>
                </div>

                <form class="payment-form" id="rocket-form">
                    <div class="form-group">
                        <label for="rocket_number" class="form-label">Your Rocket Number</label>
                        <input type="tel" class="form-input" id="rocket_number" name="rocket_number"
                            placeholder="01XXXXXXXXX" required>
                    </div>
                    <div class="form-group">
                        <label for="rocket_transaction_id" class="form-label">Transaction ID (TrxID)</label>
                        <input type="text" class="form-input" id="rocket_transaction_id" name="rocket_transaction_id"
                            placeholder="8N67MA4KO2" required>
                    </div>

                    <button type="submit" class="rocket-confirm-button">Confirm Payment</button>
                </form>

                <div class="security-note">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"
                        style="display: inline; margin-right: 5px;">
                        <path d="M12 2L3 7V12C3 16.55 6.84 20.74 9 21C11.16 20.74 21 16.55 21 12V7L12 2Z"
                            stroke="currentColor" stroke-width="2" fill="none" />
                    </svg>
                    <p>Your transaction is secured with 256-bit SSL encryption</p>
                </div>
            </div>
        </div>

    </div>
    <div id="copy-toast" class="copy-toast">Copied!</div>
@endsection

@section('scripts')
    <!--jQuery-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    {{-- Order Submission --}}
    <script>
        // Capture UTM parameters and click IDs from URL on page load
        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            const trackingFields = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid', 'gclid', 'ttclid'];

            trackingFields.forEach(function(field) {
                const value = urlParams.get(field);
                // Use class selector since there are 2 forms (v1 and v2)
                const inputs = document.querySelectorAll('.' + field);
                inputs.forEach(function(input) {
                    if (input && value) {
                        input.value = value;
                    }
                });
            });
        })();

        $(document).ready(function() {

            // $('#buynow-order').on('submit', function(e) {
            //     e.preventDefault();
            //     placeOrder(); // Call placeOrder when the form is submitted
            // });

            // Handle form submission
            $('#buynow-order').on('submit', function(e) {
                e.preventDefault();
                const paymentMethod = $('input[name="payment_method"]:checked').val();

                if (paymentMethod === 'bkash') {
                    showBkashModal();
                    return;
                } else if (paymentMethod === 'nagad') {
                    showNagadModal();
                    return;
                } else if (paymentMethod === 'rocket') {
                    showRocketModal();
                    return;
                } else {
                    placeOrder();
                }

            });

            // Handle Bkash form submission
            $('#bkash-form').on('submit', function(e) {
                e.preventDefault();
                const GetbkashCharge = parseFloat($('#bkashChargeDisplay').text().replace('৳', '')) || 0;

                // Add bKash details to the order form
                $('#buynow-order').append(`
                    <input type="hidden" name="bkash_number" value="${$('#bkash_number').val()}">
                    <input type="hidden" name="bkash_transaction_id" value="${$('#bkash_transaction_id').val()}">
                    <input type="hidden" name="bkash_charge" value="${GetbkashCharge}">
                `);

                // Close the modal and submit the order
                closeBkashModal();
                placeOrder();
            });

            // Handle Nagad form submission
            $('#nagad-form').on('submit', function(e) {
                e.preventDefault();
                const nagadCharge = parseFloat($('#nagadChargeDisplay').text().replace('৳', '')) || 0;

                // Add Nagad details to the order form
                $('#buynow-order').append(`
                    <input type="hidden" name="nagad_number" value="${$('#nagad_number').val()}">
                    <input type="hidden" name="nagad_transaction_id" value="${$('#nagad_transaction_id').val()}">
                    <input type="hidden" name="nagad_charge" value="${nagadCharge}">
                `);

                // Close the modal and submit the order
                closeNagadModal();
                placeOrder();
            });

            // Handle Rocket form submission
            $('#rocket-form').on('submit', function(e) {
                e.preventDefault();
                const rocketCharge = parseFloat($('#rocketChargeDisplay').text().replace('৳', '')) || 0;

                // Add Rocket details to the order form
                $('#buynow-order').append(`
                    <input type="hidden" name="rocket_number" value="${$('#rocket_number').val()}">
                    <input type="hidden" name="rocket_transaction_id" value="${$('#rocket_transaction_id').val()}">
                    <input type="hidden" name="rocket_charge" value="${rocketCharge}">
                `);

                // Close the modal and submit the order
                closeRocketModal();
                placeOrder();
            });

            // Make closeBkashModal available globally
            window.closeBkashModal = closeBkashModal;
            window.closeNagadModal = closeNagadModal;
            window.closeRocketModal = closeRocketModal;

            // Function to show Bkash modal
            function showBkashModal() {
                document.getElementById('bkashModal').classList.remove('hidden');
                // Set the amount in both locations
                const totalAmount = calculateTotalAmount();
                document.getElementById('bkashAmount').textContent = totalAmount;
                document.getElementById('stepAmount').textContent = totalAmount;
            }

            // Function to close Bkash modal
            function closeBkashModal() {
                document.getElementById('bkashModal').classList.add('hidden');
            }

            // Function to show Nagad modal

            function showNagadModal() {
                document.getElementById('nagadModal').classList.remove('hidden');
                // Set the amount in both locations
                const totalAmount = calculateTotalAmount();
                document.getElementById('nagadAmount').textContent = totalAmount;
                document.getElementById('nagadStepAmount').textContent = totalAmount;
            }

            // Function to close Nagad modal
            function closeNagadModal() {
                document.getElementById('nagadModal').classList.add('hidden');
            }

            // Function to show Rocket modal
            function showRocketModal() {
                document.getElementById('rocketModal').classList.remove('hidden');
                // Set the amount in both locations
                const totalAmount = calculateTotalAmount();
                document.getElementById('rocketAmount').textContent = totalAmount;
                document.getElementById('rocketStepAmount').textContent = totalAmount;
            }

            // Function to close Rocket modal
            function closeRocketModal() {
                document.getElementById('rocketModal').classList.add('hidden');
            }

            // Function to calculate total amount
            function calculateTotalAmount() {
                // Get the total amount from the order-total cell
                const totalAmount = $('.order-total').text().replace('৳', '').trim();
                return totalAmount || '0.00';
            }

            function placeOrder() {
                $.ajax({
                    url: '{{ route('buynow.order') }}', // URL to send the order data to
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    data: $('#buynow-order').serialize(), // Serialize form data to send as POST data
                    success: function(data) {
                        if (data.status === 'otp_sent') {
                            // Handle the specific COD test message
                            openOtpModal(); // Make sure the modal is shown after OTP is sent
                            $('#order-message').html(`
                                <div class="order-notification">
                                    ${data.message}  <!-- Show message for COD payment method -->
                                </div>
                            `);
                        } else if (data.success || data.status === 'success') {
                            $('#order-message').html(`
                                <div class="order-notification">
                                    ${data.message}  <!-- Show success message -->
                                </div>
                            `);
                            // Check if order_id is available
                            if (data.order_id) {
                                if (data.requires_redirect) {
                                    // Automated payment gateway - redirect to payment page
                                    $('#order-message').html('<div class="order-notification">পেমেন্ট গেটওয়েতে যাচ্ছে...</div>');
                                    $.ajax({
                                        url: '{{ route("payment.initiate") }}',
                                        type: 'POST',
                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                        contentType: 'application/json',
                                        data: JSON.stringify({ order_id: data.order_id, provider: data.provider }),
                                        success: function(result) {
                                            if (result.redirect_url) {
                                                window.location.href = result.redirect_url;
                                            } else {
                                                alert(result.message || 'পেমেন্ট গেটওয়ে শুরু করতে সমস্যা হয়েছে।');
                                                window.location.href = "{{ url('thank-you') }}/" + data.order_id;
                                            }
                                        },
                                        error: function(xhr) {
                                            var errMsg = 'পেমেন্ট গেটওয়ে শুরু করতে সমস্যা হয়েছে।';
                                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                                errMsg = xhr.responseJSON.message;
                                            }
                                            $('#order-message').html('<div class="order-notification" style="color:red;">' + errMsg + '</div>');
                                        }
                                    });
                                } else {
                                    setTimeout(function() {
                                        window.location.href = "{{ url('thank-you') }}/" + data.order_id;
                                    }, 1000);
                                }
                            } else {
                                $('#buynow-order')[0].reset();
                            }

                        } else {
                            // Display errors returned by the backend
                            let errorMessages = Array.isArray(data.errors) ? data.errors.join('<br>') :
                                'Unknown error';
                            
                            $('#order-message').html(`
                                <div class="order-notification">
                                    ${errorMessages}  <!-- Show error message -->
                                </div>
                            `);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Clear previous error states
                        $('.form-error').removeClass('form-error');
                        $('.field-error').remove();

                        if (xhr.status === 422) {
                            const resp = xhr.responseJSON || {};
                            const payloadErrors = resp.errors;

                            // Decide one message to display via push notifier
                            let notifyMsg = resp.message || 'There were some validation issues with your order.';
                            if (Array.isArray(payloadErrors) && payloadErrors.length) {
                                notifyMsg = payloadErrors[0];
                            } else if (payloadErrors && typeof payloadErrors === 'object') {
                                const firstKey = Object.keys(payloadErrors)[0];
                                if (firstKey) {
                                    const arr = payloadErrors[firstKey];
                                    if (Array.isArray(arr) && arr.length) notifyMsg = arr[0];
                                }
                            }

                            // Push once
                            if (window.pushFraudNotification) {
                                window.pushFraudNotification(notifyMsg, 'error');
                            }

                            // Preserve field-level highlighting (no extra push here)
                            if (payloadErrors && typeof payloadErrors === 'object') {
                                Object.keys(payloadErrors).forEach(function(field) {
                                    const messages = Array.isArray(payloadErrors[field]) ? payloadErrors[field] : [String(payloadErrors[field] || '')];
                                    const fieldElement = $(`[name="${field}"]`);
                                    const messageHtml = messages.join('<br>');
                                    if (fieldElement && fieldElement.length) {
                                        fieldElement.addClass('form-error');
                                        if (!fieldElement.next('.field-error').length) {
                                            fieldElement.after(`<div class="field-error">${messageHtml}</div>`);
                                        }
                                    }
                                });
                            }
                        } else {
                            $('#order-message').html(`
                                <div class="order-notification error-message">
                                    An unexpected error occurred: ${error}. Please try again.
                                </div>
                            `);
                        }
                    }
                });
            }

        });
    </script>
    {{-- Otp Send --}}
    <script>
        // Function to open the OTP modal
        function openOtpModal() {
            document.getElementById('otpModal').classList.remove('hidden');
        }

        // Function to close the OTP modal
        function closeOtpModal() {
            document.getElementById('otpModal').classList.add('hidden');
        }

        // Handle OTP form submission
        $('#otpModal').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            submitOtp(e); // Pass the event 'e' to the submitOtp function
        });

        // Function to handle OTP form submission
        function submitOtp(e) {
            e.preventDefault(); // Prevent default form submission

            var otp = document.getElementById('otp').value;

            $.ajax({
                url: '{{ route('otp.verify.buynow') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    otp: otp
                },
                success: function(response) {

                    if (response.status === 'success') {
                        $('#order-message').html(
                            '<div class="order-notification">OTP verified successfully!</div>'
                        );
                        closeOtpModal();
                        // Redirect to the Thank You page after 1 second

                        // Check if order_id is available
                        if (response.order_id) {
                            if (response.requires_redirect) {
                                // Automated payment gateway - redirect to payment page
                                $('#order-message').html('<div class="order-notification">পেমেন্ট গেটওয়েতে যাচ্ছে...</div>');
                                $.ajax({
                                    url: '{{ route("payment.initiate") }}',
                                    type: 'POST',
                                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                    contentType: 'application/json',
                                    data: JSON.stringify({ order_id: response.order_id, provider: response.provider }),
                                    success: function(result) {
                                        if (result.redirect_url) {
                                            window.location.href = result.redirect_url;
                                        } else {
                                            alert(result.message || 'পেমেন্ট গেটওয়ে শুরু করতে সমস্যা হয়েছে।');
                                            window.location.href = "{{ url('thank-you') }}/" + response.order_id;
                                        }
                                    },
                                    error: function(xhr) {
                                        var errMsg = 'পেমেন্ট গেটওয়ে শুরু করতে সমস্যা হয়েছে।';
                                        if (xhr.responseJSON && xhr.responseJSON.message) {
                                            errMsg = xhr.responseJSON.message;
                                        }
                                        $('#order-message').html('<div class="order-notification" style="color:red;">' + errMsg + '</div>');
                                    }
                                });
                            } else {
                                setTimeout(function() {
                                    window.location.href = "{{ url('thank-you') }}/" + response.order_id;
                                }, 1000);
                            }
                        } else {
                            // Order ID not received
                        }

                    } else {
                        $('#otp-message').html(
                            '<div class="order-notification">' + xhr.responseJSON.message +
                            '</div>'
                        );
                    }
                },
                error: function(xhr, status, error) {


                    if (xhr.status === 400) {
                        // If the backend returns an error response (invalid OTP)
                        $('#otp-message').html(
                            '<div class="order-notification">' + xhr.responseJSON.message +
                            '</div>'
                        );
                    } else {
                        $('#otp-message').html(
                            '<div class="order-notification">An unexpected error occurred. Please try again.</div>'
                        );
                    }
                }
            });
        }

        // Function to handle Resend OTP button click
        $(document).on('click', '.resendotp', function() {
            var resendButton = $(this);

            // Disable the button and start the countdown
            disableResendButton(resendButton, 30);

            // Make the AJAX call to resend the OTP
            $.ajax({
                url: '{{ route('otp.resend') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#otp-message').html(
                            '<div class="order-notification">OTP resent successfully!</div>'
                        );
                    } else {
                        $('#otp-message').html(
                            '<div class="order-notification">' + response.message +
                            '</div>'
                        );
                    }
                },
                error: function(xhr) {
                    $('#otp-message').html(
                        '<div class="order-notification">' +
                        (xhr.responseJSON?.message || 'An error occurred while resending OTP.') +
                        '</div>'
                    );
                }
            });
        });

        // Function to disable the Resend OTP button with a countdown
        function disableResendButton(button, seconds) {
            button.prop('disabled', true); // Disable the button
            var originalText = button.text(); // Save the original button text

            var interval = setInterval(function() {
                if (seconds > 0) {
                    button.text('Resend in ' + seconds + 's'); // Update button text with countdown
                    seconds--;
                } else {
                    clearInterval(interval); // Clear the interval when countdown ends
                    button.prop('disabled', false); // Re-enable the button
                    button.text('Resend OTP'); // Restore original button text
                }
            }, 1000); // Update every second
        }
    </script>
    {{-- Quantity controls --}}
    <script>
        function formatCurrency(value) {
            const num = parseFloat(value) || 0;
            return Number.isInteger(num) ? num.toString() : num.toFixed(2);
        }

        function syncQuantityTotals(context) {
            const unitPrice = context === 'combo'
                ? parseFloat($('#combo-order-row').data('unit-price')) || 0
                : parseFloat($('#single-order-row').data('unit-price')) || 0;

            const input = $(`.quantity-input[data-context="${context}"]`);
            if (!input.length) {
                return;
            }

            let quantity = parseInt(input.val()) || 1;
            if (quantity < 1) {
                quantity = 1;
                input.val(quantity);
            }

            if (context === 'combo') {
                $('input[name="quantity"]').val(quantity);
                const totalPrice = unitPrice * quantity;
                $('#combo-price').text(formatCurrency(totalPrice) + '৳');
                $('.combo-qty-label').text(quantity);
            } else {
                $('input[name="pqty"]').val(quantity);
                const totalPrice = unitPrice * quantity;
                $('input[name="price"]').val(totalPrice);
                $('#single-price').text(formatCurrency(totalPrice) + '৳');
                $('.product-qty-label').text('X ' + quantity);
            }

            updateOrderTotal();
        }

        $(document).on('click', '.quantity-btn', function() {
            const context = $(this).data('context');
            const action = $(this).data('action');
            const input = $(`.quantity-input[data-context="${context}"]`);

            if (!input.length || !context) {
                return;
            }

            let quantity = parseInt(input.val()) || 1;

            if (action === 'increase') {
                quantity += 1;
            } else if (action === 'decrease' && quantity > 1) {
                quantity -= 1;
            }

            input.val(quantity);
            syncQuantityTotals(context);
        });
    </script>
    {{-- Set Shipping --}}
    <script>
        window.shippingSettings = {
            flatRate: {{ $shippingSetting->flat_rate }},
            shippingOptions: @json($activeShippingOptions),
            freeShippingThreshold: {{ $shippingSetting->free_shipping_threshold }},
            specificRules: @json($specificShippingRules)
        };
    </script>
    {{-- Shipping and Payment charge Calculation --}}
    <script>
        function updateOrderTotal() {
            console.log('updateOrderTotal function called');
            
            // Check if this is a combo purchase
            const isComboPurchase = $('input[name="is_combo_purchase"]').val() === '1';
            console.log('isComboPurchase:', isComboPurchase);
            
            let price, quantity, subtotal;
            
            if (isComboPurchase) {
                // Get combo price and quantity from hidden inputs
                price = parseFloat($('input[name="price"]').val()) || 0;
                quantity = parseInt($('input[name="quantity"]').val()) || 0;
                // For combo orders, multiply price by quantity
                subtotal = price * quantity;
                console.log('Combo order - price:', price, 'quantity:', quantity, 'subtotal:', subtotal);
            } else {
                // Get product price and quantity from hidden inputs
                price = parseFloat($('input[name="price"]').val()) || 0;
                quantity = parseInt($('input[name="pqty"]').val()) || 0;
                // For regular products, use the total price directly
                subtotal = price;
                console.log('Regular order - price:', price, 'quantity:', quantity, 'subtotal:', subtotal);
            }

            // Update subtotal display
            $('#Subtotal').text(subtotal.toFixed(2) + '৳');
            console.log('Subtotal updated to:', subtotal.toFixed(2));

            // Get selected payment method
            const paymentMethod = $('input[name="payment_method"]:checked').val();
            console.log('Payment method:', paymentMethod);

            // Calculate shipping cost using BasicShipping settings
            const settings = window.shippingSettings || {
                flatRate: 80.00,
                shippingOptions: {
                    inside_dhaka: {
                        name: 'Inside Dhaka',
                        cost: 80.00,
                        active: true
                    },
                    outside_dhaka: {
                        name: 'Outside Dhaka',
                        cost: 110.00,
                        active: true
                    }
                },
                freeShippingThreshold: 1500.00
            };
            console.log('Shipping settings:', settings);

            let shippingCost = settings.flatRate;
            const selectedArea = $('input[name="shipping_area"]:checked').val();
            console.log('Selected shipping area:', selectedArea);
            if (selectedArea && settings.shippingOptions[selectedArea] && settings.shippingOptions[selectedArea].active) {
                shippingCost = parseFloat(settings.shippingOptions[selectedArea].cost);
                console.log('Using selected shipping area cost:', shippingCost);
            } else {
                // Select first active option as default
                const firstActiveOption = Object.keys(settings.shippingOptions).find(
                    key => settings.shippingOptions[key].active
                );
                if (firstActiveOption) {
                    shippingCost = parseFloat(settings.shippingOptions[firstActiveOption].cost);
                    $(`#${firstActiveOption}`).prop('checked', true);
                    console.log('Using default shipping area:', firstActiveOption, 'cost:', shippingCost);
                }
            }

            // Apply free shipping if threshold met
            // First check specific shipping rules
            let freeShippingThreshold = settings.freeShippingThreshold;
            let fallbackCost = null;
            
            // Check for specific free shipping threshold rules
            if (settings.specificRules) {
                settings.specificRules.forEach(rule => {
                    if (rule.rule_type === 'free_shipping' && rule.free_shipping_threshold) {
                        freeShippingThreshold = rule.free_shipping_threshold;
                        fallbackCost = rule.rule_value; // Fallback cost when threshold not met
                    }
                });
            }
            
            if (subtotal >= freeShippingThreshold) {
                shippingCost = 0;
                $('#flat-rate').first().html('<span class="free-shipping">Free shipping</span>');
            } else if (fallbackCost !== null) {
                // Use the fallback cost from the specific rule
                shippingCost = parseFloat(fallbackCost);
                $('#flat-rate').first().text('Flat rate: ' + shippingCost.toFixed(2) + '৳');
            } else {
                $('#flat-rate').first().text('Flat rate: ' + shippingCost.toFixed(2) + '৳');
            }
            $('input[name="shipping"]').val(shippingCost);

            // Calculate payment gateway charges
            let bkashCharge = 0;
            let nagadCharge = 0;
            let rocketCharge = 0;

            // Hide all charge rows by default
            $('#bkashChargeRow, #nagadChargeRow, #rocketChargeRow').hide();

            // Calculate and show the appropriate charge based on payment method
            const totalBeforeCharge = subtotal + shippingCost;
            if (paymentMethod === 'bkash') {
                bkashCharge = Math.ceil(totalBeforeCharge / 100) * (100 * 0.018);
                $('#bkashChargeDisplay').text(bkashCharge.toFixed(2));
                $('#bkashChargeRow').show();
                $('#bkashAmount, #stepAmount').text((totalBeforeCharge + bkashCharge).toFixed(2));
            } else if (paymentMethod === 'nagad') {
                nagadCharge = Math.ceil(totalBeforeCharge / 100) * (100 * 0.015);
                $('#nagadChargeDisplay').text(nagadCharge.toFixed(2));
                $('#nagadChargeRow').show();
                $('#nagadAmount, #nagadStepAmount').text((totalBeforeCharge + nagadCharge).toFixed(2));
            } else if (paymentMethod === 'rocket') {
                rocketCharge = Math.ceil(totalBeforeCharge / 100) * (100 * 0.018);
                $('#rocketChargeDisplay').text(rocketCharge.toFixed(2));
                $('#rocketChargeRow').show();
                $('#rocketAmount, #rocketStepAmount').text((totalBeforeCharge + rocketCharge).toFixed(2));
            }

            // Update total with the appropriate charge
            const total = subtotal + shippingCost + bkashCharge + nagadCharge + rocketCharge;
            $('.order-total').text(total.toFixed(2) + '৳');
            console.log('Final total calculated:', total.toFixed(2), 'subtotal:', subtotal, 'shipping:', shippingCost, 'charges:', bkashCharge + nagadCharge + rocketCharge);
        }

        // Set up event listeners
        $(document).ready(function() {
            // Make the entire shipping option clickable
            $(document).on('click', '.shipping-option', function(event) {
                const radio = $(this).find('input[type="radio"]');
                if (!$(event.target).is('input[type="radio"]')) {
                    radio.prop('checked', true).trigger('change');
                }
            });

            // For shipping changes
            $('input[name="shipping_area"]').change(updateOrderTotal);

            // For payment method changes
            $('input[name="payment_method"]').change(updateOrderTotal);

            // Initial calculation
            updateOrderTotal();
        });
    </script>
    <!-- Toast Notification -->
    <script>
        function copyPaymentNumber(className) {
            const number = document.querySelector('.' + className).textContent.trim();
            navigator.clipboard.writeText(number).then(() => {
                // Show green icon temporarily
                const copyIcon = document.querySelector('.copy-icon');
                const originalColor = copyIcon.style.color;
                copyIcon.style.color = '#22c55e';
                setTimeout(() => {
                    copyIcon.style.color = originalColor;
                }, 1000);

                // Show toast notification
                const toast = document.getElementById('copy-toast');
                toast.style.opacity = '1';
                setTimeout(() => {
                    toast.style.opacity = '0';
                }, 1500);
            });
        }
    </script>

    {{-- Incomeplete order ajax --}}
    <script src="{{ asset('js/incomplete-order.js') }}"></script>

    {{-- Fraud Protection System --}}
    <script src="{{ asset('js/fraud-protection.js') }}"></script>
    <script>
        // Initialize Fraud Protection with settings from backend
        const fraudProtectionSettings = @json(app(\App\Services\FraudProtectionService::class)->getFrontendSettings());
        const fraudProtection = new FraudProtection(fraudProtectionSettings);
        window.fraudProtectionInstance = fraudProtection;
    </script>
@endsection
