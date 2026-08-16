@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-slate-800 font-bold" style="font-weight: 700; color: #1e293b;">SaaS Tenant Domains</h1>
            <p class="text-muted mb-0 small">Manage tenant subdomains and their database connections for the SaaS system.</p>
        </div>
        <button type="button" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addTenantModal" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none;">
            <i class="fas fa-plus"></i> Add Subdomain
        </button>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert" style="background-color: #ecfdf5; color: #065f46;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 font-semibold" style="color: #334155; font-weight: 600;">Registered Subdomains</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-muted" style="background-color: #f8fafc;">
                    <tr>
                        <th class="ps-4 py-3">Tenant Name</th>
                        <th class="py-3">Subdomain URL</th>
                        <th class="py-3">Database Name</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-indigo-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background-color: rgba(79, 70, 229, 0.08); color: #4f46e5; font-weight: 600;">
                                        {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-slate-700" style="font-weight: 600; color: #334155;">{{ $tenant->name }}</h6>
                                        <span class="text-muted small">ID: {{ $tenant->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="http://{{ $tenant->subdomain }}.{{ str_replace('127.0.0.1', 'localhost', request()->getHttpHost()) }}" target="_blank" class="text-decoration-none font-medium" style="color: #4f46e5; font-weight: 500;">
                                    {{ $tenant->subdomain }}.{{ str_replace('127.0.0.1', 'localhost', request()->getHttpHost()) }}
                                    <i class="fas fa-external-link-alt ms-1 small"></i>
                                </a>
                            </td>
                            <td>
                                <code class="px-2 py-1 bg-light rounded text-dark fs-7">{{ $tenant->db_name ?: 'purnobd_' . $tenant->subdomain }}</code>
                            </td>
                            <td>
                                @if($tenant->is_active)
                                    <span class="badge px-2.5 py-1.5 rounded-pill" style="background-color: #ecfdf5; color: #047857; font-weight: 600; font-size: 11px;">Active</span>
                                @else
                                    <span class="badge px-2.5 py-1.5 rounded-pill" style="background-color: #fef2f2; color: #b91c1c; font-weight: 600; font-size: 11px;">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-sm btn-light border-0 me-2 rounded-3" data-bs-toggle="modal" data-bs-target="#editTenantModal{{ $tenant->id }}" style="background-color: #f1f5f9; color: #475569;">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form action="{{ route('admin.saas-tenants.destroy', $tenant->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this subdomain? This won\'t delete the actual tenant database, but will remove it from the superadmin listing.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border-0 text-danger rounded-3" style="background-color: #fef2f2;">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Tenant Modal -->
                        <div class="modal fade" id="editTenantModal{{ $tenant->id }}" tabindex="-1" aria-labelledby="editTenantModalLabel{{ $tenant->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header border-0 bg-light py-3">
                                        <h5 class="modal-title font-bold" id="editTenantModalLabel{{ $tenant->id }}" style="font-weight: 700; color: #1e293b;">Edit Tenant</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.saas-tenants.update', $tenant->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label font-semibold small text-muted" style="font-weight: 600;">Tenant Name</label>
                                                <input type="text" name="name" class="form-control rounded-3" value="{{ $tenant->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label font-semibold small text-muted" style="font-weight: 600;">Subdomain (e.g. 'wholesale')</label>
                                                <div class="input-group">
                                                    <input type="text" name="subdomain" class="form-control" value="{{ $tenant->subdomain }}" required>
                                                    <span class="input-group-text bg-light text-muted small">.{{ str_replace('127.0.0.1', 'localhost', request()->getHttpHost()) }}</span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label font-semibold small text-muted" style="font-weight: 600;">Database Name</label>
                                                <input type="text" name="db_name" class="form-control rounded-3" value="{{ $tenant->db_name }}" placeholder="purnobd_{{ $tenant->subdomain }}">
                                                <div class="form-text text-muted small">Leave blank to auto-detect using `purnobd_[subdomain]`.</div>
                                            </div>
                                            <div class="mb-3 form-check form-switch mt-4">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active{{ $tenant->id }}" {{ $tenant->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label font-semibold text-slate-700" for="edit_is_active{{ $tenant->id }}" style="font-weight: 600;">Active Status</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 p-3 bg-light">
                                            <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-3 px-4" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none;">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-network-wired text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <h5 class="text-slate-600 mb-1" style="font-weight: 600;">No Subdomains Configured</h5>
                                    <p class="text-muted small">Get started by creating your first SaaS tenant subdomain entry.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tenants->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $tenants->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Tenant Modal -->
<div class="modal fade" id="addTenantModal" tabindex="-1" aria-labelledby="addTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-light py-3">
                <h5 class="modal-title font-bold" id="addTenantModalLabel" style="font-weight: 700; color: #1e293b;">Register New Tenant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.saas-tenants.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label font-semibold small text-muted" style="font-weight: 600;">Tenant Name</label>
                        <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. Dhaka Branch Wholesellers" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold small text-muted" style="font-weight: 600;">Subdomain Prefix</label>
                        <div class="input-group">
                            <input type="text" name="subdomain" class="form-control" placeholder="e.g. dhaka-wholesale" required>
                            <span class="input-group-text bg-light text-muted small">.{{ str_replace('127.0.0.1', 'localhost', request()->getHttpHost()) }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-semibold small text-muted" style="font-weight: 600;">Database Name</label>
                        <input type="text" name="db_name" class="form-control rounded-3" placeholder="e.g. purnobd_dhaka">
                        <div class="form-text text-muted small">Leave blank to automatically default to `purnobd_[subdomain]`.</div>
                    </div>
                    <div class="mb-3 form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                        <label class="form-check-label font-semibold text-slate-700" for="is_active" style="font-weight: 600;">Mark as Active</label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-3 bg-light">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none;">Create Subdomain</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
