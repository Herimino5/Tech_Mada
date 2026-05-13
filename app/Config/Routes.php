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
$routes->get('/employe/demandes', 'EmployeController::demandes');
$routes->post('/employe/demandes', 'EmployeController::storeDemande');

// Dashboards RH/Admin
$routes->get('/rh/dashboard', 'Home::rh');
$routes->get('/admin/dashboard', 'Home::admin');
