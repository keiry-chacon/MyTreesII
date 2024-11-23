<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'Id_User';
    protected $allowedFields = ['username', 'password'];

    /**
     * Method to authenticate a user by username and password
     *
     * @param string $username The username of the user to authenticate
     * @param string $password The password to verify
     * @return mixed The user object if authenticated, null if not
     */
    public function authenticate($username, $password)
    {
        $user = $this->where('Username', $username)->first();
        
        log_message('debug', 'Usuario encontrado: ' . json_encode($user));
        
        // If user exists and the password is correct, return the user data
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return null;
    }
}


