@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between ">
                    <h1 class="h3 mb-0 text-gray-800">All Categories</h1>
                </div>
                <a href="{{ route('admin.category.add') }}" class="btn btn-primary mb-1 mt-2">Add Category / Subcategories</a>

                <div class="table-responsive mt-2">
                    <table class="table table-bordered" id="categoriesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Meta Title</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subcategories as $key => $subcategory)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $subcategory->name }}</td>
                                    <td>{{ $subcategory->slug }}</td>
                                    <td>{{ $subcategory->meta_title }}</td>
                                    <td>
                                        @if ($subcategory->image)
                                            <img src="{{ asset($subcategory->image) }}"
                                                alt="{{ $subcategory->image_alt ?? $subcategory->name }}" class="img-thumbnail"
                                                width="50" height="50">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.postsubcategory.edit', $subcategory->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="{{ route('admin.postsubcategory.view', $subcategory->id) }}" class="btn btn-sm btn-primary">View</a>
                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.postsubcategory.destroy', $subcategory->id) }}" method="POST"
                                            style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this subsubcategories?')">
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
@endsection

@section('scripts')

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
@endsection
