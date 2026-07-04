@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Add Post</h1>
                </div>

                <!-- Post Form -->
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form action="{{ route('admin.post.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Category Selection -->
                            <div class="form-group">
                                <label for="post_category_id">Category</label>
                                <select class="form-control @error('post_category_id') is-invalid @enderror" id="post_category_id"
                                    name="post_category_id" required>
                                    <option value="">Select Category</option>
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

                            <!-- Subcategory Selection -->
                            <div class="form-group">
                                <label for="post_sub_category_id">Subcategory</label>
                                <select class="form-control @error('post_sub_category_id') is-invalid @enderror"
                                    id="post_subcategory_id" name="post_sub_category_id">
                                    <option value="">Select Subcategory</option>
                                </select>
                                @error('post_sub_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div class="form-group">
                                <label for="title">Post Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Slug (Auto-generated & Validated) -->
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                    id="slug" name="slug" value="{{ old('slug') }}" required
                                    oninput="validateSlug()">
                                <small class="form-text text-muted" id="slug-status"></small>
                                <span class="text-danger" id="slug-error"></span>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Content -->
                            <div class="form-group">
                                <label for="description">Content</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="description" name="content" rows="5">{{ old('content') }}</textarea>
                                @error('content')
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
                                <label for="image">Post Image</label>
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
                                <button type="submit" class="btn btn-primary">Save Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Posts Table -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">All Posts</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="postsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Subcategory</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $post)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $post->title }}</td>
                                            <td>{{ $post->postcategory->name }}</td>
                                            <td>{{ $post->postsubcategory->name ?? 'N/A' }}</td>
                                            <td>
                                                @if ($post->image)
                                                    <img src="{{ asset($post->image) }}"
                                                        alt="{{ $post->image_alt }}" class="img-thumbnail"
                                                        width="50" height="50">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.post.view', $post->id) }}"
                                                    class="btn btn-sm btn-primary">View</a>
                                                <a href="{{ route('admin.post.edit', $post->id) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.post.destroy', $post->id) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this post?')">
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
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#postsTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true
            });
        });
    </script>
    <!-- Include DataTables.net and Bootstrap 4 integration -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $('#post_category_id').on('change', function() {
            let categoryId = $(this).val();
            if (categoryId) {
                $.ajax({
                    url: `/get-subcategories/${categoryId}`,
                    method: 'GET',
                    success: function(data) {
                        let options = '<option value="">Select Subcategory</option>';
                        if (data.length > 0) {
                            data.forEach(subcategory => {
                                options +=
                                    `<option value="${subcategory.id}">${subcategory.name}</option>`;
                            });
                        } else {
                            options = '<option value="">No Subcategories Found</option>';
                        }
                        $('#post_subcategory_id').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching subcategories:', error);
                        $('#post_subcategory_id').html(
                            '<option value="">Error loading subcategories</option>');
                    }
                });
            } else {
                $('#post_subcategory_id').html('<option value="">Select Subcategory</option>');
            }
        });
    </script>

    <!-- Include Summernote CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote for Description
            $('#description').summernote({
                height: 300, // Editor height
                placeholder: 'Write here...',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
        });
    </script>

    <script>
        document.getElementById('title').addEventListener('input', function() {
            const title = this.value;
            if (title.trim() !== '') {
                const slug = title.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-');
                document.getElementById('slug').value = slug;
                validateSlug(slug);
            }
        });

        document.getElementById('slug').addEventListener('input', function() {
            const slug = this.value;
            if (slug.trim() !== '') {
                validateSlug(slug);
            }
        });

        function validateSlug(slug) {
            fetch(`{{ url('/post/validate-slug') }}?slug=${slug}`)
                .then(response => response.json())
                .then(data => {
                    const status = document.getElementById('slug-status');
                    const error = document.getElementById('slug-error');

                    if (data.valid) {
                        status.textContent = 'Slug is available.';
                        status.classList.remove('text-danger');
                        status.classList.add('text-success');
                        error.textContent = '';
                    } else {
                        status.textContent = '';
                        error.textContent = 'This slug is already taken.';
                    }
                })
                .catch(error => console.error('Error validating slug:', error));
        }
    </script>
@endsection
