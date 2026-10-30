@extends('layouts.app')

@section('title', 'Dashboard - Vocero')

@section('content')
<!-- DASHBOARD TITLE & SUBTITLE -->
<div class="d-flex align-items-center gap-2 mb-1">
    <i class="fas fa-border-all" style="color: #109116; font-size: 22px;"></i>
    <h1 style="font-size: 25px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Dashboard del Vocero</h1>
</div>
<p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin-bottom: 24px;">
    Gestión de turnos de limpieza, grupos y evidencias – {{ \Carbon\Carbon::now()->format('d') }} de {{ ucfirst(\Carbon\Carbon::now()->locale('es')->monthName ?? 'October') }}, {{ \Carbon\Carbon::now()->format('Y') }}
</p>

<!-- STAT CARDS ROW (4 CARDS) -->
<div class="row g-3 mb-4">
    <!-- Card 1: Turno de Hoy -->
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-green">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $turnoHoy ? '1 Activo' : 'Al día' }}</div>
                <div class="stat-label">Turno de Hoy</div>
            </div>
        </div>
    </div>

    <!-- Card 2: Grupos Registrados -->
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-blue">
                <i class="fas fa-users-gear"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $gruposCount }}</div>
                <div class="stat-label">Grupos de Ficha</div>
            </div>
        </div>
    </div>

    <!-- Card 3: Próximos Turnos -->
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-amber">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $proximosTurnos->count() }}</div>
                <div class="stat-label">Próximos Turnos</div>
            </div>
        </div>
    </div>

    <!-- Card 4: Notificaciones -->
    <div class="col-xl-3 col-sm-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-red">
                <i class="fas fa-bell"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $notificacionesNoLeidas }}</div>
                <div class="stat-label">Notificaciones</div>
            </div>
        </div>
    </div>
</div>

<!-- TWO-COLUMN DASHBOARD GRID -->
<div class="row g-4">
    <!-- LEFT PANEL: TURNOS & HISTORIAL -->
    <div class="col-lg-7 col-xl-8 col-12">
        <!-- HERO BANNER IF TURNO TODAY -->
        @if(isset($turnosHoy) && $turnosHoy->isNotEmpty())
        <div class="card-today-hero mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="badge-active-pulse"></span>
                        <h4 class="m-0 fw-bold" style="color: #064e3b; font-size: 18px;">
                            ¡Jornada de Limpieza Abierta Hoy! 
                            @if($turnosHoy->count() > 1)
                                <span class="badge bg-success ms-2" style="font-size: 11px;">2 Módulos</span>
                            @endif
                        </h4>
                    </div>
                    @if($turnosHoy->count() > 1)
                        <p class="mb-1" style="color: #084d0b; font-size: 14px;">
                            <strong>Módulos a certificar:</strong> 
                            {{ $turnosHoy[0]->asignacion->modulo->nombre }} y {{ $turnosHoy[1]->asignacion->modulo->nombre }}
                            <span class="badge bg-light text-dark ms-2 border" style="font-size: 11px;">4 Fotos Requeridas</span>
                        </p>
                    @else
                        <p class="mb-1" style="color: #084d0b; font-size: 14px;">
                            <strong>Módulo:</strong> {{ $turnosHoy[0]->asignacion->modulo->nombre ?? 'N/A' }} 
                            @if($turnosHoy[0]->asignacion->modulo->ubicacion)
                                <span class="ms-2"><i class="fas fa-map-marker-alt"></i> {{ $turnosHoy[0]->asignacion->modulo->ubicacion }}</span>
                            @endif
                        </p>
                    @endif
                    <small style="color: #109116;">
                        Fecha límite de reporte: Hoy hasta las 11:59 PM
                    </small>
                </div>
                <div>
                    <a href="/vocero/subir-evidencia" class="btn-brand-solid">
                        <i class="fas fa-camera"></i> Subir Evidencias Ahora
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- PRÓXIMOS TURNOS TABLE -->
        <div class="card-custom mb-4">
            <div class="card-custom-header">
                <div class="card-custom-title">
                    <i class="fas fa-clock" style="color: #109116; font-size: 18px;"></i>
                    <span>Próximos Turnos de Limpieza</span>
                </div>
                <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 600; padding: 5px 10px; border-radius: 6px;">
                    Ficha {{ $vocero->ficha->numero_ficha ?? $vocero->ficha->nombre ?? '' }}
                </span>
            </div>

            <div class="table-responsive">
                <table class="table-clean">
                    <thead>
                        <tr>
                            <th style="width: 40%;">MÓDULO Y UBICACIÓN</th>
                            <th style="width: 25%;">FECHA PROGRAMADA</th>
                            <th style="width: 20%;">ESTADO</th>
                            <th style="width: 15%; text-align: right;">ACCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proximosTurnos as $turno)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $turno->asignacion->modulo->nombre }}</div>
                                    <small class="text-muted">{{ $turno->asignacion->modulo->ubicacion ?? 'Área de formación' }}</small>
                                </td>
                                <td>
                                    <span class="fw-medium text-secondary">{{ $turno->fecha_turno ? \Carbon\Carbon::parse($turno->fecha_turno)->format('d/m/Y') : 'N/A' }}</span>
                                </td>
                                <td>
                                    @if($turno->estado === 'Abierto')
                                        <span class="badge-status badge-status-abierto">Abierto Hoy</span>
                                    @elseif($turno->estado === 'Pendiente')
                                        <span class="badge-status badge-status-pendiente">Pendiente</span>
                                    @else
                                        <span class="badge-status badge-status-default">{{ $turno->estado }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    @if($turno->estado === 'Abierto')
                                        <a href="/vocero/subir-evidencia" class="btn-brand-outline" style="padding: 4px 10px; font-size: 11.5px;">
                                            <i class="fas fa-upload"></i> Subir
                                        </a>
                                    @else
                                        <span class="text-muted" style="font-size: 12px;">Programado</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 text-center text-muted">
                                    <i class="fas fa-calendar-check fa-2x mb-2 text-muted"></i>
                                    <div>No hay turnos programados próximamente</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- HISTORIAL DE TURNOS -->
        <div class="card-custom">
            <div class="card-custom-header">
                <div class="card-custom-title">
                    <i class="fas fa-history" style="color: #109116; font-size: 18px;"></i>
                    <span>Historial Reciente de Limpieza</span>
                </div>
                <a href="/vocero/evidencias" class="btn-brand-outline">Ver todas</a>
            </div>

            <div class="table-responsive">
                <table class="table-clean">
                    <thead>
                        <tr>
                            <th style="width: 45%;">MÓDULO</th>
                            <th style="width: 25%;">FECHA</th>
                            <th style="width: 30%; text-align: right;">ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historialTurnos->take(6) as $turno)
                            <tr>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $turno->asignacion->modulo->nombre }}</span>
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size: 13px;">{{ $turno->fecha_turno ? \Carbon\Carbon::parse($turno->fecha_turno)->format('d/m/Y') : 'N/A' }}</span>
                                </td>
                                <td style="text-align: right;">
                                    @if($turno->estado === 'Cumplido')
                                        <span class="badge-status badge-status-cumplido">
                                            <i class="fas fa-check"></i> Cumplido
                                        </span>
                                    @elseif($turno->estado === 'Incumplido')
                                        <span class="badge-status badge-status-incumplido">
                                            <i class="fas fa-times"></i> Incumplido
                                        </span>
                                    @else
                                        <span class="badge-status badge-status-pendiente">{{ $turno->estado }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-4 text-center text-muted">
                                    <div>No hay historial previo registrado</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL: ACCIONES RÁPIDAS & INFO DE FICHA -->
    <div class="col-lg-5 col-xl-4 col-12">
        <!-- FICHA INFO CARD -->
        <div class="card-custom mb-4" style="border-top: 4px solid #109116;">
            <div class="card-custom-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width: 46px; height: 46px; border-radius: 12px; background: #dcfce7; color: #109116; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h6 class="m-0 fw-bold text-dark">Ficha {{ $vocero->ficha->numero_ficha ?? $vocero->ficha->nombre ?? 'N/A' }}</h6>
                        <small class="text-muted">{{ $vocero->ficha->programa->nombre ?? 'Programa de Formación' }}</small>
                    </div>
                </div>

                <div class="p-3 rounded-3" style="background: #f8fafc; font-size: 13px;">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Vocero Líder:</span>
                        <strong class="text-dark">{{ $vocero->aprendiz->nombres ?? Auth::user()->nombres }} {{ $vocero->aprendiz->apellidos ?? '' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Jornada:</span>
                        <strong class="text-dark">{{ $vocero->ficha->jornada ?? 'Diurna' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Aprendices:</span>
                        <strong class="text-success">{{ $aprendicesCount }} Registrados</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="card-custom mb-4">
            <div class="card-custom-header">
                <div class="card-custom-title">
                    <i class="fas fa-bolt" style="color: #109116; font-size: 17px;"></i>
                    <span>Acciones Rápidas</span>
                </div>
            </div>
            <div class="card-custom-body p-3">
                <div class="d-flex flex-column gap-2">
                    <a href="/vocero/grupos" class="btn-action-tile">
                        <div class="d-flex align-items-center gap-3">
                            <div class="tile-icon tile-blue"><i class="fas fa-users-gear"></i></div>
                            <div>
                                <div class="tile-title">Gestionar Grupos</div>
                                <div class="tile-subtitle">Organizar integrantes y turnos</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>

                    <a href="/vocero/subir-evidencia" class="btn-action-tile">
                        <div class="d-flex align-items-center gap-3">
                            <div class="tile-icon tile-green"><i class="fas fa-camera"></i></div>
                            <div>
                                <div class="tile-title">Subir Evidencia</div>
                                <div class="tile-subtitle">Fotos de antes y después</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>

                    <a href="/vocero/evidencias" class="btn-action-tile">
                        <div class="d-flex align-items-center gap-3">
                            <div class="tile-icon tile-amber"><i class="fas fa-images"></i></div>
                            <div>
                                <div class="tile-title">Mis Evidencias</div>
                                <div class="tile-subtitle">Historial fotográfico de la ficha</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>

                    <a href="/vocero/aprendices" class="btn-action-tile">
                        <div class="d-flex align-items-center gap-3">
                            <div class="tile-icon tile-purple"><i class="fas fa-user-graduate"></i></div>
                            <div>
                                <div class="tile-title">Aprendices</div>
                                <div class="tile-subtitle">Listado y contactos de ficha</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </a>
                </div>
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

    .stat-content {
        line-height: 1.15;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.5px;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        margin-top: 3px;
    }

    /* Hero Card for today */
    .card-today-hero {
        background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
        border: 1.5px solid #a7f3d0;
        border-radius: 16px;
        padding: 24px 28px;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.08);
    }

    .badge-active-pulse {
        width: 10px;
        height: 10px;
        background-color: #109116;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
        animation: pulse 1.8s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    /* Status Badges */
    .badge-status {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-status-cumplido {
        background: #dcfce7;
        color: #084d0b;
    }

    .badge-status-incumplido {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-status-abierto {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-status-pendiente {
        background: #f1f5f9;
        color: #475569;
    }

    .badge-status-default {
        background: #e2e8f0;
        color: #334155;
    }

    /* Action Tiles */
    .btn-action-tile {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 14px;
        background: #ffffff;
        border: 1px solid #eef2ed;
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.18s ease;
    }

    .btn-action-tile:hover {
        background: #f8faf8;
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .tile-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .tile-blue { background: #e0f2fe; color: #0284c7; }
    .tile-green { background: #dcfce7; color: #109116; }
    .tile-amber { background: #fef3c7; color: #d97706; }
    .tile-purple { background: #f3e8ff; color: #9333ea; }

    .tile-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }

    .tile-subtitle {
        font-size: 11.5px;
        color: #64748b;
    }
</style>
@endsection
