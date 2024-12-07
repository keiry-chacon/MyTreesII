<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verifica si el usuario está autenticado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login'); 
        }

        // Si se pasan roles en los argumentos, verifica si el rol del usuario es válido
        if ($arguments) {
            $role = session()->get('role_id');
            if (!in_array($role, $arguments)) {
                return redirect()->to('/unauthorized'); 
            }
        }
    }

    
}


