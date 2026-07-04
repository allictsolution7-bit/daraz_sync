@extends('layouts.master')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-6">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Add Category</h1>
                </div>

                <!-- Category Form -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">Category Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                    id="slug" name="slug" value="{{ old('slug') }}" required>
                                <small class="form-text text-muted" id="slug-status"></small>
                                <span class="text-danger" id="slug-error"></span>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Meta Title -->
                            <div class="form-group">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                    id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Meta Description -->
                            <div class="form-group">
                                <label for="meta_description">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                    name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image -->
                            <div class="form-group">
                                <label for="image">Category Image</label>
                                <input type="file" class="form-control-file @error('image') is-invalid @enderror"
                                    id="image" name="image">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Alt -->
                            <div class="form-group">
                                <label for="image_alt">Image Alt Text</label>
                                <input type="text" class="form-control @error('image_alt') is-invalid @enderror"
                                    id="image_alt" name="image_alt" value="{{ old('image_alt') }}">
                                @error('image_alt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Canonical URL -->
                            <div class="form-group">
                                <label for="canonical_url">Canonical URL</label>
                                <input type="url" class="form-control @error('canonical_url') is-invalid @enderror"
                                    id="canonical_url" name="canonical_url" value="{{ old('canonical_url') }}">
                                @error('canonical_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Is Featured -->
                            <div class="form-group">
                                <label for="is_featured">Featured</label>
                                <select class="form-control @error('is_featured') is-invalid @enderror" id="is_featured"
                                    name="is_featured">
                                    <option value="0" {{ old('is_featured') == '0' ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('is_featured')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Add SubCategory</h1>
                </div>

                <!-- Category Form -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form action="{{ route('admin.postsubcategory.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="post_category_id">Parent Category</label>
                                <select class="form-control @error('post_category_id') is-invalid @enderror" id="post_category_id"
                                    name="post_category_id" required>
                                    <option value="">Select Parent Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('post_category_id') == $category->id ? 'selected' : '' }}>
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
                                <label for="subname">Category Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="subname" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Slug -->
                            <div class="form-group">
                                <label for="subslug">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                    id="subslug" name="slug" value="{{ old('slug') }}" required>
                                <small class="form-text text-muted" id="subslug-status"></small>
                                <span class="text-danger" id="subslug-error"></span>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Meta Title -->
                            <div class="form-group">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                    id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Meta Description -->
                            <div class="form-group">
                                <label for="meta_description">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                    name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image -->
                            <div class="form-group">
                                <label for="image">Category Image</label>
                                <input type="file" class="form-control-file @error('image') is-invalid @enderror"
                                    id="image" name="image">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Alt -->
                            <div class="form-group">
                                <label for="image_alt">Image Alt Text</label>
                                <input type="text" class="form-control @error('image_alt') is-invalid @enderror"
                                    id="image_alt" name="image_alt" value="{{ old('image_alt') }}">
                                @error('image_alt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Canonical URL -->
                            <div class="form-group">
                                <label for="canonical_url">Canonical URL</label>
                                <input type="url" class="form-control @error('canonical_url') is-invalid @enderror"
                                    id="canonical_url" name="canonical_url" value="{{ old('canonical_url') }}">
                                @error('canonical_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">All Categories</h1>
                </div>

                <!-- Categories Table -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="categoriesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category/Subcategory</th>
                                <th>Slug</th>
                                <th>Meta Title</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach ($categories as $category)
                                {{-- Parent Category Row --}}
                                <tr class="table-primary">
                                    <td>{{ $counter++ }}</td>
                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                        <span class="badge bg-info ms-2 text-light">Parent</span>
                                    </td>
                                    <td>{{ $category->slug }}</td>
                                    <td>{{ $category->meta_title ?? 'N/A' }}</td>
                                    <td>
                                        @if ($category->image)
                                            <img src="{{ asset($category->image) }}"
                                                alt="{{ $category->image_alt ?? $category->name }}" class="img-thumbnail"
                                                width="50" height="50">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.category.view', $category->id) }}"
                                            class="btn btn-sm btn-primary">View</a>
                                        <a href="{{ route('admin.category.edit', $category->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this category? This will also delete all subcategories.')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- Subcategories Rows --}}
                                @foreach ($category->postsubcategories as $subcategory)
                                    <tr class="table-light">
                                        <td>{{ $counter++ }}</td>
                                        <td>
                                            <div class="ms-4">
                                                <i class="fas fa-level-down-alt me-2"></i>
                                                {{ $subcategory->name }}
                                                <span class="badge bg-secondary ms-2 text-light">Sub</span>
                                            </div>
                                        </td>
                                        <td>{{ $subcategory->slug }}</td>
                                        <td>{{ $subcategory->meta_title ?? 'N/A' }}</td>
                                        <td>
                                            @if ($subcategory->image)
                                                <img src="{{ asset($subcategory->image) }}"
                                                    alt="{{ $subcategory->image_alt ?? $subcategory->name }}"
                                                    class="img-thumbnail" width="50" height="50">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.postsubcategory.view', $subcategory->id) }}"
                                                class="btn btn-sm btn-primary">View</a>
                                            <a href="{{ route('admin.postsubcategory.edit', $subcategory->id) }}"
                                                class="btn btn-sm btn-primary">Edit</a>
                                            <form action="{{ route('admin.postsubcategory.destroy', $subcategory->id) }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this subcategory?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Parent Categories Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Parent Categories</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="parentCategoriesTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category Name</th>
                                        <th>Slug</th>
                                        <th>Meta Title</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1; @endphp
                                    @foreach ($categories as $category)
                                        <tr class="table-primary">
                                            <td>{{ $counter++ }}</td>
                                            <td><strong>{{ $category->name }}</strong></td>
                                            <td>{{ $category->slug }}</td>
                                            <td>{{ $category->meta_title ?? 'N/A' }}</td>
                                            <td>
                                                @if ($category->image)
                                                    <img src="{{ asset($category->image) }}"
                                                        alt="{{ $category->image_alt ?? $category->name }}"
                                                        class="img-thumbnail" width="50" height="50">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.postsubcategory.view', $category->id) }}"
                                                    class="btn btn-sm btn-primary">View</a>
                                                <a href="{{ route('admin.postsubcategory.edit', $category->id) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.postsubcategory.destroy', $category->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this category? This will also delete all subcategories.')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Subcategories Table -->
                <div class="card shadow mb-4 mt-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Subcategories</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="subcategoriesTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subcategory Name</th>
                                        <th>Parent Category</th>
                                        <th>Slug</th>
                                        <th>Meta Title</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1; @endphp
                                    @foreach ($categories as $category)
                                        @foreach ($category->postsubcategories as $subcategory)
                                            <tr class="table-light">
                                                <td>{{ $counter++ }}</td>
                                                <td>{{ $subcategory->name }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td>{{ $subcategory->slug }}</td>
                                                <td>{{ $subcategory->meta_title ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($subcategory->image)
                                                        <img src="{{ asset($subcategory->image) }}"
                                                            alt="{{ $subcategory->image_alt ?? $subcategory->name }}"
                                                            class="img-thumbnail" width="50" height="50">
                                                    @else
                                                        <span class="text-muted">No Image</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.postsubcategory.view', $subcategory->id) }}"
                                                        class="btn btn-sm btn-primary">View</a>
                                                    <a href="{{ route('admin.postsubcategory.edit', $subcategory->id) }}"
                                                        class="btn btn-sm btn-primary">Edit</a>
                                                    <form action="{{ route('admin.postsubcategory.destroy', $subcategory->id) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this subcategory?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <!-- Include TinyMCE Script -->
    {{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    // Replace the description textarea with TinyMCE
    tinymce.init({
        selector: '#description',
        plugins: 'link image code',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code'
    });
</script> --}}

    <!-- Include DataTables.net and Bootstrap 4 integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#categoriesTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#parentCategoriesTable, #subcategoriesTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true
            });
        });
    </script>

    <script>
        function generateSlug(inputId, outputId, validateUrl) {
            let title = document.getElementById(inputId).value;
            let slugField = document.getElementById(outputId);

            if (title.trim() !== '') {
                let slug = title.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-') // Replace spaces with dashes
                    .replace(/-+/g, '-'); // Remove duplicate dashes

                slugField.value = slug;
                validateSlug(slug, outputId, validateUrl);
            }
        }

        function validateSlug(slug, outputId, validateUrl) {
            let status = document.getElementById(outputId + '-status');
            let error = document.getElementById(outputId + '-error');

            $.ajax({
                url: validateUrl,
                type: "GET",
                data: {
                    slug: slug
                },
                success: function(response) {
                    if (response.valid) {
                        status.textContent = 'Slug is available.';
                        status.classList.remove('text-danger');
                        status.classList.add('text-success');
                        error.textContent = '';
                    } else {
                        status.textContent = '';
                        error.textContent = 'This slug is already taken. Suggested: ' + response.slug;
                        document.getElementById(outputId).value = response.slug;
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error validating slug:', error);
                }
            });
        }

        document.getElementById('name').addEventListener('input', function() {
            generateSlug('name', 'slug', "{{ url('/postcategory/validate-slug') }}");
        });

        document.getElementById('slug').addEventListener('input', function() {
            validateSlug(this.value, 'slug', "{{ url('/postcategory/validate-slug') }}");
        });

        document.getElementById('subname').addEventListener('input', function() {
            generateSlug('subname', 'subslug', "{{ url('/postsubcategory/validate-slug') }}");
        });

        document.getElementById('subslug').addEventListener('input', function() {
            validateSlug(this.value, 'subslug', "{{ url('/postsubcategory/validate-slug') }}");
        });
    </script>
@endsection
