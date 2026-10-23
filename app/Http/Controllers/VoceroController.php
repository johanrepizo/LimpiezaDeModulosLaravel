<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Aprendiz;
use App\Models\Turno;
use App\Models\Evidencia;
use App\Models\Vocero;
use App\Models\Asignacion;
use App\Services\GrupoService;
use App\Services\EvidenciaService;
use App\Services\TurnoService;
use App\Http\Requests\GuardarGrupoRequest;
use App\Http\Requests\SubirEvidenciaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoceroController extends Controller
{
    protected $grupoService;
    protected $evidenciaService;
    protected $turnoService;

    public function __construct(GrupoService $grupoService, EvidenciaService $evidenciaService, TurnoService $turnoService)
    {
        $this->grupoService = $grupoService;
        $this->evidenciaService = $evidenciaService;
        $this->turnoService = $turnoService;
    }

    public function showDashboard()
    {
        $vocero = Auth::user()->vocero;
        if (!$vocero) {
            Auth::logout();
            return redirect('/login')->with('error', 'Tu cuenta tiene rol vocero pero no está asociada a una ficha. Contacta al administrador.');
        }

        Turno::cerrarTurnosVencidos();
        Turno::abrirTurnosHoy();

        $turnosHoy = Turno::turnosActivosHoy($vocero->ficha_id);
        $turnoHoy = $turnosHoy->first();
        $proximosTurnos = Turno::proximosFicha($vocero->ficha_id, 5);
        $historialTurnos = Turno::historialFicha($vocero->ficha_id, 15);
        $notificacionesNoLeidas = Auth::user()->notificaciones()
                                               ->where('leida', false)
                                               ->count();
        $gruposCount = Grupo::where('vocero_id', $vocero->id)->where('activo', true)->count();
        $aprendicesCount = Aprendiz::where('ficha_id', $vocero->ficha_id)->where('activo', true)->count();
        $evidenciasCount = Evidencia::whereHas('turno', function ($q) use ($vocero) {
            $q->whereHas('asignacion', function ($q2) use ($vocero) {
                $q2->where('ficha_id', $vocero->ficha_id);
            });
        })->count();

        return view('vocero.dashboard', compact(
            'vocero',
            'turnoHoy',
            'turnosHoy',
            'proximosTurnos',
            'historialTurnos',
            'notificacionesNoLeidas',
            'gruposCount',
            'aprendicesCount',
            'evidenciasCount'
        ));
    }

    // ===== GRUPOS =====
    public function guardarGrupo(GuardarGrupoRequest $request)
    {
        $vocero = Auth::user()->vocero;
        if (!$vocero) {
            return back()->with('error', 'Vocero no encontrado');
        }

        $asignacionId = $request->asignacion_id;
        if (!$asignacionId && $vocero->ficha) {
            $primeraAsig = $vocero->ficha->asignaciones()->where('activo', true)->first();
            $asignacionId = $primeraAsig?->id;
        }

        try {
            $grupo = $this->grupoService->crearGrupo(
                $vocero->id,
                $request->aprendices,
                $asignacionId
            );

            $grupo->registrarHistorial('Grupo creado', Auth::user()->id, 'Creación');

            return back()->with('success', 'Grupo creado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function editarGrupo(Request $request)
    {
        $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
            'aprendices' => 'required|array|min:1'
        ]);

        $grupo = Grupo::find($request->grupo_id);
        if (!$grupo || $grupo->vocero_id !== Auth::user()->vocero->id) {
            return back()->with('error', 'Grupo no encontrado');
        }

        try {
            $this->grupoService->editarGrupo($request->grupo_id, $request->aprendices);
            $grupo->registrarHistorial('Grupo editado', Auth::user()->id, 'Edición');
            return back()->with('success', 'Grupo actualizado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function eliminarGrupo(Request $request)
    {
        $request->validate(['grupo_id' => 'required|exists:grupos,id']);

        $grupo = Grupo::find($request->grupo_id);
        if (!$grupo || $grupo->vocero_id !== Auth::user()->vocero->id) {
            return back()->with('error', 'Grupo no encontrado');
        }

        $grupo->update(['activo' => false]);
        $grupo->registrarHistorial('Grupo eliminado', Auth::user()->id, 'Eliminación');

        // Liberar turnos pendientes que tuvieran asignado este grupo
        Turno::where('grupo_id', $grupo->id)
             ->where('estado', '!=', 'Cumplido')
             ->update(['grupo_id' => null]);
        
        $grupo->renumerarGrupos(); // Renumerar grupos restantes

        return back()->with('success', 'Grupo eliminado correctamente');
    }

    public function showGrupos()
    {
        $vocero = Auth::user()->vocero;
        if (!$vocero) {
            return redirect('/vocero')->with('error', 'Vocero no encontrado');
        }
        $grupos = Grupo::where('vocero_id', $vocero->id)
                       ->where('activo', true)
                       ->with('integrantes')
                       ->get();

        $asignacionesFicha = Asignacion::where('ficha_id', $vocero->ficha_id)
                                       ->where('activo', true)
                                       ->with('modulo')
                                       ->get();

        $capacidadMinima = $asignacionesFicha->min('modulo.capacidad') ?? 25;

        return view('vocero.grupos.index', compact('grupos', 'asignacionesFicha', 'capacidadMinima'));
    }

    // ===== EVIDENCIAS =====
    public function subirEvidenciaTurno(Request $request)
    {
        $vocero = Auth::user()->vocero;
        if (!$vocero) {
            return back()->with('error', 'Vocero no encontrado');
        }

        $turnosHoy = Turno::turnosActivosHoy($vocero->ficha_id);
        $esDobleModulo = $turnosHoy->count() >= 2;

        if ($turnosHoy->isEmpty()) {
            return back()->with('error', 'No hay turnos disponibles para certificar el día de hoy');
        }

        try {
            if ($esDobleModulo) {
                // Validación para 2 módulos asignados (4 fotos en total)
                $request->validate([
                    'foto_antes_1' => 'required|image|mimes:jpg,jpeg,png|max:10240',
                    'foto_despues_1' => 'required|image|mimes:jpg,jpeg,png|max:10240',
                    'foto_antes_2' => 'required|image|mimes:jpg,jpeg,png|max:10240',
                    'foto_despues_2' => 'required|image|mimes:jpg,jpeg,png|max:10240',
                    'observaciones' => 'nullable|string'
                ], [
                    'foto_antes_1.required' => 'La foto ANTES del primer módulo es requerida',
                    'foto_despues_1.required' => 'La foto DESPUÉS del primer módulo es requerida',
                    'foto_antes_2.required' => 'La foto ANTES del segundo módulo es requerida',
                    'foto_despues_2.required' => 'La foto DESPUÉS del segundo módulo es requerida',
                ]);

                $turno1 = $turnosHoy[0];
                $turno2 = $turnosHoy[1];

                // Evidencias Módulo 1
                $rutaAntes1 = $this->evidenciaService->procesarUpload($request->file('foto_antes_1'), $turno1->id, 'antes');
                $rutaDespues1 = $this->evidenciaService->procesarUpload($request->file('foto_despues_1'), $turno1->id, 'despues');
                $ev1Antes = $this->evidenciaService->guardarEvidencia($turno1->id, 'antes', $rutaAntes1, $request->observaciones);
                $ev1Desp = $this->evidenciaService->guardarEvidencia($turno1->id, 'despues', $rutaDespues1, $request->observaciones);
                $this->evidenciaService->guardarSnapshotIntegrantes($ev1Antes->id, $turno1->id);
                $this->evidenciaService->guardarSnapshotIntegrantes($ev1Desp->id, $turno1->id);
                $turno1->marcarCumplido();

                // Evidencias Módulo 2
                $rutaAntes2 = $this->evidenciaService->procesarUpload($request->file('foto_antes_2'), $turno2->id, 'antes');
                $rutaDespues2 = $this->evidenciaService->procesarUpload($request->file('foto_despues_2'), $turno2->id, 'despues');
                $ev2Antes = $this->evidenciaService->guardarEvidencia($turno2->id, 'antes', $rutaAntes2, $request->observaciones);
                $ev2Desp = $this->evidenciaService->guardarEvidencia($turno2->id, 'despues', $rutaDespues2, $request->observaciones);
                $this->evidenciaService->guardarSnapshotIntegrantes($ev2Antes->id, $turno2->id);
                $this->evidenciaService->guardarSnapshotIntegrantes($ev2Desp->id, $turno2->id);
                $turno2->marcarCumplido();

                // Avanzar grupo para la ficha en la siguiente fecha de limpieza
                $grupoId = $turno1->grupo_id ?? $turno2->grupo_id;
                if ($grupoId) {
                    $this->turnoService->avanzarTurnoGrupo($turno1->id, $grupoId);
                }

                return back()->with('success', '¡Certificación completada! Se han subido exitosamente las 4 evidencias fotográficas correspondientes a los 2 módulos asignados.');
            } else {
                // Caso estándar 1 módulo asignado (2 fotos)
                $request->validate([
                    'foto_antes' => 'required|image|mimes:jpg,jpeg,png|max:10240',
                    'foto_despues' => 'required|image|mimes:jpg,jpeg,png|max:10240',
                    'observaciones' => 'nullable|string'
                ], [
                    'foto_antes.required' => 'La foto ANTES es requerida',
                    'foto_despues.required' => 'La foto DESPUÉS es requerida',
                ]);

                $turno = $turnosHoy->first();
                $rutaAntes = $this->evidenciaService->procesarUpload($request->file('foto_antes'), $turno->id, 'antes');
                $rutaDespues = $this->evidenciaService->procesarUpload($request->file('foto_despues'), $turno->id, 'despues');
                $evAntes = $this->evidenciaService->guardarEvidencia($turno->id, 'antes', $rutaAntes, $request->observaciones);
                $evDesp = $this->evidenciaService->guardarEvidencia($turno->id, 'despues', $rutaDespues, $request->observaciones);
                $this->evidenciaService->guardarSnapshotIntegrantes($evAntes->id, $turno->id);
                $this->evidenciaService->guardarSnapshotIntegrantes($evDesp->id, $turno->id);
                $turno->marcarCumplido();

                if ($turno->grupo) {
                    $this->turnoService->avanzarTurnoGrupo($turno->id, $turno->grupo->id);
                }

                return back()->with('success', 'Evidencias subidas correctamente. Turno de limpieza certificado.');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function showEvidencias()
    {
        $vocero = Auth::user()->vocero;
        if (!$vocero) {
            return redirect('/login')->with('error', 'Vocero no encontrado');
        }

        $ficha = $vocero->ficha;

        // Turnos estructurados para el calendario del vocero
        $turnosFicha = Turno::whereHas('asignacion', function ($q) use ($vocero) {
            $q->where('ficha_id', $vocero->ficha_id);
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

        $evidencias = Evidencia::whereHas('turno', function ($q) use ($vocero) {
            $q->whereHas('asignacion', function ($q2) use ($vocero) {
                $q2->where('ficha_id', $vocero->ficha_id);
            });
        })->orderBy('id', 'desc')->paginate(15);

        return view('vocero.evidencias', compact('evidencias', 'eventosCalendario', 'ficha'));
    }

    public function showSubirEvidencia()
    {
        $vocero = Auth::user()->vocero;
        if (!$vocero) {
            return redirect('/login')->with('error', 'Vocero no encontrado');
        }

        $turnosHoy = Turno::turnosActivosHoy($vocero->ficha_id);
        $esDobleModulo = $turnosHoy->count() >= 2;
        $turnoHoy = $turnosHoy->first();

        $evidenciasRecientes = Evidencia::whereHas('turno', function ($q) use ($vocero) {
            $q->whereHas('asignacion', function ($q2) use ($vocero) {
                $q2->where('ficha_id', $vocero->ficha_id);
            });
        })->latest('id')->take(6)->get();

        return view('vocero.subir_evidencia', compact('turnosHoy', 'esDobleModulo', 'turnoHoy', 'evidenciasRecientes'));
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

    // ===== APRENDICES =====
    public function showAprendices()
    {
        $vocero = Auth::user()->vocero;
        $aprendices = Aprendiz::where('ficha_id', $vocero->ficha_id)
                               ->where('activo', true)
                               ->get();
        return view('vocero.aprendices', compact('aprendices'));
    }

    public function getIntegrantesIds(Request $request)
    {
        $vocero = Auth::user()->vocero;
        if (!$vocero) {
            return response()->json([]);
        }

        $aprendices = Aprendiz::where('ficha_id', $vocero->ficha_id)
                               ->where('activo', true)
                               ->with(['grupos' => function ($q) {
                                   $q->where('grupos.activo', true);
                               }])
                               ->orderBy('nombres', 'asc')
                               ->get();

        $data = $aprendices->map(function ($ap) {
            $grupoActivo = $ap->grupos->first();
            return [
                'id' => $ap->id,
                'nombres' => $ap->nombres,
                'apellidos' => $ap->apellidos,
                'tiene_grupo' => (bool)$grupoActivo,
                'grupo_id' => $grupoActivo ? $grupoActivo->id : null,
                'grupo_nombre' => $grupoActivo ? ($grupoActivo->nombre ?? ('Grupo #' . $grupoActivo->numero_grupo)) : null,
            ];
        });

        return response()->json($data);
    }

    // ===== NOTIFICACIONES =====
    public function showNotificaciones()
    {
        $notificaciones = Auth::user()->notificaciones()->orderBy('created_at', 'desc')->get();
        return view('vocero.notificaciones', compact('notificaciones'));
    }
}
