@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">View subcategory</h1>
    </div>

    <!-- subcategory Details -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td>{{ $subcategory->name }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $subcategory->description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Slug</th>
                        <td>{{ $subcategory->slug }}</td>
                    </tr>
                    <tr>
                        <th>Meta Title</th>
                        <td>{{ $subcategory->meta_title ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Meta Description</th>
                        <td>{{ $subcategory->meta_description ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Canonical URL</th>
                        <td>{{ $subcategory->canonical_url ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Image</th>
                        <td>
                            @if ($subcategory->image)
                                <img src="{{ asset($subcategory->image) }}" alt="{{ $subcategory->image_alt }}" class="img-thumbnail" width="150">
                            @else
                                No Image
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Image Alt Tag</th>
                        <td>{{ $subcategory->image_alt ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $subcategory->created_at->format('d M, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Updated At</th>
                        <td>{{ $subcategory->updated_at->format('d M, Y h:i A') }}</td>
                    </tr>

                    <tr>
                        <th>Created By</th>
                        <td>{{ $subcategory->creator ? $subcategory->creator->name : 'Unknown' }} ,Email : {{ $subcategory->creator ? $subcategory->creator->email : 'Unknown' }}</td>
                    </tr>


                </tbody>
            </table>
            <a href="{{ route('admin.category.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        </div>
    </div>
</div>
@endsection
