@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Payment Gateways</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($gateways->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No payment gateways configured yet.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Gateway</th>
                                        <th>Provider</th>
                                        <th>Mode</th>
                                        <th>Fee</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gateways as $gateway)
                                    <tr>
                                        <td>
                                            <strong>{{ $gateway->name }}</strong>
                                        </td>
                                        <td><code>{{ $gateway->provider }}</code></td>
                                        <td>
                                            @if($gateway->is_live)
                                                <span class="badge bg-success">Live</span>
                                            @else
                                                <span class="badge bg-warning">Sandbox</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($gateway->transaction_fee_percent > 0 || $gateway->transaction_fee_fixed > 0)
                                                {{ $gateway->transaction_fee_percent }}% + {{ $gateway->transaction_fee_fixed }} {{ $gateway->currency }}
                                            @else
                                                <span class="text-muted">No fee</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-gateway" type="checkbox"
                                                    data-gateway-id="{{ $gateway->id }}"
                                                    {{ $gateway->is_enabled ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.payment-gateways.edit', $gateway) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-cog"></i> Configure
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.toggle-gateway').change(function() {
        var gatewayId = $(this).data('gateway-id');
        $.ajax({
            url: '/admin/payment-gateways/' + gatewayId + '/toggle',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(data) {
                if (data.success) {
                    // Optional: show toast
                }
            }
        });
    });
});
</script>
@endsection
