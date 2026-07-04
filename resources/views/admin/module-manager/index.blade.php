@extends('layouts.master')

@section('styles')
<style>
    .module-manager-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 25px;
    }

    .module-manager-header h4 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .module-manager-header p {
        opacity: 0.9;
        margin-bottom: 0;
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

    .module-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .module-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .module-card.active {
        border-color: #28a745;
    }

    .module-card.inactive {
        opacity: 0.8;
    }

    .module-card.not-installed {
        border-style: dashed;
        border-color: #ffc107;
    }

    .module-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .module-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .module-name {
        font-size: 18px;
        font-weight: 600;
        color: #212529;
        margin-bottom: 5px;
    }

    .module-version {
        font-size: 12px;
        color: #6c757d;
        background: #f1f3f4;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .module-description {
        color: #6c757d;
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 15px;
        flex: 1;
    }

    .module-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }

    .badge-status {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-not-installed {
        background: #fff3cd;
        color: #856404;
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

    .module-footer {
        margin-top: auto;
        padding-top: 15px;
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
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
    }

    .btn-delete:hover {
        background: #5a6268;
    }

    .btn-info {
        background: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background: #138496;
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

    /* Stats Cards */
    .stats-row {
        margin-bottom: 25px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        border: 1px solid #dee2e6;
    }

    .stat-card i {
        font-size: 28px;
        margin-bottom: 10px;
    }

    .stat-card .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #212529;
    }

    .stat-card .stat-label {
        color: #6c757d;
        font-size: 13px;
    }

    .stat-active i { color: #28a745; }
    .stat-inactive i { color: #dc3545; }
    .stat-premium i { color: #667eea; }
    .stat-total i { color: #17a2b8; }

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

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #212529;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #667eea;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="module-manager-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4><i class="fas fa-puzzle-piece me-2"></i> Module Manager</h4>
                <p>Install, manage, and configure system modules</p>
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

    <!-- Stats -->
    <div class="stats-row">
        <div class="row">
            <div class="col-6 col-md-3 mb-3">
                <div class="stat-card stat-total">
                    <i class="fas fa-cubes"></i>
                    <div class="stat-number">{{ count($modules) }}</div>
                    <div class="stat-label">Total Modules</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="stat-card stat-active">
                    <i class="fas fa-check-circle"></i>
                    <div class="stat-number">{{ collect($modules)->where('status', 'Active')->count() }}</div>
                    <div class="stat-label">Active</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="stat-card stat-inactive">
                    <i class="fas fa-times-circle"></i>
                    <div class="stat-number">{{ collect($modules)->where('status', '!=', 'Active')->count() }}</div>
                    <div class="stat-label">Inactive</div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="stat-card stat-premium">
                    <i class="fas fa-crown"></i>
                    <div class="stat-number">{{ collect($modules)->where('is_premium', true)->count() }}</div>
                    <div class="stat-label">Premium</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modules Grid -->
    <h5 class="section-title">
        <i class="fas fa-th-large"></i> Installed Modules
    </h5>
    <div class="row">
        @forelse($modules as $module)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="module-card {{ $module['status'] === 'Active' ? 'active' : ($module['exists'] ? 'inactive' : 'not-installed') }}">
                <div class="module-header">
                    <div class="module-icon" style="background: {{ $module['color'] }}">
                        <i class="{{ $module['icon'] }}"></i>
                    </div>
                    <span class="module-version">v{{ $module['version'] }}</span>
                </div>

                <h5 class="module-name">{{ $module['display_name'] }}</h5>
                <p class="module-description">{{ $module['description'] }}</p>

                <div class="module-badges">
                    @if($module['status'] === 'Active')
                        <span class="badge-status badge-active">Active</span>
                    @elseif($module['status'] === 'Not Installed')
                        <span class="badge-status badge-not-installed">Not Installed</span>
                    @else
                        <span class="badge-status badge-inactive">{{ $module['status'] }}</span>
                    @endif

                    @if($module['is_premium'])
                        <span class="badge-status badge-premium"><i class="fas fa-crown me-1"></i> Premium</span>
                    @endif

                    @if($module['is_licensed'])
                        <span class="badge-status badge-licensed"><i class="fas fa-key me-1"></i> Licensed</span>
                    @else
                        <span class="badge-status badge-unlicensed"><i class="fas fa-lock me-1"></i> Not Licensed</span>
                    @endif
                </div>

                <div class="module-footer">
                    <div class="module-actions">
                        @if($module['exists'])
                            @if($module['is_enabled'])
                                <button class="btn-module btn-disable" onclick="toggleModule('{{ $module['name'] }}', 'disable')" title="Disable Module">
                                    <i class="fas fa-power-off"></i> Disable
                                </button>
                            @else
                                @if($module['is_licensed'])
                                    <button class="btn-module btn-enable" onclick="toggleModule('{{ $module['name'] }}', 'enable')" title="Enable Module">
                                        <i class="fas fa-power-off"></i> Enable
                                    </button>
                                @else
                                    <button class="btn-module btn-info" disabled title="License Required">
                                        <i class="fas fa-lock"></i> License Required
                                    </button>
                                @endif
                            @endif
                            <button class="btn-module btn-delete" onclick="deleteModule('{{ $module['name'] }}')" title="Uninstall Module">
                                <i class="fas fa-trash"></i>
                            </button>
                        @else
                            <span class="text-muted small">Upload to install</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-puzzle-piece fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No modules configured</h5>
                <p class="text-muted">Upload a module to get started</p>
            </div>
        </div>
        @endforelse
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
@endsection

@section('scripts')
<script>
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
                    showAlert(response.message || 'Upload failed', 'danger');
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

        xhr.open('POST', '{{ route("admin.module-manager.upload") }}');
        xhr.send(formData);
    }

    // Toggle Module (Enable/Disable)
    function toggleModule(name, action) {
        if (!confirm(`Are you sure you want to ${action} the "${name}" module?`)) {
            return;
        }

        const url = action === 'enable'
            ? '{{ url("admin/module-manager") }}/' + name + '/enable'
            : '{{ url("admin/module-manager") }}/' + name + '/disable';

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

        fetch('{{ url("admin/module-manager") }}/' + name, {
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

        fetch('{{ route("admin.module-manager.sync") }}', {
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
        }
    });
</script>
@endsection
