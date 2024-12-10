<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'cart'; 
    protected $primaryKey = 'Id_Cart';
    protected $allowedFields = ['Id_Cart', 'User_Id', 'Tree_Id', 'Quantity', 'Status', 'Created_At', 'Price']; 


    
    public function getCartByUserId($user_id)
    {
        return $this->where('User_Id', $user_id)  
                    ->where('Status', 'active')  
                    ->findAll();  
    }
    


    // In your model, for example CartModel.php
    public function getCartDetails($userId) {
        return $this->db->table('cart')
            ->select('cart.*, trees.photo_path, species.scientific_name, species.commercial_name')
            ->join('trees', 'trees.Id_Tree = cart.Tree_Id', 'left') // Join the 'trees' table on the Tree_Id
            ->join('species', 'species.Id_Specie = trees.Specie_Id', 'left') // Join the 'species' table on the Specie_Id
            ->where('cart.User_Id', $userId) // Filter by the current user's ID
            ->where('cart.Status', 'active') // Only include items with 'active' status
            ->get()
            ->getResultArray(); // This returns the results as an array
    }



    // Model CartModel.php
    public function abandonItem($userId, $treeId)
    {
        // Change the status to 'abandoned' where the user_id and tree_id match
        return $this->db->table('cart')
            ->set('Status', 'abandoned') // Update the status to 'abandoned'
            ->where('User_Id', $userId) // Only for the current user's cart
            ->where('Tree_Id', $treeId) // Only for the specific tree
            ->update();
    }
}