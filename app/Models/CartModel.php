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
    
    // En tu modelo, por ejemplo CartModel.php
public function getCartDetails($userId) {
    return $this->db->table('cart')
        ->select('cart.*, trees.photo_path, species.scientific_name, species.commercial_name')
        ->join('trees', 'trees.Id_Tree = cart.Tree_Id', 'left')
        ->join('species', 'species.Id_Specie = trees.Specie_Id', 'left')
        ->where('cart.User_Id', $userId)
        ->where('cart.Status', 'active')
        ->get()
        ->getResultArray(); // Esto devuelve los resultados como un array
}
// Modelo Cart_model.php
public function abandonItem($userId, $treeId)
{
    // Cambiar el estado a 'abandoned' donde el user_id y tree_id coinciden
    return $this->db->table('cart')
        ->set('Status', 'abandoned')
        ->where('User_Id', $userId)   // Solo el carrito del usuario actual
        ->where('Tree_Id', $treeId)   // Solo el árbol específico
        ->update();
}



}