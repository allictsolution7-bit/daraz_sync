@extends('layouts.master')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --primary-color: #197A94;
            --primary-hover: #136377;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --dark-color: #343a40;
            --light-bg: #fdfdfd;
            --card-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background-color: #f4f7f6;
        }

        .container {
            max-width: 900px;
            padding: 24px;
        }

        .invalid-key {
            border-color: var(--danger-color) !important;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.15) !important;
        }

        .key-error {
            color: var(--danger-color);
            font-size: 0.78rem;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        /* Settings Card Container */
        .settings-card {
            background: #ffffff;
            border: 1px solid #e3e8ec;
            border-radius: var(--border-radius);
            padding: 28px;
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
        }

        .settings-header {
            margin-bottom: 28px;
        }

        .settings-header h4 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 6px;
        }

        .settings-header p {
            color: #64748b;
            font-size: 0.9rem;
            margin: 0;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.88rem;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .form-control {
            height: 42px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 8px 14px;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(25, 122, 148, 0.15);
            outline: none;
        }

        .form-text {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 5px;
        }

        /* Option cards */
        .option.card {
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            padding: 20px;
            background: #f8fafc;
            margin-bottom: 16px;
            box-shadow: none;
            transition: var(--transition);
        }

        .option.card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            background: #ffffff;
        }

        .move-buttons {
            display: flex;
            flex-direction: column;
            gap: 4px;
            align-items: center;
            justify-content: center;
        }

        .move-buttons .btn {
            height: 26px;
            width: 26px;
            padding: 0 !important;
            font-size: 0.8rem;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: var(--transition);
        }

        .remove-option {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(220, 53, 69, 0.2);
            height: 38px;
            font-weight: 600;
            border-radius: 6px;
            transition: var(--transition);
            width: 100%;
        }

        .remove-option:hover {
            background-color: var(--danger-color);
            color: white;
            border-color: var(--danger-color);
        }

        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.25em;
            cursor: pointer;
        }

        .form-switch .form-check-label {
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            margin-left: 6px;
            color: #4a5568;
        }

        /* Buttons block */
        .actions-bar {
            display: flex;
            gap: 12px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e3e8ec;
        }

        .btn-save {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
            transition: var(--transition);
        }

        .btn-save:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-add {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            font-weight: 600;
            padding: 10px 24px;
            border-radius: 8px;
            transition: var(--transition);
        }

        .btn-add:hover {
            background-color: rgba(25, 122, 148, 0.05);
            transform: translateY(-1px);
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.12);
            border: none;
            color: #155724;
            border-radius: 8px;
            padding: 14px 18px;
            font-weight: 600;
            margin-bottom: 24px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="settings-card">
            <div class="settings-header">
                <h4>Basic Shipping Settings</h4>
                <p>Configure your storefront shipping costs, free delivery triggers, and custom target zones.</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.basic.shipping.settings.update') }}">
                @csrf
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Flat Rate (৳)</label>
                        <input type="number" step="0.01" name="flat_rate" value="{{ $setting->flat_rate ?? 80.00 }}"
                               class="form-control" required>
                        <small class="form-text">Default fallback shipping cost.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Free Shipping Threshold (৳)</label>
                        <input type="number" step="0.01" name="free_shipping_threshold"
                               value="{{ $setting->free_shipping_threshold ?? 1500.00 }}" class="form-control" required>
                        <small class="form-text">Order amount triggers zero shipping charges.</small>
                    </div>
                </div>

                <div id="shipping-options" class="mb-4">
                    <h5 class="form-label mb-2" style="font-size: 1rem; text-transform: uppercase; letter-spacing: 0.5px; color: #5a6a85;">Shipping Options</h5>
                    <p class="text-muted small mb-3">Re-order positions using Up/Down buttons to prioritize active storefront targets.</p>

                    @foreach ($shippingOptions ?? [] as $key => $option)
                        <div class="option card p-3" data-key-modified="true">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-1 move-buttons">
                                    <button type="button" class="btn btn-outline-secondary move-up">
                                        <i class="bi bi-arrow-up"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary move-down">
                                        <i class="bi bi-arrow-down"></i>
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="shipping_options[{{ $loop->index }}][name]"
                                           value="{{ $option['name'] }}" placeholder="Option Name" class="form-control name-input"
                                           required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="shipping_options[{{ $loop->index }}][key]"
                                           value="{{ $key }}" placeholder="Option Key" class="form-control key-input" required>
                                    <small class="key-error" style="display: none;">Lowercase letters and underscores only.</small>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" step="0.01" name="shipping_options[{{ $loop->index }}][cost]"
                                           value="{{ $option['cost'] }}" placeholder="Cost" class="form-control" required>
                                </div>
                                <div class="col-md-1 form-check form-switch d-flex justify-content-center align-items-center">
                                    <input type="hidden" name="shipping_options[{{ $loop->index }}][active]" value="0">
                                    <input type="checkbox" name="shipping_options[{{ $loop->index }}][active]" value="1"
                                           class="form-check-input" {{ $option['active'] ? 'checked' : '' }}>
                                </div>
                                <div class="col-md-1 text-end">
                                    <input type="hidden" name="shipping_options[{{ $loop->index }}][position]"
                                           value="{{ $option['position'] ?? $loop->index + 1 }}" class="position-input">
                                    <button type="button" class="btn remove-option" title="Remove Option"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="actions-bar">
                    <button type="button" id="add-option" class="btn-add"><i class="bi bi-plus-lg me-1"></i> Add Option</button>
                    <button type="submit" class="btn-save"><i class="bi bi-check-lg me-1"></i> Save Settings</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Generate slug from name
        function generateSlug(name) {
            return name
                .toLowerCase()
                .replace(/[^a-z0-9\s-_]/g, '')
                .trim()
                .replace(/\s+/g, '_')
                .replace(/-+/g, '_');
        }

        // Validate key format
        function isValidKey(key) {
            const regex = /^[a-z_]+$/;
            return regex.test(key);
        }

        // Update positions of all options
        function updatePositions() {
            $("#shipping-options .option").each(function(index) {
                $(this).find('.position-input').val(index + 1);
            });
        }

        // Add new shipping option
        $('#add-option').click(function() {
            const index = $('#shipping-options .option').length;
            $('#shipping-options').append(`
                <div class="option card p-3" data-key-modified="false">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-1 move-buttons">
                            <button type="button" class="btn btn-outline-secondary move-up">
                                <i class="bi bi-arrow-up"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary move-down">
                                <i class="bi bi-arrow-down"></i>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="shipping_options[${index}][name]" placeholder="Option Name"
                                   class="form-control name-input" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="shipping_options[${index}][key]" placeholder="Option Key"
                                   class="form-control key-input" required>
                            <small class="key-error" style="display: none;">Lowercase letters and underscores only.</small>
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" name="shipping_options[${index}][cost]" placeholder="Cost"
                                   class="form-control" required>
                        </div>
                        <div class="col-md-1 form-check form-switch d-flex justify-content-center align-items-center">
                            <input type="hidden" name="shipping_options[${index}][active]" value="0">
                            <input type="checkbox" name="shipping_options[${index}][active]" value="1"
                                   class="form-check-input" checked>
                        </div>
                        <div class="col-md-1 text-end">
                            <input type="hidden" name="shipping_options[${index}][position]"
                                   value="${index + 1}" class="position-input">
                            <button type="button" class="btn remove-option" title="Remove Option"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </div>
            `);
            $(`#shipping-options .option:last .name-input`).focus();
        });

        // Remove shipping option
        $(document).on('click', '.remove-option', function() {
            $(this).closest('.option').remove();
            updatePositions();
        });

        // Move option up
        $(document).on('click', '.move-up', function() {
            const $option = $(this).closest('.option');
            const $prev = $option.prev('.option');
            if ($prev.length) {
                $option.insertBefore($prev);
                updatePositions();
            }
        });

        // Move option down
        $(document).on('click', '.move-down', function() {
            const $option = $(this).closest('.option');
            const $next = $option.next('.option');
            if ($next.length) {
                $option.insertAfter($next);
                updatePositions();
            }
        });

        // Handle name input to generate key
        $(document).on('input', '.name-input', function() {
            const $option = $(this).closest('.option');
            const isKeyModified = $option.data('key-modified') === true;
            const $keyInput = $option.find('.key-input');
            const name = $(this).val();

            if (!isKeyModified && name) {
                const generatedKey = generateSlug(name);
                $keyInput.val(generatedKey);
                validateKey($keyInput);
            }
        });

        // Handle key input to mark as modified
        $(document).on('input', '.key-input', function() {
            const $option = $(this).closest('.option');
            $option.data('key-modified', true);
            validateKey($(this));
        });

        // Validate key format
        function validateKey($keyInput) {
            const key = $keyInput.val();
            const $error = $keyInput.siblings('.key-error');
            if (key && !isValidKey(key)) {
                $keyInput.addClass('invalid-key');
                $error.show();
            } else {
                $keyInput.removeClass('invalid-key');
                $error.hide();
            }
        }
    </script>
@endsection