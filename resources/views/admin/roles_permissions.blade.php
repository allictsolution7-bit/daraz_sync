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
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-bs-toggle="modal" data-target="#addRoleModal" data-bs-target="#addRoleModal"><i class="fas fa-plus"></i> Add Role</button>
            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-bs-toggle="modal" data-target="#addPermissionModal" data-bs-target="#addPermissionModal"><i class="fas fa-key"></i> Add Permission</button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(isset($allowedRoleNames))
        <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center justify-content-between text-dark" style="background:#e0f2fe; border-color:#bae6fd; border-radius:10px;">
            <span style="font-size:13px;"><i class="fas fa-shield-alt text-primary mr-2"></i> <strong>Role Management Scope:</strong> {{ $isSuperAdmin ? 'Super Admin Mode — Full Access to All Roles & Admins' : 'Admin Mode — Restricted Access (Store Manager, Vendor & Vendor Staff)' }}</span>
            <span class="badge bg-primary text-white" style="font-size:11px; padding: 5px 10px;">{{ count($allowedRoleNames) }} Visible Roles</span>
        </div>
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

    <!-- Add Permission Modal -->
    <div class="modal fade" id="addPermissionModal" tabindex="-1">
        <div class="modal-dialog"><div class="modal-content">
            <form method="POST" action="{{ route('admin.roles_permissions.permission.store') }}">
                @csrf
                <div class="modal-header modal-header-custom"><h5 class="modal-title"><i class="fas fa-key"></i> Add Permission</h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    <input type="text" name="name" class="form-control role-input-custom" placeholder="e.g. products.export, orders.refund" required>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="submit" class="btn btn-success btn-sm font-weight-bold">Create Permission</button>
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
            <a class="nav-link active" id="roles-tab" data-toggle="tab" data-bs-toggle="tab" href="#roles" role="tab" aria-controls="roles" aria-selected="true"><i class="fas fa-user-shield mr-1"></i> Roles Management</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="users-tab" data-toggle="tab" data-bs-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="false"><i class="fas fa-users mr-1"></i> {{ $isSuperAdmin ? 'Admins Access & Permissions' : 'Sub-Roles Access & Permissions' }}</a>
        </li>
        @if($isSuperAdmin)
        <li class="nav-item">
            <a class="nav-link" id="all-users-tab" data-toggle="tab" data-bs-toggle="tab" href="#all-users" role="tab" aria-controls="all-users" aria-selected="false"><i class="fas fa-globe mr-1"></i> All Database Users</a>
        </li>
        @endif
    </ul>

    <div class="tab-content" id="rolesPermissionsTabsContent">
        <!-- Roles Tab -->
        <div class="tab-pane fade show active" id="roles" role="tabpanel" aria-labelledby="roles-tab">
            <div class="rp-card mb-3">
                <div class="rp-card-header d-flex justify-content-between align-items-center">
                    <strong>Roles Management</strong>
                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-bs-toggle="modal" data-target="#addRoleModal" data-bs-target="#addRoleModal"><i class="fas fa-plus"></i> Add Role</button>
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
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-bs-toggle="modal" data-target="#editRoleModal{{ $role->id }}" data-bs-target="#editRoleModal{{ $role->id }}">Edit</button>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-bs-toggle="modal" data-target="#deleteRoleModal{{ $role->id }}" data-bs-target="#deleteRoleModal{{ $role->id }}">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-muted text-center py-3">No roles found.</td>
                        </tr>
                    @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Users Access & Permissions Tab -->
        <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
            <div class="rp-card mb-3">
                <div class="rp-card-header d-flex align-items-center justify-content-between">
                    <strong>{{ $isSuperAdmin ? 'Admins List (Manageable by Super Admin)' : 'Sub-Roles Users List (Shop Manager, Vendor, Vendor Staff)' }}</strong>


                    <div class="d-flex align-items-center" style="gap:8px;">
                        <input id="userSearch" type="text" class="form-control form-control-sm" placeholder="Search by name, email, or phone..." style="max-width:300px;">
                    </div>
                </div>
                <ul class="list-group list-group-flush" id="allUsersList">
                    @forelse($users as $u)
                        <li class="list-group-item d-flex justify-content-between align-items-center user-item" data-name="{{ strtolower($u->name) }}" data-email="{{ strtolower($u->email) }}" data-phone="{{ strtolower($u->phone ?? '') }}">
                            <div>
                                <strong>{{ $u->name }}</strong> 
                                <span class="text-muted small">({{ $u->email }} @if($u->phone) • {{ $u->phone }} @endif)</span><br>
                                <span class="small">Roles:
                                    @forelse ($u->roles as $role)
                                        <span class="badge bg-primary text-white rounded-pill mr-1">{{ $role->name }}</span>
                                    @empty
                                        <span class="badge bg-secondary rounded-pill">No Role</span>
                                    @endforelse
                                </span>
                                @if($u->permissions->count() > 0)
                                    <span class="badge bg-warning text-dark ml-2 rounded-pill"><i class="fas fa-key mr-1"></i> {{ $u->permissions->count() }} Custom Permissions</span>
                                @endif
                            </div>
                            <div class="btn-group">
                                @if(Route::has('admin.users.edit'))
                                    <a href="{{ route('admin.users.edit', ['id' => $u->id]) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-user-edit mr-1"></i> Edit User</a>
                                @elseif(Route::has('users.edit'))
                                    <a href="{{ route('users.edit', ['id' => $u->id]) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-user-edit mr-1"></i> Edit User</a>
                                @endif
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-bs-toggle="modal" data-target="#editUserRolesModal{{ $u->id }}" data-bs-target="#editUserRolesModal{{ $u->id }}"><i class="fas fa-user-tag mr-1"></i> Edit Roles</button>
                                <button class="btn btn-outline-dark btn-sm" data-toggle="modal" data-bs-toggle="modal" data-target="#editUserPermissionsModal{{ $u->id }}" data-bs-target="#editUserPermissionsModal{{ $u->id }}"><i class="fas fa-key mr-1"></i> Custom Permissions</button>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No users found.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        @if($isSuperAdmin)
        <!-- All Database Users Tab (Super Admin Only) -->
        <div class="tab-pane fade" id="all-users" role="tabpanel" aria-labelledby="all-users-tab">
            <div class="rp-card mb-3">
                <div class="rp-card-header d-flex align-items-center justify-content-between">
                    <strong>All System Users in Database ({{ $allUsers->count() }})</strong>
                    <div class="d-flex align-items-center" style="gap:8px;">
                        <input id="allUserSearch" type="text" class="form-control form-control-sm" placeholder="Search by name, email, or phone..." style="max-width:300px;">
                    </div>
                </div>
                <ul class="list-group list-group-flush" id="globalUsersList">
                    @forelse($allUsers as $u)
                        <li class="list-group-item d-flex justify-content-between align-items-center user-item" data-name="{{ strtolower($u->name) }}" data-email="{{ strtolower($u->email) }}" data-phone="{{ strtolower($u->phone ?? '') }}">
                            <div>
                                <strong>{{ $u->name }}</strong> 
                                <span class="text-muted small">({{ $u->email }} @if($u->phone) • {{ $u->phone }} @endif)</span><br>
                                <span class="small">Roles:
                                    @forelse ($u->roles as $role)
                                        <span class="badge bg-primary text-white rounded-pill mr-1">{{ $role->name }}</span>
                                    @empty
                                        <span class="badge bg-secondary rounded-pill">No Role</span>
                                    @endforelse
                                </span>
                                @if($u->permissions->count() > 0)
                                    <span class="badge bg-warning text-dark ml-2 rounded-pill"><i class="fas fa-key mr-1"></i> {{ $u->permissions->count() }} Custom Permissions</span>
                                @endif
                            </div>
                            <div class="btn-group">
                                @if(Route::has('admin.users.edit'))
                                    <a href="{{ route('admin.users.edit', ['id' => $u->id]) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-user-edit mr-1"></i> Edit User</a>
                                @elseif(Route::has('users.edit'))
                                    <a href="{{ route('users.edit', ['id' => $u->id]) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-user-edit mr-1"></i> Edit User</a>
                                @endif
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-bs-toggle="modal" data-target="#editUserRolesModal{{ $u->id }}" data-bs-target="#editUserRolesModal{{ $u->id }}"><i class="fas fa-user-tag mr-1"></i> Edit Roles</button>
                                <button class="btn btn-outline-dark btn-sm" data-toggle="modal" data-bs-toggle="modal" data-target="#editUserPermissionsModal{{ $u->id }}" data-bs-target="#editUserPermissionsModal{{ $u->id }}"><i class="fas fa-key mr-1"></i> Custom Permissions</button>
                            </div>
                        </li>

                    @empty
                        <li class="list-group-item text-muted">No users found in database.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        @endif
    </div>



    <!-- ALL ROLE MODALS -->
    @foreach($roles as $role)
        <!-- Edit Role Modal -->
        <div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
                <form method="POST" action="{{ route('admin.roles_permissions.role.update', $role) }}">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title"><i class="fas fa-user-pen" style="color: #60a5fa;"></i> Edit Role — <span class="text-info">{{ $role->name }}</span></h5>
                        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
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
                            <button type="button" class="btn btn-perm-tool copy-perms"><i class="fas fa-copy mr-1"></i> Copy (Export)</button>
                            <button type="button" class="btn btn-perm-tool paste-perms"><i class="fas fa-paste mr-1"></i> Paste (Import)</button>
                            <button type="button" class="btn btn-perm-tool expand-all"><i class="fas fa-chevron-down mr-1"></i> Expand</button>
                            <button type="button" class="btn btn-perm-tool collapse-all"><i class="fas fa-chevron-up mr-1"></i> Collapse</button>
                        </div>
                        <div class="role-input-wrapper mb-2"><label><i class="fas fa-shield-halved mr-1"></i> Module & Access Permissions</label></div>
                        <div class="accordion" id="accordionRole{{ $role->id }}">
                            @foreach($groupedPermissions as $group => $subgroups)
                                @php $groupSlug = \Illuminate\Support\Str::slug($group); @endphp
                                <div class="card permissions-group mb-3">
                                    <div class="card-header d-flex align-items-center justify-content-between py-2 px-3" id="heading-role-{{ $role->id }}-{{ $groupSlug }}">
                                        <a class="text-decoration-none d-flex align-items-center flex-grow-1" data-toggle="collapse" data-bs-toggle="collapse" href="#collapse-role-{{ $role->id }}-{{ $groupSlug }}" aria-expanded="true">
                                            <h6 class="mb-0 text-capitalize d-flex align-items-center">
                                                <i class="fas fa-layer-group text-primary mr-2 me-2"></i>
                                                {{ $group === 'pos' ? 'Point of Sale (POS)' : str_replace('_',' ', $group) }}
                                            </h6>
                                        </a>
                                        <div class="d-flex align-items-center">
                                            <span class="badge-perm-count mr-3 me-3">{{ $subgroups->flatten()->count() }} permissions</span>
                                            <label class="m-0 small font-weight-bold text-muted cursor-pointer d-flex align-items-center" style="user-select: none;">
                                                <input type="checkbox" class="select-all-group mr-1 me-1" data-group="{{ $groupSlug }}"> Select All
                                            </label>
                                        </div>
                                    </div>
                                    <div id="collapse-role-{{ $role->id }}-{{ $groupSlug }}" class="collapse show">
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
                        <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
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
                    <div class="modal-header modal-header-custom"><h5 class="modal-title">Delete Role</h5>
                        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body p-4">Are you sure you want to delete the <strong>{{ $role->name }}</strong> role?</div>
                    <div class="modal-footer modal-footer-custom">
                        <button type="submit" class="btn btn-danger btn-sm font-weight-bold">Delete Role</button>
                    </div>
                </form>
            </div></div>
        </div>
    @endforeach

    <!-- ALL USER MODALS -->
    @php $targetModalUsers = ($isSuperAdmin && isset($allUsers) && $allUsers->count() > 0) ? $allUsers : $users; @endphp
    @foreach($targetModalUsers as $user)

        <!-- Edit User Roles Modal -->
        <div class="modal fade" id="editUserRolesModal{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
                <form method="POST" action="{{ route('admin.roles_permissions.user_roles.update', $user) }}">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title"><i class="fas fa-user-tag" style="color: #60a5fa;"></i> Assign Roles to {{ $user->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body p-4">
                        @foreach($roles as $role)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="userrole_{{ $user->id }}_{{ $role->id }}" {{ $user->roles->pluck('name')->contains($role->name) ? 'checked' : '' }}>
                                <label class="form-check-label font-weight-bold" for="userrole_{{ $user->id }}_{{ $role->id }}">{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer modal-footer-custom">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm font-weight-bold">Update Roles</button>
                    </div>
                </form>
            </div></div>
        </div>

        <!-- Edit User Direct Custom Permissions Modal -->
        <div class="modal fade" id="editUserPermissionsModal{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
                <form method="POST" action="{{ route('admin.roles_permissions.user_permissions.update', $user) }}">
                    @csrf
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title"><i class="fas fa-key" style="color: #f59e0b;"></i> Custom Direct Permissions — <span class="text-info">{{ $user->name }}</span></h5>
                        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-warning py-2 px-3 small mb-3">
                            <i class="fas fa-info-circle mr-1"></i> Permissions inherited from assigned roles are pre-checked and marked with <strong>Default (Role)</strong>. You can grant additional custom permissions below for this specific user.
                        </div>

                        @if($user->hasRole('vendor') || $user->vendorSettings)
                        <div class="card border-primary mb-3" style="background: #f0f7ff; border: 1px solid #bfdbfe !important;">
                            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 text-primary font-weight-bold"><i class="fas fa-boxes mr-1"></i> Parent Admin Product Access Toggle</h6>
                                    <small class="text-muted">Allow this vendor to view and copy products created by their parent Admin directly into their store catalog.</small>
                                </div>
                                <div class="form-check form-switch ms-3">
                                    <input type="checkbox" class="form-check-input" id="toggleAdminProducts{{ $user->id }}" name="can_access_admin_products" value="1" style="width: 2.5em; height: 1.25em; cursor: pointer;" {{ ($user->vendorSettings?->can_access_admin_products || (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('vendor.access_admin_products'))) ? 'checked' : '' }}>
                                    <label class="form-check-label font-weight-bold ms-2" for="toggleAdminProducts{{ $user->id }}">Enable Access</label>
                                </div>
                            </div>
                        </div>
                        @endif
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
                        <div class="role-input-wrapper mb-2"><label><i class="fas fa-shield-halved mr-1"></i> Custom Direct & Role Permissions</label></div>

                        @php
                            $rolePermissions = $user->roles ? $user->roles->flatMap(function($r) {
                                return $r->permissions ? $r->permissions->pluck('name') : collect();
                            })->unique()->toArray() : [];

                            $directPermissions = $user->permissions ? $user->permissions->pluck('name')->toArray() : [];
                            
                            if (empty($rolePermissions) && method_exists($user, 'getPermissionsViaRoles')) {
                                $rolePermissions = $user->getPermissionsViaRoles()->pluck('name')->toArray();
                            }
                            if (empty($directPermissions) && method_exists($user, 'getDirectPermissionNames')) {
                                $directPermissions = $user->getDirectPermissionNames()->toArray();
                            }
                        @endphp
                        <div class="accordion" id="accordionUserPerm{{ $user->id }}">
                            @foreach($groupedPermissions as $group => $subgroups)
                                @php $groupSlug = \Illuminate\Support\Str::slug($group); @endphp
                                <div class="card permissions-group mb-3">
                                    <div class="card-header d-flex align-items-center justify-content-between py-2 px-3" id="heading-uperm-{{ $user->id }}-{{ $groupSlug }}">
                                        <a class="text-decoration-none d-flex align-items-center flex-grow-1" data-toggle="collapse" data-bs-toggle="collapse" href="#collapse-uperm-{{ $user->id }}-{{ $groupSlug }}" aria-expanded="true">
                                            <h6 class="mb-0 text-capitalize d-flex align-items-center">
                                                <i class="fas fa-layer-group text-primary mr-2 me-2"></i>
                                                {{ $group === 'pos' ? 'Point of Sale (POS)' : str_replace('_',' ', $group) }}
                                            </h6>
                                        </a>
                                        <div class="d-flex align-items-center">
                                            <span class="badge-perm-count mr-3 me-3">{{ $subgroups->flatten()->count() }} permissions</span>
                                            <label class="m-0 small font-weight-bold text-muted cursor-pointer d-flex align-items-center" style="user-select: none;">
                                                <input type="checkbox" class="select-all-group mr-1 me-1" data-group="{{ $groupSlug }}"> Select All
                                            </label>
                                        </div>
                                    </div>
                                    <div id="collapse-uperm-{{ $user->id }}-{{ $groupSlug }}" class="collapse show">
                                        <div class="card-body p-3 permissions-scroll bg-light">
                                            <div class="row">
                                                @foreach($subgroups as $sub => $perms)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="subgroup-box">
                                                            <div class="subgroup-title">{{ $sub === 'core' ? $group : str_replace('_',' ', $sub) }}</div>
                                                            @foreach($perms as $permission)
                                                                @php
                                                                    $isRolePerm = in_array($permission->name, $rolePermissions);
                                                                    $isDirectPerm = in_array($permission->name, $directPermissions);
                                                                    $isChecked = $isRolePerm || $isDirectPerm;
                                                                @endphp
                                                                <div class="perm-item-check" style="{{ $isRolePerm ? 'background: #f0fdf4; border-radius:4px;' : '' }}">
                                                                    <input class="form-check-input permission-checkbox" data-group="{{ $groupSlug }}" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="uperm_{{ $user->id }}_{{ $permission->id }}" {{ $isChecked ? 'checked' : '' }}>
                                                                    <label for="uperm_{{ $user->id }}_{{ $permission->id }}">
                                                                        {{ $permission->name }}
                                                                        @if($isRolePerm)
                                                                            <span class="badge bg-success text-white ml-1" style="font-size: 9px; font-weight: normal;">Default (Role)</span>
                                                                        @elseif($isDirectPerm)
                                                                            <span class="badge bg-warning text-dark ml-1" style="font-size: 9px; font-weight: normal;">Custom Extra</span>
                                                                        @endif
                                                                    </label>
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
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning btn-sm font-weight-bold text-dark"><i class="fas fa-save mr-1"></i> Save Custom Permissions</button>
                    </div>
                </form>
            </div></div>
        </div>
    @endforeach

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable"><div class="modal-content">
            <form method="POST" action="{{ route('admin.roles_permissions.role.store') }}">
                @csrf
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title"><i class="fas fa-plus-circle" style="color: #60a5fa;"></i> Create New Role</h5>
                    <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
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
                        <button type="button" class="btn btn-perm-tool copy-perms"><i class="fas fa-copy mr-1"></i> Copy (Export)</button>
                        <button type="button" class="btn btn-perm-tool paste-perms"><i class="fas fa-paste mr-1"></i> Paste (Import)</button>
                        <button type="button" class="btn btn-perm-tool expand-all"><i class="fas fa-chevron-down mr-1"></i> Expand</button>
                        <button type="button" class="btn btn-perm-tool collapse-all"><i class="fas fa-chevron-up mr-1"></i> Collapse</button>
                    </div>
                    <div class="role-input-wrapper mb-2"><label><i class="fas fa-shield-halved mr-1"></i> Module & Access Permissions</label></div>
                    <div class="accordion" id="accordionAddRole">
                        @foreach($groupedPermissions as $group => $subgroups)
                            @php $groupSlug = \Illuminate\Support\Str::slug($group); @endphp
                            <div class="card permissions-group mb-3">
                                <div class="card-header d-flex align-items-center justify-content-between py-2 px-3" id="heading-add-{{ $groupSlug }}">
                                    <a class="text-decoration-none d-flex align-items-center flex-grow-1" data-toggle="collapse" data-bs-toggle="collapse" href="#collapse-add-{{ $groupSlug }}" aria-expanded="true">
                                        <h6 class="mb-0 text-capitalize d-flex align-items-center">
                                            <i class="fas fa-layer-group text-primary mr-2 me-2"></i>
                                            {{ $group === 'pos' ? 'Point of Sale (POS)' : str_replace('_',' ', $group) }}
                                        </h6>
                                    </a>
                                    <div class="d-flex align-items-center">
                                        <span class="badge-perm-count mr-3 me-3">{{ $subgroups->flatten()->count() }} permissions</span>
                                        <label class="m-0 small font-weight-bold text-muted cursor-pointer d-flex align-items-center" style="user-select: none;">
                                            <input type="checkbox" class="select-all-group mr-1 me-1" data-group="{{ $groupSlug }}"> Select All
                                        </label>
                                    </div>
                                </div>
                                <div id="collapse-add-{{ $groupSlug }}" class="collapse show">
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
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-dismiss="modal" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 font-weight-bold"><i class="fas fa-plus mr-1"></i> Create Role</button>
                </div>
            </form>
        </div></div>
    </div>
</div>
@endsection 

@push('scripts')
<script>
    // Select all permissions within a specific group card
    document.addEventListener('change', function(e){
        if (e.target && e.target.classList.contains('select-all-group')) {
            const card = e.target.closest('.card.permissions-group');
            if (card) {
                const checked = e.target.checked;
                card.querySelectorAll("input.permission-checkbox").forEach(cb => cb.checked = checked);
            }
        }
        if (e.target && e.target.classList.contains('permission-checkbox')) {
            const card = e.target.closest('.card.permissions-group');
            if (card) {
                const groupCheckbox = card.querySelector('.select-all-group');
                if (groupCheckbox) {
                    const allCBs = card.querySelectorAll('.permission-checkbox');
                    const checkedCBs = card.querySelectorAll('.permission-checkbox:checked');
                    groupCheckbox.checked = (allCBs.length > 0 && allCBs.length === checkedCBs.length);
                }
            }
        }
    });

    // Global select/clear/expand/collapse in each modal
    document.addEventListener('click', function(e){
        const modal = e.target.closest('.modal-content');
        if (!modal) return;

        if (e.target.classList.contains('select-all-perms') || e.target.closest('.select-all-perms')) {
            modal.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
            modal.querySelectorAll('.select-all-group').forEach(cb => cb.checked = true);
        }
        if (e.target.classList.contains('clear-all-perms') || e.target.closest('.clear-all-perms')) {
            modal.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
            modal.querySelectorAll('.select-all-group').forEach(cb => cb.checked = false);
        }
        if (e.target.classList.contains('expand-all') || e.target.closest('.expand-all')) {
            modal.querySelectorAll('.collapse').forEach(el => $(el).collapse('show'));
        }
        if (e.target.classList.contains('collapse-all') || e.target.closest('.collapse-all')) {
            modal.querySelectorAll('.collapse').forEach(el => $(el).collapse('hide'));
        }
        if (e.target.classList.contains('copy-perms') || e.target.closest('.copy-perms')) {
            e.preventDefault();
            const permNames = Array.from(modal.querySelectorAll('.permission-checkbox:checked')).map(cb => cb.value);
            localStorage.setItem('copied_permissions', JSON.stringify(permNames));
            if (permNames.length === 0) {
                const msg = 'No permissions selected. Copied empty list.';
                if (typeof toastr !== 'undefined' && typeof toastr.warning === 'function') {
                    toastr.warning(msg);
                } else {
                    alert(msg);
                }
            } else {
                const msg = `${permNames.length} permissions copied to clipboard.`;
                if (typeof toastr !== 'undefined' && typeof toastr.success === 'function') {
                    toastr.success(msg);
                } else {
                    alert(msg);
                }
            }
        }
        if (e.target.classList.contains('paste-perms') || e.target.closest('.paste-perms')) {
            e.preventDefault();
            const stored = localStorage.getItem('copied_permissions');
            if (stored) {
                const permNames = JSON.parse(stored);
                modal.querySelectorAll('.permission-checkbox').forEach(cb => {
                    const newValue = permNames.includes(cb.value);
                    cb.checked = newValue;
                    cb.dispatchEvent(new Event('change', { bubbles: true }));
                });
                modal.querySelectorAll('.permissions-group').forEach(card => {
                    const groupCheckbox = card.querySelector('.select-all-group');
                    if (groupCheckbox) {
                        const allCBs = card.querySelectorAll('.permission-checkbox');
                        const checkedCBs = card.querySelectorAll('.permission-checkbox:checked');
                        const newGroupValue = (allCBs.length > 0 && allCBs.length === checkedCBs.length);
                        if (groupCheckbox.checked !== newGroupValue) {
                            groupCheckbox.checked = newGroupValue;
                            groupCheckbox.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                });
                const msg = `Successfully pasted ${permNames.length} permissions.`;
                if (typeof toastr !== 'undefined' && typeof toastr.success === 'function') {
                    toastr.success(msg);
                } else {
                    alert(msg);
                }
            } else {
                const msg = 'No copied permissions found in clipboard.';
                if (typeof toastr !== 'undefined' && typeof toastr.warning === 'function') {
                    toastr.warning(msg);
                } else {
                    alert(msg);
                }
            }
        }
    });

    // Enhanced permission filter in the open modal
    document.addEventListener('input', function(e){
        if (!e.target.classList.contains('permission-search') && !e.target.classList.contains('permission-search-custom')) return;
        const modal = e.target.closest('.modal-content');
        if (!modal) return;

        const term = e.target.value.trim().toLowerCase();

        modal.querySelectorAll('.permissions-group').forEach(groupCard => {
            const groupCategoryTitle = groupCard.querySelector('.card-header .text-capitalize');
            const categoryText = groupCategoryTitle ? groupCategoryTitle.textContent.toLowerCase() : '';
            const isCategoryMatch = term !== '' && categoryText.includes(term);

            let visibleCount = 0;
            groupCard.querySelectorAll('.perm-item-check, .form-check').forEach(row => {
                const label = row.querySelector('label');
                const input = row.querySelector('input');
                const txt = (label ? label.textContent : '') + ' ' + (input ? input.value : '');
                const isMatch = term === '' || txt.toLowerCase().includes(term) || isCategoryMatch;
                row.style.display = isMatch ? '' : 'none';
                if (isMatch) visibleCount++;
            });

            groupCard.querySelectorAll('.col-md-6').forEach(col => {
                const totalItems = col.querySelectorAll('.perm-item-check, .form-check');
                let colVisibleCount = 0;
                totalItems.forEach(item => {
                    if (item.style.display !== 'none') colVisibleCount++;
                });
                col.style.display = (term !== '' && colVisibleCount === 0) ? 'none' : '';
            });

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

    // User search by name, email or phone across user lists
    document.addEventListener('input', function(e){
        if (e.target.id !== 'userSearch' && e.target.id !== 'allUserSearch') return;
        const targetListId = e.target.id === 'allUserSearch' ? '#globalUsersList' : '#allUsersList';
        const term = e.target.value.trim().toLowerCase();
        document.querySelectorAll(targetListId + ' .user-item').forEach(li => {
            const name = (li.getAttribute('data-name') || '').toLowerCase();
            const email = (li.getAttribute('data-email') || '').toLowerCase();
            const phone = (li.getAttribute('data-phone') || '').toLowerCase();
            li.style.display = (name.includes(term) || email.includes(term) || phone.includes(term)) ? '' : 'none';
        });
    });


</script>
@endpush
