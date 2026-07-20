<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('/connexion', 'AuthController::index');
$routes->post('/connexion', 'AuthController::connecter');
$routes->post('/deconnexion', 'AuthController::deconnecter');

$routes->group('client', ['namespace' => 'App\Controllers\Client', 'filter' => 'clientAuth'], static function ($routes) {
    $routes->get('tableau-de-bord', 'CompteController::index');

    $routes->get('depot', 'DepotController::index');
    $routes->post('depot', 'DepotController::effectuer');

    $routes->get('retrait', 'RetraitController::index');
    $routes->post('retrait', 'RetraitController::effectuer');

    $routes->get('transfert', 'TransfertController::index');
    $routes->post('transfert', 'TransfertController::effectuer');

    $routes->get('historique', 'HistoriqueController::index');
});

$routes->get('/admin/connexion', 'Admin\AuthController::index');
$routes->post('/admin/connexion', 'Admin\AuthController::connecter');
$routes->post('/admin/deconnexion', 'Admin\AuthController::deconnecter');

$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'adminAuth'], static function ($routes) {
    $routes->get('/', 'DashboardController::index');

    $routes->get('prefixes', 'PrefixeController::index');
    $routes->post('prefixes', 'PrefixeController::creer');
    $routes->post('prefixes/(:num)/basculer', 'PrefixeController::basculerActif/$1');
    $routes->post('prefixes/(:num)/supprimer', 'PrefixeController::supprimer/$1');

    $routes->get('types-operation', 'TypeOperationController::index');
    $routes->post('types-operation', 'TypeOperationController::creer');
    $routes->post('types-operation/(:num)/basculer', 'TypeOperationController::basculerActif/$1');

    $routes->get('frais', 'FraisController::index');
    $routes->post('frais', 'FraisController::creer');
    $routes->post('frais/(:num)/supprimer', 'FraisController::supprimer/$1');
});
