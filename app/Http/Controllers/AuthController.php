<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->rol_id == 1 ? redirect('/admin') : redirect('/vocero');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        $correo = trim(strtolower($request->correo));
        $usuarioExistente = Usuario::where('correo', $correo)->first();

        if (!$usuarioExistente) {
            return back()->with('error', 'El correo no se encuentra registrado en el sistema.')->withInput($request->only('correo'));
        }

        if (!$usuarioExistente->activo) {
            return back()->with('error', 'Esta cuenta de usuario ha sido desactivada por el administrador.')->withInput($request->only('correo'));
        }

        if ($this->authService->verificarBloqueado($usuarioExistente->id)) {
            return back()->with('error', 'Cuenta bloqueada temporalmente por varios intentos fallidos. Intenta de nuevo en unos minutos.')->withInput($request->only('correo'));
        }

        $usuario = $this->authService->validarCredenciales($correo, $request->password);

        if (!$usuario) {
            $restantes = max(0, 3 - ($usuarioExistente->fresh()->intentos_fallidos ?? 0));
            $msg = $restantes > 0 
                ? "Contraseña incorrecta. Te quedan {$restantes} intento(s) antes del bloqueo temporal."
                : "Contraseña incorrecta. Tu cuenta ha sido bloqueada temporalmente por 15 minutos.";
            return back()->with('error', $msg)->withInput($request->only('correo'));
        }

        Auth::login($usuario, $request->boolean('remember'));

        // Regenerar ID de sesión después de login
        $request->session()->regenerate();

        if ($usuario->primer_acceso && $usuario->rol_id == 2) {
            return redirect('/cambiar-password')->with('info', 'Por favor cambia tu contraseña');
        }

        return $usuario->rol_id == 1 ? redirect('/admin') : redirect('/vocero');
    }

    public function showCambiarPasswordForm()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        return view('auth.cambiar_password');
    }

    public function cambiarPassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $usuario = Auth::user();

        $request->validate([
            'password_actual' => 'required',
            'password_nueva' => 'required|confirmed',
            'password_nueva_confirmation' => 'required'
        ]);

        if (!Hash::check($request->password_actual, $usuario->password)) {
            return back()->with('error', 'La contraseña actual es incorrecta');
        }

        $validacion = $this->authService->validarContraseña($request->password_nueva);
        if ($validacion[0] === false) {
            return back()->withErrors($validacion[1] ?? []);
        }

        $usuario->actualizarPassword(Hash::make($request->password_nueva));
        $usuario->marcarPrimerAccesoCompletado();

        return back()->with('success', 'Contraseña actualizada correctamente');
    }

    public function recuperarPassword(Request $request)
    {
        $request->validate(['correo' => 'required|email']);

        $usuario = Usuario::obtenerPorEmail($request->correo);

        if ($usuario) {
            $nuevaPassword = $this->authService->generarContraseñaAleatoria();
            $usuario->actualizarPassword(Hash::make($nuevaPassword));

            // Crear notificación para admin
            $usuario->notificaciones()->create([
                'titulo' => 'Recuperación de Contraseña',
                'descripcion' => "Nueva contraseña: $nuevaPassword",
                'tipo' => 'credenciales'
            ]);
        }

        // Mensaje genérico por seguridad
        return back()->with('success', 'Si el correo existe, recibirá instrucciones');
    }

    public function logout(Request $request)
    {
        Session::flush();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
