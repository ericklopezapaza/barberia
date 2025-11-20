<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Admin;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si hay sesión activa y si el tipo es 'admin'
        if (session('staff.tipo') !== 'admin') {
            return redirect('/staff/login');
        }

        // Carga los datos del admin desde la BD
        $adminId = session('staff.id');
        $admin = Admin::find($adminId);

        // Comparte el admin con todas las vistas
        view()->share('admin', $admin);

        return $next($request);
    }
}
//63837570