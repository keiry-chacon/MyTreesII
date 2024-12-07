<?php

namespace App\Controllers;

use App\Models\PurchaseModel;
use App\Models\TreeModel; 
use CodeIgniter\Controller;

class Purchase extends BaseController
{
    // Cargar el servicio de sesión

    public function processPurchase()
    {
     $validation = \Config\Services::validation();

        $rules = [
            'shipping_location' => 'required|min_length[5]',
            'payment_method' => 'required',
            'tree_id' => 'required|is_natural_no_zero'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Obtener los datos del formulario
        $treeId = $this->request->getPost('tree_id');
        $shippingLocation = $this->request->getPost('shipping_location');
        $paymentMethod = $this->request->getPost('payment_method');

        // Preparar los datos para insertar en la base de datos
        $data = [
            'Tree_Id' => $treeId,  // Cambiado a mayúsculas
            'User_Id' => session()->get('user_id'),  // Cambiado a mayúsculas
            'Shipping_Location' => $shippingLocation,  // Cambiado a mayúsculas
            'Payment_Method' => $paymentMethod,  // Cambiado a mayúsculas
            'Purchase_Date' => date('Y-m-d H:i:s'),
        ];
        

        // Cargar el modelo de compra
        $purchaseModel = new PurchaseModel();
        
        // Insertar en la base de datos
        $purchaseModel->save($data);
        $treeModel = new TreeModel();
        $treeModel->update($treeId, ['StatusT' => '0']);
        // Redirigir al usuario con un mensaje de éxito
        return redirect()->to('purchase/success');
    }
    public function success()
{
    $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

    return view('friend/success');
}

}
