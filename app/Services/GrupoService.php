<?php

namespace App\Services;

use App\Models\Grupo;
use App\Models\Aprendiz;
use App\Models\Turno;
use Exception;

class GrupoService
{
    public function crearGrupo(int $idVocero, array $idAprendices, int $idAsignacion): Grupo
    {
        if (!$this->validarAprendicesDisponibles($idVocero, $idAprendices)) {
            throw new Exception('Algunos aprendices no están disponibles');
        }

        $vocero = \App\Models\Vocero::find($idVocero);
        $asignacionesFicha = \App\Models\Asignacion::where('ficha_id', $vocero->ficha_id)
                                                  ->where('activo', true)
                                                  ->with('modulo')
                                                  ->get();

        $capacidadMaxima = $asignacionesFicha->min('modulo.capacidad') ?? 25;
        if (count($idAprendices) > $capacidadMaxima) {
            throw new Exception("El módulo tiene una capacidad máxima de {$capacidadMaxima} persona(s). No puedes asignar " . count($idAprendices) . " aprendices a este grupo.");
        }

        $nombre = Grupo::proximoNombreGrupo($idVocero);
        $proximaFecha = Turno::proximaFechaLibre($idAsignacion);

        if (!$proximaFecha) {
            throw new Exception('No hay turnos disponibles');
        }

        $grupo = Grupo::create([
            'vocero_id' => $idVocero,
            'asignacion_id' => $idAsignacion,
            'nombre' => $nombre,
            'fecha_limpieza' => $proximaFecha->toDateString(),
            'numero_grupo' => $this->proximoNumeroGrupo($idVocero),
            'activo' => true
        ]);

        // Asignar integrantes
        foreach ($idAprendices as $idAprendiz) {
            $grupo->integrantes()->attach($idAprendiz);
        }

        // Asignar turno a todos los módulos activos de la ficha en esa fecha
        $idsAsignaciones = $asignacionesFicha->pluck('id')->push($idAsignacion)->unique();
        Turno::whereIn('asignacion_id', $idsAsignaciones)
             ->where('fecha_turno', $proximaFecha->toDateString())
             ->update(['grupo_id' => $grupo->id]);

        return $grupo;
    }

    public function editarGrupo(int $idGrupo, array $idAprendices): void
    {
        $grupo = Grupo::with('vocero')->find($idGrupo);
        if (!$grupo) return;

        $asignacionesFicha = \App\Models\Asignacion::where('ficha_id', $grupo->vocero->ficha_id)
                                                  ->where('activo', true)
                                                  ->with('modulo')
                                                  ->get();
        $capacidadMaxima = $asignacionesFicha->min('modulo.capacidad') ?? 25;
        if (count($idAprendices) > $capacidadMaxima) {
            throw new Exception("El módulo tiene una capacidad máxima de {$capacidadMaxima} persona(s). No puedes asignar " . count($idAprendices) . " aprendices a este grupo.");
        }

        // Validar que los aprendices no pertenezcan a OTRO grupo activo
        foreach ($idAprendices as $idAprendiz) {
            $aprendiz = Aprendiz::find($idAprendiz);
            if (!$aprendiz || $aprendiz->ficha_id !== $grupo->vocero->ficha_id || !$aprendiz->activo) {
                throw new Exception("El aprendiz seleccionado no es válido.");
            }
            $enOtroGrupo = $aprendiz->grupos()
                ->where('grupos.activo', true)
                ->where('grupos.id', '!=', $idGrupo)
                ->exists();
            if ($enOtroGrupo) {
                throw new Exception("El aprendiz {$aprendiz->nombres} {$aprendiz->apellidos} ya pertenece a otro grupo de limpieza activo.");
            }
        }

        // Desasociar integrantes anteriores
        $grupo->integrantes()->detach();

        // Asociar nuevos integrantes
        foreach ($idAprendices as $idAprendiz) {
            $grupo->integrantes()->attach($idAprendiz);
        }

        // Si la fecha del turno ya pasó, reasignar al siguiente turno libre
        if ($grupo->fecha_limpieza && now()->toDateString() > $grupo->fecha_limpieza->toDateString()) {
            $proximaFecha = Turno::proximaFechaLibre($grupo->asignacion_id);
            if ($proximaFecha) {
                $idsAsignaciones = $asignacionesFicha->pluck('id')->push($grupo->asignacion_id)->unique();
                Turno::whereIn('asignacion_id', $idsAsignaciones)
                     ->where('fecha_turno', $proximaFecha->toDateString())
                     ->update(['grupo_id' => $grupo->id]);
                $grupo->update(['fecha_limpieza' => $proximaFecha->toDateString()]);
            }
        }
    }

    public function proximoNombreGrupo(int $idVocero): string
    {
        return Grupo::proximoNombreGrupo($idVocero);
    }

    public function proximoNumeroGrupo(int $idVocero): int
    {
        $ultimoGrupo = Grupo::where('vocero_id', $idVocero)
                            ->where('activo', true)
                            ->orderBy('numero_grupo', 'desc')
                            ->first();
        
        return ($ultimoGrupo ? $ultimoGrupo->numero_grupo : 0) + 1;
    }

    public function renumerarGrupos(int $idVocero): void
    {
        $gruposActivos = Grupo::where('vocero_id', $idVocero)
                              ->where('activo', true)
                              ->orderBy('numero_grupo', 'asc')
                              ->get();

        foreach ($gruposActivos as $index => $grupo) {
            $grupo->update(['numero_grupo' => $index + 1]);
        }
    }

    public function validarAprendicesDisponibles(int $idVocero, array $idAprendices): bool
    {
        $vocero = \App\Models\Vocero::find($idVocero);
        if (!$vocero) return false;

        foreach ($idAprendices as $idAprendiz) {
            $aprendiz = Aprendiz::find($idAprendiz);
            if (!$aprendiz || $aprendiz->ficha_id !== $vocero->ficha_id || !$aprendiz->activo) {
                return false;
            }
            if ($aprendiz->grupos()->where('grupos.activo', true)->exists()) {
                return false;
            }
        }

        return true;
    }
}
