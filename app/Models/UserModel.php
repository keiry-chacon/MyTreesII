<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    // Specifies the table associated with this model
    protected $table = 'users';  
    
    // Defines the primary key for the table
    protected $primaryKey = 'Id_User';
    
    // Specifies the return type of queries (array in this case)
    protected $returnType = 'array';
    
    // Disables the use of soft deletes for this model
    protected $useSoftDeletes = false;
    
    // Protects fields from mass assignment
    protected $protectFields = true;  
    
    // Defines the fields that can be inserted or updated
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

    // Prevents inserting empty fields and ensures only changed fields are updated
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Casts fields to specific data types (empty in this case)
    protected array $casts = [];
    protected array $castHandlers = [];

    // Validation rules for each field
    protected $validationRules = [
        'First_Name'  => 'required|min_length[1]',
        'Last_Name1'  => 'required|min_length[1]',
        'Last_Name2'  => 'required|min_length[1]',
        'Username'    => 'required|alpha_numeric|min_length[1]|is_unique[users.Username]',
        'Password'    => 'required|min_length[1]',
        'Email'       => 'required|valid_email|is_unique[users.Email]', // Ensures the email is unique
        'Phone'       => 'required|numeric|min_length[1]',
        'Gender'      => 'required',
    ];

    // Custom validation messages for specific fields
    protected $validationMessages = [
        'Email' => [
            'is_unique' => 'Email is already registered.',
        ],
        'Username' => [
            'is_unique' => 'The username is already registered.',
        ]
    ];

    // Specifies whether to skip validation (set to false, meaning validation is applied)
    protected $skipValidation = false;
    
    // Cleans validation rules before applying them (set to true)
    protected $cleanValidationRules = true;

    // Callbacks for different actions in the model (currently not used)
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
        
        // Log the user data for debugging purposes
        log_message('debug', 'User found: ' . json_encode($user));
        
        // If the user exists and the password matches, return the user data
        if ($user && password_verify($password, $user['Password'])) {
            return $user;
        }
        
        // Return null if authentication fails
        return null;
    }



    /**
     * Method to get all users with the role of "friends".
     *
     * @return array List of users with role "friends"
     */
    public function getAvailableUsers()
    {
        return $this->where('Role_Id', 2)
                    ->findAll();
    }




    /**
     * Method to get the count of users by gender for active friends.
     *
     * @return array An array with gender counts (F, M, O).
     */
    public function getFriends(): array
    {
        // Initialize gender count array
        $genderCounts = [
            'F' => 0,
            'M' => 0,
            'O' => 0
        ];

        // Instance of the model
        $userModel = new \App\Models\UserModel();

        // Query the database to count users by gender who are active and have the "friends" role
        $query = $userModel->select('Gender, COUNT(*) as count')
            ->where('StatusU', 1) // Filters for active users
            ->where('Role_Id', 2) // Filters for users with the "friends" role
            ->groupBy('Gender')   // Groups the results by gender
            ->get();

        // Process the query results
        $results = $query->getResultArray();
        foreach ($results as $row) {
            $gender = $row['Gender'];
            $count = $row['count'];

            // Update the gender count if the gender exists in the genderCounts array
            if (isset($genderCounts[$gender])) {
                $genderCounts[$gender] = $count;
            }
        }

        // Return the gender count array
        return $genderCounts;
    }
}




