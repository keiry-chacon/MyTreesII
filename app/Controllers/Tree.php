<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TreeModel; 
use App\Models\PurchaseModel; 
use App\Models\CartModel;
use App\Models\SpeciesModel; 

class Tree extends BaseController
{
    private $db;
    private $treeModel;
    private $speciesModel;
    private $userModel;
    private $purchaseModel;
    protected $cartModel;

    public function __construct()
    {
        // Connect to the database
        $this->db               = \Config\Database::connect();

        // Load the Models
        $this->treeModel        = model(TreeModel::class);
        $this->speciesModel     = model(SpeciesModel::class); 
        $this->userModel        = model(UserModel::class);
        $this->purchaseModel    = model(PurchaseModel::class);
        $this->session          = \Config\Services::session();
        $this->cartModel = new CartModel();
    }




// ==============================================================================================
// Administrator Functions
    /**
     * Displays the list of registered trees
    */
    public function indexManageTrees()
    {
        $username = session()->get('username');
        $user     = $this->userModel->where('Username', $username)->first();
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        $trees = $this->treeModel->getAvailableTrees();

        $data['trees']              = $trees;
        $data['profilePic']         = $profilePic;
        $data['uploads_profile']    = base_url('uploads_profile/');
        return view('shared/header', $data) . view('shared/navegation_admin', $data) . view('admin/manageTrees', $data);
    }




    /**
     * Displays the interface of the add tree section
    */
    public function indexAddTree()
    {
        $username = session()->get('username');
        $user     = $this->userModel->where('Username', $username)->first();
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        $species = $this->speciesModel->getSpeciesByStatus(1);

        $data['species']            = $species;
        $data['profilePic']         = $profilePic;
        $data['uploads_profile']    = base_url('uploads_profile/');
        return view('shared/header', $data) . view('shared/navegation_admin', $data) . view('admin/addtree', $data);
    }


    /**
     * Add a new tree to the database
    */
    public function addtree()
    {
        // Data received from the form
        $data = [
            'Specie_Id'     => $this->request->getPost('specie_id'),
            'Location'      => $this->request->getPost('location'),
            'Size'          => $this->request->getPost('size'),
            'StatusT'       => $this->request->getPost('status'),
            'Price'         => $this->request->getPost('price'),
        ];
    
        // Handling the tree picture
        $file = $this->request->getFile('treePic'); // Get the file
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Generate a unique name to avoid collisions
            $newName = $file->getRandomName();
    
            // Move the file to the uploads folder
            $file->move(FCPATH . '/uploads_tree', $newName);  // FCPATH is the root folder of your project
    
            // Save the file name in the record
            $data['Photo_Path'] = $newName;
        } else {
            // If no file is uploaded, use the default image
            $data['Photo_Path'] = 'default_tree.png';
        }
    
        $treeModel = new TreeModel();
    
        // Validation and saving
        if (!$treeModel->save($data)) {
            // Get validation errors from the model
            $errors = $treeModel->errors();
    
            // Redirect back to the form with errors
            return redirect()->back()->withInput()->with('error', $errors);
        }
    
        // If everything is correct, redirect to the login page with a success message
        return redirect()->to('/admin/managetrees')->with('success', 'Tree Added Succesfuly');
    }




    /**
     * Displays the interface of the update tree section
     */
    public function indexUpdateTree()
    {
        $username = session()->get('username');
        $user     = $this->userModel->where('Username', $username)->first();
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        // Get the tree ID from the URL (GET parameter)
        $idTree = $this->request->getGet('id_tree');
        
        // Validate that the ID is valid
        if (!$idTree || !is_numeric($idTree)) {
            return redirect()->to('/managetrees')->with('error', 'Invalid Tree ID');
        }

        // Create the tree model
        $treeModel = new TreeModel();

        // Search for the tree by ID
        $tree = $treeModel->find($idTree);

        // Check if the tree exists
        if (!$tree) {
            return redirect()->to('/admin/managetrees')->with('error', 'Tree not found');
        }

        // Fetch species data to display in the dropdown
        $species = $this->speciesModel->getSpeciesByStatus(1);

        // Prepare the data to be passed to the view
        $data = [
            'species' => $species,
            'tree' => $tree,
            'profilePic' => $profilePic,
            'uploads_profile' => base_url('uploads_profile/'),
            'error_msg' => session()->get('error')  // Get any error message, if exists
        ];

        // Return the full view with header and the update tree form
        return view('shared/header', $data) . view('shared/navegation_admin', $data) . view('admin/updateTree', $data);
    }


    /**
     * Update an existing tree in the database
     */
    public function updateTree() //ESTA FUNCIÓN ACTUALIZA DE UN ÁRBOL DE MANAGE TREES
    {
        // Get the form data
        $idTree       = $this->request->getPost('id_tree'); // Tree ID from the POST data
        $specieId     = $this->request->getPost('specie_id');
        $location     = $this->request->getPost('location');
        $size         = $this->request->getPost('size');
        $status       = $this->request->getPost('status');
        $price        = $this->request->getPost('price');

        // Validate that the tree ID is valid
        if (!$idTree || !is_numeric($idTree)) {
            log_message('error', 'Invalid or missing tree ID.');
            return redirect()->to('/managetrees')->with('error', 'Invalid tree ID');
        }

        // Load the tree model
        $treeModel = new TreeModel();

        // Prepare the data for the update
        $data = [];
        if (!empty($specieId)) {
            $data['Specie_Id'] = $specieId;
        }
        if (!empty($location)) {
            $data['Location'] = $location;
        }
        if (!empty($size)) {
            $data['Size'] = $size;
        }
        if (!empty($status)) {
            $data['StatusT'] = $status;
        }
        if (!empty($price)) {
            $data['Price'] = $price;
        }

        // Check if there are any fields to update
        if (empty($data)) {
            return redirect()->back()->with('error', 'No fields to update.');
        }

        // Handling the tree picture
        $file = $this->request->getFile('treePic'); // Get the file if it exists
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Generate a unique name for the image to avoid collision
            $newName = $file->getRandomName();
            
            // Move the file to the uploads folder
            $file->move(FCPATH . '/uploads_tree', $newName);  // FCPATH is the root folder of your project
            
            // Save the new file name in the record
            $data['Photo_Path'] = $newName;
        }

        // Check if tree exists in the database
        $tree = $treeModel->find($idTree);
        if (!$tree) {
            return redirect()->to('/admin/managetrees')->with('error', 'Tree not found');
        }

        // Try to update the tree
        if (!$treeModel->update($idTree, $data)) {
            // Get errors from the model
            $errors = $treeModel->errors();
            log_message('error', 'Errors trying to update tree: ' . implode(', ', $errors));
            
            // Redirect with errors if the update fails
            return redirect()->back()->withInput()->with('error', implode(', ', $errors));
        }

        // If everything is correct, redirect to the trees management page with a success message
        return redirect()->to('/admin/managetrees')->with('success', 'Tree updated successfully');
    }




    /**
     * Delete a tree
    */
    public function deletetree()
    {
        // Id_Tree received from the form
        $idTree    = $this->request->getPost('id_tree');
    
        $treeModel = new TreeModel();
    
        // Validation and saving
        if (!$treeModel->update($idTree, ['StatusT' => 0])) {
            // Get validation errors from the model
            $errors = $treeModel->errors();
    
            // Redirect back to the form with errors
            return redirect()->back()->withInput()->with('error', $errors);
        }
    
        // If everything is correct, redirect to the login page with a success message
        return redirect()->to('/admin/managetrees')->with('success', 'Tree Deleted Succesfully');
    }

    


    /**
     * Displays the interface of the update tree friend section
     */
    public function indexUpdateFriendTree()
    {
        $username = session()->get('username');
        $user     = $this->userModel->where('Username', $username)->first();
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        // Get the tree ID from the URL (GET parameter)
        $idTree = $this->request->getGet('id_tree');
        
        // Validate that the ID is valid
        if (!$idTree || !is_numeric($idTree)) {
            return redirect()->to('/admin/managefriends')->with('error', 'Invalid Tree ID');
        }

        // Create the tree model
        $treeModel = new TreeModel();

        // Search for the tree by ID
        $tree = $treeModel->find($idTree);

        // Check if the tree exists
        if (!$tree) {
            return redirect()->to('/admin/managefriends')->with('error', 'Tree not found');
        }

        // Fetch species data to display in the dropdown
        $species = $this->speciesModel->getSpeciesByStatus(1);

        // Prepare the data to be passed to the view
        $data = [
            'species' => $species,
            'tree' => $tree,
            'profilePic' => $profilePic,
            'uploads_profile' => base_url('uploads_profile/'),
            'error_msg' => session()->get('error')  // Get any error message, if exists
        ];

        // Return the full view with header and the update tree form
        return view('shared/header', $data) . view('shared/navegation_admin', $data) . view('admin/updatefriendtree', $data);
    }


    /**
     * Update an existing tree from Manage Friends in the database
     */
    public function updateFriendTree() //ESTA ES LA DE UPDATE TREE EN MANAFE FRIENDS
    {
        // Get the form data
        $idTree       = $this->request->getPost('id_tree'); // Tree ID from the POST data
        $size         = $this->request->getPost('size');
        $status       = $this->request->getPost('status');

        // Validate that the tree ID is valid
        if (!$idTree || !is_numeric($idTree)) {
            log_message('error', 'Invalid or missing tree ID.');
            return redirect()->to('/managefriends')->with('error', 'Invalid tree ID');
        }

        // Load the tree model
        $treeModel = new TreeModel();

        // Prepare the data for the update
        $data = [];
        if (!empty($specieId)) {
            $data['Specie_Id'] = $specieId;
        }
        if (!empty($location)) {
            $data['Location'] = $location;
        }
        if (!empty($size)) {
            $data['Size'] = $size;
        }
        if (!empty($status)) {
            $data['StatusT'] = $status;
        }

        // Check if there are any fields to update
        if (empty($data)) {
            return redirect()->back()->with('error', 'No fields to update.');
        }

        // Check if tree exists in the database
        $tree = $treeModel->find($idTree);
        if (!$tree) {
            return redirect()->to('/managefriends')->with('error', 'Tree not found');
        }

        // Try to update the tree
        if (!$treeModel->update($idTree, $data)) {
            // Get errors from the model
            $errors = $treeModel->errors();
            log_message('error', 'Errors trying to update tree: ' . implode(', ', $errors));
            
            // Redirect with errors if the update fails
            return redirect()->back()->withInput()->with('error', implode(', ', $errors));
        }

        // If everything is correct, redirect to the trees management page with a success message
        return redirect()->to('/managefriends')->with('success', 'Tree updated successfully');
    }






// ==============================================================================================
// Friend Functions
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

