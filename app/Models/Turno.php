<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class Turno extends Model
{
    protected $table = 'turnos';
    protected $fillable = ['asignacion_id', 'fecha_turno', 'grupo_id', 'estado'];
    protected $casts = [
        'fecha_turno' => 'date',
    ];
    public $timestamps = true;

    const ESTADO_PENDIENTE = 'Pendiente';
    const ESTADO_ABIERTO = 'Abierto';
    const ESTADO_CUMPLIDO = 'Cumplido';
    const ESTADO_INCUMPLIDO = 'Incumplido';

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'asignacion_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function evidencias()
    {
        return $this->hasMany(Evidencia::class, 'turno_id');
    }

    public static function generarTurnosAsignacion(int $idAsignacion): int
    {
        $asignacion = Asignacion::find($idAsignacion);
        if (!$asignacion) return 0;

        $fechaInicio = Carbon::parse($asignacion->fecha_inicio);
        $fechaFin = $fechaInicio->copy()->addYears(2);
        $diaSemana = $asignacion->dia_semana;
        
        $turnosCreados = 0;

        while ($fechaInicio <= $fechaFin) {
            if ($fechaInicio->dayOfWeek == $diaSemana) {
                self::create([
                    'asignacion_id' => $idAsignacion,
                    'fecha_turno' => $fechaInicio->toDateString(),
                    'estado' => self::ESTADO_PENDIENTE
                ]);
                $turnosCreados++;
            }
            $fechaInicio->addDay();
        }

        return $turnosCreados;
    }

    public static function proximaFechaLibre(int $idAsignacion): ?Carbon
    {
        $turno = self::where('asignacion_id', $idAsignacion)
                      ->whereNull('grupo_id')
                      ->orderBy('fecha_turno', 'asc')
                      ->first();
        
        return $turno ? Carbon::parse($turno->fecha_turno) : null;
    }

    public static function asignarGrupoAlTurno(int $idAsignacion, string $fechaTurno, int $idGrupo): void
    {
        self::where('asignacion_id', $idAsignacion)
             ->where('fecha_turno', $fechaTurno)
             ->update(['grupo_id' => $idGrupo]);
    }

    public function avanzarGrupo(int $idGrupo): void
    {
        $proximaFecha = self::proximaFechaLibre($this->asignacion_id);
        if ($proximaFecha) {
            self::asignarGrupoAlTurno($this->asignacion_id, $proximaFecha->toDateString(), $idGrupo);
            Grupo::find($idGrupo)->update(['fecha_limpieza' => $proximaFecha->toDateString()]);
        }
    }

    public function marcarCumplido(): void
    {
        $this->update(['estado' => self::ESTADO_CUMPLIDO]);
    }

    public static function cerrarTurnosVencidos(): void
    {
        self::where('estado', self::ESTADO_ABIERTO)
             ->where('fecha_turno', '<', now()->toDateString())
             ->whereDoesntHave('evidencias')
             ->update(['estado' => self::ESTADO_INCUMPLIDO]);
    }

    public static function abrirTurnosHoy(): void
    {
        self::where('estado', self::ESTADO_PENDIENTE)
             ->where('fecha_turno', now()->toDateString())
             ->update(['estado' => self::ESTADO_ABIERTO]);
    }

    public static function turnosActivosHoy(int $idFicha): Collection
    {
        self::abrirTurnosHoy();

        return self::whereHas('asignacion', function ($q) use ($idFicha) {
                    $q->where('ficha_id', $idFicha)->where('activo', true);
                })
                ->where('estado', self::ESTADO_ABIERTO)
                ->where('fecha_turno', now()->toDateString())
                ->with(['asignacion.modulo', 'grupo.integrantes'])
                ->get();
    }

    public static function turnoActivoHoy(int $idFicha): ?self
    {
        return self::turnosActivosHoy($idFicha)->first();
    }

    public static function historialFicha(int $idFicha, int $limit = 15): Collection
    {
        return self::whereHas('asignacion', function ($q) use ($idFicha) {
                    $q->where('ficha_id', $idFicha);
                })
                ->orderBy('fecha_turno', 'desc')
                ->limit($limit)
                ->get();
    }

    public static function proximosFicha(int $idFicha, int $limit = 5): Collection
    {
        return self::whereHas('asignacion', function ($q) use ($idFicha) {
                    $q->where('ficha_id', $idFicha);
                })
                ->where('fecha_turno', '>=', now()->toDateString())
                ->orderBy('fecha_turno', 'asc')
                ->limit($limit)
                ->get();
    }

    public function tienePar(): bool
    {
        return $this->evidencias()->where('tipo', 'antes')->exists() &&
               $this->evidencias()->where('tipo', 'despues')->exists();
    }
}
