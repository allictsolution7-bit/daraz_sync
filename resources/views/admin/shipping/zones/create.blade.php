@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Shipping Zone</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.shipping.zones.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Zone Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                            <div class="form-group">
                                <label>Regions</label>
                                <div class="checkbox-group">
                                    @foreach($cities as $city)
                                        <div class="form-check">
                                            <input type="checkbox" name="regions[]" class="form-check-input" value="{{ $city }}" id="city_{{ $loop->index }}">
                                            <label class="form-check-label" for="city_{{ $loop->index }}">{{ $city }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        
                        <hr>
                        <div class="form-group mb-2">
                            <label>
                                <input type="checkbox" name="is_active" value="1" checked>
                                Active
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Zone</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection