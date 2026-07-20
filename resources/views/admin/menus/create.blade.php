@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .menu-create-wrapper {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
            padding: 1.5rem 0;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Glassmorphism Card styling */
        .premium-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            margin-bottom: 2rem;
            max-width: 650px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Gradient Header area */
        .gradient-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #c084fc 100%);
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .gradient-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        }

        .gradient-header-title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .gradient-header-subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            margin-top: 0.35rem;
            margin-bottom: 0;
        }

        .premium-card-body {
            padding: 2.25rem;
        }

        /* Form Group Elements */
        .form-group label {
            font-weight: 700;
            color: #475569;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.6rem;
            display: block;
        }

        .form-control-premium {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: #0f172a;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
            width: 100%;
        }

        .form-control-premium:focus {
            background-color: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
            outline: none;
        }

        .form-control-premium.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.825rem;
            font-weight: 600;
            margin-top: 0.35rem;
        }

        /* Toggle switches */
        .custom-switch-premium {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.25rem 0;
            cursor: pointer;
        }

        .switch-input {
            display: none;
        }

        .switch-slider {
            width: 46px;
            height: 24px;
            background-color: #cbd5e1;
            border-radius: 9999px;
            position: relative;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .switch-slider::before {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background-color: white;
            top: 3px;
            left: 3px;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .switch-input:checked + .switch-slider {
            background-color: #10b981;
        }

        .switch-input:checked + .switch-slider::before {
            transform: translateX(22px);
        }

        .switch-label-text {
            font-weight: 700;
            color: #475569;
            font-size: 0.9rem;
            user-select: none;
        }

        /* Action Buttons */
        .premium-btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2.25rem;
            border-top: 1.5px solid #f1f5f9;
            padding-top: 1.75rem;
        }

        .btn-premium {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            flex: 1;
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white !important;
            box-shadow: 0 8px 20px -6px rgba(99, 102, 241, 0.4);
        }

        .btn-premium-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            box-shadow: 0 10px 24px -6px rgba(99, 102, 241, 0.5);
            transform: translateY(-1px);
        }

        .btn-premium-default {
            background-color: #f1f5f9;
            color: #475569 !important;
            border: 1px solid #e2e8f0;
        }

        .btn-premium-default:hover {
            background-color: #e2e8f0;
            color: #1e293b !important;
            transform: translateY(-1px);
        }
    </style>
@endsection

@section('content')
<div class="container-fluid menu-create-wrapper">
    <div class="premium-card">
        <!-- Gradient Header -->
        <div class="gradient-header">
            <h1 class="gradient-header-title">Create Menu</h1>
            <p class="gradient-header-subtitle">Define menu properties and assign its listing location.</p>
        </div>

        <div class="premium-card-body">
            <form action="{{ route('admin.menus.store') }}" method="POST">
                @csrf

                <!-- Menu Name field -->
                <div class="form-group mb-4">
                    <label for="name">Menu Name</label>
                    <input type="text" name="name" id="name"
                        class="form-control-premium @error('name') is-invalid @enderror"
                        placeholder="e.g. Primary Header Menu, Footer Quick Links"
                        value="{{ old('name') }}" required autocomplete="off">
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Display Location field -->
                <div class="form-group mb-4">
                    <label for="location">Display Location</label>
                    <input type="text" name="location" id="location"
                        class="form-control-premium @error('location') is-invalid @enderror"
                        placeholder="e.g. main-navigation, footer-column-1"
                        value="{{ old('location') }}" autocomplete="off">
                    @error('location')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Active Toggle switch -->
                <div class="form-group mb-2">
                    <label class="custom-switch-premium">
                        <input type="checkbox" name="status" class="switch-input" id="status" value="1" checked>
                        <span class="switch-slider"></span>
                        <span class="switch-label-text">Enable Menu Listing (Active)</span>
                    </label>
                </div>

                <!-- Action Button Group -->
                <div class="premium-btn-group">
                    <a href="{{ route('admin.menus.index') }}" class="btn-premium btn-premium-default">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <button type="submit" class="btn-premium btn-premium-primary">
                        <i class="fas fa-save"></i> Save Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection