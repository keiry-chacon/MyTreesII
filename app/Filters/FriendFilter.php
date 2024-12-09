<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;

class FriendFilter implements FilterInterface
{
    public function before($request, $arguments = null)
    {
        // Obtén el rol del usuario desde la sesión
        $userRole = session()->get('role_id');

        // Verifica si el rol es 2 (friend)
        if ($userRole != 2) {
            // Si no es amigo, redirige a la página de "Acceso Denegado"
            return redirect()->to('/unauthorized');
        }
    }

    public function after($request, $response, $arguments = null)
    {
        // No es necesario realizar ninguna acción después de que se ejecute el controlador.
    }
}
