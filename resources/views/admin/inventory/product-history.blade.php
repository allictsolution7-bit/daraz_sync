@extends('layouts.master')

@section('styles')
    <style>
        .product-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .product-thumb {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 12px;
            border: 3px solid rgba(255,255,255,0.3);
        }
        .movement-timeline {
            position: relative;
            padding-left: 30px;
        }
        .movement-timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .movement-item {
            position: relative;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .movement-item::before {
            content: '';
            position: absolute;
            left: -22px;
            top: 20px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 3px solid white;
            background: #197A94;
            z-index: 1;
        }
        .movement-item.sale::before { background: #dc3545; }
        .movement-item.return::before { background: #17a2b8; }
        .movement-item.restock::before { background: #28a745; }
        .movement-item.initial::before { background: #28a745; }
        .movement-item.damage::before { background: #6c757d; }
        .movement-item.adjustment::before { background: #197A94; }
        .movement-item.inventory_count::before { background: #ffc107; }
        
        .quantity-badge {
            font-size: 1.1em;
            font-weight: bold;
            padding: 6px 12px;
        }
        .quantity-positive { background: #d4edda; color: #155724; }
        .quantity-negative { background: #f8d7da; color: #721c24; }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.inventory.index') }}">Inventory</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.inventory.history') }}">History</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->title }}</li>
            </ol>
        </nav>

        <!-- Product Header -->
        <div class="product-header">
            <div class="d-flex align-items-center">
                @if($product->thumb_image)
                    <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title }}" class="product-thumb me-4">
                @else
                    <div class="product-thumb me-4 bg-white bg-opacity-25 d-flex align-items-center justify-content-center">
                        <i class="fas fa-image fa-2x text-white"></i>
                    </div>
                @endif
                
                <div class="flex-grow-1">
                    <h3 class="mb-2">{{ $product->title }}</h3>
                    <div class="row">
                        <div class="col-md-3">
                            <small class="opacity-75">Product ID</small>
                            <div class="fw-bold">{{ $product->id }}</div>
                        </div>
                        <div class="col-md-3">
                            <small class="opacity-75">Type</small>
                            <div class="fw-bold">{{ ucfirst($product->product_type) }}</div>
                        </div>
                        <div class="col-md-3">
                            <small class="opacity-75">Current Stock</small>
                            <div class="fw-bold">
                                @if($product->product_type === 'variable')
                                    {{ $product->variationCombinations->sum('stock_quantity') }} units
                                @else
                                    {{ $product->quantity ?? 0 }} units
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <small class="opacity-75">Stock Status</small>
                            <div class="fw-bold">
                                <span class="badge bg-{{ $product->stock_status === 'in_stock' ? 'success' : ($product->stock_status === 'out_of_stock' ? 'danger' : 'warning') }}">
                                    {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-end">
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-light btn-sm me-2">
                        <i class="fas fa-arrow-left"></i> Back to Inventory
                    </a>
                    <button type="button" class="btn btn-warning btn-sm adjust-stock-btn" 
                            data-product-id="{{ $product->id }}" 
                            data-product-title="{{ $product->title }}"
                            data-product-type="{{ $product->product_type }}">
                        <i class="fas fa-edit"></i> Adjust Stock
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">📊 Stock Movement History</h5>
                        <small class="text-muted">Total {{ $movements->total() }} movements found</small>
                    </div>
                    <div class="card-body">
                        @if($movements->count() > 0)
                            <div class="movement-timeline">
                                @foreach($movements as $movement)
                                    <div class="movement-item {{ $movement->type }}">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1">
                                                    {{ ucfirst(str_replace('_', ' ', $movement->type)) }}
                                                    @if($movement->variationCombination)
                                                        <small class="text-muted">- {{ $movement->variationCombination->display_name }}</small>
                                                    @endif
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock"></i>
                                                    {{ $movement->created_at->format('M d, Y - h:i A') }}
                                                    ({{ $movement->created_at->diffForHumans() }})
                                                </small>
                                            </div>
                                            <span class="quantity-badge badge quantity-{{ $movement->quantity > 0 ? 'positive' : 'negative' }}">
                                                {{ $movement->quantity > 0 ? '+' : '' }}{{ $movement->quantity }}
                                            </span>
                                        </div>
                                        
                                        @if($movement->notes)
                                            <div class="mb-2">
                                                <strong>Notes:</strong> {{ $movement->notes }}
                                            </div>
                                        @endif
                                        
                                        <div class="d-flex justify-content-between align-items-center text-muted small">
                                            <div>
                                                @if($movement->reference_type && $movement->reference_id)
                                                    <i class="fas fa-link"></i>
                                                    Reference: {{ $movement->reference_type }} #{{ $movement->reference_id }}
                                                @endif
                                            </div>
                                            <div>
                                                @if($movement->creator)
                                                    <i class="fas fa-user"></i>
                                                    By: {{ $movement->creator->name }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $movements->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-history fa-4x text-muted mb-3"></i>
                                <h5>No Stock Movements Found</h5>
                                <p class="text-muted">This product has no recorded stock movements yet.</p>
                                <button type="button" class="btn btn-primary adjust-stock-btn" 
                                        data-product-id="{{ $product->id }}" 
                                        data-product-title="{{ $product->title }}"
                                        data-product-type="{{ $product->product_type }}">
                                    <i class="fas fa-plus"></i> Add Initial Stock
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="adjustStockModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📝 Adjust Stock</h5>
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
                
                $('#adjustStockModal .modal-title').html('📝 Adjust Stock - ' + productTitle);
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
                    })
                    .fail(function() {
                        $('#adjust-stock-content').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                Failed to load adjustment form. Please try again.
                            </div>
                        `);
                    });
            });

            // Handle successful stock adjustment
            $(document).on('stockAdjusted', function() {
                // Reload the page to show updated movements
                setTimeout(function() {
                    location.reload();
                }, 1000);
            });
        });
    </script>
@endsection
