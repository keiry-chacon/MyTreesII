<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\TreeModel; 
use App\Models\CartModel; 

class Cart extends BaseController
{
    private $db;
    private $treeModel;
    private $userModel;
    private $cartModel;

    public function __construct()
    {
        // Connect to the database
        $this->db               = \Config\Database::connect();

        // Load the Models
        $this->treeModel        = model(TreeModel::class); 
        $this->userModel        = model(UserModel::class);
        $this->cartModel    = model(CartModel::class);

    }


    public function removeItem()
    {
        $session = session();
        $userId = $session->get('user_id');
        $treeId = $this->request->getPost('tree_id');


        $this->cartModel->abandonItem($userId, $treeId);
        return redirect()->to('/friend/dashboard');
    }


    
    public function addToCart()
    {
        $session = session();

        // Obtener los datos enviados desde el formulario
        $treeId = $this->request->getPost('tree_id');
        $price = $this->request->getPost('price');
        $userId = $session->get('user_id'); // Asegúrate de que el usuario esté autenticado
        $quantity = 1; // Por defecto 1

        // Validar que el usuario esté autenticado
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'You must be logged in to add items to the cart.');
        }
        $existingCart = $this->cartModel->where([
            'User_Id' => $userId,
            'Tree_Id' => $treeId,
            'Status' => 'active'
        ])->first();

        if ($existingCart) {
            // Si ya existe un carrito, mostrar mensaje de error
            return redirect()->back()->with('error', 'This product is already in your abandoned cart.');
        }
        // Crear los datos para insertar
        $cartData = [
            'User_Id' => $userId,
            'Tree_Id' => $treeId,
            'Quantity' => $quantity,
            'Price' => $price
        ];

        // Usar CartModel para insertar los datos
        if ($this->cartModel->insert($cartData)) {
            return redirect()->to('/friend/dashboard')->with('error', 'You must be logged in to add items to the cart.');
        } else {
            return redirect()->back()->with('error', 'Failed to add product to cart.');
        }
    }
}