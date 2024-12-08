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


    // Ruta para mostrar el perfil del usuario
    $routes->get('/profile', 'User::profile');

    // Ruta para editar el perfil del usuario
    $routes->post('update', 'User::updateProfile', ['as' => 'user.updateProfile']); 






// ================================================================
//                              Admin

// Home
$routes->get('/adminhome', 'Admin::indexHome');


//Manage Species
$routes->get('/managespecies', 'Admin::indexManageSpecies');

//Add Species
$routes->get('/addspecies', 'Admin::indexAddSpecies');
$routes->post('/admin/addspecies', 'Admin::addSpecies');

//Update Species
$routes->get('/updatespecies', 'Admin::indexUpdateSpecies');
$routes->post('/admin/updatespecies', 'Admin::updateSpecies');

//Delete Species
$routes->post('/admin/deletespecies', 'Admin::deleteSpecies');



//Manage Trees
$routes->get('/managetrees', 'Admin::indexManageTrees');

//Add Tree
$routes->get('/addtree', 'Admin::indexAddTree');
$routes->post('/admin/addtree', 'Admin::addTree');

//Update Tree
$routes->get('/updatetree', 'Admin::indexUpdateTree');
$routes->post('/admin/updatetree', 'Admin::updateTree');

//Delete Tree
$routes->post('/admin/deletetree', 'Admin::deleteTree');




//Manage Friends Trees
//View friends list
$routes->get('/managefriends', 'Admin::indexManageFriends');

//View the list of friends trees
$routes->get('/friendtrees', 'Admin::indexFriendTrees');
$routes->post('/admin/friendtrees', 'Admin::friendtrees');

//Update Friend Tree
$routes->get('/updatefriendtree', 'Admin::indexUpdateFriendTree');
$routes->post('/admin/updatefriendtree', 'Admin::updateFriendTree');


//Add User
$routes->get('/adduser', 'Admin::indexAddUser');
$routes->post('/admin/adduser', 'Admin::addUser');




// ================================================================
//                              Friend
$routes->get('/friend/dashboard', 'Friend::index');

//Mytrees
$routes->get('/mytrees', 'Tree::mytrees');
$routes->get('/friend/tree_detail/(:segment)', 'Tree::treeDetail/$1');
$routes->get('/friend/tree_detail_friend/(:segment)', 'Tree::treeDetailFriend/$1');

//User
$routes->post('purchase/processPurchase', 'Purchase::processPurchase');
$routes->get('purchase/success', 'Purchase::success');




// ================================================================
//                              Operator
$routes->get('/operatorhome', 'User::indexOperatorHome');




// ================================================================
//                       Administartor/Operator
//Register Update
$routes->get('/registerupdate', 'User::indexRegisterUpdate');
$routes->post('/operator/registerupdate', 'User::registerUpdate');




$routes->group('admin', ['filter' => 'auth:1'], function ($routes) {
    $routes->get('dashboard', 'Admin::index');
});

$routes->group('friend', ['filter' => 'auth:2'], function ($routes) {
    $routes->get('dashboard', 'Friend::index');
});

$routes->group('operator', ['filter' => 'auth:3'], function ($routes) {
    $routes->get('dashboard', 'Operator::index');
});


