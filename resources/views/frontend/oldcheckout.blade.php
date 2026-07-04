@extends('frontend.app')
@section('styles')
    <style>
        /* Checkout Page Styles */
        .checkout-container {
            margin: 15px auto;
            padding: 0 15px;
        }

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
            min-height: 120px;
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
            .checkout-container {
                padding: 10px 5px;
                margin: 0px auto;
            }
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
                padding: 20px;
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
            padding: 20px;
            margin-bottom: 20px;
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
@section('content')
    <div class="base-container checkout-container">
        <h1 class="checkout-header">Checkout</h1>
        <div class="divider"></div>

        <form id="order-form">
            @csrf
            <div class="order-inner">

                <div class="billing-info">
                    <h2 class="billing-header">Billing details</h2>

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
                    {{-- <div class="billing-city">
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
                    </div> --}}

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
                    <textarea class="note-textarea" name="message" id="" cols="30" rows="5"></textarea>

                </div>

                <div class="checkout-order-info">
                    {{-- Order Details Table --}}
                    <div id="cart-table" class="checkout-cart-table">
                        <h2 class="cct-header">Your order</h2>
                        <table class="cct-table">

                            <tr class="cct-table-head">
                                <th class="cct-product">Product</th>
                                <th class="cct-subtotal">Subtotal</th>
                            </tr>

                            @foreach ($groupedCarts as $productId => $items)
                                @php
                                    $product = $items->first()->product;
                                    $firstItem = $items->first();
                                @endphp
                                <tr data-product-id="{{ $productId }}"
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
                                <td class="">Subtotal</td>
                                <td class="cct-price" id="Subtotal">{{ $sub_total }}৳</td>
                            </tr>

                            <tr>
                                <td class="cct-shipping-head">Shipping</td>
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
                                <td class=""> Total</td>
                                <td class="cct-price order-total" id="flat-rate">
                                    {{ $sub_total + $shipping }}৳
                                </td>
                            </tr>

                        </table>
                    </div>

                    <!-- Shipping Area Selection -->
                    <div class="shipping-area">
                        <h5 class="shipping-area-title">Shipping Area</h5>
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

            </div>

            <input type="hidden" name="shipping" value="{{ $shipping }}">

        </form>

        <!-- OTP Verification Modal -->
        <div id="otpModal" class="otp-modal-area hidden">
            <div class="otp-modal-inner">
                <div class="otp-modal-header">
                    <h5 class="otp-modal-title">Mobile Verification</h5>
                    <button type="button" class="otp-close-button" onclick="closeOtpModal()">
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
                        <div class="otp-buttons">
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

        <!-- Include a section for messages -->
        <div id="order-message" class=""></div>
        <div id="copy-toast" class="copy-toast">Copied!</div>

    </div>
@endsection

@section('scripts')
    <!--jQuery-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    {{-- Order Submit and Manual Payment Submission --}}
    <script>
        $(document).ready(function() {
            // Handle form submission
            $('#order-form').on('submit', function(e) {
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
                $('#order-form').append(`
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
                $('#order-form').append(`
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
                $('#order-form').append(`
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
                    url: '{{ route('order.store') }}', // URL to send the order data
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for security
                    },
                    data: $('#order-form').serialize(), // Serialize form data to send as POST data
                    success: function(data) {
                        if (data.status === 'otp_sent') {
                            // Handle the specific COD test message
                            openOtpModal(); // Make sure the modal is shown after OTP is sent
                            $('#order-message').html(`
                            <div class="order-notification">
                                ${data.message}  <!-- Show message for COD payment method -->
                            </div>
                        `);
                        } else if (data.success) {
                            $('#order-message').html(`
                            <div class="order-notification">
                                ${data.message}  <!-- Show success message -->
                            </div>
                            `);

                            // Check if order_id is available
                            if (data.order_id) {
                                // Redirect to the thank you page with order ID
                                setTimeout(function() {
                                    window.location.href = "{{ url('thank-you') }}/" + data
                                        .order_id; // Ensure order_id is appended
                                }, 1000); // 2 seconds before redirecting
                            } else {
                            }

                            $('#order-form')[0].reset(); // Clear form after success (optional)
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

                        if (xhr.status === 422) {
                            const resp = xhr.responseJSON || {};
                            const payloadErrors = resp.errors;

                            // Choose a single message to push
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

                            if (window.pushFraudNotification) {
                                window.pushFraudNotification(notifyMsg, 'error');
                            }

                            // Keep field-level highlights
                            if (payloadErrors && typeof payloadErrors === 'object') {
                                Object.keys(payloadErrors).forEach(function(field) {
                                    const messages = Array.isArray(payloadErrors[field]) ? payloadErrors[field] : [String(payloadErrors[field] || '')];
                                    const input = $('[name="' + field + '"]');
                                    const errorDiv = input.parent();
                                    if (!errorDiv.hasClass('form-error')) {
                                        errorDiv.addClass('form-error');
                                        errorDiv.append('<div class="error-message">' + messages[0] + '</div>');
                                    }
                                });
                            }
                        } else {
                            // Generic error handling for other types of errors
                            $('#order-message').html(`
                            <div class="order-notification">
                                An unexpected error occurred: ${error}. Please try again.
                            </div>
                        `);
                        }
                    }
                });
            }

        });
    </script>
    {{-- Otp Send & Resend --}}
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
                url: '{{ route('otp.verify') }}',
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
                            // Redirect to the thank you page with order ID
                            setTimeout(function() {
                                window.location.href = "{{ url('thank-you') }}/" + response
                                    .order_id; // Ensure order_id is appended
                            }, 1000); // 2 seconds before redirecting
                        } else {
                        }

                    } else {
                        $('#otp-message').html(
                            '<div class="order-notification">' + responseJSON.message +
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
    {{-- Set Shipping --}}
    <script>
        window.shippingSettings = {
            flatRate: {{ $shippingSetting->flat_rate }},
            shippingOptions: @json($activeShippingOptions),
            freeShippingThreshold: {{ $shippingSetting->free_shipping_threshold }},
            specificRules: @json($specificShippingRules)
        };
    </script>
    {{-- Order total & orders table calculate --}}
    <script>
        function updateOrderTotal() {
            // Calculate subtotal from DOM
            let subtotal = 0;
            $('.cct-table tr[data-product-id]').each(function() {
                const price = parseFloat($(this).find('.cct-price').text().replace('৳', '')) || 0;
                subtotal += price;
            });

            // Update subtotal display
            $('#Subtotal').text(subtotal.toFixed(2) + '৳');

            // Get selected payment method
            const paymentMethod = $('input[name="payment_method"]:checked').val();

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

            let shippingCost = settings.flatRate;
            const selectedArea = $('input[name="shipping_area"]:checked').val();
            if (selectedArea && settings.shippingOptions[selectedArea] && settings.shippingOptions[selectedArea].active) {
                shippingCost = parseFloat(settings.shippingOptions[selectedArea].cost);
            } else {
                // Default to first active option
                const firstActiveOption = Object.keys(settings.shippingOptions).find(
                    key => settings.shippingOptions[key].active
                );
                if (firstActiveOption) {
                    shippingCost = parseFloat(settings.shippingOptions[firstActiveOption].cost);
                    $(`#${firstActiveOption}`).prop('checked', true);
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
                $('#flat-rate').html('<span class="free-shipping">Free shipping</span>');
            } else if (fallbackCost !== null) {
                // Use the fallback cost from the specific rule
                shippingCost = parseFloat(fallbackCost);
                $('#flat-rate').text('Flat rate: ' + shippingCost.toFixed(2) + '৳');
            } else {
                $('#flat-rate').text('Flat rate: ' + shippingCost.toFixed(2) + '৳');
            }
            $('input[name="shipping"]').val(shippingCost);

            // Calculate payment gateway charges
            let bkashCharge = 0;
            let nagadCharge = 0;
            let rocketCharge = 0;

            // Hide all charge rows by default
            $('#bkashChargeRow, #nagadChargeRow, #rocketChargeRow').hide();

            // Calculate and show charges
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

            // Update total
            const total = totalBeforeCharge + bkashCharge + nagadCharge + rocketCharge;
            $('.order-total').text(total.toFixed(2) + '৳');
        }

        // Set up event listeners
        $(document).ready(function() {
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
