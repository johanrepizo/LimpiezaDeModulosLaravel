@extends('layouts.app')

@section('title', 'Dashboard - Administrador')

@section('content')
<!-- DASHBOARD TITLE & SUBTITLE -->
<div class="d-flex align-items-center gap-2 mb-1">
    <i class="fas fa-border-all" style="color: #109116; font-size: 22px;"></i>
    <h1 style="font-size: 25px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Dashboard</h1>
</div>
<p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin-bottom: 24px;">
    Resumen general del sistema — {{ \Carbon\Carbon::now()->format('d') }} de {{ ucfirst(\Carbon\Carbon::now()->locale('es')->monthName ?? 'noviembre') }}, {{ \Carbon\Carbon::now()->format('Y') }}
</p>

<!-- STAT CARDS ROW (4 CARDS) -->
<div class="row g-3 mb-4">
    <!-- Card 1: Fichas Activas -->
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-green">
                <i class="fas fa-id-card"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $fichas }}</div>
                <div class="stat-label">Fichas Activas</div>
            </div>
        </div>
    </div>

    <!-- Card 2: Voceros Registrados -->
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-blue">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $voceros }}</div>
                <div class="stat-label">Voceros Registrados</div>
            </div>
        </div>
    </div>

    <!-- Card 3: Asignaciones Activas -->
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-amber">
                <i class="fas fa-door-open"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $asignaciones }}</div>
                <div class="stat-label">Asignaciones Activas</div>
            </div>
        </div>
    </div>

    <!-- Card 4: Incumplimientos -->
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-red">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $incumplimientosCount }}</div>
                <div class="stat-label">Incumplimientos</div>
            </div>
        </div>
    </div>
</div>

<!-- WIDGET REPORTE TRIMESTRAL DE CUMPLIMIENTO (CADA 3 MESES) -->
@if(isset($reporteTrimestralResumen))
<div class="card-custom mb-4 p-4" style="border-left: 4px solid #109116; background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 54px; height: 54px; border-radius: 14px; background: #dcfce7; color: #109116; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; box-shadow: 0 2px 8px rgba(16, 145, 22, 0.15);">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="badge" style="background: #109116; color: #ffffff; font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 6px;">
                        Reporte Trimestral (Cada 3 meses)
                    </span>
                    <span class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 6px;">
                        {{ $reporteTrimestralResumen['fecha_inicio'] }} al {{ $reporteTrimestralResumen['fecha_fin'] }}
                    </span>
                    <span class="badge" style="background: {{ $reporteTrimestralResumen['desempeno_color'] === 'success' ? '#dcfce7; color: #084d0b;' : ($reporteTrimestralResumen['desempeno_color'] === 'warning' ? '#fef3c7; color: #92400e;' : '#fee2e2; color: #991b1b;') }}; font-weight: 700; font-size: 11px; padding: 4px 8px; border-radius: 6px;">
                        Desempeño {{ $reporteTrimestralResumen['desempeno'] }}
                    </span>
                </div>
                <h4 class="fw-bold mb-1" style="color: #0f172a; font-size: 18px; letter-spacing: -0.2px;">
                    Cumplimiento de Limpieza: {{ $reporteTrimestralResumen['info_trimestre']['nombre'] }} {{ $reporteTrimestralResumen['ano'] }}
                </h4>
                <div class="text-muted small">
                    Tasa de cumplimiento: <strong class="text-success">{{ $reporteTrimestralResumen['tasa_cumplimiento'] }}%</strong> &bull;
                    <strong>{{ $reporteTrimestralResumen['turnos_cumplidos'] }}</strong> turnos cumplidos &bull;
                    <strong class="text-danger">{{ $reporteTrimestralResumen['turnos_incumplidos'] }}</strong> incumplidos &bull;
                    <strong>{{ $reporteTrimestralResumen['total_evidencias'] }}</strong> evidencias fotográficas
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-4 flex-wrap">
            <div style="min-width: 140px; text-align: center;">
                <div class="fw-bold fs-3 text-success mb-0" style="line-height: 1;">{{ $reporteTrimestralResumen['tasa_cumplimiento'] }}%</div>
                <small class="text-muted d-block mb-1" style="font-size: 11px;">Tasa Global</small>
                <div class="progress" style="height: 7px; border-radius: 4px; background: #e2e8f0; width: 140px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $reporteTrimestralResumen['tasa_cumplimiento'] }}%;"></div>
                </div>
            </div>
            <a href="/admin/reportes" class="btn-brand-solid" style="padding: 9px 18px; font-weight: 600; text-decoration: none;">
                <i class="fas fa-file-lines me-1"></i> Ver Reportes Trimestrales
            </a>
        </div>
    </div>
</div>
@endif

<!-- TWO-COLUMN DASHBOARD GRID -->
<div class="row g-4">
    <!-- LEFT PANEL: ÚLTIMAS EVIDENCIAS -->
    <div class="col-lg-7 col-xl-7 col-12">
        <div class="card-custom h-100">
            <div class="card-custom-header">
                <div class="card-custom-title">
                    <i class="fas fa-images" style="color: #109116; font-size: 18px;"></i>
                    <span>Últimas Evidencias</span>
                </div>
                <a href="/admin/evidencias" class="btn-brand-outline">Ver todas</a>
            </div>

            <div class="table-responsive">
                <table class="table-clean">
                    <thead>
                        <tr>
                            <th style="width: 28%;">VOCERO</th>
                            <th style="width: 16%;">FICHA</th>
                            <th style="width: 28%;">MÓDULO</th>
                            <th style="width: 22%;">FECHA</th>
                            <th style="width: 6%; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimasEvidencias as $evidencia)
                            @php
                                $voceroNombre = null;
                                if ($evidencia->turno?->grupo?->vocero?->aprendiz) {
                                    $ap = $evidencia->turno->grupo->vocero->aprendiz;
                                    $voceroNombre = $ap->nombres . ' ' . $ap->apellidos;
                                } elseif ($evidencia->turno?->asignacion?->ficha?->voceros?->first()?->aprendiz) {
                                    $ap = $evidencia->turno->asignacion->ficha->voceros->first()->aprendiz;
                                    $voceroNombre = $ap->nombres . ' ' . $ap->apellidos;
                                } else {
                                    $voceroNombre = 'Vocero Ficha';
                                }

                                $fichaCodigo = $evidencia->turno?->asignacion?->ficha?->numero_ficha 
                                    ?? $evidencia->turno?->asignacion?->ficha?->nombre 
                                    ?? '2024001';
                                $moduloNombre = $evidencia->turno?->asignacion?->modulo?->nombre ?? 'Módulo General';
                                $fechaFormat = $evidencia->created_at 
                                    ? \Carbon\Carbon::parse($evidencia->created_at)->format('d/m/Y H:i') 
                                    : ($evidencia->turno?->fecha_turno ? \Carbon\Carbon::parse($evidencia->turno->fecha_turno)->format('d/m/Y 12:00') : now()->format('d/m/Y 12:00'));
                            @endphp
                            <tr>
                                <td>
                                    <span style="font-weight: 600; color: #1e293b; display: block; max-width: 170px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $voceroNombre }}</span>
                                </td>
                                <td>
                                    <span style="font-weight: 700; color: #334155;">{{ $fichaCodigo }}</span>
                                </td>
                                <td>
                                    <span style="color: #475569;">{{ $moduloNombre }}</span>
                                </td>
                                <td style="white-space: nowrap;">
                                    <span style="color: #64748b; font-size: 12.5px;">{{ $fechaFormat }}</span>
                                </td>
                                <td style="text-align: center;">
                                    <i class="fas fa-check" style="color: #109116; font-weight: 900; font-size: 15px;"></i>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center text-muted">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <i class="fas fa-images fa-3x mb-2" style="color: #cbd5e1;"></i>
                                        <span style="font-size: 14px; font-weight: 500;">No hay evidencias registradas recientemente</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL: INCUMPLIMIENTOS -->
    <div class="col-lg-5 col-xl-5 col-12">
        <div class="card-custom h-100 card-incumplimientos">
            <div class="card-custom-header" style="border-bottom: 1px solid #fee2e2;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-triangle-exclamation" style="color: #dc2626; font-size: 17px;"></i>
                    <span style="font-size: 15.5px; font-weight: 700; color: #dc2626;">Incumplimientos</span>
                </div>
                <span class="badge-total-incumplimientos">{{ $incumplimientosCount }} total</span>
            </div>

            <div class="incumplimientos-list">
                @forelse($incumplimientos as $item)
                    @php
                        $voceroInc = null;
                        if ($item->grupo?->vocero?->aprendiz) {
                            $ap = $item->grupo->vocero->aprendiz;
                            $voceroInc = $ap->nombres . ' ' . $ap->apellidos;
                        } elseif ($item->asignacion?->ficha?->voceros?->first()?->aprendiz) {
                            $ap = $item->asignacion->ficha->voceros->first()->aprendiz;
                            $voceroInc = $ap->nombres . ' ' . $ap->apellidos;
                        } else {
                            $voceroInc = 'Sin Vocero asignado';
                        }

                        $fichaNum = $item->asignacion?->ficha?->numero_ficha 
                            ?? $item->asignacion?->ficha?->nombre 
                            ?? '2024003';
                        $modNom = $item->asignacion?->modulo?->nombre ?? 'Área de Almacenamiento';
                        $fechaVenc = $item->fecha_turno 
                            ? \Carbon\Carbon::parse($item->fecha_turno)->format('d/m/Y 00:00') 
                            : '01/12/2024 00:00';
                    @endphp
                    <div class="incumplimiento-item">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                            <span class="incumplimiento-title">Ficha {{ $fichaNum }} — {{ $modNom }}</span>
                            <span class="badge-vencido">Vencido</span>
                        </div>
                        <div class="incumplimiento-meta">Vocero: {{ $voceroInc }}</div>
                        <div class="incumplimiento-meta">Venció: {{ $fechaVenc }}</div>
                    </div>
                @empty
                    <div class="py-5 text-center text-muted">
                        <div class="d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-check-circle fa-3x mb-2" style="color: #86efac;"></i>
                            <span style="font-size: 14px; font-weight: 600; color: #084d0b;">¡Todo al día!</span>
                            <span style="font-size: 13px; color: #64748b;">No hay turnos con incumplimientos registrados</span>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Stat Cards Styling */
    .card-stat {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8e0;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 4px 12px rgba(0, 0, 0, 0.02);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .card-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }

    .stat-icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    .stat-green {
        background: #dcfce7;
        color: #109116;
    }

    .stat-blue {
        background: #e0f2fe;
        color: #0284c7;
    }

    .stat-amber {
        background: #fef3c7;
        color: #d97706;
    }

    .stat-red {
        background: #fee2e2;
        color: #dc2626;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }

    .stat-label {
        font-size: 12.5px;
        font-weight: 600;
        color: #64748b;
        margin-top: 2px;
    }

    /* Incumplimientos Card */
    .card-incumplimientos {
        background: #ffffff;
        border: 1px solid #fee2e2;
    }

    .badge-total-incumplimientos {
        background: #fee2e2;
        color: #dc2626;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
    }

    .incumplimientos-list {
        padding: 16px 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-height: 430px;
        overflow-y: auto;
    }

    .incumplimiento-item {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 10px;
        padding: 12px 14px;
        transition: transform 0.15s ease;
    }

    .incumplimiento-item:hover {
        transform: translateX(2px);
    }

    .incumplimiento-title {
        font-weight: 700;
        color: #991b1b;
        font-size: 13.5px;
    }

    .badge-vencido {
        background: #dc2626;
        color: #ffffff;
        font-size: 10px;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .incumplimiento-meta {
        font-size: 12px;
        color: #7f1d1d;
        margin-top: 2px;
    }
</style>
@endsection
