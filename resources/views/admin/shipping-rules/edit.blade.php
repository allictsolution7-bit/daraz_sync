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
            <h4>Edit Shipping Rule #{{ $shippingRule->id }}</h4>
            <a href="{{ route('admin.shipping.rules.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Rules
            </a>
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

        <form method="POST" action="{{ route('admin.shipping.rules.update', $shippingRule) }}">
            @csrf
            @method('PUT')
            
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
                                    <option value="{{ $product->id }}" {{ old('ruleable_id', $shippingRule->ruleable_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->title }}
                                    </option>
                                @endforeach
                            @elseif($ruleableType === 'landing_page')
                                @foreach($landingPages as $landingPage)
                                    <option value="{{ $landingPage->id }}" {{ old('ruleable_id', $shippingRule->ruleable_id) == $landingPage->id ? 'selected' : '' }}>
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
                            <option value="override" {{ old('rule_type', $shippingRule->rule_type) === 'override' ? 'selected' : '' }}>Override (Free Shipping)</option>
                            <option value="free_shipping" {{ old('rule_type', $shippingRule->rule_type) === 'free_shipping' ? 'selected' : '' }}>Free Shipping (Threshold)</option>
                            <option value="custom_cost" {{ old('rule_type', $shippingRule->rule_type) === 'custom_cost' ? 'selected' : '' }}>Custom Cost</option>
                            {{-- <option value="percentage" {{ old('rule_type', $shippingRule->rule_type) === 'percentage' ? 'selected' : '' }}>Percentage of Order</option> --}}
                            <option value="delivery_area" {{ old('rule_type', $shippingRule->rule_type) === 'delivery_area' ? 'selected' : '' }}>Delivery Area</option>
                            {{-- <option value="conditional" {{ old('rule_type', $shippingRule->rule_type) === 'conditional' ? 'selected' : '' }}>Conditional Rules</option> --}}
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Priority</label>
                        <input type="number" name="priority" class="form-control" value="{{ old('priority', $shippingRule->priority) }}" 
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
                               step="0.01" min="0" value="{{ old('rule_value', $shippingRule->rule_value) }}">
                        <small class="form-text text-muted" id="rule_value_help"></small>
                    </div>
                    <div class="col-md-6" id="free_shipping_threshold_field" style="display: none;">
                        <label class="form-label">Free Shipping Threshold</label>
                        <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" 
                               class="form-control" step="0.01" min="0" value="{{ old('free_shipping_threshold', $shippingRule->free_shipping_threshold) }}">
                        <small class="form-text text-muted">Minimum order amount for free shipping</small>
                    </div>
                </div>

                <!-- Delivery Area Fields -->
                <div class="row mt-3" id="delivery_area_fields" style="display: none;">
                    <div class="col-md-4">
                        <label class="form-label">Delivery Area Name</label>
                        <input type="text" name="delivery_area_name" id="delivery_area_name" class="form-control" 
                               value="{{ old('delivery_area_name', $shippingRule->delivery_area_name) }}" placeholder="e.g., Inside Dhaka">
                        <small class="form-text text-muted">Display name for the delivery area</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Delivery Area Slug</label>
                        <input type="text" name="delivery_area_slug" id="delivery_area_slug" class="form-control" 
                               value="{{ old('delivery_area_slug', $shippingRule->delivery_area_slug) }}" placeholder="e.g., inside_dhaka">
                        <small class="form-text text-muted">URL-friendly identifier</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Shipping Cost</label>
                        <input type="number" name="rule_value" id="delivery_area_cost" class="form-control" 
                               step="0.01" min="0" value="{{ old('rule_value', $shippingRule->rule_value) }}" placeholder="e.g., 80">
                        <small class="form-text text-muted">Cost for this delivery area</small>
                    </div>
                </div>

                <!-- Conditions Section -->
                <div class="conditions-section" id="conditions_section">
                    <h6 class="mt-3 mb-2">Conditions</h6>
                    <div id="conditions_container">
                        @if($shippingRule->conditions)
                            @foreach($shippingRule->conditions as $index => $condition)
                                <div class="condition-row" data-condition="{{ $index }}">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select name="conditions[{{ $index }}][type]" class="form-select condition-type">
                                                <option value="min_quantity" {{ $condition['type'] === 'min_quantity' ? 'selected' : '' }}>Minimum Quantity</option>
                                                <option value="max_quantity" {{ $condition['type'] === 'max_quantity' ? 'selected' : '' }}>Maximum Quantity</option>
                                                <option value="min_amount" {{ $condition['type'] === 'min_amount' ? 'selected' : '' }}>Minimum Amount</option>
                                                <option value="max_amount" {{ $condition['type'] === 'max_amount' ? 'selected' : '' }}>Maximum Amount</option>
                                                <option value="min_weight" {{ $condition['type'] === 'min_weight' ? 'selected' : '' }}>Minimum Weight</option>
                                                <option value="max_weight" {{ $condition['type'] === 'max_weight' ? 'selected' : '' }}>Maximum Weight</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="number" name="conditions[{{ $index }}][value]" class="form-control" 
                                                   placeholder="Value" step="0.01" value="{{ $condition['value'] ?? '' }}">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-condition">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
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
                           {{ old('is_active', $shippingRule->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active (Rule will be applied)
                    </label>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Rule
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
            let conditionCount = {{ $shippingRule->conditions ? count($shippingRule->conditions) : 0 }};
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

            // Trigger change event on page load
            $('#rule_type').trigger('change');
        });
    </script>
@endsection
