<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class User extends BaseController
{
    private $db;
    private $userModel;

    public function __construct()
    {
        // Connect to the database
        $this->db = \Config\Database::connect();
        
        // Load the UserModel
        $this->userModel = model(UserModel::class);
    }

    /**
     * Handle the user login process
     */
    public function login()
    {
        // If it's a POST request
        if ($this->request->getMethod() === 'post') {
            $username = trim($this->request->getPost('username'));
            $password = trim($this->request->getPost('password'));

            // Log username and password for debugging (do not log passwords in production)
            log_message('debug', 'Username: ' . $username);
            log_message('debug', 'Password: ' . $password);
            
            if (empty($username) || empty($password)) {
                return redirect()->to('/login')->with('error', 'Por favor, completa todos los campos');
            }

            // Hash the password (typically not needed for verification since it’s already hashed in the model)
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Authenticate the user using the UserModel's authenticate method
            $user = $this->userModel->authenticate($username, $hashedPassword);

            if ($user) {
                $newUsername = 'keiry';
                
                $builder = $this->db->table('users');  
                $builder->set('Username', $newUsername);
                $builder->where('Username', $username);  // Original username
                $builder->update();
                
                session()->set([
                    'username'   => $user['Username'],
                    'role_id'    => $user['Role_Id'],
                    'isLoggedIn' => true,
                ]);

                return redirect()->to(base_url('/signup'));

            } else {
                return redirect()->to('/login')->with('error', 'Credenciales no válidas');
            }
        }

        // Display the login page with any error messages
        $data['error'] = session('error');
        return view('shared/header', $data)
            . view('user/login', $data);
    }

    /**
     * Display the signup page
     */
    public function signup()
    {
        // Pass any error messages to the view
        $data['error'] = session('error');
        
        return view('shared/header', $data)
            . view('user/signup', $data);
    }
}

   


  
