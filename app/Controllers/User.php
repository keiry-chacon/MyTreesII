<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CountryModel;
use App\Models\ProvinceModel;
use App\Models\DistrictModel;
use CodeIgniter\Router\Router;

class User extends BaseController
{
    private $db;
    private $userModel;
    private $countryModel;
    private $provinceModel;
    private $districtModel;

    public function __construct()
    {
        // Connect to the database
        $this->db               = \Config\Database::connect();

        // Load the Models
        $this->countryModel     = model(CountryModel::class);
        $this->provinceModel    = model(ProvinceModel::class);
        $this->districtModel    = model(DistrictModel::class);
        $this->userModel        = model(UserModel::class);
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
                    return redirect()->to('/operator/dashboard');
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
             throw new \CodeIgniter\Exceptions\PageNotFoundException('Usuario no encontrado.');
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



    


}




   


  
