<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Servicio;
use App\Models\Barbero;
use Illuminate\Support\Facades\Storage;

class BarberoController extends Controller
{
    // Muestra el panel principal del barbero
    public function dashboard()
    {
        $barberoId = session('staff')['id'];
        $barbero = Barbero::find($barberoId);
        $reservas = Reserva::where('barbero_id', $barberoId)
            ->orderBy('fecha_reserva', 'asc')
            ->get();

        $clientes = User::where('role', 'cliente')->get();
        $servicios = Servicio::all();

        return view('barbero.dashboard', compact('barbero', 'reservas', 'clientes', 'servicios'));
    }

    // Ver citas asignadas
    public function verCitas()
    {
        $barberoId = session('staff')['id'];
        $reservas = Reserva::where('barbero_id', $barberoId)->get();
        return view('barbero.citas', compact('reservas'));
    }

    // Cambiar el estado de una reserva
    public function cambiarEstado(Request $request, $id)
    {
        $barberoId = session('staff')['id'];
        $reserva = Reserva::where('barbero_id', $barberoId)->findOrFail($id);

        $nuevoEstado = $request->input('estado');
        if (in_array($nuevoEstado, ['Atendido', 'Cancelado', 'No vino'])) {
            $reserva->estado = $nuevoEstado;
            $reserva->save();
            return redirect()->back()->with('success', 'Estado de la reserva actualizado correctamente.');
        }

        return redirect()->back()->with('error', 'Estado inválido.');
    }
    
    public function updateAvatar(Request $request)
{
    $barberoId = session('staff')['id'];
    $barbero = Barbero::findOrFail($barberoId);

    $request->validate([
        'imagen_perfil' => 'required|image|mimes:jpeg,png,jpg,gif|max:51200', // máximo 50 MB
    ]);

    if ($request->hasFile('imagen_perfil')) {
        $file = $request->file('imagen_perfil');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = 'imagenes/barberos/' . $filename;

        // Eliminar imagen anterior si existe y no es la default
        if ($barbero->imagen_perfil && file_exists(public_path($barbero->imagen_perfil))) {
            unlink(public_path($barbero->imagen_perfil));
        }

        // Subir nueva imagen
        $file->move(public_path('imagenes/barberos'), $filename);

        // Guardar ruta en la base de datos
        $barbero->imagen_perfil = $path;
        $barbero->save();

        // Retornar con success
        return redirect()->back()->with('success', 'Imagen de perfil actualizada correctamente.');
    }

    return redirect()->back()->with('error', 'No se seleccionó ninguna imagen.');
}



    public function logout()
    {
        session()->forget('staff'); 
        return redirect()->route('staff.login.form'); 
    }
}
