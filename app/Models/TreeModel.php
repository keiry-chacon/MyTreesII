<?php

namespace App\Models;

use CodeIgniter\Model;

class TreeModel extends Model
{
    protected $table            = 'trees';
    protected $primaryKey       = 'Id_Tree';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['Specie_Id', 'Location', 'Size', 'StatusT', 'Price', 'Photo_Path']; 

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
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
    


    public function getAvailableTreesCount(): int
    {
        // Count the number of trees that are available (StatusT = 1)
        return $this->where('StatusT', 1)
                    ->countAllResults();
    }
    

    
    public function getSoldTreesCount()
    {
        // Get the count of trees that have been sold (StatusT = 0)
        
        // Instantiate the TreeModel
        $treeModel = new \App\Models\TreeModel();
    
        // Query the model to count the sold trees
        $soldTreesCount = $treeModel
            ->where('StatusT', 0) // Condition for sold trees
            ->countAllResults(); // Count the results
    
        return $soldTreesCount;
    }
    


    public function getAvailableTrees()
    {
        // Retrieve all trees that are available (StatusT = 1) along with their species details
        return $this->select('trees.*, species.Commercial_Name, species.Scientific_Name') // Select tree and species details
                    ->join('species', 'species.Id_Specie = trees.Specie_Id') // Join the 'species' table using Specie_Id
                    ->where('trees.StatusT', 1) // Filter for available trees
                    ->findAll(); // Retrieve all matching records
    }
    


    public function getFriendsTrees($friendId)
    {
        // Retrieve trees purchased by a specific friend
        return $this->db->table('trees as trees_main') // Assign alias to 'trees' table
            ->select(
                'purchase.Id_Purchase, 
                 purchase.Payment_Method, 
                 purchase.Shipping_Location, 
                 purchase.Purchase_Date, 
                 trees_main.Id_Tree, 
                 trees_main.Specie_Id, 
                 species.Commercial_Name,  
                 species.Scientific_Name,  
                 trees_main.Location, 
                 purchase.StatusP, 
                 trees_main.Price, 
                 trees_main.Photo_Path'
            )
            ->join('purchase', 'trees_main.Id_Tree = purchase.Tree_Id') // Join 'purchase' table using Tree_Id
            ->join('species', 'species.Id_Specie = trees_main.Specie_Id') // Join 'species' table using Specie_Id
            ->where('purchase.User_Id', $friendId) // Filter by friend's ID
            ->where('purchase.StatusP', 1) // Only include active purchases
            ->distinct() // Remove duplicates
            ->get()
            ->getResultArray(); // Return the results as an array
    }
    


    public function getTreeById($id)
    {
        // Retrieve the details of a specific tree by its ID
        return $this->select('trees.*, species.Commercial_Name, species.Scientific_Name') // Select tree and species details
            ->join('species', 'species.Id_Specie = trees.Specie_Id') // Join 'species' table using Specie_Id
            ->where('trees.Id_Tree', $id) // Filter by tree ID
            ->first(); // Retrieve only the first result (ID is unique)
    }
    


    public function getTreeDetails($treeId)
    {
        // Query to get the details of a specific tree by its ID
        $query = $this->db->get_where('trees', array('Tree_Id' => $treeId)); // Query with condition
    
        // If the query returns results, return them
        if ($query->num_rows() > 0) {
            return $query->row_array(); // Return the results as an array
        }
    
        // If the tree is not found, return null
        return null;
    }
}
