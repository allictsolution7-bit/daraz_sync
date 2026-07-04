@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Publishers</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.publishers.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New Publisher
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Logo</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($publishers as $publisher)
                                        <tr>
                                            <td>{{ $publisher->id }}</td>
                                            <td>{{ $publisher->name }}</td>
                                            <td>
                                                @if ($publisher->logo)
                                                    <img src="{{ asset($publisher->logo) }}" alt="{{ $publisher->name }}" class="img-circle mr-2" width="40">
                                                @endif
                                            </td>
                                            <td>{{ $publisher->email }}</td>
                                            <td>{{ $publisher->address }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.publishers.edit', $publisher) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.publishers.destroy', $publisher) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this publisher?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger mx-1">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No publishers found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $publishers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
