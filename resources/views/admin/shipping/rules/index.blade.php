@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Shipping Rules</h3>
                    <a href="{{ route('admin.shipping.rules.create') }}" class="btn btn-primary float-right">Add New Rule</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Zone</th>
                                <th>Conditions</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rules as $rule)
                            <tr>
                                <td>{{ $rule->name }}</td>
                                <td>{{ str_replace('_', ' ', ucfirst($rule->type)) }}</td>
                                <td>{{ $rule->zone->name ?? 'Global' }}</td>
                                <td>
                                    @if($rule->min_amount)
                                        Min Amount: ${{ $rule->min_amount }}<br>
                                    @endif
                                    @if($rule->min_items)
                                        Min Items: {{ $rule->min_items }}<br>
                                    @endif
                                    @if($rule->category_id)
                                        Category: {{ $rule->category->name }}<br>
                                    @endif
                                </td>
                                <td>${{ $rule->shipping_cost ?? '0.00' }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input rule-toggle" 
                                               id="toggle{{ $rule->id }}" 
                                               {{ $rule->is_active ? 'checked' : '' }}
                                               data-rule-id="{{ $rule->id }}">
                                        <label class="custom-control-label" for="toggle{{ $rule->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.shipping.rules.edit', $rule) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('admin.shipping.rules.destroy', $rule) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
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
    $('.rule-toggle').change(function() {
        const ruleId = $(this).data('rule-id');
        $.post(`/admin/shipping-rules/toggle/${ruleId}`);
    });
</script>
@endsection
