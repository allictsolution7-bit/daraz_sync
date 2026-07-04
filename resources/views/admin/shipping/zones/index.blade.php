@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Shipping Zones</h3>
                    <a href="{{ route('admin.shipping.zones.create') }}" class="btn btn-primary float-right">Add New Zone</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Regions</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($zones as $zone)
                            <tr>
                                <td>{{ $zone->name }}</td>
                                <td>{{ implode(', ', json_decode($zone->regions, true) ?? []) }}</td>
                                {{-- <td>{{ is_array($zone->regions) ? implode(', ', $zone->regions) : $zone->regions }}</td> --}}
                                <td>{{ $zone->is_active ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <a href="{{ route('admin.shipping.zones.edit', $zone) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('admin.shipping.zones.destroy', $zone) }}" method="POST" class="d-inline">
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