<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!$request->user() || $request->user()->role !== $role) {
            return redirect('/')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
