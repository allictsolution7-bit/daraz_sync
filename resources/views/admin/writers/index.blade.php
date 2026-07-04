@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Writers</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.writers.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add New Writer
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
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Popularity Score</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($writers as $writer)
                                        <tr>
                                            <td>{{ $writer->id }}</td>
                                            <td>
                                                @if ($writer->photo)
                                                    <img src="{{ asset($writer->photo) }}" alt="{{ $writer->name }}" class="img-circle mr-2" width="40">
                                                @endif
                                            </td>
                                            <td>{{ $writer->name }}</td>
                                            <td>{{ $writer->email }}</td>
                                            <td>{{ $writer->popularity_score }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.writers.edit', $writer) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.writers.destroy', $writer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this writer?');">
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
                                            <td colspan="6" class="text-center">No writers found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $writers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
