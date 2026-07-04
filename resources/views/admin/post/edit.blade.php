@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Post</h1>
        </div>

        <!-- Edit Post Form -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                            value="{{ $post->slug }}">
                    </div>

                    <div class="form-group">
                        <label for="post_category_id">Category</label>
                        <select class="form-control" id="post_category_id" name="post_category_id" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $post->post_category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="post_sub_category_id">Subcategory</label>
                        <select class="form-control" id="post_sub_category_id" name="post_sub_category_id">
                            <option value="">Select Subcategory</option>
                            @foreach ($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}"
                                    {{ $post->post_sub_category_id == $subcategory->id ? 'selected' : '' }}>
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Content</label>
                        <textarea class="form-control" id="description" name="content" rows="10" required>{{ $post->content }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="meta_title">Meta Title</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                            value="{{ $post->meta_title }}">
                    </div>

                    <div class="form-group">
                        <label for="meta_description">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ $post->meta_description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="canonical_url">Canonical URL</label>
                        <input type="url" class="form-control" id="canonical_url" name="canonical_url"
                            value="{{ $post->canonical_url }}">
                    </div>

                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags"
                            value="{{ $post->tags }}">
                    </div>

                    <div class="form-group">
                        <label for="image">Featured Image</label>
                        @if ($post->image)
                            <div class="mb-2">
                                <img src="{{ asset($post->image) }}" alt="{{ $post->image_alt }}"
                                    class="img-thumbnail" width="150">
                            </div>
                        @endif
                        <input type="file" class="form-control-file" id="image" name="image">
                    </div>

                    <div class="form-group">
                        <label for="image_alt">Image Alt Text</label>
                        <input type="text" class="form-control" id="image_alt" name="image_alt"
                            value="{{ $post->image_alt }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Update Post</button>
                    <a href="{{ route('admin.post.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
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
                        $('#post_sub_category_id').html(options);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching subcategories:', error);
                        $('#post_sub_category_id').html(
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
