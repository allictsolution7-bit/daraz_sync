@extends('layouts.master')

@section('styles')
    <style>
        .out-of-stock-card {
            border-left: 4px solid #dc3545;
            background: #f8d7da;
        }
        .product-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .stock-status {
            font-size: 1.1em;
            font-weight: bold;
            color: #dc3545;
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
                <li class="breadcrumb-item active" aria-current="page">Out of Stock</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">❌ Out of Stock Products</h4>
            <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Inventory
            </a>
        </div>

        @if($outOfStockProducts->count() > 0)
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i>
                <strong>{{ $outOfStockProducts->total() }} products</strong> are currently out of stock and need restocking.
            </div>

            <div class="row">
                @foreach($outOfStockProducts as $product)
                    <div class="col-lg-6 col-xl-4 mb-4">
                        <div class="card out-of-stock-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    @if($product->thumb_image)
                                        <img src="{{ asset('storage/' . $product->thumb_image) }}" 
                                             alt="{{ $product->title }}" class="product-thumb me-3">
                                    @else
                                        <div class="product-thumb me-3 bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-2">{{ $product->title }}</h6>
                                        <p class="text-muted small mb-2">
                                            ID: {{ $product->id }} | 
                                            {{ $product->category->name ?? 'No Category' }}
                                        </p>
                                        
                                        <div class="mb-3">
                                            @if($product->product_type === 'variable')
                                                @php
                                                    $totalStock = $product->variationCombinations->sum('stock_quantity');
                                                    $outOfStockCombinations = $product->variationCombinations->where('stock_quantity', '<=', 0);
                                                @endphp
                                                <div class="stock-status">
                                                    {{ $totalStock }} units total
                                                </div>
                                                <small class="text-muted">
                                                    {{ $outOfStockCombinations->count() }}/{{ $product->variationCombinations->count() }} variations out of stock
                                                </small>
                                            @else
                                                <div class="stock-status">
                                                    {{ $product->quantity ?? 0 }} units
                                                </div>
                                                <small class="text-muted">
                                                    Last updated: {{ $product->updated_at->diffForHumans() }}
                                                </small>
                                            @endif
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-danger adjust-stock-btn" 
                                                    data-product-id="{{ $product->id }}" 
                                                    data-product-title="{{ $product->title }}"
                                                    data-product-type="{{ $product->product_type }}">
                                                <i class="fas fa-plus"></i> Restock Now
                                            </button>
                                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                                               class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($product->product_type === 'variable' && $product->variationCombinations->count() > 0)
                                    <div class="mt-3 pt-3 border-top">
                                        <h6 class="small text-muted mb-2">Out of Stock Variations:</h6>
                                        @foreach($product->variationCombinations->where('stock_quantity', '<=', 0)->take(3) as $combination)
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <small class="text-truncate me-2">{{ $combination->display_name }}</small>
                                                <span class="badge bg-danger">
                                                    {{ $combination->stock_quantity }} units
                                                </span>
                                            </div>
                                        @endforeach
                                        @if($product->variationCombinations->where('stock_quantity', '<=', 0)->count() > 3)
                                            <small class="text-muted">
                                                +{{ $product->variationCombinations->where('stock_quantity', '<=', 0)->count() - 3 }} more...
                                            </small>
                                        @endif
                                    </div>
                                @endif

                                <!-- Last Stock Movement -->
                                @php
                                    $lastMovement = App\Models\StockMovement::where('product_id', $product->id)
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                                @endphp
                                @if($lastMovement)
                                    <div class="mt-3 pt-3 border-top">
                                        <small class="text-muted">
                                            <strong>Last Movement:</strong> 
                                            {{ ucfirst($lastMovement->type) }} 
                                            ({{ $lastMovement->quantity > 0 ? '+' : '' }}{{ $lastMovement->quantity }})
                                            {{ $lastMovement->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $outOfStockProducts->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h4>Excellent Stock Management! 🎉</h4>
                <p class="text-muted">No products are currently out of stock. Keep up the great work!</p>
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Inventory
                </a>
            </div>
        @endif
    </div>

    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="adjustStockModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📝 Restock Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="adjust-stock-content">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Adjust stock modal
            $(document).on('click', '.adjust-stock-btn', function() {
                const productId = $(this).data('product-id');
                const productTitle = $(this).data('product-title');
                const productType = $(this).data('product-type');
                
                $('#adjustStockModal .modal-title').html('📝 Restock - ' + productTitle);
                $('#adjust-stock-content').html(`
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
                
                $('#adjustStockModal').modal('show');
                
                // Load adjustment form
                $.get('{{ route("admin.inventory.adjust-form", ":id") }}'.replace(':id', productId))
                    .done(function(response) {
                        $('#adjust-stock-content').html(response);
                        
                        // Pre-select restock type
                        if (productType === 'simple') {
                            $('#adjustment-type').val('restock');
                        } else {
                            // Pre-select restock for all variations
                            $('select[name*="[type]"]').val('restock');
                        }
                    })
                    .fail(function() {
                        $('#adjust-stock-content').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                Failed to load restock form. Please try again.
                            </div>
                        `);
                    });
            });
        });
    </script>
@endsection
