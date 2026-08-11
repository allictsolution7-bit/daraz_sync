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

        .email-existing,
        .slug-feedback {
            font-size: 0.9rem;
            margin-top: 5px;
            font-weight: 400 !important;
        }

        .slug-feedback.available {
            color: green;
        }

        .slug-feedback.unavailable {
            color: red;
        }

        .slug-preview {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }

        .form-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .form-section h6 {
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }
        .gap-4 {
            gap: 1.5rem;
        }
        .registration-form {
            transition: max-width 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
            max-width: 480px;
            width: 100%;
        }
        .registration-form.wide-form {
            max-width: 950px !important;
        }
        .role-radio:checked + .role-label {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
        }
        .role-label {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border-width: 2px;
            border-radius: 8px;
        }
        .role-label:hover {
            border-color: #007bff;
        }
        #generate-password {
            cursor: pointer;
            text-decoration: none;
        }
        #generate-password:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('content')
    <div class="my-3">
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
            <h5 class="mt-1 text-center">{{ __('Register as Partner') }}</h5>
            <p class="text-center text-muted small">Choose your role and register to start partnership</p>

            <form method="POST" action="{{ route('partner.register.submit') }}">
                @csrf

                <div class="row">
                    <div id="form-left-col" class="col-md-6">
                        <!-- Role Selection -->
                        <div class="form-section">
                            <h6><i class="fas fa-user-tag"></i> Select Partnership Role</h6>
                            <div class="form-group">
                                <label class="d-block">Join As <span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-2 justify-content-between role-selector">
                                    <div class="flex-fill mr-2" style="min-width: 120px; margin-bottom: 8px;">
                                        <input type="radio" name="role" id="role_vendor" value="vendor" class="d-none role-radio" {{ old('role', 'vendor') == 'vendor' ? 'checked' : '' }}>
                                        <label for="role_vendor" class="btn btn-outline-primary btn-block p-3 text-center role-label">
                                            <i class="fas fa-store d-block mb-1 fa-2x"></i>
                                            Vendor
                                        </label>
                                    </div>
                                    <div class="flex-fill mr-2" style="min-width: 120px; margin-bottom: 8px;">
                                        <input type="radio" name="role" id="role_reseller" value="reseller" class="d-none role-radio" {{ old('role') == 'reseller' ? 'checked' : '' }}>
                                        <label for="role_reseller" class="btn btn-outline-primary btn-block p-3 text-center role-label">
                                            <i class="fas fa-people-arrows d-block mb-1 fa-2x"></i>
                                            Reseller
                                        </label>
                                    </div>
                                    <div class="flex-fill mr-2" style="min-width: 120px; margin-bottom: 8px;">
                                        <input type="radio" name="role" id="role_wholeseller" value="wholeseller" class="d-none role-radio" {{ old('role') == 'wholeseller' ? 'checked' : '' }}>
                                        <label for="role_wholeseller" class="btn btn-outline-primary btn-block p-3 text-center role-label">
                                            <i class="fas fa-warehouse d-block mb-1 fa-2x"></i>
                                            Wholeseller
                                        </label>
                                    </div>
                                    <div class="flex-fill mr-2" style="min-width: 120px; margin-bottom: 8px;">
                                        <input type="radio" name="role" id="role_retailer" value="retailer" class="d-none role-radio" {{ old('role') == 'retailer' ? 'checked' : '' }}>
                                        <label for="role_retailer" class="btn btn-outline-primary btn-block p-3 text-center role-label">
                                            <i class="fas fa-shopping-basket d-block mb-1 fa-2x"></i>
                                            Retailer
                                        </label>
                                    </div>
                                    <div class="flex-fill" style="min-width: 120px; margin-bottom: 8px;">
                                        <input type="radio" name="role" id="role_paid_vendor" value="paid_vendor" class="d-none role-radio" {{ old('role') == 'paid_vendor' ? 'checked' : '' }}>
                                        <label for="role_paid_vendor" class="btn btn-outline-primary btn-block p-3 text-center role-label">
                                            <i class="fas fa-money-bill-wave d-block mb-1 fa-2x"></i>
                                            Paid Vendor
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Vendor Type selection (only shown if role is Vendor) -->
                            <div class="form-group d-none" id="vendor-type-group">
                                <label>Vendor Type <span class="text-danger">*</span></label>
                                <div class="d-flex gap-4 align-items-center mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="vendor_type" id="retailer" value="retailer" checked>
                                        <label class="form-check-label" for="retailer">Retailer</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="vendor_type" id="wholesale_vendor" value="wholeseller">
                                        <label class="form-check-label" for="wholesale_vendor">Wholeseller</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="form-section">
                            <h6><i class="fas fa-user"></i> Personal Information</h6>

                            <div class="form-group">
                                <label for="name">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" 
                                       placeholder="Enter your full name" name="name" value="{{ old('name') }}" 
                                       autocomplete="name" autofocus required>
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
                                    @else
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                       value="{{ old('email') }}" id="email" placeholder="your@email.com" autocomplete="email"
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
                                <label for="phone">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone"
                                       value="{{ old('phone') }}" id="phone" placeholder="01XXXXXXXXX" autocomplete="tel" required>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="password" class="mb-0">{{ __('Password') }} <span class="text-danger">*</span></label>
                                    <a id="generate-password" class="text-primary font-weight-bold small"><i class="fas fa-key"></i> Generate Password</a>
                                </div>
                                <div class="input-group mt-2">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                           name="password" required autocomplete="new-password">
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
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">Confirm Password <span class="text-danger">*</span></label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" 
                                       required autocomplete="new-password">
                            </div>
                        </div>
                    </div>

                    <div id="form-right-col" class="col-md-6">
                        <!-- Business Information -->
                        <div class="form-section" id="business-info-section">
                            <h6><i class="fas fa-store"></i> Business Information</h6>

                            <div class="form-group">
                                <label for="business_name">{{ __('Business/Shop Name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('business_name') is-invalid @enderror required-field" 
                                       id="business_name" name="business_name" value="{{ old('business_name') }}" 
                                       placeholder="Your Shop Name">
                                @error('business_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="store_slug">{{ __('Store URL (Slug)') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('store_slug') is-invalid @enderror required-field" 
                                       id="store_slug" name="store_slug" value="{{ old('store_slug') }}" 
                                       placeholder="my-awesome-shop" pattern="[a-z0-9-]+">
                                <div class="slug-preview">
                                    Your store will be accessible at: <strong id="slug-url">{{ url('/store/') }}/your-slug</strong>
                                </div>
                                <div class="slug-feedback"></div>
                                <small class="text-muted">Only lowercase letters, numbers, and hyphens. No spaces.</small>
                                @error('store_slug')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="business_email">{{ __('Business Email') }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('business_email') is-invalid @enderror required-field" 
                                       id="business_email" name="business_email" value="{{ old('business_email') }}" 
                                       placeholder="shop@example.com">
                                @error('business_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="business_phone">{{ __('Business Phone') }} <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('business_phone') is-invalid @enderror required-field" 
                                       id="business_phone" name="business_phone" value="{{ old('business_phone') }}" 
                                       placeholder="01XXXXXXXXX">
                                @error('business_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="business_address">{{ __('Business Address') }}</label>
                                <textarea class="form-control @error('business_address') is-invalid @enderror" 
                                          id="business_address" name="business_address" rows="3" 
                                          placeholder="Enter your business address">{{ old('business_address') }}</textarea>
                                @error('business_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
                    <i class="fas fa-store"></i> {{ __('Register') }}
                </button>

                <div class="text-center mt-3">
                    <small>
                        <span class="text-muted">Already have an account?</span>
                        <a href="{{ route('login') }}">{{ __('Login') }}</a>
                    </small>
                    <br>
                    <small>
                        <span class="text-muted">Want to register as customer?</span>
                        <a href="{{ route('register') }}">{{ __('Customer Registration') }}</a>
                    </small>
                </div>
            </form>
        </div>
    </div>

    <!-- OTP Verification Modal -->
    <div class="modal fade" id="otpVerificationModal" tabindex="-1" role="dialog" 
         aria-labelledby="otpVerificationModalLabel" aria-hidden="true" data-backdrop="true" data-keyboard="true">
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
        // Toggle business information sections based on chosen role
        function toggleBusinessSection() {
            const role = $('input[name="role"]:checked').val();
            if (role === 'reseller') {
                $('#business-info-section').addClass('d-none');
                $('#business-info-section').find('.required-field').prop('required', false);
                
                // Adjust layout for narrow centered view
                $('#form-left-col').removeClass('col-md-6').addClass('col-md-12');
                $('#form-right-col').addClass('d-none');
                $('.registration-form').removeClass('wide-form');
            } else {
                $('#business-info-section').removeClass('d-none');
                $('#business-info-section').find('.required-field').prop('required', true);
                
                // Adjust layout for side-by-side view
                $('#form-left-col').removeClass('col-md-12').addClass('col-md-6');
                $('#form-right-col').removeClass('d-none');
                $('.registration-form').addClass('wide-form');
            }

            if (role === 'vendor') {
                $('#vendor-type-group').removeClass('d-none');
            } else {
                $('#vendor-type-group').addClass('d-none');
            }
        }
        $('input[name="role"]').on('change', toggleBusinessSection);
        toggleBusinessSection(); // Initial trigger

        // Password generation and strength functions (same as customer registration)
        function generatePassword(length = 12) {
            if (length < 4) throw new Error("Password length should be at least 4 characters");
            
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
            const strengths = [
                { width: '0%', color: 'bg-danger', text: 'Very Weak', textColor: 'red' },
                { width: '20%', color: 'bg-danger', text: 'Weak', textColor: 'red' },
                { width: '40%', color: 'bg-warning', text: 'Moderate', textColor: 'orange' },
                { width: '60%', color: 'bg-info', text: 'Good', textColor: 'blue' },
                { width: '80%', color: 'bg-primary', text: 'Strong', textColor: 'blue' },
                { width: '100%', color: 'bg-success', text: 'Very Strong', textColor: 'green' }
            ];
            progressBar.style.width = strengths[strength].width;
            progressBar.className = `progress-bar ${strengths[strength].color}`;
            strengthText.textContent = strengths[strength].text;
            strengthText.style.color = strengths[strength].textColor;
        }

        function updatePasswordRequirements(password) {
            const requirements = {
                length: password.length >= 8,
                lowercase: /[a-z]/.test(password),
                uppercase: /[A-Z]/.test(password),
                number: /\d/.test(password),
                special: /[\W_]/.test(password)
            };

            Object.keys(requirements).forEach(key => {
                const element = document.getElementById(key);
                if (requirements[key]) {
                    element.classList.add('valid');
                    element.classList.remove('invalid');
                } else {
                    element.classList.add('invalid');
                    element.classList.remove('valid');
                }
            });
        }

        $(document).ready(function() {
            // Password toggle view logic
            $('#toggle-password').on('click', function() {
                const passwordField = $('#password');
                const passwordFieldConfirm = $('#password-confirm');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                passwordFieldConfirm.attr('type', type);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            // Password strength check trigger
            $('#password').on('input', function() {
                const password = $(this).val();
                updatePasswordStrength(password);
                updatePasswordRequirements(password);
            });

            // Generate password button trigger
            $('#generate-password').on('click', function() {
                const newPassword = generatePassword();
                $('#password').val(newPassword).trigger('input');
                $('#password-confirm').val(newPassword);
            });

            // Auto slug generation based on business name
            $('#business_name').on('input', function() {
                const businessName = $(this).val();
                const slug = businessName
                    .toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                    .replace(/\s+/g, '-') // collapse whitespace and replace by -
                    .replace(/-+/g, '-'); // collapse dashes
                $('#store_slug').val(slug).trigger('input');
            });

            // Slug availability check
            $('#store_slug').on('input', function() {
                const slug = $(this).val();
                if (!slug) return;
                
                $('#slug-url').text('{{ url('/store/') }}/' + slug);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('vendor.register.check-slug') }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'slug': slug
                    },
                    success: function(response) {
                        if (response.available) {
                            $('#store_slug').removeClass('is-invalid');
                            $('.slug-feedback').removeClass('unavailable').addClass('available').text('Store URL is available!');
                        } else {
                            $('#store_slug').addClass('is-invalid');
                            $('.slug-feedback').removeClass('available').addClass('unavailable').text('Store URL is already taken.');
                        }
                    }
                });
            });

            // Email availability check
            $('#email').on('input', function() {
                const email = $(this).val();
                if (!email) return;
                
                $.ajax({
                    type: 'POST',
                    url: '{{ route('check.email') }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'email': email
                    },
                    success: function(response) {
                        if (response.exists) {
                            $('#email').addClass('is-invalid');
                            $('#email').siblings('.email-existing').html('<strong>Email already exists.</strong>');
                        } else {
                            $('#email').removeClass('is-invalid');
                            $('#email').siblings('.email-existing').html('');
                        }
                    }
                });
            });

            // OTP Verification functionality
            let resendTimer = null;
            let resendCount = 0;
            const maxResendLimit = 5;

            // Handle form submission
            $('form[method="POST"]').not('#otpVerificationForm').on('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = $('#submitBtn');
                const originalText = submitBtn.html();
                
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Registering...');

                $.ajax({
                    url: '{{ route("partner.register.submit") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'otp_sent') {
                            $('#phoneNumberDisplay').text(formData.get('phone'));
                            $('#otpVerificationModal').modal('show');
                            startResendTimer();
                        } else if (response.status === 'success') {
                            window.location.href = response.redirect || '/vendor/dashboard';
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Registration failed. Please try again.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('\n');
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

                verifyBtn.prop('disabled', true);
                spinner.removeClass('d-none');

                $.ajax({
                    url: '{{ route("vendor.register.verify-otp") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        otp_code: otpCode
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#otpVerificationModal').modal('hide');
                            alert('Registration successful! Your account is pending approval.');
                            window.location.href = response.redirect || '/vendor/dashboard';
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

            // Handle OTP resend
            $('#resendOtpBtn').on('click', function() {
                if (resendCount >= maxResendLimit) {
                    alert('You have reached the maximum resend attempts.');
                    return;
                }

                const resendBtn = $(this);
                const spinner = resendBtn.find('.spinner-border');

                resendBtn.prop('disabled', true);
                spinner.removeClass('d-none');

                $.ajax({
                    url: '{{ route("vendor.register.resend-otp") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            alert('OTP resent successfully.');
                            resendCount++;
                            startResendTimer();
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to resend OTP. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alert(errorMessage);
                        resendBtn.prop('disabled', false);
                    },
                    complete: function() {
                        spinner.addClass('d-none');
                    }
                });
            });

            // Helper function for resend timer
            function startResendTimer() {
                let timeLeft = 60;
                $('#resendOtpBtn').prop('disabled', true);
                
                if (resendTimer) clearInterval(resendTimer);

                resendTimer = setInterval(function() {
                    if (timeLeft <= 0) {
                        clearInterval(resendTimer);
                        $('#resendOtpBtn').prop('disabled', false);
                        $('#resendTimer').text('');
                    } else {
                        $('#resendTimer').text('Resend available in ' + timeLeft + 's');
                        timeLeft--;
                    }
                }, 1000);
            }
        });
    </script>
@endsection
