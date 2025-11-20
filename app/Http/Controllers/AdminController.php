<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barbero;
use App\Models\Admin;
use App\Models\Servicio;
use App\Models\Reserva;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function dashboard()
    {
        $adminId = session('staff')['id'] ?? null;

        if (!$adminId) {
            return redirect()->route('staff.login.form');
        }

        $admin = Admin::find($adminId); 
        $barberos = Barbero::all();
        $servicios = Servicio::all();

        return view('admin.dashboard', compact('admin', 'servicios', 'barberos'));
    }

    // ─────────── AGREGAR BARBERO ───────────
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:191',
            'apellido' => 'nullable|string|max:191',
            'email' => 'required|email|unique:barberos,email',
            'password' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:20',
            
            'imagen_perfil' => 'nullable|string|max:191', 
        ]);

        Barbero::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'telefono' => $request->telefono,
            'imagen_perfil' => $request->imagen_perfil,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Barbero agregado correctamente.');
    }

    // ─────────── MOSTRAR FORMULARIO DE EDICIÓN ───────────
    public function editBarbero($id)
    {
        $barbero = Barbero::findOrFail($id);
        return view('admin.tabla-barberos', compact('barbero'));
    }

    // ─────────── ACTUALIZAR ESTADO DEL BARBERO  ───────────
    public function updateBarbero(Request $request, $id)
    {
        $barbero = Barbero::findOrFail($id);

        $request->validate([
            'estado' => 'required|in:Activo,Permiso',
            'fecha_inicio_permiso' => 'nullable|date',
            'fecha_fin_permiso' => 'nullable|date|after_or_equal:fecha_inicio_permiso',
        ]);

        if ($request->estado === 'Activo') {
            $request->merge([
                'fecha_inicio_permiso' => null,
                'fecha_fin_permiso' => null,
            ]);
        }

        $barbero->update([
            'estado' => $request->estado,
            'fecha_inicio_permiso' => $request->fecha_inicio_permiso,
            'fecha_fin_permiso' => $request->fecha_fin_permiso,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Barbero actualizado correctamente.');
    }

    // ─────────── ELIMINAR BARBERO ───────────
    public function deleteBarbero($id)
    {
        $barbero = Barbero::findOrFail($id);
        $barbero->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Barbero eliminado correctamente.');
    }
 
    
    // ─────────── AGREGAR SERVICIO ───────────
    public function storeServicio(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:191',
            'descripcion' => 'nullable|string',
            'duracion' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
        ]);

        Servicio::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'duracion' => $request->duracion,
            'precio' => $request->precio,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Servicio agregado correctamente.');
    }
    
    // ─────────── ACTUALIZAR SERVICIO ───────────
    public function updateServicio(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:191',
            'descripcion' => 'nullable|string',
            'duracion' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
        ]);

        $servicio->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'duracion' => $request->duracion,
            'precio' => $request->precio,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Servicio actualizado correctamente.');
    }

    // ─────────── ELIMINAR SERVICIO ───────────
    public function deleteServicio($id)
    {
        $servicio = Servicio::findOrFail($id);
        $servicio->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Servicio eliminado correctamente.');
    }
    //reportes de reservas
    public function reporteReservas(Request $request)
    {
        $adminId = session('staff')['id'] ?? null;
        if(!$adminId){
            return redirect()->route('staff.login.form');
        }
        $admin = Admin::find($adminId);

        $query = Reserva::query();

        // Filtrar por fechas
        if ($request->filled(['fecha_inicio', 'fecha_fin'])) {
            $query->whereBetween('fecha_reserva', [
                $request->fecha_inicio,
                $request->fecha_fin
            ]);
        }

        // Filtrar por barbero si se seleccionó
        if ($request->filled('barbero_id')) {
            $query->where('barbero_id', $request->barbero_id);
        }

        $reservas = $query->get();

        return view('admin.dashboard', [
            'admin' => $admin,
            'barberos' => Barbero::all(),
            'servicios' => Servicio::all(),
            'reservas' => $reservas
            
        ]);
    }

    

    public function reporteReservasPDF(Request $request)
    {
        // Convertir el barbero_id a entero o null si no se selecciona
        $barbero_id = $request->barbero_id !== '' ? (int) $request->barbero_id : null;

        // Iniciar query de reservas
        $query = Reserva::query();

        // Filtrar por fechas si se proporcionan
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_reserva', [$request->fecha_inicio, $request->fecha_fin]);
        }

        // Filtrar por barbero si se seleccionó uno
        if ($barbero_id) {
            $query->where('barbero_id', $barbero_id);
        }

        // Obtener resultados
        $reservas = $query->get();

        // Generar PDF
        $pdf = Pdf::loadView('admin.reporte-pdf', [
            'reservas' => $reservas,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'barbero_id' => $barbero_id
        ]);

        // Abrir PDF en navegador
        return $pdf->stream('reporte-reservas.pdf');
    }



    // ─────────── PERFIL Y LOGOUT ───────────
    public function perfil()
    {
        return view('admin.perfil');
    }

    public function logout()
    {
        session()->forget('staff'); 
        return redirect()->route('staff.login.form'); 
    }
}
