<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Services;

class AdminFilter implements FilterInterface
{
    public function before($request, $arguments = null)
    {
        // Get the user's role (assuming you already have the authentication system set up)
        $userRole = session()->get('role_id'); // Here you get the user's role from the session (for example)
    
        // Check if the role is 1 (admin)
        if ($userRole != 1) {
            // If not admin, redirect to the "Access Denied" page
            return redirect()->to('/unauthorized');
        }
    }
    

    
    public function after($request, $response, $arguments = null)
    {
        // No action is needed after the controller is executed.
    }
}

