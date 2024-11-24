<?php

namespace App\Controllers;

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

    public function index()
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
                'role_id'    => $user['Role_Id'],
                'isLoggedIn' => true,
            ]);

            // Redirect to a dashboard or home page, not the login page
            return redirect()->to('/login')->with('success', 'Login exitoso');
        } else {
            // Incorrect login attempt
            return redirect()->to('/login')->with('error', 'Datos incorrectos');
        }
    }
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

   


  
