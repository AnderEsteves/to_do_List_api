<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//-----------------------------------------

$routes->get('tasks', 'Tasks::list');

$routes->get('tasks/(:num)', 'Tasks::getById/$1');

$routes->post('tasks','Tasks::create');

$routes->put('tasks/(:num)', 'Tasks::updateById/$1');

$routes->delete('tasks/(:num)', 'Tasks::deleteById/$1');






