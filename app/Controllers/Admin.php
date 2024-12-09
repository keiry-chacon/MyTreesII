<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SpeciesModel;
use App\Models\TreeModel;
use App\Models\CountryModel;
use App\Models\ProvinceModel;
use App\Models\DistrictModel;


class Admin extends BaseController
{
    private $db;
    private $userModel;
    private $speciesModel;
    private $treeModel;
    private $countryModel;
    private $provinceModel;
    private $districtModel;

    public function __construct()
    {
        // Connect to the database
        $this->db               = \Config\Database::connect();
        
        // Load the Models
        $this->userModel        = model(UserModel::class);
        $this->speciesModel     = model(SpeciesModel::class);
        $this->treeModel        = model(TreeModel::class);
        $this->countryModel     = model(CountryModel::class);
        $this->provinceModel    = model(ProvinceModel::class);
        $this->districtModel    = model(DistrictModel::class);
    }





    /**
     * Displays the Admin page
     */    
    public function indexHome()
    {
        $username = session()->get('username');
        $user     = $this->userModel->where('Username', $username)->first();
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        // Obtener el número de árboles disponibles
        $treeModel = new TreeModel();
        $availableTreesCount = $treeModel->where('StatusT', 1)->countAllResults(); // Árboles disponibles
        $soldTreesCount = $treeModel->where('StatusT', 0)->countAllResults(); // Árboles vendidos

        $userModel = new UserModel();
        $genders = $userModel->getFriends(); // Datos de géneros

        return view('shared/header', [
            'uploads_profile' => base_url('uploads_profile/')
        ]) . 
        view('shared/navegation_admin', [
            'profilePic'      => $profilePic,
            'uploads_profile' => base_url('uploads_profile/')
        ]) . 
        view('admin/adminHome', [
            'availableTreesCount' => $availableTreesCount,
            'soldTreesCount' => $soldTreesCount,
            'genders' => $genders, // Datos de género
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





    /**
     * Displays the list of registered species
    */
    public function indexManageSpecies()
    {
        $species = $this->speciesModel->getSpeciesByStatus(1);

        $data['species'] = $species;
        return view('shared/header', $data) . view('admin/manageSpecies', $data);
    }



    

    /**
     * Displays the interface of the add species section
    */
    public function indexAddSpecies()
    {
        return view('shared/header') . view('admin/addSpecies');
    }


    /**
     * Add a new species to the database
    */
    public function addspecies()
    {
        // Data received from the form
        $data = [
            'Commercial_Name'  => $this->request->getPost('commercial_name'),
            'Scientific_Name'  => $this->request->getPost('scientific_name'),
        ];
    
        $speciesModel = new SpeciesModel();
    
        // Validation and saving
        if (!$speciesModel->save($data)) {
            // Get validation errors from the model
            $errors = $speciesModel->errors();
    
            // Redirect back to the form with errors
            return redirect()->back()->withInput()->with('error', $errors);
        }
    
        // If everything is correct, redirect to the login page with a success message
        return redirect()->to('/managespecies')->with('success', 'Species Added Succesfully');
    }






    /**
     * Displays the interface of the update species section
    */
    public function indexUpdateSpecies()
    {
        // Get the species ID from the URL (GET parameter)
        $idSpecie = $this->request->getGet('id_specie');
        
        // Validate that the ID is valid
        if (!$idSpecie || !is_numeric($idSpecie)) {
            return redirect()->to('/managespecies')->with('error', 'Invalid species ID');
        }

        // Create the species model
        $speciesModel = new SpeciesModel();

        // Search for the species by ID
        $specie = $speciesModel->find($idSpecie);

        // Check if the species exists
        if (!$specie) {
            return redirect()->to('/managespecies')->with('error', 'Species not found');
        }

        // Pass the species data and any error (if any) to the view
        $error_msg = session()->get('error');  // Get the error, if it exists
        return view('shared/header') . view('admin/updateSpecies', ['specie' => $specie, 'error_msg' => $error_msg]);
    }


    /**
     * Update an existing species to the database
    */
    public function updateSpecies()
    {
        // Get the form data
        $idSpecie       = $this->request->getPost('id_specie'); // Species ID from the URL
        $commercialName = $this->request->getPost('commercial_name');
        $scientificName = $this->request->getPost('scientific_name');

        // Validate that the species ID is valid
        if (!$idSpecie || !is_numeric($idSpecie)) {
            log_message('error', 'Invalid or missing species ID.');
            return redirect()->to('/managespecies')->with('error', 'Invalid species ID');
        }
        
        // Load the species model
        $speciesModel = new SpeciesModel();

        // Prepare the data for the update
        $data = [];
        if (!empty($commercialName)) {
            $data['Commercial_Name'] = $commercialName;
        }
        if (!empty($scientificName)) {
            $data['Scientific_Name'] = $scientificName;
        }

        // Check if there are any fields to update
        if (empty($data)) {
            return redirect()->back()->with('error', 'No fields to update.');
        }

        // Conditional validation: Disable uniqueness validation only for unchanged fields
        if (!empty($commercialName)) {
            // Validate only the commercial name if it is being updated
            $speciesModel->setValidationRules([
                'Commercial_Name' => 'required|min_length[1]|is_unique[species.Commercial_Name,Id_Specie,' . $idSpecie . ']',
                'Scientific_Name' => 'permit_empty', // Allow no change without validation
            ]);
        } elseif (!empty($scientificName)) {
            // Validate only the scientific name if it is being updated
            $speciesModel->setValidationRules([
                'Commercial_Name' => 'permit_empty',
                'Scientific_Name' => 'required|min_length[1]|is_unique[species.Scientific_Name,Id_Specie,' . $idSpecie . ']',
            ]);
        }

        // Try to update the species
        if (!$speciesModel->update($idSpecie, $data)) {
            // Get errors from the model
            $errors = $speciesModel->errors();
            log_message('error', 'Errors trying to update: ' . implode(', ', $errors));

            // Redirect with errors if the update fails
            return redirect()->back()->withInput()->with('error', implode(', ', $errors));
        }

        // If everything is correct, redirect to the species management page with a success message
        return redirect()->to('/managespecies')->with('success', 'Species updated successfully');
    }


    


    /**
     * Delete a species to the database
    */
    public function deletespecies()
    {
        // Id_Specie received from the form
        $idSpecie       = $this->request->getPost('id_specie');
    
        $speciesModel   = new SpeciesModel();
    
        // Validation and saving
        if (!$speciesModel->update($idSpecie, ['StatusS' => 0])) {
            // Get validation errors from the model
            $errors = $speciesModel->errors();
    
            // Redirect back to the form with errors
            return redirect()->back()->withInput()->with('error', $errors);
        }
    
        // If everything is correct, redirect to the login page with a success message
        return redirect()->to('/managespecies')->with('success', 'Tree Deleted Succesfully');
    }





    /**
     * Displays the list of registered trees
    */
    public function indexManageTrees()
    {
        $trees = $this->treeModel->getAvailableTrees();

        $data['trees'] = $trees;
        return view('shared/header', $data) . view('admin/manageTrees', $data);
    }





    /**
     * Displays the interface of the add tree section
    */
    public function indexAddTree()
    {
        $species = $this->speciesModel->getSpeciesByStatus(1);

        $data['species'] = $species;
        return view('shared/header', $data) . view('admin/addtree', $data);
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
        return redirect()->to('/managetrees')->with('success', 'Tree Added Succesfuly');
    }







    /**
     * Displays the interface of the update tree section
     */
    public function indexUpdateTree()
    {
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
            return redirect()->to('/managetrees')->with('error', 'Tree not found');
        }

        // Fetch species data to display in the dropdown
        $species = $this->speciesModel->getSpeciesByStatus(1);

        // Prepare the data to be passed to the view
        $data = [
            'species' => $species,
            'tree' => $tree,
            'error_msg' => session()->get('error')  // Get any error message, if exists
        ];

        // Return the full view with header and the update tree form
        return view('shared/header', $data) . view('admin/updateTree', $data);
    }





    /**
     * Update an existing tree in the database
     */
    public function updateTree()
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
            return redirect()->to('/managetrees')->with('error', 'Tree not found');
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
        return redirect()->to('/managetrees')->with('success', 'Tree updated successfully');
    }








    /**
     * Add a new species to the database
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
        return redirect()->to('/managetrees')->with('success', 'Tree Deleted Succesfully');
    }





    /**
     * Displays the list of registered friends
     */
    public function indexManageFriends()
    {
        // Obtener la lista de usuarios disponibles
        $users = $this->userModel->getAvailableUsers();

        // Preparar los datos para pasar a las vistas
        $data['users'] = $users;
        $data['uploads_folder'] = base_url('uploads_profile/'); // Correcta llamada a base_url()

        // Renderizar ambas vistas correctamente
        return view('shared/header', $data) . view('admin/manageFriends', $data);
    }






     /**
     * Displays the list of friend trees
    */
    public function indexFriendTrees()
    {
        $idUser = $this->request->getGet('id_user');

        $trees = $this->treeModel->getFriendsTrees($idUser);

        $data['trees'] = $trees;
        $data['uploads_folder'] = base_url('uploads_tree/'); // Correcta llamada a base_url()
        return view('shared/header', $data) . view('admin/friendtrees', $data);
    }





       /**
     * Displays the interface of the update tree section
     */
    public function indexUpdateFriendTree()
    {
        // Get the tree ID from the URL (GET parameter)
        $idTree = $this->request->getGet('id_tree');
        
        // Validate that the ID is valid
        if (!$idTree || !is_numeric($idTree)) {
            return redirect()->to('/managefriends')->with('error', 'Invalid Tree ID');
        }

        // Create the tree model
        $treeModel = new TreeModel();

        // Search for the tree by ID
        $tree = $treeModel->find($idTree);

        // Check if the tree exists
        if (!$tree) {
            return redirect()->to('/managefriends')->with('error', 'Tree not found');
        }

        // Fetch species data to display in the dropdown
        $species = $this->speciesModel->getSpeciesByStatus(1);

        // Prepare the data to be passed to the view
        $data = [
            'species' => $species,
            'tree' => $tree,
            'error_msg' => session()->get('error')  // Get any error message, if exists
        ];

        // Return the full view with header and the update tree form
        return view('shared/header', $data) . view('admin/updatefriendtree', $data);
    }


    /**
     * Update an existing tree in the database
     */
    public function updateFriendTree() //PENSAR EN BORRAR ESTA
    {
        // Get the form data
        $idTree       = $this->request->getPost('id_tree'); // Tree ID from the POST data
        $size         = $this->request->getPost('size');
        $status       = $this->request->getPost('status');

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

        // Check if there are any fields to update
        if (empty($data)) {
            return redirect()->back()->with('error', 'No fields to update.');
        }

        // Check if tree exists in the database
        $tree = $treeModel->find($idTree);
        if (!$tree) {
            return redirect()->to('/managetrees')->with('error', 'Tree not found');
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
        return redirect()->to('/managetrees')->with('success', 'Tree updated successfully');
    }





    /**
     * Displays the interface of the add species section
    */
    public function indexAddUser()
    {
        $country    = $this->countryModel->findAll();
        $province   = $this->provinceModel->findAll();
        $district   = $this->districtModel->findAll();

        $data['country']    = $country;
        $data['province']   = $province;        
        $data['district']   = $district;
        return view('shared/header', $data) . view('admin/addUser', $data);    
    }


    /**
     * Add a new user to the database
    */
    public function adduser()
    {
        // Data received from the form
        $data = [
            'First_Name'  => $this->request->getPost('first_name'),
            'Last_Name1'  => $this->request->getPost('last_name1'),
            'Last_Name2'  => $this->request->getPost('last_name2'),
            'Username'    => $this->request->getPost('username'),
            'Password'    => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'Email'       => $this->request->getPost('email'),
            'Phone'       => $this->request->getPost('phone'),
            'Gender'      => $this->request->getPost('gender'),
            'District_Id' => $this->request->getPost('district'),
            'Role_Id'     => $this->request->getPost('role'),
        ];
    
        // Handling the profile picture
        $file = $this->request->getFile('profilePic'); // Get the file
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Generate a unique name to avoid collisions
            $newName = $file->getRandomName();
    
            // Move the file to the uploads folder
            $file->move(WRITEPATH . 'uploads_profile', $newName);
    
            // Save the file name in the record
            $data['Profile_Pic'] = $newName;
        } else {
            // If no file is uploaded, use the default image
            $data['Profile_Pic'] = 'default_profile.png';
        }
    
        $userModel = new UserModel();
    
        // Validation and saving
        if (!$userModel->save($data)) {
            // Get validation errors from the model
            $errors = $userModel->errors();
    
            // Redirect back to the form with errors
            return redirect()->back()->withInput()->with('error', $errors);
        }
    
        // If everything is correct, redirect to the login page with a success message
        return redirect()->to('/adminhome')->with('success', 'The account was successfully created!');
    }

}