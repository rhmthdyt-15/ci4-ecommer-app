<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/register', 'Register::index');
$routes->post('/register', 'Register::index');
$routes->get('/login', 'Login::index');
$routes->post('/login', 'Login::index');
$routes->get('/logout', 'Logout::index');

// $routes->get('/profile', 'Profile::index');
$routes->group('profile', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Profile::index');
    $routes->get('edit/(:num)', 'Profile::edit/$1');
    $routes->post('update/(:num)', 'Profile::update/$1');
});

$routes->group('shop', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('sortby/(:segment)', 'Shop::index/$1');
    $routes->get('category/(:segment)', 'Shop::category/$1');
    $routes->post('search', 'Shop::search');
});

$routes->group('cart', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Cart::index');
    $routes->post('add', 'Cart::add');
    $routes->post('update/(:num)', 'Cart::update/$1');
    $routes->post('delete/(:num)', 'Cart::delete/$1');
});

$routes->group('checkout', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Checkout::index');
    $routes->post('create', 'Checkout::create');
});

$routes->group('myorder', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'MyOrder::index');
    $routes->get('detail/(:num)', 'MyOrder::detail/$1');
    $routes->get('confirm/(:segment)', 'MyOrder::confirm/$1');
    $routes->post('confirm/(:segment)', 'MyOrder::confirm/$1');

});

$routes->group('category', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Category::index');
    $routes->get('reset', 'Category::reset');
    $routes->get('create', 'Category::create');
    $routes->post('store', 'Category::store');
    $routes->get('edit/(:num)', 'Category::edit/$1');
    $routes->post('update/(:num)', 'Category::update/$1');
    $routes->post('delete/(:num)', 'Category::delete/$1');
});

// orders admin
$routes->group('order', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Order::index');
    $routes->get('detail/(:num)', 'Order::detail/$1');
    $routes->get('reset', 'Order::reset');
    $routes->get('create', 'Order::create');
    $routes->post('store', 'Order::store');
    $routes->get('edit/(:num)', 'Order::edit/$1');
    $routes->post('update/(:num)', 'Order::update/$1');
    $routes->post('delete/(:num)', 'Order::delete/$1');
});

$routes->group('product', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'Product::index');
    $routes->get('reset', 'Product::reset');
    $routes->get('create', 'Product::create');
    $routes->post('store', 'Product::store');
    $routes->get('edit/(:num)', 'Product::edit/$1');
    $routes->post('update/(:num)', 'Product::update/$1');
    $routes->post('delete/(:num)', 'Product::delete/$1');
});

$routes->group('user', ['namespace' => 'App\Controllers'], function($routes) {
    $routes->get('/', 'User::index');
    $routes->get('reset', 'User::reset');
    $routes->get('create', 'User::create');
    $routes->post('store', 'User::store');
    $routes->get('edit/(:num)', 'User::edit/$1');
    $routes->post('update/(:num)', 'User::update/$1');
    $routes->post('delete/(:num)', 'User::delete/$1');
});



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}