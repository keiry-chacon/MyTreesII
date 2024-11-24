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
                'profile_pic'    => $user['Profile_Pic'],

            ]);
           


            switch ($user['Role_Id']) {
                case '1':
                    return redirect()->to('/admin/dashboard');
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
    public function logout()
    {
        // Destruir todas las variables de sesión
        session()->destroy();

        // Redirigir a la página de login (o la página que desees)
        return redirect()->to('/login');
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




   


  
