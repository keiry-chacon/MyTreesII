<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';  
    protected $primaryKey       = 'Id_User';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'First_Name',
        'Last_Name1',
        'Last_Name2',
        'Username',
        'Password',
        'Email',
        'Phone',
        'Gender',
        'Profile_Pic',
        'District_Id',
        'Role_Id'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Validation
    protected $validationRules = [
        'First_Name'  => 'required|min_length[1]',
        'Last_Name1'  => 'required|min_length[1]',
        'Last_Name2'  => 'required|min_length[1]',
        'Username'    => 'required|alpha_numeric|min_length[1]|is_unique[users.Username]',
        'Password'    => 'required|min_length[1]',
        'Email'       => 'required|valid_email|is_unique[users.Email]', // Validación de correo único
        'Phone'       => 'required|numeric|min_length[1]',
        'Gender'      => 'required',
    ];

    protected $validationMessages = [
        'Email' => [
            'is_unique' => 'Email is already registered.',
        ],
        'Username' => [
            'is_unique' => 'The username is already registered.',
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

/**
 * Method to authenticate a user with a username and password.
 *
 * @param string $username The username to authenticate.
 * @param string $password The password to verify.
 * @return mixed The user data if authenticated successfully, null otherwise.
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


    
    public function getAvailableUsers()
    {
        return $this->where('Role_Id', 2)
                    ->findAll();
    }


}



