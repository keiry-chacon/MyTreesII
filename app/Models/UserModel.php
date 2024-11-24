<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';  
    protected $primaryKey = 'Id_User';  
    protected $allowedFields = ['username', 'password'];  

    /**
     * Method to authenticate a user by username and password.
     *
     * @param string $username The username to authenticate
     * @param string $password The password to verify
     * @return mixed User data if authenticated, null otherwise
     */
    public function authenticate($username, $password)
    {
        // Search for the user by username
        $user = $this->where('Username', $username)->first();
        
        log_message('debug', 'User found: ' . json_encode($user));
        
        // If the user exists and the password is correct, return the user data
        if ($user && password_verify($password, $user['Password'])) {
            return $user;
        }
        
        return null;
    }
}



