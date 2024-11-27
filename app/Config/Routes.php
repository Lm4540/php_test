<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//  rutas de información
$routes->get('/', 'Home::index');
$routes->get('/about', 'Home::about');
$routes->get('/contact', 'Home::contact');


$routes->get('/login', 'Home::login');
$routes->post('/login', 'Home::login_post');
$routes->get('/logout', 'Home::logout');

$routes->get('/register', 'Home::register');
$routes->post('/register', 'Home::register_post');

$routes->get('/catalogs', 'Catalog::catalogs');
$routes->get('/catalog/(:num)', 'Catalog::catalog');
$routes->get('/product/(:num)', 'Catalog::product');

