@extends('layouts.master')

@section('title', 'Fraud Protection Settings')

@section('content')
<div class="container-fluid px-2">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h3 mb-1 text-gray-800">
                        <i class="fas fa-shield-alt text-primary me-2"></i>
                        Fraud Protection
                    </h1>
                    <p class="text-muted mb-0">Secure your store against fraudulent orders</p>
                </div>
                <div>
                    <a href="{{ route('admin.fraud-protection.logs') }}" class="btn btn-outline-primary">
                        <i class="fas fa-history me-1"></i> View Logs
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.fraud-protection.update') }}" method="POST">
        @csrf

        {{-- MODULE 1: DUPLICATE ORDER PROTECTION --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="p-2 me-3">
                                <i class="fas fa-copy text-white fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-white">Duplicate Order Protection</h5>
                                <small class="text-white">Prevent customers from placing multiple orders in short time</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="duplicate_protection_enabled" 
                                       name="duplicate_protection_enabled" value="1"
                                       {{ $fraudSettings->duplicate_protection_enabled ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="duplicate_protection_enabled">
                                    Enable Duplicate Protection
                                </label>
                            </div>
                            <small class="text-muted">Master switch to enable/disable all duplicate order protection features. When disabled, customers can place multiple orders without restrictions.</small>
                        </div>

                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <h6 class="mb-0">Order Interval Restriction</h6>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="order_interval_enabled" 
                                               name="order_interval_enabled" value="1"
                                               {{ $fraudSettings->order_interval_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label" for="order_interval_enabled">
                                            Enable interval restriction
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mb-3">Prevents customers from placing new orders within a specified time period after their last order.</small>
                                    <div class="mb-3">
                                        <label class="form-label">Wait Time (Minutes)</label>
                                        <input type="number" class="form-control" name="order_interval_minutes" 
                                               value="{{ $fraudSettings->order_interval_minutes }}" min="1" placeholder="60">
                                        <div class="form-text">Recommended: 60 minutes. Higher values reduce duplicate orders but may inconvenience legitimate customers.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-hourglass-half text-warning me-2"></i>
                                        <h6 class="mb-0">Pending Order Restriction</h6>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="pending_order_restriction_enabled" 
                                               name="pending_order_restriction_enabled" value="1"
                                               {{ $fraudSettings->pending_order_restriction_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label" for="pending_order_restriction_enabled">
                                            Block new orders if customer has pending orders
                                        </label>
                                    </div>
                                    <small class="text-muted">Prevents customers from placing new orders while they have unprocessed orders. Helps reduce duplicate orders and order confusion.</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label fw-semibold">Custom Message (Duplicate Order)</label>
                            <textarea class="form-control" name="duplicate_order_message" rows="2" 
                                      placeholder="আপনি সম্প্রতি একটি অর্ডার করেছেন। অনুগ্রহ করে কিছুক্ষণ পরে আবার চেষ্টা করুন।">{{ $fraudSettings->duplicate_order_message }}</textarea>
                            <div class="form-text">This message will be shown to customers when their order is blocked due to duplicate protection. Use clear, friendly language.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODULE 2: FAKE ORDER PROTECTION --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="p-2 me-3">
                                <i class="fas fa-user-secret text-white fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-white">Fake Order Protection</h5>
                                <small class="text-white">Smart detection of fake data using AI-powered pattern recognition</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="fake_protection_enabled" 
                                       name="fake_protection_enabled" value="1"
                                       {{ $fraudSettings->fake_protection_enabled ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="fake_protection_enabled">
                                    Enable Fake Order Protection
                                </label>
                            </div>
                            <small class="text-muted">Master switch for fake order detection. Uses AI-powered pattern recognition to identify and block orders with fake or suspicious data.</small>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-lg-6">
                                <div class="border rounded p-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-phone text-success me-2"></i>
                                        <h6 class="mb-0">Phone Number Validation</h6>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="phone_validation_enabled" 
                                               name="phone_validation_enabled" value="1"
                                               {{ $fraudSettings->phone_validation_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label" for="phone_validation_enabled">
                                            Enable phone validation
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mb-3">Validates phone numbers to ensure they meet standard length requirements and block obviously fake numbers.</small>
                                    <div class="mb-3">
                                        <label class="form-label">Allowed Phone Lengths</label>
                                        <div class="d-flex gap-3">
                                            @php $allowedLengths = $fraudSettings->allowed_phone_lengths ?? [11, 12, 14]; @endphp
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="length_11" 
                                                       name="allowed_phone_lengths[]" value="11"
                                                       {{ in_array(11, $allowedLengths) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="length_11">11 digits</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="length_12" 
                                                       name="allowed_phone_lengths[]" value="12"
                                                       {{ in_array(12, $allowedLengths) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="length_12">12 digits</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="length_14" 
                                                       name="allowed_phone_lengths[]" value="14"
                                                       {{ in_array(14, $allowedLengths) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="length_14">14 digits</label>
                                            </div>
                                        </div>
                                        <div class="form-text">Select valid phone number lengths for your region. Most mobile numbers are 11 digits, landlines may be 12-14 digits.</div>
                                    </div>
                                    
                                    <!-- Phone Number Whitelist -->
                                    <div class="mt-4 pt-3 border-top">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="phone_whitelist_enabled" 
                                                   name="phone_whitelist_enabled" value="1"
                                                   {{ $fraudSettings->phone_whitelist_enabled ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="phone_whitelist_enabled">
                                                Enable Phone Number Whitelist
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mb-3">Only allow specific phone number patterns. When enabled, only numbers matching the predefined patterns will be accepted.</small>
                                        
                                        <div class="alert alert-info">
                                            <h6 class="alert-heading mb-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Allowed Phone Patterns
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong>Local Format:</strong>
                                                    <ul class="mb-0 mt-1">
                                                        <li>014xxxxxxxxx</li>
                                                        <li>018xxxxxxxxx</li>
                                                        <li>013xxxxxxxxx</li>
                                                        <li>017xxxxxxxxx</li>
                                                        <li>019xxxxxxxxx</li>
                                                        <li>015xxxxxxxxx</li>
                                                        <li>016xxxxxxxxx</li>
                                                        <li>011xxxxxxxxx</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>International Format:</strong>
                                                    <ul class="mb-0 mt-1">
                                                        <li>88014xxxxxxxxx</li>
                                                        <li>88018xxxxxxxxx</li>
                                                        <li>88013xxxxxxxxx</li>
                                                        <li>88017xxxxxxxxx</li>
                                                        <li>88019xxxxxxxxx</li>
                                                        <li>88015xxxxxxxxx</li>
                                                        <li>88016xxxxxxxxx</li>
                                                        <li>88011xxxxxxxxx</li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Plus Format:</strong>
                                                    <ul class="mb-0 mt-1">
                                                        <li>+88014xxxxxxxxx</li>
                                                        <li>+88018xxxxxxxxx</li>
                                                        <li>+88013xxxxxxxxx</li>
                                                        <li>+88017xxxxxxxxx</li>
                                                        <li>+88019xxxxxxxxx</li>
                                                        <li>+88015xxxxxxxxx</li>
                                                        <li>+88016xxxxxxxxx</li>
                                                        <li>+88011xxxxxxxxx</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="border rounded p-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-id-badge text-info me-2"></i>
                                        <h6 class="mb-0">Name Validation</h6>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="name_validation_enabled" 
                                               name="name_validation_enabled" value="1"
                                               {{ $fraudSettings->name_validation_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label" for="name_validation_enabled">
                                            Enable name validation
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mb-3">Validates customer names to ensure they appear legitimate and not fake or gibberish.</small>
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <label class="form-label">Min Length</label>
                                            <input type="number" class="form-control" name="name_min_length" 
                                                   value="{{ $fraudSettings->name_min_length }}" min="1">
                                            <div class="form-text">Minimum characters required</div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Max Length</label>
                                            <input type="number" class="form-control" name="name_max_length" 
                                                   value="{{ $fraudSettings->name_max_length }}" min="1">
                                            <div class="form-text">Maximum characters allowed</div>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="name_disallow_numeric" 
                                               name="name_disallow_numeric" value="1"
                                               {{ $fraudSettings->name_disallow_numeric ? 'checked' : '' }}>
                                        <label class="form-check-label" for="name_disallow_numeric">
                                            Disallow numbers in name
                                        </label>
                                    </div>
                                    <small class="text-muted">Blocks names that contain only numbers, which are typically fake entries.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-lg-4">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-ban text-danger me-2"></i>
                                        <h6 class="mb-0">Block Sequential Numbers</h6>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="block_sequential_numbers" 
                                               name="block_sequential_numbers" value="1"
                                               {{ $fraudSettings->block_sequential_numbers ? 'checked' : '' }}>
                                        <label class="form-check-label" for="block_sequential_numbers">
                                            Enable blocking
                                        </label>
                                    </div>
                                    <small class="text-muted">Blocks phone numbers with repeated digits like 0000000000, 1111111111, etc. These are typically fake numbers.</small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-user-slash text-warning me-2"></i>
                                        <h6 class="mb-0">Block Repeated Names</h6>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="block_repeated_names" 
                                               name="block_repeated_names" value="1"
                                               {{ $fraudSettings->block_repeated_names ? 'checked' : '' }}>
                                        <label class="form-check-label" for="block_repeated_names">
                                            Enable blocking
                                        </label>
                                    </div>
                                    <small class="text-muted">Blocks names with repeated characters like aaaaa, bbbbb, etc. These are typically fake entries.</small>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-keyboard text-secondary me-2"></i>
                                        <h6 class="mb-0">Block Gibberish Names</h6>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="block_gibberish_names" 
                                               name="block_gibberish_names" value="1"
                                               {{ $fraudSettings->block_gibberish_names ? 'checked' : '' }}>
                                        <label class="form-check-label" for="block_gibberish_names">
                                            Enable blocking
                                        </label>
                                    </div>
                                    <small class="text-muted">Blocks keyboard patterns like asdfgh, qwerty, etc. These are typically fake or test entries.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-lg-6">
                                <div class="border rounded p-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-code text-dark me-2"></i>
                                        <h6 class="mb-0">Custom Pattern Blacklist</h6>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="custom_pattern_enabled" 
                                               name="custom_pattern_enabled" value="1"
                                               {{ $fraudSettings->custom_pattern_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label" for="custom_pattern_enabled">
                                            Enable custom patterns
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mb-3">Allows you to define custom regex patterns to block specific phone number formats or name patterns.</small>
                                    <div class="mb-3">
                                        <label class="form-label">Blocked Phone Patterns</label>
                                        <textarea class="form-control" name="blocked_phone_patterns_text" rows="3" 
                                                  placeholder="^0170.*&#10;^0180.*">{{ $fraudSettings->blocked_phone_patterns ? implode("\n", $fraudSettings->blocked_phone_patterns) : '' }}</textarea>
                                        <div class="form-text">Regex patterns, one per line. Example: ^0170.* blocks all numbers starting with 0170</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="border rounded p-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-map-marker-alt text-success me-2"></i>
                                        <h6 class="mb-0">Address Validation</h6>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="address_validation_enabled" 
                                               name="address_validation_enabled" value="1"
                                               {{ $fraudSettings->address_validation_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label" for="address_validation_enabled">
                                            Enable address validation
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mb-3">Validates delivery addresses to ensure they are complete and legitimate, not just random text.</small>
                                    <div class="mb-3">
                                        <label class="form-label">Blocked Name Patterns</label>
                                        <textarea class="form-control" name="blocked_name_patterns_text" rows="3" 
                                                  placeholder="test&#10;demo&#10;fake">{{ $fraudSettings->blocked_name_patterns ? implode("\n", $fraudSettings->blocked_name_patterns) : '' }}</textarea>
                                        <div class="form-text">Regex patterns, one per line. Example: test|demo|fake blocks names containing these words</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Blocked Address Patterns</label>
                                        <textarea class="form-control" name="blocked_address_patterns_text" rows="3" 
                                                  placeholder="test address&#10;fake location&#10;demo place">{{ $fraudSettings->blocked_address_patterns ? implode("\n", $fraudSettings->blocked_address_patterns) : '' }}</textarea>
                                        <div class="form-text">Regex patterns, one per line. Example: test|demo|fake blocks addresses containing these words</div>
                                    </div>
                                    <div>
                                        <label class="form-label">Minimum Address Length</label>
                                        <input type="number" class="form-control" name="address_min_length" 
                                               value="{{ $fraudSettings->address_min_length }}" min="1">
                                        <div class="form-text">Minimum characters required for a valid address. Helps filter out incomplete addresses.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Custom Message (Fake Data)</label>
                            <textarea class="form-control" name="fake_data_message" rows="2" 
                                      placeholder="অবৈধ তথ্য সনাক্ত করা হয়েছে। অনুগ্রহ করে সঠিক তথ্য প্রদান করুন।">{{ $fraudSettings->fake_data_message }}</textarea>
                            <div class="form-text">This message will be shown to customers when their order is blocked due to fake data detection. Be polite and helpful.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODULE 3: FRAUD & SCAM PROTECTION --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-danger border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="p-2 me-3">
                                <i class="fas fa-exclamation-triangle text-white fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-white">Fraud & Scam Protection</h5>
                                <small class="text-white">Advanced fraud prevention with blacklisting and courier history analysis</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="fraud_protection_enabled" 
                                       name="fraud_protection_enabled" value="1"
                                       {{ $fraudSettings->fraud_protection_enabled ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="fraud_protection_enabled">
                                    Enable Fraud & Scam Protection
                                </label>
                            </div>
                            <small class="text-muted">Advanced fraud prevention using blacklists, IP rate limiting, and courier delivery history analysis to block suspicious orders.</small>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-lg-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-ban text-danger me-2"></i>
                                        <h6 class="mb-0">Blacklist Management</h6>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="blacklist_enabled" 
                                               name="blacklist_enabled" value="1"
                                               {{ $fraudSettings->blacklist_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label" for="blacklist_enabled">
                                            Enable blacklist
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mb-3">Permanently blocks specific phone numbers and IP addresses that have been identified as fraudulent or problematic.</small>
                                    <div class="mb-3">
                                        <label class="form-label">Blacklisted Phone Numbers</label>
                                        <textarea class="form-control" name="blacklisted_phones_text" rows="4" 
                                                  placeholder="01700000000&#10;01711111111">{{ $fraudSettings->blacklisted_phones ? implode("\n", $fraudSettings->blacklisted_phones) : '' }}</textarea>
                                        <div class="form-text">Current: {{ count($fraudSettings->blacklisted_phones ?? []) }} phones. One phone number per line, include country code if needed.</div>
                                    </div>
                                    <div>
                                        <label class="form-label">Blacklisted IPs</label>
                                        <textarea class="form-control" name="blacklisted_ips_text" rows="3" 
                                                  placeholder="192.168.1.1&#10;10.0.0.1">{{ $fraudSettings->blacklisted_ips ? implode("\n", $fraudSettings->blacklisted_ips) : '' }}</textarea>
                                        <div class="form-text">Current: {{ count($fraudSettings->blacklisted_ips ?? []) }} IPs. One IP address per line. Use with caution as it may block legitimate users.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-tachometer-alt text-info me-2"></i>
                                        <h6 class="mb-0">Rate Limiting & Analysis</h6>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="ip_rate_limiting_enabled" 
                                               name="ip_rate_limiting_enabled" value="1"
                                               {{ $fraudSettings->ip_rate_limiting_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label" for="ip_rate_limiting_enabled">
                                            Enable IP rate limiting
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mb-3">Limits the number of orders that can be placed from the same IP address within a time period to prevent bulk fake orders.</small>
                                    <div class="mb-3">
                                        <label class="form-label">Max Orders Per IP Per Hour</label>
                                        <input type="number" class="form-control" name="ip_max_orders_per_hour" 
                                               value="{{ $fraudSettings->ip_max_orders_per_hour }}" min="1" placeholder="5">
                                        <div class="form-text">Recommended: 5 orders. Higher values allow more orders but may not catch bulk fraud attempts.</div>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="courier_success_check_enabled" 
                                               name="courier_success_check_enabled" value="1"
                                               {{ $fraudSettings->courier_success_check_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label" for="courier_success_check_enabled">
                                            Enable courier success rate check
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mb-3">Analyzes customer delivery history to block customers with poor delivery success rates or high bad history.</small>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label">Min Success Rate (%)</label>
                                            <input type="number" class="form-control" name="min_success_rate" 
                                                   value="{{ $fraudSettings->min_success_rate }}" 
                                                   min="0" max="100" step="0.01" placeholder="20.00">
                                            <div class="form-text">Block if success rate below this</div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label">Max Bad Rate (%)</label>
                                            <input type="number" class="form-control" name="max_bad_history_rate" 
                                                   value="{{ $fraudSettings->max_bad_history_rate }}" 
                                                   min="0" max="100" step="0.01" placeholder="80.00">
                                            <div class="form-text">Block if bad history exceeds this</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">Custom Message (Fraud Detected)</label>
                                <textarea class="form-control" name="fraud_detected_message" rows="2" 
                                          placeholder="নিরাপত্তা কারণে আপনার অর্ডার ব্লক করা হয়েছে। সহায়তার জন্য আমাদের সাথে যোগাযোগ করুন।">{{ $fraudSettings->fraud_detected_message }}</textarea>
                                <div class="form-text">Message shown when fraud is detected through rate limiting or courier analysis.</div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label fw-semibold">Custom Message (Blacklist)</label>
                                <textarea class="form-control" name="blacklist_message" rows="2" 
                                          placeholder="আপনার অ্যাকাউন্ট সাময়িকভাবে স্থগিত করা হয়েছে। সহায়তার জন্য আমাদের সাথে যোগাযোগ করুন।">{{ $fraudSettings->blacklist_message }}</textarea>
                                <div class="form-text">Message shown when customer is on blacklist (phone or IP).</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ALERT & NOTIFICATIONS --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="p-2 me-3">
                                <i class="fas fa-bell text-white fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-white">Alert & Notifications</h5>
                                <small class="text-white">Configure logging and admin notifications</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-file-alt text-primary me-2"></i>
                                        <h6 class="mb-0">Logging</h6>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="log_blocked_attempts" 
                                               name="log_blocked_attempts" value="1"
                                               {{ $fraudSettings->log_blocked_attempts ? 'checked' : '' }}>
                                        <label class="form-check-label" for="log_blocked_attempts">
                                            Log blocked attempts
                                        </label>
                                    </div>
                                    <small class="text-muted">Save all blocked attempts to database and log files for analysis and monitoring. Recommended for security auditing.</small>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-envelope text-success me-2"></i>
                                        <h6 class="mb-0">Email Alerts</h6>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="send_admin_alerts" 
                                               name="send_admin_alerts" value="1"
                                               {{ $fraudSettings->send_admin_alerts ? 'checked' : '' }}>
                                        <label class="form-check-label" for="send_admin_alerts">
                                            Send admin email alerts
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mb-3">Receive email notifications when orders are blocked, helping you monitor fraud attempts in real-time.</small>
                                    <div>
                                        <label class="form-label">Admin Alert Email</label>
                                        <input type="email" class="form-control" name="admin_alert_email" 
                                               value="{{ $fraudSettings->admin_alert_email }}" 
                                               placeholder="admin@example.com">
                                        <div class="form-text">Email address to receive fraud protection alerts and notifications.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-start">
                    <button type="submit" class="btn btn-primary btn-lg px-4 mb-3">
                        <i class="fas fa-save me-2"></i>Save Settings
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .form-check-input {
        position: relative;
        top: 4px;
    }
    .card {
        border: none;
        border-radius: 12px;
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .bg-gradient {
        background: linear-gradient(45deg, var(--bs-primary), var(--bs-primary));
    }
    
    .border {
        border-color: #e3e6f0 !important;
    }
    
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    .form-check-input:checked {
        background-color: #4e73df;
        border-color: #4e73df;
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #4e73df, #36b9cc);
        border: none;
        border-radius: 8px;
        font-weight: 600;
    }
    
    .btn-primary:hover {
        background: linear-gradient(45deg, #375a7f, #2c9faf);
        transform: translateY(-1px);
    }
    
    .text-gray-800 {
        color: #5a5c69 !important;
    }
    
    .shadow-sm {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    }
</style>
@endsection

