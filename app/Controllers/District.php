<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DistrictModel;

class District extends BaseController
{
    private $districtModel;

    public function __construct()
    {
        $this->districtModel = model(DistrictModel::class);
    }

    public function getDistricts()
    {
        $provinceId = $this->request->getPost('province'); // Asegúrate de que el nombre sea 'province_id'
        $districts = $this->districtModel->where('Province_Id', $provinceId)->findAll();

        $options = '<option value="">Select District</option>';
        foreach ($districts as $district) {
            $options .= '<option value="' . $district['ID_District'] . '">' . $district['District_Name'] . '</option>';
        }

        return $this->response->setJSON($options);
    }
}

