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
        return $this->db->table('tree_update') // Selecciona la tabla tree_update como base
                    ->select('tree_update.*, trees.Specie_Id, species.Commercial_Name, species.Scientific_Name')
                    ->join('trees', 'trees.Id_Tree = tree_update.Tree_Id') // Relación entre tree_update y trees
                    ->join('species', 'species.Id_Specie = trees.Specie_Id') // Relación entre trees y species
                    ->where('tree_update.Tree_Id', $id_tree) // Filtro basado en Tree_Id
                    ->get()
                    ->getResultArray(); // Devuelve los resultados como un arreglo asociativo
    }



}
