@extends('layouts.authmaster')

@section('styles')
    <style>
        .input-group-text {
            cursor: pointer;
        }

        .progress {
            height: 10px;
        }

        .progress-bar {
            transition: width 0.5s;
        }

        .strength-text {
            margin-top: 5px;
            font-weight: bold;
            transition: color 0.5s;
        }

        .requirements {
            list-style-type: none;
            padding: 0;
            margin: 10px 0 0 0;
        }

        .requirements li {
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .requirements li.valid::before {
            content: '✔';
            color: green;
            margin-right: 5px;
        }

        .requirements li.invalid::before {
            content: '✖';
            color: red;
            margin-right: 5px;
        }

        .email-existing {
            font-size: 0.9rem;
            color: red;
            margin-top: 5px;
            font-weight: 400 !important;
        }

        /* Role Selection Tabs and Premium Package Cards */
        .role-tabs {
            display: flex;
            background: #f1f5f9;
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 24px;
            border: 1px solid #e2e8f0;
        }
        .role-tab-btn {
            flex: 1;
            text-align: center;
            padding: 10px 16px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            background: transparent;
            color: #64748b;
            border-radius: 9px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .role-tab-btn.active {
            background: #fff;
            color: #2563eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        .reg-package-card {
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            background: #fff;
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .reg-package-card:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
        }
        .reg-package-card.selected {
            border-color: #10b981;
            background-color: #f0fdf4;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.1);
        }
        .reg-package-card.selected::before {
            content: '✔';
            position: absolute;
            top: -10px;
            right: -10px;
            background: #10b981;
            color: #fff;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            font-family: Arial, sans-serif;
            font-weight: bold;
        }
        .cycle-select {
            font-size: 13px;
            border-radius: 6px;
            margin-top: 8px;
            border: 1px solid #cbd5e1;
            padding: 4px 8px;
            width: 100%;
        }
        .registration-form {
            transition: max-width 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .registration-form.wide {
            max-width: 850px !important;
        }
    </style>
@endsection

@section('content')
    <div class="my-">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
    <div class="container form-container">
        <div class="registration-form">
            <div class="form-logo text-center">
                <img src="{{ \App\Services\SettingsService::getLogo() }}" alt="" class="img-fluid" width="150">
            </div>
            <h5 class=" mt-1 text-center mb-3">{{ __('Register Now!') }}</h5>

            <!-- Role Selection Tabs -->
            <div class="role-tabs">
                <button type="button" class="role-tab-btn active" id="user-tab-btn" onclick="selectRegRole('user')">
                    <i class="fas fa-user me-1"></i> Register as User
                </button>
                <button type="button" class="role-tab-btn" id="admin-tab-btn" onclick="selectRegRole('admin')">
                    <i class="fas fa-user-shield me-1"></i> Register as Admin
                </button>
            </div>

            <form method="POST" action="{{ route('register') }}" id="mainRegisterForm">
                @csrf
                <input type="hidden" name="role" id="register_role" value="user">
                <input type="hidden" name="package_id" id="register_package_id" value="">
                <input type="hidden" name="billing_cycle" id="register_billing_cycle" value="">
                <input type="hidden" name="custom_features" id="register_custom_features" value="">

                <!-- Admin Packages Selector -->
                <div id="admin-packages-section" style="display: none;" class="mb-4">
                    <h6 class="font-weight-bold mb-3 border-bottom pb-2 text-primary">
                        <i class="fas fa-box-open me-1"></i> Select Subscription Package
                    </h6>

                    <!-- Billing Cycle Tabs -->
                    <div class="d-flex justify-content-center mb-3">
                        <div class="btn-group w-100" id="cycleTabs" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary active" id="cycle-monthly-btn" onclick="setRegCycle('monthly')">Monthly</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="cycle-yearly-btn" onclick="setRegCycle('yearly')">Yearly</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="cycle-lifetime-btn" onclick="setRegCycle('lifetime')">Lifetime</button>
                        </div>
                    </div>

                    <div id="regPackagesContainer" class="row">
                        <!-- Loaded dynamically via JS -->
                    </div>

                    <!-- Custom Package Interactive Feature Selector Container -->
                    <div id="customFeaturesContainer" class="mt-3 p-3 bg-white border rounded-3 shadow-sm" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                            <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 13px;">
                                <i class="fas fa-sliders-h text-primary me-2"></i> Custom Package Feature Picker (<span id="customSelectedCountBadge">0 selected</span>)
                            </h6>
                            <button type="button" class="btn btn-sm btn-link text-decoration-none p-0 text-primary small font-weight-bold" onclick="toggleAllCustomRegFeatures(true)">Select All</button>
                        </div>
                        <div id="customFeaturesChecklist" class="row g-2" style="max-height: 250px; overflow-y: auto;">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="fullname">{{ __('Name') }}</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter your name"
                        autocomplete="name" name="name" autofocus required>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                @if(setting('registration', 'email_enabled', '1') == '1')
                <div class="form-group">
                    <label for="email">{{ __('Email Address') }}
                        @if(setting('registration', 'email_required', '0') == '0')
                            <small class="text-muted">(Optional)</small>
                        @endif
                    </label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" id="email" 
                        placeholder="Enter your email" 
                        autocomplete="email"
                        @if(setting('registration', 'email_required', '0') == '1') required @endif>
                    <div class="email-existing"></div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                @endif
                <div class="form-group">
                    <label for="phone">{{ __('Phone Number') }}</label>
                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone"
                        value="{{ old('phone') }}" id="phone" placeholder="Enter your phone number" autocomplete="tel"
                        required>
                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">{{ __('Password') }}</label>
                    <div class="input-group mt-2">
                        <input id="password" type="password" class="form-control" name="password" required
                            autocomplete="new-password">
                        <div class="input-group-append">
                            <button id="toggle-password" class="btn btn-outline-primary" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="progress mt-2">
                        <div id="password-strength" class="progress-bar" role="progressbar" style="width: 0;"></div>
                    </div>
                    <div id="strength-text" class="strength-text"></div>
                    <ul id="password-requirements" class="requirements">
                        <li id="length" class="invalid">Minimum 8 characters</li>
                        <li id="lowercase" class="invalid">At least one lowercase letter</li>
                        <li id="uppercase" class="invalid">At least one uppercase letter</li>
                        <li id="number" class="invalid">At least one number</li>
                        <li id="special" class="invalid">At least one special character</li>
                    </ul>

                    <span id="password-error" class="invalid-feedback" role="alert" style="display: none;">
                        <strong>Password error message</strong>
                    </span>
                    <a id="generate-password" class="btn btn-primary mt-1">Generate Password</a>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                        autocomplete="new-password">
                </div>
                {{-- <div class="form-group">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="help-block email-existing">
                            <small>{{ $errors->first('g-recaptcha-response') }}</small>
                        </span>
                    @endif
                </div> --}}
                <button type="submit" class="btn btn-primary btn-block">{{ __('Register') }}</button>

                <div class="text-center mt-3">
                    @if (Route::has('login'))
                        <small>
                            <span class="text-muted">Existing User?</span>
                            <a href="{{ route('login') }}">{{ __('Login') }}</a>
                        </small>
                        <br>
                    @endif
                    @if (Route::has('vendor.register'))
                        <small>
                            <span class="text-muted">Want to become a seller?</span>
                            <a href="{{ route('vendor.register') }}">{{ __('Vendor Registration') }}</a>
                        </small>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <!-- OTP Verification Modal -->
    <div class="modal fade" id="otpVerificationModal" tabindex="-1" role="dialog" aria-labelledby="otpVerificationModalLabel" aria-hidden="true" data-backdrop="true" data-keyboard="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otpVerificationModalLabel">Verify Your Phone Number</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalBtn">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                        <p class="text-muted">We've sent a verification code to your phone number</p>
                        <p class="font-weight-bold" id="phoneNumberDisplay"></p>
                    </div>
                    
                    <form id="otpVerificationForm">
                        @csrf
                        <div class="form-group">
                            <label for="otp_code">Enter Verification Code</label>
                            <input type="text" class="form-control text-center" id="otp_code" name="otp_code" 
                                   placeholder="0000" maxlength="4" required>
                            <small class="form-text text-muted">Enter the 4-digit code sent to your phone</small>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block" id="verifyOtpBtn">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                Verify Code
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p class="text-muted">Didn't receive the code?</p>
                        <button type="button" class="btn btn-link" id="resendOtpBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Resend Code
                        </button>
                        <div id="resendTimer" class="text-muted mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function generatePassword(length = 12) {
            if (length < 4) {
                throw new Error("Password length should be at least 4 characters");
            }

            const lowercase = "abcdefghijklmnopqrstuvwxyz";
            const uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            const digits = "0123456789";
            const specialCharacters = "!@#$%^&*()-_=+[]{}|;:,.<>?";

            let password = [
                lowercase[Math.floor(Math.random() * lowercase.length)],
                uppercase[Math.floor(Math.random() * uppercase.length)],
                digits[Math.floor(Math.random() * digits.length)],
                specialCharacters[Math.floor(Math.random() * specialCharacters.length)]
            ];

            const allCharacters = lowercase + uppercase + digits + specialCharacters;
            for (let i = password.length; i < length; i++) {
                password.push(allCharacters[Math.floor(Math.random() * allCharacters.length)]);
            }

            password = password.sort(() => Math.random() - 0.5);
            return password.join('');
        }

        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength += 1;
            if (password.match(/[a-z]+/)) strength += 1;
            if (password.match(/[A-Z]+/)) strength += 1;
            if (password.match(/[0-9]+/)) strength += 1;
            if (password.match(/[\W_]+/)) strength += 1;
            return strength;
        }

        function updatePasswordStrength(password) {
            const strength = checkPasswordStrength(password);
            const progressBar = document.getElementById('password-strength');
            const strengthText = document.getElementById('strength-text');
            const strengths = [{
                    width: '0%',
                    color: 'bg-danger',
                    text: 'Very Weak',
                    textColor: 'red'
                },
                {
                    width: '20%',
                    color: 'bg-danger',
                    text: 'Weak',
                    textColor: 'red'
                },
                {
                    width: '40%',
                    color: 'bg-warning',
                    text: 'Moderate',
                    textColor: 'orange'
                },
                {
                    width: '60%',
                    color: 'bg-info',
                    text: 'Good',
                    textColor: 'blue'
                },
                {
                    width: '80%',
                    color: 'bg-primary',
                    text: 'Strong',
                    textColor: 'blue'
                },
                {
                    width: '100%',
                    color: 'bg-success',
                    text: 'Very Strong',
                    textColor: 'green'
                }
            ];
            progressBar.style.width = strengths[strength].width;
            progressBar.className = `progress-bar ${strengths[strength].color}`;
            strengthText.textContent = strengths[strength].text;
            strengthText.style.color = strengths[strength].textColor;
        }

        function updatePasswordRequirements(password) {
            const lengthRequirement = document.getElementById('length');
            const lowercaseRequirement = document.getElementById('lowercase');
            const uppercaseRequirement = document.getElementById('uppercase');
            const numberRequirement = document.getElementById('number');
            const specialRequirement = document.getElementById('special');

            if (password.length >= 8) {
                lengthRequirement.classList.add('valid');
                lengthRequirement.classList.remove('invalid');
            } else {
                lengthRequirement.classList.add('invalid');
                lengthRequirement.classList.remove('valid');
            }

            if (/[a-z]/.test(password)) {
                lowercaseRequirement.classList.add('valid');
                lowercaseRequirement.classList.remove('invalid');
            } else {
                lowercaseRequirement.classList.add('invalid');
                lowercaseRequirement.classList.remove('valid');
            }

            if (/[A-Z]/.test(password)) {
                uppercaseRequirement.classList.add('valid');
                uppercaseRequirement.classList.remove('invalid');
            } else {
                uppercaseRequirement.classList.add('invalid');
                uppercaseRequirement.classList.remove('valid');
            }

            if (/\d/.test(password)) {
                numberRequirement.classList.add('valid');
                numberRequirement.classList.remove('invalid');
            } else {
                numberRequirement.classList.add('invalid');
                numberRequirement.classList.remove('valid');
            }

            if (/[\W_]/.test(password)) {
                specialRequirement.classList.add('valid');
                specialRequirement.classList.remove('invalid');
            } else {
                specialRequirement.classList.add('invalid');
                specialRequirement.classList.remove('valid');
            }
        }

        document.getElementById('generate-password').addEventListener('click', function() {
            const password = generatePassword(12);
            document.getElementById('password').value = password;
            document.getElementById('password-confirm').value = password;
            updatePasswordStrength(password);
            updatePasswordRequirements(password);
        });

        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const passwordConfirmField = document.getElementById('password-confirm');
            const icon = this.querySelector('i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordConfirmField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                passwordConfirmField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            updatePasswordStrength(password);
            updatePasswordRequirements(password);
        });
    </script>

    <script>
        // Assuming you're using jQuery for AJAX
        $('#email').on('input', function() {
            var email = $(this).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('check.email') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'email': email
                },
                success: function(response) {
                    if (response.exists) {
                        // Email exists
                        $('#email').addClass('is-invalid');
                        $('#email').siblings('.email-existing').html(
                            '<strong>Email already exists.</strong>');
                    } else {
                        // Email does not exist
                        $('#email').removeClass('is-invalid');
                        $('#email').siblings('.email-existing').html('');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    $('#email').addClass('is-invalid');
                    $('#email').siblings('.email-existing').html(
                        '<strong>Error checking email. Please try again later.</strong>');
                }
            });
        });
    </script>

    <script>
        // OTP Verification functionality
        let resendTimer = null;
        let resendCount = 0;
        const maxResendLimit = 5;

        // Handle form submission
        $('form[method="POST"]').on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const phone = formData.get('phone');
            const name = formData.get('name');
            const email = formData.get('email');
            const password = formData.get('password');
            const password_confirmation = formData.get('password_confirmation');

            // Basic validation
            if (!name || !phone || !password || !password_confirmation) {
                alert('Please fill in all required fields.');
                return;
            }

            if (password !== password_confirmation) {
                alert('Passwords do not match.');
                return;
            }

            // Show loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Registering...');

            // Send registration request
            $.ajax({
                url: '{{ route("register") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'otp_sent') {
                        // Show OTP modal
                        $('#phoneNumberDisplay').text(phone);
                        $('#otpVerificationModal').modal('show');
                        startResendTimer();
                    } else if (response.status === 'success') {
                        // Registration successful, redirect
                        window.location.href = response.redirect || '/account/profile';
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Registration failed. Please try again.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        const errorMessages = [];
                        
                        Object.keys(errors).forEach(key => {
                            errorMessages.push(errors[key][0]);
                        });
                        
                        errorMessage = errorMessages.join('\n');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    alert(errorMessage);
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Handle OTP verification
        $('#otpVerificationForm').on('submit', function(e) {
            e.preventDefault();
            
            const otpCode = $('#otp_code').val();
            if (!otpCode || otpCode.length !== 4) {
                alert('Please enter a valid 4-digit OTP code.');
                return;
            }

            const verifyBtn = $('#verifyOtpBtn');
            const spinner = verifyBtn.find('.spinner-border');
            const originalText = verifyBtn.html();

            verifyBtn.prop('disabled', true);
            spinner.removeClass('d-none');

            $.ajax({
                url: '{{ route("verify.registration.otp") }}',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    otp_code: otpCode
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#otpVerificationModal').modal('hide');
                        alert('Registration successful! Welcome to Thikana Shop.');
                        window.location.href = response.redirect || '/account/profile';
                    } else {
                        alert(response.message || 'OTP verification failed. Please try again.');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'OTP verification failed. Please try again.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    alert(errorMessage);
                },
                complete: function() {
                    verifyBtn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });

        // Handle resend OTP
        $('#resendOtpBtn').on('click', function() {
            if (resendCount >= maxResendLimit) {
                alert('Maximum resend limit reached. Please try again later.');
                return;
            }

            const resendBtn = $(this);
            const spinner = resendBtn.find('.spinner-border');
            const originalText = resendBtn.html();

            resendBtn.prop('disabled', true);
            spinner.removeClass('d-none');

            $.ajax({
                url: '{{ route("resend.registration.otp") }}',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'success') {
                        resendCount++;
                        startResendTimer();
                        alert('OTP resent successfully!');
                    } else {
                        alert(response.message || 'Failed to resend OTP. Please try again.');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to resend OTP. Please try again.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    alert(errorMessage);
                },
                complete: function() {
                    resendBtn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });

        // Resend timer functionality
        function startResendTimer() {
            let timeLeft = 60;
            const timerElement = $('#resendTimer');
            const resendBtn = $('#resendOtpBtn');
            
            resendBtn.prop('disabled', true);
            
            resendTimer = setInterval(function() {
                timerElement.text(`Resend available in ${timeLeft} seconds`);
                timeLeft--;
                
                if (timeLeft < 0) {
                    clearInterval(resendTimer);
                    timerElement.text('');
                    resendBtn.prop('disabled', false);
                }
            }, 1000);
        }

        // Clear timer when modal is closed
        $('#otpVerificationModal').on('hidden.bs.modal', function() {
            if (resendTimer) {
                clearInterval(resendTimer);
                resendTimer = null;
            }
        });

        // Multiple ways to close the modal
        $('#closeModalBtn').on('click', function() {
            $('#otpVerificationModal').modal('hide');
        });

        $('#otpVerificationModal .close').on('click', function() {
            $('#otpVerificationModal').modal('hide');
        });

        $('#otpVerificationModal [data-dismiss="modal"]').on('click', function() {
            $('#otpVerificationModal').modal('hide');
        });

        // Close modal with Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('#otpVerificationModal').hasClass('show')) {
                $('#otpVerificationModal').modal('hide');
            }
        });

        // Auto-focus OTP input when modal is shown
        $('#otpVerificationModal').on('shown.bs.modal', function() {
            $('#otp_code').focus();
        });

        // Format OTP input (numbers only)
        $('#otp_code').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // COMPREHENSIVE SIDEBAR FEATURES & SAAS PACKAGES REGISTRATION SYSTEM
        const SIDEBAR_FEATURE_GROUPS = [
            {
                category: "Main Dashboard",
                icon: "fas fa-chart-pie text-primary",
                items: [
                    "Dashboard Overview"
                ]
            },
            {
                category: "Product Catalog",
                icon: "fas fa-cubes text-info",
                items: [
                    "All Products Management",
                    "Add New Product",
                    "Product Categories & Subcategories",
                    "Brands Management",
                    "Writers & Authors",
                    "Publishers Management",
                    "Product Reviews & Feedback",
                    "Combo Offers & Bundles"
                ]
            },
            {
                category: "Inventory & Stock",
                icon: "fas fa-warehouse text-success",
                items: [
                    "Inventory Overview",
                    "Low Stock Alerts",
                    "Out of Stock Items",
                    "Stock Movement History"
                ]
            },
            {
                category: "Landing Pages",
                icon: "fas fa-pager text-warning",
                items: [
                    "Landing Pages Directory",
                    "Create & Design Landing Page"
                ]
            },
            {
                category: "Sales & Orders",
                icon: "fas fa-cart-shopping text-primary",
                items: [
                    "All Orders Management",
                    "My Assigned Orders",
                    "Incomplete & Abandoned Orders",
                    "Point of Sale (POS) Terminal"
                ]
            },
            {
                category: "Shipping & Delivery",
                icon: "fas fa-truck-fast text-emerald",
                items: [
                    "Global Shipping Settings",
                    "Advanced Shipping Rules & Delivery Zones",
                    "Courier Integrations (Pathao/Steadfast)"
                ]
            },
            {
                category: "Reports & Insights",
                icon: "fas fa-chart-column text-warning",
                items: [
                    "Sales & Revenue Reports",
                    "Customer Analytics Reports"
                ]
            },
            {
                category: "Connected Apps & Integrations",
                icon: "fas fa-plug text-danger",
                items: [
                    "Daraz Marketplace Sync",
                    "WooCommerce Data Migration",
                    "Telegram Notifications Bot",
                    "Delayed Purchase Event Queue"
                ]
            },
            {
                category: "Security & Trust",
                icon: "fas fa-shield-halved text-danger",
                items: [
                    "Fraud Checker & Risk Scanner",
                    "Fraud Protection Shield",
                    "System Backup & Schedules"
                ]
            },
            {
                category: "Content & Pages",
                icon: "fas fa-newspaper text-secondary",
                items: [
                    "Hero Sliders Management",
                    "Custom Web Pages",
                    "Navigation Menu Builder",
                    "Blog Posts Management",
                    "Blog Categories & Topics",
                    "Post Comments Moderation"
                ]
            },
            {
                category: "Multi-Vendor Management",
                icon: "fas fa-store text-info",
                items: [
                    "Vendor Directory & Approvals",
                    "Add New Vendor",
                    "Vendor Product Approval",
                    "Vendor Withdrawal Requests",
                    "Global Vendor Commission Settings"
                ]
            },
            {
                category: "System Settings & Control",
                icon: "fas fa-gears text-secondary",
                items: [
                    "Users & Team Members Management",
                    "Contact Messages Inbox",
                    "Newsletter Subscriptions",
                    "Roles & Permissions Matrix",
                    "System Extensions & Modules",
                    "All Website Settings",
                    "Payment Gateway Setup",
                    "License Key Management",
                    "System Updates & Version Control"
                ]
            }
        ];

        const DEFAULT_FEATURES = SIDEBAR_FEATURE_GROUPS.flatMap(g => g.items);

        const DEFAULT_PACKAGES = [
            {
                id: "starter_plan",
                name: "Starter Plan",
                details: "Ideal for fresh startups and small stores requiring core ecommerce functionality.",
                priceMonthly: "1200",
                priceYearly: "12000",
                priceLifetime: "30000",
                status: true,
                features: [
                    "Dashboard Overview",
                    "All Products Management",
                    "Add New Product",
                    "Product Categories & Subcategories",
                    "Inventory Overview",
                    "All Orders Management",
                    "Global Shipping Settings",
                    "All Website Settings"
                ]
            },
            {
                id: "pro_plan",
                name: "Professional Plan",
                details: "Perfect for growing merchants and professional retailers needing advanced tools.",
                priceMonthly: "3500",
                priceYearly: "35000",
                priceLifetime: "80000",
                status: true,
                features: [
                    "Dashboard Overview",
                    "All Products Management",
                    "Add New Product",
                    "Product Categories & Subcategories",
                    "Brands Management",
                    "Product Reviews & Feedback",
                    "Inventory Overview",
                    "Low Stock Alerts",
                    "Landing Pages Directory",
                    "All Orders Management",
                    "Point of Sale (POS) Terminal",
                    "Global Shipping Settings",
                    "Courier Integrations (Pathao/Steadfast)",
                    "Sales & Revenue Reports",
                    "Fraud Checker & Risk Scanner",
                    "Roles & Permissions Matrix",
                    "Payment Gateway Setup"
                ]
            },
            {
                id: "enterprise_plan",
                name: "Enterprise Ultimate",
                details: "Tailored specifically for large-scale multi-vendor operations and enterprise networks.",
                priceMonthly: "8500",
                priceYearly: "85000",
                priceLifetime: "200000",
                status: true,
                features: DEFAULT_FEATURES
            },
            {
                id: "custom_plan",
                name: "Custom Package",
                details: "Build a tailored subscription plan by picking exact features & modules.",
                priceMonthly: "Custom",
                priceYearly: "Custom",
                priceLifetime: "Custom",
                status: true,
                isCustom: true,
                features: []
            }
        ];

        let activeCycle = 'monthly';

        window.setRegCycle = function(cycle) {
            activeCycle = cycle;
            $('#register_billing_cycle').val(cycle);
            $('#cycleTabs button').removeClass('active');
            $(`#cycle-${cycle}-btn`).addClass('active');
            renderRegPackages();
        };

        window.selectRegRole = function(role) {
            $('#register_role').val(role);
            $('.role-tab-btn').removeClass('active');
            if (role === 'admin') {
                $('#admin-tab-btn').addClass('active');
                $('.registration-form').addClass('wide');
                $('#admin-packages-section').slideDown();
                setRegCycle('monthly');
            } else {
                $('#user-tab-btn').addClass('active');
                $('.registration-form').removeClass('wide');
                $('#admin-packages-section').slideUp();
                $('#register_package_id').val('');
                $('#register_billing_cycle').val('');
            }
        };

        function renderRegPackages() {
            const container = document.getElementById('regPackagesContainer');
            container.innerHTML = '';
            
            let list = DEFAULT_PACKAGES;
            const stored = localStorage.getItem('admin_packages_list_v3');
            if (stored) {
                list = JSON.parse(stored).filter(p => p.status !== false);
            }

            let featPool = DEFAULT_FEATURES;
            const storedFeats = localStorage.getItem('admin_packages_features_pool_v3');
            if (storedFeats) {
                featPool = JSON.parse(storedFeats);
            }

            if (list.length === 0) {
                container.innerHTML = `<p class="col-12 text-muted text-center py-3">No packages are currently active.</p>`;
                return;
            }

            list.forEach((pkg, idx) => {
                const col = document.createElement('div');
                col.className = 'col-md-6 col-12 mb-3';

                // Determine price based on activeCycle
                let price = pkg.priceMonthly;
                let cycleText = '/mo';
                if (activeCycle === 'yearly') {
                    price = pkg.priceYearly;
                    cycleText = '/yr';
                } else if (activeCycle === 'lifetime') {
                    price = pkg.priceLifetime;
                    cycleText = ' One-time';
                }

                if (pkg.isCustom) {
                    col.innerHTML = `
                        <div class="reg-package-card shadow-sm border rounded-3 p-3 h-100 d-flex flex-column justify-content-between" id="regCard_${pkg.id}" onclick="selectPackageCard('${pkg.id}')" style="cursor: pointer; background: #fff; border-style: dashed !important; border-color: #3b82f6 !important;">
                            <div>
                                <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-2">
                                    <div>
                                        <h6 class="font-weight-bold mb-0 text-primary"><i class="fas fa-sliders-h me-1"></i> ${pkg.name}</h6>
                                        <p class="small text-muted mb-0" style="font-size: 11px; line-height: 1.2;">${pkg.details}</p>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary text-white font-weight-bold">Custom</span>
                                    </div>
                                </div>
                                <div class="p-2 text-center text-muted small bg-light rounded-3" style="font-size: 11.5px;">
                                    <i class="fas fa-check-square text-primary me-1"></i> Pick & choose modules below
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    // Render features grouped by category for high clarity
                    let featuresHTML = '';
                    SIDEBAR_FEATURE_GROUPS.forEach(group => {
                        const poolCategoryItems = group.items.filter(item => featPool.includes(item));
                        if (poolCategoryItems.length > 0) {
                            const groupIncludedItems = poolCategoryItems.filter(item => pkg.features && pkg.features.includes(item));
                            featuresHTML += `
                                <div class="mb-2">
                                    <div class="small font-weight-bold text-muted text-uppercase mb-1 border-bottom pb-1" style="font-size: 9.5px; letter-spacing: 0.5px;">
                                        <i class="${group.icon} me-1"></i> ${group.category} (${groupIncludedItems.length}/${poolCategoryItems.length})
                                    </div>
                            `;
                            poolCategoryItems.forEach(feat => {
                                const isIncluded = pkg.features && pkg.features.includes(feat);
                                featuresHTML += `
                                    <div class="d-flex align-items-center mb-1 text-start" style="font-size: 11px; ${isIncluded ? 'color: #0f172a; font-weight: 600;' : 'color: #94a3b8; text-decoration: line-through; opacity: 0.7;'}">
                                        <i class="${isIncluded ? 'fas fa-check-circle me-2' : 'fas fa-times-circle me-2'}" style="color: ${isIncluded ? '#10b981' : '#ef4444'} !important;"></i>
                                        <span>${feat}</span>
                                    </div>
                                `;
                            });
                            featuresHTML += `</div>`;
                        }
                    });

                    col.innerHTML = `
                        <div class="reg-package-card shadow-sm border rounded-3 p-3" id="regCard_${pkg.id}" onclick="selectPackageCard('${pkg.id}')" style="cursor: pointer; background: #fff;">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-2">
                                <div>
                                    <h6 class="font-weight-bold mb-0 text-dark">${pkg.name}</h6>
                                    <p class="small text-muted mb-0" style="font-size: 11px; line-height: 1.2;">${pkg.details}</p>
                                </div>
                                <div class="text-end">
                                    <span class="font-weight-bold text-success" style="font-size: 14px;">TK ${price}</span>
                                    <small class="text-muted d-block" style="font-size: 9px; margin-top: -2px;">${cycleText}</small>
                                </div>
                            </div>
                            <div class="features-list-wrapper" style="max-height: 160px; overflow-y: auto; padding-right: 4px;">
                                ${featuresHTML}
                            </div>
                        </div>
                    `;
                }
                container.appendChild(col);
            });

            // Re-apply highlight to selected card
            const currentSelected = $('#register_package_id').val();
            if (currentSelected && list.some(p => p.id === currentSelected)) {
                selectPackageCard(currentSelected);
            } else if (list.length > 0) {
                selectPackageCard(list[0].id);
            }
        }

        // Render Custom Package Interactive Feature Checklist
        function renderCustomRegFeaturesPicker() {
            const container = document.getElementById('customFeaturesChecklist');
            if (!container) return;
            container.innerHTML = '';

            SIDEBAR_FEATURE_GROUPS.forEach(group => {
                let itemsHTML = '';
                group.items.forEach(feat => {
                    const safeId = 'custom_reg_' + feat.replace(/[^a-zA-Z0-9]/g, '_');
                    itemsHTML += `
                        <div class="col-md-6 col-12">
                            <label class="d-flex align-items-center p-2 rounded border bg-white shadow-sm mb-0 w-100" for="${safeId}" style="cursor: pointer; min-height: 38px;">
                                <input class="custom-reg-feature-cb flex-shrink-0 me-2" type="checkbox" value="${feat}" id="${safeId}" onchange="updateCustomRegFeaturesState()" style="cursor: pointer; width: 16px; height: 16px; margin: 0;">
                                <span class="text-dark font-weight-medium" style="font-size: 11px; line-height: 1.3;">${feat}</span>
                            </label>
                        </div>
                    `;
                });

                const groupCol = document.createElement('div');
                groupCol.className = 'col-12 mb-2';
                groupCol.innerHTML = `
                    <div class="small font-weight-bold text-primary text-uppercase mb-1 border-bottom pb-1" style="font-size: 10px;">
                        <i class="${group.icon} me-1"></i> ${group.category}
                    </div>
                    <div class="row g-2">${itemsHTML}</div>
                `;
                container.appendChild(groupCol);
            });
            updateCustomRegFeaturesState();
        }

        window.updateCustomRegFeaturesState = function() {
            const checkedCbs = Array.from(document.querySelectorAll('.custom-reg-feature-cb:checked')).map(cb => cb.value);
            document.getElementById('customSelectedCountBadge').textContent = checkedCbs.length + ' selected';
            $('#register_custom_features').val(JSON.stringify(checkedCbs));
        };

        window.toggleAllCustomRegFeatures = function(checkAll) {
            document.querySelectorAll('.custom-reg-feature-cb').forEach(cb => {
                cb.checked = checkAll;
            });
            updateCustomRegFeaturesState();
        };

        window.selectPackageCard = function(pkgId) {
            $('.reg-package-card').removeClass('selected');
            $(`#regCard_${pkgId}`).addClass('selected');
            $('#register_package_id').val(pkgId);
            $('#register_billing_cycle').val(activeCycle);

            if (pkgId === 'custom_plan') {
                $('#customFeaturesContainer').slideDown();
                if ($('#customFeaturesChecklist').children().length === 0) {
                    renderCustomRegFeaturesPicker();
                }
            } else {
                $('#customFeaturesContainer').slideUp();
            }
        };

        // Intercept form submission to save details client-side
        $('#mainRegisterForm').on('submit', function(e) {
            const role = $('#register_role').val();
            if (role === 'admin') {
                const pkgId = $('#register_package_id').val();
                const cycle = $('#register_billing_cycle').val();
                if (!pkgId || !cycle) {
                    e.preventDefault();
                    alert('Please select an admin package and billing cycle.');
                    return false;
                }

                // Gather details
                const name = $('#name').val();
                const email = $('#email').val() || '';
                const phone = $('#phone').val();

                // Find package details
                const storedPackages = localStorage.getItem('admin_packages_list_v3');
                let pkgName = "Starter Plan";
                let pkgPrice = "1200";
                
                let list = DEFAULT_PACKAGES;
                if (storedPackages) {
                    list = JSON.parse(storedPackages);
                }

                const selectedPkg = list.find(p => p.id === pkgId);
                if (selectedPkg) {
                    pkgName = selectedPkg.name;
                    pkgPrice = cycle === 'monthly' ? selectedPkg.priceMonthly : (cycle === 'yearly' ? selectedPkg.priceYearly : selectedPkg.priceLifetime);
                }

                const customFeatsJson = $('#register_custom_features').val() || '[]';
                const customFeatsArray = JSON.parse(customFeatsJson);

                const registeredAdmins = JSON.parse(localStorage.getItem('registered_admins') || '[]');
                registeredAdmins.push({
                    id: 'adm_' + Date.now(),
                    name: name,
                    email: email,
                    phone: phone,
                    role: 'admin',
                    packageName: pkgName,
                    billingCycle: cycle.toUpperCase(),
                    price: pkgPrice,
                    custom_features: customFeatsArray,
                    created_at: new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                });
                localStorage.setItem('registered_admins', JSON.stringify(registeredAdmins));
            }
        });
    </script>
@endsection
