<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class User extends Controller
{
    public function login()
    {
        // Si se realiza una petición POST
        if ($this->request->getMethod() === 'post') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            // Verifica el usuario
            $model = new UserModel();
            $user = $model->authenticate($username, $password);

            if ($user) {
                // Crea la sesión
                session()->set([
                    'username' => $user['Username'],
                    'role_id'  => $user['Role_Id'],
                    'isLoggedIn' => true
                ]);

                // Redirigir según el rol
                if ($user['Role_Id'] == 1) {
                    return redirect()->to('/admin/dashboard');
                } else if ($user['Role_Id'] == 2) {
                    return redirect()->to('/friend/home');
                }
            } else {
                return redirect()->to('/login')->with('error', 'Credenciales no válidas');
            }
        }
        return view('shared/header', ['error' => session('error')]).view('user/login', ['error' => session('error')]);
    }
}

