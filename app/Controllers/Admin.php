<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SpeciesModel;


class Admin extends BaseController
{
    private $db;
    private $userModel;
    private $speciesModel;

    public function __construct()
    {
        // Connect to the database
        $this->db               = \Config\Database::connect();
        
        // Load the Models
        $this->userModel        = model(UserModel::class);
        $this->speciesModel     = model(SpeciesModel::class);
    }





    /**
     * Displays the Admin page
    */    
    public function indexHome()
    {
        $username   = session()->get('username');
        $user       = $this->userModel->where('Username', $username)->first();
        $profilePic = $user['Profile_Pic'] ?? 'default_profile.jpg';

        return view('shared/header', [
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('shared/navegation_admin', [
            'profilePic'        => $profilePic,
            'uploads_profile'   => base_url('uploads_profile/')
        ]) . 
        view('admin/adminHome', [
        ]);
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
     * Displays the list of registered species
    */
    public function indexManageSpecies()
    {
        $species = $this->speciesModel->getSpeciesByStatus(1);

        $data['species'] = $species;
        return view('shared/header', $data) . view('admin/manageSpecies', $data);
    }



    

    /**
     * Displays the interface of the add species section
    */
    public function indexAddSpecies()
    {
        return view('shared/header') . view('admin/addSpecies');
    }


    /**
     * Add a new species to the database
    */
    public function addspecies()
    {
        // Data received from the form
        $data = [
            'Commercial_Name'  => $this->request->getPost('commercial_name'),
            'Scientific_Name'  => $this->request->getPost('scientific_name'),
        ];
    
        $speciesModel = new SpeciesModel();
    
        // Validation and saving
        if (!$speciesModel->save($data)) {
            // Get validation errors from the model
            $errors = $speciesModel->errors();
    
            // Redirect back to the form with errors
            return redirect()->back()->withInput()->with('error', $errors);
        }
    
        // If everything is correct, redirect to the login page with a success message
        return redirect()->to('/managespecies')->with('success', 'Species Added Succesfully');
    }






    /**
     * Displays the interface of the update species section
    */
    public function indexUpdateSpecies()
    {
        // Get the species ID from the URL (GET parameter)
        $idSpecie = $this->request->getGet('id_specie');
        
        // Validate that the ID is valid
        if (!$idSpecie || !is_numeric($idSpecie)) {
            return redirect()->to('/managespecies')->with('error', 'Invalid species ID');
        }

        // Create the species model
        $speciesModel = new SpeciesModel();

        // Search for the species by ID
        $specie = $speciesModel->find($idSpecie);

        // Check if the species exists
        if (!$specie) {
            return redirect()->to('/managespecies')->with('error', 'Species not found');
        }

        // Pass the species data and any error (if any) to the view
        $error_msg = session()->get('error');  // Get the error, if it exists
        return view('shared/header') . view('admin/updateSpecies', ['specie' => $specie, 'error_msg' => $error_msg]);
    }


    /**
     * Update an existing species to the database
    */
    public function updateSpecies()
    {
        // Get the form data
        $idSpecie       = $this->request->getPost('id_specie'); // Species ID from the URL
        $commercialName = $this->request->getPost('commercial_name');
        $scientificName = $this->request->getPost('scientific_name');

        // Validate that the species ID is valid
        if (!$idSpecie || !is_numeric($idSpecie)) {
            log_message('error', 'Invalid or missing species ID.');
            return redirect()->to('/managespecies')->with('error', 'Invalid species ID');
        }
        
        // Load the species model
        $speciesModel = new SpeciesModel();

        // Prepare the data for the update
        $data = [];
        if (!empty($commercialName)) {
            $data['Commercial_Name'] = $commercialName;
        }
        if (!empty($scientificName)) {
            $data['Scientific_Name'] = $scientificName;
        }

        // Check if there are any fields to update
        if (empty($data)) {
            return redirect()->back()->with('error', 'No fields to update.');
        }

        // Conditional validation: Disable uniqueness validation only for unchanged fields
        if (!empty($commercialName)) {
            // Validate only the commercial name if it is being updated
            $speciesModel->setValidationRules([
                'Commercial_Name' => 'required|min_length[1]|is_unique[species.Commercial_Name,Id_Specie,' . $idSpecie . ']',
                'Scientific_Name' => 'permit_empty', // Allow no change without validation
            ]);
        } elseif (!empty($scientificName)) {
            // Validate only the scientific name if it is being updated
            $speciesModel->setValidationRules([
                'Commercial_Name' => 'permit_empty',
                'Scientific_Name' => 'required|min_length[1]|is_unique[species.Scientific_Name,Id_Specie,' . $idSpecie . ']',
            ]);
        }

        // Try to update the species
        if (!$speciesModel->update($idSpecie, $data)) {
            // Get errors from the model
            $errors = $speciesModel->errors();
            log_message('error', 'Errors trying to update: ' . implode(', ', $errors));

            // Redirect with errors if the update fails
            return redirect()->back()->withInput()->with('error', implode(', ', $errors));
        }

        // If everything is correct, redirect to the species management page with a success message
        return redirect()->to('/managespecies')->with('success', 'Species updated successfully');
    }


    


    /**
     * Add a new species to the database
    */
    public function deletespecies()
    {
        // Id_Specie received from the form
        $idSpecie       = $this->request->getPost('id_specie');
    
        $speciesModel   = new SpeciesModel();
    
        // Validation and saving
        if (!$speciesModel->update($idSpecie, ['StatusS' => 0])) {
            // Get validation errors from the model
            $errors = $speciesModel->errors();
    
            // Redirect back to the form with errors
            return redirect()->back()->withInput()->with('error', $errors);
        }
    
        // If everything is correct, redirect to the login page with a success message
        return redirect()->to('/managespecies')->with('success', 'Species Added Succesfully');
    }





}