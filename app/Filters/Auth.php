<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if the user is authenticated
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login'); 
        }
    
        // If roles are passed in the arguments, check if the user's role is valid
        if ($arguments) {
            $role = session()->get('role_id');
            if (!in_array($role, $arguments)) {
                return redirect()->to('/unauthorized'); 
            }
        }
    }
    

    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Typically, you can add logic here to execute after processing the request
        // If no additional logic is needed, simply leave this method empty.
    }
}


