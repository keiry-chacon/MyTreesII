<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TreeModel; 
use App\Models\PurchaseModel; 

class Tree extends BaseController
{
    private $db;
    private $treeModel;
    private $userModel;
    private $purchaseModel;

    public function __construct()
    {
        // Connect to the database
        $this->db               = \Config\Database::connect();

        // Load the Models
        $this->treeModel        = model(TreeModel::class); 
        $this->userModel        = model(UserModel::class);
        $this->purchaseModel    = model(PurchaseModel::class);
        $this->session = \Config\Services::session();


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

        return view('shared/header', [
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('shared/navegation_friend', [
            'profilePic'        => $profilePic,
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('friend/mytrees', [
            'trees' => $trees,
            'purchase_message'  => $purchaseMessage,
            'uploads_folder'    => base_url('uploads_tree/')
        ]);
    }
    public function treeDetail($id)
    {
        // Instanciar el modelo de árboles
        $treeModel = new TreeModel();
        
        // Obtener los detalles del árbol por su ID
        $tree = $treeModel->getTreeById($id);
        $purchaseModel = new PurchaseModel(); // Instanciar el modelo de productos
        $purchaseModel = $purchaseModel->getPurchaseByUserId($id);
        // Verificar si el árbol existe
        if (!$tree) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Árbol no encontrado');
        }

        $user       = $this->userModel->where('Username', session()->get('username'))->first(); 
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        return view('shared/header') . 
        view('shared/navegation_friend', [
            'profilePic'        => $profilePic,
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('friend/tree_detail', [
            'tree' => $tree,
            'products'       => $purchaseModel,
            'uploads_folder'    => base_url('uploads_tree/')
        ]);
    }
        public function detail($id)
        {
            // Cargar el modelo para obtener la información del árbol
            $this->load->model('TreeModel');
            $tree = $this->TreeModel->getTreeById($id);
    
            // Validar si existe
            if (!$tree) {
                show_404();
                return;
            }
    
            // Cargar la vista con los detalles
            $data['tree'] = $tree;
            $this->load->view('tree_detail_friend', $data);
        }
        
    }


