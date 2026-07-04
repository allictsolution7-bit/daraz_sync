@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Category</h1>
        </div>

        <!-- Edit Form -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $category->name) }}" required>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <!-- Slug -->
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                            value="{{ old('slug', $category->slug) }}" required>
                    </div>

                    <!-- Meta Title -->
                    <div class="form-group">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                            value="{{ old('meta_title', $category->meta_title) }}">
                    </div>

                    <!-- Meta Description -->
                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $category->meta_description) }}</textarea>
                    </div>

                    <!-- Canonical URL -->
                    <div class="form-group">
                        <label for="canonical_url">Canonical URL</label>
                        <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                            value="{{ old('canonical_url', $category->canonical_url) }}">
                    </div>

                    <!-- Image -->
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                        @if ($category->image)
                            <img src="{{ asset($category->image) }}" alt="{{ $category->image_alt }}"
                                class="mt-2 img-thumbnail" width="100">
                        @endif
                    </div>

                    <!-- Image Alt Text -->
                    <div class="form-group">
                        <label for="image_alt">Image Alt Text</label>
                        <input type="text" class="form-control" id="image_alt" name="image_alt"
                            value="{{ old('image_alt', $category->image_alt) }}">
                    </div>

                    <!-- Is Featured -->
                    <div class="form-group">
                        <label for="is_featured">Featured</label>
                        <select class="form-control @error('is_featured') is-invalid @enderror" id="is_featured"
                            name="is_featured">
                            <option value="0"
                                {{ old('is_featured', $category->is_featured) == '0' ? 'selected' : '' }}>No</option>
                            <option value="1"
                                {{ old('is_featured', $category->is_featured) == '1' ? 'selected' : '' }}>Yes</option>
                        </select>
                        @error('is_featured')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('admin.category.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
