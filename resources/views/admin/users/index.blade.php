@extends('layouts.master')

@section('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
        </nav>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <h5>All Users</h5>
        <hr>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded">Add User</a>
            <button type="button" class="btn btn-danger" id="deleteSelectedBtn" disabled onclick="deleteSelected()">
                <i class="fas fa-trash"></i> Delete Selected
            </button>
        </div>
        <table class="table table-striped" id="users">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                    </th>
                    <th>User ID</th>
                    <th>Serial</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <input type="checkbox" class="user-checkbox" value="{{ $user->id }}" onchange="updateDeleteButton()">
                        </td>
                        <td>{{ $user->id }}</td>
                        <td></td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->hasRole('super_admin'))
                                <span class="badge bg-danger">Super Admin</span>
                            @elseif($user->getRoleNames()->count())
                                @foreach($user->getRoleNames() as $role)
                                    <span class="badge bg-info">{{ $role }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary">User</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                   class="btn btn-sm btn-outline-primary me-1" title="View">
                                    <img src="{{ asset('view.svg') }}" alt="View" width="20">
                                </a>
                                <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}" 
                                   class="btn btn-sm btn-outline-info me-1" title="Edit">
                                    <img src="{{ asset('edit.svg') }}" alt="Edit" width="20">
                                </a>
                                <form action="{{ route('admin.users.destroy', ['id' => $user->id]) }}" 
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
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-RXf+QSDCUQs6hM1N06bOKa0CMQ0pVbkP4ylBO9K6Yf6V3u/6t6h2c1zF3JCn5l9N8t5g4U5qXT6xJTLNlrnZag==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script>
        $(document).ready(function() {
            $('#users').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy','pdf', 'csv', 'excel', 'print'
                ],
                order: [[1, 'asc']], // Sort by User ID ascending
                pageLength: 25,
                columnDefs: [
                    {
                        targets: 2, // Serial column (index 2)
                        searchable: false,
                        orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        targets: 0, // Checkbox column
                        searchable: false,
                        orderable: false
                    }
                ]
            });
        });

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            updateDeleteButton();
        }

        function updateDeleteButton() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            
            if (checkboxes.length > 0) {
                deleteBtn.disabled = false;
                deleteBtn.textContent = `Delete Selected (${checkboxes.length})`;
            } else {
                deleteBtn.disabled = true;
                deleteBtn.textContent = 'Delete Selected';
            }
        }

        function deleteSelected() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const userIds = Array.from(checkboxes).map(cb => cb.value);
            
            if (userIds.length === 0) {
                alert('Please select users to delete.');
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${userIds.length} user(s)?`)) {
                // Create a form to submit multiple user IDs
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.users.bulk-delete") }}';
                
                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Add method override
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                // Add user IDs
                userIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'user_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection
