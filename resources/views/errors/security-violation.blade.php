<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Violation - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 3rem;
            max-width: 500px;
            width: 90%;
            text-align: center;
        }
        .icon {
            font-size: 4rem;
            color: #e74c3c;
            margin-bottom: 1rem;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }
        p {
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn {
            background: #3498db;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #2980b9;
        }
        .details {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 1rem;
            margin-top: 2rem;
            text-align: left;
            font-size: 0.9rem;
            color: #6c757d;
        }
        .details strong {
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🛡️</div>
        <h1>Security Violation Detected</h1>
        <p>Your request has been blocked due to a security policy violation. This may be due to system integrity checks or suspicious activity.</p>
        
        <div class="details">
            <strong>What happened?</strong><br>
            The system detected a potential security issue with your request. This could be due to:<br><br>
            
            <strong>Possible causes:</strong><br>
            • System integrity check failure<br>
            • Suspicious request pattern<br>
            • Multiple tamper attempts detected<br>
            • Invalid license configuration<br><br>
            
            <strong>What to do:</strong><br>
            • Contact your administrator<br>
            • Clear your browser cache<br>
            • Try refreshing the page<br>
            • Check your license status
        </div>
        
        <a href="{{ url('/') }}" class="btn">Return to Home</a>
        <a href="{{ url('/admin/license') }}" class="btn" style="margin-left: 10px; background: #27ae60;">Check License</a>
    </div>
</body>
</html>
