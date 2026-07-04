@extends('layouts.master')

@section('styles')
    <style>
        .form-section {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
        }
        .rule-type-info {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.25rem;
        }
        .conditions-section {
            display: none;
        }
        .condition-row {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Create Shipping Rule</h4>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-lightning"></i> Quick Templates
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-template="delivery_area_dhaka">Delivery Area - Dhaka</a></li>
                        <li><a class="dropdown-item" href="#" data-template="free_shipping_threshold">Free Shipping Threshold</a></li>
                        <li><a class="dropdown-item" href="#" data-template="custom_cost">Custom Fixed Cost</a></li>
                        <li><a class="dropdown-item" href="#" data-template="percentage">Percentage Based</a></li>
                    </ul>
                </div>
                <a href="{{ route('admin.shipping.rules.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Rules
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.shipping.rules.store') }}">
            @csrf
            
            <!-- Rule Target Section -->
            <div class="form-section">
                <h5 class="mb-3">Rule Target</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Apply to</label>
                        <select name="ruleable_type" id="ruleable_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="product" {{ old('ruleable_type', $ruleableType) === 'product' ? 'selected' : '' }}>Product</option>
                            <option value="landing_page" {{ old('ruleable_type', $ruleableType) === 'landing_page' ? 'selected' : '' }}>Landing Page</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Select Item</label>
                        <select name="ruleable_id" id="ruleable_id" class="form-select" required>
                            <option value="">Select an item</option>
                            @if($ruleableType === 'product')
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('ruleable_id', $ruleableId) == $product->id ? 'selected' : '' }}>
                                        {{ $product->title }}
                                    </option>
                                @endforeach
                            @elseif($ruleableType === 'landing_page')
                                @foreach($landingPages as $landingPage)
                                    <option value="{{ $landingPage->id }}" {{ old('ruleable_id', $ruleableId) == $landingPage->id ? 'selected' : '' }}>
                                        {{ $landingPage->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <!-- Rule Configuration Section -->
            <div class="form-section">
                <h5 class="mb-3">Rule Configuration</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Rule Type</label>
                        <select name="rule_type" id="rule_type" class="form-select" required>
                            <option value="">Select Rule Type</option>
                            <option value="override" {{ old('rule_type') === 'override' ? 'selected' : '' }}>Override (Free Shipping)</option>
                            <option value="free_shipping" {{ old('rule_type') === 'free_shipping' ? 'selected' : '' }}>Free Shipping (Threshold)</option>
                            <option value="custom_cost" {{ old('rule_type') === 'custom_cost' ? 'selected' : '' }}>Custom Cost</option>
                            {{-- <option value="percentage" {{ old('rule_type') === 'percentage' ? 'selected' : '' }}>Percentage of Order</option> --}}
                            <option value="delivery_area" {{ old('rule_type') === 'delivery_area' ? 'selected' : '' }}>Delivery Area</option>
                            {{-- <option value="conditional" {{ old('rule_type') === 'conditional' ? 'selected' : '' }}>Conditional Rules</option> --}}
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Priority</label>
                        <input type="number" name="priority" class="form-control" value="{{ old('priority', 0) }}" 
                               min="0" max="100" required>
                        <small class="form-text text-muted">Higher numbers have higher priority (0-100)</small>
                    </div>
                </div>

                <!-- Rule Type Info -->
                <div class="rule-type-info" id="rule_type_info" style="display: none;">
                    <strong>Rule Type Information:</strong>
                    <div id="rule_type_description"></div>
                </div>

                <!-- Rule Value Fields -->
                <div class="row mt-3">
                    <div class="col-md-6" id="rule_value_field" style="display: none;">
                        <label class="form-label">Rule Value</label>
                        <input type="number" name="rule_value" id="rule_value" class="form-control" 
                               step="0.01" min="0" value="{{ old('rule_value') }}">
                        <small class="form-text text-muted" id="rule_value_help"></small>
                    </div>
                    <div class="col-md-6" id="free_shipping_threshold_field" style="display: none;">
                        <label class="form-label">Free Shipping Threshold</label>
                        <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" 
                               class="form-control" step="0.01" min="0" value="{{ old('free_shipping_threshold') }}">
                        <small class="form-text text-muted">Minimum order amount for free shipping</small>
                    </div>
                </div>

                <!-- Delivery Area Fields -->
                <div class="row mt-3" id="delivery_area_fields" style="display: none;">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6>Delivery Areas</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-delivery-area">
                                <i class="bi bi-plus-circle"></i> Add Delivery Area
                            </button>
                        </div>
                        
                        <div id="delivery-areas-container">
                            <!-- Delivery areas will be added here dynamically -->
                        </div>
                    </div>
                </div>

                <!-- Conditions Section -->
                <div class="conditions-section" id="conditions_section">
                    <h6 class="mt-3 mb-2">Conditions</h6>
                    <div id="conditions_container">
                        <!-- Conditions will be added dynamically -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add_condition">
                        <i class="bi bi-plus"></i> Add Condition
                    </button>
                </div>
            </div>

            <!-- Status Section -->
            <div class="form-section">
                <h5 class="mb-3">Status</h5>
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" 
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active (Rule will be applied)
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Create Rule
                </button>
                <a href="{{ route('admin.shipping.rules.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Handle ruleable type change
            $('#ruleable_type').change(function() {
                const type = $(this).val();
                const $select = $('#ruleable_id');
                
                $select.empty().append('<option value="">Select an item</option>');
                
                if (type === 'product') {
                    @foreach($products as $product)
                        $select.append('<option value="{{ $product->id }}">{{ $product->title }}</option>');
                    @endforeach
                } else if (type === 'landing_page') {
                    @foreach($landingPages as $landingPage)
                        $select.append('<option value="{{ $landingPage->id }}">{{ $landingPage->title }}</option>');
                    @endforeach
                }
            });

            // Handle rule type change
            $('#rule_type').change(function() {
                const ruleType = $(this).val();
                const $info = $('#rule_type_info');
                const $description = $('#rule_type_description');
                const $ruleValueField = $('#rule_value_field');
                const $ruleValue = $('#rule_value');
                const $ruleValueHelp = $('#rule_value_help');
                const $thresholdField = $('#free_shipping_threshold_field');
                const $deliveryAreaFields = $('#delivery_area_fields');
                const $conditionsSection = $('#conditions_section');

                // Hide all fields first
                $info.hide();
                $ruleValueField.hide();
                $thresholdField.hide();
                $deliveryAreaFields.hide();
                $conditionsSection.hide();

                if (ruleType) {
                    $info.show();
                    
                    switch(ruleType) {
                        case 'override':
                            $description.html('This rule will make shipping completely free for this item.');
                            break;
                        case 'free_shipping':
                            $description.html('This rule will provide free shipping when the order amount meets the threshold. If threshold is not met, the fallback cost will be applied.');
                            $thresholdField.show();
                            $ruleValueField.show();
                            $ruleValueHelp.text('Enter the fallback shipping cost when threshold is not met (in ৳)');
                            break;
                        case 'custom_cost':
                            $description.html('This rule will set a fixed shipping cost for this item.');
                            $ruleValueField.show();
                            $ruleValueHelp.text('Enter the fixed shipping cost in ৳');
                            break;
                        case 'percentage':
                            $description.html('This rule will calculate shipping as a percentage of the order total.');
                            $ruleValueField.show();
                            $ruleValueHelp.text('Enter the percentage (e.g., 5 for 5%)');
                            break;
                        case 'delivery_area':
                            $description.html('This rule will set different shipping costs based on delivery area (e.g., Inside Dhaka, Outside Dhaka).');
                            $deliveryAreaFields.show();
                            break;
                        case 'conditional':
                            $description.html('This rule will apply based on specific conditions.');
                            $conditionsSection.show();
                            break;
                    }
                }
            });

            // Add condition functionality
            let conditionCount = 0;
            $('#add_condition').click(function() {
                const conditionHtml = `
                    <div class="condition-row" data-condition="${conditionCount}">
                        <div class="row">
                            <div class="col-md-4">
                                <select name="conditions[${conditionCount}][type]" class="form-select condition-type">
                                    <option value="min_quantity">Minimum Quantity</option>
                                    <option value="max_quantity">Maximum Quantity</option>
                                    <option value="min_amount">Minimum Amount</option>
                                    <option value="max_amount">Maximum Amount</option>
                                    <option value="min_weight">Minimum Weight</option>
                                    <option value="max_weight">Maximum Weight</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="conditions[${conditionCount}][value]" class="form-control" placeholder="Value" step="0.01">
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-condition">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#conditions_container').append(conditionHtml);
                conditionCount++;
            });

            // Remove condition
            $(document).on('click', '.remove-condition', function() {
                $(this).closest('.condition-row').remove();
            });

            // Trigger change event on page load if values exist
            $('#rule_type').trigger('change');

            // Quick templates functionality
            $('[data-template]').click(function(e) {
                e.preventDefault();
                const template = $(this).data('template');
                
                switch(template) {
                    case 'delivery_area_dhaka':
                        $('#rule_type').val('delivery_area').trigger('change');
                        $('#delivery_area_name').val('ঢাকার ভিতরে');
                        $('#delivery_area_slug').val('inside_dhaka');
                        $('#delivery_area_cost').val('80');
                        break;
                    case 'free_shipping_threshold':
                        $('#rule_type').val('free_shipping').trigger('change');
                        $('#free_shipping_threshold').val('500');
                        break;
                    case 'custom_cost':
                        $('#rule_type').val('custom_cost').trigger('change');
                        $('#rule_value').val('100');
                        break;
                    case 'percentage':
                        $('#rule_type').val('percentage').trigger('change');
                        $('#rule_value').val('5');
                        break;
                }
            });

            // Delivery area management
            let deliveryAreaCount = 0;

            // Add delivery area
            $('#add-delivery-area').click(function() {
                addDeliveryArea();
            });

            function addDeliveryArea(name = '', slug = '', cost = '') {
                const index = deliveryAreaCount++;
                const deliveryAreaHtml = `
                    <div class="delivery-area-item card mb-3 p-3" data-index="${index}">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-1">
                                <div class="d-flex flex-column gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-secondary move-area-up" 
                                            title="Move Up">
                                        <i class="bi bi-chevron-up"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary move-area-down" 
                                            title="Move Down">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Delivery Area Name</label>
                                <input type="text" name="delivery_areas[${index}][name]" class="form-control delivery-area-name" 
                                       value="${name}" placeholder="e.g., Inside Dhaka" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Delivery Area Slug</label>
                                <input type="text" name="delivery_areas[${index}][slug]" class="form-control delivery-area-slug" 
                                       value="${slug}" placeholder="e.g., inside_dhaka" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Shipping Cost</label>
                                <input type="number" name="delivery_areas[${index}][cost]" class="form-control delivery-area-cost" 
                                       step="0.01" min="0" value="${cost}" placeholder="e.g., 80" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-danger btn-sm remove-delivery-area">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#delivery-areas-container').append(deliveryAreaHtml);
                updateDeliveryAreaPositions();
            }

            // Remove delivery area
            $(document).on('click', '.remove-delivery-area', function() {
                $(this).closest('.delivery-area-item').remove();
                updateDeliveryAreaPositions();
            });

            // Move delivery area up
            $(document).on('click', '.move-area-up', function() {
                const $item = $(this).closest('.delivery-area-item');
                const $prev = $item.prev('.delivery-area-item');
                if ($prev.length) {
                    $item.insertBefore($prev);
                    updateDeliveryAreaPositions();
                }
            });

            // Move delivery area down
            $(document).on('click', '.move-area-down', function() {
                const $item = $(this).closest('.delivery-area-item');
                const $next = $item.next('.delivery-area-item');
                if ($next.length) {
                    $item.insertAfter($next);
                    updateDeliveryAreaPositions();
                }
            });

            // Update delivery area positions
            function updateDeliveryAreaPositions() {
                $('.delivery-area-item').each(function(index) {
                    $(this).find('input[name*="[name]"]').attr('name', `delivery_areas[${index}][name]`);
                    $(this).find('input[name*="[slug]"]').attr('name', `delivery_areas[${index}][slug]`);
                    $(this).find('input[name*="[cost]"]').attr('name', `delivery_areas[${index}][cost]`);
                    $(this).attr('data-index', index);
                });
            }

            // Auto-generate slug from name
            $(document).on('input', '.delivery-area-name', function() {
                const $item = $(this).closest('.delivery-area-item');
                const $slugInput = $item.find('.delivery-area-slug');
                const name = $(this).val();
                const slug = name.toLowerCase()
                    .replace(/[^a-z0-9\s-_]/g, '')
                    .trim()
                    .replace(/\s+/g, '_')
                    .replace(/-+/g, '_');
                $slugInput.val(slug);
            });
        });
    </script>
@endsection
