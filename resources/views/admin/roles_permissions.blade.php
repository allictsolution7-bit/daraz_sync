@extends('layouts.master')

@section('styles')
<style>
    :root {
        --rp-bg: #f8fafc;
        --rp-border: #e5e7eb;
        --rp-text: #0f172a;
        --rp-muted: #6b7280;
        --rp-primary: #2563eb;
    }
    .rp-container { background: var(--rp-bg); border-radius: 12px; padding: 18px; }
    .rp-card { border: 1px solid var(--rp-border); border-radius: 12px; background: #fff; box-shadow: 0 8px 16px rgba(15,23,42,0.05); }
    .rp-card-header { padding: 12px 16px; border-bottom: 1px solid var(--rp-border); background: #f8fafc; }
    .rp-summary { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; }
    .rp-pill { background: #fff; border: 1px solid var(--rp-border); border-radius: 12px; padding: 10px 12px; box-shadow: 0 4px 10px rgba(15,23,42,0.04); }
    .rp-pill .label { font-size: 12px; text-transform: uppercase; letter-spacing: .06em; color: var(--rp-muted); }
    .rp-pill .value { font-size: 20px; font-weight: 800; color: var(--rp-text); }
    .perm-toolbar .btn { padding: 4px 10px; font-size: 12px; }
    .permission-search { max-width: 260px; }
    .permissions-group { border: 1px solid #e9ecef; border-radius: 8px; }
    .permissions-group .card-header { background: #f8f9fa; border-bottom: 1px solid #e9ecef; }
    .modal-dialog-scrollable .modal-body { max-height: 70vh; overflow-y: auto; }
    .permissions-scroll { max-height: none; overflow: visible; }
    .form-check { margin-bottom: 6px; }
    .badge-light { background: #eef2f7; color: #495057; }
    .modal-header .modal-title { font-weight: 600; }
    .permissions-group .text-uppercase { letter-spacing: .02em; }
    .form-check-input[type=checkbox] { border-radius: .25em; margin-top: 9px; }
    .table-hover tbody tr:hover { background: #f8fafc; }
    .role-actions .btn { margin-left: 6px; }
</style>
@endsection

@section('content')
<div class="container-fluid py-3 rp-container">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Roles & Permissions</h4>
            <div class="text-muted">Keep access simple and consistent.</div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addRoleModal"><i class="fas fa-plus"></i> Add Role</button>
            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addPermissionModal"><i class="fas fa-key"></i> Add Permission</button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
        $roleCount = $roles->count();
        $permCount = $permissions->count();
        $userCount = $users->count();
        $usersWithRoles = collect($users)->filter(fn($u) => $u->roles && $u->roles->count() > 0);
        $latestUsers = collect($users)->sortByDesc('created_at')->take(5);
    @endphp

    <div class="rp-summary mb-3">
        <div class="rp-pill">
            <div class="label">Roles</div>
            <div class="value">{{ $roleCount }}</div>
        </div>
        <div class="rp-pill">
            <div class="label">Permissions</div>
            <div class="value">{{ $permCount }}</div>
        </div>
        <div class="rp-pill">
            <div class="label">Users</div>
            <div class="value">{{ $userCount }}</div>
        </div>
        <div class="rp-pill">
            <div class="label">Users with roles</div>
            <div class="value">{{ $usersWithRoles->count() }}</div>
        </div>
    </div>

    <!-- Permissions Section -->
    {{-- <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><strong>Permissions</strong></span>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPermissionModal">Add Permission</button>
        </div>
        <ul class="list-group list-group-flush">
            @forelse($permissions as $permission)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $permission->name }}</span>
                    <span>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPermissionModal{{ $permission->id }}">Edit</button>
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deletePermissionModal{{ $permission->id }}">Delete</button>
                    </span>
                </li>
                <!-- Edit Permission Modal -->
                <div class="modal fade" id="editPermissionModal{{ $permission->id }}" tabindex="-1">
                    <div class="modal-dialog"><div class="modal-content">
                        <form method="POST" action="{{ route('admin.roles_permissions.permission.update', $permission) }}">
                            @csrf
                            <div class="modal-header"><h5 class="modal-title">Edit Permission</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="name" class="form-control" value="{{ $permission->name }}" required>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div></div>
                </div>
                <!-- Delete Permission Modal -->
                <div class="modal fade" id="deletePermissionModal{{ $permission->id }}" tabindex="-1">
                    <div class="modal-dialog"><div class="modal-content">
                        <form method="POST" action="{{ route('admin.roles_permissions.permission.delete', $permission) }}">
                            @csrf
                            <div class="modal-header"><h5 class="modal-title">Delete Permission</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">Are you sure you want to delete this permission?</div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div></div>
                </div>
            @empty
                <li class="list-group-item text-muted">No permissions found.</li>
            @endforelse
        </ul>
    </div> --}}
    <!-- Add Permission Modal -->
    <div class="modal fade" id="addPermissionModal" tabindex="-1">
        <div class="modal-dialog"><div class="modal-content">
            <form method="POST" action="{{ route('admin.roles_permissions.permission.store') }}">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Add Permission</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control" placeholder="Permission Name" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div></div>
    </div>

    @php
        // Group permissions by top-level resource and second-level subgroup
        $groupedPermissions = collect($permissions)
            ->groupBy(function($perm){
                $parts = explode('.', $perm->name);
                return $parts[0]; // e.g., products, orders, shipping
            })
            ->map(function($set){
                return $set->groupBy(function($perm){
                    $parts = explode('.', $perm->name);
                    return $parts[1] ?? 'core'; // e.g., zones/rules/basic or core
                })->sortKeys();
            })
            ->sortKeys();
    @endphp

    <!-- Roles Section -->
    <div class="rp-card mb-3">
        <div class="rp-card-header d-flex justify-content-between align-items-center">
            <strong>Roles</strong>
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addRoleModal"><i class="fas fa-plus"></i> Add Role</button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
            @forelse($roles as $role)
                <tr>
                    <td class="align-middle"><strong>{{ $role->name }}</strong></td>
                    <td class="align-middle" style="max-width: 600px;">
                        <div class="d-flex flex-wrap" style="gap: 4px;">
                            @forelse($role->permissions as $perm)
                                <span class="badge bg-light text-secondary border" style="font-size: 10px; padding: 4px 6px; font-weight: normal;">{{ $perm->name }}</span>
                            @empty
                                <span class="text-muted small">None</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="align-middle text-end role-actions">
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editRoleModal{{ $role->id }}">Edit</button>
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteRoleModal{{ $role->id }}">Delete</button>
                    </td>
                </tr>
                <!-- Edit Role Modal -->
                <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1">
                    <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
                        <form method="POST" action="{{ route('admin.roles_permissions.role.update', $role) }}">
                            @csrf
                            <div class="modal-header"><h5 class="modal-title">Edit Role</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <input type="text" name="name" class="form-control mb-2" value="{{ $role->name }}" required>
                                <div class="perm-toolbar d-flex align-items-center mb-2">
                                    {{-- <input type="text" class="form-control form-control-sm permission-search mr-2" placeholder="Search permissions..."> --}}
                                    <button type="button" class="btn btn-sm btn-outline-secondary select-all-perms mr-1">Select all</button>
                                    {{-- <button type="button" class="btn btn-sm btn-outline-secondary clear-all-perms mr-1">Clear</button> --}}
                                    {{-- <button type="button" class="btn btn-sm btn-outline-secondary expand-all mr-1">Expand</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary collapse-all">Collapse</button> --}}
                                </div>
                                <label class="mb-2">Permissions</label>
                                <div class="accordion" id="accordionRole{{ $role->id }}">
                                    @foreach($groupedPermissions as $group => $subgroups)
                                        @php $groupSlug = \Illuminate\Support\Str::slug($group); @endphp
                                        <div class="card permissions-group mb-2">
                                            <div class="card-header p-2" id="heading-{{ $role->id }}-{{ $groupSlug }}">
                                                <a class="d-block text-decoration-none" data-toggle="collapse" href="#collapse-role-{{ $role->id }}-{{ $groupSlug }}" aria-expanded="true" aria-controls="collapse-role-{{ $role->id }}-{{ $groupSlug }}">
                                                    <h6 class="mb-0 d-flex align-items-center justify-content-between">
                                                        <span class="text-capitalize">{{ str_replace('_',' ', $group) }}</span>
                                                        <span class="ml-2 badge badge-light">{{ $subgroups->flatten()->count() }}</span>
                                                        <label class="m-0 small">
                                                            <input type="checkbox" class="select-all-group" data-group="{{ $groupSlug }}"> Select all
                                                        </label>
                                                    </h6>
                                                </a>
                                            </div>
                                            <div id="collapse-role-{{ $role->id }}-{{ $groupSlug }}" class="collapse show" data-parent="#accordionRole{{ $role->id }}">
                                            <div class="card-body p-2 permissions-scroll">
                                                <div class="row">
                                                    @foreach($subgroups as $sub => $perms)
                                                        <div class="col-md-6 mb-2">
                                                            <div class="font-weight-bold small text-muted mb-1 text-uppercase">{{ $sub === 'core' ? $group : str_replace('_',' ', $sub) }}</div>
                                                            @foreach($perms as $permission)
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox" data-group="{{ $groupSlug }}" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="editroleperm_{{ $role->id }}_{{ $permission->id }}" {{ $role->permissions->pluck('name')->contains($permission->name) ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="editroleperm_{{ $role->id }}_{{ $permission->id }}">{{ $permission->name }}</label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Update</button>
                            </div>
                        </form>
                    </div></div>
                </div>
                <!-- Delete Role Modal -->
                <div class="modal fade" id="deleteRoleModal{{ $role->id }}" tabindex="-1">
                    <div class="modal-dialog"><div class="modal-content">
                        <form method="POST" action="{{ route('admin.roles_permissions.role.delete', $role) }}">
                            @csrf
                            <div class="modal-header"><h5 class="modal-title">Delete Role</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">Are you sure you want to delete this role?</div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div></div>
                </div>
            @empty
                <li class="list-group-item text-muted">No roles found.</li>
            @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Users overview: who has roles + latest created users with quick search -->
    <div class="rp-card mb-3">
        <div class="rp-card-header d-flex align-items-center justify-content-between">
            <strong>Users</strong>
            <div class="d-flex align-items-center" style="gap:8px;">
                <input id="userSearch" type="text" class="form-control form-control-sm" placeholder="Search by email or phone..." style="max-width:280px;">
                <small class="text-muted">Type to filter</small>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h6 class="mb-2">With roles</h6>
                    <ul class="list-group" id="usersWithRoles">
                        @forelse($usersWithRoles as $u)
                            <li class="list-group-item d-flex justify-content-between align-items-center user-item" data-email="{{ strtolower($u->email) }}" data-phone="{{ strtolower($u->phone ?? '') }}">
                                <div>
                                    <strong>{{ $u->name }}</strong>
                                    <div class="small text-muted">{{ $u->email }} @if($u->phone) • {{ $u->phone }} @endif</div>
                                    <div class="mt-1">
                                        @foreach($u->roles as $r)
                                            <span class="badge badge-primary mr-1">{{ $r->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editUserRolesModal{{ $u->id }}">Edit Roles</button>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No users have roles yet.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-2">Latest created (5)</h6>
                    <ul class="list-group" id="latestUsers">
                        @forelse($latestUsers as $u)
                            <li class="list-group-item d-flex justify-content-between align-items-center user-item" data-email="{{ strtolower($u->email) }}" data-phone="{{ strtolower($u->phone ?? '') }}">
                                <div>
                                    <strong>{{ $u->name }}</strong>
                                    <div class="small text-muted">{{ $u->email }} @if($u->phone) • {{ $u->phone }} @endif</div>
                                </div>
                                <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editUserRolesModal{{ $u->id }}">Assign Roles</button>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">No users found.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Role Modal -->
                <div class="modal fade" id="addRoleModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
            <form method="POST" action="{{ route('admin.roles_permissions.role.store') }}">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Add Role</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Role Name" required>
                    <div class="perm-toolbar d-flex align-items-center mb-2">
                        <input type="text" class="form-control form-control-sm permission-search mr-2" placeholder="Search permissions...">
                        <button type="button" class="btn btn-sm btn-outline-secondary select-all-perms mr-1">Select all</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary clear-all-perms mr-1">Clear</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary expand-all mr-1">Expand</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary collapse-all">Collapse</button>
                    </div>
                    <label class="mb-2">Permissions</label>
                    <div class="accordion" id="accordionAddRole">
                        @foreach($groupedPermissions as $group => $subgroups)
                            @php $groupSlug = \Illuminate\Support\Str::slug($group); @endphp
                            <div class="card permissions-group mb-2">
                                <div class="card-header p-2" id="heading-add-{{ $groupSlug }}">
                                    <a class="d-block text-decoration-none" data-toggle="collapse" href="#collapse-add-{{ $groupSlug }}" aria-expanded="true" aria-controls="collapse-add-{{ $groupSlug }}">
                                        <h6 class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-capitalize">{{ str_replace('_',' ', $group) }}</span>
                                            <span class="ml-2 badge badge-light">{{ $subgroups->flatten()->count() }}</span>
                                            <label class="m-0 small"><input type="checkbox" class="select-all-group" data-group="{{ $groupSlug }}"> Select all</label>
                                        </h6>
                                    </a>
                                </div>
                                <div id="collapse-add-{{ $groupSlug }}" class="collapse show" data-parent="#accordionAddRole">
                                <div class="card-body p-2 permissions-scroll">
                                    <div class="row">
                                        @foreach($subgroups as $sub => $perms)
                                            <div class="col-md-6 mb-2">
                                                <div class="font-weight-bold small text-muted mb-1 text-uppercase">{{ $sub === 'core' ? $group : str_replace('_',' ', $sub) }}</div>
                                                @foreach($perms as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" data-group="{{ $groupSlug }}" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="addroleperm_{{ $permission->id }}">
                                                        <label class="form-check-label" for="addroleperm_{{ $permission->id }}">{{ $permission->name }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div></div>
    </div>

    <!-- Assign Roles to Users Section -->
    <div class="rp-card mb-3">
        <div class="rp-card-header"><strong>Assign Roles to Users</strong></div>
        <ul class="list-group list-group-flush">
            @forelse($users as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $user->name }}</strong> <span class="text-muted small">({{ $user->email }})</span><br>
                        <span class="small">Roles:
                            @forelse ($user->roles as $role)
                                <span class="badge bg-primary rounded-pill">{{ $role->name }}</span>
                            @empty
                                <span class="badge bg-primary rounded-pill">None</span>
                            @endforelse
                        </span>
                    </div>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editUserRolesModal{{ $user->id }}">Edit Roles</button>
                </li>
                <!-- Edit User Roles Modal -->
                <div class="modal fade" id="editUserRolesModal{{ $user->id }}" tabindex="-1">
                    <div class="modal-dialog"><div class="modal-content">
                        <form method="POST" action="{{ route('admin.roles_permissions.user_roles.update', $user) }}">
                            @csrf
                            <div class="modal-header"><h5 class="modal-title">Assign Roles to {{ $user->name }}</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                @foreach($roles as $role)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="userrole_{{ $user->id }}_{{ $role->id }}" {{ $user->roles->pluck('name')->contains($role->name) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="userrole_{{ $user->id }}_{{ $role->id }}">{{ $role->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success">Update Roles</button>
                            </div>
                        </form>
                    </div></div>
                </div>
            @empty
                <li class="list-group-item text-muted">No users found.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection 

@push('scripts')
<script>
    // Select all permissions within a group in the current modal content
    document.addEventListener('change', function(e){
        if (e.target && e.target.classList.contains('select-all-group')) {
            const container = e.target.closest('.modal-content') || document;
            const group = e.target.getAttribute('data-group');
            const checked = e.target.checked;
            container.querySelectorAll("input.permission-checkbox[data-group='"+group+"']").forEach(cb => cb.checked = checked);
        }
    });

    // Global select/clear/expand/collapse in each modal
    document.addEventListener('click', function(e){
        const modal = e.target.closest('.modal-content');
        if (!modal) return;

        if (e.target.classList.contains('select-all-perms')) {
            modal.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
        }
        if (e.target.classList.contains('clear-all-perms')) {
            modal.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
            modal.querySelectorAll('.select-all-group').forEach(cb => cb.checked = false);
        }
        if (e.target.classList.contains('expand-all')) {
            modal.querySelectorAll('.collapse').forEach(el => $(el).collapse('show'));
        }
        if (e.target.classList.contains('collapse-all')) {
            modal.querySelectorAll('.collapse').forEach(el => $(el).collapse('hide'));
        }
    });

    // Simple permission filter in the open modal
    document.addEventListener('input', function(e){
        if (!e.target.classList.contains('permission-search')) return;
        const modal = e.target.closest('.modal-content');
        const term = e.target.value.toLowerCase();
        modal.querySelectorAll('.permissions-group .form-check').forEach(row => {
            const label = row.querySelector('label');
            const txt = label ? label.textContent.toLowerCase() : '';
            row.style.display = txt.includes(term) ? '' : 'none';
        });
    });

    // User search by email or phone across both lists
    document.addEventListener('input', function(e){
        if (e.target.id !== 'userSearch') return;
        const term = e.target.value.trim().toLowerCase();
        document.querySelectorAll('#usersWithRoles .user-item, #latestUsers .user-item').forEach(li => {
            const email = (li.getAttribute('data-email') || '').toLowerCase();
            const phone = (li.getAttribute('data-phone') || '').toLowerCase();
            li.style.display = (email.includes(term) || phone.includes(term)) ? '' : 'none';
        });
    });
</script>
@endpush
