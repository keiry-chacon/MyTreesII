<?php

namespace App\Models;

use CodeIgniter\Model;

class TreeUpdateModel extends Model
{
    protected $table            = 'tree_update';
    protected $primaryKey       = 'Id_TreeUpdate';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['Tree_Id', 'Size', 'Photo_Path']; 

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

    

    public function getTreeUpdatesWithSpecies($id_tree)
    {
        // Retrieve updates for a specific tree along with its species information
        return $this->db->table('tree_update') // Select the 'tree_update' table as the base
                    ->select('tree_update.*, trees.Specie_Id, tree_update.Photo_Path, species.Commercial_Name, species.Scientific_Name')
                    ->join('trees', 'trees.Id_Tree = tree_update.Tree_Id') // Relationship between 'tree_update' and 'trees'
                    ->join('species', 'species.Id_Specie = trees.Specie_Id') // Relationship between 'trees' and 'species'
                    ->where('tree_update.Tree_Id', $id_tree) // Filter based on Tree_Id
                    ->get()
                    ->getResultArray(); // Return the results as an associative array
    }
}
