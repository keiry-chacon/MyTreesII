<?php

namespace App\Controllers;

use App\Models\UserModel;

class Friend extends BaseController
{
    private $db;
    private $userModel;

    public function __construct()
    {
        // Connect to the database
        $this->db = \Config\Database::connect();
        // Load the UserModel
        $this->userModel = model(UserModel::class);
    }

    public function index()
    {
        return view('shared/header') . view('friend/dashboard');
    }
}