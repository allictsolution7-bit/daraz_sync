@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Shipping Rule</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shipping.rules.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Rule Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Rule Type</label>
                            <select name="type" class="form-control" id="ruleType" required>
                                <option value="location_free">Free Shipping for Location</option>
                                <option value="location_min_amount">Free Shipping for Location with Minimum Amount</option>
                                <option value="location_paid">Paid Shipping for Location</option>
                                <option value="product_specific">Free Shipping for Specific Products</option>
                                <option value="min_items">Free Shipping for Minimum Items</option>
                                <option value="flat_rate">Flat Rate Shipping</option>
                                <option value="global_min_amount">Global Free Shipping with Minimum Amount</option>
                                <option value="weight_based">Weight-based Shipping</option>
                                <option value="category_min_amount">Category-based Free Shipping with Minimum Amount</option>
                            </select>
                        </div>

                        <div class="form-group zone-field">
                            <label>Zone</label>
                            <select name="zone_id" class="form-control">
                                <option value="">Select Zone</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group amount-field" style="display: none;">
                            <label>Minimum Amount</label>
                            <input type="number" name="min_amount" class="form-control" step="0.01">
                        </div>

                        <div class="form-group items-field" style="display: none;">
                            <label>Minimum Items</label>
                            <input type="number" name="min_items" class="form-control">
                        </div>

                        <div class="form-group cost-field">
                            <label>Shipping Cost</label>
                            <input type="number" name="shipping_cost" class="form-control" step="0.01">
                        </div>

                        <div class="form-group weight-field" style="display: none;">
                            <label>Minimum Weight</label>
                            <input type="number" name="min_weight" class="form-control" step="0.01">
                            <label>Maximum Weight</label>
                            <input type="number" name="max_weight" class="form-control" step="0.01">
                        </div>

                        <div class="form-group category-field" style="display: none;">
                            <label>Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group products-field" style="display: none;">
                            <label>Products</label>
                            <select name="products[]" class="form-control select2" multiple>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Priority</label>
                            <input type="number" name="priority" class="form-control" value="0">
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_active" value="1" checked>
                                Active
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">Create Rule</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();

        $('#ruleType').change(function() {
            const type = $(this).val();
            
            // Hide all conditional fields
            $('.zone-field, .amount-field, .items-field, .cost-field, .weight-field, .category-field, .products-field').hide();
            
            // Show relevant fields based on type
            switch(type) {
                case 'location_free':
                    $('.zone-field').show();
                    break;
                case 'location_min_amount':
                    $('.zone-field, .amount-field, .cost-field').show();
                    break;
                case 'location_paid':
                    $('.zone-field, .cost-field').show();
                    break;
                case 'product_specific':
                    $('.products-field').show();
                    break;
                case 'min_items':
                    $('.items-field, .cost-field').show();
                    break;
                case 'flat_rate':
                    $('.cost-field').show();
                    break;
                case 'global_min_amount':
                    $('.amount-field, .cost-field').show();
                    break;
                case 'weight_based':
                    $('.weight-field, .cost-field').show();
                    break;
                case 'category_min_amount':
                    $('.category-field, .amount-field, .cost-field').show();
                    break;
            }
        });

        // Trigger change event on page load
        $('#ruleType').trigger('change');
    });
</script>
@endpush
@endsection