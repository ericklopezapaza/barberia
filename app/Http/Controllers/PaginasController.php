<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaginasController extends Controller
{
    public function index()
    {
        return view('index'); // resources/views/index.blade.php
    }

    public function servicios()
    {
        return view('servicios'); // resources/views/servicios.blade.php
    }

    public function nosotros()
    {
        return view('nosotros'); // resources/views/nosotros.blade.php
    }

   

}
