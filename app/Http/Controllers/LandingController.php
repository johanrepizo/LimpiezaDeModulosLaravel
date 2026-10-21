<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    /**
     * Muestra la página principal (landing page) que explica todo el proyecto GestiLimpieza.
     * Retorna con encabezados no-cache para evitar que el navegador use cachés antiguos de redirección 301.
     */
    public function index()
    {
        return response()
            ->view('landing')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
