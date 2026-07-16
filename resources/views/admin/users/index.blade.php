@extends('layouts.master')

@section('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <style>
        /* Premium Admin Packages Styling */
        .premium-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            background: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .premium-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }
        .gradient-header {
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
            color: #fff;
            padding: 24px;
            position: relative;
        }
        .gradient-header.starter {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        .gradient-header.pro {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        }
        .gradient-header.enterprise {
            background: linear-gradient(135deg, #b45309 0%, #78350f 100%);
        }
        .gradient-header.lifetime {
            background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
        }
        .package-price-badge {
            position: absolute;
            bottom: -15px;
            right: 20px;
            background: #10b981;
            color: #fff;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }
        .feature-item {
            padding: 10px 0;
            border-bottom: 1px dashed #f1f5f9;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .feature-item:last-child {
            border-bottom: none;
        }
        .feature-included {
            color: #1e293b;
            font-weight: 500;
        }
        .feature-excluded {
            color: #94a3b8;
            text-decoration: line-through;
            opacity: 0.7;
        }
        .feature-icon-included {
            color: #10b981;
            margin-right: 10px;
            font-size: 16px;
        }
        .feature-icon-excluded {
            color: #ef4444;
            margin-right: 10px;
            font-size: 16px;
        }
        .stat-box {
            border-radius: 12px;
            padding: 20px;
            color: #fff;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .stat-box-blue {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        }
        .stat-box-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .stat-box-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.4;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                @if(request()->get('view') === 'packages')
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admin Packages</li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                @endif
            </ol>
        </nav>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(request()->get('view') === 'packages')
            <!-- ADMIN PACKAGES WORKSPACE -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Admin Packages</h4>
                    <p class="text-muted mb-0">Create and manage SaaS pricing packages and feature matrices</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#manageFeaturesModal">
                        <i class="fas fa-list-ul me-2"></i> Manage Features Pool
                    </button>
                    <button class="btn btn-primary rounded-pill px-4" onclick="openCreateModal()">
                        <i class="fas fa-plus me-2"></i> Create Package
                    </button>
                </div>
            </div>

            <!-- Stats Dashboard Row -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-box stat-box-blue">
                        <div>
                            <h5 class="mb-1" style="color: #fff;">Total Packages</h5>
                            <h2 class="mb-0 font-weight-bold" id="totalPackagesCount" style="color: #fff;">0</h2>
                        </div>
                        <i class="fas fa-box-open stat-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box stat-box-green">
                        <div>
                            <h5 class="mb-1" style="color: #fff;">Active Packages</h5>
                            <h2 class="mb-0 font-weight-bold" id="activePackagesCount" style="color: #fff;">0</h2>
                        </div>
                        <i class="fas fa-check-circle stat-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box stat-box-purple">
                        <div>
                            <h5 class="mb-1" style="color: #fff;">Features Pool</h5>
                            <h2 class="mb-0 font-weight-bold" id="totalFeaturesCount" style="color: #fff;">0</h2>
                        </div>
                        <i class="fas fa-magic stat-icon"></i>
                    </div>
                </div>
            </div>

            <!-- Packages Grid -->
            <div class="row" id="packagesGrid">
                <!-- Dynamic cards populated by script -->
            </div>

            <!-- Create/Edit Package Modal -->
            <div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                        <div class="modal-header bg-dark text-white border-0 py-3">
                            <h5 class="modal-title" id="packageModalLabel">Create New Package</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form id="packageForm">
                                <input type="hidden" id="packageId">
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="packageName" class="form-label font-weight-bold">Package Name</label>
                                        <input type="text" class="form-control" id="packageName" placeholder="e.g. Professional Plan" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="packageTheme" class="form-label font-weight-bold">Visual Theme</label>
                                        <select class="form-select" id="packageTheme">
                                            <option value="starter">Blue (Starter)</option>
                                            <option value="pro">Purple (Pro)</option>
                                            <option value="enterprise">Gold (Enterprise)</option>
                                            <option value="lifetime">Pink (Lifetime)</option>
                                            <option value="default">Dark Slate (Default)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="packageDetails" class="form-label font-weight-bold">Description / Details</label>
                                    <textarea class="form-control" id="packageDetails" rows="2" placeholder="Describe who this plan is suitable for..." required></textarea>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="priceMonthly" class="form-label font-weight-bold">Monthly Price (TK)</label>
                                        <input type="text" class="form-control" id="priceMonthly" placeholder="e.g. 2900" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="priceYearly" class="form-label font-weight-bold">Yearly Price (TK)</label>
                                        <input type="text" class="form-control" id="priceYearly" placeholder="e.g. 29000" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="priceLifetime" class="form-label font-weight-bold">Lifetime Price (TK)</label>
                                        <input type="text" class="form-control" id="priceLifetime" placeholder="e.g. 59000" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="packageStatus" checked>
                                        <label class="form-check-label font-weight-bold" for="packageStatus">Active Status</label>
                                    </div>
                                </div>

                                <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-tasks text-primary me-2"></i> Included/Excluded Features Matrix</h6>
                                <div class="row" id="modalFeaturesContainer">
                                    <!-- Dynamic feature check list populated by JS -->
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer border-0 bg-light p-3">
                            <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary px-4 rounded-pill" onclick="savePackage()">Save Package</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manage Features Pool Modal -->
            <div class="modal fade" id="manageFeaturesModal" tabindex="-1" aria-labelledby="manageFeaturesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0" style="border-radius: 16px; overflow: hidden;">
                        <div class="modal-header bg-secondary text-white border-0 py-3">
                            <h5 class="modal-title" id="manageFeaturesModalLabel">Manage Features Pool</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form id="newFeatureForm" onsubmit="addFeature(event)" class="mb-4">
                                <label for="newFeatureName" class="form-label font-weight-bold">Add New Feature</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="newFeatureName" placeholder="e.g. Premium Support, 24/7 Monitoring" required>
                                    <button class="btn btn-success" type="submit"><i class="fas fa-plus"></i> Add</button>
                                </div>
                            </form>

                            <h6 class="border-bottom pb-2 mb-3">Existing Features Pool</h6>
                            <ul class="list-group list-group-flush" id="featuresListContainer" style="max-height: 250px; overflow-y: auto;">
                                <!-- Populate via JS -->
                            </ul>
                        </div>
                        <div class="modal-footer border-0 bg-light p-3">
                            <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- ORIGINAL USERS WORKSPACE -->
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
                                @if($user->getRoleNames()->count())
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
        @endif
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

    @if(request()->get('view') === 'packages')
        <script>
            // MOCKED PERSISTENT PACKAGES MANAGEMENT SYSTEM
            const DEFAULT_FEATURES = [
                "1 Store Dashboard",
                "Unlimited Products",
                "Advanced Sales Reports",
                "Custom Domain Settings",
                "24/7 Priority Support",
                "Fraud Checker Integration",
                "WooCommerce Migration",
                "Custom Payment Gateways"
            ];

            const DEFAULT_PACKAGES = [
                {
                    id: "starter_plan",
                    name: "Starter Plan",
                    theme: "starter",
                    details: "Ideal for fresh startups and hobbyists looking to build their first online storefront.",
                    priceMonthly: "1200",
                    priceYearly: "12000",
                    priceLifetime: "30000",
                    status: true,
                    features: ["1 Store Dashboard", "Unlimited Products"]
                },
                {
                    id: "pro_plan",
                    name: "Professional Plan",
                    theme: "pro",
                    details: "Perfect for growing merchants and professional retailers needing premium tools.",
                    priceMonthly: "3500",
                    priceYearly: "35000",
                    priceLifetime: "80000",
                    status: true,
                    features: ["1 Store Dashboard", "Unlimited Products", "Advanced Sales Reports", "Fraud Checker Integration", "24/7 Priority Support"]
                },
                {
                    id: "enterprise_plan",
                    name: "Enterprise Ultimate",
                    theme: "enterprise",
                    details: "Tailored specifically for large-scale operations requiring absolute maximum horsepower.",
                    priceMonthly: "8500",
                    priceYearly: "85000",
                    priceLifetime: "200000",
                    status: true,
                    features: ["1 Store Dashboard", "Unlimited Products", "Advanced Sales Reports", "Custom Domain Settings", "24/7 Priority Support", "Fraud Checker Integration", "WooCommerce Migration", "Custom Payment Gateways"]
                }
            ];

            // LocalStorage Keys
            const FEATURES_KEY = "admin_packages_features_pool";
            const PACKAGES_KEY = "admin_packages_list";

            // State variables
            let featuresPool = [];
            let packagesList = [];

            // Initialize Data
            function initData() {
                const storedFeatures = localStorage.getItem(FEATURES_KEY);
                const storedPackages = localStorage.getItem(PACKAGES_KEY);

                if (!storedFeatures) {
                    localStorage.setItem(FEATURES_KEY, JSON.stringify(DEFAULT_FEATURES));
                    featuresPool = DEFAULT_FEATURES;
                } else {
                    featuresPool = JSON.parse(storedFeatures);
                }

                if (!storedPackages) {
                    localStorage.setItem(PACKAGES_KEY, JSON.stringify(DEFAULT_PACKAGES));
                    packagesList = DEFAULT_PACKAGES;
                } else {
                    packagesList = JSON.parse(storedPackages);
                }
            }

            // Render Dashboard Stats and Grid
            function renderWorkspace() {
                initData();
                
                // Update Counts
                document.getElementById('totalPackagesCount').textContent = packagesList.length;
                document.getElementById('activePackagesCount').textContent = packagesList.filter(p => p.status).length;
                document.getElementById('totalFeaturesCount').textContent = featuresPool.length;

                // Render Grid
                const grid = document.getElementById('packagesGrid');
                grid.innerHTML = '';

                if (packagesList.length === 0) {
                    grid.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" alt="Empty" width="100" style="opacity: 0.3;">
                            <h5 class="mt-3 text-muted">No Packages Found</h5>
                            <p class="text-muted">Get started by creating your first SaaS tier!</p>
                            <button class="btn btn-primary rounded-pill px-4 mt-2" onclick="openCreateModal()">Create Package</button>
                        </div>
                    `;
                    return;
                }

                packagesList.forEach(pkg => {
                    const cardCol = document.createElement('div');
                    cardCol.className = 'col-md-4 mb-4';

                    let featuresHTML = '';
                    featuresPool.forEach(feat => {
                        const isIncluded = pkg.features.includes(feat);
                        featuresHTML += `
                            <div class="feature-item ${isIncluded ? 'feature-included' : 'feature-excluded'}">
                                <i class="${isIncluded ? 'fas fa-check-circle feature-icon-included' : 'fas fa-times-circle feature-icon-excluded'}"></i>
                                <span>${feat}</span>
                            </div>
                        `;
                    });

                    cardCol.innerHTML = `
                        <div class="premium-card">
                            <div class="gradient-header ${pkg.theme || 'default'}">
                                <span class="badge ${pkg.status ? 'bg-success' : 'bg-secondary'} mb-2">${pkg.status ? 'Active' : 'Inactive'}</span>
                                <h4 class="mb-1 font-weight-bold" style="color: #fff;">${pkg.name}</h4>
                                <p class="small mb-0 opacity-80" style="color: rgba(255,255,255,0.85); min-height: 40px;">${pkg.details}</p>
                                <div class="package-price-badge">Monthly: TK ${pkg.priceMonthly}</div>
                            </div>
                            <div class="card-body p-4 pt-4 flex-grow-1">
                                <div class="row text-center mb-3 border-bottom pb-3">
                                    <div class="col-6 border-end">
                                        <span class="text-muted d-block small">Yearly Plan</span>
                                        <strong class="text-dark">TK ${pkg.priceYearly}</strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block small">Lifetime Plan</span>
                                        <strong class="text-dark">TK ${pkg.priceLifetime}</strong>
                                    </div>
                                </div>
                                <div class="features-list-wrapper mb-4">
                                    ${featuresHTML}
                                </div>
                            </div>
                            <div class="card-footer bg-light border-0 p-3 d-flex justify-content-between gap-2">
                                <button class="btn btn-outline-info rounded-pill px-3 flex-grow-1" onclick="openEditModal('${pkg.id}')">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                                <button class="btn btn-outline-danger rounded-pill px-3" onclick="deletePackage('${pkg.id}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    grid.appendChild(cardCol);
                });

                // Update the feature pool checklist inside Manage Features modal
                renderFeaturesPoolList();
            }

            // Render list inside features pool modal
            function renderFeaturesPoolList() {
                const container = document.getElementById('featuresListContainer');
                container.innerHTML = '';

                featuresPool.forEach((feat, idx) => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center px-0 py-2';
                    li.innerHTML = `
                        <span>${feat}</span>
                        <button class="btn btn-sm btn-link text-danger" onclick="deleteFeature(${idx})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    `;
                    container.appendChild(li);
                });
            }

            // Render features checklist in Package Form Modal
            function renderModalFeaturesChecklist(checkedFeatures = []) {
                const container = document.getElementById('modalFeaturesContainer');
                container.innerHTML = '';

                if (featuresPool.length === 0) {
                    container.innerHTML = `<p class="text-muted col-12">No features created in the features pool yet.</p>`;
                    return;
                }

                featuresPool.forEach((feat, idx) => {
                    const col = document.createElement('div');
                    col.className = 'col-md-6 mb-2';
                    const isChecked = checkedFeatures.includes(feat);
                    col.innerHTML = `
                        <div class="form-check">
                            <input class="form-check-input feature-checkbox" type="checkbox" value="${feat}" id="featCheck_${idx}" ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label text-dark" for="featCheck_${idx}">
                                ${feat}
                            </label>
                        </div>
                    `;
                    container.appendChild(col);
                });
            }

            // Add Feature to pool
            function addFeature(event) {
                event.preventDefault();
                const input = document.getElementById('newFeatureName');
                const name = input.value.trim();
                if (!name) return;

                if (featuresPool.includes(name)) {
                    Swal.fire('Error', 'Feature already exists in the pool!', 'error');
                    return;
                }

                featuresPool.push(name);
                localStorage.setItem(FEATURES_KEY, JSON.stringify(featuresPool));
                input.value = '';
                
                // Re-render
                renderWorkspace();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Feature added to pool',
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            // Delete Feature from pool
            function deleteFeature(idx) {
                const featToDelete = featuresPool[idx];
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Removing "${featToDelete}" will also exclude it from all current packages.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        featuresPool.splice(idx, 1);
                        localStorage.setItem(FEATURES_KEY, JSON.stringify(featuresPool));

                        // Clean up package assignments
                        packagesList.forEach(pkg => {
                            pkg.features = pkg.features.filter(f => f !== featToDelete);
                        });
                        localStorage.setItem(PACKAGES_KEY, JSON.stringify(packagesList));

                        renderWorkspace();
                        Swal.fire('Deleted!', 'Feature removed from pool.', 'success');
                    }
                });
            }

            // Open Create Modal
            function openCreateModal() {
                document.getElementById('packageModalLabel').textContent = 'Create New Package';
                document.getElementById('packageId').value = '';
                document.getElementById('packageForm').reset();
                document.getElementById('packageStatus').checked = true;
                
                renderModalFeaturesChecklist([]);
                
                const modal = new bootstrap.Modal(document.getElementById('packageModal'));
                modal.show();
            }

            // Open Edit Modal
            function openEditModal(pkgId) {
                const pkg = packagesList.find(p => p.id === pkgId);
                if (!pkg) return;

                document.getElementById('packageModalLabel').textContent = 'Edit Package';
                document.getElementById('packageId').value = pkg.id;
                document.getElementById('packageName').value = pkg.name;
                document.getElementById('packageTheme').value = pkg.theme || 'default';
                document.getElementById('packageDetails').value = pkg.details;
                document.getElementById('priceMonthly').value = pkg.priceMonthly;
                document.getElementById('priceYearly').value = pkg.priceYearly;
                document.getElementById('priceLifetime').value = pkg.priceLifetime;
                document.getElementById('packageStatus').checked = pkg.status;

                renderModalFeaturesChecklist(pkg.features);

                const modal = new bootstrap.Modal(document.getElementById('packageModal'));
                modal.show();
            }

            // Save Package (Create or Update)
            function savePackage() {
                const idInput = document.getElementById('packageId').value;
                const name = document.getElementById('packageName').value.trim();
                const theme = document.getElementById('packageTheme').value;
                const details = document.getElementById('packageDetails').value.trim();
                const priceMonthly = document.getElementById('priceMonthly').value.trim();
                const priceYearly = document.getElementById('priceYearly').value.trim();
                const priceLifetime = document.getElementById('priceLifetime').value.trim();
                const status = document.getElementById('packageStatus').checked;

                if (!name || !details || !priceMonthly || !priceYearly || !priceLifetime) {
                    Swal.fire('Validation Error', 'Please fill in all required fields.', 'error');
                    return;
                }

                // Gather checked features
                const selectedFeatures = [];
                document.querySelectorAll('.feature-checkbox:checked').forEach(cb => {
                    selectedFeatures.push(cb.value);
                });

                if (idInput) {
                    // Update
                    const pkgIdx = packagesList.findIndex(p => p.id === idInput);
                    if (pkgIdx !== -1) {
                        packagesList[pkgIdx] = {
                            id: idInput,
                            name,
                            theme,
                            details,
                            priceMonthly,
                            priceYearly,
                            priceLifetime,
                            status,
                            features: selectedFeatures
                        };
                    }
                } else {
                    // Create
                    const newId = 'pkg_' + Date.now();
                    packagesList.push({
                        id: newId,
                        name,
                        theme,
                        details,
                        priceMonthly,
                        priceYearly,
                        priceLifetime,
                        status,
                        features: selectedFeatures
                    });
                }

                localStorage.setItem(PACKAGES_KEY, JSON.stringify(packagesList));
                
                // Hide modal
                const modalEl = document.getElementById('packageModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                renderWorkspace();

                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully',
                    text: 'The SaaS tier was recorded successfully.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            // Delete Package
            function deletePackage(pkgId) {
                const pkg = packagesList.find(p => p.id === pkgId);
                if (!pkg) return;

                Swal.fire({
                    title: 'Delete Package?',
                    text: `Are you absolutely sure you want to remove "${pkg.name}"? This cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Delete Package'
                }).then((result) => {
                    if (result.isConfirmed) {
                        packagesList = packagesList.filter(p => p.id !== pkgId);
                        localStorage.setItem(PACKAGES_KEY, JSON.stringify(packagesList));
                        renderWorkspace();
                        Swal.fire('Deleted!', 'The package has been removed.', 'success');
                    }
                });
            }

            // Initial render on DOM load
            document.addEventListener('DOMContentLoaded', function() {
                renderWorkspace();
            });
        </script>
    @else
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
    @endif
@endsection
