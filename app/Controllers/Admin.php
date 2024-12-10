<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SpeciesModel;
use App\Models\TreeModel;
use App\Models\CountryModel;
use App\Models\ProvinceModel;
use App\Models\DistrictModel;


class Admin extends BaseController
{
    private $db;
    private $userModel;
    private $speciesModel;
    private $treeModel;
    private $countryModel;
    private $provinceModel;
    private $districtModel;

    public function __construct()
    {
        // Connect to the database
        $this->db               = \Config\Database::connect();
        
        // Load the Models
        $this->userModel        = model(UserModel::class);
        $this->speciesModel     = model(SpeciesModel::class);
        $this->treeModel        = model(TreeModel::class);
        $this->countryModel     = model(CountryModel::class);
        $this->provinceModel    = model(ProvinceModel::class);
        $this->districtModel    = model(DistrictModel::class);
    }









    /**
     * Displays the profile page
    */
    public function Profile()
    {
        // Get the user ID from the session
        $username = session()->get('username');

        if (!$username) {
            // Redirect to the login page if the user is not logged in
            return redirect()->to('/login');
        }

        // Fetch user data from the database
        $user = $this->userModel->where('Username', $username)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'User not found');
        }

        // Pass the user data to the view
        return view('shared/header', [
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('shared/navegation_friend', [
            'profilePic'        => $user['Profile_Pic'],
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('user/profile', [
            'user' => $user
        ]);
    }





    





    





    /**
     * Displays the list of registered friends
     */
    public function indexManageFriends()
    {
        // Obtener la lista de usuarios disponibles
        $users = $this->userModel->getAvailableUsers();

        // Preparar los datos para pasar a las vistas
        $data['users'] = $users;
        $data['uploads_folder'] = base_url('uploads_profile/'); // Correcta llamada a base_url()

        // Renderizar ambas vistas correctamente
        return view('shared/header', $data) . view('admin/manageFriends', $data);
    }






    





    





    

}