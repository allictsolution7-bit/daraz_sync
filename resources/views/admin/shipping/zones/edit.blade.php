@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Shipping Zone</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shipping.zones.update', ['zone' => $zone->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="name">Zone Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $zone->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="regions">Regions</label>
                            <select name="regions[]" id="regions" class="form-control" multiple required>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ in_array($city, json_decode($zone->regions, true) ?? []) ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <select name="is_active" id="is_active" class="form-control">
                                <option value="1" {{ $zone->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$zone->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Zone</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection