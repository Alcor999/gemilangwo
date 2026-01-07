<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check database connection
try {
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "Database connection: OK\n";
} catch (\Exception $e) {
    echo "Database connection error: " . $e->getMessage() . "\n";
    exit;
}

// Check users
$users = \App\Models\User::all();
echo "\n=== All Users ===\n";
foreach ($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
}

// Check routes
echo "\n=== Customer Support Routes ===\n";
$routes = \Illuminate\Support\Facades\Route::getRoutes();
foreach ($routes as $route) {
    if (strpos($route->uri, 'support/tickets') !== false && strpos($route->uri, 'customer') !== false) {
        echo "Route: " . $route->uri . " | Methods: " . implode(',', $route->methods) . " | Middleware: " . implode(',', $route->middleware()) . "\n";
    }
}
