<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CountryModel;
use App\Models\ProvinceModel;
use App\Models\DistrictModel;
use App\Models\TreeUpdateModel;
use App\Models\CartModel;
use App\Models\TreeModel;


use CodeIgniter\Router\Router;

class User extends BaseController
{
    private $db;
    private $userModel;
    private $countryModel;
    private $provinceModel;
    private $districtModel;
    private $treeupdateModel;
    private $treeModel;
    private $cartModel; // Agregar CartModel

    public function __construct()
    {
        // Connect to the database
        $this->db               = \Config\Database::connect();

        // Load the Models
        $this->countryModel     = model(CountryModel::class);
        $this->provinceModel    = model(ProvinceModel::class);
        $this->districtModel    = model(DistrictModel::class);
        $this->userModel        = model(UserModel::class);
        $this->treeupdateModel  = model(TreeUpdateModel::class);
        $this->treeModel        = model(TreeModel::class);
        $this->cartModel        = model(CartModel::class); // Inicializar CartModel

    }
    /**
     * Displays the Friend page
    */
    public function indexLogin()
    {
    // Return the 'header' view followed by the 'login' view
    return view('shared/header') . view('user/login');
    }

    /**
     * Handle the user login process
    */
    public function login()
    {
    // Get the username and password from the post request and remove any extra spaces
    $username = trim($this->request->getPost('username'));
    $password = trim($this->request->getPost('password'));

    // Log the username for debugging (password should never be logged in production)
    log_message('debug', 'Username: ' . $username);

    // If either the username or password is empty, redirect back to the login page with an error message
    if (empty($username) || empty($password)) {
        return redirect()->to('/login')->with('error', 'Please fill out all fields');
    }

    // Authenticate the user using the UserModel's authenticate method
    $user = $this->userModel->authenticate($username, $password);

    if ($user) {
        // If authentication is successful, store user session data
        session()->set([
        'username'   => $user['Username'],
        'user_id'    => $user['Id_User'],
        'role_id'    => $user['Role_Id'],
        'isLoggedIn' => true,
        'profile_pic'=> $user['Profile_Pic'],
        ]);
        
        // Redirect user to the appropriate page based on their role
        switch ($user['Role_Id']) {
        case '1':
            return redirect()->to('/adminhome'); // Admin dashboard
        case '2':
            return redirect()->to('/friend/dashboard'); // Friend dashboard
        case '3':
            return redirect()->to('/operatorhome'); // Operator home
        default:
            return redirect()->to('/'); // Default redirect
        }
    } else {
        // If authentication fails, redirect back to the login page with an error message
        return redirect()->to('/login')->with('error', 'Incorrect credentials');
    }
    }

    /**
     * Handle the user LogOut process
    */
    public function logout()
    {
    // Destroy all session variables to log out the user
    session()->destroy();

    // Redirect the user to the login page after logging out
    return redirect()->to('/login');
    }

    /**
     * Displays the SignUp page
     */
    public function indexSignUp()
    {
    // Fetch data for countries, provinces, and districts to populate the sign-up form
    $country = $this->countryModel->findAll();
    $province = $this->provinceModel->findAll();
    $district = $this->districtModel->findAll();

    // Prepare the data array with countries, provinces, and districts
    $data['country'] = $country;
    $data['province'] = $province;        
    $data['district'] = $district;

    // Return the 'header' view with the data, followed by the 'signup' view
    return view('shared/header', $data) . view('user/signup', $data);
    }

    /**
     * Fetches provinces based on the selected country
     */
    public function getProvinces()
    {
    // Get the selected country ID from the POST request
    $countryId = $this->request->getPost('country_id');

    // If no country is selected, return an error message
    if (empty($countryId)) {
        return $this->response->setJSON(['message' => 'Please select a country first']);
    }

    // Create a new instance of the ProvinceModel
    $provinceModel = new ProvinceModel();

    try {
        // Use a custom method to fetch provinces by the selected country
        $provinces = $provinceModel->getProvincesByCountry($countryId);

        // If no provinces are found, return a message indicating this
        if (empty($provinces)) {
        return $this->response->setJSON(['message' => 'No provinces available']);
        }

        // Prepare HTML options for the provinces to be displayed in the dropdown
        $options = '<option value="">Select Province</option>';
        foreach ($provinces as $province) {
        $options .= '<option value="' . $province['Id_Province'] . '">' . $province['Province_Name'] . '</option>';
        }

        // Return the generated options as a JSON response
        return $this->response->setJSON(['options' => $options]);

    } catch (\Exception $e) {
        // If an error occurs while fetching provinces, return an error message
        return $this->response->setJSON(['message' => 'Error fetching provinces: ' . $e->getMessage()]);
    }
    }
    /**
     * Get the districts based on the province ID
     */
    public function getDistricts()
    {
        $provinceId = $this->request->getPost('province_id');  // Get the province ID from the post request
        
        // Create an instance of the DistrictModel to interact with the database
        $districtModel = new DistrictModel();
        
        // Fetch districts that match the provided province ID
        $districts = $districtModel->where('Province_Id', $provinceId)->findAll();
        
        // If districts are found, prepare the options for the dropdown
        if ($districts) {
            $options = '';  // Initialize an empty string for the options
            foreach ($districts as $district) {
                // Append each district to the options list
                $options .= "<option value='{$district['Id_District']}'>{$district['District_Name']}</option>";
            }
            // Return the options as a JSON response
            return $this->response->setJSON(['options' => $options]);
        } else {
            // If no districts are found, return an error message
            return $this->response->setJSON(['message' => 'No districts found']);
        }
    }

    /**
     * Add a new user to the database
     */
    public function signup()
    {
        $Role_Id = 2;  // Set the default role ID for the user (2 represents a normal user)

        // Data received from the form
        $data = [
            'First_Name'  => $this->request->getPost('first_name'),
            'Last_Name1'  => $this->request->getPost('last_name1'),
            'Last_Name2'  => $this->request->getPost('last_name2'),
            'Username'    => $this->request->getPost('username'),
            'Password'    => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),  // Hash the password
            'Email'       => $this->request->getPost('email'),
            'Phone'       => $this->request->getPost('phone'),
            'Gender'      => $this->request->getPost('gender'),
            'District_Id' => $this->request->getPost('district'),
            'Role_Id'     => $Role_Id,
        ];

        // Handle the profile picture upload
        $file = $this->request->getFile('profilePic'); // Get the file input
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Generate a unique name for the file to avoid collisions
            $newName = $file->getRandomName();

            // Move the file to the uploads folder
            $file->move(FCPATH . '/uploads_profile', $newName);  // FCPATH is the root folder of the project

            // Save the file name in the data array
            $data['Profile_Pic'] = $newName;
        } else {
            // If no file is uploaded, use a default profile picture
            $data['Profile_Pic'] = 'default_profile.png';
        }

        // Create a new instance of UserModel to interact with the database
        $userModel = new UserModel();

        // Validate and save the user data
        if (!$userModel->save($data)) {
            // Get validation errors from the model
            $errors = $userModel->errors();

            // Redirect back to the form with errors
            return redirect()->back()->withInput()->with('error', $errors);
        }

        // If everything is correct, redirect to the login page with a success message
        return redirect()->to('/login')->with('success', 'Your account was successfully created!');
    }

    /**
     * Displays the RegisterUpdate page
     */
    public function indexRegisterUpdate()
    {
        // Get the tree ID from the URL (GET parameter)
        $idTree = $this->request->getGet('id_tree');

        // Validate that the ID is valid
        if (!$idTree || !is_numeric($idTree)) {
            // If the ID is invalid, redirect with an error message
            return redirect()->to('/managetrees')->with('error', 'Invalid Tree ID');
        }

        // Create the tree model to interact with the database
        $treeModel = new TreeModel();

        // Search for the tree by ID
        $tree = $treeModel->find($idTree);

        // Check if the tree exists
        if (!$tree) {
            // If the tree does not exist, redirect with an error message
            return redirect()->to('/managetrees')->with('error', 'Tree not found');
        }

        // Prepare the data to be passed to the view
        $data = [
            'tree' => $tree,
            'error_msg' => session()->get('error')
        ];

        // Return the full view with header and the update tree form
        return view('shared/header', $data) . view('operator/registerUpdate', $data);
    }

    /**
     * Update a tree with a register (This function is called in Admin with UpdateTree)
     */
    public function registerUpdate()
    {
        // Get the tree ID and size from the form
        $idTree = $this->request->getPost('id_tree');
        $size = $this->request->getPost('size');

        // Validate that the tree ID is valid
        if (!$idTree || !is_numeric($idTree)) {
            log_message('error', 'Invalid or missing tree ID.');
            // If the ID is invalid, redirect with an error message
            return redirect()->to('/managefriends')->with('error', 'Invalid tree ID');
        }

        // Create the tree model to interact with the database
        $treeModel = new TreeModel();

        // Prepare the data to update
        $data = [];
        if (!empty($size)) {
            $data['Size'] = $size;
        }

        // Handle image file (optional)
        $file = $this->request->getFile('treePic');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Generate a unique name for the image
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads_tree', $newName);  // Move the image to the upload folder
            $data['Photo_Path'] = $newName;
        }

        // Check if the tree exists in the database
        $tree = $treeModel->find($idTree);
        if (!$tree) {
            // If the tree is not found, redirect with an error
            return redirect()->to('/managefriends')->with('error', 'Tree not found');
        }

        // Update the tree in the database
        if (!$treeModel->update($idTree, $data)) {
            // If update fails, log the errors and redirect with the error message
            $errors = $treeModel->errors();
            log_message('error', 'Errors trying to update tree: ' . implode(', ', $errors));
            return redirect()->back()->withInput()->with('error', implode(', ', $errors));
        }

        // Call another function to register the tree update
        $result = $this->registerUpdateTree($idTree, $size, $newName ?? null);
        if ($result !== true) {
            // If the update fails, return the error message
            return redirect()->back()->withInput()->with('error', $result);
        }

        // If everything is successful, redirect with a success message
        return redirect()->to('/managefriends')->with('success', 'Tree updated and registered successfully');
    }
    /**
     * Register a tree with a register
     */
    public function registerUpdateTree($idTree, $size, $photoPath = null)
    {
        $treeupdateModel = new TreeUpdateModel();

        // Prepare the data to insert
        $data = [
            'Tree_Id' => $idTree, // Make sure this field matches the table structure
            'Size' => $size
        ];

        // Add photo path if it exists
        if (!empty($photoPath)) {
            $data['Photo_Path'] = $photoPath;
        }

        // Try to insert the new record
        if (!$treeupdateModel->insert($data)) {
            $errors = $treeupdateModel->errors();
            log_message('error', 'Errors trying to insert tree in TreeUpdateModel: ' . implode(', ', $errors));
            return implode(', ', $errors);
        }

        return true;
    }

    /**
     * Displays the Operator page
     */
    public function indextreeHistory()
    {
        $session = session();

        // Get the tree ID from the GET request
        $idTree = $this->request->getGet('id_tree'); 
        $user       = $this->userModel->where('Username', session()->get('username'))->first(); 
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg'; // Profile picture or default
        $userId = $user['Id_User']; 
        $role =   $user['Role_Id']; 
        
        // Instantiate the model
        $treeupdateModel = new TreeUpdateModel();

        // Get the tree update data
        $treeUpdates = $treeupdateModel->getTreeUpdatesWithSpecies($idTree);
        $cartCount = $this->cartModel->where('User_Id', $userId)->where('Status', 'active')->countAllResults();
        $carts = $this->cartModel->getCartDetails($userId);

        // Prepare data for the view
        $data = [
            'treeUpdates' => $treeUpdates,
            'uploads_folder' => base_url('uploads_tree/'),
            'cartCount' => $cartCount,
            'carts' => $carts,
            'profilePic' => $profilePic,
            'uploads_profile' => base_url('uploads_profile/'),
        ];

        // Prepare navigation data
        $navegationData = [
            'profilePic' => $profilePic,
            'uploads_profile' => base_url('uploads_profile/'),
            'cartCount' => $cartCount,
            'carts' => $carts,
            'uploads_folder' => base_url('uploads_tree/'),
        ];

        // Determine the navigation view based on user role
        $navegationView = ($role == '2') // If role is '2', use friend navigation
            ? view('shared/navegation_friend', $navegationData)
            : (($role == '3') // If role is '3', use operator navigation
                ? view('shared/navegation_operator', $navegationData) 
                : view('shared/navegation_admin', $navegationData));

        // Render views
        return view('shared/header', $data) 
            . $navegationView
            . view('shared/tree_history', $data);
    }

    /**
     * Displays the Operator home page
     */
    public function indexOperatorHome()
    {
        // Get session username and user data
        $username   = session()->get('username');
        $user       = $this->userModel->where('Username', $username)->first();
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        // Get available trees count
        $treeModel = new TreeModel();
        $availableTreesCount = $treeModel->where('StatusT', 1)->countAllResults(); // Count available trees

        $userModel = new UserModel();
        $genders = $userModel->getFriends(); // Get gender data

        // Return the view with necessary data
        return view('shared/header', [
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('shared/navegation_operator', [
            'profilePic'        => $profilePic,
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('operator/operatorHome', [
            'availableTreesCount' => $availableTreesCount,
            'genders' => $genders, // Gender data
        ]);
    }

    /**
     * Displays the profile page
     */
    public function profile()
    {
        // Check if the username is set in the session
        if (!isset($_SESSION['username'])) {
            return redirect()->back()->with('error', 'Username not specified.');
        }

        // Get the user data from the database
        $userData = $this->userModel->where('Username', $_SESSION['username'])->first();

        // Check if the user exists
        if (!$userData) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found.');
        }

        // Check if the profile has a picture, if not assign a default one
        $profileImage = $userData['Profile_Pic'] ?? 'default_profile.jpg';

        // Determine the navigation view based on the role
        $navigationView = 'shared/navegation_friend';  // Default value
        if (isset($userData['Role_Id'])) {
            switch ($userData['Role_Id']) {
                case 'admin':
                    $navigationView = 'shared/navegation_admin';  // If role is 'admin', use 'navegation_admin'
                    break;
                case 'operator':
                    $navigationView = 'shared/navegation_operator';  // If role is 'operator', use 'navegation_operator'
                    break;
                case 'friend':
                default:
                    $navigationView = 'shared/navegation_friend';  // If role is 'friend' or any other value, use 'navegation_friend'
                    break;
            }
        }

        // Pass data to the view
        return view('shared/header', [
            'uploads_profile' => base_url('uploads_profile/')
        ]) . 
        view($navigationView, [  // Load navigation view based on the role
            'profilePic' => $profileImage,
            'uploads_profile' => base_url('uploads_profile/')
        ]) . 
        view('user/Profile', [
            'userData' => $userData,
            'uploads_profile' => base_url('uploads_profile/'),
            'profileImage' => $profileImage, // Correct variable name
        ]);
    }
    /**
     * Updates the user's profile
     */
    public function updateProfile()
    {
        $userId = session()->get('Id_User');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'You must log in.');
        }

        $uploads_folder = WRITEPATH . 'uploads_user/';  // Folder path where profile images are uploaded

        // Check if the form was submitted via POST
        if ($this->request->getMethod() === 'post') {
            $updateSuccess = false;

            // Handle profile image upload
            $file = $this->request->getFile('profileImage');
            if ($file && $file->isValid()) {
                $fileName = $userId . '.' . $file->getExtension();  // Set file name with user ID and file extension
                $file->move($uploads_folder, $fileName);  // Move the file to the upload folder

                // Update the Profile_Pic field in the database with the new image
                $this->userModel->update($userId, ['Profile_Pic' => $fileName]);
                session()->set('ProfileImage', $fileName);  // Set the new profile image in session
                $updateSuccess = true;
            }

            // Update other user details
            $newData = [
                'Username' => $this->request->getPost('username'),
                'First_Name' => $this->request->getPost('first_name'),
                'Last_Name1' => $this->request->getPost('last_name1'),
                'Last_Name2' => $this->request->getPost('last_name2'),
                'Email' => $this->request->getPost('email'),
                'Phone' => $this->request->getPost('phone'),
                'Gender' => $this->request->getPost('gender'),
            ];

            // If a new password is provided, hash and update it
            if ($this->request->getPost('password')) {
                $newData['Password'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
            }

            // Update the user data in the database
            if ($this->userModel->update($userId, $newData)) {
                session()->set('Username', $newData['Username']);
                $updateSuccess = true;
            }

            // Redirect with success or error message based on the result
            if ($updateSuccess) {
                return redirect()->route('user/profile/' . $newData['Username'])->with('success', 'Profile updated successfully.');
            } else {
                return redirect()->back()->with('error', 'There was a problem updating the profile.');
            }
        }
    }

    /**
     * Displays the friend page
     */    
    public function indexFriend()
    {
        $purchaseMessage = session()->get('purchase_message');  // Get any purchase message from the session
        if ($purchaseMessage) {
            session()->remove('purchase_message');  // Remove the purchase message after displaying it
        }

        $user       = $this->userModel->where('Username', $_SESSION['username'])->first();  // Get user details from the database
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';  // Get the profile picture or use default

        $trees = $this->treeModel->getAvailableTrees();  // Get available trees for the user
        $session = session();
        $userId = $session->get('user_id');  // Get the user ID from the session
        $cartCount = $this->cartModel->where('User_Id', $userId)->where('Status', 'active')->countAllResults();  // Count the active carts for the user

        // Query to get the active cart items
        $carts = $this->cartModel->getCartDetails($userId);

        // Return the view for the friend page with necessary data
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
}





   


  
