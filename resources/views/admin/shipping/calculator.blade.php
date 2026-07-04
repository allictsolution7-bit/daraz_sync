@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Shipping Calculator</h3>
                </div>
                <div class="card-body">
                    <form id="calculatorForm">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Order Amount</label>
                            <input type="number" name="total_amount" class="form-control" step="0.01" required>
                        </div>

                        <div class="form-group">
                            <label>Total Weight (kg)</label>
                            <input type="number" name="total_weight" class="form-control" step="0.01">
                        </div>

                        <button type="submit" class="btn btn-primary">Calculate Shipping</button>
                    </form>

                    <div class="mt-4" id="result" style="display: none;">
                        <h4>Shipping Cost: <span id="shippingCost"></span></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#calculatorForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("admin.shipping.calculate") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#shippingCost').text('$' + response.formatted_cost);
                $('#result').show();
            },
            error: function(xhr) {
                alert('Error calculating shipping cost');
            }
        });
    });
});
</script>
@endpush
@endsection