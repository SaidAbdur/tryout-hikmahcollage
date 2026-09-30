<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$routes = ['/', '/login', '/register', '/admin/login'];
foreach($routes as $route) {
    $request = Illuminate\Http\Request::create($route, 'GET');
    try {
        $response = $kernel->handle($request);
        echo "$route -> " . $response->getStatusCode() . "\n";
        if ($response->getStatusCode() == 500 && isset($response->exception)) {
            echo "Exception: " . $response->exception->getMessage() . " at " . $response->exception->getFile() . ":" . $response->exception->getLine() . "\n";
        }
    } catch (\Throwable $e) {
        echo "$route -> Caught Exception: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
}
