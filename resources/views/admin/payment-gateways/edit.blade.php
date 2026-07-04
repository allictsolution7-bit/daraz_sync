@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Configure {{ $gateway->name }}</h5>
                    <a href="{{ route('admin.payment-gateways.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.payment-gateways.update', $gateway) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- General Settings --}}
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">General Settings</h6>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Display Name</label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ old('name', $gateway->name) }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Currency</label>
                                    <input type="text" name="currency" class="form-control"
                                           value="{{ old('currency', $gateway->currency) }}" maxlength="3">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Sort Order</label>
                                    <input type="number" name="sort_order" class="form-control"
                                           value="{{ old('sort_order', $gateway->sort_order) }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_enabled" value="1"
                                               {{ $gateway->is_enabled ? 'checked' : '' }}>
                                        <label class="form-check-label">Enabled</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_live" value="1"
                                               {{ $gateway->is_live ? 'checked' : '' }}>
                                        <label class="form-check-label">Live Mode</label>
                                    </div>
                                    <small class="text-muted">When off, uses sandbox/test environment</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Fee Settings --}}
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Transaction Fees</h6>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Fee Percent (%)</label>
                                    <input type="number" step="0.01" name="transaction_fee_percent" class="form-control"
                                           value="{{ old('transaction_fee_percent', $gateway->transaction_fee_percent) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fixed Fee ({{ $gateway->currency }})</label>
                                    <input type="number" step="0.01" name="transaction_fee_fixed" class="form-control"
                                           value="{{ old('transaction_fee_fixed', $gateway->transaction_fee_fixed) }}">
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- Provider-Specific Configuration --}}
                        @if($gateway->provider === 'eps')
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">EPS Credentials</h6>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Merchant ID</label>
                                    <input type="text" name="merchant_id" class="form-control"
                                           value="{{ old('merchant_id', $gateway->additional_config['merchant_id'] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Store ID</label>
                                    <input type="text" name="store_id" class="form-control"
                                           value="{{ old('store_id', $gateway->additional_config['store_id'] ?? '') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Username (Email)</label>
                                    <input type="text" name="username" class="form-control"
                                           value="{{ old('username', $gateway->additional_config['username'] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control"
                                           value="{{ old('password', $gateway->additional_config['password'] ?? '') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hash Key</label>
                                <input type="text" name="hash_key" class="form-control"
                                       value="{{ old('hash_key', $gateway->additional_config['hash_key'] ?? '') }}">
                                <small class="text-muted">HMAC-SHA512 signing key provided by EPS</small>
                            </div>
                        </div>
                        @else
                        {{-- Generic API key fields for other gateways --}}
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">API Credentials</h6>
                            <div class="mb-3">
                                <label class="form-label">Public Key / API Key</label>
                                <input type="text" name="public_key" class="form-control"
                                       value="{{ old('public_key') }}"
                                       placeholder="Leave blank to keep existing">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Secret Key</label>
                                <input type="password" name="secret_key" class="form-control"
                                       value=""
                                       placeholder="Leave blank to keep existing">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Webhook Secret</label>
                                <input type="password" name="webhook_secret" class="form-control"
                                       value=""
                                       placeholder="Leave blank to keep existing">
                            </div>
                        </div>
                        @endif

                        <hr>

                        {{-- Callback URLs (read-only, for reference) --}}
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Callback URLs (for gateway configuration)</h6>
                            <div class="mb-2">
                                <label class="form-label small text-muted">Callback URL</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm bg-light" readonly
                                           value="{{ route('payment.callback', ['provider' => $gateway->provider]) }}">
                                    <button class="btn btn-outline-secondary btn-sm" type="button"
                                            onclick="navigator.clipboard.writeText(this.previousElementSibling.value)">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small text-muted">Webhook URL</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm bg-light" readonly
                                           value="{{ route('payment.webhook', ['provider' => $gateway->provider]) }}">
                                    <button class="btn btn-outline-secondary btn-sm" type="button"
                                            onclick="navigator.clipboard.writeText(this.previousElementSibling.value)">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
