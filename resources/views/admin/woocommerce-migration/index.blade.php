@extends('layouts.master')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Ultra-Premium WooCommerce Migration Theme */
    #woocommerce-migration-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 20px;
        padding: 32px;
        color: white;
        box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.1), 0 10px 10px -5px rgba(15, 23, 42, 0.04);
        margin-bottom: 35px;
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, rgba(99, 102, 241, 0) 70%);
        border-radius: 50%;
    }

    .breadcrumb-custom {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #94a3b8;
        margin-bottom: 0;
        padding: 0;
        list-style: none;
    }

    .breadcrumb-custom a {
        color: #cbd5e1;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .breadcrumb-custom a:hover {
        color: #818cf8;
    }

    .breadcrumb-separator {
        color: #64748b;
    }

    .breadcrumb-active {
        color: #94a3b8;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.025em;
        margin: 0;
    }

    /* Premium Modern Cards */
    .migration-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 24px;
        padding: 32px;
        margin-bottom: 30px;
        box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.04), 0 4px 6px -2px rgba(15, 23, 42, 0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .migration-card:hover {
        box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 10px 10px -5px rgba(15, 23, 42, 0.04);
        transform: translateY(-3px);
    }

    .migration-card h3 {
        font-size: 1.3rem;
        font-weight: 800;
        margin-top: 0;
        margin-bottom: 28px;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 12px;
        letter-spacing: -0.02em;
    }

    .migration-card h3 i {
        color: #4f46e5;
        background: rgba(79, 70, 229, 0.08);
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    /* Form Design overrides */
    .form-group label {
        font-weight: 700;
        color: #344054;
        font-size: 0.85rem;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-control {
        border-radius: 12px;
        border: 1px solid #d0d5dd;
        padding: 12px 18px;
        height: auto;
        font-size: 0.95rem;
        color: #1d2939;
        background-color: #fcfcfd;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
        background-color: #ffffff;
    }

    .form-text.text-muted {
        font-size: 0.775rem;
        color: #667085 !important;
        margin-top: 6px;
    }

    /* Connection status section */
    .connection-status-panel {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px 24px;
        background: #f8fafc;
        border: 1px dashed #e2e8f0;
        border-radius: 20px;
        text-align: center;
        margin-top: 15px;
    }

    .status-icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 20px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.05);
    }

    .status-success-icon {
        background: #d1fae5;
        color: #059669;
    }

    .status-error-icon {
        background: #fee2e2;
        color: #dc2626;
    }

    .status-pending-icon {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .connection-status-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .connection-status-desc {
        font-size: 0.85rem;
        color: #64748b;
        max-width: 250px;
        margin: 0 auto;
        line-height: 1.4;
    }

    /* Synced Record Stats override */
    .migration-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-top: 15px;
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
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        transition: all 0.3s ease;
        min-height: 120px;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -3px rgba(15, 23, 42, 0.08);
        border-color: #cbd5e1;
    }

    .stat-card-details {
        z-index: 2;
    }

    .stat-card-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        margin-bottom: 8px;
    }

    .stat-card-number {
        font-size: 2.25rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        letter-spacing: -0.03em;
        line-height: 1;
    }

    .stat-card-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        z-index: 2;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.04);
    }

    /* Soft premium icon color schemes */
    .icon-categories { background: rgba(99, 102, 241, 0.08); color: #4f46e5; }
    .icon-products { background: rgba(236, 72, 153, 0.08); color: #db2777; }
    .icon-users { background: rgba(14, 165, 233, 0.08); color: #0284c7; }
    .icon-orders { background: rgba(16, 185, 129, 0.08); color: #059669; }

    /* Timeline Stepper */
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin: 40px 0;
        position: relative;
        gap: 15px;
    }

    .step-indicator::before {
        content: '';
        position: absolute;
        top: 27px;
        left: 40px;
        right: 40px;
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
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.15rem;
        border: 4px solid #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .step-label {
        margin-top: 12px;
        font-size: 0.875rem;
        font-weight: 700;
        color: #64748b;
        transition: color 0.3s ease;
    }

    .step.active .step-number {
        background: #4f46e5;
        color: white;
        box-shadow: 0 0 0 6px rgba(79, 70, 229, 0.2);
    }

    .step.active .step-label {
        color: #4f46e5;
    }

    .step.completed .step-number {
        background: #10b981;
        color: white;
        box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.15);
    }

    .step.completed .step-label {
        color: #10b981;
    }

    /* Dry Run styling */
    .dry-run-switch {
        background: #f8fafc;
        padding: 20px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }

    .dry-run-notice {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #b45309;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Migration Buttons */
    .btn-action-premium {
        font-weight: 700;
        font-size: 0.875rem;
        padding: 14px 24px;
        border-radius: 12px;
        border: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    }

    .btn-action-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(15, 23, 42, 0.08);
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
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: white;
        font-size: 1.05rem;
        padding: 16px 36px;
        border-radius: 12px;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
    }

    .btn-migrate-all:hover {
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.45);
        color: white;
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
        background: linear-gradient(90deg, #4f46e5, #3b82f6);
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
        padding: 6px 0;
        border-bottom: 1px solid #1e293b;
    }

    .log-success { color: #4ade80; }
    .log-error { color: #f87171; }
    .log-info { color: #60a5fa; }
</style>
@endsection

@section('content')
<div id="woocommerce-migration-page" class="container-fluid mt-4">
    <!-- Header Block -->
    <div class="dashboard-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-title"><i class="fas fa-exchange-alt mr-2" style="color: #818cf8;"></i> WooCommerce Sync Manager</h1>
        </div>
        <ul class="breadcrumb-custom">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i> Home</a></li>
            <li class="breadcrumb-separator"><i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i></li>
            <li class="breadcrumb-active">WooCommerce Migration</li>
        </ul>
    </div>

    <section class="content">
        <div class="row d-flex align-items-stretch">
            <!-- Left Column: Settings and Connection -->
            <div class="col-lg-6 d-flex flex-column">
                <!-- Settings Configuration -->
                <div class="migration-card flex-grow-1">
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
                            <h5 class="font-weight-bold text-dark mb-3" style="font-size: 0.85rem; letter-spacing: 0.05em;">REST API CONFIGURATION</h5>
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
                            <h5 class="font-weight-bold text-dark mb-3" style="font-size: 0.85rem; letter-spacing: 0.05em;">DATABASE CONFIGURATION</h5>
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

            <!-- Right Column: Status -->
            <div class="col-lg-6 d-flex flex-column">
                <!-- Connection Status -->
                <div class="migration-card flex-grow-1" style="display: flex; flex-direction: column;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="m-0">
                            <i class="fas fa-plug"></i> Status Check
                        </h3>
                        <button type="button" class="btn btn-sm btn-outline-primary" style="border-radius:8px; padding: 6px 14px; font-weight: 700;" onclick="testConnection()">
                            <i class="fas fa-sync-alt me-1"></i> Test Connection
                        </button>
                    </div>
                    
                    <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                        <div id="connection-status-wrapper" class="w-100">
                            <!-- JS Injectable Premium status loader panel -->
                            <div class="connection-status-panel">
                                <div class="status-icon-wrapper status-pending-icon">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                                <div class="connection-status-title">Checking Connection</div>
                                <div class="connection-status-desc">Validating WooCommerce credentials and network latency...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Migration Statistics (Full width row) -->
        <div class="migration-card">
            <h3><i class="fas fa-chart-bar"></i> Synced Record Stats</h3>
            <div class="migration-stats">
                <div class="stat-card">
                    <div class="stat-card-details">
                        <div class="stat-card-title">Categories</div>
                        <p class="stat-card-number" id="stats-categories-num">{{ $migrationStats['categories']['migrated'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon icon-categories">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-details">
                        <div class="stat-card-title">Products</div>
                        <p class="stat-card-number" id="stats-products-num">{{ $migrationStats['products']['migrated'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon icon-products">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-details">
                        <div class="stat-card-title">Customers</div>
                        <p class="stat-card-number" id="stats-users-num">{{ $migrationStats['users']['migrated'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon icon-users">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-card-details">
                        <div class="stat-card-title">Orders</div>
                        <p class="stat-card-number" id="stats-orders-num">{{ $migrationStats['orders']['migrated'] ?? 0 }}</p>
                    </div>
                    <div class="stat-card-icon icon-orders">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Migration Actions & Execution Controls -->
        <div class="migration-card">
            <h3><i class="fas fa-database"></i> Migration Controller</h3>
            
            <div class="dry-run-switch d-flex align-items-center justify-content-between">
                <div>
                    <strong class="text-dark d-block" style="font-size: 1rem;">Simulation Mode (Dry Run)</strong>
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
        const wrapper = document.getElementById('connection-status-wrapper');
        
        wrapper.innerHTML = `
            <div class="connection-status-panel">
                <div class="status-icon-wrapper status-pending-icon">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <div class="connection-status-title">Performing check...</div>
                <div class="connection-status-desc">Validating credentials and server response time.</div>
            </div>
        `;
        
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
                wrapper.innerHTML = `
                    <div class="connection-status-panel" style="background: #f0fdf4; border-color: #bbf7d0;">
                        <div class="status-icon-wrapper status-success-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="connection-status-title text-success">Connection Online</div>
                        <div class="connection-status-desc text-success" style="opacity: 0.85;">${data.message}</div>
                    </div>
                `;
            } else {
                wrapper.innerHTML = `
                    <div class="connection-status-panel" style="background: #fef2f2; border-color: #fecaca;">
                        <div class="status-icon-wrapper status-error-icon">
                            <i class="fas fa-times"></i>
                        </div>
                        <div class="connection-status-title text-danger">Connection Offline</div>
                        <div class="connection-status-desc text-danger" style="opacity: 0.85;">${data.message}</div>
                    </div>
                `;
            }
        })
        .catch(error => {
            wrapper.innerHTML = `
                <div class="connection-status-panel" style="background: #fef2f2; border-color: #fecaca;">
                    <div class="status-icon-wrapper status-error-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="connection-status-title text-danger">Network Error</div>
                    <div class="connection-status-desc text-danger" style="opacity: 0.85;">${error.message}</div>
                </div>
            `;
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
                
                // Update numbers directly on page
                const element = document.getElementById(`stats-${entity}-num`);
                if (element && data.stats.total) {
                    element.textContent = data.stats.total;
                }
                
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
                    
                    const element = document.getElementById(`stats-${step}-num`);
                    if (element && data.stats.total) {
                        element.textContent = data.stats.total;
                    }
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
