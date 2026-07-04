@extends('layouts.master')

@section('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <style>
        .rule-type-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .priority-badge {
            background-color: #6c757d;
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shipping Rules</li>
            </ol>
        </nav>
        
        <h5>Shipping Rules Management</h5> <hr>
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.shipping.rules.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="product" {{ request('type') === 'product' ? 'selected' : '' }}>Products ({{ $productCount }})</option>
                        <option value="landing_page" {{ request('type') === 'landing_page' ? 'selected' : '' }}>Landing Pages ({{ $landingPageCount }})</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Rule Type</label>
                    <select name="rule_type" class="form-select">
                        <option value="">All Rule Types</option>
                        <option value="override" {{ request('rule_type') === 'override' ? 'selected' : '' }}>Override (Free)</option>
                        <option value="free_shipping" {{ request('rule_type') === 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                        <option value="custom_cost" {{ request('rule_type') === 'custom_cost' ? 'selected' : '' }}>Custom Cost</option>
                        <option value="percentage" {{ request('rule_type') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="delivery_area" {{ request('rule_type') === 'delivery_area' ? 'selected' : '' }}>Delivery Area</option>
                        <option value="conditional" {{ request('rule_type') === 'conditional' ? 'selected' : '' }}>Conditional</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary">Filter</button>
                        <a href="{{ route('admin.shipping.rules.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Add New Rule Button -->
        @can('shipping.rules.create')
        <a href="{{ route('admin.shipping.rules.create') }}" class="btn btn-primary rounded mb-2">
            <i class="bi bi-plus-circle"></i> Add New Rule
        </a>
        @else
        <button type="button" class="btn btn-secondary rounded mb-2" disabled title="No permission to create">
            <i class="bi bi-plus-circle"></i> Add New Rule
        </button>
        @endcan

        <!-- Rules Table -->
        @if($shippingRules->count() > 0)
            <table class="table table-striped" id="shippingRules">
                <thead>
                    <tr>
                        <th>Rule ID</th>
                        <th>Serial</th>
                        <th>Type</th>
                        <th>Item</th>
                        <th>Rule Type</th>
                        <th>Value</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shippingRules as $rule)
                        <tr>
                            <td>{{ $rule->id }}</td>
                            <td></td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $rule->ruleable_type === 'App\Models\Product' ? 'Product' : 'Landing Page' }}
                                </span>
                            </td>
                            <td>
                                <strong>{{ $rule->ruleable->title ?? 'N/A' }}</strong>
                                @if($rule->ruleable_type === 'App\Models\Product')
                                    <br><small class="text-muted">Product ID: {{ $rule->ruleable_id }}</small>
                                @else
                                    <br><small class="text-muted">Landing Page ID: {{ $rule->ruleable_id }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge rule-type-badge 
                                    @switch($rule->rule_type)
                                        @case('override') bg-success @break
                                        @case('free_shipping') bg-primary @break
                                        @case('custom_cost') bg-warning @break
                                        @case('percentage') bg-info @break
                                        @case('delivery_area') bg-dark @break
                                        @case('conditional') bg-secondary @break
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $rule->rule_type)) }}
                                </span>
                            </td>
                            <td>
                                @if($rule->rule_type === 'override')
                                    <span class="text-success">Free</span>
                                @elseif($rule->rule_type === 'free_shipping')
                                    <span class="text-primary">Threshold: ৳{{ number_format($rule->free_shipping_threshold, 2) }}</span>
                                @elseif($rule->rule_type === 'custom_cost')
                                    <span class="text-warning">৳{{ number_format($rule->rule_value, 2) }}</span>
                                @elseif($rule->rule_type === 'percentage')
                                    <span class="text-info">{{ $rule->rule_value }}%</span>
                                @elseif($rule->rule_type === 'delivery_area')
                                    <div>
                                        <strong>{{ $rule->delivery_area_name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $rule->delivery_area_slug ?? 'N/A' }}</small><br>
                                        <span class="text-dark">৳{{ number_format($rule->rule_value, 2) }}</span>
                                    </div>
                                @else
                                    <span class="text-secondary">Conditional</span>
                                @endif
                            </td>
                            <td>
                                <span class="priority-badge">{{ $rule->priority }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $rule->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $rule->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $rule->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form method="POST" action="{{ route('admin.shipping.rules.toggle', $rule) }}" class="d-inline me-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $rule->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                                title="{{ $rule->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas {{ $rule->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>
                                    @can('shipping.rules.update')
                                    <a href="{{ route('admin.shipping.rules.edit', $rule) }}" 
                                       class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <img src="{{ asset('edit.svg') }}" alt="Edit" width="20">
                                    </a>
                                    @else
                                    <button type="button" class="btn btn-sm btn-outline-secondary me-1" disabled title="No permission to edit">
                                        <img src="{{ asset('edit.svg') }}" alt="Edit" width="20">
                                    </button>
                                    @endcan
                                    @can('shipping.rules.delete')
                                    <form method="POST" action="{{ route('admin.shipping.rules.destroy', $rule) }}" class="d-inline" 
                                          onsubmit="return confirm('Are you sure you want to delete this shipping rule?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <img src="{{ asset('delete.svg') }}" alt="Delete" width="20">
                                        </button>
                                    </form>
                                    @else
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="No permission to delete">
                                        <img src="{{ asset('delete.svg') }}" alt="Delete" width="20">
                                    </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-5">
                <i class="bi bi-truck" style="font-size: 3rem; color: #6c757d;"></i>
                <h5 class="mt-3">No Shipping Rules Found</h5>
                <p class="text-muted">Create your first shipping rule to get started.</p>
                <a href="{{ route('admin.shipping.rules.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add New Rule
                </a>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>

    <script>
        $(document).ready(function() {
            $('#shippingRules').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy','pdf', 'csv', 'excel', 'print'
                ],
                order: [[0, 'asc']], // Sort by Rule ID ascending
                pageLength: 25,
                columnDefs: [
                    {
                        targets: 1, // Serial column (index 1)
                        searchable: false,
                        orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }
                ]
            });
        });
    </script>
@endsection
