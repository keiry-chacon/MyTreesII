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
    

    
    public function getAvailableTrees()
    {
        return $this->select('trees.*, species.Commercial_Name, species.Scientific_Name') 
                ->join('species', 'species.Id_Specie = trees.Specie_Id') 
                ->where('trees.StatusT', 1) 
                ->findAll();
    }
    public function getTreeById($id)
    {
        return $this->select('trees.*, species.Commercial_Name, species.Scientific_Name')
            ->join('species', 'species.Id_Specie = trees.Specie_Id')  // Ajusta el nombre de las columnas según tu esquema
            ->where('trees.Id_Tree', $id)  // Filtra por el ID del árbol
            ->first();  // Obtiene solo el primer resultado (ya que el ID es único)
    }
    public function getTreeDetails($treeId) {
        // Realizar la consulta para obtener los detalles del árbol
        $query = $this->db->get_where('trees', array('Tree_Id' => $treeId));

        // Si la consulta tiene resultados, devolverlos
        if ($query->num_rows() > 0) {
            return $query->row_array(); // Retorna como array
        }

        // Si no se encuentra el árbol, devolver null
        return null;
    }
}
