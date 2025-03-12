<?php

/**
 * Afrigig - Freelancer Platform
 * 
 * This is the main entry point for the Afrigig application.
 * A simplified version of the Laravel framework's index.php
 */

// Define the application start time
define('LARAVEL_START', microtime(true));

// Register the Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// Bootstrap the application
$app = require_once __DIR__.'/../bootstrap/app.php';

// Get the HTTP kernel instance
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Handle the incoming request
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Send the response back to the client
$response->send();

// Terminate the request
$kernel->terminate($request, $response); 