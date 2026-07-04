<?php
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

$routesFile = __DIR__ . '/routes/web.php';
$routesContent = file_get_contents($routesFile);

// Let's create a custom middleware inline in the route file to log in the user first.
$testRoute = "
Route::get('/test-auth-dashboard', function(\\Illuminate\Http\Request \$request) {
    \$controller = app(\\App\\Http\\Controllers\\AdminController::class);
    \$licenseService = app(\\App\\Services\\LicenseService::class);
    return \$controller->admin(\$request, \$licenseService);
})->middleware([
    function (\$request, \$next) {
        \$user = \\App\\Models\\User::where('email', 'admin@purnobd.com')->first();
        if (\$user) {
            Auth::login(\$user);
        }
        return \$next(\$request);
    },
    'auth',
    'license',
    'authorize.by_route',
    'TrackInstallation'
]);
";

$newContent = preg_replace('/<\?php/', "<?php\n" . $testRoute, $routesContent, 1);
file_put_contents($routesFile, $newContent);

echo "Added /test-auth-dashboard route with inline login middleware.\n";
sleep(2);

try {
    $client = new Client(['cookies' => true, 'allow_redirects' => true]);
    $response = $client->get('http://127.0.0.1:8000/test-auth-dashboard');
    $body = (string) $response->getBody();
    
    echo "HTTP Status: " . $response->getStatusCode() . "\n";
    echo "Body length: " . strlen($body) . "\n";
    echo "First 500 chars of body:\n" . substr($body, 0, 500) . "\n\n";
    
    if (str_contains($body, 'Not authorized')) {
        echo "FOUND 'Not authorized' in the raw HTTP response!\n";
    } else {
        echo "Did NOT find 'Not authorized' in the raw HTTP response.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    file_put_contents($routesFile, $routesContent);
    echo "Restored routes/web.php\n";
}
