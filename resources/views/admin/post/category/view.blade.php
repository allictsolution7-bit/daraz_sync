@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View Category</h1>
    </div>

    <!-- Category Details -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td>{{ $category->name }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $category->description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{ $category->slug }}</td>
                    </tr>
                    <tr>
                        <th>Meta Title</th>
                        <td>{{ $category->meta_title ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Meta Description</th>
                        <td>{{ $category->meta_description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Canonical URL</th>
                        <td>{{ $category->canonical_url ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Image</th>
                        <td>
                            @if ($category->image)
                                <img src="{{ asset($category->image) }}" alt="{{ $category->image_alt }}" class="img-thumbnail" width="150">
                            @else
                                No Image
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Image Alt Tag</th>
                        <td>{{ $category->image_alt ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $category->created_at->format('d M, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $category->updated_at->format('d M, Y h:i A') }}</td>
                    </tr>

                    <tr>
                        <th>Created By</th>
                        <td>{{ $category->creator ? $category->creator->name : 'Unknown' }} ,Email : {{ $category->creator ? $category->creator->email : 'Unknown' }}</td>
                    </tr>


                </tbody>
            </table>
            <a href="{{ route('admin.category.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
</div>
@endsection
