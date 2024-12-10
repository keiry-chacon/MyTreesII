<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseModel extends Model
{
    protected $table = 'purchase'; 
    protected $primaryKey = 'Id_Purchase';
    protected $allowedFields = ['Tree_Id', 'User_Id', 'Shipping_Location', 'Payment_Method', 'Purchase_Date', 'StatusP']; 



    public function getFriendsTrees($user_id)
    {
        // Retrieve the trees purchased by a user's friends
        return $this->select('purchase.*, species.Commercial_Name, species.Scientific_Name, trees.Photo_Path, trees.Location')
                    ->join('trees', 'trees.Id_Tree = purchase.Tree_Id') // Join the 'trees' table using Tree_Id
                    ->join('species', 'species.Id_Specie = trees.Specie_Id') // Join the 'species' table using Specie_Id
                    ->where('purchase.User_Id', $user_id) // Filter by the current user's ID
                    ->where('purchase.StatusP', 1) // Only include purchases with status 1 (active or valid)
                    ->findAll(); // Retrieve all matching records
    }
    


    public function getPurchaseByUserId($userId)
    {
        // Retrieve all purchases made by a specific user
        return $this->where('User_Id', $userId) // Filter by the user's ID
                    ->orderBy('Purchase_Date', 'DESC') // Order by purchase date (most recent first)
                    ->findAll(); // Retrieve all purchases for the user
    }
    

    
    public function insertPurchase($data)
    {
        // Insert a new purchase into the 'purchase' table
        return $this->db->table('purchase')->insert($data); // Add the provided data as a new purchase record
    }    
}
