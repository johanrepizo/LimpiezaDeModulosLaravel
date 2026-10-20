<?php

namespace App\Services;

use App\Models\Turno;
use App\Models\Grupo;
use App\Models\Asignacion;
use Carbon\Carbon;

class TurnoService
{
    public function generarTurnosAsignacion(int $idAsignacion, Carbon $fechaInicio, int $diaSemana): int
    {
        $asignacion = Asignacion::find($idAsignacion);
        if (!$asignacion) return 0;

        $fechaFin = $fechaInicio->copy()->addYears(2);
        $turnosCreados = 0;
        $fecha = $fechaInicio->copy();

        while ($fecha <= $fechaFin) {
            if ($fecha->dayOfWeek == $diaSemana) {
                Turno::create([
                    'asignacion_id' => $idAsignacion,
                    'fecha_turno' => $fecha->toDateString(),
                    'estado' => Turno::ESTADO_PENDIENTE
                ]);
                $turnosCreados++;
            }
            $fecha->addDay();
        }

        return $turnosCreados;
    }

    public function avanzarTurnoGrupo(int $idTurno, int $idGrupo): void
    {
        $turno = Turno::with('asignacion.ficha')->find($idTurno);
        if (!$turno) return;

        $fichaId = $turno->asignacion?->ficha_id;
        $idsAsignaciones = $fichaId
            ? Asignacion::where('ficha_id', $fichaId)->where('activo', true)->pluck('id')->all()
            : [$turno->asignacion_id];

        $proximaFecha = Turno::proximaFechaLibre($turno->asignacion_id);
        if ($proximaFecha) {
            Turno::whereIn('asignacion_id', $idsAsignaciones)
                 ->where('fecha_turno', $proximaFecha->toDateString())
                 ->update(['grupo_id' => $idGrupo]);

            Grupo::find($idGrupo)?->update(['fecha_limpieza' => $proximaFecha->toDateString()]);
        }
    }

    public function proximaFechaLibre(int $idAsignacion): ?Carbon
    {
        return Turno::proximaFechaLibre($idAsignacion);
    }

    public function cerrarTurnosVencidos(): void
    {
        Turno::cerrarTurnosVencidos();
    }

    public function abrirTurnosHoy(): void
    {
        Turno::abrirTurnosHoy();
    }
}
