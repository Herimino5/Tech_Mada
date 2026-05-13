<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Basic auth routes for TechMada RH
$routes->get('/', 'AuthController::showLogin');
$routes->get('/login', 'AuthController::showLogin');
$routes->post('/login', 'AuthController::login');
$routes->post('/logout', 'AuthController::logout');

// Dashboard placeholders (to be implemented)
$routes->get('/employe/dashboard', 'Home::employe');
$routes->get('/rh/dashboard', 'Home::rh');
$routes->get('/admin/dashboard', 'Home::admin');
