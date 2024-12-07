<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'cart'; 
    protected $primaryKey = 'Id_Cart';
    protected $allowedFields = ['Tree_Id', 'User_Id', 'Shipping_Location', 'Payment_Method', 'Purchase_Date', 'StatusP']; 

    public function getCartByUserId($user_id)
    {
        return $this->select('purchase.*, species.Commercial_Name, species.Scientific_Name, trees.Photo_Path, trees.Location')
                    ->join('trees', 'trees.Id_Tree = purchase.Tree_Id') 
                    ->join('species', 'species.Id_Specie = trees.Specie_Id')  // Assuming the relationship is through Species_Id
                    ->where('purchase.User_Id', $user_id)
                    ->where('purchase.StatusP', 1) 
                    ->findAll();
    }

}