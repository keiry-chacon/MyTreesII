<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

//Principal Login
$routes->get('/login', 'User::indexLogin');
$routes->post('/user/login', 'User::login');

// Logout
$routes->post('/user/logout', 'User::logout');

//SignUp
$routes->get('/signup', 'User::indexSignUp');
$routes->post('/user/signup', 'User::signup');


$routes->get('/unauthorized', 'Error::unauthorized');
$routes->post('/redirectDashboard', 'Error::redirectDashboard');





// ================================================================
//                              Admin
// Principal
$routes->get('/adminhome', 'Admin::indexHome');

//Manage Specie
$routes->get('/managespecies', 'Admin::indexManageSpecies');

//Add Specie
$routes->get('/addspecies', 'Admin::indexAddSpecies');
$routes->post('/admin/addspecies', 'Admin::addSpecies');

//Update Specie
$routes->get('/updatespecies', 'Admin::indexUpdateSpecies');
$routes->post('/admin/updatespecies', 'Admin::updateSpecies');

//Delete Specie
$routes->post('/admin/deletespecies', 'Admin::deleteSpecies');



//Add Tree
$routes->get('/addtree', 'Admin::indexAddTree');
$routes->post('/admin/addtree', 'Admin::addTree');

//Edit Tree
$routes->get('/edittree', 'Admin::indexEditTree');
$routes->post('/admin/edittree', 'Admin::editTree');

//Delete Tree
$routes->post('/admin/deletetree', 'Admin::deleteTree');

//Admin Profile
$routes->get('/profile', 'Admin::profile');
$routes->post('/user/profile', 'Admin::Profile');





// ================================================================
//                              Friend
$routes->get('/friend/dashboard', 'Friend::index');

//Mytrees
$routes->get('/mytrees', 'Friend::mytrees');


//User
$routes->get('/profile', 'Friend::profile');
$routes->post('/user/profile', 'Friend::Profile');



$routes->group('admin', ['filter' => 'auth:1'], function ($routes) {
    $routes->get('dashboard', 'Admin::index');
});

$routes->group('friend', ['filter' => 'auth:2'], function ($routes) {
    $routes->get('dashboard', 'Friend::index');
});

$routes->group('operator', ['filter' => 'auth:3'], function ($routes) {
    $routes->get('dashboard', 'Operator::index');
});


