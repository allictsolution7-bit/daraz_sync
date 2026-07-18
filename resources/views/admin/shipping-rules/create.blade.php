@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .shipping-create-container {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            padding: 1.5rem;
            border-radius: 1rem;
        }
        .page-header-premium {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            padding: 2rem;
            border-radius: 1rem;
            color: white;
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.15);
            margin-bottom: 2rem;
        }
        .form-section-premium {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        .form-section-title {
            font-weight: 700;
            color: #1e293b;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .rule-type-info {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 1rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
            color: #1e40af;
            font-size: 0.9rem;
        }
        .form-select, .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .form-select:focus, .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .btn-premium {
            padding: 0.6rem 1.2rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-premium-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        .btn-premium-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            transform: translateY(-1px);
            color: white;
        }
        .btn-premium-secondary {
            background-color: #f1f5f9;
            border-color: #e2e8f0;
            color: #475569;
        }
        .btn-premium-secondary:hover {
            background-color: #e2e8f0;
            color: #334155;
            transform: translateY(-1px);
        }
        .quick-template-btn {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            color: #475569;
            transition: all 0.2s;
            text-decoration: none;
        }
        .quick-template-btn:hover {
            background-color: #eff6ff;
            border-color: #bfdbfe;
            color: #2563eb;
        }
        .delivery-area-item {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            background-color: #f8fafc;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.2s;
        }
        .delivery-area-item:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .condition-row {
            background-color: #f8fafc;
            padding: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
        }
    </style>
@endsection

@section('content')
    <div class="shipping-create-container">
        <!-- Header Section -->
        <div class="page-header-premium shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 font-weight-bold text-white">Create Shipping Rule</h3>
                    <p class="mb-0 text-white-50">Create customized shipping settings targeting specific products or pages.</p>
                </div>
                <div>
                    <a href="{{ route('admin.shipping.rules.index') }}" class="btn btn-light btn-premium text-primary">
                        <i class="fas fa-arrow-left"></i> Back to Rules
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-left: 4px solid #ef4444; border-radius: 0.5rem;">
                <ul class="mb-0 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Quick Templates -->
        <div class="mb-4 d-flex flex-wrap gap-2 align-items-center">
            <span class="text-muted small font-weight-medium mr-2">Quick Templates:</span>
            <a href="#" class="quick-template-btn" data-template="delivery_area_dhaka">
                <i class="fas fa-map-marker-alt text-danger"></i> Dhaka Delivery Zone
            </a>
            <a href="#" class="quick-template-btn" data-template="free_shipping_threshold">
                <i class="fas fa-gift text-primary"></i> Free Shipping > ৳500
            </a>
            <a href="#" class="quick-template-btn" data-template="custom_cost">
                <i class="fas fa-money-bill-wave text-success"></i> Fixed ৳100 Cost
            </a>
        </div>

        <form method="POST" action="{{ route('admin.shipping.rules.store') }}">
            @csrf
            
            <!-- Rule Target Section -->
            <div class="form-section-premium">
                <div class="form-section-title">
                    <i class="fas fa-bullseye text-primary"></i> Target Selection
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small font-weight-medium">Apply To</label>
                        <select name="ruleable_type" id="ruleable_type" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="product" {{ old('ruleable_type', $ruleableType) === 'product' ? 'selected' : '' }}>Product</option>
                            <option value="landing_page" {{ old('ruleable_type', $ruleableType) === 'landing_page' ? 'selected' : '' }}>Landing Page</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small font-weight-medium">Select Item</label>
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
            <div class="form-section-premium">
                <div class="form-section-title">
                    <i class="fas fa-cog text-primary"></i> Rule Parameters
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted small font-weight-medium">Rule Type</label>
                        <select name="rule_type" id="rule_type" class="form-select" required>
                            <option value="">Select Rule Type</option>
                            <option value="override" {{ old('rule_type') === 'override' ? 'selected' : '' }}>Override (Free Shipping)</option>
                            <option value="free_shipping" {{ old('rule_type') === 'free_shipping' ? 'selected' : '' }}>Free Shipping (Threshold)</option>
                            <option value="custom_cost" {{ old('rule_type') === 'custom_cost' ? 'selected' : '' }}>Custom Cost</option>
                            <option value="delivery_area" {{ old('rule_type') === 'delivery_area' ? 'selected' : '' }}>Delivery Area</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small font-weight-medium">Priority (0-100)</label>
                        <input type="number" name="priority" class="form-control" value="{{ old('priority', 0) }}" min="0" max="100" required>
                        <small class="form-text text-muted">Rules with higher priority numbers are applied first.</small>
                    </div>
                </div>

                <!-- Rule Type Info Banner -->
                <div class="rule-type-info shadow-sm" id="rule_type_info" style="display: none;">
                    <strong><i class="fas fa-info-circle"></i> Rule Info:</strong>
                    <span id="rule_type_description" class="ml-1"></span>
                </div>

                <!-- Dynamic Rule Value Fields -->
                <div class="row g-3 mt-1">
                    <div class="col-md-6" id="rule_value_field" style="display: none;">
                        <label class="form-label text-muted small font-weight-medium">Rule Value (৳ / %)</label>
                        <input type="number" name="rule_value" id="rule_value" class="form-control" step="0.01" min="0" value="{{ old('rule_value') }}">
                        <small class="form-text text-muted" id="rule_value_help"></small>
                    </div>
                    <div class="col-md-6" id="free_shipping_threshold_field" style="display: none;">
                        <label class="form-label text-muted small font-weight-medium">Free Shipping Threshold (৳)</label>
                        <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" class="form-control" step="0.01" min="0" value="{{ old('free_shipping_threshold') }}">
                        <small class="form-text text-muted">Minimum checkout amount for free shipping.</small>
                    </div>
                </div>

                <!-- Delivery Area Dynamic Fields -->
                <div class="mt-4" id="delivery_area_fields" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-map-marked-alt text-primary"></i> Multi-Area Shipping Rates</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-premium" id="add-delivery-area">
                            <i class="fas fa-plus"></i> Add Area Zone
                        </button>
                    </div>
                    <div id="delivery-areas-container">
                        <!-- Added dynamically -->
                    </div>
                </div>

                <!-- Conditions Section -->
                <div class="mt-4" id="conditions_section" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-sliders-h text-primary"></i> Condition Settings</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-premium" id="add_condition">
                            <i class="fas fa-plus"></i> Add Condition
                        </button>
                    </div>
                    <div id="conditions_container">
                        <!-- Dynamic conditions -->
                    </div>
                </div>
            </div>

            <!-- Status Section -->
            <div class="form-section-premium">
                <div class="form-section-title">
                    <i class="fas fa-toggle-on text-primary"></i> Rule Status
                </div>
                <div class="form-check form-switch" style="padding-left: 3.5rem;">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', true) ? 'checked' : '' }} style="cursor: pointer; transform: scale(1.2); margin-left: -2.5rem; float: left;">
                    <label class="form-check-label font-weight-medium text-dark" for="is_active" style="cursor: pointer; user-select: none;">
                        Activate this rule immediately
                    </label>
                    <small class="form-text text-muted d-block mt-1">If deactivated, this rule won't affect checkout calculations.</small>
                </div>
            </div>

            <!-- Submission -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-premium btn-premium-primary">
                    <i class="fas fa-check-circle"></i> Create Shipping Rule
                </button>
                <a href="{{ route('admin.shipping.rules.index') }}" class="btn btn-premium btn-premium-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                            $description.html('This rule makes shipping completely free for the target product/landing page.');
                            break;
                        case 'free_shipping':
                            $description.html('Offers free shipping if checkout total exceeds threshold. Fallback cost applies otherwise.');
                            $thresholdField.show();
                            $ruleValueField.show();
                            $ruleValueHelp.text('Fallback shipping cost in ৳ (when total is below threshold)');
                            break;
                        case 'custom_cost':
                            $description.html('Sets a custom, fixed flat-rate shipping fee for this item.');
                            $ruleValueField.show();
                            $ruleValueHelp.text('Fixed shipping fee amount in ৳');
                            break;
                        case 'percentage':
                            $description.html('Calculates shipping cost dynamically as a percentage of the total order value.');
                            $ruleValueField.show();
                            $ruleValueHelp.text('Rate percentage (e.g., 5 represents 5%)');
                            break;
                        case 'delivery_area':
                            $description.html('Configure dynamic shipping rates based on delivery locations (e.g. Inside/Outside Dhaka).');
                            $deliveryAreaFields.show();
                            break;
                        case 'conditional':
                            $description.html('Applies custom rates strictly when checkout rules or quantities match set criteria.');
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
                        <div class="row g-2 align-items-center">
                            <div class="col-md-5">
                                <select name="conditions[${conditionCount}][type]" class="form-select condition-type">
                                    <option value="min_quantity">Minimum Quantity</option>
                                    <option value="max_quantity">Maximum Quantity</option>
                                    <option value="min_amount">Minimum Amount</option>
                                    <option value="max_amount">Maximum Amount</option>
                                    <option value="min_weight">Minimum Weight</option>
                                    <option value="max_weight">Maximum Weight</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <input type="number" name="conditions[${conditionCount}][value]" class="form-control" placeholder="Condition Value" step="0.01">
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-condition action-btn">
                                    <i class="fas fa-trash-alt"></i>
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
                        $('#delivery-areas-container').empty();
                        addDeliveryArea('ঢাকার ভিতরে (Inside Dhaka)', 'inside_dhaka', '80');
                        addDeliveryArea('ঢাকার বাইরে (Outside Dhaka)', 'outside_dhaka', '150');
                        break;
                    case 'free_shipping_threshold':
                        $('#rule_type').val('free_shipping').trigger('change');
                        $('#free_shipping_threshold').val('500');
                        $('#rule_value').val('0');
                        break;
                    case 'custom_cost':
                        $('#rule_type').val('custom_cost').trigger('change');
                        $('#rule_value').val('100');
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
                    <div class="delivery-area-item shadow-sm" data-index="${index}">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-1">
                                <div class="d-flex flex-column gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-secondary move-area-up p-1" title="Move Up">
                                        <i class="fas fa-chevron-up"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary move-area-down p-1" title="Move Down">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small font-weight-medium">Area Zone Name</label>
                                <input type="text" name="delivery_areas[${index}][name]" class="form-control delivery-area-name" 
                                       value="${name}" placeholder="e.g., Inside Dhaka" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small font-weight-medium">Slug (Auto-generated)</label>
                                <input type="text" name="delivery_areas[${index}][slug]" class="form-control delivery-area-slug" 
                                       value="${slug}" placeholder="e.g., inside_dhaka" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-muted small font-weight-medium">Shipping Cost (৳)</label>
                                <input type="number" name="delivery_areas[${index}][cost]" class="form-control delivery-area-cost" 
                                       step="0.01" min="0" value="${cost}" placeholder="e.g., 80" required>
                            </div>
                            <div class="col-md-2 text-end">
                                <label class="form-label d-block">&nbsp;</label>
                                <button type="button" class="btn btn-outline-danger btn-sm remove-delivery-area btn-premium">
                                    <i class="fas fa-trash-alt"></i> Remove
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
