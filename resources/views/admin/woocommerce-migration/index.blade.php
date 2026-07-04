@extends('layouts.master')

@section('styles')
<style>
    .migration-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .migration-card h3 {
        margin-top: 0;
        margin-bottom: 15px;
        color: #495057;
        border-bottom: 2px solid #197A94;
        padding-bottom: 10px;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 10px;
    }

    .status-success {
        background: #d4edda;
        color: #155724;
    }

    .status-error {
        background: #f8d7da;
        color: #721c24;
    }

    .status-warning {
        background: #fff3cd;
        color: #856404;
    }

    .migration-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
    }

    .stat-card h4 {
        margin: 0 0 10px 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .stat-card .stat-number {
        font-size: 32px;
        font-weight: bold;
        margin: 0;
    }

    .migration-button {
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin: 5px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .migration-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .btn-primary-migration {
        background: #197A94;
        color: white;
    }

    .btn-success-migration {
        background: #28a745;
        color: white;
    }

    .btn-warning-migration {
        background: #ffc107;
        color: #212529;
    }

    .btn-danger-migration {
        background: #dc3545;
        color: white;
    }

    .progress-container {
        margin-top: 15px;
        display: none;
    }

    .progress-bar {
        width: 100%;
        height: 30px;
        background: #e9ecef;
        border-radius: 15px;
        overflow: hidden;
        position: relative;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #197A94, #0056b3);
        transition: width 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }

    .migration-log {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 15px;
        max-height: 400px;
        overflow-y: auto;
        font-family: 'Courier New', monospace;
        font-size: 13px;
        margin-top: 15px;
        display: none;
    }

    .log-entry {
        padding: 5px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .log-success {
        color: #28a745;
    }

    .log-error {
        color: #dc3545;
    }

    .log-info {
        color: #197A94;
    }

    .connection-status {
        padding: 15px;
        border-radius: 6px;
        margin-top: 15px;
    }

    .connection-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .connection-error {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin: 20px 0;
        position: relative;
    }

    .step-indicator::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #dee2e6;
        z-index: 0;
    }

    .step {
        position: relative;
        z-index: 1;
        background: white;
        padding: 10px;
        text-align: center;
        flex: 1;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: bold;
    }

    .step.active .step-number {
        background: #197A94;
        color: white;
    }

    .step.completed .step-number {
        background: #28a745;
        color: white;
    }

    .dry-run-notice {
        background: #fff3cd;
        border: 1px solid #ffc107;
        color: #856404;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">WooCommerce Migration</li>
                    </ol>
                </div>
                <div class="col-sm-12">
                    <h4 class="m-0">
                        <i class="fas fa-exchange-alt"></i> WooCommerce Migration
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <!-- Settings Configuration -->
            <div class="migration-card">
                <h3>
                    <i class="fas fa-cog"></i> WooCommerce Connection Settings
                </h3>
                <form id="settings-form">
                    @csrf
                    <div class="form-group">
                        <label for="connection_method">Connection Method</label>
                        <select name="connection_method" id="connection_method" class="form-control" onchange="toggleConnectionFields()">
                            <option value="api" {{ ($settings->connection_method ?? 'api') == 'api' ? 'selected' : '' }}>REST API</option>
                            <option value="database" {{ ($settings->connection_method ?? 'api') == 'database' ? 'selected' : '' }}>Direct Database</option>
                        </select>
                        <small class="form-text text-muted">Choose how to connect to your WooCommerce store</small>
                    </div>

                    <!-- API Settings -->
                    <div id="api-settings" style="display: {{ ($settings->connection_method ?? 'api') == 'api' ? 'block' : 'none' }};">
                        <h5 class="mt-3 mb-3">API Configuration</h5>
                        <div class="form-group">
                            <label for="api_url">API URL</label>
                            <input type="url" name="api_url" id="api_url" class="form-control" 
                                   value="{{ $settings->api_url ?? '' }}" 
                                   placeholder="https://yourstore.com">
                            <small class="form-text text-muted">Your WooCommerce store URL</small>
                        </div>
                        <div class="form-group">
                            <label for="consumer_key">Consumer Key</label>
                            <input type="text" name="consumer_key" id="consumer_key" class="form-control" 
                                   value="{{ $settings->consumer_key ?? '' }}" 
                                   placeholder="ck_...">
                            <small class="form-text text-muted">WooCommerce REST API Consumer Key</small>
                        </div>
                        <div class="form-group">
                            <label for="consumer_secret">Consumer Secret</label>
                            <input type="password" name="consumer_secret" id="consumer_secret" class="form-control" 
                                   value="{{ $settings->consumer_secret ?? '' }}" 
                                   placeholder="cs_...">
                            <small class="form-text text-muted">WooCommerce REST API Consumer Secret</small>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="api_version">API Version</label>
                                    <input type="text" name="api_version" id="api_version" class="form-control" 
                                           value="{{ $settings->api_version ?? 'wc/v3' }}" 
                                           placeholder="wc/v3">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="verify_ssl">Verify SSL</label>
                                    <select name="verify_ssl" id="verify_ssl" class="form-control">
                                        <option value="1" {{ ($settings->verify_ssl ?? true) ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ !($settings->verify_ssl ?? true) ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Database Settings -->
                    <div id="database-settings" style="display: {{ ($settings->connection_method ?? 'api') == 'database' ? 'block' : 'none' }};">
                        <h5 class="mt-3 mb-3">Database Configuration</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="db_host">Database Host</label>
                                    <input type="text" name="db_host" id="db_host" class="form-control" 
                                           value="{{ $settings->db_host ?? '' }}" 
                                           placeholder="127.0.0.1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="db_port">Database Port</label>
                                    <input type="text" name="db_port" id="db_port" class="form-control" 
                                           value="{{ $settings->db_port ?? '3306' }}" 
                                           placeholder="3306">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="db_database">Database Name</label>
                            <input type="text" name="db_database" id="db_database" class="form-control" 
                                   value="{{ $settings->db_database ?? '' }}" 
                                   placeholder="wordpress_db">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="db_username">Database Username</label>
                                    <input type="text" name="db_username" id="db_username" class="form-control" 
                                           value="{{ $settings->db_username ?? '' }}" 
                                           placeholder="username">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="db_password">Database Password</label>
                                    <input type="password" name="db_password" id="db_password" class="form-control" 
                                           value="{{ $settings->db_password ?? '' }}" 
                                           placeholder="password">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="db_table_prefix">Table Prefix</label>
                            <input type="text" name="db_table_prefix" id="db_table_prefix" class="form-control" 
                                   value="{{ $settings->db_table_prefix ?? 'wp_' }}" 
                                   placeholder="wp_">
                            <small class="form-text text-muted">Usually 'wp_' for WordPress/WooCommerce</small>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Connection Status -->
            <div class="migration-card">
                <h6>
                    <i class="fas fa-plug"></i> Connection Status
                    <button type="button" class="btn btn-sm btn-primary float-right" onclick="testConnection()">
                        <i class="fas fa-sync-alt"></i> Test Connection
                    </button>
                </h6>
                <div id="connection-status" class="connection-status">
                    <i class="fas fa-spinner fa-spin"></i> Checking connection...
                </div>
            </div>

            <!-- Migration Statistics -->
            <div class="migration-card">
                <h3><i class="fas fa-chart-bar"></i> Migration Statistics</h3>
                <div class="migration-stats">
                    <div class="stat-card">
                        <h4>Categories</h4>
                        <p class="stat-number">{{ $migrationStats['categories']['migrated'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <h4>Products</h4>
                        <p class="stat-number">{{ $migrationStats['products']['migrated'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <h4>Users</h4>
                        <p class="stat-number">{{ $migrationStats['users']['migrated'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                        <h4>Orders</h4>
                        <p class="stat-number">{{ $migrationStats['orders']['migrated'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Migration Options -->
            <div class="migration-card">
                <h3><i class="fas fa-database"></i> Migration Options</h3>
                
                <div class="dry-run-notice" id="dry-run-notice" style="display: none;">
                    <i class="fas fa-info-circle"></i> <strong>Dry Run Mode:</strong> This will simulate the migration without making any changes to your database.
                </div>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="dry-run-checkbox" onchange="toggleDryRun()">
                    <label class="form-check-label" for="dry-run-checkbox">
                        Enable Dry Run Mode (Test without making changes)
                    </label>
                </div>

                <div class="step-indicator">
                    <div class="step" id="step-1">
                        <div class="step-number">1</div>
                        <div>Categories</div>
                    </div>
                    <div class="step" id="step-2">
                        <div class="step-number">2</div>
                        <div>Products</div>
                    </div>
                    <div class="step" id="step-3">
                        <div class="step-number">3</div>
                        <div>Users</div>
                    </div>
                    <div class="step" id="step-4">
                        <div class="step-number">4</div>
                        <div>Orders</div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <h4>Individual Migration</h4>
                    <button type="button" class="migration-button btn-primary-migration" onclick="migrateEntity('categories')">
                        <i class="fas fa-tags"></i> Migrate Categories
                    </button>
                    <button type="button" class="migration-button btn-success-migration" onclick="migrateEntity('products')">
                        <i class="fas fa-box"></i> Migrate Products
                    </button>
                    <button type="button" class="migration-button btn-warning-migration" onclick="migrateEntity('users')">
                        <i class="fas fa-users"></i> Migrate Users
                    </button>
                    <button type="button" class="migration-button btn-danger-migration" onclick="migrateEntity('orders')">
                        <i class="fas fa-shopping-cart"></i> Migrate Orders
                    </button>
                </div>

                <div class="text-center mt-3">
                    <h4>Full Migration</h4>
                    <button type="button" class="migration-button btn-primary-migration" style="padding: 15px 40px; font-size: 16px;" onclick="migrateAll()">
                        <i class="fas fa-rocket"></i> Migrate All Data
                    </button>
                </div>

                <!-- Progress Bar -->
                <div class="progress-container" id="progress-container">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill" style="width: 0%">0%</div>
                    </div>
                </div>

                <!-- Migration Log -->
                <div class="migration-log" id="migration-log"></div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    let isDryRun = false;
    let currentMigration = null;

    // Test connection on page load
    document.addEventListener('DOMContentLoaded', function() {
        testConnection();
        
        // Handle settings form submission
        document.getElementById('settings-form').addEventListener('submit', function(e) {
            e.preventDefault();
            saveSettings();
        });
    });

    function toggleConnectionFields() {
        const method = document.getElementById('connection_method').value;
        const apiSettings = document.getElementById('api-settings');
        const dbSettings = document.getElementById('database-settings');
        
        if (method === 'api') {
            apiSettings.style.display = 'block';
            dbSettings.style.display = 'none';
        } else {
            apiSettings.style.display = 'none';
            dbSettings.style.display = 'block';
        }
    }

    function saveSettings() {
        const form = document.getElementById('settings-form');
        const formData = new FormData(form);
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        
        fetch('{{ route("admin.woocommerce-migration.settings.save") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Settings saved successfully!');
                // Clear cache and reload connection
                testConnection();
            } else {
                alert('Failed to save settings: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error saving settings: ' + error.message);
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }

    function toggleDryRun() {
        isDryRun = document.getElementById('dry-run-checkbox').checked;
        document.getElementById('dry-run-notice').style.display = isDryRun ? 'block' : 'none';
    }

    function testConnection() {
        const statusDiv = document.getElementById('connection-status');
        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing connection...';
        
        fetch('{{ route("admin.woocommerce-migration.test-connection") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusDiv.className = 'connection-status connection-success';
                statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> <strong>Connected Successfully!</strong> ' + data.message;
            } else {
                statusDiv.className = 'connection-status connection-error';
                statusDiv.innerHTML = '<i class="fas fa-times-circle"></i> <strong>Connection Failed:</strong> ' + data.message;
            }
        })
        .catch(error => {
            statusDiv.className = 'connection-status connection-error';
            statusDiv.innerHTML = '<i class="fas fa-times-circle"></i> <strong>Error:</strong> ' + error.message;
        });
    }

    function migrateEntity(entity) {
        if (currentMigration) {
            if (!confirm('A migration is already in progress. Do you want to continue?')) {
                return;
            }
        }

        const endpoint = `{{ url('admin/woocommerce-migration/migrate') }}/${entity}`;
        
        showProgress();
        addLog(`Starting ${entity} migration...`, 'info');
        updateProgress(30);
        
        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                dry_run: isDryRun
            })
        })
        .then(response => response.json())
        .then(data => {
            updateProgress(100);
            setTimeout(() => hideProgress(), 500);
            
            if (data.success) {
                addLog(`${entity} migration completed successfully!`, 'success');
                displayStats(data.stats, entity);
                updateMigrationStats();
            } else {
                addLog(`Migration failed: ${data.message}`, 'error');
            }
        })
        .catch(error => {
            updateProgress(0);
            hideProgress();
            addLog(`Error: ${error.message}`, 'error');
        });
    }

    function migrateAll() {
        if (!confirm('This will migrate all data from WooCommerce. This may take a while. Continue?')) {
            return;
        }

        if (currentMigration) {
            alert('A migration is already in progress.');
            return;
        }

        currentMigration = true;
        showProgress();
        addLog('Starting full migration...', 'info');
        
        const steps = ['categories', 'products', 'users', 'orders'];
        let currentStep = 0;

        function runNextStep() {
            if (currentStep >= steps.length) {
                hideProgress();
                addLog('Full migration completed!', 'success');
                currentMigration = false;
                updateMigrationStats();
                return;
            }

            const step = steps[currentStep];
            updateStepIndicator(currentStep + 1, 'active');
            addLog(`Migrating ${step}...`, 'info');

            const endpoint = `{{ url('admin/woocommerce-migration/migrate') }}/${step}`;
            const stepPercent = ((currentStep + 1) / steps.length) * 100;
            updateProgress(stepPercent);
            
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    dry_run: isDryRun
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateStepIndicator(currentStep + 1, 'completed');
                    addLog(`${step} migrated: ${data.stats.created} created, ${data.stats.updated} updated`, 'success');
                    displayStats(data.stats, step);
                } else {
                    addLog(`${step} failed: ${data.message}`, 'error');
                }
                
                currentStep++;
                setTimeout(runNextStep, 1000); // Wait 1 second before next step
            })
            .catch(error => {
                addLog(`Error migrating ${step}: ${error.message}`, 'error');
                currentStep++;
                setTimeout(runNextStep, 1000);
            });
        }

        runNextStep();
    }

    function updateStepIndicator(stepNumber, status) {
        const step = document.getElementById(`step-${stepNumber}`);
        if (step) {
            step.className = `step ${status}`;
        }
    }

    function displayStats(stats, entity) {
        let message = `${entity.toUpperCase()} Migration: `;
        message += `Total: ${stats.total}, `;
        message += `Created: ${stats.created}, `;
        message += `Updated: ${stats.updated}, `;
        message += `Skipped: ${stats.skipped}`;
        
        if (stats.errors && stats.errors.length > 0) {
            message += `, Errors: ${stats.errors.length}`;
            stats.errors.slice(0, 5).forEach(error => {
                addLog(`  - ${error}`, 'error');
            });
        }
        
        addLog(message, stats.errors && stats.errors.length > 0 ? 'error' : 'success');
    }

    function showProgress() {
        document.getElementById('progress-container').style.display = 'block';
        document.getElementById('migration-log').style.display = 'block';
        updateProgress(0);
    }

    function hideProgress() {
        document.getElementById('progress-container').style.display = 'none';
    }

    function updateProgress(percent) {
        const fill = document.getElementById('progress-fill');
        fill.style.width = percent + '%';
        fill.textContent = percent + '%';
    }

    function addLog(message, type = 'info') {
        const log = document.getElementById('migration-log');
        const entry = document.createElement('div');
        entry.className = `log-entry log-${type}`;
        entry.innerHTML = `[${new Date().toLocaleTimeString()}] ${message}`;
        log.appendChild(entry);
        log.scrollTop = log.scrollHeight;
    }

    function updateMigrationStats() {
        // Reload page to update stats
        setTimeout(() => {
            location.reload();
        }, 2000);
    }
</script>
@endsection

