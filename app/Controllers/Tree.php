<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TreeModel; 
use App\Models\PurchaseModel; 
use App\Models\CartModel; 

class Tree extends BaseController
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
        $this->session          = \Config\Services::session();
        $this->cartModel = new CartModel();


    }


    /**
     * Displays the mytrees page
    */    
    public function mytrees()
    {
        $purchaseMessage = session()->get('purchase_message');
        if ($purchaseMessage) {
            session()->remove('purchase_message');
        }

        // Retrieve user details
        $user       = $this->userModel->where('Username', session()->get('username'))->first(); 
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        // Get the trees for the user
        $trees = $this->purchaseModel->getFriendsTrees( $user['Id_User']);

        $cartCount = $this->cartModel->where('User_Id', $user['Id_User'])->where('Status', 'active')->countAllResults();
        $carts = $this->cartModel->getCartDetails($user['Id_User']);
        return view('shared/header') . 
        view('shared/navegation_friend', [
            'profilePic'        => $profilePic,
            'uploads_profile'   => base_url('uploads_profile/'),
            'cartCount' => $cartCount,
            'carts' => $carts,
            'uploads_folder'    => base_url('uploads_tree/')

        ]) .  
        view('friend/mytrees', [
            'trees' => $trees,
            'purchase_message'  => $purchaseMessage,
            'uploads_folder'    => base_url('uploads_tree/')
        ]);
    }
    public function treeDetail($id)
    {
        $treeModel = new TreeModel();
        
        $tree = $treeModel->getTreeById($id);

        if (!$tree) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Árbol no encontrado');
        }

        $user       = $this->userModel->where('Username', session()->get('username'))->first(); 
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        $cartCount = $this->cartModel->where('User_Id', $user['Id_User'])->where('Status', 'active')->countAllResults();
        $carts = $this->cartModel->getCartDetails($user['Id_User']);
        return view('shared/header') . 
        view('shared/navegation_friend', [
            'profilePic'        => $profilePic,
            'uploads_profile'   => base_url('uploads_profile/'),
            'cartCount' => $cartCount,
            'carts' => $carts,
            'uploads_folder'    => base_url('uploads_tree/')

        ]) . 
        view('friend/tree_detail', [
            'tree' => $tree,
            'uploads_folder'    => base_url('uploads_tree/')
        ]);
    }
    public function detail($id) {
        $tree = $this->treeModel->getTreeById($id);

        if ($tree) {
            echo $this->view('tree_detail_friend', ['tree' => $tree], true);
        } else {
            echo "Error: Tree not found.";
        }
    
    }
    public function treeDetailFriend($id)
    {
        $treeModel = new TreeModel();
        
        $tree = $treeModel->getTreeById($id);
        $purchaseModel = new PurchaseModel(); // Instanciar el modelo de productos
        $purchaseModel = $purchaseModel->getPurchaseByUserId($id);
        if (!$tree) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Árbol no encontrado');
        }

        $user       = $this->userModel->where('Username', session()->get('username'))->first(); 
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        $cartCount = $this->cartModel->where('User_Id', $user['Id_User'])->where('Status', 'active')->countAllResults();
        $carts = $this->cartModel->getCartDetails($user['Id_User']);
        return view('shared/header') . 
        view('shared/navegation_friend', [
            'profilePic'        => $profilePic,
            'uploads_profile'   => base_url('uploads_profile/'),
            'cartCount' => $cartCount,
            'carts' => $carts,
            'uploads_folder'    => base_url('uploads_tree/')

        ]) . 
        view('friend/tree_detail_friend', [
            'tree' => $tree,
            'products'       => $purchaseModel,
            'uploads_folder'    => base_url('uploads_tree/')
        ]);
    }
    

}


