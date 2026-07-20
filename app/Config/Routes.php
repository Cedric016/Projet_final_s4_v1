<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Dashboard::index');

$routes->get('dashboard', 'Dashboard::index');

$routes->get('prefixes', 'Prefixes::index');
$routes->get('prefixes/create', 'Prefixes::create');
$routes->post('prefixes/store', 'Prefixes::store');
$routes->get('prefixes/edit/(:num)', 'Prefixes::edit/$1');
$routes->post('prefixes/update/(:num)', 'Prefixes::update/$1');
$routes->get('prefixes/delete/(:num)', 'Prefixes::delete/$1');

$routes->get('types', 'Types::index');
$routes->get('types/create', 'Types::create');
$routes->post('types/store', 'Types::store');
$routes->get('types/edit/(:num)', 'Types::edit/$1');
$routes->post('types/update/(:num)', 'Types::update/$1');
$routes->get('types/delete/(:num)', 'Types::delete/$1');

$routes->get('baremes', 'Baremes::index');
$routes->get('baremes/create', 'Baremes::create');
$routes->post('baremes/store', 'Baremes::store');
$routes->get('baremes/edit/(:num)', 'Baremes::edit/$1');
$routes->post('baremes/update/(:num)', 'Baremes::update/$1');
$routes->get('baremes/delete/(:num)', 'Baremes::delete/$1');

$routes->get('clients', 'Clients::index');
$routes->get('clients/create', 'Clients::create');
$routes->post('clients/store', 'Clients::store');
$routes->get('clients/edit/(:num)', 'Clients::edit/$1');
$routes->post('clients/update/(:num)', 'Clients::update/$1');
$routes->get('clients/delete/(:num)', 'Clients::delete/$1');

$routes->get('operations', 'Operations::index');
$routes->post('operations/executer', 'Operations::executer');
$routes->get('operations/historique', 'Operations::historique');

$routes->get('settings', 'Settings::index');
$routes->post('settings/update', 'Settings::update');

$routes->get('situation/gains', 'Situation::gains');
$routes->get('situation/operateurs', 'Situation::operateurs');

// Espace client (4209)
$routes->get('client/login', 'Client::login');
$routes->post('client/authentifier', 'Client::authentifier');
$routes->get('client/deconnecter', 'Client::deconnecter');
$routes->get('client', 'Client::index');
$routes->get('client/operations', 'Client::operations');
$routes->post('client/executer', 'Client::executer');
$routes->get('client/historique', 'Client::historique');
