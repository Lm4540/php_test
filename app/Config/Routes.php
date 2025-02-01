<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//  rutas de información
$routes->get('/', 'Home::index', ['as'=> 'home']);
$routes->get('/about', 'Home::about', ['as'=> 'about']);
$routes->get('/contact', 'Home::contact', ['as'=> 'contact']);
$routes->get('/sucursal', 'Home::sucursal',['as'=> 'sucursal']);
$routes->get('/terms', 'Home::terms', ['as'=> 'terms']);
$routes->get('/privacy_policy', 'Home::privacy_policy', ['as'=> 'privacy_policy']);
$routes->get('/login', 'Home::login', ['as'=> 'login']);
$routes->post('/login', 'Home::login_post', ['as'=> 'loginPost']);
$routes->get('/logout', 'Home::logout', ['as'=> 'logout']);
$routes->get('/catalog', 'Home::catalog', ['as'=> 'catalog']);
$routes->get('/catalog/products', 'Home::catalogProducts', ['as'=> 'catalog_products']);
$routes->get('/image', 'Home::image');
$routes->get('/rm_image', 'Home::rm_image');
$routes->get('/product/(:num)', 'Home::ClientViewProduct/$1');

//solo los clientes que ha iniciado session
$routes->get('/vip', 'Home::Vip', ['as'=> 'vip']);
$routes->get('/vip/categories', 'Home::ClientCategories');
$routes->get('/vip/categories/(:num)', 'Home::ClientCategorie/$1');

$routes->get('/vip/catalogs', 'Home::ClientCatalogs');
$routes->get('/vip/catalogs/(:num)', 'Home::ClientCatalog/$1');
$routes->get('/vip/catalogs/(:num)/products', 'Home::ClientCatalogProducts/$1');


$routes->get('/vip/product', 'Home::ClientAllProducts');
$routes->get('/vip/products', 'Home::clientProduct');

$routes->get('/vip/product/(:num)', 'Home::ClientViewProduct/$1');

// $routes->get('users/(:num)/gallery/(:num)', 'Galleries::showUserGallery/$1/$2', ['as' => 'user_gallery']);


