<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Reserva;
use App\Models\Barbero;
use App\Models\Servicio;
use Illuminate\Support\Facades\Hash;


class PerfilController extends Controller
{
    /**
     * Mostrar el formulario de edición de perfil
     */
    public function editar()
    {
    $user = Auth::user();

    // Obtener barberos
    $barberos = User::where('role', 'barbero')->get();

    // Obtener reservas del usuario
    $reservas = Reserva::where('usuario_id', $user->id)->get();

    $barberos = Barbero::all(); // Obtener todos los barberos

    $servicios = Servicio::all(); // Obtener todos los servicios disponibles

    // Retornar la vista pasando $user, $barberos y $reservas
    return view('auth.perfil', compact('user', 'barberos', 'reservas', 'servicios'));
    }

    /**
     * Procesar la actualización del perfil
     */
    public function actualizar(Request $request)
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            return redirect()->route('perfil.editar')->with('error', 'Usuario no encontrado.');
        }

        // Validación de los campos
        $request->validate([
            'name' => 'required|string|max:191',
            'apellido_paterno' => 'nullable|string|max:191',
            'apellido_materno' => 'nullable|string|max:191',
            'email' => ['required','email','max:191', Rule::unique('users')->ignore($user->id)],
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:51200',
        ]);

        // Actualizar datos
        $user->name = $request->name;
        $user->apellido_paterno = $request->apellido_paterno;
        $user->apellido_materno = $request->apellido_materno;
        $user->email = $request->email;

        // Subida de imagen de perfil
        if($request->hasFile('imagen')){
            $imagen = $request->file('imagen');
            $nombreImagen = uniqid().'_'.$imagen->getClientOriginalName();

            // Crear carpeta uploads si no existe
            $uploadsPath = public_path('uploads');
            if(!file_exists($uploadsPath)){
                mkdir($uploadsPath, 0755, true);
            }

            // Mover imagen al directorio
            $imagen->move($uploadsPath, $nombreImagen);

            // Eliminar imagen anterior si no es default
            if($user->imagen_perfil && $user->imagen_perfil != 'default.png' 
               && file_exists($uploadsPath.'/'.$user->imagen_perfil)){
                unlink($uploadsPath.'/'.$user->imagen_perfil);
            }

            $user->imagen_perfil = $nombreImagen;
        }

        // Guardar cambios en la base de datos
        $user->save();

        return redirect()->route('perfil.editar')->with('success', 'Perfil actualizado correctamente');
    }

    // Mostrar el formulario para crear contraseña
    public function mostrarCrearContrasenaForm()
    {
        $user = Auth::user();

        // Evitar que acceda si ya tiene contraseña
        if ($user->password) {
            return redirect()->route('perfil.editar');
        }

        return view('auth.crear_contrasena', compact('user'));
    }

    // Guardar la contraseña creada
    public function crearContrasena(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        // Obtener usuario real desde la base de datos
        $user = User::find(Auth::id());

        if (!$user) {
            return redirect()->route('login')->with('error', 'Usuario no encontrado.');
        }

        // Guardar la contraseña
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('perfil.editar')->with('success', 'Contraseña creada correctamente. Ahora puedes iniciar sesión también con email y contraseña.');
    }

}
