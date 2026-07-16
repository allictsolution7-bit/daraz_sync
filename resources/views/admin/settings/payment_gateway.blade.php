@extends('layouts.master')

@section('styles')
<style>
    .settings-container {
        margin: 20px 0;
    }

    .settings-content {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .tab-header {
        border-bottom: 2px solid #197A94;
        padding-bottom: 15px;
        margin-bottom: 30px;
    }

    .tab-title {
        color: #197A94;
        font-size: 24px;
        font-weight: 600;
        margin: 0;
    }

    .save-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        background: #197A94;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        transition: all 0.2s;
    }

    .save-button:hover {
        background: #0056b3;
        transform: translateY(-1px);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Payment Settings</h6>
            <button type="submit" form="paymentSettingsForm" class="save-button">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form id="paymentSettingsForm" action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <div class="settings-container">
            <div class="settings-content">
                <div class="tab-header">
                    <h2 class="tab-title">Manual Payment Gateways</h2>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label">Enable Cash on Delivery</label>
                    <div class="col-md-9">
                        <select name="ecommerce[cod]" class="form-control">
                            <option value="1"
                                {{ setting('ecommerce', 'cod', '1') == '1' ? 'selected' : '' }}>
                                Show</option>
                            <option value="0"
                                {{ setting('ecommerce', 'cod', '1') == '0' ? 'selected' : '' }}>
                                Hide</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-md-3 col-form-label">Enable Bkash</label>
                    <div class="col-md-9">
                        <select name="ecommerce[bkash]" class="form-control">
                            <option value="1"
                                {{ setting('ecommerce', 'bkash', '1') == '1' ? 'selected' : '' }}>
                                Show</option>
                            <option value="0"
                                {{ setting('ecommerce', 'bkash', '1') == '0' ? 'selected' : '' }}>
                                Hide</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-md-3 col-form-label">Bkash Payment Number</label>
                    <div class="col-md-9">
                        <input type="text" name="ecommerce[bkash_number]" class="form-control"
                            value="{{ setting('ecommerce', 'bkash_number', '') }}" placeholder="01XXXXXXXXX">
                        <small class="form-text text-muted">Number displayed to customers for manual bKash payments.</small>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-md-3 col-form-label">Enable Nagad</label>
                    <div class="col-md-9">
                        <select name="ecommerce[nagad]" class="form-control">
                            <option value="1"
                                {{ setting('ecommerce', 'nagad', '1') == '1' ? 'selected' : '' }}>
                                Show</option>
                            <option value="0"
                                {{ setting('ecommerce', 'nagad', '1') == '0' ? 'selected' : '' }}>
                                Hide</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-md-3 col-form-label">Nagad Payment Number</label>
                    <div class="col-md-9">
                        <input type="text" name="ecommerce[nagad_number]" class="form-control"
                            value="{{ setting('ecommerce', 'nagad_number', '') }}" placeholder="01XXXXXXXXX">
                        <small class="form-text text-muted">Number displayed to customers for manual Nagad payments.</small>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-md-3 col-form-label">Enable Rocket</label>
                    <div class="col-md-9">
                        <select name="ecommerce[rocket]" class="form-control">
                            <option value="1"
                                {{ setting('ecommerce', 'rocket', '1') == '1' ? 'selected' : '' }}>
                                Show</option>
                            <option value="0"
                                {{ setting('ecommerce', 'rocket', '1') == '0' ? 'selected' : '' }}>
                                Hide</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row mt-3">
                    <label class="col-md-3 col-form-label">Rocket Payment Number</label>
                    <div class="col-md-9">
                        <input type="text" name="ecommerce[rocket_number]" class="form-control"
                            value="{{ setting('ecommerce', 'rocket_number', '') }}" placeholder="01XXXXXXXXX">
                        <small class="form-text text-muted">Number displayed to customers for manual Rocket payments.</small>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection
