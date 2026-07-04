<form action="{{ route('admin.inventory.adjust', $product->id) }}" method="POST" id="adjust-stock-form">
    @csrf
    
    <div class="mb-3">
        <div class="d-flex align-items-center mb-2">
            @if($product->thumb_image)
                <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title }}" 
                     class="me-3" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
            @endif
            <div>
                <h6 class="mb-1">{{ $product->title }}</h6>
                <small class="text-muted">ID: {{ $product->id }} | {{ ucfirst($product->product_type) }} Product</small>
            </div>
        </div>
    </div>

    @if($product->product_type === 'variable')
        <!-- Variable Product Adjustments -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            This is a variable product. You can adjust stock for individual variation combinations below. 
            <strong>Only fill in the combinations you want to adjust - leave others blank.</strong>
        </div>

        @if($product->variationCombinations->count() > 0)
            <div id="variable-adjustments">
                @foreach($product->variationCombinations as $index => $combination)
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>{{ $combination->display_name }}</strong>
                            <span class="badge bg-{{ $combination->stock_quantity > 0 ? 'success' : 'danger' }} ms-2">
                                Current: {{ $combination->stock_quantity }} units
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">Adjustment Type</label>
                                    <select name="adjustments[{{ $index }}][type]" class="form-select combination-type">
                                        <option value="">Select Type</option>
                                        <option value="adjustment">Adjustment</option>
                                        <option value="restock">Restock</option>
                                        <option value="damage">Damage/Loss</option>
                                        <option value="inventory_count">Inventory Count</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Quantity Change</label>
                                    <input type="number" name="adjustments[{{ $index }}][quantity]" 
                                           class="form-control combination-quantity" placeholder="e.g. +10 or -5">
                                    <small class="text-muted">Use + for increase, - for decrease</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Notes</label>
                                    <input type="text" name="adjustments[{{ $index }}][notes]" 
                                           class="form-control" placeholder="Optional notes">
                                </div>
                            </div>
                            <input type="hidden" name="adjustments[{{ $index }}][combination_id]" value="{{ $combination->id }}">
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                This variable product has no variation combinations. Please create variations first.
            </div>
        @endif
    @else
        <!-- Simple Product Adjustment -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Current stock: <strong>{{ $product->quantity ?? 0 }} units</strong>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label for="adjustment-type" class="form-label">Adjustment Type</label>
                <select name="type" id="adjustment-type" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="adjustment">Manual Adjustment</option>
                    <option value="restock">Restock</option>
                    <option value="damage">Damage/Loss</option>
                    <option value="inventory_count">Inventory Count</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="quantity" class="form-label">Quantity Change</label>
                <input type="number" name="quantity" id="quantity" class="form-control" 
                       placeholder="e.g. +10 or -5" required>
                <small class="text-muted">Use + for increase, - for decrease</small>
            </div>
            <div class="col-md-4">
                <label for="notes" class="form-label">Notes</label>
                <input type="text" name="notes" id="notes" class="form-control" 
                       placeholder="Optional notes">
            </div>
        </div>

        <!-- Preview -->
        <div class="mt-3">
            <div class="alert alert-light">
                <strong>Preview:</strong>
                <span id="stock-preview">
                    Current: {{ $product->quantity ?? 0 }} units → New: <span id="new-stock">{{ $product->quantity ?? 0 }}</span> units
                </span>
            </div>
        </div>
    @endif

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary" id="submit-adjustment">
            <i class="fas fa-save"></i> Apply Adjustment
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        @if($product->product_type === 'simple')
            // Simple product stock preview
            $('#quantity').on('input', function() {
                const currentStock = {{ $product->quantity ?? 0 }};
                const adjustment = parseInt($(this).val()) || 0;
                const newStock = currentStock + adjustment;
                
                $('#new-stock').text(newStock);
                
                // Update preview styling
                if (newStock < 0) {
                    $('#stock-preview').removeClass('alert-light alert-success alert-warning')
                                      .addClass('alert-danger');
                } else if (newStock <= {{ $product->low_stock_threshold ?? 5 }}) {
                    $('#stock-preview').removeClass('alert-light alert-success alert-danger')
                                      .addClass('alert-warning');
                } else {
                    $('#stock-preview').removeClass('alert-light alert-warning alert-danger')
                                      .addClass('alert-success');
                }
            });
        @endif

        // Form submission
        $('#adjust-stock-form').on('submit', function(e) {
            e.preventDefault();
            
            @if($product->product_type === 'variable')
                // Variable product validation - at least one combination must be filled
                let hasValidCombination = false;
                $('.combination-type').each(function(index) {
                    const type = $(this).val();
                    const quantity = $(`input[name="adjustments[${index}][quantity]"]`).val();
                    
                    if (type && quantity) {
                        hasValidCombination = true;
                    } else if (type && !quantity) {
                        alert('Please enter quantity for ' + $(this).closest('.card').find('.card-header strong').text());
                        return false;
                    } else if (!type && quantity) {
                        alert('Please select adjustment type for ' + $(this).closest('.card').find('.card-header strong').text());
                        return false;
                    }
                });
                
                if (!hasValidCombination) {
                    alert('Please adjust at least one variation combination.');
                    return false;
                }
            @endif
            
            const submitBtn = $('#submit-adjustment');
            const originalText = submitBtn.html();
            
            submitBtn.prop('disabled', true)
                     .html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...');
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#adjustStockModal').modal('hide');
                    
                    // Show success message
                    if (response.message) {
                        toastr.success(response.message);
                    } else {
                        toastr.success('Stock adjusted successfully!');
                    }
                    
                    // Reload the inventory table
                    if (typeof table !== 'undefined') {
                        table.ajax.reload();
                    } else {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'An error occurred while adjusting stock.';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        errorMessage = errors.join('<br>');
                    }
                    
                    toastr.error(errorMessage);
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });
    });
</script>
