<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(
    Illuminate\Http\Request::capture()
);

use App\Http\Middleware\CheckLicense;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

$user = \App\Models\User::where('email', 'admin@purnobd.com')->first();
if ($user) {
    Auth::login($user);
}

$request = Request::create('http://127.0.0.1:8000/admin/dashboard', 'GET');
$request->setUserResolver(function () use ($user) {
    return $user;
});

// Mock route
$route = Route::get('/admin/dashboard', function() {});
$request->setRouteResolver(function () use ($route) {
    return $route;
});

$middleware = app(CheckLicense::class);

echo "Running CheckLicense middleware...\n";
$response = $middleware->handle($request, function ($req) {
    return response("Middleware Passed!");
});

echo "Response status: " . $response->getStatusCode() . "\n";
echo "Response content:\n" . $response->getContent() . "\n";
