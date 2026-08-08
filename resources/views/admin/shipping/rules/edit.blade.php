@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Shipping Rule</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.shipping.rules.update', ['rule' => $rule->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label>Rule Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $rule->name }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Rule Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="flat_rate" {{ $rule->type == 'flat_rate' ? 'selected' : '' }}>Flat Rate</option>
                                    <option value="free_shipping" {{ $rule->type == 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                                    <option value="weight_based" {{ $rule->type == 'weight_based' ? 'selected' : '' }}>Weight Based</option>
                                    <option value="quantity_based" {{ $rule->type == 'quantity_based' ? 'selected' : '' }}>Quantity Based</option>
                                    <option value="price_based" {{ $rule->type == 'price_based' ? 'selected' : '' }}>Price Based</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Shipping Zone</label>
                                <select name="zone_id" class="form-control">
                                    <option value="">-- Select Zone --</option>
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}" {{ $rule->zone_id == $zone->id ? 'selected' : '' }}>
                                            {{ $zone->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Minimum Order Amount</label>
                                        <input type="number" name="min_amount" class="form-control" step="0.01" value="{{ $rule->min_amount }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Minimum Items</label>
                                        <input type="number" name="min_items" class="form-control" value="{{ $rule->min_items }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Shipping Cost</label>
                                        <input type="number" name="shipping_cost" class="form-control" step="0.01" value="{{ $rule->shipping_cost }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Minimum Weight (kg)</label>
                                        <input type="number" name="min_weight" class="form-control" step="0.01" value="{{ $rule->min_weight }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Maximum Weight (kg)</label>
                                        <input type="number" name="max_weight" class="form-control" step="0.01" value="{{ $rule->max_weight }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Product Category</label>
                                <select name="category_id" class="form-control">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $rule->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Products</label>
                                <select name="products[]" class="form-control select2" multiple>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ $rule->products->contains($product->id) ? 'selected' : '' }}>
                                            {{ $product->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Leave empty to apply to all products</small>
                            </div>
                            
                            <div class="form-group">
                                <label>Priority</label>
                                <input type="number" name="priority" class="form-control" value="{{ $rule->priority }}" min="0">
                                <small class="form-text text-muted">Higher number = higher priority</small>
                            </div>
                            
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="is_active" value="1" {{ $rule->is_active ? 'checked' : '' }}>
                                    Active
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Update Rule</button>
                            <a href="{{ route('admin.shipping.rules.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush