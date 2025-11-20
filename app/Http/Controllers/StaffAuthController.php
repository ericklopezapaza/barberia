<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\Barbero;
use Illuminate\Http\Request;
use App\Models\Admin;

class StaffAuthController extends Controller
{
    public function showLoginForm() // metodo para mostrar el formulario de inicio de sesion del personal
    {                                     // showLoginForm es el nombre del metodo que se llama en las rutas web.php
        return view('auth.login_staff');  // Retorna la vista del formulario de inicio de sesion del personal
                          // autth.login_staff es la ubicacion de la vista en resources/views/auth/login_staff.blade.php
    }

    public function login(Request $request) // metodo para procesar el inicio de sesion del personal-- request $request es el objeto que contiene los datos del formulario
    {
        $request->validate([          
            'email' => 'required|email',     
            'password'=> 'required',         
        ]);                                 

        
        $barbero = Barbero::where('email', $request->email)->first(); // busca al barbero por su email--firts trae el primer resultado que encuentra--$request->email es el email que se envia desde el formulario
        if($barbero && Hash::check($request->password, $barbero->password)){ // verifica que el barbero exista y que la contraseña sea correcta--hash::check es una funcion que verifica que la contraseña ingresada coincida con la almacenada en la base de datos
        session(['staff'=>['tipo'=> 'barbero', 'id' => $barbero->id]]); // crea una sesion para el personal con el tipo de barbero y su id
            return redirect('/barbero/dashboard'); // redirige al dashboard del barbero
        }

        
        $admin = Admin::where('email', $request->email)->first();
        if($admin && Hash::check($request->password, $admin->password)){ //() verifica que el admin exista y que la contraseña sea correcta
        session(['staff'=>['tipo'=> 'admin', 'id'=> $admin->id]]); // crea una sesion para el personal con el tipo de admin y su id
            return redirect('/admin/dashboard');
        }

        return back()->withErrors(['email' => 'credenciales no validas']);
        
    }
}
