@extends('layouts.master')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    /* Premium Dashboard Theme override */
    #woocommerce-migration-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
    }

    .migration-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.025);
        transition: box-shadow 0.3s ease;
    }

    .migration-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
    }

    .migration-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-top: 0;
        margin-bottom: 24px;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .migration-card h3 i {
        color: #6366f1;
    }

    /* Form Fields */
    .form-group label {
        font-weight: 600;
        color: #334155;
        font-size: 0.875rem;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 10px 16px;
        height: auto;
        font-size: 0.95rem;
        color: #0f172a;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background-color: #ffffff;
    }

    .form-text.text-muted {
        font-size: 0.775rem;
        color: #64748b !important;
        margin-top: 6px;
    }

    /* Status Badge & Connection status */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 9999px;
        font-size: 13px;
        font-weight: 600;
    }

    .connection-status {
        padding: 20px;
        border-radius: 12px;
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .connection-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .connection-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    /* Migration Stats Cards */
    .migration-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    @media (max-width: 992px) {
        .migration-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .migration-stats {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        position: relative;
        overflow: hidden;
        padding: 24px;
        border-radius: 14px;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
    }

    .stat-card h4 {
        margin: 0 0 8px 0;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        opacity: 0.9;
    }

    .stat-card .stat-number {
        font-size: 2.25rem;
        font-weight: 800;
        margin: 0;
    }

    /* Modern Gradients */
    .stat-card-categories { background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); }
    .stat-card-products { background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); }
    .stat-card-users { background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%); }
    .stat-card-orders { background: linear-gradient(135deg, #10b981 0%, #047857 100%); }

    /* Timeline Stepper */
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin: 35px 0;
        position: relative;
        gap: 15px;
    }

    .step-indicator::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 30px;
        right: 30px;
        height: 4px;
        background: #e2e8f0;
        z-index: 0;
        border-radius: 2px;
    }

    .step {
        position: relative;
        z-index: 1;
        background: transparent;
        text-align: center;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .step-number {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        border: 4px solid #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .step-label {
        margin-top: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        transition: color 0.3s ease;
    }

    .step.active .step-number {
        background: #6366f1;
        color: white;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
    }

    .step.active .step-label {
        color: #6366f1;
    }

    .step.completed .step-number {
        background: #10b981;
        color: white;
    }

    .step.completed .step-label {
        color: #10b981;
    }

    /* Dry Run notice */
    .dry-run-notice {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #b45309;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dry-run-switch {
        background: #f1f5f9;
        padding: 12px 20px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }

    /* Migration Buttons */
    .btn-action-premium {
        font-weight: 600;
        font-size: 0.9rem;
        padding: 12px 20px;
        border-radius: 10px;
        border: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    }

    .btn-action-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .btn-migrate-categories { background-color: #e0e7ff; color: #4338ca; }
    .btn-migrate-categories:hover { background-color: #c7d2fe; }

    .btn-migrate-products { background-color: #fce7f3; color: #be185d; }
    .btn-migrate-products:hover { background-color: #fbcfe8; }

    .btn-migrate-users { background-color: #e0f2fe; color: #0369a1; }
    .btn-migrate-users:hover { background-color: #bae6fd; }

    .btn-migrate-orders { background-color: #d1fae5; color: #047857; }
    .btn-migrate-orders:hover { background-color: #a7f3d0; }

    .btn-migrate-all {
        background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
        color: white;
        font-size: 1.05rem;
        padding: 16px 36px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
    }

    .btn-migrate-all:hover {
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    }

    /* Progress bar */
    .progress-container {
        margin-top: 30px;
        display: none;
    }

    .progress-bar-wrapper {
        width: 100%;
        height: 24px;
        background: #e2e8f0;
        border-radius: 9999px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #6366f1, #3b82f6);
        transition: width 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 9999px;
    }

    /* Custom Terminal Log */
    .migration-log {
        background: #0f172a;
        border: 1px solid #1e293b;
        border-radius: 12px;
        padding: 20px;
        max-height: 350px;
        overflow-y: auto;
        font-family: 'Fira Code', 'Courier New', monospace;
        font-size: 13px;
        line-height: 1.6;
        margin-top: 24px;
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.2);
        display: none;
    }

    .log-entry {
        padding: 4px 0;
        border-bottom: 1px solid #1e293b;
    }

    .log-success { color: #4ade80; }
    .log-error { color: #f87171; }
    .log-info { color: #60a5fa; }

    /* Layout overrides */
    .content-header h4 {
        font-weight: 800;
        color: #0f172a;
        font-size: 1.6rem;
    }
</style>
@endsection

@section('content')
<div id="woocommerce-migration-page">
    <div class="content-header py-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h4 class="m-0">
                        <i class="fas fa-exchange-alt mr-2" style="color: #6366f1;"></i> WooCommerce Sync Manager
                    </h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-transparent m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: #6366f1;">Home</a></li>
                        <li class="breadcrumb-item active">WooCommerce Migration</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            
            <div class="row">
                <!-- Left Column: Settings and Connection (Reduced width to col-lg-6) -->
                <div class="col-lg-6">
                    <!-- Settings Configuration -->
                    <div class="migration-card">
                        <h3>
                            <i class="fas fa-cog"></i> Connection Settings
                        </h3>
                        <form id="settings-form">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="connection_method">Connection Method</label>
                                <select name="connection_method" id="connection_method" class="form-control" onchange="toggleConnectionFields()">
                                    <option value="api" {{ ($settings->connection_method ?? 'api') == 'api' ? 'selected' : '' }}>REST API Connection</option>
                                    <option value="database" {{ ($settings->connection_method ?? 'api') == 'database' ? 'selected' : '' }}>Direct Database Access</option>
                                </select>
                                <small class="form-text text-muted">Choose your preferred mechanism for connecting to your WooCommerce storefront.</small>
                            </div>

                            <!-- API Settings -->
                            <div id="api-settings" style="display: {{ ($settings->connection_method ?? 'api') == 'api' ? 'block' : 'none' }};">
                                <h5 class="font-weight-bold text-dark mb-3" style="font-size: 0.95rem;">REST API CONFIGURATION</h5>
                                <div class="form-group mb-3">
                                    <label for="api_url">API URL</label>
                                    <input type="url" name="api_url" id="api_url" class="form-control" 
                                           value="{{ $settings->api_url ?? '' }}" 
                                           placeholder="https://yourstore.com">
                                    <small class="form-text text-muted">The base URL of your WooCommerce installation.</small>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="consumer_key">Consumer Key</label>
                                    <input type="text" name="consumer_key" id="consumer_key" class="form-control" 
                                           value="{{ $settings->consumer_key ?? '' }}" 
                                           placeholder="ck_...">
                                    <small class="form-text text-muted">Generated consumer key from WooCommerce -> Settings -> Advanced -> REST API.</small>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="consumer_secret">Consumer Secret</label>
                                    <input type="password" name="consumer_secret" id="consumer_secret" class="form-control" 
                                           value="{{ $settings->consumer_secret ?? '' }}" 
                                           placeholder="cs_...">
                                    <small class="form-text text-muted">Corresponding consumer secret key.</small>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="api_version">API Version</label>
                                            <input type="text" name="api_version" id="api_version" class="form-control" 
                                                   value="{{ $settings->api_version ?? 'wc/v3' }}" 
                                                   placeholder="wc/v3">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="verify_ssl">Verify SSL</label>
                                            <select name="verify_ssl" id="verify_ssl" class="form-control">
                                                <option value="1" {{ ($settings->verify_ssl ?? true) ? 'selected' : '' }}>Enforce Verification</option>
                                                <option value="0" {{ !($settings->verify_ssl ?? true) ? 'selected' : '' }}>Disable (Development Only)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Database Settings -->
                            <div id="database-settings" style="display: {{ ($settings->connection_method ?? 'api') == 'database' ? 'block' : 'none' }};">
                                <h5 class="font-weight-bold text-dark mb-3" style="font-size: 0.95rem;">DATABASE CONFIGURATION</h5>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group mb-3">
                                            <label for="db_host">Database Host</label>
                                            <input type="text" name="db_host" id="db_host" class="form-control" 
                                                   value="{{ $settings->db_host ?? '' }}" 
                                                   placeholder="127.0.0.1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="db_port">Port</label>
                                            <input type="text" name="db_port" id="db_port" class="form-control" 
                                                   value="{{ $settings->db_port ?? '3306' }}" 
                                                   placeholder="3306">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="db_database">Database Name</label>
                                    <input type="text" name="db_database" id="db_database" class="form-control" 
                                           value="{{ $settings->db_database ?? '' }}" 
                                           placeholder="wordpress_db">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="db_username">Database Username</label>
                                            <input type="text" name="db_username" id="db_username" class="form-control" 
                                                   value="{{ $settings->db_username ?? '' }}" 
                                                   placeholder="username">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="db_password">Database Password</label>
                                            <input type="password" name="db_password" id="db_password" class="form-control" 
                                                   value="{{ $settings->db_password ?? '' }}" 
                                                   placeholder="password">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="db_table_prefix">Table Prefix</label>
                                    <input type="text" name="db_table_prefix" id="db_table_prefix" class="form-control" 
                                           value="{{ $settings->db_table_prefix ?? 'wp_' }}" 
                                           placeholder="wp_">
                                    <small class="form-text text-muted">WordPress installation table prefix (default is usually 'wp_').</small>
                                </div>
                            </div>

                            <div class="form-group mt-4 mb-0">
                                <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px; font-weight:600; background-color:#6366f1; border-color:#6366f1;">
                                    <i class="fas fa-save mr-2"></i> Save Connection Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Status (Increased width to col-lg-6) -->
                <div class="col-lg-6">
                    <!-- Connection Status -->
                    <div class="migration-card" style="min-height: 280px;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="m-0 font-weight-bold" style="font-size: 1.05rem; color:#0f172a;">
                                <i class="fas fa-plug mr-2" style="color:#6366f1;"></i> Status
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius:8px;" onclick="testConnection()">
                                <i class="fas fa-sync-alt"></i> Test Connection
                            </button>
                        </div>
                        <div id="connection-status" class="connection-status">
                            <i class="fas fa-spinner fa-spin"></i> Checking connection...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Migration Statistics (Full width row) -->
            <div class="migration-card">
                <h3><i class="fas fa-chart-bar"></i> Synced Record Stats</h3>
                <div class="migration-stats">
                    <div class="stat-card stat-card-categories">
                        <h4>Categories</h4>
                        <p class="stat-number">{{ $migrationStats['categories']['migrated'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card stat-card-products">
                        <h4>Products</h4>
                        <p class="stat-number">{{ $migrationStats['products']['migrated'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card stat-card-users">
                        <h4>Customers</h4>
                        <p class="stat-number">{{ $migrationStats['users']['migrated'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card stat-card-orders">
                        <h4>Orders</h4>
                        <p class="stat-number">{{ $migrationStats['orders']['migrated'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- Migration Actions & Execution Controls -->
            <div class="migration-card">
                <h3><i class="fas fa-database"></i> Migration Controller</h3>
                
                <div class="dry-run-switch d-flex align-items-center justify-content-between">
                    <div>
                        <strong class="text-dark d-block">Simulation Mode (Dry Run)</strong>
                        <span class="text-muted" style="font-size: 0.8rem;">Perform a safe import simulation without changing store state.</span>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="dry-run-checkbox" onchange="toggleDryRun()">
                        <label class="custom-control-label" for="dry-run-checkbox"></label>
                    </div>
                </div>

                <div class="dry-run-notice" id="dry-run-notice" style="display: none;">
                    <i class="fas fa-info-circle" style="font-size: 1.25rem;"></i> 
                    <div>
                        <strong>Dry Run Active:</strong> Database mutation is suspended. Output results represent simulated transactions.
                    </div>
                </div>

                <!-- Stepper Progress -->
                <div class="step-indicator">
                    <div class="step" id="step-1">
                        <div class="step-number">1</div>
                        <div class="step-label">Categories</div>
                    </div>
                    <div class="step" id="step-2">
                        <div class="step-number">2</div>
                        <div class="step-label">Products</div>
                    </div>
                    <div class="step" id="step-3">
                        <div class="step-number">3</div>
                        <div class="step-label">Customers</div>
                    </div>
                    <div class="step" id="step-4">
                        <div class="step-number">4</div>
                        <div class="step-label">Orders</div>
                    </div>
                </div>

                <div class="text-center mt-5 mb-4">
                    <h5 class="font-weight-bold text-muted mb-3" style="font-size: 0.85rem; letter-spacing: 0.08em; text-transform: uppercase;">Partial Segment Sync</h5>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <button type="button" class="btn-action-premium btn-migrate-categories m-1" onclick="migrateEntity('categories')">
                            <i class="fas fa-tags"></i> Sync Categories
                        </button>
                        <button type="button" class="btn-action-premium btn-migrate-products m-1" onclick="migrateEntity('products')">
                            <i class="fas fa-box"></i> Sync Products
                        </button>
                        <button type="button" class="btn-action-premium btn-migrate-users m-1" onclick="migrateEntity('users')">
                            <i class="fas fa-users"></i> Sync Customers
                        </button>
                        <button type="button" class="btn-action-premium btn-migrate-orders m-1" onclick="migrateEntity('orders')">
                            <i class="fas fa-shopping-cart"></i> Sync Orders
                        </button>
                    </div>
                </div>

                <hr class="my-4" style="border-top: 1px dashed #cbd5e1;">

                <div class="text-center">
                    <h5 class="font-weight-bold text-muted mb-3" style="font-size: 0.85rem; letter-spacing: 0.08em; text-transform: uppercase;">Full Sync Orchestration</h5>
                    <button type="button" class="btn-action-premium btn-migrate-all" onclick="migrateAll()">
                        <i class="fas fa-rocket mr-2"></i> Launch Full Migration
                    </button>
                </div>

                <!-- Progress Bar -->
                <div class="progress-container" id="progress-container">
                    <div class="progress-bar-wrapper">
                        <div class="progress-fill" id="progress-fill" style="width: 0%">0%</div>
                    </div>
                </div>

                <!-- Migration Log Console -->
                <div class="migration-log" id="migration-log"></div>
            </div>
        </div>
    </section>
</div>
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

    // Make database/api fields reactive on connection method change
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
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving Settings...';
        
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
        statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Performing connection check...';
        
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
                statusDiv.innerHTML = '<i class="fas fa-check-circle mr-2" style="font-size: 1.2rem;"></i> <div><strong>Connection Established</strong><br><span style="font-size:0.8rem; opacity:0.9;">' + data.message + '</span></div>';
            } else {
                statusDiv.className = 'connection-status connection-error';
                statusDiv.innerHTML = '<i class="fas fa-times-circle mr-2" style="font-size: 1.2rem;"></i> <div><strong>Connection offline</strong><br><span style="font-size:0.8rem; opacity:0.9;">' + data.message + '</span></div>';
            }
        })
        .catch(error => {
            statusDiv.className = 'connection-status connection-error';
            statusDiv.innerHTML = '<i class="fas fa-times-circle mr-2" style="font-size: 1.2rem;"></i> <div><strong>Network error</strong><br><span style="font-size:0.8rem; opacity:0.9;">' + error.message + '</span></div>';
        });
    }

    // Keep log console clean and responsive
    function addLog(message, type = 'info') {
        const log = document.getElementById('migration-log');
        const entry = document.createElement('div');
        entry.className = `log-entry log-${type}`;
        entry.innerHTML = `[${new Date().toLocaleTimeString()}] ${message}`;
        log.appendChild(entry);
        log.scrollTop = log.scrollHeight;
    }

    function migrateEntity(entity) {
        if (currentMigration) {
            if (!confirm('A sync process is currently running. Do you want to start another one?')) {
                return;
            }
        }

        const endpoint = `{{ url('admin/woocommerce-migration/migrate') }}/${entity}`;
        
        showProgress();
        addLog(`Initiating ${entity} sync batch...`, 'info');
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
                addLog(`${entity} sync completed successfully!`, 'success');
                displayStats(data.stats, entity);
                updateMigrationStats();
            } else {
                addLog(`Sync batch failed: ${data.message}`, 'error');
            }
        })
        .catch(error => {
            updateProgress(0);
            hideProgress();
            addLog(`Network exception: ${error.message}`, 'error');
        });
    }

    function migrateAll() {
        if (!confirm('This will execute a complete synchronization with WooCommerce. Depending on catalog size, this may take several minutes. Proceed?')) {
            return;
        }

        if (currentMigration) {
            alert('A migration is already in progress.');
            return;
        }

        currentMigration = true;
        showProgress();
        addLog('Launching full synchronization pipeline...', 'info');
        
        const steps = ['categories', 'products', 'users', 'orders'];
        let currentStep = 0;

        function runNextStep() {
            if (currentStep >= steps.length) {
                hideProgress();
                addLog('Full synchronization workflow finished!', 'success');
                currentMigration = false;
                updateMigrationStats();
                return;
            }

            const step = steps[currentStep];
            updateStepIndicator(currentStep + 1, 'active');
            addLog(`Processing pipeline: ${step}...`, 'info');

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
                    addLog(`${step} sync: ${data.stats.created} imported, ${data.stats.updated} updated`, 'success');
                    displayStats(data.stats, step);
                } else {
                    addLog(`${step} sync failed: ${data.message}`, 'error');
                }
                
                currentStep++;
                setTimeout(runNextStep, 1000); // Wait 1 second before next step
            })
            .catch(error => {
                addLog(`Error during ${step} pipeline: ${error.message}`, 'error');
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
        let displayEntity = entity === 'users' ? 'CUSTOMERS' : entity.toUpperCase();
        let message = `[REPORT] ${displayEntity}: `;
        message += `Processed: ${stats.total}, `;
        message += `Created: ${stats.created}, `;
        message += `Updated: ${stats.updated}, `;
        message += `Skipped: ${stats.skipped}`;
        
        if (stats.errors && stats.errors.length > 0) {
            message += `, Errors: ${stats.errors.length}`;
            stats.errors.slice(0, 5).forEach(error => {
                addLog(`  - ERR: ${error}`, 'error');
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
        fill.textContent = Math.round(percent) + '%';
    }

    function updateMigrationStats() {
        // Reload page to update stats
        setTimeout(() => {
            location.reload();
        }, 2500);
    }
</script>
@endsection
