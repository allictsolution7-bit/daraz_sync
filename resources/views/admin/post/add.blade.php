@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Typography & Layout Animation */
        .post-add-wrapper {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
            padding: 1.5rem 0;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Glassmorphism Card styling */
        .premium-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            margin-bottom: 2.5rem;
        }

        .premium-card:hover {
            box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.25);
        }

        /* Gradient Header area */
        .gradient-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 50%, #4f46e5 100%);
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .gradient-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        }

        .gradient-header-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .gradient-header-subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            margin-top: 0.35rem;
            margin-bottom: 0;
        }

        .premium-card-body {
            padding: 2.25rem;
        }

        .premium-card-header-simple {
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 2.25rem;
            background: #f8fafc;
        }

        .premium-card-title-simple {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        /* Form Group Elements */
        .form-group label {
            font-weight: 700;
            color: #475569;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.6rem;
            display: block;
        }

        .form-control-premium {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: #0f172a;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
            width: 100%;
        }

        .form-control-premium:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
            outline: none;
        }

        .form-control-premium.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.825rem;
            font-weight: 600;
            margin-top: 0.35rem;
        }

        /* Action Buttons */
        .premium-btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            border-top: 1.5px solid #f1f5f9;
            padding-top: 1.75rem;
        }

        .btn-premium {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            color: white !important;
            box-shadow: 0 8px 20px -6px rgba(37, 99, 235, 0.4);
        }

        .btn-premium-primary:hover {
            background: linear-gradient(135deg, #0284c7 0%, #1d4ed8 100%);
            box-shadow: 0 10px 24px -6px rgba(37, 99, 235, 0.5);
            transform: translateY(-1px);
        }

        .btn-premium-default {
            background-color: #f1f5f9;
            color: #475569 !important;
            border: 1px solid #e2e8f0;
        }

        .btn-premium-default:hover {
            background-color: #e2e8f0;
            color: #1e293b !important;
            transform: translateY(-1px);
        }

        /* Summernote Customizations */
        .note-editor.note-frame {
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 14px !important;
            overflow: hidden;
            background: #ffffff;
        }

        .note-toolbar {
            background-color: #f8fafc !important;
            border-bottom: 1.5px solid #e2e8f0 !important;
            padding: 8px 12px !important;
        }

        /* Modernized Table styling */
        .premium-table-container {
            overflow-x: auto;
        }

        .premium-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .premium-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            padding: 1.125rem 1.5rem;
            border-bottom: 2px solid #e2e8f0;
            border-top: none;
        }

        .premium-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            color: #334155;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .premium-table tr:last-child td {
            border-bottom: none;
        }

        .premium-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .premium-table tbody tr:hover {
            background-color: rgba(248, 250, 252, 0.8);
        }

        .post-id-badge {
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            background: #f1f5f9;
            padding: 0.25rem 0.6rem;
            border-radius: 8px;
        }

        .post-title-text {
            font-weight: 700;
            color: #0f172a;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        /* Category and Subcategory Badges */
        .category-badge {
            background-color: rgba(14, 165, 233, 0.06);
            color: #0369a1;
            border: 1px solid rgba(14, 165, 233, 0.12);
            padding: 0.3rem 0.75rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .subcategory-badge {
            background-color: rgba(79, 70, 229, 0.06);
            color: #4338ca;
            border: 1px solid rgba(79, 70, 229, 0.12);
            padding: 0.3rem 0.75rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Table Action Buttons */
        .btn-table-action {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            transition: all 0.2s ease;
            cursor: pointer;
            margin-right: 0.25rem;
        }

        .btn-table-action:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn-table-action-view:hover {
            color: #0ea5e9;
            background: rgba(14, 165, 233, 0.06);
            border-color: rgba(14, 165, 233, 0.2);
        }

        .btn-table-action-edit:hover {
            color: #2563eb;
            background: rgba(37, 99, 235, 0.06);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .btn-table-action-delete:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.06);
            border-color: rgba(239, 68, 68, 0.2);
        }

        /* DataTables Styling */
        .dataTables_wrapper .dataTables_length select {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.35rem 1.75rem 0.35rem 0.75rem;
            color: #0f172a;
            font-weight: 600;
            outline: none;
            transition: all 0.2s ease;
        }

        .dataTables_wrapper .dataTables_filter input {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.45rem 1rem;
            color: #0f172a;
            font-weight: 500;
            outline: none;
            transition: all 0.2s ease;
            margin-left: 0.5rem;
            min-width: 240px;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.4rem 0.85rem !important;
            margin-left: 0.25rem !important;
            border-radius: 10px !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            color: #475569 !important;
            transition: all 0.2s ease !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%) !important;
            color: white !important;
            border-color: transparent !important;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2) !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid post-add-wrapper">
        <div class="row">
            <div class="col-md-12">
                <!-- Add Post Form Card -->
                <div class="premium-card">
                    <!-- Gradient Header -->
                    <div class="gradient-header">
                        <h1 class="gradient-header-title">Create Blog Post</h1>
                        <p class="gradient-header-subtitle">Publish a new article to the store blog catalog.</p>
                    </div>

                    <div class="premium-card-body">
                        <form action="{{ route('admin.post.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <!-- Category Selection -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="post_category_id">Category</label>
                                        <select class="form-control-premium @error('post_category_id') is-invalid @enderror" id="post_category_id"
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
                                </div>

                                <!-- Subcategory Selection -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="post_sub_category_id">Subcategory</label>
                                        <select class="form-control-premium @error('post_sub_category_id') is-invalid @enderror"
                                            id="post_subcategory_id" name="post_sub_category_id">
                                            <option value="">Select Subcategory</option>
                                        </select>
                                        @error('post_sub_category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Title -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="title">Post Title</label>
                                        <input type="text" class="form-control-premium @error('title') is-invalid @enderror"
                                            id="title" name="title" placeholder="Enter post title" value="{{ old('title') }}" required autocomplete="off">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Slug -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="slug">Slug</label>
                                        <input type="text" class="form-control-premium @error('slug') is-invalid @enderror"
                                            id="slug" name="slug" placeholder="auto-generated-slug" value="{{ old('slug') }}" required
                                            oninput="validateSlug()" autocomplete="off">
                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                            <small class="form-text" id="slug-status" style="font-weight:600;"></small>
                                            <span class="text-danger small" id="slug-error" style="font-weight:600;"></span>
                                        </div>
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Content editor -->
                            <div class="form-group mb-4">
                                <label for="description">Content Body</label>
                                <textarea class="form-control-premium @error('content') is-invalid @enderror" id="description" name="content" rows="6">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <!-- Meta Title -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="meta_title">Meta Title (SEO)</label>
                                        <input type="text" class="form-control-premium @error('meta_title') is-invalid @enderror"
                                            id="meta_title" name="meta_title" placeholder="Meta title for search engines" value="{{ old('meta_title') }}">
                                        @error('meta_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Canonical URL -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="canonical_url">Canonical URL</label>
                                        <input type="url" class="form-control-premium @error('canonical_url') is-invalid @enderror"
                                            id="canonical_url" name="canonical_url" placeholder="https://example.com/canonical-url" value="{{ old('canonical_url') }}">
                                        @error('canonical_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Meta Description -->
                            <div class="form-group mb-4">
                                <label for="meta_description">Meta Description (SEO)</label>
                                <textarea class="form-control-premium @error('meta_description') is-invalid @enderror" id="meta_description"
                                    name="meta_description" placeholder="Write page meta description here..." rows="3">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <!-- Image file upload -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="image">Post Featured Image</label>
                                        <input type="file" class="form-control-premium @error('image') is-invalid @enderror"
                                            id="image" name="image">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Image Alt -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="image_alt">Image Alt Text (SEO)</label>
                                        <input type="text" class="form-control-premium @error('image_alt') is-invalid @enderror"
                                            id="image_alt" name="image_alt" placeholder="Describe image content" value="{{ old('image_alt') }}">
                                        @error('image_alt')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit button group -->
                            <div class="premium-btn-group">
                                <a href="{{ route('admin.post.index') }}" class="btn-premium btn-premium-default">
                                    <i class="fas fa-arrow-left"></i> Back to Listing
                                </a>
                                <button type="submit" class="btn-premium btn-premium-primary" style="flex:1;">
                                    <i class="fas fa-save"></i> Save Publication Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Posts List Table below form -->
            <div class="col-md-12">
                <div class="premium-card">
                    <div class="premium-card-header-simple">
                        <h6 class="premium-card-title-simple">Quick Overview: Existing Posts</h6>
                    </div>
                    <div class="premium-card-body">
                        <div class="premium-table-container">
                            <table class="premium-table" id="postsTable">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">#</th>
                                        <th>Post Title</th>
                                        <th>Category</th>
                                        <th>Subcategory</th>
                                        <th>Thumbnail</th>
                                        <th style="width: 150px; text-align: right; padding-right: 1.5rem;">Action Tools</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($posts as $post)
                                        <tr>
                                            <td>
                                                <span class="post-id-badge">#{{ $loop->iteration }}</span>
                                            </td>
                                            <td>
                                                <span class="post-title-text" title="{{ $post->title }}">{{ $post->title }}</span>
                                            </td>
                                            <td>
                                                <span class="category-badge">
                                                    <i class="fas fa-folder"></i> {{ $post->postcategory->name }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($post->postsubcategory)
                                                    <span class="subcategory-badge">
                                                        <i class="fas fa-folder-open"></i> {{ $post->postsubcategory->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted small">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($post->image)
                                                    <img src="{{ asset($post->image) }}"
                                                        alt="{{ $post->image_alt }}" class="img-thumbnail"
                                                        width="50" style="border-radius: 8px; border: 1px solid #cbd5e1; max-height: 50px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted small">No Image</span>
                                                @endif
                                            </td>
                                            <td style="text-align: right; padding-right: 1.5rem; white-space: nowrap;">
                                                <a href="{{ route('admin.post.view', $post->id) }}"
                                                    class="btn-table-action btn-table-action-view" title="View publication"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.post.edit', $post->id) }}"
                                                    class="btn-table-action btn-table-action-edit" title="Edit publication"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('admin.post.destroy', $post->id) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-table-action btn-table-action-delete"
                                                        onclick="return confirm('Are you sure you want to delete this post?')"
                                                        title="Delete publication">
                                                        <i class="fas fa-trash-alt"></i>
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
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search posts...",
                    lengthMenu: "Show _MENU_ items",
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>',
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                }
            });
        });
    </script>
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
            $('#description').summernote({
                height: 300,
                placeholder: 'Write your post content here...',
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
