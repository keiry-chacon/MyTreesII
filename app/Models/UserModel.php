<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    $this->db = \Config\Database::connect();
    $this->userModel = model(UserModel::class);
    protected $table      = 'users';
    protected $primaryKey = 'Id_User';

    // Autenticación del usuario
    public function authenticate($username, $password)
    {
        $user = $this->where('username', $username)->first();

        // Verificar contraseña (asumiendo contraseñas cifradas con password_hash)
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }
}
