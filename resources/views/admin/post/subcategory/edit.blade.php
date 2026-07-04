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
                <form action="{{ route('admin.postsubcategory.update', $subcategory->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="category_id">Parent Category</label>
                        
                        <select class="form-control @error('post_category_id') is-invalid @enderror" id="post_category_id"
                            name="post_category_id" required>
                            <option value="">Select Parent Category</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ (old('post_category_id') ?? $subcategory->post_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            
                            @endforeach
                        </select>
                        @error('post_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $subcategory->name) }}" required>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $subcategory->description) }}</textarea>
                    </div>

                    <!-- Slug -->
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                            value="{{ old('slug', $subcategory->slug) }}" required>
                    </div>

                    <!-- Meta Title -->
                    <div class="form-group">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                            value="{{ old('meta_title', $subcategory->meta_title) }}">
                    </div>

                    <!-- Meta Description -->
                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $subcategory->meta_description) }}</textarea>
                    </div>

                    <!-- Canonical URL -->
                    <div class="form-group">
                        <label for="canonical_url">Canonical URL</label>
                        <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                            value="{{ old('canonical_url', $subcategory->canonical_url) }}">
                    </div>

                    <!-- Image -->
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control-file" id="image" name="image">
                        @if ($subcategory->image)
                            <img src="{{ asset($subcategory->image) }}" alt="{{ $category->image_alt }}"
                                class="mt-2 img-thumbnail" width="100">
                        @endif
                    </div>

                    <!-- Image Alt Text -->
                    <div class="form-group">
                        <label for="image_alt">Image Alt Text</label>
                        <input type="text" class="form-control" id="image_alt" name="image_alt"
                            value="{{ old('image_alt', $subcategory->image_alt) }}">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('admin.postsubcategory.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
