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

// Espace personnel employe
$routes->get('/employe/dashboard', 'EmployeController::dashboard');
$routes->get('/employe/demandes/nouvelle', 'EmployeController::createDemande');
$routes->post('/employe/demandes', 'EmployeController::storeDemande');

// Espace RH
$routes->get('/rh/dashboard', 'RhController::dashboard');
$routes->get('/rh/demandes', 'RhController::demandes');
$routes->post('/rh/demandes/(:num)/approuver', 'RhController::approuver/$1');
$routes->post('/rh/demandes/(:num)/refuser', 'RhController::refuser/$1');
$routes->get('/rh/soldes', 'RhController::soldes');

// Dashboards Admin
$routes->get('/admin/dashboard', 'Home::admin');
