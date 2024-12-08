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
        return view('shared/header') . view('user/login');
    }


    /**
     * Handle the user login process
    */
    public function login()
    {
        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));

        // Log username for debugging (don't log passwords in production)
        log_message('debug', 'Username: ' . $username);

        if (empty($username) || empty($password)) {
            return redirect()->to('/login')->with('error', 'Por favor, completa todos los campos');
        }

        // Authenticate the user using the UserModel's authenticate method
        $user = $this->userModel->authenticate($username, $password);

        if ($user) {
            // Store session data
            session()->set([
                'username'   => $user['Username'],
                'user_id'   => $user['Id_User'],
                'role_id'    => $user['Role_Id'],
                'isLoggedIn' => true,
                'profile_pic'=> $user['Profile_Pic'],

            ]);
           
            switch ($user['Role_Id']) {
                case '1':
                    return redirect()->to('/adminhome');
                case '2':
                    return redirect()->to('/friend/dashboard');
                case '3':
                    return redirect()->to('/operatorhome');
                default:
                    return redirect()->to('/'); 
            }
        } else {
            // Incorrect login attempt
            return redirect()->to('/login')->with('error', 'Datos incorrectos');
        }
    }


    /**
     * Handle the user LogOut process
    */
    public function logout()
    {
        // Destroy all session variables
        session()->destroy();

        // Redirects to the login page
        return redirect()->to('/login');
    }





    /**
     * Displays the SignUp page
     */
    public function indexSignUp()
    {
        $country = $this->countryModel->findAll();
        $province = $this->provinceModel->findAll();
        $district = $this->districtModel->findAll();

        $data['country'] = $country;
        $data['province'] = $province;        
        $data['district'] = $district;
        return view('shared/header', $data) . view('user/signup', $data);
    }





    /**
     * Add a new user to the database
    */
    public function signup()
    {
        $Role_Id = 2;
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
            'Role_Id'     => $Role_Id,
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

        // Prepare the data to be passed to the view
        $data = [
            'tree' => $tree,
            'error_msg' => session()->get('error')
        ];        

        // Return the full view with header and the update tree form
        return view('shared/header', $data) . view('operator/registerUpdate', $data);
    }


    /**
     * Update a tree with a register
     */
    public function registerUpdate()  //Esta función está en ADmin con UpdateTree
    {
        // Get the form data
        $idTree       = $this->request->getPost('id_tree'); // Tree ID from the POST data
        $size         = $this->request->getPost('size');

        // Validate that the tree ID is valid
        if (!$idTree || !is_numeric($idTree)) {
            log_message('error', 'Invalid or missing tree ID.');
            return redirect()->to('/managefriends')->with('error', 'Invalid tree ID');
        }

        // Load the tree model
        $treeModel          = new TreeModel();


        // Prepare the data for the update
        $data = [];
        if (!empty($size)) {
            $data['Size'] = $size;
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
            $file->move(WRITEPATH . 'uploads_tree', $newName);
            
            // Save the new file name in the record
            $data['Photo_Path'] = $newName;
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

        $result = $this->registerUpdateTree($idTree, $size, $newName ?? null);

        if ($result !== true) {
            // Si hubo un error en la función de inserción, redirigir con error
            return redirect()->back()->withInput()->with('error', $result);
        }
        
        // If everything is correct, redirect to the trees management page with a success message
        return redirect()->to('/managefriends')->with('success', 'Tree updated successfully');
    }


    /**
     * register a tree with a register
     */
    public function registerUpdateTree($idTree, $size, $photoPath = null)
    {
        $treeupdateModel = new TreeUpdateModel();

        // Crear los datos para insertar
        $data = [
            'Id_Tree' => $idTree, // Asegúrate de que este campo exista en tu tabla
            'Size' => $size
        ];

        // Si hay una foto, agregarla a los datos
        if (!empty($photoPath)) {
            $data['Photo_Path'] = $photoPath;
        }

        // Intentar insertar el nuevo registro
        if (!$treeupdateModel->insert($data)) {
            // Si falla, obtener los errores del modelo
            $errors = $treeupdateModel->errors();
            log_message('error', 'Errors trying to insert tree in TreeUpdateModel: ' . implode(', ', $errors));
            return implode(', ', $errors);
        }

        // Si todo es correcto, devolver true
        return true;
    }

    






    /**
     * Displays the Operator page
    */  
    public function indexOperatorHome()
    {
        $username   = session()->get('username');
        $user       = $this->userModel->where('Username', $username)->first();
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        return view('shared/header', [
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('shared/navegation_operator', [
            'profilePic'        => $profilePic,
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('operator/operatorHome', [
        ]);
    }





    /**
     * Displays the profile page
     */

     public function profile()
     {
         // Verificar si el nombre de usuario existe en la sesión
         if (!isset($_SESSION['username'])) {
             return redirect()->back()->with('error', 'Usuario no especificado.');
         }
     
         // Obtener los datos del usuario de la base de datos
         $userData = $this->userModel->where('Username', $_SESSION['username'])->first();
     
         // Verificar si el usuario existe
         if (!$userData) {
             throw new \CodeIgniter\Exceptions\PageNotFoundException('User not founded.');
         }
     
         // Verificar si el perfil tiene imagen, si no asignar una imagen por defecto
         $profileImage = $userData['Profile_Pic'] ?? 'default_profile.jpg';
     
         // Determinar la vista de navegación según el rol
         $navigationView = 'shared/navegation_friend';  // Valor por defecto
         if (isset($userData['Role_Id'])) {
             switch ($userData['Role_Id']) {
                 case 'admin':
                     $navigationView = 'shared/navegation_admin';  // Si el rol es 'admin', carga 'navegation_admin'
                     break;
                 case 'operator':
                     $navigationView = 'shared/navegation_operator';  // Si el rol es 'operator', carga 'navegation_operator'
                     break;
                 case 'friend':
                 default:
                     $navigationView = 'shared/navegation_friend';  // Si el rol es 'friend' o cualquier otro valor, carga 'navegation_friend'
                     break;
             }
         }
     
         // Pasar los datos a la vista
         return view('shared/header', [
             'uploads_profile' => base_url('uploads_profile/')
         ]) . 
         view($navigationView, [  // Cargar la vista de navegación según el rol
             'profilePic' => $profileImage,
             'uploads_profile' => base_url('uploads_profile/')
         ]) . 
         view('user/Profile', [
             'userData' => $userData,
             'uploads_profile' => base_url('uploads_profile/'),
             'profileImage' => $profileImage, // Corregir nombre de variable
         ]);
     }
     
    

    public function updateProfile()
    {
        $userId = session()->get('Id_User');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Debe iniciar sesión.');
        }

        $uploads_folder = WRITEPATH . 'uploads_user/';

        if ($this->request->getMethod() === 'post') {
            $updateSuccess = false;

            // Manejo de la imagen de perfil
            $file = $this->request->getFile('profileImage');
            if ($file && $file->isValid()) {
                $fileName = $userId . '.' . $file->getExtension();
                $file->move($uploads_folder, $fileName);

                // Actualizar el campo Profile_Pic en la base de datos
                $this->userModel->update($userId, ['Profile_Pic' => $fileName]);
                session()->set('ProfileImage', $fileName);
                $updateSuccess = true;
            }

            // Actualizar otros datos del usuario
            $newData = [
                'Username' => $this->request->getPost('username'),
                'First_Name' => $this->request->getPost('first_name'),
                'Last_Name1' => $this->request->getPost('last_name1'),
                'Last_Name2' => $this->request->getPost('last_name2'),
                'Email' => $this->request->getPost('email'),
                'Phone' => $this->request->getPost('phone'),
                'Gender' => $this->request->getPost('gender'),
            ];

            if ($this->request->getPost('password')) {
                $newData['Password'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
            }

            if ($this->userModel->update($userId, $newData)) {
                session()->set('Username', $newData['Username']);
                $updateSuccess = true;
            }

            if ($updateSuccess) {
                return redirect()->route('user/profile/' . $newData['Username'])->with('success', 'Perfil actualizado con éxito.');
            } else {
                return redirect()->back()->with('error', 'Hubo un problema al actualizar el perfil.');
            }
        }
    }

/**
     * Displays the friend page
    */    
    public function indexFriend()
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

    


}





   


  
