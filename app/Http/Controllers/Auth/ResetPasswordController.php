<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    // Sobrescribir método para redirigir al login con mensaje
    protected function sendResetResponse(Request $request, $response)
    {
        return redirect()->route('login.form')
                         ->with('status', '¡Contraseña restablecida correctamente! Ahora puedes iniciar sesión.');
    }
}
