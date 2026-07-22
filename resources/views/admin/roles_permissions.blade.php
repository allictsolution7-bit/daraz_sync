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
    .permission-search { max-width: 260px; }
    .modal-dialog-scrollable .modal-body { max-height: 70vh; overflow-y: auto; }
    .permissions-scroll { max-height: none; overflow: visible; }
    .table-hover tbody tr:hover { background: #f8fafc; }
    .nav-tabs-custom {
        border-bottom: 2px solid var(--rp-border);
        margin-bottom: 20px;
        display: flex;
        gap: 8px;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        color: var(--rp-muted);
        font-weight: 600;
        padding: 10px 16px;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s;
        background: transparent;
    }
    .nav-tabs-custom .nav-link:hover {
        color: var(--rp-primary);
        background: #f1f5f9;
        border: none;
    }
    .nav-tabs-custom .nav-link.active {
        color: var(--rp-primary);
        background: #fff;
        border: 1px solid var(--rp-border);
        border-bottom: 2px solid #fff;
        margin-bottom: -2px;
    }

    /* Premium Modal Styling */
    .modal-content {
        border-radius: 16px !important;
        border: none !important;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25) !important;
        overflow: hidden;
    }
    .modal-header-custom {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #fff;
        padding: 18px 24px;
        border-bottom: 1px solid #334155;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100%;
    }
    .modal-header-custom .modal-title {
        font-weight: 700;
        font-size: 1.15rem;
        letter-spacing: -0.01em;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 0;
    }
    .modal-header-custom .close {
        color: #94a3b8;
        opacity: 0.8;
        font-size: 1.5rem;
        transition: all 0.2s;
        text-shadow: none;
        margin-left: auto !important;
        background: transparent;
        border: none;
        outline: none;
        padding: 0 4px;
        line-height: 1;
        cursor: pointer;
    }
    .modal-header-custom .close:hover {
        color: #fff;
        opacity: 1;
    }
    .role-input-wrapper label {
        font-weight: 600;
        font-size: 0.825rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 6px;
    }
    .role-input-custom {
        border-radius: 10px !important;
        border: 1.5px solid #cbd5e1 !important;
        padding: 10px 14px !important;
        font-weight: 600 !important;
        color: #0f172a !important;
        transition: all 0.2s;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.03);
    }
    .role-input-custom:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15) !important;
        outline: none;
    }
    .perm-toolbar {
        background: #f1f5f9;
        border-radius: 12px;
        padding: 10px 14px;
        gap: 8px;
        border: 1px solid #e2e8f0;
    }
    .search-input-wrapper {
        position: relative;
        flex: 1;
        max-width: 300px;
    }
    .search-input-wrapper .fa-search {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
    }
    .permission-search-custom {
        padding-left: 34px !important;
        border-radius: 8px !important;
        border: 1px solid #cbd5e1 !important;
        background: #fff !important;
        font-size: 13px !important;
    }
    .permission-search-custom:focus {
        border-color: #2563eb !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }
    .btn-perm-tool {
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        transition: all 0.15s;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #475569;
    }
    .btn-perm-tool:hover {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
        transform: translateY(-1px);
    }
    .permissions-group {
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(15,23,42,0.02);
        transition: all 0.2s;
    }
    .permissions-group:hover {
        border-color: #cbd5e1 !important;
        box-shadow: 0 4px 12px rgba(15,23,42,0.05);
    }
    .permissions-group .card-header {
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 12px 16px !important;
    }
    .permissions-group .card-header h6 {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.95rem;
    }
    .subgroup-box {
        background: #ffffff;
        border-radius: 10px;
        border-left: 3px solid #3b82f6;
        padding: 10px 14px;
        height: 100%;
        border-top: 1px solid #f1f5f9;
        border-right: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
    }
    .subgroup-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #475569;
        margin-bottom: 8px;
        padding-bottom: 4px;
        border-bottom: 1px dashed #cbd5e1;
    }
    .perm-item-check {
        padding: 5px 8px;
        border-radius: 6px;
        transition: background 0.15s;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
    }
    .perm-item-check:hover {
        background: #eff6ff;
    }
    .perm-item-check label {
        font-size: 13px;
        font-weight: 500;
        color: #334155;
        cursor: pointer;
        margin-bottom: 0;
        user-select: none;
    }
    .perm-item-check .form-check-input {
        cursor: pointer;
        width: 16px;
        height: 16px;
        margin-top: 0;
        margin-right: 8px;
        border-color: #94a3b8;
    }
    .badge-perm-count {
        background: #e0e7ff;
        color: #4338ca;
        font-weight: 700;
        border-radius: 20px;
        padding: 3px 10px;
        font-size: 11px;
    }
    .modal-footer-custom {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 14px 24px;
    }
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
                if (($parts[0] === 'admin' && isset($parts[1]) && $parts[1] === 'pos') || $parts[0] === 'pos') {
                    return 'pos';
                }
                return $parts[0];
            })
            ->map(function($set, $groupKey){
                return $set->groupBy(function($perm) use ($groupKey) {
                    $parts = explode('.', $perm->name);
                    if ($groupKey === 'pos') {
                        return $parts[2] ?? $parts[1] ?? 'access';
                    }
                    return $parts[1] ?? 'core';
                })->sortKeys();
            })
            ->sortKeys();
    @endphp

    <ul class="nav nav-tabs nav-tabs-custom mb-3" id="rolesPermissionsTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="roles-tab" data-toggle="tab" href="#roles" role="tab" aria-controls="roles" aria-selected="true"><i class="fas fa-user-shield mr-1"></i> Roles</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="false"><i class="fas fa-users mr-1"></i> Users Overview</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="assign-tab" data-toggle="tab" href="#assign" role="tab" aria-controls="assign" aria-selected="false"><i class="fas fa-user-tag mr-1"></i> Assign Roles</a>
        </li>
    </ul>

    <div class="tab-content" id="rolesPermissionsTabsContent">
        <!-- Roles Tab -->
        <div class="tab-pane fade show active" id="roles" role="tabpanel" aria-labelledby="roles-tab">
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
                            <div class="modal-header modal-header-custom">
                                <h5 class="modal-title"><i class="fas fa-user-pen" style="color: #60a5fa;"></i> Edit Role — <span class="text-info">{{ $role->name }}</span></h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="role-input-wrapper mb-3">
                                    <label><i class="fas fa-id-card mr-1"></i> Role Name</label>
                                    <input type="text" name="name" class="form-control role-input-custom" value="{{ $role->name }}" required>
                                </div>
                                <div class="perm-toolbar d-flex flex-wrap align-items-center mb-3">
                                    <div class="search-input-wrapper mr-auto">
                                        <i class="fas fa-search"></i>
                                        <input type="text" class="form-control permission-search-custom permission-search" placeholder="Search permissions...">
                                    </div>
                                    <button type="button" class="btn btn-perm-tool select-all-perms"><i class="fas fa-check-double mr-1"></i> Select all</button>
                                    <button type="button" class="btn btn-perm-tool clear-all-perms"><i class="fas fa-times mr-1"></i> Clear</button>
                                    <button type="button" class="btn btn-perm-tool expand-all"><i class="fas fa-chevron-down mr-1"></i> Expand</button>
                                    <button type="button" class="btn btn-perm-tool collapse-all"><i class="fas fa-chevron-up mr-1"></i> Collapse</button>
                                </div>
                                <div class="role-input-wrapper mb-2"><label><i class="fas fa-shield-halved mr-1"></i> Module & Access Permissions</label></div>
                                <div class="accordion" id="accordionRole{{ $role->id }}">
                                    @foreach($groupedPermissions as $group => $subgroups)
                                        @php $groupSlug = \Illuminate\Support\Str::slug($group); @endphp
                                        <div class="card permissions-group mb-3">
                                            <div class="card-header" id="heading-{{ $role->id }}-{{ $groupSlug }}">
                                                <a class="d-block text-decoration-none" data-toggle="collapse" href="#collapse-role-{{ $role->id }}-{{ $groupSlug }}" aria-expanded="true" aria-controls="collapse-role-{{ $role->id }}-{{ $groupSlug }}">
                                                    <h6 class="mb-0 d-flex align-items-center justify-content-between">
                                                        <span class="text-capitalize d-flex align-items-center">
                                                            <i class="fas fa-layer-group text-primary mr-2"></i>
                                                            {{ $group === 'pos' ? 'Point of Sale (POS)' : ($group === 'incomplete_orders' ? 'Incomplete Orders' : str_replace('_',' ', $group)) }}
                                                        </span>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge-perm-count mr-3">{{ $subgroups->flatten()->count() }} permissions</span>
                                                            <label class="m-0 small font-weight-bold text-muted" onclick="event.stopPropagation()">
                                                                <input type="checkbox" class="select-all-group" data-group="{{ $groupSlug }}"> Select all
                                                            </label>
                                                        </div>
                                                    </h6>
                                                </a>
                                            </div>
                                            <div id="collapse-role-{{ $role->id }}-{{ $groupSlug }}" class="collapse show" data-parent="#accordionRole{{ $role->id }}">
                                            <div class="card-body p-3 permissions-scroll bg-light">
                                                <div class="row">
                                                    @foreach($subgroups as $sub => $perms)
                                                        <div class="col-md-6 mb-3">
                                                            <div class="subgroup-box">
                                                                <div class="subgroup-title">{{ $sub === 'core' ? $group : str_replace('_',' ', $sub) }}</div>
                                                                @foreach($perms as $permission)
                                                                    <div class="perm-item-check">
                                                                        <input class="form-check-input permission-checkbox" data-group="{{ $groupSlug }}" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="editroleperm_{{ $role->id }}_{{ $permission->id }}" {{ $role->permissions->pluck('name')->contains($permission->name) ? 'checked' : '' }}>
                                                                        <label for="editroleperm_{{ $role->id }}_{{ $permission->id }}">{{ $permission->name }}</label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="modal-footer modal-footer-custom">
                                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 font-weight-bold"><i class="fas fa-save mr-1"></i> Update Role</button>
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
    </div>

        <!-- Users Overview Tab -->
        <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
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
</div>

        <!-- Assign Roles Tab -->
        <div class="tab-pane fade" id="assign" role="tabpanel" aria-labelledby="assign-tab">
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
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
            <form method="POST" action="{{ route('admin.roles_permissions.role.store') }}">
                @csrf
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title"><i class="fas fa-plus-circle" style="color: #60a5fa;"></i> Create New Role</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <div class="role-input-wrapper mb-3">
                        <label><i class="fas fa-id-card mr-1"></i> Role Name</label>
                        <input type="text" name="name" class="form-control role-input-custom" placeholder="e.g. Content Manager, Vendor Specialist" required>
                    </div>
                    <div class="perm-toolbar d-flex flex-wrap align-items-center mb-3">
                        <div class="search-input-wrapper mr-auto">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control permission-search-custom permission-search" placeholder="Search permissions...">
                        </div>
                        <button type="button" class="btn btn-perm-tool select-all-perms"><i class="fas fa-check-double mr-1"></i> Select all</button>
                        <button type="button" class="btn btn-perm-tool clear-all-perms"><i class="fas fa-times mr-1"></i> Clear</button>
                        <button type="button" class="btn btn-perm-tool expand-all"><i class="fas fa-chevron-down mr-1"></i> Expand</button>
                        <button type="button" class="btn btn-perm-tool collapse-all"><i class="fas fa-chevron-up mr-1"></i> Collapse</button>
                    </div>
                    <div class="role-input-wrapper mb-2"><label><i class="fas fa-shield-halved mr-1"></i> Module & Access Permissions</label></div>
                    <div class="accordion" id="accordionAddRole">
                        @foreach($groupedPermissions as $group => $subgroups)
                            @php $groupSlug = \Illuminate\Support\Str::slug($group); @endphp
                            <div class="card permissions-group mb-3">
                                <div class="card-header" id="heading-add-{{ $groupSlug }}">
                                    <a class="d-block text-decoration-none" data-toggle="collapse" href="#collapse-add-{{ $groupSlug }}" aria-expanded="true" aria-controls="collapse-add-{{ $groupSlug }}">
                                        <h6 class="mb-0 d-flex align-items-center justify-content-between">
                                            <span class="text-capitalize d-flex align-items-center">
                                                <i class="fas fa-layer-group text-primary mr-2"></i>
                                                {{ $group === 'pos' ? 'Point of Sale (POS)' : ($group === 'incomplete_orders' ? 'Incomplete Orders' : str_replace('_',' ', $group)) }}
                                            </span>
                                            <div class="d-flex align-items-center">
                                                <span class="badge-perm-count mr-3">{{ $subgroups->flatten()->count() }} permissions</span>
                                                <label class="m-0 small font-weight-bold text-muted" onclick="event.stopPropagation()"><input type="checkbox" class="select-all-group" data-group="{{ $groupSlug }}"> Select all</label>
                                            </div>
                                        </h6>
                                    </a>
                                </div>
                                <div id="collapse-add-{{ $groupSlug }}" class="collapse show" data-parent="#accordionAddRole">
                                <div class="card-body p-3 permissions-scroll bg-light">
                                    <div class="row">
                                        @foreach($subgroups as $sub => $perms)
                                            <div class="col-md-6 mb-3">
                                                <div class="subgroup-box">
                                                    <div class="subgroup-title">{{ $sub === 'core' ? $group : str_replace('_',' ', $sub) }}</div>
                                                    @foreach($perms as $permission)
                                                        <div class="perm-item-check">
                                                            <input class="form-check-input permission-checkbox" data-group="{{ $groupSlug }}" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="addroleperm_{{ $permission->id }}">
                                                            <label for="addroleperm_{{ $permission->id }}">{{ $permission->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 font-weight-bold"><i class="fas fa-plus mr-1"></i> Create Role</button>
                </div>
            </form>
        </div></div>
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

    // Enhanced permission filter in the open modal
    document.addEventListener('input', function(e){
        if (!e.target.classList.contains('permission-search')) return;
        const modal = e.target.closest('.modal-content');
        const term = e.target.value.trim().toLowerCase();

        modal.querySelectorAll('.permissions-group').forEach(groupCard => {
            const groupHeader = groupCard.querySelector('.card-header');
            const groupTitle = groupHeader ? groupHeader.textContent.toLowerCase() : '';
            const isGroupMatch = term !== '' && groupTitle.includes(term);

            let visibleCount = 0;
            groupCard.querySelectorAll('.perm-item-check, .form-check').forEach(row => {
                const label = row.querySelector('label');
                const txt = label ? label.textContent.toLowerCase() : '';
                const isMatch = term === '' || txt.includes(term) || isGroupMatch;
                row.style.display = isMatch ? '' : 'none';
                if (isMatch) visibleCount++;
            });

            // Hide/Show subgroup columns if all items inside are hidden
            groupCard.querySelectorAll('.col-md-6').forEach(col => {
                const totalItems = col.querySelectorAll('.perm-item-check, .form-check');
                let colVisibleCount = 0;
                totalItems.forEach(item => {
                    if (item.style.display !== 'none') colVisibleCount++;
                });
                col.style.display = (term !== '' && colVisibleCount === 0) ? 'none' : '';
            });

            // Hide whole card if no permissions match
            if (term === '') {
                groupCard.style.display = '';
            } else if (visibleCount > 0) {
                groupCard.style.display = '';
                const collapseEl = groupCard.querySelector('.collapse');
                if (collapseEl && typeof $ !== 'undefined') {
                    $(collapseEl).collapse('show');
                }
            } else {
                groupCard.style.display = 'none';
            }
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
