@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mt-2">Fraud Check Results</h4>
            <a href="{{ route('admin.fraud-checker.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Phone</th>
                                <th>Risk Level</th>
                                <th>Risk Score</th>
                                <th>Success Rate</th>
                                <th>Total Parcels</th>
                                <th>Last Checked</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $result)
                                <tr>
                                    <td>{{ $result->phone }}</td>
                                    <td>
                                        <span class="{{ $result->risk_level_badge_class }}">
                                            {{ $result->risk_level_display }}
                                        </span>
                                    </td>
                                    <td>{{ $result->risk_score }}/100</td>
                                    <td>{{ $result->delivery_success_rate }}%</td>
                                    <td>{{ $result->total_parcels }}</td>
                                    <td>{{ $result->formatted_last_checked }}</td>
                                    <td>
                                        <a href="{{ route('admin.fraud-checker.result-details', $result->id) }}" class="btn btn-sm btn-info">Details</a>
                                        <button class="btn btn-sm btn-warning refresh-result" data-id="{{ $result->id }}">Refresh</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No fraud check results found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($results->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $results->links() }}
                    </div>
                @endif
            </div>
        </div>
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
                        btn.prop('disabled', false).html('Refresh');
                    });
                }
            });
        });
    </script>
@endsection
