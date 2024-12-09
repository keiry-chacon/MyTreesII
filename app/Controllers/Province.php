<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProvinceModel;

class Province extends BaseController
{
    private $provinceModel;

    public function __construct()
    {
        $this->provinceModel = model(ProvinceModel::class);
    }

    
}

