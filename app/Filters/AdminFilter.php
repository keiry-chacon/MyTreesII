<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Services;

class AdminFilter implements FilterInterface
{
    public function before($request, $arguments = null)
    {
        // Obtén el rol del usuario (supongo que ya tienes el sistema de autenticación configurado)
        $userRole = session()->get('role_id'); // Aquí obtienes el rol del usuario desde la sesión (por ejemplo)

        // Verifica si el rol es 1 (admin)
        if ($userRole != 1) {
            // Si no es admin, redirige a la página de "Acceso Denegado"
            return redirect()->to('/unauthorized');
        }
    }

    public function after($request, $response, $arguments = null)
    {
        // No es necesario realizar ninguna acción después de que se ejecute el controlador.
    }
}

