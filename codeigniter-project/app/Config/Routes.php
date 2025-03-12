<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Authentication Routes
$routes->group('', ['namespace' => 'App\Controllers\Auth'], function ($routes) {
    // Registration
    $routes->get('register', 'RegisterController::index');
    $routes->post('register', 'RegisterController::register');
    $routes->get('verify-email/(:any)', 'RegisterController::verify/$1');

    // Login
    $routes->get('login', 'LoginController::index');
    $routes->post('login', 'LoginController::login');
    $routes->post('logout', 'LoginController::logout');

    // Password Reset
    $routes->get('forgot-password', 'ForgotPasswordController::index');
    $routes->post('forgot-password', 'ForgotPasswordController::sendResetLink');
    $routes->get('reset-password/(:any)', 'ResetPasswordController::index/$1');
    $routes->post('reset-password', 'ResetPasswordController::reset');

    // Email Verification
    $routes->get('email/verify', 'EmailVerificationController::notice');
    $routes->get('email/verify/(:any)', 'EmailVerificationController::verify/$1');
    $routes->post('email/resend', 'EmailVerificationController::resend');
});

// Protected Routes
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('profile', 'ProfileController::index');
    $routes->post('profile', 'ProfileController::update');
});

// Bid Routes
$routes->group('jobs', ['filter' => 'auth'], function ($routes) {
    $routes->get('(:num)/bids/create', 'BidController::create/$1');
    $routes->post('(:num)/bids', 'BidController::store/$1');
});

$routes->group('bids', ['filter' => 'auth'], function ($routes) {
    $routes->get('(:num)', 'BidController::show/$1');
    $routes->post('(:num)/accept', 'BidController::accept/$1');
    $routes->post('(:num)/reject', 'BidController::reject/$1');
    $routes->post('(:num)/withdraw', 'BidController::withdraw/$1');
});
