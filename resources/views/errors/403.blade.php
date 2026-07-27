<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden - Permission Required</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 1.5rem;
        }
        .error-card {
            text-align: center;
            padding: 2.5rem 2rem;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            border: 1px solid #e2e8f0;
            max-width: 520px;
            width: 100%;
        }
        .icon-circle {
            width: 72px;
            height: 72px;
            background: #fef2f2;
            color: #ef4444;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.25rem;
            border: 2px solid #fee2e2;
        }
        h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.5rem 0;
        }
        p.subtitle {
            color: #64748b;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }
        .permission-box {
            background: #f1f5f9;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.75rem;
            text-align: left;
        }
        .permission-box label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            display: block;
            margin-bottom: 0.4rem;
        }
        .permission-badge {
            display: inline-block;
            background: #e0e7ff;
            color: #3730a3;
            font-family: monospace, monospace;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            word-break: break-all;
        }
        .actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #4f46e5;
            color: white;
            border: none;
        }
        .btn-primary:hover {
            background-color: #4338ca;
        }
        .btn-secondary {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }
        .btn-secondary:hover {
            background-color: #e2e8f0;
        }
    </style>
</head>
<body>
    @php
        $message = $customMessage ?? ($exception->getMessage() ?: 'Access Denied: Missing required permission.');
    @endphp
    <div class="error-card">
        <div class="icon-circle">
            <i class="fas fa-lock"></i>
        </div>
        <h1>Access Restricted</h1>
        <p class="subtitle">You do not have permission to access this page or perform this action.</p>

        <div class="permission-box">
            <label><i class="fas fa-key me-1"></i> Required Permission / Setting Info</label>
            <div class="permission-badge">
                {{ $message }}
            </div>
        </div>

        <div class="actions">
            <a href="javascript:history.back()" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Go Back
            </a>
            <a href="{{ auth()->check() && auth()->user()->hasRole('vendor') ? route('vendor.dashboard') : '/admin/dashboard' }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Go to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
