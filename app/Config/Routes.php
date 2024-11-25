<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Principal Login
$routes->get('/login', 'User::index');
$routes->post('/user/login', 'User::login');

// Logout
$routes->post('/logout', 'User::logout');

//SignUp
$routes->get('/signup', 'User::indexSignUp');
$routes->post('/user/signup', 'User::signup');


$routes->get('/unauthorized', 'Error::unauthorized');
$routes->post('/redirectDashboard', 'Error::redirectDashboard');


$routes->group('admin', ['filter' => 'auth:1'], function ($routes) {
    $routes->get('dashboard', 'Admin::index');
});

$routes->group('friend', ['filter' => 'auth:2'], function ($routes) {
    $routes->get('dashboard', 'Friend::index');
});

$routes->group('operator', ['filter' => 'auth:3'], function ($routes) {
    $routes->get('dashboard', 'Operator::index');
});


