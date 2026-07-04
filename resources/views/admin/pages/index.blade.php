@extends('layouts.master')

@section('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pages</li>
            </ol>
        </nav>
        <h5>All Pages</h5>
        <hr>
        <a href="{{route ("admin.pages.create")}}" class="btn btn-primary rounded mb-2">Add Page</a>
        <table class="table table-striped" id="Pages">
            <thead>
            <tr>
                <th>Page ID</th>
                <th>Serial</th>
                <th>Title</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($pages as $page)
                <tr>
                    <td>{{ $page->id }}</td>
                    <td></td>
                    <td>{{ $page->title }}</td>
                    <td>{{ $page->slug }}</td>
                    <td>
                        <span class="badge {{ $page->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $page->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $page->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.pages.edit', $page->id) }}" 
                               class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                <img src="{{ asset('edit.svg') }}" alt="Edit" width="20">
                            </a>
                            <a href="{{ url($page->slug) }}" 
                               class="btn btn-sm btn-outline-info me-1" title="View Frontend" 
                               target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" 
                                    title="Copy Full URL" onclick="copyToClipboard('{{ url($page->slug) }}')">
                                <i class="fas fa-copy"></i>
                            </button>
                            <form action="{{ route('admin.pages.destroy', $page->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        title="Delete" onclick="return confirm('Are you sure?')">
                                    <img src="{{ asset('delete.svg') }}" alt="Delete" width="20">
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>

    <script>
        $(document).ready(function() {
            $('#Pages').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy','pdf', 'csv', 'excel', 'print'
                ],
                order: [[0, 'asc']], // Sort by Page ID ascending
                pageLength: 25,
                columnDefs: [
                    {
                        targets: 1, // Serial column (index 1)
                        searchable: false,
                        orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }
                ]
            });
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'position-fixed top-0 end-0 p-3';
                toast.style.zIndex = '9999';
                toast.innerHTML = `
                    <div class="toast show" role="alert">
                        <div class="toast-header">
                            <strong class="me-auto">Copied!</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">
                            "${text}" copied to clipboard
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);
                
                // Remove toast after 3 seconds
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
@endsection
