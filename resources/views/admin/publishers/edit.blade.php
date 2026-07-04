@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Publisher</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.publishers.index') }}" class="btn btn-default btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.publishers.update', $publisher) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $publisher->name) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="logo">Photo</label>
                                        @if ($publisher->logo)
                                            <div class="mb-2">
                                                <img src="{{ asset($publisher->logo) }}" alt="{{ $publisher->name }}" class="img-thumbnail" width="100">
                                            </div>
                                        @endif
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="logo" name="logo">
                                            <label class="custom-file-label" for="logo">Choose file</label>
                                        </div>
                                        <small class="form-text text-muted">Leave empty to keep the current image</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $publisher->email) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $publisher->phone) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $publisher->address) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="bio">Bio</label>
                                        <textarea name="bio" id="bio" rows="5" class="form-control">{{ old('bio', $publisher->bio) }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="popularity_score">Popularity Score</label>
                                        <input type="number" name="popularity_score" id="popularity_score" class="form-control" value="{{ old('popularity_score', $publisher->popularity_score) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Publisher
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });
        });
    </script>
@endpush
