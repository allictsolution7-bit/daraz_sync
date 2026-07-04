@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mt-2">Fraud Check Result Details</h4>
            <div>
                <button class="btn btn-warning refresh-result" data-id="{{ $result->id }}">
                    <i class="fas fa-sync"></i> Refresh Result
                </button>
                <a href="{{ route('admin.fraud-checker.results') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Results
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Phone Number:</strong></td>
                                <td>{{ $result->phone }}</td>
                            </tr>
                            <tr>
                                <td><strong>Risk Level:</strong></td>
                                <td>
                                    <span class="{{ $result->risk_level_badge_class }}">
                                        {{ $result->risk_level_display }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Risk Score:</strong></td>
                                <td>{{ $result->risk_score }}/100</td>
                            </tr>
                            <tr>
                                <td><strong>Last Checked:</strong></td>
                                <td>{{ $result->formatted_last_checked }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Delivery Statistics</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Total Parcels:</strong></td>
                                <td>{{ $result->total_parcels }}</td>
                            </tr>
                            <tr>
                                <td><strong>Delivered Parcels:</strong></td>
                                <td>{{ $result->delivered_parcels }}</td>
                            </tr>
                            <tr>
                                <td><strong>Canceled Parcels:</strong></td>
                                <td>{{ $result->canceled_parcels }}</td>
                            </tr>
                            <tr>
                                <td><strong>Success Rate:</strong></td>
                                <td>{{ $result->delivery_success_rate }}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if($result->recommendation)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Recommendation</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $result->recommendation }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(!empty($result->risk_factors))
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Risk Factors</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($result->risk_factors as $factor)
                                <li class="list-group-item">
                                    <i class="fas fa-exclamation-triangle text-warning"></i> {{ $factor }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($result->orders->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Related Orders ({{ $result->orders->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($result->orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->name }}</td>
                                            <td>{{ $order->total_with_charge }}</td>
                                            <td>{{ ucfirst($order->status) }}</td>
                                            <td>{{ $order->created_at->format('d M, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Refresh result functionality
            $('.refresh-result').click(function() {
                const id = $(this).data('id');
                const btn = $(this);
                
                if (confirm('Are you sure you want to refresh this result? This will call the APIs again.')) {
                    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Refreshing...');
                    
                    $.get(`/admin/fraud-checker/results/${id}/refresh`, function(response) {
                        if (response.success) {
                            alert('Result refreshed successfully!');
                            location.reload();
                        } else {
                            alert('Refresh failed: ' + response.message);
                        }
                    }).fail(function() {
                        alert('Refresh failed');
                    }).always(function() {
                        btn.prop('disabled', false).html('<i class="fas fa-sync"></i> Refresh Result');
                    });
                }
            });
        });
    </script>
@endsection
