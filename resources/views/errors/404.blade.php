<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <script>
        // Redirect legacy POS print/download routes to the correct active routes
        (function() {
            const path = window.location.pathname;
            
            // Map of path patterns to redirect templates
            const patterns = [
                { regex: /^\/admin\/pos\/print-receipt\/(\d+)/i, replacement: '/order/$1/print-receipt' },
                { regex: /^\/admin\/pos\/print-invoice\/(\d+)/i, replacement: '/order/$1/print-invoice' },
                { regex: /^\/admin\/pos\/print-package-slip\/(\d+)/i, replacement: '/order/$1/print-package-slip' },
                { regex: /^\/admin\/pos\/download-receipt\/(\d+)/i, replacement: '/order/$1/download-receipt' },
                { regex: /^\/admin\/pos\/download-invoice\/(\d+)/i, replacement: '/order/$1/download-invoice' },
                { regex: /^\/admin\/pos\/download-package-slip\/(\d+)/i, replacement: '/order/$1/download-package-slip' }
            ];

            for (const pattern of patterns) {
                if (pattern.regex.test(path)) {
                    const newPath = path.replace(pattern.regex, pattern.replacement);
                    window.location.href = newPath;
                    return;
                }
            }
        })();
    </script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            font-size: 4rem;
            margin: 0;
            color: #4f46e5;
        }
        p {
            color: #4b5563;
            margin-bottom: 1.5rem;
        }
        .btn {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you are looking for does not exist or has been moved.</p>
        <a href="/admin/transactions" class="btn">Back to Orders</a>
    </div>
</body>
</html>
