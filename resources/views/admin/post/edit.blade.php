@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Typography & Layout Animation */
        .post-edit-wrapper {
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
            margin-bottom: 2rem;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
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

        /* Featured Image Card */
        .featured-image-container {
            display: inline-block;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 0.75rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .featured-image-preview {
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            object-fit: cover;
            max-height: 150px;
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
    </style>
@endsection

@section('content')
    <div class="container-fluid post-edit-wrapper">
        <div class="premium-card">
            <!-- Gradient Header -->
            <div class="gradient-header">
                <h1 class="gradient-header-title">Edit Blog Post</h1>
                <p class="gradient-header-subtitle">Update properties, tags, categories, or the text body of this post.</p>
            </div>

            <div class="premium-card-body">
                <form action="{{ route('admin.post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Category Selection -->
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="post_category_id">Category</label>
                                <select class="form-control-premium" id="post_category_id" name="post_category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $post->post_category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Subcategory Selection -->
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="post_sub_category_id">Subcategory</label>
                                <select class="form-control-premium" id="post_sub_category_id" name="post_sub_category_id">
                                    <option value="">Select Subcategory</option>
                                    @foreach ($subcategories as $subcategory)
                                        <option value="{{ $subcategory->id }}"
                                            {{ $post->post_sub_category_id == $subcategory->id ? 'selected' : '' }}>
                                            {{ $subcategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Title -->
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control-premium" id="title" name="title" value="{{ $post->title }}" required autocomplete="off">
                            </div>
                        </div>

                        <!-- Slug -->
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control-premium" id="slug" name="slug" value="{{ $post->slug }}" autocomplete="off" oninput="validateSlug()">
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="form-text" id="slug-status" style="font-weight:600;"></small>
                                    <span class="text-danger small" id="slug-error" style="font-weight:600;"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content body editor -->
                    <div class="form-group mb-4">
                        <label for="description">Content Body</label>
                        <textarea class="form-control-premium" id="description" name="content" rows="10" required>{{ $post->content }}</textarea>
                    </div>

                    <div class="row">
                        <!-- Meta Title -->
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="meta_title">Meta Title (SEO)</label>
                                <input type="text" class="form-control-premium" id="meta_title" name="meta_title" value="{{ $post->meta_title }}">
                            </div>
                        </div>

                        <!-- Canonical URL -->
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="canonical_url">Canonical URL</label>
                                <input type="url" class="form-control-premium" id="canonical_url" name="canonical_url" value="{{ $post->canonical_url }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Tags -->
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="tags">Tags</label>
                                <input type="text" class="form-control-premium" id="tags" name="tags" value="{{ $post->tags }}">
                            </div>
                        </div>

                        <!-- Image Alt Text -->
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label for="image_alt">Image Alt Text (SEO)</label>
                                <input type="text" class="form-control-premium" id="image_alt" name="image_alt" value="{{ $post->image_alt }}">
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image & upload selector -->
                    <div class="form-group mb-4">
                        <label for="image">Featured Image</label>
                        @if ($post->image)
                            <div class="mb-3">
                                <div class="featured-image-container">
                                    <img src="{{ asset($post->image) }}" alt="{{ $post->image_alt }}" class="featured-image-preview">
                                </div>
                            </div>
                        @endif
                        <input type="file" class="form-control-premium" id="image" name="image">
                    </div>

                    <!-- Submit action button group -->
                    <div class="premium-btn-group">
                        <a href="{{ route('admin.post.index') }}" class="btn-premium btn-premium-default">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn-premium btn-premium-primary" style="flex:1;">
                            <i class="fas fa-save"></i> Update Publication Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
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
                $('#post_sub_category_id').html('<option value="">Select Subcategory</option>');
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
