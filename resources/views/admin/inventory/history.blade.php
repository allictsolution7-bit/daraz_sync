@extends('layouts.master')

@section('styles')
    <style>
        .movement-card {
            border-left: 4px solid #197A94;
            transition: all 0.3s ease;
        }
        .movement-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .movement-type-initial { border-left-color: #28a745; }
        .movement-type-adjustment { border-left-color: #197A94; }
        .movement-type-sale { border-left-color: #dc3545; }
        .movement-type-return { border-left-color: #17a2b8; }
        .movement-type-restock { border-left-color: #28a745; }
        .movement-type-damage { border-left-color: #6c757d; }
        .movement-type-inventory_count { border-left-color: #ffc107; }
        
        .quantity-positive { color: #28a745; font-weight: bold; }
        .quantity-negative { color: #dc3545; font-weight: bold; }
        .quantity-neutral { color: #6c757d; font-weight: bold; }
        
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.inventory.index') }}">Inventory</a></li>
                <li class="breadcrumb-item active" aria-current="page">Stock Movement History</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">📊 Stock Movement History</h4>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Inventory
            </a>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.inventory.history') }}" id="filter-form">
                <div class="row">
                    <div class="col-md-3">
                        <label for="product_id" class="form-label">Product</label>
                        <select name="product_id" id="product_id" class="form-select">
                            <option value="">All Products</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="type" class="form-label">Movement Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">All Types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.inventory.history') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        @if($movements->count() > 0)
            <!-- Movement Type Legend -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Movement Type Legend</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2" style="width: 20px; height: 20px; background: #28a745; border-radius: 3px;"></div>
                                        <span><strong>Initial/Restock:</strong> Stock added</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2" style="width: 20px; height: 20px; background: #dc3545; border-radius: 3px;"></div>
                                        <span><strong>Sale:</strong> Stock sold</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2" style="width: 20px; height: 20px; background: #17a2b8; border-radius: 3px;"></div>
                                        <span><strong>Return:</strong> Stock returned</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2" style="width: 20px; height: 20px; background: #197A94; border-radius: 3px;"></div>
                                        <span><strong>Adjustment:</strong> Manual adjustment</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2" style="width: 20px; height: 20px; background: #6c757d; border-radius: 3px;"></div>
                                        <span><strong>Damage:</strong> Stock damaged/lost</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-2" style="width: 20px; height: 20px; background: #ffc107; border-radius: 3px;"></div>
                                        <span><strong>Inventory Count:</strong> Physical count adjustment</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Movement History -->
            <div class="row">
                @foreach($movements as $movement)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="card movement-card movement-type-{{ $movement->type }} h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="card-title mb-1">
                                            {{ $movement->product->title ?? 'Unknown Product' }}
                                        </h6>
                                        <small class="text-muted">
                                            {{ $movement->created_at->format('M d, Y - h:i A') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-{{ $movement->type === 'sale' || $movement->type === 'damage' ? 'danger' : ($movement->type === 'return' || $movement->type === 'restock' || $movement->type === 'initial' ? 'success' : 'primary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $movement->type)) }}
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Quantity Change:</span>
                                        <span class="quantity-{{ $movement->quantity > 0 ? 'positive' : ($movement->quantity < 0 ? 'negative' : 'neutral') }}">
                                            {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                        </span>
                                    </div>
                                    
                                    @if($movement->variationCombination)
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <strong>Variation:</strong> {{ $movement->variationCombination->display_name }}
                                            </small>
                                        </div>
                                    @endif
                                </div>

                                @if($movement->notes)
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <strong>Notes:</strong> {{ $movement->notes }}
                                        </small>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center">
                                    @if($movement->reference_type && $movement->reference_id)
                                        <small class="text-muted">
                                            Ref: {{ $movement->reference_type }} #{{ $movement->reference_id }}
                                        </small>
                                    @else
                                        <span></span>
                                    @endif
                                    
                                    @if($movement->creator)
                                        <small class="text-muted">
                                            By: {{ $movement->creator->name }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $movements->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-history fa-4x text-muted mb-3"></i>
                <h4>No Stock Movements Found</h4>
                <p class="text-muted">
                    @if(request()->hasAny(['product_id', 'type', 'date_from', 'date_to']))
                        No stock movements match your current filters. Try adjusting your search criteria.
                    @else
                        No stock movements have been recorded yet.
                    @endif
                </p>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Inventory
                </a>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Auto-submit form when filters change (optional)
            $('#product_id, #type').on('change', function() {
                // Uncomment to auto-submit on filter change
                // $('#filter-form').submit();
            });

            // Set max date to today
            const today = new Date().toISOString().split('T')[0];
            $('#date_from, #date_to').attr('max', today);

            // Validate date range
            $('#date_from, #date_to').on('change', function() {
                const fromDate = $('#date_from').val();
                const toDate = $('#date_to').val();
                
                if (fromDate && toDate && fromDate > toDate) {
                    alert('From date cannot be later than To date.');
                    $(this).val('');
                }
            });
        });
    </script>
@endsection
