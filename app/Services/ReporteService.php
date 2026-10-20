<?php

namespace App\Services;

use App\Models\Turno;
use App\Models\Ficha;
use App\Models\Modulo;
use App\Models\Programa;
use App\Models\Evidencia;
use Carbon\Carbon;

class ReporteService
{
    /**
     * Mapeo de meses y nombres de cada trimestre.
     */
    const TRIMESTRES = [
        1 => [
            'nombre' => 'Trimestre 1 (Enero - Marzo)',
            'nombre_corto' => 'Trimestre 1',
            'mes_inicio' => 1,
            'mes_fin' => 3,
            'meses' => ['Enero', 'Febrero', 'Marzo']
        ],
        2 => [
            'nombre' => 'Trimestre 2 (Abril - Junio)',
            'nombre_corto' => 'Trimestre 2',
            'mes_inicio' => 4,
            'mes_fin' => 6,
            'meses' => ['Abril', 'Mayo', 'Junio']
        ],
        3 => [
            'nombre' => 'Trimestre 3 (Julio - Septiembre)',
            'nombre_corto' => 'Trimestre 3',
            'mes_inicio' => 7,
            'mes_fin' => 9,
            'meses' => ['Julio', 'Agosto', 'Septiembre']
        ],
        4 => [
            'nombre' => 'Trimestre 4 (Octubre - Diciembre)',
            'nombre_corto' => 'Trimestre 4',
            'mes_inicio' => 10,
            'mes_fin' => 12,
            'meses' => ['Octubre', 'Noviembre', 'Diciembre']
        ],
    ];

    /**
     * Obtiene el trimestre actual (1 a 4).
     */
    public function getTrimestreActual(): int
    {
        $mes = Carbon::now()->month;
        return (int) ceil($mes / 3);
    }

    /**
     * Genera el reporte completo de cumplimiento para un trimestre y año específicos.
     */
    public function generarReporteTrimestral(int $ano, int $trimestre, ?int $fichaId = null, ?int $programaId = null): array
    {
        $infoTrimestre = self::TRIMESTRES[$trimestre] ?? self::TRIMESTRES[1];

        $fechaInicio = Carbon::create($ano, $infoTrimestre['mes_inicio'], 1)->startOfDay();
        $fechaFin = Carbon::create($ano, $infoTrimestre['mes_fin'], 1)->endOfMonth()->endOfDay();

        $query = Turno::whereBetween('fecha_turno', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
            ->with([
                'asignacion.ficha.programa',
                'asignacion.modulo',
                'grupo.vocero.aprendiz',
                'evidencias'
            ]);

        if ($fichaId) {
            $query->whereHas('asignacion', fn($q) => $q->where('ficha_id', $fichaId));
        } elseif ($programaId) {
            $query->whereHas('asignacion.ficha', fn($q) => $q->where('programa_id', $programaId));
        }

        $turnos = $query->get();

        $hoy = Carbon::today()->toDateString();

        // Clasificación de turnos
        $turnosCumplidos = 0;
        $turnosIncumplidos = 0;
        $turnosPendientes = 0;

        foreach ($turnos as $t) {
            $tieneEvidencias = $t->evidencias->count() > 0 || $t->estado === Turno::ESTADO_CUMPLIDO;
            $fechaTurno = $t->fecha_turno->toDateString();

            if ($tieneEvidencias) {
                $turnosCumplidos++;
            } elseif ($fechaTurno < $hoy || $t->estado === Turno::ESTADO_INCUMPLIDO) {
                $turnosIncumplidos++;
            } else {
                $turnosPendientes++;
            }
        }

        $totalTurnos = $turnos->count();
        $turnosEvaluados = $turnosCumplidos + $turnosIncumplidos;
        $tasaCumplimiento = $turnosEvaluados > 0 
            ? round(($turnosCumplidos / $turnosEvaluados) * 100, 1) 
            : ($totalTurnos > 0 ? 0 : 100);

        // Nivel de desempeño
        if ($turnosEvaluados === 0) {
            $desempeno = 'En Curso / Programado';
            $desempenoColor = 'secondary';
        } elseif ($tasaCumplimiento >= 85) {
            $desempeno = 'Excelente';
            $desempenoColor = 'success';
        } elseif ($tasaCumplimiento >= 70) {
            $desempeno = 'Bueno';
            $desempenoColor = 'primary';
        } elseif ($tasaCumplimiento >= 50) {
            $desempeno = 'Regular';
            $desempenoColor = 'warning';
        } else {
            $desempeno = 'Crítico';
            $desempenoColor = 'danger';
        }

        // Total de evidencias fotográficas subidas en el trimestre
        $queryEvidencias = Evidencia::whereBetween('created_at', [$fechaInicio, $fechaFin]);
        if ($fichaId) {
            $queryEvidencias->whereHas('turno.asignacion', fn($q) => $q->where('ficha_id', $fichaId));
        } elseif ($programaId) {
            $queryEvidencias->whereHas('turno.asignacion.ficha', fn($q) => $q->where('programa_id', $programaId));
        }
        $totalEvidencias = $queryEvidencias->count();

        // Desglose por Ficha
        $desgloseFichas = $this->calcularDesgloseFichas($turnos, $hoy);

        // Desglose por Módulo
        $desgloseModulos = $this->calcularDesgloseModulos($turnos, $hoy);

        // Desglose mes a mes dentro del trimestre
        $desgloseMensual = $this->calcularDesgloseMensual($ano, $infoTrimestre, $turnos, $hoy);

        return [
            'ano' => $ano,
            'trimestre' => $trimestre,
            'info_trimestre' => $infoTrimestre,
            'fecha_inicio' => $fechaInicio->format('d/m/Y'),
            'fecha_fin' => $fechaFin->format('d/m/Y'),
            'total_turnos' => $totalTurnos,
            'turnos_evaluados' => $turnosEvaluados,
            'turnos_cumplidos' => $turnosCumplidos,
            'turnos_incumplidos' => $turnosIncumplidos,
            'turnos_pendientes' => $turnosPendientes,
            'tasa_cumplimiento' => $tasaCumplimiento,
            'desempeno' => $desempeno,
            'desempeno_color' => $desempenoColor,
            'total_evidencias' => $totalEvidencias,
            'desglose_fichas' => $desgloseFichas,
            'desglose_modulos' => $desgloseModulos,
            'desglose_mensual' => $desgloseMensual,
        ];
    }

    /**
     * Calcula métricas de cumplimiento por cada Ficha en el periodo.
     */
    protected function calcularDesgloseFichas($turnos, string $hoy): array
    {
        $fichasMap = [];

        // Agrupar turnos por ficha
        foreach ($turnos as $t) {
            $ficha = $t->asignacion?->ficha;
            if (!$ficha) continue;

            $fId = $ficha->id;
            if (!isset($fichasMap[$fId])) {
                $modulosNombres = $ficha->asignaciones ? $ficha->asignaciones->where('activo', true)->map(fn($a) => $a->modulo->nombre)->values()->toArray() : [];
                $fichasMap[$fId] = [
                    'ficha_id' => $fId,
                    'numero_ficha' => $ficha->numero_ficha ?? $ficha->nombre,
                    'nombre' => $ficha->nombre,
                    'jornada' => $ficha->jornada ?? 'Diurna',
                    'programa' => $ficha->programa->nombre ?? 'N/A',
                    'modulos' => $modulosNombres,
                    'total_turnos' => 0,
                    'cumplidos' => 0,
                    'incumplidos' => 0,
                    'pendientes' => 0,
                    'tasa' => 0,
                ];
            }

            $fichasMap[$fId]['total_turnos']++;
            $tieneEv = $t->evidencias->count() > 0 || $t->estado === Turno::ESTADO_CUMPLIDO;
            $fechaTurno = $t->fecha_turno->toDateString();

            if ($tieneEv) {
                $fichasMap[$fId]['cumplidos']++;
            } elseif ($fechaTurno < $hoy || $t->estado === Turno::ESTADO_INCUMPLIDO) {
                $fichasMap[$fId]['incumplidos']++;
            } else {
                $fichasMap[$fId]['pendientes']++;
            }
        }

        // Calcular porcentaje para cada ficha
        foreach ($fichasMap as &$item) {
            $eval = $item['cumplidos'] + $item['incumplidos'];
            $item['tasa'] = $eval > 0 ? round(($item['cumplidos'] / $eval) * 100, 1) : ($item['total_turnos'] > 0 ? 0 : 100);
            
            if ($eval === 0) {
                $item['estado'] = 'Programado';
                $item['estado_color'] = 'secondary';
            } elseif ($item['tasa'] >= 85) {
                $item['estado'] = 'Excelente';
                $item['estado_color'] = 'success';
            } elseif ($item['tasa'] >= 70) {
                $item['estado'] = 'Bueno';
                $item['estado_color'] = 'primary';
            } elseif ($item['tasa'] >= 50) {
                $item['estado'] = 'Regular';
                $item['estado_color'] = 'warning';
            } else {
                $item['estado'] = 'Crítico';
                $item['estado_color'] = 'danger';
            }
        }

        // Ordenar por tasa descendente
        usort($fichasMap, fn($a, $b) => $b['tasa'] <=> $a['tasa']);

        return array_values($fichasMap);
    }

    /**
     * Calcula métricas de cumplimiento por cada Módulo de limpieza.
     */
    protected function calcularDesgloseModulos($turnos, string $hoy): array
    {
        $modulosMap = [];

        foreach ($turnos as $t) {
            $modulo = $t->asignacion?->modulo;
            if (!$modulo) continue;

            $mId = $modulo->id;
            if (!isset($modulosMap[$mId])) {
                $modulosMap[$mId] = [
                    'modulo_id' => $mId,
                    'nombre' => $modulo->nombre,
                    'ubicacion' => $modulo->ubicacion ?? 'Centro de Formación',
                    'capacidad' => $modulo->capacidad ?? 25,
                    'total_turnos' => 0,
                    'cumplidos' => 0,
                    'incumplidos' => 0,
                    'pendientes' => 0,
                    'tasa' => 0,
                ];
            }

            $modulosMap[$mId]['total_turnos']++;
            $tieneEv = $t->evidencias->count() > 0 || $t->estado === Turno::ESTADO_CUMPLIDO;
            $fechaTurno = $t->fecha_turno->toDateString();

            if ($tieneEv) {
                $modulosMap[$mId]['cumplidos']++;
            } elseif ($fechaTurno < $hoy || $t->estado === Turno::ESTADO_INCUMPLIDO) {
                $modulosMap[$mId]['incumplidos']++;
            } else {
                $modulosMap[$mId]['pendientes']++;
            }
        }

        foreach ($modulosMap as &$item) {
            $eval = $item['cumplidos'] + $item['incumplidos'];
            $item['tasa'] = $eval > 0 ? round(($item['cumplidos'] / $eval) * 100, 1) : ($item['total_turnos'] > 0 ? 0 : 100);
        }

        usort($modulosMap, fn($a, $b) => $b['tasa'] <=> $a['tasa']);

        return array_values($modulosMap);
    }

    /**
     * Calcula la evolución mensual dentro del trimestre (3 meses).
     */
    protected function calcularDesgloseMensual(int $ano, array $infoTrimestre, $turnos, string $hoy): array
    {
        $meses = [];

        for ($m = $infoTrimestre['mes_inicio']; $m <= $infoTrimestre['mes_fin']; $m++) {
            $nombreMes = $infoTrimestre['meses'][$m - $infoTrimestre['mes_inicio']];
            
            $turnosMes = $turnos->filter(function ($t) use ($m, $ano) {
                return $t->fecha_turno->year === $ano && $t->fecha_turno->month === $m;
            });

            $cumplidos = 0;
            $incumplidos = 0;
            $pendientes = 0;

            foreach ($turnosMes as $t) {
                $tieneEv = $t->evidencias->count() > 0 || $t->estado === Turno::ESTADO_CUMPLIDO;
                $fechaTurno = $t->fecha_turno->toDateString();

                if ($tieneEv) {
                    $cumplidos++;
                } elseif ($fechaTurno < $hoy || $t->estado === Turno::ESTADO_INCUMPLIDO) {
                    $incumplidos++;
                } else {
                    $pendientes++;
                }
            }

            $total = $turnosMes->count();
            $eval = $cumplidos + $incumplidos;
            $tasa = $eval > 0 ? round(($cumplidos / $eval) * 100, 1) : ($total > 0 ? 0 : 100);

            $meses[] = [
                'mes_numero' => $m,
                'nombre' => $nombreMes,
                'total' => $total,
                'cumplidos' => $cumplidos,
                'incumplidos' => $incumplidos,
                'pendientes' => $pendientes,
                'tasa' => $tasa,
            ];
        }

        return $meses;
    }

    /**
     * Comparativa de los 4 trimestres del año para análisis de evolución.
     */
    public function obtenerComparativaTrimestres(int $ano): array
    {
        $hoy = Carbon::today()->toDateString();
        $comparativa = [];

        foreach (self::TRIMESTRES as $num => $info) {
            $fechaInicio = Carbon::create($ano, $info['mes_inicio'], 1)->startOfDay();
            $fechaFin = Carbon::create($ano, $info['mes_fin'], 1)->endOfMonth()->endOfDay();

            $turnos = Turno::whereBetween('fecha_turno', [$fechaInicio->toDateString(), $fechaFin->toDateString()])
                ->with('evidencias')
                ->get();

            $cumplidos = 0;
            $incumplidos = 0;
            $pendientes = 0;

            foreach ($turnos as $t) {
                $tieneEv = $t->evidencias->count() > 0 || $t->estado === Turno::ESTADO_CUMPLIDO;
                $fechaTurno = $t->fecha_turno->toDateString();

                if ($tieneEv) {
                    $cumplidos++;
                } elseif ($fechaTurno < $hoy || $t->estado === Turno::ESTADO_INCUMPLIDO) {
                    $incumplidos++;
                } else {
                    $pendientes++;
                }
            }

            $total = $turnos->count();
            $eval = $cumplidos + $incumplidos;
            $tasa = $eval > 0 ? round(($cumplidos / $eval) * 100, 1) : 0;

            $comparativa[$num] = [
                'numero' => $num,
                'nombre' => $info['nombre_corto'],
                'nombre_completo' => $info['nombre'],
                'total' => $total,
                'cumplidos' => $cumplidos,
                'incumplidos' => $incumplidos,
                'pendientes' => $pendientes,
                'tasa' => $tasa,
            ];
        }

        return $comparativa;
    }
}

