<?php
namespace App\Controllers;


class Error extends BaseController
{
    public function unauthorized()
    {
        return view('errors/unauthorized');
    }
public function redirectDashboard()
{
    $role = session()->get('role_id');  
    
    switch ($role) {
        case 1:
            return redirect()->to('/admin/dashboard');
        case 2:
            return redirect()->to('/friend/dashboard');
        case 3:
            return redirect()->to('/operator/dashboard');
        default:
            return redirect()->to('/');  
    }
}

}
