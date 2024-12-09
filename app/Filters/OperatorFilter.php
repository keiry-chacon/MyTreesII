<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;

class OperatorFilter implements FilterInterface
{
    public function before($request, $arguments = null)
    {
        // Obtén el rol del usuario desde la sesión
        $userRole = session()->get('role_id');

        // Verifica si el rol es 3 (operator)
        if ($userRole != 3) {
            // Si no es operador, redirige a la página de "Acceso Denegado"
            return redirect()->to('/unauthorized');
        }
    }

    public function after($request, $response, $arguments = null)
    {
        // No es necesario realizar ninguna acción después de que se ejecute el controlador.
    }
}

