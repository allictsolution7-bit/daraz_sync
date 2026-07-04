@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <h4 class="mt-2">My Delivery Integrations</h4>
        <hr>
        {{-- SteadFast Balance --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title mb-2">Steadfast Courier Balance</h6>
                        <div id="steadfast-balance-value" style="font-size: 1.5rem; font-weight: bold;">
                            Loading...
                        </div>
                        <button id="refresh-steadfast-balance"
                            class="btn btn-sm btn-outline-primary mt-2">Refresh</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title mb-2">Pathao Courier Balance</h6>
                        <div id="pathao-balance-value" style="font-size: 1.2rem; font-weight: bold;">
                            Not available from Pathao API
                        </div>
                        <button id="refresh-pathao-balance" class="btn btn-sm btn-outline-primary mt-2">Refresh</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Delivery Integrations</h5>
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Provider</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($integrations as $item)
                                    <tr>
                                        <td><strong>{{ ucfirst($item->provider) }}</strong></td>
                                        <td>
                                            @if($item->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.delivery.integration', $item->id) }}" class="btn btn-sm btn-info">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function fetchSteadfastBalance() {
            const balanceDiv = document.getElementById('steadfast-balance-value');
            balanceDiv.textContent = 'Loading...';
            fetch("{{ route('admin.steadfast.balance') }}")
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        balanceDiv.textContent = data.balance !== null ? data.balance + ' BDT' : 'N/A';
                    } else {
                        balanceDiv.textContent = 'Error: ' + (data.message || 'Could not fetch balance');
                    }
                })
                .catch(() => {
                    balanceDiv.textContent = 'Error fetching balance';
                });
        }
        document.addEventListener('DOMContentLoaded', function() {
            fetchSteadfastBalance();
            document.getElementById('refresh-steadfast-balance').addEventListener('click', fetchSteadfastBalance);
        });
    </script>
@endsection
