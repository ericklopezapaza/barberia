<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Barbero;

class BarberoMiddleware
{
      public function handle(Request $request, Closure $next): Response
    {   
        if(session('staff.tipo') !== 'barbero'){   // session() es que indica si hay una session activa-> staff.tipo !== 'barebero' es la condicion que debe cumplirse para que se permita el acceso a la ruta protegida por este middleware
            return redirect('/'); // Redirige al usuario a la página de inicio si no es un barbero
            
        }
        // Trae los datos del barbero desde la DB
        $barberoId = session('staff.id');
        $barbero = Barbero::find($barberoId);

        // Comparte $barbero con todas las vistas
        view()->share('barbero', $barbero);

        return $next($request);
    }
}
