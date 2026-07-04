@extends('layouts.master')

@section('styles')
<style>
    .modules-container {
        padding: 15px 0;
    }

    .module-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        cursor: pointer;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .module-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        border-color: #197A94;
    }

    .module-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 5px;
    }

    .module-icon {
        width: 45px;
        height: 45px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        margin-bottom: 10px;
    }

    .module-name {
        font-size: 16px;
        font-weight: 600;
        color: #212529;
        margin-bottom: 5px;
        flex: 1;
        line-height: 20px;
    }

    .module-description {
        color: #6c757d;
        font-size: 12px;
        line-height: 1.5;
        margin-bottom: 10px;
        flex: 1;
    }

    .module-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-builtin {
        background: #e2e3e5;
        color: #383d41;
    }

    .badge-premium {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .badge-licensed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .badge-unlicensed {
        background: #f5c6cb;
        color: #721c24;
    }

    .badge-not-installed {
        background: #fff3cd;
        color: #856404;
    }

    .module-footer {
        margin-top: auto;
        padding-top: 5px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .module-actions {
        display: flex;
        gap: 8px;
    }

    .btn-module {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-module-primary {
        background: #197A94;
        color: white;
    }

    .btn-module-primary:hover {
        background: #0056b3;
        transform: translateY(-1px);
    }

    .btn-module-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-module-secondary:hover {
        background: #5a6268;
    }

    .btn-enable {
        background: #28a745;
        color: white;
    }

    .btn-enable:hover {
        background: #218838;
    }

    .btn-disable {
        background: #dc3545;
        color: white;
    }

    .btn-disable:hover {
        background: #c82333;
    }

    .btn-delete {
        background: #6c757d;
        color: white;
        padding: 6px 10px;
    }

    .btn-delete:hover {
        background: #5a6268;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .page-subtitle {
        opacity: 0.9;
        font-size: 14px;
        margin-bottom: 0;
    }

    .module-count {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-left: 10px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-header {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-upload {
        background: white;
        color: #667eea;
    }

    .btn-upload:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
    }

    .btn-sync {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    .btn-sync:hover {
        background: rgba(255,255,255,0.3);
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #212529;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    .section-title i {
        color: #667eea;
    }

    .section-title .badge {
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 15px;
        background: #667eea;
        color: white;
    }

    /* Upload Modal */
    .upload-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
        justify-content: center;
        align-items: center;
    }

    .upload-modal.show {
        display: flex;
    }

    .upload-modal-content {
        background: white;
        border-radius: 16px;
        padding: 30px;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .upload-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .upload-modal-header h5 {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }

    .btn-close-modal {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6c757d;
    }

    .upload-zone {
        border: 2px dashed #dee2e6;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
    }

    .upload-zone:hover,
    .upload-zone.dragover {
        border-color: #667eea;
        background: #f8f9fa;
    }

    .upload-zone i {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 15px;
    }

    .upload-zone p {
        color: #6c757d;
        margin-bottom: 10px;
    }

    .upload-zone small {
        color: #adb5bd;
    }

    .upload-progress {
        display: none;
        margin-top: 20px;
    }

    .upload-progress.show {
        display: block;
    }

    .progress-bar-container {
        background: #e9ecef;
        border-radius: 10px;
        height: 10px;
        overflow: hidden;
    }

    .progress-bar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        height: 100%;
        transition: width 0.3s;
    }

    .upload-status {
        text-align: center;
        margin-top: 15px;
        color: #6c757d;
    }

    /* Alert Messages */
    .alert-float {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1100;
        min-width: 300px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .module-version {
        font-size: 11px;
        color: #6c757d;
        background: #f1f3f4;
        padding: 2px 6px;
        border-radius: 4px;
    }

    .module-badges {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="page-title">
                    <i class="fas fa-puzzle-piece me-2"></i> Modules & Tools
                    <span class="module-count">{{ count($builtInTools) + count($systemModules) }} Total</span>
                </h4>
                <p class="page-subtitle">Manage and configure your modules and integrations</p>
            </div>
            <div class="header-actions mt-3 mt-md-0">
                <button class="btn-header btn-sync" onclick="syncModules()">
                    <i class="fas fa-sync-alt"></i> Sync License
                </button>
                <button class="btn-header btn-upload" onclick="openUploadModal()">
                    <i class="fas fa-cloud-upload-alt"></i> Upload Module
                </button>
            </div>
        </div>
    </div>

    <!-- System Modules Section (Installable) - Show First -->
    @if(count($systemModules) > 0)
    <h5 class="section-title">
        <i class="fas fa-cubes"></i> System Modules
        <span class="badge">{{ count($systemModules) }}</span>
    </h5>
    <div class="modules-container">
        <div class="row">
            @foreach($systemModules as $module)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="module-card {{ $module['status'] === 'Active' ? 'active' : '' }}"
                     @if($module['route']) onclick="openModule('{{ $module['route'] }}')" @endif>
                    <div class="module-card-header">
                        <div class="module-icon" style="background: {{ $module['color'] }};">
                            <i class="{{ $module['icon'] }}"></i>
                        </div>
                        <span class="module-version">v{{ $module['version'] }}</span>
                    </div>
                    <h5 class="module-name">{{ $module['display_name'] }}</h5>
                    <p class="module-description">{{ $module['description'] }}</p>
                    <div class="module-badges">
                        @if($module['status'] === 'Active')
                            <span class="module-badge badge-active">Active</span>
                        @elseif($module['status'] === 'Not Installed')
                            <span class="module-badge badge-not-installed">Not Installed</span>
                        @else
                            <span class="module-badge badge-inactive">{{ $module['status'] }}</span>
                        @endif

                        @if($module['is_premium'])
                            <span class="module-badge badge-premium"><i class="fas fa-crown"></i></span>
                        @endif

                        @if($module['is_licensed'])
                            <span class="module-badge badge-licensed"><i class="fas fa-key"></i></span>
                        @else
                            <span class="module-badge badge-unlicensed"><i class="fas fa-lock"></i></span>
                        @endif
                    </div>
                    <div class="module-footer">
                        <div class="module-actions">
                            @if($module['exists'])
                                @if($module['is_enabled'])
                                    <button class="btn-module btn-disable" onclick="event.stopPropagation(); toggleModule('{{ $module['name'] }}', 'disable')" title="Disable">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                @else
                                    @if($module['is_licensed'])
                                        <button class="btn-module btn-enable" onclick="event.stopPropagation(); toggleModule('{{ $module['name'] }}', 'enable')" title="Enable">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                    @else
                                        <button class="btn-module btn-module-secondary" disabled title="License Required">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @endif
                                @endif
                                <button class="btn-module btn-delete" onclick="event.stopPropagation(); deleteModule('{{ $module['name'] }}')" title="Uninstall">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @else
                                <span class="text-muted small">Upload to install</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Built-in Tools Section -->
    <h5 class="section-title mt-4">
        <i class="fas fa-check-circle"></i> Built-in Tools
        <span class="badge">{{ count($builtInTools) }}</span>
    </h5>
    <div class="modules-container">
        <div class="row">
            @foreach($builtInTools as $module)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="module-card active" onclick="openModule('{{ $module['route'] }}')">
                    <div class="module-card-header">
                        <div class="module-icon" style="background: {{ $module['color'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }};">
                            <i class="{{ $module['icon'] }}"></i>
                        </div>
                    </div>
                    <h5 class="module-name">{{ $module['name'] }}</h5>
                    <p class="module-description">{{ $module['description'] }}</p>
                    <div class="module-footer">
                        <div class="module-badges">
                            <span class="module-badge badge-active">Active</span>
                            <span class="module-badge badge-builtin">Built-in</span>
                        </div>
                        <div class="module-actions">
                            <button class="btn-module btn-module-primary" onclick="event.stopPropagation(); openModule('{{ $module['route'] }}')">
                                <i class="fas fa-cog"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="upload-modal" id="uploadModal">
    <div class="upload-modal-content">
        <div class="upload-modal-header">
            <h5><i class="fas fa-cloud-upload-alt me-2"></i> Upload Module</h5>
            <button class="btn-close-modal" onclick="closeUploadModal()">&times;</button>
        </div>

        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="upload-zone" id="uploadZone" onclick="document.getElementById('moduleFile').click()">
                <i class="fas fa-file-archive"></i>
                <p>Drag & drop module ZIP file here</p>
                <p><strong>or click to browse</strong></p>
                <small>Maximum file size: 50MB</small>
            </div>
            <input type="file" id="moduleFile" name="module" accept=".zip" style="display: none;" onchange="handleFileSelect(this)">

            <div class="upload-progress" id="uploadProgress">
                <div class="progress-bar-container">
                    <div class="progress-bar" id="progressBar" style="width: 0%"></div>
                </div>
                <div class="upload-status" id="uploadStatus">Uploading...</div>
            </div>
        </form>

        <div class="mt-3">
            <small class="text-muted">
                <i class="fas fa-info-circle me-1"></i>
                Module must contain a valid <code>module.json</code> file with name and version.
            </small>
        </div>
    </div>
</div>

<!-- Alert Container -->
<div id="alertContainer"></div>

<!-- Version Mismatch Modal -->
<div class="upload-modal" id="versionMismatchModal">
    <div class="upload-modal-content" style="max-width: 450px;">
        <div class="upload-modal-header">
            <h5><i class="fas fa-exclamation-triangle text-warning me-2"></i> Version Mismatch</h5>
            <button class="btn-close-modal" onclick="closeVersionMismatchModal()">&times;</button>
        </div>
        <div class="text-center py-3">
            <i class="fas fa-sync-alt" style="font-size: 48px; color: #ffc107;"></i>
            <h5 class="mt-3 mb-2">Update Required</h5>
            <p class="text-muted mb-3">This module requires a different system version.</p>
            <div class="d-flex justify-content-center gap-4 mb-3">
                <div class="text-center">
                    <small class="text-muted d-block">Module Version</small>
                    <span class="badge bg-primary fs-6" id="moduleVersionBadge">-</span>
                </div>
                <div class="text-center">
                    <small class="text-muted d-block">Your Version</small>
                    <span class="badge bg-secondary fs-6" id="coreVersionBadge">-</span>
                </div>
            </div>
            <p class="text-muted small">Please update your core system to the latest version before installing this module.</p>
        </div>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('admin.updates.index') }}" class="btn btn-primary">
                <i class="fas fa-download me-1"></i> Go to Updates
            </a>
            <button class="btn btn-secondary" onclick="closeVersionMismatchModal()">Close</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModule(route) {
        if (route) {
            window.location.href = route;
        }
    }

    // Upload Modal Functions
    function openUploadModal() {
        document.getElementById('uploadModal').classList.add('show');
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.remove('show');
        document.getElementById('uploadForm').reset();
        document.getElementById('uploadProgress').classList.remove('show');
    }

    // Drag and Drop
    const uploadZone = document.getElementById('uploadZone');

    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    uploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('moduleFile').files = files;
            handleFileSelect(document.getElementById('moduleFile'));
        }
    });

    // File Upload Handler
    function handleFileSelect(input) {
        if (input.files.length === 0) return;

        const file = input.files[0];
        if (!file.name.endsWith('.zip')) {
            showAlert('Please select a ZIP file', 'danger');
            return;
        }

        const formData = new FormData();
        formData.append('module', file);
        formData.append('_token', '{{ csrf_token() }}');

        document.getElementById('uploadProgress').classList.add('show');
        document.getElementById('uploadStatus').textContent = 'Uploading ' + file.name + '...';
        document.getElementById('progressBar').style.width = '0%';

        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                document.getElementById('progressBar').style.width = percent + '%';
                document.getElementById('uploadStatus').textContent = 'Uploading... ' + percent + '%';
            }
        });

        xhr.addEventListener('load', function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    showAlert(response.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert(response.message, 'danger');
                    document.getElementById('uploadProgress').classList.remove('show');
                }
            } else {
                try {
                    const response = JSON.parse(xhr.responseText);
                    // Handle version mismatch
                    if (response.version_mismatch) {
                        showVersionMismatchModal(response.module_version, response.core_version);
                    } else {
                        showAlert(response.message || 'Upload failed', 'danger');
                    }
                } catch (e) {
                    showAlert('Upload failed. Please try again.', 'danger');
                }
                document.getElementById('uploadProgress').classList.remove('show');
            }
        });

        xhr.addEventListener('error', function() {
            showAlert('Network error. Please try again.', 'danger');
            document.getElementById('uploadProgress').classList.remove('show');
        });

        xhr.open('POST', '{{ route("admin.modules.upload") }}');
        xhr.send(formData);
    }

    // Toggle Module (Enable/Disable)
    function toggleModule(name, action) {
        if (!confirm(`Are you sure you want to ${action} the "${name}" module?`)) {
            return;
        }

        const url = action === 'enable'
            ? '{{ url("admin/modules") }}/' + name + '/enable'
            : '{{ url("admin/modules") }}/' + name + '/disable';

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            showAlert('An error occurred. Please try again.', 'danger');
        });
    }

    // Delete Module
    function deleteModule(name) {
        if (!confirm(`Are you sure you want to uninstall the "${name}" module? A backup will be created.`)) {
            return;
        }

        fetch('{{ url("admin/modules") }}/' + name, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            showAlert('An error occurred. Please try again.', 'danger');
        });
    }

    // Sync Modules with License
    function syncModules() {
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<span class="loading-spinner"></span> Syncing...';
        btn.disabled = true;

        fetch('{{ route("admin.modules.sync") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            showAlert('Sync failed. Please try again.', 'danger');
        })
        .finally(() => {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        });
    }

    // Show Alert
    function showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-float alert-dismissible fade show" role="alert">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        const container = document.getElementById('alertContainer');
        container.innerHTML = alertHtml;

        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }
        }, 5000);
    }

    // Close modal on outside click
    document.getElementById('uploadModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeUploadModal();
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeUploadModal();
            closeVersionMismatchModal();
        }
    });

    // Version Mismatch Modal Functions
    function showVersionMismatchModal(moduleVersion, coreVersion) {
        closeUploadModal();
        document.getElementById('moduleVersionBadge').textContent = 'v' + moduleVersion;
        document.getElementById('coreVersionBadge').textContent = 'v' + coreVersion;
        document.getElementById('versionMismatchModal').classList.add('show');
    }

    function closeVersionMismatchModal() {
        document.getElementById('versionMismatchModal').classList.remove('show');
    }

    // Close version mismatch modal on outside click
    document.getElementById('versionMismatchModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeVersionMismatchModal();
        }
    });
</script>
@endsection
