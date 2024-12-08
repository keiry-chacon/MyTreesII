<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TreeModel; 
use App\Models\PurchaseModel; 
use App\Models\CartModel;

class Friend extends BaseController
{
    private $db;
    private $treeModel;
    private $userModel;
    private $purchaseModel;
    protected $cartModel;

    public function __construct()
    {
        
        // Connect to the database
        $this->db               = \Config\Database::connect();

        // Load the Models
        $this->treeModel        = model(TreeModel::class); 
        $this->userModel        = model(UserModel::class);
        $this->purchaseModel    = model(PurchaseModel::class);
        $this->cartModel = new CartModel();

    }

    /**
     * Displays the friend page
    */    
    public function index()
    {
        $purchaseMessage = session()->get('purchase_message');
        if ($purchaseMessage) {
            session()->remove('purchase_message');
        }

        $user       = $this->userModel->where('Username', $_SESSION['username'])->first(); 
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        $trees = $this->treeModel->getAvailableTrees(); 
        $session = session();
        $userId = $session->get('user_id');
        $cartCount = $this->cartModel->where('User_Id', $userId)->where('Status', 'active')->countAllResults();

// Consulta para obtener los elementos activos en el carrito
         $carts = $this->cartModel->getCartDetails($userId);




        return view('shared/header', [
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('shared/navegation_friend', [
            'profilePic'        => $profilePic,
            'uploads_profile'   => base_url('uploads_profile/'),
            'cartCount' => $cartCount,
            'carts' => $carts,
            'uploads_folder'    => base_url('uploads_tree/')

        ]) . 
        view('friend/dashboard', [
            'trees'             => $trees,
            'purchase_message'  => $purchaseMessage,
            'uploads_folder'    => base_url('uploads_tree/')
        ]);
    }


    /**
     * Displays the profile page
    */
    public function Profile()
    {
        // Get the user ID from the session
        $username = session()->get('username');

        if (!$username) {
            // Redirect to the login page if the user is not logged in
            return redirect()->to('/login');
        }

        // Fetch user data from the database
        $user = $this->userModel->where('Username', $username)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User not found');
        }

        // Pass the user data to the view
        return view('shared/header', [
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('shared/navegation_friend', [
            'profilePic'        => $user['Profile_Pic'],
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('user/profile', [
            'user' => $user
        ]);
    }
}

