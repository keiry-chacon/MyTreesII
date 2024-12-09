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

    public function getProvinces()
    {
        $countryId = $this->request->getPost('country'); // Asegúrate de que el nombre sea 'country_id'
        
        $provinces = $this->provinceModel->where('Country_Id', $countryId)->findAll();

        $options = '<option value="">Select Province</option>';
        foreach ($provinces as $province) {
            $options .= '<option value="' . $province['Id_Province'] . '">' . $province['Provinc_Name'] . '</option>';
        }

        return $this->response->setJSON($options);
    }
}

