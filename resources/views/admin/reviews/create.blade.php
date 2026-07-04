@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Customer Review</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.reviews.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reviewer_name">Reviewer Name <span class="text-danger">*</span></label>
                                    <input type="text" name="reviewer_name" id="reviewer_name" class="form-control" value="{{ old('reviewer_name') }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="reviewer_image">Reviewer Image</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="reviewer_image" name="reviewer_image">
                                        <label class="custom-file-label" for="reviewer_image">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Recommended size: 100x100 pixels</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="review_date">Review Date <span class="text-danger">*</span></label>
                                    <input type="date" name="review_date" id="review_date" class="form-control" value="{{ old('review_date', date('Y-m-d')) }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="rating">Rating <span class="text-danger">*</span></label>
                                    <select name="rating" id="rating" class="form-control" required>
                                        <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 Stars</option>
                                        <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 Stars</option>
                                        <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 Stars</option>
                                        <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 Stars</option>
                                        <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 Star</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_name">Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name') }}" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="product_image">Product Image</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="product_image" name="product_image">
                                        <label class="custom-file-label" for="product_image">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Recommended size: 200x200 pixels</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="review_text">Review Text <span class="text-danger">*</span></label>
                                    <textarea name="review_text" id="review_text" rows="5" class="form-control" required>{{ old('review_text') }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Review
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
        // File input display filename
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });
    });
</script>
@endpush