<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


// User Routes (Common Routes)
// ================================================================
// Home and General Routes
$routes->get('/', 'Home::index'); // Home page

// Login Routes
$routes->get('/login', 'User::indexLogin'); // Login page
$routes->post('/user/login', 'User::login'); // Process login

// Logout Route
$routes->post('/user/logout', 'User::logout'); // Logout

// SignUp Routes
$routes->get('/signup', 'User::indexSignUp'); // Sign-up page
$routes->post('/user/signup', 'User::signup'); // Process sign-up
$routes->post('user/getProvinces', 'User::getProvinces'); // Get provinces
$routes->post('user/getDistricts', 'User::getDistricts'); // Get districts

// Unauthorized page
$routes->get('/unauthorized', 'Error::unauthorized');
$routes->post('/redirectDashboard', 'User::redirectToDashboard');

// Profile Routes
$routes->get('/profile', 'User::profile'); // View user profile
$routes->post('update', 'User::updateProfile', ['as' => 'user.updateProfile']); // Update user profile

// ================================================================
// Admin Routes (For Admin Users)
$routes->group('admin', ['filter' => 'auth:1'], function ($routes) {
    $routes->get('dashboard', 'Admin::indexHome'); // Admin dashboard
    $routes->get('/managespecies', 'Admin::indexManageSpecies');
    $routes->get('/addspecies', 'Admin::indexAddSpecies');
    $routes->post('/admin/addspecies', 'Admin::addSpecies');
    $routes->get('/updatespecies', 'Admin::indexUpdateSpecies');
    $routes->post('/admin/updatespecies', 'Admin::updateSpecies');
    $routes->post('/admin/deletespecies', 'Admin::deleteSpecies');

    $routes->get('/managetrees', 'Admin::indexManageTrees');
    $routes->get('/addtree', 'Admin::indexAddTree');
    $routes->post('/admin/addtree', 'Admin::addTree');
    $routes->get('/updatetree', 'Admin::indexUpdateTree');
    $routes->post('/admin/updatetree', 'Admin::updateTree');
    $routes->post('/admin/deletetree', 'Admin::deleteTree');

    $routes->post('/admin/friendtrees', 'Admin::friendtrees');
    $routes->get('/updatefriendtree', 'Admin::indexUpdateFriendTree');
    $routes->post('/admin/updatefriendtree', 'Admin::updateFriendTree');

    $routes->get('adduser', 'Admin::indexAddUser');
    $routes->post('/admin/adduser', 'Admin::addUser');
});

// ================================================================
// Friend Routes (For Friend Users)
$routes->group('friend', ['filter' => 'auth:2'], function ($routes) {
    $routes->get('dashboard', 'User::indexFriend'); // Friend dashboard

    $routes->get('mytrees', 'Tree::mytrees'); // View friend's trees
    $routes->get('tree_detail/(:segment)', 'Tree::treeDetail/$1'); // View tree details
    $routes->get('tree_detail_friend/(:segment)', 'Tree::treeDetailFriend/$1'); // View friend's tree details

    $routes->post('purchase/processPurchase', 'Purchase::processPurchase'); // Process purchase
    $routes->get('purchase/success', 'Purchase::success'); // Purchase success
    $routes->post('addToCart', 'Cart::addToCart'); // Add item to cart
    $routes->post('showCart', 'Cart::showCart'); // Show cart
    $routes->post('cartRemove', 'Cart::removeItem'); // Remove item from cart
    $routes->post('cart/buyAll', 'Purchase::buyAllItems'); // Buy all items in cart
});

// ================================================================
// Operator Routes (For Operator Users)
$routes->group('operator', ['filter' => 'auth:3'], function ($routes) {
    $routes->get('dashboard', 'User::indexOperatorHome'); // Operator home/dashboard
    $routes->get('registerupdate', 'User::indexRegisterUpdate'); // Register update page
    $routes->post('registerupdate', 'User::registerUpdate'); // Process register update

});

// ================================================================
// Admin and Operator Shared Routes
$routes->group('', ['filter' => 'auth:1,3'], function ($routes) {
    $routes->get('/registerupdate', 'User::indexRegisterUpdate'); // Register update page
    $routes->post('/operator/registerupdate', 'User::registerUpdate'); // Process register update
    $routes->get('/managefriends', 'User::indexManageFriends');
    $routes->get('/friendtrees', 'Admin::indexFriendTrees');

});

$routes->group('', ['filter' => 'auth:1,2,3'], function ($routes) {
    $routes->get('/treehistory', 'User::indexTreeHistory'); // View tree history
    $routes->post('/operator/treehistory', 'User::treeHistory'); // Process tree history, no se que hace esto

});



