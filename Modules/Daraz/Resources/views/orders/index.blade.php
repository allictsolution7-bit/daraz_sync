@extends(request()->is('vendor/*') ? 'vendor.layouts.app' : 'layouts.master')

@section('title', 'Daraz Orders')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #daraz-orders-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background: #f8fafc;
        min-height: 100vh;
        padding: 20px 0;
    }

    .hdr-panel {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .hdr-title h4 { font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 4px; }
    .hdr-title p { font-size: 0.85rem; color: #64748b; margin: 0; }

    /* Filter Card */
    .filter-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    /* Tabs */
    .nav-tabs-daraz {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 20px;
        display: flex;
        gap: 8px;
        overflow-x: auto;
    }
    .nav-tabs-daraz .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 10px 16px;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease;
        white-space: nowrap;
        background: transparent;
    }
    .nav-tabs-daraz .nav-link.active {
        color: #f57224; /* Daraz Orange */
        border-bottom-color: #f57224;
        background: transparent;
    }

    /* Order Card Item */
    .order-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 16px;
        overflow: hidden;
        transition: box-shadow 0.2s ease;
    }
    .order-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .order-card-header {
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        flex-wrap: wrap;
        gap: 12px;
    }
    .order-card-body {
        padding: 16px 20px;
    }

    /* Print Badge Buttons */
    .btn-doc-print {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 6px;
        text-decoration: none !important;
        transition: all 0.15s ease;
    }
    .btn-doc-awb {
        background-color: #e0f2fe;
        color: #0284c7;
        border: 1px solid #bae6fd;
    }
    .btn-doc-awb:hover {
        background-color: #0284c7;
        color: #ffffff;
    }

    .btn-doc-invoice {
        background-color: #e0e7ff;
        color: #4f46e5;
        border: 1px solid #c7d2fe;
    }
    .btn-doc-invoice:hover {
        background-color: #4f46e5;
        color: #ffffff;
    }

    .btn-doc-picklist {
        background-color: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }
    .btn-doc-picklist:hover {
        background-color: #d97706;
        color: #ffffff;
    }

    .badge-status {
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-pending { background: #fff7ed; color: #c2410c; border: 1px solid #ffedd5; }
    .status-ready_to_ship { background: #eff6ff; color: #1d4ed8; border: 1px solid #dbeafe; }
    .status-shipped { background: #f0fdf4; color: #15803d; border: 1px solid #dcfce7; }
    .status-delivered { background: #059669; color: #ffffff; }
    .status-canceled { background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; }

    .item-img {
        width: 54px;
        height: 54px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .spinner-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        border-radius: 12px;
    }
</style>
@endsection

@section('content')
<div id="daraz-orders-page" class="container-fluid">

    <!-- Header Panel -->
    <div class="hdr-panel">
        <div class="hdr-title">
            <h4>Daraz Orders & Document Management</h4>
            <p>Sync live orders, view item details, and download or print AWB, Invoices, and PickLists.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button id="btn-sync-orders" class="btn btn-warning btn-sm fw-bold px-3 py-2 text-dark shadow-sm">
                <i class="fas fa-rotate me-1"></i> Sync / Fetch Orders
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="filter-card">
        <form id="filter-form" class="row g-3 align-items-center">
            <div class="col-md-3">
                <label class="form-label fw-bold fs-7 mb-1 text-secondary">Daraz Store Channel</label>
                <select name="store_id" id="select-store" class="form-select form-select-sm">
                    @forelse($stores as $st)
                        <option value="{{ $st->id }}" {{ $st->id == $selectedStoreId ? 'selected' : '' }}>
                            {{ $st->name }} ({{ $st->app_key }})
                        </option>
                    @empty
                        <option value="">No Active Stores Available</option>
                    @endforelse
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold fs-7 mb-1 text-secondary">Created After</label>
                <input type="date" name="created_after" id="created_after" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold fs-7 mb-1 text-secondary">Created Before</label>
                <input type="date" name="created_before" id="created_before" class="form-control form-control-sm">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm fw-bold w-100 py-1" style="height: 31px; margin-top: 23px;">
                    <i class="fas fa-filter me-1"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Status Tabs -->
    <ul class="nav nav-tabs-daraz" id="order-tabs">
        <li class="nav-item">
            <button class="nav-link active" data-status="">All Orders</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="pending">Pending</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="ready_to_ship">Ready to Ship</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="shipped">Shipped</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="delivered">Delivered</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-status="canceled">Canceled</button>
        </li>
    </ul>

    <!-- Orders Container -->
    <div id="orders-wrapper" class="position-relative min-vh-50">
        <div id="loading-spinner" class="spinner-overlay d-none">
            <div class="text-center">
                <div class="spinner-border text-warning" role="status"></div>
                <div class="mt-2 fw-semibold text-secondary fs-7">Fetching orders from Daraz API...</div>
            </div>
        </div>

        <div id="orders-list">
            <!-- Order cards injected via JS -->
        </div>
    </div>
</div>

<!-- Order Items & Doc Modal -->
<div class="modal fade" id="itemsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold fs-6">Order Details & Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-items-body">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStatus = '';

    const selectStore = document.getElementById('select-store');
    const filterForm = document.getElementById('filter-form');
    const ordersList = document.getElementById('orders-list');
    const loadingSpinner = document.getElementById('loading-spinner');
    const btnSyncOrders = document.getElementById('btn-sync-orders');
    const orderTabs = document.querySelectorAll('#order-tabs .nav-link');

    // Tab clicks
    orderTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            orderTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            currentStatus = this.getAttribute('data-status');
            loadOrders();
        });
    });

    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        loadOrders();
    });

    btnSyncOrders.addEventListener('click', function() {
        loadOrders();
    });

    function loadOrders() {
        const storeId = selectStore.value;
        if (!storeId) {
            ordersList.innerHTML = `<div class="alert alert-warning text-center">Please select a Daraz store.</div>`;
            return;
        }

        loadingSpinner.classList.remove('d-none');
        ordersList.innerHTML = '';

        const createdAfter = document.getElementById('created_after').value;
        const createdBefore = document.getElementById('created_before').value;

        let query = `store_id=${storeId}&limit=50`;
        if (currentStatus) query += `&status=${currentStatus}`;
        if (createdAfter) query += `&created_after=${createdAfter}T00:00:00Z`;
        if (createdBefore) query += `&created_before=${createdBefore}T23:59:59Z`;

        const fetchUrl = `{{ route('admin.daraz.orders.fetch') }}?${query}`;

        fetch(fetchUrl)
            .then(res => res.json())
            .then(data => {
                loadingSpinner.classList.add('d-none');
                if (!data.success) {
                    ordersList.innerHTML = `
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-triangle me-2"></i> ${data.message || 'Failed to fetch orders.'}
                        </div>`;
                    return;
                }

                renderOrders(data.orders || [], storeId);
            })
            .catch(err => {
                loadingSpinner.classList.add('d-none');
                ordersList.innerHTML = `
                    <div class="alert alert-danger text-center">
                        An error occurred while connecting to server.
                    </div>`;
            });
    }

    function renderOrders(orders, storeId) {
        if (!orders.length) {
            ordersList.innerHTML = `
                <div class="text-center py-5 bg-white rounded-3 border">
                    <i class="fas fa-box-open fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="fw-bold text-secondary">No Orders Found</h6>
                    <p class="text-muted small">There are no orders matching your selected criteria.</p>
                </div>`;
            return;
        }

        let html = '';
        orders.forEach(order => {
            const orderId = order.order_id || order.order_number || 'N/A';
            const status = (order.statuses && order.statuses[0]) ? order.statuses[0] : (order.status || 'pending');
            const itemsCount = order.items_count || order.order_items_count || 1;
            const price = order.price || '0.00';
            const trackingNo = order.tracking_code || order.order_number || 'N/A';
            const updatedAt = order.updated_at ? new Date(order.updated_at).toLocaleString() : 'N/A';
            const customerName = order.customer_first_name ? `${order.customer_first_name} ${order.customer_last_name || ''}` : 'Customer';

            const docBaseUrl = `{{ route('admin.daraz.orders.document') }}?store_id=${storeId}&order_id=${orderId}`;

            html += `
            <div class="order-card">
                <div class="order-card-header">
                    <div>
                        <span class="text-muted">Order Number:</span>
                        <strong class="text-dark me-3">#${orderId}</strong>
                        <span class="text-muted small me-2"><i class="far fa-clock me-1"></i> Updated: ${updatedAt}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-status status-${status}">${status.replace(/_/g, ' ')}</span>
                        
                        <!-- Dedicated Document Download / Print Badges -->
                        <a href="${docBaseUrl}&type=awb&print=1" target="_blank" class="btn-doc-print btn-doc-awb" title="Print Air Waybill">
                            <i class="fas fa-print"></i> AWB
                        </a>
                        <a href="${docBaseUrl}&type=invoice&print=1" target="_blank" class="btn-doc-print btn-doc-invoice" title="Print Invoice">
                            <i class="fas fa-file-invoice"></i> Invoice
                        </a>
                        <a href="${docBaseUrl}&type=picklist&print=1" target="_blank" class="btn-doc-print btn-doc-picklist" title="Print PickList">
                            <i class="fas fa-list-check"></i> PickList
                        </a>
                    </div>
                </div>
                <div class="order-card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light p-2 rounded text-secondary fw-bold">
                                    <i class="fas fa-box fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">${customerName}</div>
                                    <div class="text-muted small">${itemsCount} Item(s)</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Total Amount</div>
                            <div class="fw-bold text-dark fs-6">৳${parseFloat(price).toFixed(2)}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">Delivery & Tracking</div>
                            <div class="fw-semibold text-secondary small">TN: ${trackingNo}</div>
                        </div>
                        <div class="col-md-2 text-end d-flex flex-column gap-1 align-items-end">
                            <button class="btn btn-outline-secondary btn-sm fw-semibold btn-view-items w-100" data-order-id="${orderId}" data-store-id="${storeId}">
                                <i class="fas fa-eye me-1"></i> View Items
                            </button>
                            <button class="btn btn-outline-warning text-dark btn-sm fw-semibold btn-view-logistic w-100" data-order-id="${orderId}" data-store-id="${storeId}">
                                <i class="fas fa-truck me-1"></i> Logistic Status
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
        });

        ordersList.innerHTML = html;

        // Attach modal triggers
        document.querySelectorAll('.btn-view-items').forEach(btn => {
            btn.addEventListener('click', function() {
                const oId = this.getAttribute('data-order-id');
                const sId = this.getAttribute('data-store-id');
                openItemsModal(sId, oId);
            });
        });

        document.querySelectorAll('.btn-view-logistic').forEach(btn => {
            btn.addEventListener('click', function() {
                const oId = this.getAttribute('data-order-id');
                const sId = this.getAttribute('data-store-id');
                openLogisticModal(sId, oId);
            });
        });
    }

    function openLogisticModal(storeId, orderId) {
        const modal = new bootstrap.Modal(document.getElementById('itemsModal'));
        const modalBody = document.getElementById('modal-items-body');
        modalBody.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-warning" role="status"></div></div>`;
        modal.show();

        const url = `{{ url('admin/daraz/orders') }}/${orderId}/logistic?store_id=${storeId}`;
        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    modalBody.innerHTML = `<div class="alert alert-danger">${data.message || 'Failed to load logistics data.'}</div>`;
                    return;
                }

                const log = data.logistic || {};
                let logHtml = `
                    <div class="mb-3">
                        <h6 class="fw-bold text-dark m-0"><i class="fas fa-truck text-warning me-2"></i>Logistic Details for Order #${orderId}</h6>
                    </div>
                    <div class="bg-light p-3 rounded border">
                        <pre class="m-0 text-dark" style="font-size: 0.85rem; max-height: 400px; overflow-y: auto;">${JSON.stringify(log, null, 2)}</pre>
                    </div>`;
                modalBody.innerHTML = logHtml;
            })
            .catch(err => {
                modalBody.innerHTML = `<div class="alert alert-danger">An error occurred while fetching logistics info.</div>`;
            });
    }

    function openItemsModal(storeId, orderId) {
        const modal = new bootstrap.Modal(document.getElementById('itemsModal'));
        const modalBody = document.getElementById('modal-items-body');
        modalBody.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>`;
        modal.show();

        const url = `{{ url('admin/daraz/orders') }}/${orderId}/items?store_id=${storeId}`;
        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    modalBody.innerHTML = `<div class="alert alert-danger">${data.message || 'Failed to load items.'}</div>`;
                    return;
                }

                let itemsHtml = `
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold m-0">Order #${orderId} Items</h6>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.daraz.orders.document') }}?store_id=${storeId}&order_id=${orderId}&type=invoice&print=1" target="_blank" class="btn btn-sm btn-outline-indigo">
                                <i class="fas fa-print me-1"></i> Print Invoice
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Order Item ID</th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>`;
                
                (data.items || []).forEach(item => {
                    itemsHtml += `
                        <tr>
                            <td><code class="fw-bold text-primary">${item.order_item_id || 'N/A'}</code></td>
                            <td>${item.name || 'N/A'}</td>
                            <td><span class="badge bg-light text-dark border">${item.sku || 'N/A'}</span></td>
                            <td>৳${parseFloat(item.item_price || 0).toFixed(2)}</td>
                            <td><span class="badge bg-secondary">${item.status || 'N/A'}</span></td>
                        </tr>`;
                });

                itemsHtml += `</tbody></table></div>`;
                modalBody.innerHTML = itemsHtml;
            });
    }

    // Initial load
    loadOrders();
});
</script>
@endsection
