<?php

namespace App\Models;

use CodeIgniter\Model;

class SpeciesModel extends Model
{
    protected $table            = 'species';  
    protected $primaryKey       = 'Id_Specie';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;  
    protected $allowedFields = [
        'Commercial_Name',
        'Scientific_Name',
        'StatusS'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Validation
    protected $validationRules = [
        'Commercial_Name'  => 'required|min_length[1]|is_unique[species.Commercial_Name]',
        'Scientific_Name'  => 'required|min_length[1]|is_unique[species.Scientific_Name]'
    ];

    protected $validationMessages = [
        'Commercial_Name' => [
            'is_unique' => 'Commercial Name is already registered.',
        ],
        'Scientific_Name' => [
            'is_unique' => 'Scientific Name is already registered.',
        ]
    ];

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



    public function getSpeciesByStatus($status = 1)
    {
        return $this->where('StatusS', $status)->findAll();  // Filter species whose status is equal to 1
    }
}
