<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use App\Models\Ficha;
use App\Models\Modulo;
use App\Models\Asignacion;
use App\Models\Aprendiz;
use App\Models\Vocero;
use App\Models\Usuario;
use App\Models\Grupo;
use App\Models\Evidencia;
use App\Models\Turno;
use App\Models\Notificacion;
use App\Models\LogSincronizacion;
use App\Services\TurnoService;
use App\Services\AuthService;
use App\Services\ReporteService;
use App\Http\Requests\GuardarProgramaRequest;
use App\Http\Requests\GuardarFichaRequest;
use App\Http\Requests\AsignarModuloRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    protected $turnoService;
    protected $authService;
    protected $reporteService;

    public function __construct(TurnoService $turnoService, AuthService $authService, ReporteService $reporteService)
    {
        $this->turnoService = $turnoService;
        $this->authService = $authService;
        $this->reporteService = $reporteService;
    }

    public function showDashboard()
    {
        // Actualizar estados automáticos de turnos
        Turno::cerrarTurnosVencidos();
        Turno::abrirTurnosHoy();

        $fichas = Ficha::where('activo', true)->count();
        $aprendices = Aprendiz::where('activo', true)->count();
        $grupos = Grupo::where('activo', true)->count();
        $evidencias = Evidencia::count();
        $voceros = Vocero::where('activo', true)->count();
        $asignaciones = Asignacion::where('activo', true)->count();
        $incumplimientosCount = Turno::where('estado', Turno::ESTADO_INCUMPLIDO)->count();

        // Resumen del reporte trimestral actual para el widget del dashboard
        $anoActual = Carbon::now()->year;
        $trimestreActual = $this->reporteService->getTrimestreActual();
        $reporteTrimestralResumen = $this->reporteService->generarReporteTrimestral($anoActual, $trimestreActual);

        // Últimas evidencias registradas con sus relaciones
        $ultimasEvidencias = Evidencia::with([
            'turno.asignacion.ficha',
            'turno.asignacion.modulo',
            'turno.grupo.vocero.aprendiz'
        ])
        ->orderBy('id', 'desc')
        ->limit(6)
        ->get();

        // Incumplimientos / turnos vencidos
        $incumplimientos = Turno::where('estado', Turno::ESTADO_INCUMPLIDO)
            ->with([
                'asignacion.ficha.voceros.aprendiz',
                'asignacion.modulo',
                'grupo.vocero.aprendiz'
            ])
            ->orderBy('fecha_turno', 'desc')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'fichas',
            'aprendices',
            'grupos',
            'evidencias',
            'voceros',
            'asignaciones',
            'incumplimientosCount',
            'reporteTrimestralResumen',
            'ultimasEvidencias',
            'incumplimientos'
        ));
    }

    // ===== PROGRAMAS (Solo Lectura - SICEFA) =====
    public function guardarPrograma(Request $request)
    {
        return back()->with('warning', 'Los programas de formación están sincronizados desde SICEFA y no pueden ser modificados.');
    }

    public function eliminarPrograma(Request $request)
    {
        return back()->with('warning', 'Los programas de formación están sincronizados desde SICEFA y no pueden ser eliminados.');
    }

    public function showProgramas()
    {
        $programas = Programa::all();
        return view('admin.programas.index', compact('programas'));
    }

    public function showFichasPorPrograma($programa)
    {
        $programa = ($programa instanceof Programa) ? $programa : Programa::findOrFail($programa);
        $fichas = $programa->fichas()->with('aprendices', 'voceros', 'asignaciones')->get();
        return view('admin.fichas.por-programa', compact('programa', 'fichas'));
    }

    // ===== FICHAS (Solo Lectura - SICEFA) =====
    public function guardarFicha(Request $request)
    {
        return back()->with('warning', 'Las fichas están sincronizadas desde SICEFA y no pueden ser modificadas.');
    }

    public function eliminarFicha(Request $request)
    {
        return back()->with('warning', 'Las fichas están sincronizadas desde SICEFA y no pueden ser eliminadas.');
    }

    public function showFichas()
    {
        $fichas = Ficha::with('programa')->get();
        return view('admin.fichas.index', compact('fichas'));
    }

    // ===== MODULOS =====
    public function guardarModulo(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:modulos,nombre,' . ($request->id ?? 'NULL'),
            'ubicacion' => 'required',
            'capacidad' => 'required|integer',
            'descripcion' => 'nullable'
        ]);

        $id = $request->input('id');
        
        if ($id) {
            $modulo = Modulo::find($id);
            if ($modulo) {
                $modulo->update($request->all());
                return back()->with('success', 'Módulo actualizado correctamente');
            }
        } else {
            Modulo::create($request->all());
            return back()->with('success', 'Módulo creado correctamente');
        }

        return back()->with('error', 'Error al guardar el módulo');
    }

    public function showModulos(Request $request)
    {
        $modulos = Modulo::withCount('asignaciones')->get();
        $queryAsignaciones = Asignacion::with(['modulo', 'ficha.programa', 'turnos.evidencias']);
        if ($request->filled('ficha_id')) {
            $queryAsignaciones->where('ficha_id', $request->ficha_id);
        }
        $asignaciones = $queryAsignaciones->latest()->get();
        // Cargar fichas activas con el conteo de módulos activos asignados actualmente
        $fichas = Ficha::where('activo', true)
                       ->with('programa')
                       ->withCount(['asignaciones' => function ($q) {
                           $q->where('activo', true);
                       }])
                       ->get();
        $fichaSeleccionada = $request->filled('ficha_id') ? Ficha::find($request->ficha_id) : null;
        return view('admin.modulos.index', compact('modulos', 'asignaciones', 'fichas', 'fichaSeleccionada'));
    }

    // ===== ASIGNACIONES =====
    public function crearAsignacion(AsignarModuloRequest $request)
    {
        $modulo = Modulo::find($request->modulo_id);
        $ficha = Ficha::find($request->ficha_id);

        if (!$modulo || !$ficha) {
            return back()->with('error', 'Módulo o ficha no encontrados');
        }

        // Regla: Cada ficha puede tener entre 1 y máximo 2 módulos activos simultáneamente
        $modulosActivos = Asignacion::where('ficha_id', $request->ficha_id)
                                    ->where('activo', true)
                                    ->count();

        if ($modulosActivos >= 2) {
            return back()->with('error', "La ficha {$ficha->numero_ficha} ya tiene 2 módulos asignados (máximo permitido). Para asignar este módulo, primero cancela una de las asignaciones activas.");
        }

        // Verificar que el módulo específico no esté ya asignado activamente a esta ficha
        $existente = Asignacion::where('modulo_id', $request->modulo_id)
                                ->where('ficha_id', $request->ficha_id)
                                ->where('activo', true)
                                ->first();

        if ($existente) {
            return back()->with('error', 'Este módulo ya está asignado activamente a esta ficha');
        }

        // Si la ficha ya tiene 1 módulo activo, sincronizar día y fecha para que limpien el mismo día
        $primeraAsignacion = Asignacion::where('ficha_id', $request->ficha_id)
                                        ->where('activo', true)
                                        ->first();

        $diaSemana = $primeraAsignacion ? $primeraAsignacion->dia_semana : $request->dia_semana;
        $fechaInicio = $primeraAsignacion ? $primeraAsignacion->fecha_inicio : $request->fecha_inicio;

        $asignacion = Asignacion::create([
            'modulo_id' => $request->modulo_id,
            'ficha_id' => $request->ficha_id,
            'fecha_inicio' => $fechaInicio,
            'dia_semana' => $diaSemana,
            'activo' => true
        ]);

        // Generar 104 turnos (2 años)
        $turnosCreados = $this->turnoService->generarTurnosAsignacion(
            $asignacion->id,
            Carbon::parse($fechaInicio),
            $diaSemana
        );

        // Si la ficha ya tenía turnos con grupos asignados, sincronizar el mismo grupo al nuevo módulo
        if ($primeraAsignacion) {
            $turnosConGrupo = Turno::where('asignacion_id', $primeraAsignacion->id)
                                   ->whereNotNull('grupo_id')
                                   ->get();

            foreach ($turnosConGrupo as $t1) {
                Turno::where('asignacion_id', $asignacion->id)
                     ->where('fecha_turno', $t1->fecha_turno)
                     ->update([
                         'grupo_id' => $t1->grupo_id,
                         'estado' => $t1->estado
                     ]);
            }
        }

        // Notificar a voceros
        $voceros = $ficha->obtenerVocerosActivos();
        foreach ($voceros as $vocero) {
            $vocero->usuario->notificaciones()->create([
                'titulo' => 'Nueva Asignación de Módulo',
                'descripcion' => "Se asignó nuevo módulo: {$modulo->nombre}" . ($primeraAsignacion ? " (El mismo grupo limpiará ambos módulos el mismo día)" : ""),
                'tipo' => 'info'
            ]);
        }

        $msg = "Asignación creada correctamente. La ficha ahora tiene " . ($modulosActivos + 1) . " de 2 módulos asignados.";
        if ($primeraAsignacion) {
            $msg .= " Se sincronizó el mismo día para que el mismo grupo limpie ambos módulos y reporte 4 fotos.";
        }

        return back()->with('success', $msg);
    }

    public function editarAsignacion(Request $request)
    {
        $asignacion = Asignacion::find($request->id);
        if (!$asignacion) {
            return back()->with('error', 'Asignación no encontrada');
        }

        $asignacion->update([
            'fecha_inicio' => $request->fecha_inicio,
            'dia_semana' => $request->dia_semana
        ]);

        return back()->with('success', 'Asignación actualizada correctamente');
    }

    public function cancelarAsignacion(Request $request)
    {
        $asignacion = Asignacion::with('turnos.evidencias')->find($request->id);
        if (!$asignacion) {
            return back()->with('error', 'Asignación no encontrada');
        }

        // Desactivar la asignación (queda registrada en el historial como cancelada)
        $asignacion->update(['activo' => false]);
        
        // Contar evidencias existentes para informar al admin
        $evidenciasConservadas = Evidencia::whereHas('turno', function($q) use ($asignacion) {
            $q->where('asignacion_id', $asignacion->id);
        })->count();

        // Eliminar ÚNICAMENTE los turnos futuros/pendientes que NO tengan evidencias registradas
        // Los turnos que ya tengan evidencias se conservan intactos en la base de datos
        Turno::where('asignacion_id', $asignacion->id)
              ->whereIn('estado', [Turno::ESTADO_PENDIENTE, Turno::ESTADO_ABIERTO])
              ->whereDoesntHave('evidencias')
              ->delete();

        $msg = 'Asignación cancelada correctamente.';
        if ($evidenciasConservadas > 0) {
            $msg .= " Se conservaron de forma segura las {$evidenciasConservadas} evidencias fotográficas registradas de este módulo.";
        }

        return back()->with('success', $msg);
    }

    // ===== VOCEROS =====
    public function activarVocero(Request $request)
    {
        $request->validate([
            'aprendiz_id' => 'required|exists:aprendices,id',
            'ficha_id' => 'required|exists:fichas,id'
        ]);

        $ficha = Ficha::find($request->ficha_id);
        $aprendiz = Aprendiz::find($request->aprendiz_id);

        if (!$ficha || !$aprendiz) {
            return back()->with('error', 'Ficha o aprendiz no encontrados');
        }

        // Verificar máximo 2 voceros activos
        $vocesActivos = $ficha->obtenerVocerosActivos()->count();
        if ($vocesActivos >= 2) {
            return back()->with('warning', 'La ficha ya tiene 2 voceros activos');
        }

        // Crear usuario
        $password = $this->authService->generarContraseñaAleatoria();
        $usuario = Usuario::create([
            'correo' => 'ap' . $aprendiz->id . '@sena.edu.co',
            'nombres' => $aprendiz->nombres . ' ' . $aprendiz->apellidos,
            'password' => Hash::make($password),
            'rol_id' => 2,
            'primer_acceso' => true,
            'activo' => true
        ]);

        // Crear vocero
        Vocero::create([
            'aprendiz_id' => $aprendiz->id,
            'usuario_id' => $usuario->id,
            'ficha_id' => $ficha->id,
            'activo' => true
        ]);

        // Crear notificación
        $usuario->notificaciones()->create([
            'titulo' => 'Credenciales de Acceso',
            'descripcion' => "Correo: {$usuario->correo} | Contraseña: $password",
            'tipo' => 'credenciales'
        ]);

        return back()->with('success', "Vocero activado. Contraseña: $password");
    }

    public function desactivarVocero(Request $request)
    {
        $vocero = Vocero::find($request->id);
        if (!$vocero) {
            return back()->with('error', 'Vocero no encontrado');
        }

        $vocero->update([
            'activo' => false,
            'fecha_desactivacion' => now()
        ]);

        return back()->with('success', 'Vocero desactivado correctamente');
    }

    public function reactivarVocero(Request $request)
    {
        $vocero = Vocero::find($request->id);
        if (!$vocero) {
            return back()->with('error', 'Vocero no encontrado');
        }

        $vocero->update([
            'activo' => true,
            'fecha_activacion' => now(),
            'fecha_desactivacion' => null
        ]);

        return back()->with('success', 'Vocero reactivado correctamente');
    }

    public function reenviarCredenciales(Request $request)
    {
        $vocero = Vocero::find($request->id);
        if (!$vocero) {
            return back()->with('error', 'Vocero no encontrado');
        }

        $password = $this->authService->generarContraseñaAleatoria();
        $vocero->usuario->actualizarPassword(Hash::make($password));

        $vocero->usuario->notificaciones()->create([
            'titulo' => 'Credenciales de Acceso',
            'descripcion' => "Correo: {$vocero->usuario->correo} | Contraseña: $password",
            'tipo' => 'credenciales'
        ]);

        return back()->with('success', "Credenciales reenviadas. Contraseña: $password");
    }

    // ===== CAMBIO DE ROL APRENDIZ <-> VOCERO =====
    public function cambiarRolAprendiz(Request $request)
    {
        $request->validate([
            'aprendiz_id' => 'required|exists:aprendices,id',
            'nuevo_rol' => 'required|in:vocero,aprendiz'
        ]);

        $aprendiz = Aprendiz::with(['ficha', 'vocero.usuario'])->findOrFail($request->aprendiz_id);
        $ficha = $aprendiz->ficha;

        if ($request->nuevo_rol === 'vocero') {
            // Verificar si ya es vocero activo
            if ($aprendiz->vocero && $aprendiz->vocero->activo) {
                return back()->with('info', "El aprendiz {$aprendiz->nombres} {$aprendiz->apellidos} ya es un Vocero activo.");
            }

            // REGLA DE NEGOCIO: Máximo 2 voceros activos por ficha
            $vocerosActivosCount = Vocero::where('ficha_id', $ficha->id)->where('activo', true)->count();
            if ($vocerosActivosCount >= 2) {
                return back()->with('error', "No es posible asignar más voceros. La Ficha {$ficha->numero_ficha} ya cuenta con el número máximo permitido de 2 voceros activos. Debe cambiar o desactivar uno antes de asignar uno nuevo.");
            }

            // Si ya existe un registro de vocero para este aprendiz, reactivarlo
            if ($aprendiz->vocero) {
                $vocero = $aprendiz->vocero;
                $vocero->update([
                    'activo' => true,
                    'fecha_activacion' => now(),
                    'fecha_desactivacion' => null
                ]);

                if ($vocero->usuario) {
                    $password = $this->authService->generarContraseñaAleatoria();
                    $vocero->usuario->update([
                        'activo' => true,
                        'rol_id' => 2,
                        'password' => Hash::make($password)
                    ]);

                    $vocero->usuario->notificaciones()->create([
                        'titulo' => 'Rol Actualizado a Vocero',
                        'descripcion' => "Has sido reasignado como Vocero. Correo: {$vocero->usuario->correo} | Contraseña: $password",
                        'tipo' => 'credenciales'
                    ]);

                    return back()->with('success', "Se asignó a {$aprendiz->nombres} {$aprendiz->apellidos} como Vocero exitosamente. Credenciales de acceso: Correo: {$vocero->usuario->correo} | Contraseña: $password");
                }
            }

            // Crear nuevo usuario y vocero si no existía
            $password = $this->authService->generarContraseñaAleatoria();
            $correo = 'ap' . $aprendiz->id . '@sena.edu.co';

            $usuario = Usuario::where('correo', $correo)->first();
            if (!$usuario) {
                $usuario = Usuario::create([
                    'correo' => $correo,
                    'nombres' => $aprendiz->nombres . ' ' . $aprendiz->apellidos,
                    'password' => Hash::make($password),
                    'rol_id' => 2,
                    'primer_acceso' => true,
                    'activo' => true
                ]);
            } else {
                $usuario->update([
                    'rol_id' => 2,
                    'activo' => true,
                    'password' => Hash::make($password)
                ]);
            }

            Vocero::create([
                'aprendiz_id' => $aprendiz->id,
                'usuario_id' => $usuario->id,
                'ficha_id' => $ficha->id,
                'activo' => true,
                'fecha_activacion' => now()
            ]);

            $usuario->notificaciones()->create([
                'titulo' => 'Credenciales de Acceso como Vocero',
                'descripcion' => "Has sido asignado como Vocero. Correo: {$usuario->correo} | Contraseña: $password",
                'tipo' => 'credenciales'
            ]);

            return back()->with('success', "Se promovió a {$aprendiz->nombres} {$aprendiz->apellidos} a Vocero. Credenciales de acceso: Correo: {$usuario->correo} | Contraseña: $password");

        } else {
            // Cambiar a Aprendiz regular
            if (!$aprendiz->vocero || !$aprendiz->vocero->activo) {
                return back()->with('info', "El aprendiz {$aprendiz->nombres} {$aprendiz->apellidos} ya es un Aprendiz regular.");
            }

            $vocero = $aprendiz->vocero;
            $vocero->update([
                'activo' => false,
                'fecha_desactivacion' => now()
            ]);

            if ($vocero->usuario) {
                $vocero->usuario->update(['activo' => false]);
            }

            return back()->with('success', "{$aprendiz->nombres} {$aprendiz->apellidos} ahora es Aprendiz regular. Se ha revocado su rol y credenciales de Vocero. Queda 1 cupo disponible para vocero en esta ficha.");
        }
    }

    // Redirección para retrocompatibilidad
    public function showVoceros(Request $request)
    {
        return redirect()->route('admin.aprendices', $request->query());
    }

    // ===== APRENDICES Y VOCEROS (Estructura Jerárquica: Programas -> Fichas -> Aprendices/Voceros) =====
    public function showAprendices(Request $request)
    {
        // Nivel 3: Detalle de una Ficha específica (Aprendices y Voceros)
        if ($request->filled('ficha_id')) {
            $ficha = Ficha::with(['programa'])->findOrFail($request->ficha_id);
            $vocerosActivos = Vocero::where('ficha_id', $ficha->id)
                ->where('activo', true)
                ->with(['usuario', 'aprendiz'])
                ->get();
            $vocerosCount = $vocerosActivos->count();

            $query = Aprendiz::where('ficha_id', $ficha->id)->with(['vocero.usuario']);

            if ($request->filled('buscar')) {
                $b = $request->buscar;
                $query->where(function($q) use ($b) {
                    $q->where('nombres', 'like', "%$b%")
                      ->orWhere('apellidos', 'like', "%$b%")
                      ->orWhere('documento', 'like', "%$b%");
                });
            }

            if ($request->filled('rol')) {
                if ($request->rol === 'vocero') {
                    $query->whereHas('vocero', function($q) {
                        $q->where('activo', true);
                    });
                } elseif ($request->rol === 'aprendiz') {
                    $query->where(function($q) {
                        $q->whereDoesntHave('vocero')
                          ->orWhereHas('vocero', function($q2) {
                              $q2->where('activo', false);
                          });
                    });
                }
            }

            $aprendices = $query->paginate(25)->appends($request->query());
            $totalAprendicesFicha = Aprendiz::where('ficha_id', $ficha->id)->count();

            return view('admin.aprendices.index', [
                'nivel' => 'ficha',
                'ficha' => $ficha,
                'programa' => $ficha->programa,
                'aprendices' => $aprendices,
                'vocerosActivos' => $vocerosActivos,
                'vocerosCount' => $vocerosCount,
                'totalAprendicesFicha' => $totalAprendicesFicha
            ]);
        }

        // Nivel 2: Fichas de un Programa específico
        if ($request->filled('programa_id')) {
            $programa = Programa::with([
                'fichas.aprendices',
                'fichas.voceros' => function($q) { $q->where('activo', true); }
            ])->findOrFail($request->programa_id);

            return view('admin.aprendices.index', [
                'nivel' => 'fichas',
                'programa' => $programa,
                'fichas' => $programa->fichas
            ]);
        }

        // Modo listado global
        if ($request->query('view') === 'all') {
            $query = Aprendiz::with(['ficha.programa', 'vocero.usuario']);

            if ($request->filled('buscar')) {
                $b = $request->buscar;
                $query->where(function($q) use ($b) {
                    $q->where('nombres', 'like', "%$b%")
                      ->orWhere('apellidos', 'like', "%$b%")
                      ->orWhere('documento', 'like', "%$b%");
                });
            }

            if ($request->filled('rol')) {
                if ($request->rol === 'vocero') {
                    $query->whereHas('vocero', function($q) {
                        $q->where('activo', true);
                    });
                } elseif ($request->rol === 'aprendiz') {
                    $query->where(function($q) {
                        $q->whereDoesntHave('vocero')
                          ->orWhereHas('vocero', function($q2) {
                              $q2->where('activo', false);
                          });
                    });
                }
            }

            $aprendices = $query->paginate(25)->appends($request->query());

            return view('admin.aprendices.index', [
                'nivel' => 'todos',
                'aprendices' => $aprendices
            ]);
        }

        // Nivel 1: Programas de Formación
        $programas = Programa::with([
            'fichas.aprendices',
            'fichas.voceros' => function($q) { $q->where('activo', true); }
        ])->get();

        return view('admin.aprendices.index', [
            'nivel' => 'programas',
            'programas' => $programas
        ]);
    }

    // ===== GRUPOS (Estructura Jerárquica: Programas -> Fichas -> Grupos) =====
    public function showGrupos(Request $request)
    {
        $fichas = Ficha::where('activo', true)->orderBy('numero_ficha')->get();

        // Nivel 3: Detalle de Grupos de una Ficha específica
        if ($request->filled('ficha_id')) {
            $ficha = Ficha::with(['programa', 'asignaciones.modulo'])->findOrFail($request->ficha_id);

            $query = Grupo::where('activo', true)
                ->where(function ($q) use ($ficha) {
                    $q->whereHas('vocero', fn($sq) => $sq->where('ficha_id', $ficha->id))
                      ->orWhereHas('asignacion', fn($sq) => $sq->where('ficha_id', $ficha->id));
                })
                ->with(['vocero.aprendiz', 'integrantes', 'asignacion.modulo', 'turnos'])
                ->orderBy('numero_grupo', 'asc');

            if ($request->filled('buscar')) {
                $b = $request->buscar;
                $query->where('nombre', 'like', "%$b%");
            }

            $totalGruposFicha = $ficha->contarGruposActivos();
            $grupos = $query->paginate(12)->appends($request->query());

            return view('admin.grupos.index', [
                'nivel' => 'ficha',
                'ficha' => $ficha,
                'programa' => $ficha->programa,
                'grupos' => $grupos,
                'totalGruposFicha' => $totalGruposFicha,
                'fichas' => $fichas,
                'fichaSeleccionada' => $ficha
            ]);
        }

        // Nivel 2: Fichas de un Programa específico
        if ($request->filled('programa_id')) {
            $programa = Programa::with([
                'fichas.asignaciones.modulo',
                'fichas.voceros.grupos' => fn($q) => $q->where('activo', true)
            ])->findOrFail($request->programa_id);

            return view('admin.grupos.index', [
                'nivel' => 'fichas',
                'programa' => $programa,
                'fichas' => $programa->fichas,
                'fichasTodas' => $fichas
            ]);
        }

        // Modo Listado Global
        if ($request->query('view') === 'all') {
            $query = Grupo::with(['vocero.aprendiz', 'vocero.ficha.programa', 'integrantes', 'asignacion.modulo'])
                ->orderBy('id', 'asc');

            if ($request->filled('buscar')) {
                $b = $request->buscar;
                $query->where(function ($q) use ($b) {
                    $q->where('nombre', 'like', "%$b%")
                      ->orWhereHas('vocero.ficha', fn($sq) => $sq->where('numero_ficha', 'like', "%$b%"))
                      ->orWhereHas('vocero.aprendiz', fn($sq) => $sq->where('nombres', 'like', "%$b%")->orWhere('apellidos', 'like', "%$b%"))
                      ->orWhereHas('asignacion.modulo', fn($sq) => $sq->where('nombre', 'like', "%$b%"));
                });
            }

            $grupos = $query->paginate(25)->appends($request->query());

            return view('admin.grupos.index', [
                'nivel' => 'todos',
                'grupos' => $grupos,
                'fichas' => $fichas
            ]);
        }

        // Nivel 1: Programas (Por defecto)
        $programas = Programa::where('activo', true)->with([
            'fichas.asignaciones.modulo',
            'fichas.voceros.grupos' => fn($q) => $q->where('activo', true)
        ])->get();

        $totalGruposSistema = Grupo::where('activo', true)->count();
        $totalFichas = Ficha::where('activo', true)->count();

        return view('admin.grupos.index', [
            'nivel' => 'programas',
            'programas' => $programas,
            'totalGruposSistema' => $totalGruposSistema,
            'totalFichas' => $totalFichas,
            'fichas' => $fichas
        ]);
    }

    // ===== EVIDENCIAS (Estructura Jerárquica: Programas -> Fichas -> Evidencias) =====
    public function showEvidencias(Request $request)
    {
        $fichas = Ficha::where('activo', true)->orderBy('numero_ficha')->get();

        // Nivel 3: Detalle de Evidencias de una Ficha específica
        if ($request->filled('ficha_id')) {
            $ficha = Ficha::with(['programa', 'asignaciones.modulo'])->findOrFail($request->ficha_id);

            // Turnos de la ficha estructurados para el calendario de limpiezas y evidencias
            $turnosFicha = Turno::whereHas('asignacion', function ($q) use ($ficha) {
                $q->where('ficha_id', $ficha->id);
            })->with([
                'asignacion.modulo',
                'grupo.integrantes',
                'grupo.vocero.aprendiz',
                'evidencias'
            ])->orderBy('fecha_turno', 'asc')->get();

            $hoy = now()->toDateString();

            $eventosCalendario = $turnosFicha->map(function ($turno) use ($hoy) {
                $fecha = $turno->fecha_turno->toDateString();
                $tieneEvidencias = $turno->evidencias->count() > 0;
                $esPasado = $fecha < $hoy;
                $esHoy = $fecha === $hoy;
                $esFuturo = $fecha > $hoy;

                // Estados de color solicitados:
                // 1. Verde: si la ficha SÍ subió evidencias
                // 2. Rojo: días de limpieza anteriores donde la ficha NO subió evidencias
                // 3. Gris: futuras limpiezas y si la limpieza es hoy y no han subido nada aún
                if ($tieneEvidencias) {
                    $color = 'verde';
                    $estadoTexto = 'Evidencia entregada';
                    $mensajeDetalle = 'Evidencias entregadas correctamente';
                } elseif ($esPasado) {
                    $color = 'rojo';
                    $estadoTexto = 'Vencida sin evidencia';
                    $mensajeDetalle = 'No subieron evidencias';
                } else {
                    $color = 'gris';
                    $estadoTexto = 'En espera de evidencia';
                    $mensajeDetalle = 'En espera de subida de evidencias';
                }

                $evidenciasFormateadas = $turno->evidencias->map(function ($ev) {
                    return [
                        'id' => $ev->id,
                        'tipo' => $ev->tipo,
                        'ruta_archivo' => $ev->ruta_archivo ? asset('storage/' . $ev->ruta_archivo) : null,
                        'observaciones' => $ev->observaciones,
                        'fecha_subida' => $ev->created_at ? $ev->created_at->format('d/m/Y H:i') : null,
                    ];
                });

                $integrantes = $turno->grupo && $turno->grupo->integrantes ? $turno->grupo->integrantes->map(function ($int) {
                    return $int->nombres . ' ' . $int->apellidos;
                }) : [];

                return [
                    'id' => $turno->id,
                    'fecha' => $fecha,
                    'modulo_nombre' => $turno->asignacion?->modulo?->nombre ?? 'Módulo General',
                    'modulo_ubicacion' => $turno->asignacion?->modulo?->ubicacion ?? 'Centro de Formación',
                    'grupo_nombre' => $turno->grupo?->nombre ?? 'Sin grupo asignado',
                    'tiene_grupo' => (bool)$turno->grupo_id,
                    'integrantes' => $integrantes,
                    'vocero_nombre' => $turno->grupo?->vocero?->aprendiz ? ($turno->grupo->vocero->aprendiz->nombres . ' ' . $turno->grupo->vocero->aprendiz->apellidos) : null,
                    'estado_bd' => $turno->estado,
                    'color' => $color,
                    'estado_texto' => $estadoTexto,
                    'mensaje_detalle' => $mensajeDetalle,
                    'tiene_evidencias' => $tieneEvidencias,
                    'num_evidencias' => $turno->evidencias->count(),
                    'evidencias' => $evidenciasFormateadas,
                    'es_hoy' => $esHoy,
                    'es_futuro' => $esFuturo,
                    'es_pasado' => $esPasado
                ];
            });

            $query = Evidencia::whereHas('turno.asignacion', function ($q) use ($ficha) {
                $q->where('ficha_id', $ficha->id);
            })->with([
                'turno.asignacion.modulo',
                'turno.asignacion.ficha.programa',
                'turno.grupo.vocero.aprendiz',
                'integrantes.aprendiz'
            ])->orderBy('id', 'desc');

            if ($request->filled('tipo') && in_array($request->tipo, ['antes', 'despues'])) {
                $query->where('tipo', $request->tipo);
            }

            $totalEvidenciasFicha = Evidencia::whereHas('turno.asignacion', function ($q) use ($ficha) {
                $q->where('ficha_id', $ficha->id);
            })->count();

            $evidencias = $query->paginate(12)->appends($request->query());

            return view('admin.evidencias.index', [
                'nivel' => 'ficha',
                'ficha' => $ficha,
                'programa' => $ficha->programa,
                'evidencias' => $evidencias,
                'totalEvidenciasFicha' => $totalEvidenciasFicha,
                'eventosCalendario' => $eventosCalendario,
                'fichas' => $fichas,
                'fichaSeleccionada' => $ficha
            ]);
        }

        // Nivel 2: Fichas de un Programa específico
        if ($request->filled('programa_id')) {
            $programa = Programa::with([
                'fichas.asignaciones.modulo',
                'fichas.asignaciones.turnos.evidencias'
            ])->findOrFail($request->programa_id);

            return view('admin.evidencias.index', [
                'nivel' => 'fichas',
                'programa' => $programa,
                'fichas' => $programa->fichas,
                'fichasTodas' => $fichas
            ]);
        }

        // Modo Listado Global
        if ($request->query('view') === 'all') {
            $query = Evidencia::with([
                'turno.asignacion.ficha.programa',
                'turno.asignacion.modulo',
                'turno.grupo.vocero.aprendiz',
                'integrantes.aprendiz'
            ])->orderBy('id', 'desc');

            if ($request->filled('tipo') && in_array($request->tipo, ['antes', 'despues'])) {
                $query->where('tipo', $request->tipo);
            }

            if ($request->filled('buscar')) {
                $b = $request->buscar;
                $query->where(function ($q) use ($b) {
                    $q->whereHas('turno.asignacion.ficha', function ($sq) use ($b) {
                        $sq->where('numero_ficha', 'like', "%$b%")
                          ->orWhere('nombre', 'like', "%$b%");
                    })->orWhereHas('turno.asignacion.modulo', function ($sq) use ($b) {
                        $sq->where('nombre', 'like', "%$b%");
                    });
                });
            }

            $evidencias = $query->paginate(15)->appends($request->query());

            return view('admin.evidencias.index', [
                'nivel' => 'todos',
                'evidencias' => $evidencias,
                'fichas' => $fichas
            ]);
        }

        // Nivel 1: Programas (Por defecto)
        $programas = Programa::where('activo', true)->with([
            'fichas.asignaciones.modulo',
            'fichas.asignaciones.turnos.evidencias'
        ])->get();

        $totalEvidenciasSistema = Evidencia::count();
        $totalFichas = Ficha::where('activo', true)->count();

        return view('admin.evidencias.index', [
            'nivel' => 'programas',
            'programas' => $programas,
            'totalEvidenciasSistema' => $totalEvidenciasSistema,
            'totalFichas' => $totalFichas,
            'fichas' => $fichas
        ]);
    }

    public function getEvidenciaTurno(Request $request)
    {
        $turno = Turno::find($request->turno_id);
        if (!$turno) {
            return response()->json(['error' => 'Turno no encontrado'], 404);
        }

        $evidencias = Evidencia::obtenerParTurno($turno->id);
        return response()->json($evidencias);
    }

    // ===== REPORTES DE CUMPLIMIENTO TRIMESTRAL (CADA 3 MESES) =====
    public function showReportes(Request $request)
    {
        $anoActual = Carbon::now()->year;
        $trimestreActual = $this->reporteService->getTrimestreActual();

        $anoSeleccionado = (int) $request->input('ano', $anoActual);
        $trimestreSeleccionado = (int) $request->input('trimestre', $trimestreActual);
        $fichaId = $request->filled('ficha_id') ? (int) $request->ficha_id : null;
        $programaId = $request->filled('programa_id') ? (int) $request->programa_id : null;

        $reporte = $this->reporteService->generarReporteTrimestral(
            $anoSeleccionado,
            $trimestreSeleccionado,
            $fichaId,
            $programaId
        );

        $comparativaTrimestres = $this->reporteService->obtenerComparativaTrimestres($anoSeleccionado);

        $fichas = Ficha::where('activo', true)->orderBy('numero_ficha')->get();
        $programas = Programa::where('activo', true)->orderBy('nombre')->get();

        return view('admin.reportes.index', compact(
            'reporte',
            'comparativaTrimestres',
            'anoSeleccionado',
            'trimestreSeleccionado',
            'fichaId',
            'programaId',
            'fichas',
            'programas'
        ));
    }

    public function exportarReporteCsv(Request $request)
    {
        $anoActual = Carbon::now()->year;
        $trimestreActual = $this->reporteService->getTrimestreActual();

        $ano = (int) $request->input('ano', $anoActual);
        $trimestre = (int) $request->input('trimestre', $trimestreActual);
        $fichaId = $request->filled('ficha_id') ? (int) $request->ficha_id : null;
        $programaId = $request->filled('programa_id') ? (int) $request->programa_id : null;

        $reporte = $this->reporteService->generarReporteTrimestral($ano, $trimestre, $fichaId, $programaId);

        $filename = "reporte_cumplimiento_T{$trimestre}_{$ano}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($reporte) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 para Excel
            fputs($handle, "\xEF\xBB\xBF");

            // Encabezado general
            fputcsv($handle, ['SENA - GestiLimpieza: Sistema de Control de Limpieza']);
            fputcsv($handle, ['Reporte de Cumplimiento Trimestral']);
            fputcsv($handle, ['Periodo:', $reporte['info_trimestre']['nombre'] . ' ' . $reporte['ano']]);
            fputcsv($handle, ['Rango de Fechas:', $reporte['fecha_inicio'] . ' al ' . $reporte['fecha_fin']]);
            fputcsv($handle, ['Tasa de Cumplimiento Global:', $reporte['tasa_cumplimiento'] . '%']);
            fputcsv($handle, ['Nivel de Desempeño:', $reporte['desempeno']]);
            fputcsv($handle, ['Turnos Cumplidos:', $reporte['turnos_cumplidos']]);
            fputcsv($handle, ['Turnos Incumplidos:', $reporte['turnos_incumplidos']]);
            fputcsv($handle, ['Turnos Pendientes:', $reporte['turnos_pendientes']]);
            fputcsv($handle, ['Total Evidencias Subidas:', $reporte['total_evidencias']]);
            fputcsv($handle, []);

            // Desglose por Ficha
            fputcsv($handle, ['DESGLOSE DE CUMPLIMIENTO POR FICHA ACADÉMICA']);
            fputcsv($handle, ['Ficha', 'Programa', 'Jornada', 'Módulos Asignados', 'Turnos Totales', 'Cumplidos', 'Incumplidos', 'Pendientes', '% Cumplimiento', 'Nivel de Desempeño']);
            foreach ($reporte['desglose_fichas'] as $f) {
                fputcsv($handle, [
                    $f['numero_ficha'],
                    $f['programa'],
                    $f['jornada'],
                    implode(', ', $f['modulos']),
                    $f['total_turnos'],
                    $f['cumplidos'],
                    $f['incumplidos'],
                    $f['pendientes'],
                    $f['tasa'] . '%',
                    $f['estado']
                ]);
            }

            fputcsv($handle, []);
            // Desglose por Módulo
            fputcsv($handle, ['DESGLOSE DE CUMPLIMIENTO POR MÓDULO DE LIMPIEZA']);
            fputcsv($handle, ['Módulo', 'Ubicación', 'Capacidad', 'Turnos Totales', 'Cumplidos', 'Incumplidos', 'Pendientes', '% Cumplimiento']);
            foreach ($reporte['desglose_modulos'] as $m) {
                fputcsv($handle, [
                    $m['nombre'],
                    $m['ubicacion'],
                    $m['capacidad'],
                    $m['total_turnos'],
                    $m['cumplidos'],
                    $m['incumplidos'],
                    $m['pendientes'],
                    $m['tasa'] . '%'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ===== NOTIFICACIONES =====
    public function showNotificaciones()
    {
        $notificaciones = auth()->user()->notificaciones()->orderBy('created_at', 'desc')->get();
        return view('admin.notificaciones', compact('notificaciones'));
    }

    // ===== SINCRONIZACIÓN =====
    public function showSincronizacion()
    {
        $logs = LogSincronizacion::orderBy('created_at', 'desc')->limit(20)->get();
        return view('admin.sincronizacion', compact('logs'));
    }
}
