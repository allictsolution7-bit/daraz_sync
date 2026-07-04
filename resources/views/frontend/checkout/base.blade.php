@extends('frontend.app')

{{-- Version-specific styles will be injected here --}}
@section('styles')
    <style>
        /* Checkout Page Styles */
        .checkout-container {
            width: 1340px;
            margin: 15px auto;
            padding: 0 15px;
        }
        @media (max-width: 1340px) {
            .checkout-container {
                width: 100%;
            }
        }
        @media (max-width: 768px) {
            .checkout-container {
                width: 100%;
                padding: 0 5px;
            }
        }
        
    </style>
    @yield('version-styles')

@endsection

@section('content')

<div class="checkout-container">

    @yield('checkout-form')

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

{{-- All JavaScript (shared across all versions) --}}
@section('scripts')
    <!--jQuery-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    {{-- Order Submit and Manual Payment Submission --}}
    <script>
        // Capture UTM parameters and click IDs from URL on page load
        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            const trackingFields = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid', 'gclid', 'ttclid'];

            trackingFields.forEach(function(field) {
                const value = urlParams.get(field);
                const input = document.getElementById(field);
                if (input && value) {
                    input.value = value;
                }
            });
        })();

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
                                    // Manual payment or COD - redirect to thank you
                                    setTimeout(function() {
                                        window.location.href = "{{ url('thank-you') }}/" + data.order_id;
                                    }, 1000);
                                }
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
    @yield('version-scripts')
@endsection
