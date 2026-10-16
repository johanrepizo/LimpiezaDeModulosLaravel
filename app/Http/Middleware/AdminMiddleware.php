<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login')
                ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
        }

        if (Auth::user()->rol_id == 1) {
            return $next($request);
        }

        return redirect('/vocero')->with('error', 'No autorizado para el panel de administración');
    }
}
