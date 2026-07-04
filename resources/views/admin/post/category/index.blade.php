@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h1 class="h3 mb-0 text-gray-800 ">All Categories</h1>
                </div>

                <a href="{{ route('admin.category.add') }}" class="btn btn-primary">Add Category / SubCategory</a>

                <!-- Categories Table -->
                <div class="table-responsive mt-2">
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
                                                <a href="{{ route('admin.category.view', $category->id) }}"
                                                    class="btn btn-sm btn-primary">View</a>
                                                <a href="{{ route('admin.category.edit', $category->id) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.category.destroy', $category->id) }}"
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
@endsection
