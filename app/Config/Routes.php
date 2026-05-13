<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LivreController::index');
$routes->get('/livres/(:num)', 'LivreController::detail/$1');
$routes->get('/livres/nouveau', 'LivreController::ajouter');
$routes->post('/livres/store', 'LivreController::enregistrer');
$routes->post('/livres/supprimer/(:num)', 'LivreController::supprimer/$1');
$routes->post('/livres/emprunter/(:num)', 'EmpruntController::emprunter/$1');
$routes->post('/livres/retourner/(:num)', 'EmpruntController::retourner/$1');
