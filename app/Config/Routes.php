<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */



$routes->get('/utils/services/dte/(:alpha)', 'Home::getDTE/$1');
$routes->get('/utils/services/sendPDF/(:num)', 'Home::sendPDF/$1');
//  $routes->post('/utils/services/sendMail', 'Home::testMail');
// $routes->get('/utils/services/dte/(:alphanum)', 'Home::yourMethod/$1', ['as' => 'downloadPDF']);

//$routes->post('/utils/system/sendDte', 'mailer::sendDte', ['as' => 'sedDTE']);
$routes->get('/', 'Home::index', ['as' => 'home']);
$routes->get('/about', 'Home::about', ['as' => 'about']);
$routes->get('/contact', 'Home::contact', ['as' => 'contact']);
$routes->get('/sucursal', 'Home::sucursal', ['as' => 'sucursal']);
$routes->get('/terms', 'Home::terms', ['as' => 'terms']);
$routes->get('/privacy_policy', 'Home::privacy_policy', ['as' => 'privacy_policy']);
$routes->get('/login', 'Home::login', ['as' => 'login']);
$routes->post('/login', 'Home::login_post', ['as' => 'loginPost']);
$routes->get('/logout', 'Home::logout', ['as' => 'logout']);
$routes->get('/catalog', 'Home::catalog', ['as' => 'catalog']);
$routes->get('/catalog/products', 'Home::catalogProducts', ['as' => 'catalog_products']);
$routes->get('/image', 'Home::image');
$routes->get('/rm_image', 'Home::rm_image');
$routes->get('/product/(:num)', 'Home::ClientViewProduct/$1');
$routes->get('/data_product/(:num)', 'Home::getProductData/$1');
$routes->get('/mail', 'Home::anotherTest', ['as' => 'testMail']);


//solo los clientes que ha iniciado session

$routes->get('/vip', 'Home::ClientAllProducts', ['as' => 'vip', 'filter' => 'auth']);
$routes->get('/vip/categories', 'Home::ClientCategories', ['filter' => 'auth']);
$routes->get('/vip/categories/(:num)', 'Home::ClientCategorie/$1', ['filter' => 'auth']);
$routes->get('/vip/catalogs', 'Home::ClientCatalogs', ['filter' => 'auth']);
$routes->get('/vip/catalogs/(:num)', 'Home::ClientCatalog/$1', ['filter' => 'auth']);
$routes->get('/vip/catalogs/(:num)/products', 'Home::ClientCatalogProducts/$1', ['filter' => 'auth']);
$routes->get('/vip/product', 'Home::ClientAllProducts', ['filter' => 'auth']);
$routes->get('/vip/products', 'Home::clientProduct', ['filter' => 'auth']);
$routes->get('/vip/product/(:num)', 'Home::ClientViewProduct/$1', ['filter' => 'auth']);


// $routes->get('users/(:num)/gallery/(:num)', 'Galleries::showUserGallery/$1/$2', ['as' => 'user_gallery']);


