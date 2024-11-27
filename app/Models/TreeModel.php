<?php

namespace App\Models;

use CodeIgniter\Model;

class TreeModel extends Model
{
    protected $table = 'trees'; 
    protected $primaryKey = 'Id_Tree';
    protected $allowedFields = ['Specie_Id', 'Location', 'Size', 'StatusT', 'Price', 'Photo_Path']; 
    public function getAvailableTrees()
    {
        return $this->select('trees.*, species.Commercial_Name, species.Scientific_Name') 
                ->join('species', 'species.Id_Specie = trees.Specie_Id') 
                ->where('trees.StatusT', 1) 
                ->findAll();
    }
}
