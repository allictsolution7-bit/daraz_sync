@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Menus</h5>
                        <div class="card-tools">
                            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Create New Menu
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($menus as $menu)
                                    <tr>
                                        <td>{{ $menu->id }}</td>
                                        <td>{{ $menu->name }}</td>
                                        <td>{{ $menu->location }}</td>
                                        <td>
                                            @if ($menu->status)
                                                <span class="badge bg-success text-white">Active</span>
                                            @else
                                                <span class="badge bg-danger text-white">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm edit-menu-btn"
                                                data-url="/admin/menus/{{ $menu->id }}/edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this menu?')">
                                                    <i class="fas fa-trash"></i> Delete
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
@endsection


@section('scripts')
<script>
    $(document).ready(function() {
        // Prevent any drag events on the edit buttons
        $('.edit-menu-btn').on('dragstart', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Handle click with stopPropagation to prevent event bubbling
        $('.edit-menu-btn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var url = $(this).data('url');
            // Use setTimeout to ensure this happens after any other event handlers
            setTimeout(function() {
                window.location.href = url;
            }, 10);
            return false;
        });
    });
</script>
@endsection
