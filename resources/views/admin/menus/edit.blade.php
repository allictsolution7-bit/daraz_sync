@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css">
    <style>
        .dd {
            max-width: 100%;
        }

        .dd-handle {
            height: auto;
        }

        .dd-item>button {
            margin: 8px 0;
        }

        .menu-item-actions {
            float: right;
            margin-right: 10px;
        }

        .menu-item-actions a {
            margin-left: 5px;
        }

        /* Modal Styles */
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .modal-header .close {
            color: white;
            opacity: 0.8;
        }

        .modal-header .close:hover {
            opacity: 1;
        }

        .form-group label {
            font-weight: 600;
            color: #495057;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }

        .loading-spinner {
            display: none;
            margin-left: 10px;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Toast Notification Styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }

        .toast {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            margin-bottom: 10px;
            min-width: 300px;
            border-left: 4px solid #28a745;
        }

        .toast.toast-success {
            border-left-color: #28a745;
        }

        .toast.toast-error {
            border-left-color: #dc3545;
        }

        .toast-header {
            background: transparent;
            border-bottom: 1px solid #e9ecef;
            padding: 12px 15px;
            font-weight: 600;
        }

        .toast-body {
            padding: 12px 15px;
            color: #495057;
        }

        .toast-close {
            background: none;
            border: none;
            font-size: 18px;
            color: #6c757d;
            cursor: pointer;
        }

        .toast-close:hover {
            color: #343a40;
        }

        /* Preview Styles */
        .preview-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px dashed #dee2e6 !important;
        }

        .preview-item {
            color: #495057;
            font-size: 14px;
        }

        .preview-item i {
            color: #667eea;
            font-size: 16px;
        }

        .preview-item small {
            font-size: 12px;
            opacity: 0.7;
        }

        /* Success Animation */
        .preview-container.border-success {
            border: 2px solid #28a745 !important;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            transition: all 0.3s ease;
        }

        .preview-container {
            transition: all 0.3s ease;
        }

        /* Edit Modal Specific Styles */
        #editMenuItemModal .modal-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        #editMenuItemModal .btn-primary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        #editMenuItemModal .btn-primary:hover {
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
        }

        /* Form field focus states for edit modal */
        #editMenuItemModal .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Menu</h5>
                        <div class="card-tools">
                            <a href="{{ route('admin.menus.index') }}" class="btn btn-default btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Menus
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name">Menu Name</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $menu->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" name="location" id="location"
                                    class="form-control @error('location') is-invalid @enderror"
                                    value="{{ old('location', $menu->location) }}">
                                @error('location')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="status" class="custom-control-input" id="status"
                                        {{ $menu->status ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="status">Active</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Menu</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title">Add Menu Item</h5>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMenuItemModal" 
                                title="Add new menu item (Ctrl+N)">
                            <i class="fas fa-plus"></i> Add New Item
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Menu Structure <span class="badge badge-primary">{{ $menuItems->count() }} items</span></h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="refreshMenuStructure()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dd" id="nestable">
                            <ol class="dd-list">
                                @foreach ($menuItems as $item)
                                    @include('admin.menus.partials.menu-item', ['item' => $item])
                                @endforeach
                            </ol>
                        </div>
                        <form id="menu-structure-form" action="{{ route('admin.menu-items.update-order') }}" method="POST">
                            @csrf
                            <input type="hidden" name="menu_structure" id="menu_structure">
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="button" id="save-menu-order" class="btn btn-success">Save Menu Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Menu Item Modal -->
    <div class="modal fade" id="editMenuItemModal" tabindex="-1" role="dialog" aria-labelledby="editMenuItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMenuItemModalLabel">
                        Edit Menu Item
                        <small class="d-block text-white-50">Update menu item details</small>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editMenuItemForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_item_id" name="item_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_title">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="edit_title" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_url">URL</label>
                                    <input type="text" name="url" id="edit_url" class="form-control" placeholder="e.g., /about-us">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_parent_id">Parent Item</label>
                                    <select name="parent_id" id="edit_parent_id" class="form-control">
                                        <option value="">None (Top Level)</option>
                                        @foreach($menuItems as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_target">Open In</label>
                                    <select name="target" id="edit_target" class="form-control">
                                        <option value="_self">Same Window</option>
                                        <option value="_blank">New Window</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_icon_class">Icon Class</label>
                                    <input type="text" name="icon_class" id="edit_icon_class" class="form-control" placeholder="e.g., fas fa-home">
                                    <small class="form-text text-muted">Example: fas fa-home, fas fa-user, fas fa-cog</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_order">Order</label>
                                    <input type="number" name="order" id="edit_order" class="form-control" value="0" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="status" class="custom-control-input" id="edit_status">
                                <label class="custom-control-label" for="edit_status">Active</label>
                            </div>
                        </div>

                        <!-- Mega Menu Configuration (only for top-level items) -->
                        <div id="editMegaMenuSection" class="mega-menu-config mt-4" style="display: none;">
                            <hr>
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-th-large"></i> Mega Menu Configuration
                            </h6>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="has_mega_menu" value="1" class="custom-control-input" id="edit_has_mega_menu">
                                    <label class="custom-control-label" for="edit_has_mega_menu">Enable Mega Menu</label>
                                </div>
                                <small class="form-text text-muted">When enabled, this item will display a 4-column mega menu dropdown.</small>
                            </div>

                            <div id="editMegaMenuColumns" style="display: none;">
                                <p class="text-muted small mb-3">Assign existing menus to each column. Leave empty to skip a column.</p>

                                <div class="row">
                                    @for($col = 1; $col <= 4; $col++)
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header py-2 bg-light">
                                                <strong>Column {{ $col }}</strong>
                                            </div>
                                            <div class="card-body py-2">
                                                <div class="form-group mb-2">
                                                    <label class="small">Select Menu</label>
                                                    <select name="mega_menu_config[column_{{ $col }}]"
                                                            id="edit_mega_menu_column_{{ $col }}"
                                                            class="form-control form-control-sm">
                                                        <option value="">-- None --</option>
                                                        @foreach($allMenus as $m)
                                                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->menu_items_count }} items)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="small">Custom Header (optional)</label>
                                                    <input type="text"
                                                           name="mega_menu_headers[column_{{ $col }}]"
                                                           id="edit_mega_menu_header_{{ $col }}"
                                                           class="form-control form-control-sm"
                                                           placeholder="Leave empty to use menu name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="form-group">
                            <label>Preview:</label>
                            <div class="preview-container p-3 border rounded bg-light">
                                <div class="preview-item d-flex align-items-center">
                                    <i id="editPreviewIcon" class="fas fa-home mr-2"></i>
                                    <span id="editPreviewTitle">Menu Item Title</span>
                                    <small id="editPreviewUrl" class="text-muted ml-2"></small>
                                    <span id="editPreviewMegaMenu" class="badge badge-info ml-2" style="display: none;">Mega Menu</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="updateMenuItemBtn" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Menu Item
                        <span class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Add Menu Item Modal -->
    <div class="modal fade" id="addMenuItemModal" tabindex="-1" role="dialog" aria-labelledby="addMenuItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMenuItemModalLabel">
                        Add Menu Item to {{ $menu->name }}
                        <small class="d-block text-white-50">Currently {{ $menuItems->count() }} items</small>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addMenuItemForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="url">URL</label>
                                    <input type="text" name="url" id="url" class="form-control" placeholder="e.g., /about-us">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parent_id">Parent Item</label>
                                    <select name="parent_id" id="parent_id" class="form-control">
                                        <option value="">None (Top Level)</option>
                                        @foreach($menuItems as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="target">Open In</label>
                                    <select name="target" id="target" class="form-control">
                                        <option value="_self">Same Window</option>
                                        <option value="_blank">New Window</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="icon_class">Icon Class</label>
                                    <input type="text" name="icon_class" id="icon_class" class="form-control" placeholder="e.g., fas fa-home">
                                    <small class="form-text text-muted">Example: fas fa-home, fas fa-user, fas fa-cog</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Order</label>
                                    <input type="number" name="order" id="order" class="form-control" value="0" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="status" class="custom-control-input" id="add_status" checked>
                                <label class="custom-control-label" for="add_status">Active</label>
                            </div>
                        </div>

                        <!-- Mega Menu Configuration (only for top-level items) -->
                        <div id="addMegaMenuSection" class="mega-menu-config mt-4">
                            <hr>
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-th-large"></i> Mega Menu Configuration
                            </h6>

                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="has_mega_menu" value="1" class="custom-control-input" id="add_has_mega_menu">
                                    <label class="custom-control-label" for="add_has_mega_menu">Enable Mega Menu</label>
                                </div>
                                <small class="form-text text-muted">When enabled, this item will display a 4-column mega menu dropdown.</small>
                            </div>

                            <div id="addMegaMenuColumns" style="display: none;">
                                <p class="text-muted small mb-3">Assign existing menus to each column. Leave empty to skip a column.</p>

                                <div class="row">
                                    @for($col = 1; $col <= 4; $col++)
                                    <div class="col-md-6 mb-3">
                                        <div class="card">
                                            <div class="card-header py-2 bg-light">
                                                <strong>Column {{ $col }}</strong>
                                            </div>
                                            <div class="card-body py-2">
                                                <div class="form-group mb-2">
                                                    <label class="small">Select Menu</label>
                                                    <select name="mega_menu_config[column_{{ $col }}]"
                                                            id="add_mega_menu_column_{{ $col }}"
                                                            class="form-control form-control-sm">
                                                        <option value="">-- None --</option>
                                                        @foreach($allMenus as $m)
                                                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->menu_items_count }} items)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="small">Custom Header (optional)</label>
                                                    <input type="text"
                                                           name="mega_menu_headers[column_{{ $col }}]"
                                                           id="add_mega_menu_header_{{ $col }}"
                                                           class="form-control form-control-sm"
                                                           placeholder="Leave empty to use menu name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="form-group">
                            <label>Preview:</label>
                            <div class="preview-container p-3 border rounded bg-light">
                                <div class="preview-item d-flex align-items-center">
                                    <i id="previewIcon" class="fas fa-home mr-2"></i>
                                    <span id="previewTitle">Menu Item Title</span>
                                    <small id="previewUrl" class="text-muted ml-2"></small>
                                    <span id="previewMegaMenu" class="badge badge-info ml-2" style="display: none;">Mega Menu</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="saveMenuItemBtn" class="btn btn-primary">
                        <i class="fas fa-save"></i> Add Menu Item
                        <span class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js"></script>
    <script>
        $(document).ready(function() {
            var updateOutput = function(e) {
                var list = e.length ? e : $(e.target);
                var items = [];

                function getItems(item, parentId = null) {
                    var id = item.data('id');
                    var order = item.index();

                    items.push({
                        id: id,
                        parent_id: parentId,
                        order: order
                    });

                    if (item.children('.dd-list').length) {
                        item.children('.dd-list').children('.dd-item').each(function() {
                            getItems($(this), id);
                        });
                    }
                }

                list.children('.dd-item').each(function() {
                    getItems($(this));
                });

                $('#menu-structure').val(JSON.stringify(items));
            };

            $('#nestable').nestable({
                maxDepth: 3
            }).on('change', updateOutput);

            updateOutput($('#nestable').data('output', $('#menu-structure')));

            // Add Menu Item AJAX functionality
            $('#saveMenuItemBtn').on('click', function() {
                var form = $('#addMenuItemForm');
                var formData = form.serialize();
                var submitBtn = $(this);
                var originalText = submitBtn.html();

                // Show loading state
                submitBtn.prop('disabled', true);
                $('.loading-spinner').show();
                showLoadingPreview();

                // Clear previous alerts
                $('#alert-container').empty();

                $.ajax({
                    url: "{{ route('admin.menus.items.store', $menu) }}",
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            showToast(response.message, 'success');

                            // Show success animation in preview
                            $('#previewTitle').html('<i class="fas fa-check-circle text-success"></i> ' + response.data.title);
                            $('#previewIcon').attr('class', 'fas fa-star mr-2 text-warning');
                            $('#previewUrl').text('(' + (response.data.url || 'No URL') + ')').show();
                            
                            // Add success animation class
                            $('.preview-container').addClass('border-success').removeClass('border');

                            // Reset form
                            form[0].reset();
                            $('#status').prop('checked', true);
                            $('#order').val(0);

                            // Close modal after 1.5 seconds
                            setTimeout(function() {
                                $('#addMenuItemModal').modal('hide');
                                // Refresh menu structure without page reload
                                refreshMenuStructure();
                            }, 1500);
                        } else {
                            showToast(response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = 'An error occurred while creating the menu item.';
                        
                        if (xhr.status === 422) {
                            // Validation errors
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = [];
                            for (var field in errors) {
                                errorMessages.push(errors[field][0]);
                            }
                            showToast(errorMessages.join('<br>'), 'error');
                        } else {
                            showToast(errorMessage, 'error');
                        }
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.prop('disabled', false);
                        $('.loading-spinner').hide();
                        resetPreview();
                    }
                });
            });

            // Reset form when modal is closed
            $('#addMenuItemModal').on('hidden.bs.modal', function() {
                $('#addMenuItemForm')[0].reset();
                $('#status').prop('checked', true);
                $('#order').val(0);
                updatePreview();
            });

            // Confirm modal close if form has changes
            $('#addMenuItemModal').on('hide.bs.modal', function(e) {
                var form = $('#addMenuItemForm')[0];
                var hasChanges = false;
                
                // Check if any field has been modified
                if (form.title.value || form.url.value || form.icon_class.value || 
                    form.order.value !== '0' || !form.status.checked) {
                    hasChanges = true;
                }
                
                if (hasChanges) {
                    if (!confirm('You have unsaved changes. Are you sure you want to close?')) {
                        e.preventDefault();
                        return false;
                    }
                }
            });

            // Real-time preview updates
            $('#title').on('input', updatePreview);
            $('#url').on('input', updatePreview);
            $('#icon_class').on('input', updatePreview);

            // Function to update preview
            function updatePreview() {
                var title = $('#title').val() || 'Menu Item Title';
                var url = $('#url').val();
                var iconClass = $('#icon_class').val() || 'fas fa-home';
                
                $('#previewTitle').text(title);
                $('#previewIcon').attr('class', iconClass + ' mr-2');
                
                if (url) {
                    $('#previewUrl').text('(' + url + ')').show();
                } else {
                    $('#previewUrl').hide();
                }
            }

            // Initialize preview
            updatePreview();

            // Mega Menu Configuration - Add Modal
            // Show/hide mega menu section based on parent selection
            $('#parent_id').on('change', function() {
                var hasParent = $(this).val() !== '';
                if (hasParent) {
                    $('#addMegaMenuSection').slideUp();
                    $('#add_has_mega_menu').prop('checked', false).trigger('change');
                } else {
                    $('#addMegaMenuSection').slideDown();
                }
            });

            // Toggle mega menu columns visibility for Add modal
            $('#add_has_mega_menu').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#addMegaMenuColumns').slideDown();
                    $('#previewMegaMenu').show();
                } else {
                    $('#addMegaMenuColumns').slideUp();
                    $('#previewMegaMenu').hide();
                }
            });

            // Mega Menu Configuration - Edit Modal
            // Show/hide mega menu section based on parent selection
            $('#edit_parent_id').on('change', function() {
                var hasParent = $(this).val() !== '';
                if (hasParent) {
                    $('#editMegaMenuSection').slideUp();
                    $('#edit_has_mega_menu').prop('checked', false).trigger('change');
                } else {
                    $('#editMegaMenuSection').slideDown();
                }
            });

            // Toggle mega menu columns visibility for Edit modal
            $('#edit_has_mega_menu').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#editMegaMenuColumns').slideDown();
                    $('#editPreviewMegaMenu').show();
                } else {
                    $('#editMegaMenuColumns').slideUp();
                    $('#editPreviewMegaMenu').hide();
                }
            });

            // Keyboard shortcut to open modal (Ctrl+N)
            $(document).on('keydown', function(e) {
                if (e.ctrlKey && e.key === 'n') {
                    e.preventDefault();
                    $('#addMenuItemModal').modal('show');
                }
                // Keyboard shortcut to edit selected item (Ctrl+E)
                if (e.ctrlKey && e.key === 'e') {
                    e.preventDefault();
                    // Find the first menu item and edit it (for demo purposes)
                    var firstItem = $('.dd-item').first();
                    if (firstItem.length) {
                        var itemId = firstItem.data('id');
                        var itemTitle = firstItem.find('.dd-handle').text().trim();
                        // You can enhance this to select the currently focused item
                        showToast('Press Ctrl+E on a specific menu item to edit it', 'info');
                    }
                }
            });

            // Show loading state when modal opens
            $('#addMenuItemModal').on('show.bs.modal', function() {
                // Reset form and preview
                $('#addMenuItemForm')[0].reset();
                $('#status').prop('checked', true);
                $('#order').val(0);
                updatePreview();
                
                // Focus on title field
                setTimeout(function() {
                    $('#title').focus();
                }, 500);
            });

            // Function to show loading preview
            function showLoadingPreview() {
                $('#previewTitle').html('<i class="fas fa-spinner fa-spin"></i> Saving...');
                $('#previewIcon').attr('class', 'fas fa-clock mr-2');
                $('#previewUrl').hide();
            }

            // Function to reset preview
            function resetPreview() {
                updatePreview();
            }

            // Function to delete menu item via AJAX
            window.deleteMenuItem = function(itemId, itemTitle) {
                if (confirm('Are you sure you want to delete "' + itemTitle + '"?')) {
                    var deleteUrl = "{{ url('admin/menus') }}/{{ $menu->id }}/items/" + itemId;
                    console.log('Delete URL:', deleteUrl);
                    console.log('Item ID:', itemId);
                    console.log('Item Title:', itemTitle);
                    
                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(response) {
                            console.log('Delete success response:', response);
                            if (response.success) {
                                // Show success message
                                showToast('Menu item deleted successfully!', 'success');
                                // Refresh menu structure without page reload
                                refreshMenuStructure();
                            } else {
                                showToast('Error: ' + response.message, 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Delete error:', xhr, status, error);
                            console.log('Response text:', xhr.responseText);
                            showToast('Error deleting menu item', 'error');
                        }
                    });
                }
            };

            // Function to edit menu item via popup
            window.editMenuItem = function(itemId, title, url, target, iconClass, order, status, parentId, hasMegaMenu, megaMenuConfig, megaMenuHeaders) {
                // Populate the edit form
                $('#edit_item_id').val(itemId);
                $('#edit_title').val(title);
                $('#edit_url').val(url);
                $('#edit_target').val(target);
                $('#edit_icon_class').val(iconClass);
                $('#edit_order').val(order);
                $('#edit_status').prop('checked', status);
                $('#edit_parent_id').val(parentId);

                // Handle mega menu configuration
                hasMegaMenu = hasMegaMenu || false;
                megaMenuConfig = megaMenuConfig || {};
                megaMenuHeaders = megaMenuHeaders || {};

                // Show/hide mega menu section based on parent
                if (parentId) {
                    $('#editMegaMenuSection').hide();
                } else {
                    $('#editMegaMenuSection').show();
                }

                // Set mega menu checkbox
                $('#edit_has_mega_menu').prop('checked', hasMegaMenu);

                // Show/hide columns based on mega menu enabled
                if (hasMegaMenu) {
                    $('#editMegaMenuColumns').show();
                    $('#editPreviewMegaMenu').show();
                } else {
                    $('#editMegaMenuColumns').hide();
                    $('#editPreviewMegaMenu').hide();
                }

                // Set mega menu column values
                for (var i = 1; i <= 4; i++) {
                    var colKey = 'column_' + i;
                    $('#edit_mega_menu_column_' + i).val(megaMenuConfig[colKey] || '');
                    $('#edit_mega_menu_header_' + i).val(megaMenuHeaders[colKey] || '');
                }

                // Store original values for change detection
                $('#editMenuItemModal').data('original-values', {
                    title: title,
                    url: url,
                    icon_class: iconClass,
                    order: order,
                    status: status,
                    parent_id: parentId,
                    has_mega_menu: hasMegaMenu,
                    mega_menu_config: megaMenuConfig,
                    mega_menu_headers: megaMenuHeaders
                });

                // Update parent dropdown to exclude current item
                updateParentDropdown(itemId);

                // Update preview
                updateEditPreview();

                // Show the edit modal
                $('#updateMenuItemBtn').prop('disabled', false);
                $('#editMenuItemModal').modal('show');
            };

            // Function to update parent dropdown to prevent circular references
            function updateParentDropdown(currentItemId) {
                var parentSelect = $('#edit_parent_id');
                
                // Reset to original options
                parentSelect.html('<option value="">None (Top Level)</option>');
                
                // Add menu items excluding current item
                @foreach($menuItems as $item)
                    if ({{ $item->id }} != currentItemId) {
                        parentSelect.append('<option value="{{ $item->id }}">{{ $item->title }}</option>');
                    }
                @endforeach
                
                // Set the current parent
                parentSelect.val($('#edit_parent_id').val());
            }

            // Function to update edit preview
            function updateEditPreview() {
                var title = $('#edit_title').val() || 'Menu Item Title';
                var url = $('#edit_url').val();
                var iconClass = $('#edit_icon_class').val() || 'fas fa-home';
                
                $('#editPreviewTitle').text(title);
                $('#editPreviewIcon').attr('class', iconClass + ' mr-2');
                
                if (url) {
                    $('#editPreviewUrl').text('(' + url + ')').show();
                } else {
                    $('#editPreviewUrl').hide();
                }
            }

            // Real-time preview updates for edit form
            $('#edit_title').on('input', updateEditPreview);
            $('#edit_url').on('input', updateEditPreview);
            $('#edit_icon_class').on('input', updateEditPreview);

            // Update menu item AJAX functionality
            $('#updateMenuItemBtn').on('click', function() {
                var form = $('#editMenuItemForm');
                var formData = form.serialize();
                var submitBtn = $(this);
                var originalText = submitBtn.html();

                // Show loading state
                submitBtn.prop('disabled', true);
                $('.loading-spinner').show();

                // Get the item ID
                var itemId = $('#edit_item_id').val();
                var updateUrl = "{{ url('admin/menus') }}/{{ $menu->id }}/items/" + itemId;

                $.ajax({
                    url: updateUrl,
                    type: 'PUT',
                    data: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            showToast(response.message, 'success');

                            // Close modal
                            $('#editMenuItemModal').modal('hide');

                            // Refresh menu structure without page reload
                            refreshMenuStructure();
                        } else {
                            showToast(response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = 'An error occurred while updating the menu item.';
                        
                        if (xhr.status === 422) {
                            // Validation errors
                            var errors = xhr.responseJSON.errors;
                            var errorMessages = [];
                            for (var field in errors) {
                                errorMessages.push(errors[field][0]);
                            }
                            showToast(errorMessages.join('<br>'), 'error');
                        } else {
                            showToast(errorMessage, 'error');
                        }
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.prop('disabled', false);
                        $('.loading-spinner').hide();
                    }
                });
            });

            // Reset edit form when modal is closed
            $('#editMenuItemModal').on('hidden.bs.modal', function() {
                $('#editMenuItemForm')[0].reset();
                $('#edit_item_id').val('');
                $('#edit_title').val('');
                $('#edit_url').val('');
                $('#edit_target').val('_self');
                $('#edit_icon_class').val('');
                $('#edit_order').val(0);
                $('#edit_status').prop('checked', true);
                $('#edit_parent_id').val('');
                updateEditPreview();
            });

            // Confirm edit modal close if form has changes
            $('#editMenuItemModal').on('hide.bs.modal', function(e) {
                var form = $('#editMenuItemForm')[0];
                var hasChanges = false;
                
                // Check if any field has been modified from original values
                var originalValues = $(this).data('original-values');
                if (originalValues) {
                    if (form.title.value !== originalValues.title || 
                        form.url.value !== originalValues.url || 
                        form.icon_class.value !== originalValues.icon_class || 
                        form.order.value !== originalValues.order || 
                        form.status.checked !== originalValues.status ||
                        form.parent_id.value !== originalValues.parent_id) {
                        hasChanges = true;
                    }
                }
                
                if (hasChanges) {
                    if (!confirm('You have unsaved changes. Are you sure you want to close?')) {
                        e.preventDefault();
                        return false;
                    }
                }
            });

            // Function to refresh menu structure
            function refreshMenuStructure() {
                var refreshBtn = $('.card-tools .btn-outline-secondary');
                var originalText = refreshBtn.html();
                
                // Show loading state
                refreshBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Refreshing...');
                
                $.ajax({
                    url: "{{ route('admin.menus.edit', $menu) }}",
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        // Extract the menu structure from the response
                        var tempDiv = $('<div>').html(response);
                        var newMenuStructure = tempDiv.find('#nestable').html();
                        
                        if (newMenuStructure) {
                            // Update the menu structure
                            $('#nestable').html(newMenuStructure);
                            
                            // Reinitialize nestable
                            $('#nestable').nestable('destroy');
                            $('#nestable').nestable({
                                maxDepth: 3
                            }).on('change', updateOutput);
                            
                            // Update output
                            updateOutput($('#nestable').data('output', $('#menu_structure')));
                            
                            // Update menu item count
                            updateMenuItemCount();
                            
                            // Show success message
                            showToast('Menu structure refreshed successfully!', 'success');
                        }
                    },
                    error: function(xhr) {
                        showToast('Error refreshing menu structure', 'error');
                    },
                    complete: function() {
                        // Reset button state
                        refreshBtn.prop('disabled', false).html(originalText);
                    }
                });
            }

            // Function to update menu item count
            function updateMenuItemCount() {
                var itemCount = $('#nestable .dd-item').length;
                $('.card-title .badge').text(itemCount + ' items');
            }

            // Toast notification functions
            function showToast(message, type = 'success') {
                var toastClass = type === 'success' ? 'toast-success' : 'toast-error';
                var iconClass = type === 'success' ? 'fas fa-check-circle text-success' : 'fas fa-exclamation-circle text-danger';
                
                var toast = $(
                    '<div class="toast ' + toastClass + '">' +
                        '<div class="toast-header">' +
                            '<i class="' + iconClass + ' mr-2"></i>' +
                            '<strong class="mr-auto">' + (type === 'success' ? 'Success' : 'Error') + '</strong>' +
                            '<button type="button" class="toast-close" onclick="$(this).closest(\'.toast\').remove()">&times;</button>' +
                        '</div>' +
                        '<div class="toast-body">' + message + '</div>' +
                    '</div>'
                );
                
                $('#toastContainer').append(toast);
                
                // Auto remove after 5 seconds
                setTimeout(function() {
                    toast.fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 5000);
            }

            // Update existing functions to use toast notifications
            $('#save-menu-order').on('click', function() {
                var data = $('.dd').nestable('serialize');

                $.ajax({
                    url: "{{ route('admin.menu-items.update-order') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        items: JSON.stringify(data)
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast('Menu order updated successfully!', 'success');
                        } else {
                            showToast('Error: ' + response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        showToast('Error updating menu order', 'error');
                    }
                });
            });
        });
    </script>
@endsection
