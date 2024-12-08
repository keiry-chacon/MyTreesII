<?php

namespace App\Controllers;

use App\Models\PurchaseModel;
use App\Models\TreeModel; 
use CodeIgniter\Controller;
use App\Models\CartModel; 


class Purchase extends BaseController
{
    // Cargar el servicio de sesión
    protected $cartModel;
    protected $purchaseModel;

    // Constructor: cargar los modelos
    public function __construct()
    {
        // Cargar el CartModel
        $this->cartModel = new CartModel();
        
        // Cargar el PurchaseModel
        $this->purchaseModel = new PurchaseModel();
    }
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
public function buyAllItems()
{
    $session = session();
    $userId = $session->get('user_id');

    // Obtener todos los árboles del carrito del usuario
    $cartItems = $this->cartModel->getCartByUserId($userId);

    if (empty($cartItems)) {
        // Si el carrito está vacío, redirigir con un mensaje de error
        return redirect()->to('/friend/dashboard')->with('error', 'No items in the cart.');
    }

    // Obtener la ubicación de envío y el método de pago desde el formulario
    $shippingLocation = $this->request->getPost('shipping_location');
    $paymentMethod = $this->request->getPost('payment_method');

    // Verificar si la ubicación de envío y el método de pago están definidos
    if (!$shippingLocation || !$paymentMethod) {
        return redirect()->back()->with('error', 'Shipping location and payment method are required.');
    }

    // Procesar la compra para cada artículo en el carrito
    foreach ($cartItems as $item) {
        $data = [
            'Tree_Id' => $item['Tree_Id'],  
            'User_Id' => $userId, 
            'Shipping_Location' => $shippingLocation,  
            'Payment_Method' => $paymentMethod,  
        ];
        
        // Inserta la compra en la tabla de compras
        $this->purchaseModel->insertPurchase($data);

        // Eliminar los elementos del carrito después de la compra
        $this->cartModel->abandonItem($userId, $item['Tree_Id']);
    }

    // Redirigir al usuario a la página de dashboard después de la compra
    return redirect()->to('/purchase/success')->with('success', 'Purchase successful!');
}


}
