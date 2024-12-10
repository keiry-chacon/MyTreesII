<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;

class FriendFilter implements FilterInterface
{
    public function before($request, $arguments = null)
    {
        // Get the user's role from the session
        $userRole = session()->get('role_id');
    
        // Check if the role is 2 (friend)
        if ($userRole != 2) {
            // If not a friend, redirect to the "Access Denied" page
            return redirect()->to('/unauthorized');
        }
    }
    

    
    public function after($request, $response, $arguments = null)
    {
        // No action is needed after the controller is executed.
    }    
}
