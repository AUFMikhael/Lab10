<?php

require "vendor/autoload.php";
require "init.php";

// Database connection object (from init.php (DatabaseConnection))
global $conn;

// Start the session to manage user login state
session_start();

try {

    // Create Router instance
    $router = new \Bramus\Router\Router();

    // Define routes
    // Home route (default route)
    $router->get('/', '\App\Controllers\HomeController@index');
    
    // Supplier routes
    $router->get('/suppliers', '\App\Controllers\SupplierController@list');
    $router->get('/suppliers/{id}', '\App\Controllers\SupplierController@single');
    $router->post('/suppliers/{id}', '\App\Controllers\SupplierController@update');
    
    // Login routes
    $router->get('/login', '\App\Controllers\LoginController@showLoginForm');
    $router->post('/login', '\App\Controllers\LoginController@handleLogin');
    $router->get('/welcome', '\App\Controllers\LoginController@showWelcomePage');
    $router->get('/logout', '\App\Controllers\LoginController@logout');

    // Run the router
    $router->run();

} catch (Exception $e) {

    echo json_encode([
        'error' => $e->getMessage()
    ]);

}
