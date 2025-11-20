<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Reserva;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // Mostrar el formulario de login/registro
    public function showLoginForm()
    {
        return view('auth.login_cliente');
    }

    // Proceso del login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();   
            return redirect('/perfil')->with('success', '¡Inicio de sesión correcto!');     
        }

        return back()->with('error', 'Correo o contraseña incorrectos.');
    }

    
    public function register(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'email' => [
            'required',
            'email',
            'unique:users,email',
            function($attribute, $value, $fail) {
                if (!str_ends_with(strtolower($value), '@gmail.com')) {
                    $fail('El correo debe ser una cuenta de Gmail.');
                }
            },
        ],
            'password'         => 'required|min:6|confirmed',
        ], [
            'email.unique' => 'Este correo ya está registrado, prueba con otro.'
        ]);

        $user = User::create([
            'name'             => $request->name,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'role'             => 'cliente',
        ]);

        Auth::login($user);

        return redirect('/perfil')->with('success', '¡Registro completado con éxito!');
    }

    // Mostrar perfil del usuario logueado
    public function perfil()
    {
        $user = Auth::user();

        $barberos = User::where('role', 'barbero')->get();
        $reservas = Reserva::where('usuario_id', $user->id)->get();

        return view('auth.perfil', compact('user', 'barberos', 'reservas'));
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Sesión cerrada correctamente.');
    }

    // Redirigir al login de Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback de Google SIN verificación de email
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Separar nombre completo en nombre y apellidos
            $nombreCompleto = $googleUser->getName();
            $partes = explode(' ', $nombreCompleto);

            $nombre = $partes[0];
            $apellido_paterno = $partes[1] ?? null;
            $apellido_materno = $partes[2] ?? null;

            // Buscar o crear usuario
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $nombre,
                    'apellido_paterno' => $apellido_paterno,
                    'apellido_materno' => $apellido_materno,
                    'imagen_perfil' => $googleUser->getAvatar(),
                    'role' => 'cliente',
                    'google_id' => $googleUser->getId(),
                ]
            );

            Auth::login($user);

            // Si el usuario no tiene contraseña, redirigir a crear contraseña
            if (!$user->password) {
                return redirect()->route('perfil.crearContrasenaForm');
            }

            return redirect()->route('perfil.editar');

        } catch (\Exception $e) {
            return redirect()->route('login.form')->with('error', 'Error al iniciar sesión con Google: ' . $e->getMessage());
        }
    }
}

