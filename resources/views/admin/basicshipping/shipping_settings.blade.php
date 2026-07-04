@extends('layouts.master')

@section('styles')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .invalid-key {
            border-color: red;
        }
        .key-error {
            color: red;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .move-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">BasicShipping Settings</h4>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.basic.shipping.settings.update') }}" class="mb-4">
            @csrf
            <div class="mb-3">
                <label class="form-label">Flat Rate (৳)</label>
                <input type="number" step="0.01" name="flat_rate" value="{{ $setting->flat_rate ?? 80.00 }}"
                       class="form-control" required>
                <small class="form-text text-muted">Default shipping cost if no option is selected.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Free Shipping Threshold (৳)</label>
                <input type="number" step="0.01" name="free_shipping_threshold"
                       value="{{ $setting->free_shipping_threshold ?? 1500.00 }}" class="form-control" required>
                <small class="form-text text-muted">Minimum order amount for free shipping.</small>
            </div>

            <div id="shipping-options" class="mb-4">
                <h5>Shipping Options</h5>
                <p class="text-muted mb-3">Use the buttons to move shipping options up or down. It will be applied also in frontend.</p>

                @foreach ($shippingOptions ?? [] as $key => $option)
                    <div class="option card mb-3 p-3" data-key-modified="true">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-2 move-buttons">
                                <button type="button" class="btn btn-outline-secondary move-up mb-1">
                                    <i class="bi bi-arrow-up"></i> Up
                                </button>
                                <button type="button" class="btn btn-outline-secondary move-down">
                                    <i class="bi bi-arrow-down"></i> Down
                                </button>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="shipping_options[{{ $loop->index }}][name]"
                                       value="{{ $option['name'] }}" placeholder="Name" class="form-control name-input"
                                       required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="shipping_options[{{ $loop->index }}][key]"
                                       value="{{ $key }}" placeholder="Key" class="form-control key-input" required>
                                <small class="key-error" style="display: none;">Key must be lowercase letters and underscores only.</small>
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.01" name="shipping_options[{{ $loop->index }}][cost]"
                                       value="{{ $option['cost'] }}" placeholder="Cost" class="form-control" required>
                            </div>
                            <div class="col-md-1 form-check form-switch">
                                <input type="hidden" name="shipping_options[{{ $loop->index }}][active]" value="0">
                                <input type="checkbox" name="shipping_options[{{ $loop->index }}][active]" value="1"
                                       class="form-check-input" {{ $option['active'] ? 'checked' : '' }}>
                                <label class="form-check-label">Active</label>
                            </div>
                            <div class="col-md-1">
                                <input type="hidden" name="shipping_options[{{ $loop->index }}][position]"
                                       value="{{ $option['position'] ?? $loop->index + 1 }}" class="position-input">
                                <button type="button" class="btn btn-danger remove-option">Remove</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex gap-2">
                <button type="button" id="add-option" class="btn btn-secondary">Add Option</button>
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
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
                <div class="option card mb-3 p-3" data-key-modified="false">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-2 move-buttons">
                            <button type="button" class="btn btn-outline-secondary move-up mb-1">
                                <i class="bi bi-arrow-up"></i> Up
                            </button>
                            <button type="button" class="btn btn-outline-secondary move-down">
                                <i class="bi bi-arrow-down"></i> Down
                            </button>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="shipping_options[${index}][name]" placeholder="Name"
                                   class="form-control name-input" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="shipping_options[${index}][key]" placeholder="Key"
                                   class="form-control key-input" required>
                            <small class="key-error" style="display: none;">Key must be lowercase letters and underscores only.</small>
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" name="shipping_options[${index}][cost]" placeholder="Cost"
                                   class="form-control" required>
                        </div>
                        <div class="col-md-1 form-check form-switch">
                            <input type="hidden" name="shipping_options[${index}][active]" value="0">
                            <input type="checkbox" name="shipping_options[${index}][active]" value="1"
                                   class="form-check-input" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                        <div class="col-md-1">
                            <input type="hidden" name="shipping_options[${index}][position]"
                                   value="${index + 1}" class="position-input">
                            <button type="button" class="btn btn-danger remove-option">Remove</button>
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