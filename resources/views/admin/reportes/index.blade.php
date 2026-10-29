@extends('layouts.app')

@section('title', 'Reportes de Cumplimiento Trimestral')

@section('content')
<!-- BREADCRUMB (NO SE IMPRIME) -->
<div class="mb-3 d-print-none">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
            <li class="breadcrumb-item active">Reportes de Cumplimiento Trimestral</li>
        </ol>
    </nav>
</div>

<!-- HEADER GENERAL (NO SE IMPRIME) -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 d-print-none">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="fas fa-chart-pie" style="color: #109116; font-size: 24px;"></i>
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Reportes de Cumplimiento Trimestral</h1>
        </div>
        <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
            Seguimiento y evaluación periódica de limpieza cada 3 meses para fichas y módulos del Centro de Formación
        </p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary d-flex align-items-center gap-1" onclick="window.print()" style="border-radius: 8px; font-weight: 600;">
            <i class="fas fa-print me-1"></i> Imprimir / PDF
        </button>
        <a href="{{ route('admin.reportes.csv', ['ano' => $anoSeleccionado, 'trimestre' => $trimestreSeleccionado, 'ficha_id' => $fichaId, 'programa_id' => $programaId]) }}" class="btn btn-success d-flex align-items-center gap-1" style="background: #109116; border-color: #109116; border-radius: 8px; font-weight: 600;">
            <i class="fas fa-file-excel me-1"></i> Exportar CSV
        </a>
    </div>
</div>

<!-- ENCABEZADO OFICIAL SENA (SOLO VISIBLE AL IMPRIMIR) -->
<div class="d-none d-print-block mb-4 pb-3 border-bottom text-center">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div style="font-size: 20px; font-weight: 900; color: #084d0b;">SERVICIO NACIONAL DE APRENDIZAJE - SENA</div>
        <div style="font-size: 11px; color: #64748b;">Fecha de emisión: {{ now()->format('d/m/Y H:i') }}</div>
    </div>
    <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 0;">
        INFORME TRIMESTRAL DE CUMPLIMIENTO DEL PROTOCOLO DE LIMPIEZA Y DESINFECCIÓN
    </h3>
    <div style="font-size: 12.5px; color: #475569;">
        {{ $reporte['info_trimestre']['nombre'] }} &bull; Periodo evaluado: {{ $reporte['fecha_inicio'] }} al {{ $reporte['fecha_fin'] }}
    </div>
</div>

<!-- FILTRO GENERADOR DE REPORTES (NO SE IMPRIME) -->
<div class="card-custom mb-4 p-4 d-print-none" style="border-left: 4px solid #109116;">
    <form method="GET" action="/admin/reportes" class="row g-3 align-items-end">
        <div class="col-md-2 col-sm-6">
            <label for="ano" class="form-label small fw-bold text-muted mb-1"><i class="fas fa-calendar me-1"></i> Año:</label>
            <select name="ano" id="ano" class="form-select form-select-sm" style="border-radius: 8px;">
                @foreach([2025, 2026, 2027] as $y)
                    <option value="{{ $y }}" {{ $anoSeleccionado == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 col-sm-6">
            <label for="trimestre" class="form-label small fw-bold text-muted mb-1"><i class="fas fa-clock-rotate-left me-1"></i> Trimestre (Cada 3 Meses):</label>
            <select name="trimestre" id="trimestre" class="form-select form-select-sm" style="border-radius: 8px;">
                <option value="1" {{ $trimestreSeleccionado == 1 ? 'selected' : '' }}>Trimestre 1 (Enero - Marzo)</option>
                <option value="2" {{ $trimestreSeleccionado == 2 ? 'selected' : '' }}>Trimestre 2 (Abril - Junio)</option>
                <option value="3" {{ $trimestreSeleccionado == 3 ? 'selected' : '' }}>Trimestre 3 (Julio - Septiembre)</option>
                <option value="4" {{ $trimestreSeleccionado == 4 ? 'selected' : '' }}>Trimestre 4 (Octubre - Diciembre)</option>
            </select>
        </div>

        <div class="col-md-3 col-sm-6">
            <label for="ficha_id" class="form-label small fw-bold text-muted mb-1"><i class="fas fa-folder me-1"></i> Ficha (Opcional):</label>
            <select name="ficha_id" id="ficha_id" class="form-select form-select-sm" style="border-radius: 8px;">
                <option value="">Todas las Fichas</option>
                @foreach($fichas as $f)
                    <option value="{{ $f->id }}" {{ $fichaId == $f->id ? 'selected' : '' }}>
                        Ficha {{ $f->numero_ficha ?? $f->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 col-sm-6 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-success w-100 py-2" style="background: #109116; border-color: #109116; border-radius: 8px; font-weight: 700;">
                <i class="fas fa-arrows-rotate me-1"></i> Generar Reporte
            </button>
            @if(request('ficha_id') || request('programa_id'))
                <a href="/admin/reportes?ano={{ $anoSeleccionado }}&trimestre={{ $trimestreSeleccionado }}" class="btn btn-sm btn-outline-secondary py-2" title="Limpiar filtros" style="border-radius: 8px;">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- TARJETAS DE INDICADORES CLAVE DEL TRIMESTRE -->
<div class="row g-3 mb-4">
    <!-- 1. Tasa de Cumplimiento Global -->
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper {{ $reporte['desempeno_color'] === 'success' ? 'stat-green' : ($reporte['desempeno_color'] === 'warning' ? 'stat-amber' : 'stat-red') }}">
                <i class="fas fa-percent"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $reporte['tasa_cumplimiento'] }}%</div>
                <div class="stat-label">Cumplimiento Global</div>
                <span class="badge" style="background: {{ $reporte['desempeno_color'] === 'success' ? '#dcfce7; color: #084d0b;' : ($reporte['desempeno_color'] === 'warning' ? '#fef3c7; color: #92400e;' : '#fee2e2; color: #991b1b;') }}; font-size: 11px; font-weight: 700; margin-top: 3px;">
                    Desempeño {{ $reporte['desempeno'] }}
                </span>
            </div>
        </div>
    </div>

    <!-- 2. Turnos Cumplidos con Evidencia -->
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $reporte['turnos_cumplidos'] }}</div>
                <div class="stat-label">Turnos Cumplidos</div>
                <small class="text-muted" style="font-size: 11.5px;">con evidencias fotográficas</small>
            </div>
        </div>
    </div>

    <!-- 3. Turnos Incumplidos / Vencidos -->
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-red">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $reporte['turnos_incumplidos'] }}</div>
                <div class="stat-label">Turnos Incumplidos</div>
                <small class="text-danger" style="font-size: 11.5px;">vencidos sin evidencia</small>
            </div>
        </div>
    </div>

    <!-- 4. Total Evidencias Fotográficas -->
    <div class="col-xl-3 col-md-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-blue">
                <i class="fas fa-camera"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $reporte['total_evidencias'] }}</div>
                <div class="stat-label">Evidencias Subidas</div>
                <small class="text-muted" style="font-size: 11.5px;">fotos antes y después</small>
            </div>
        </div>
    </div>
</div>

<!-- EVOLUCIÓN MENSUAL DEL TRIMESTRE (3 MESES) -->
<div class="card-custom p-4 mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold mb-0" style="color: #0f172a; font-size: 16px;">
            <i class="fas fa-chart-line text-success me-2"></i> Evolución Mes a Mes en el Trimestre
        </h5>
        <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 12px;">
            Periodo: {{ $reporte['fecha_inicio'] }} al {{ $reporte['fecha_fin'] }}
        </span>
    </div>

    <div class="row g-3">
        @foreach($reporte['desglose_mensual'] as $mes)
            <div class="col-md-4">
                <div class="p-3 rounded border h-100" style="background: #f8fafc;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold" style="font-size: 14px; color: #0f172a;">{{ $mes['nombre'] }}</span>
                        <span class="badge {{ $mes['tasa'] >= 80 ? 'bg-success' : ($mes['tasa'] >= 50 ? 'bg-warning text-dark' : 'bg-danger') }}" style="font-size: 12px; font-weight: 700;">
                            {{ $mes['tasa'] }}%
                        </span>
                    </div>

                    <div class="progress mb-2" style="height: 7px; border-radius: 4px; background: #e2e8f0;">
                        <div class="progress-bar {{ $mes['tasa'] >= 80 ? 'bg-success' : ($mes['tasa'] >= 50 ? 'bg-warning' : 'bg-danger') }}" role="progressbar" style="width: {{ $mes['tasa'] }}%;"></div>
                    </div>

                    <div class="d-flex justify-content-between small text-muted" style="font-size: 11.5px;">
                        <span><i class="fas fa-check text-success me-1"></i> {{ $mes['cumplidos'] }} cumplidos</span>
                        <span><i class="fas fa-times text-danger me-1"></i> {{ $mes['incumplidos'] }} vencidos</span>
                        <span><i class="fas fa-clock text-secondary me-1"></i> {{ $mes['pendientes'] }} pendientes</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- COMPARATIVA ANUAL DE LOS 4 TRIMESTRES -->
<div class="card-custom p-4 mb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="fw-bold mb-0" style="color: #0f172a; font-size: 16px;">
            <i class="fas fa-calendar-check text-success me-2"></i> Comparativa Anual por Trimestres (Año {{ $anoSeleccionado }})
        </h5>
        <small class="text-muted">Comportamiento del cumplimiento a lo largo del año</small>
    </div>

    <div class="row g-3">
        @foreach($comparativaTrimestres as $numTrim => $item)
            @php
                $esSeleccionado = $numTrim == $trimestreSeleccionado;
            @endphp
            <div class="col-md-3 col-sm-6">
                <a href="/admin/reportes?ano={{ $anoSeleccionado }}&trimestre={{ $numTrim }}" class="text-decoration-none d-block">
                    <div class="p-3 rounded border text-center h-100 transition-all" style="background: {{ $esSeleccionado ? '#f0fdf4' : '#ffffff' }}; border-color: {{ $esSeleccionado ? '#109116' : '#e2e8f0' }} !important; box-shadow: {{ $esSeleccionado ? '0 4px 12px rgba(16, 145, 22, 0.15)' : 'none' }};">
                        <span class="badge mb-2" style="background: {{ $esSeleccionado ? '#109116' : '#e2e8f0' }}; color: {{ $esSeleccionado ? '#ffffff' : '#475569' }}; font-size: 11px;">
                            {{ $item['nombre'] }}
                        </span>
                        <div class="fw-bold fs-4 {{ $esSeleccionado ? 'text-success' : 'text-dark' }}">
                            {{ $item['tasa'] }}%
                        </div>
                        <small class="text-muted d-block mb-2" style="font-size: 11px;">
                            {{ $item['cumplidos'] }} de {{ $item['total'] }} turnos
                        </small>
                        <div class="progress" style="height: 6px; border-radius: 3px; background: #e2e8f0;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $item['tasa'] }}%;"></div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<!-- TABLA DE DESGLOSE POR FICHA ACADÉMICA -->
<div class="card-custom mb-4">
    <div class="card-custom-header">
        <div class="card-custom-title">
            <i class="fas fa-id-card text-success"></i>
            <span>Cumplimiento por Ficha Académica ({{ count($reporte['desglose_fichas']) }})</span>
        </div>
        <small class="text-muted">Ordenado por mayor porcentaje de cumplimiento</small>
    </div>

    <div class="table-responsive">
        <table class="table-clean">
            <thead>
                <tr>
                    <th style="width: 20%;">FICHA</th>
                    <th style="width: 22%;">PROGRAMA</th>
                    <th style="width: 18%;">MÓDULOS</th>
                    <th style="width: 14%;">TURNOS</th>
                    <th style="width: 14%;">% CUMPLIMIENTO</th>
                    <th style="width: 12%; text-align: right;">DESEMPEÑO</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reporte['desglose_fichas'] as $f)
                    <tr>
                        <td>
                            <strong style="color: #0f172a; font-size: 14px;">Ficha {{ $f['numero_ficha'] }}</strong>
                            <small class="text-muted d-block">{{ $f['jornada'] }}</small>
                        </td>
                        <td>
                            <span style="color: #334155; font-size: 13px;">{{ Str::limit($f['programa'], 32) }}</span>
                        </td>
                        <td>
                            @forelse($f['modulos'] as $modNom)
                                <span class="badge" style="background: #f1f5f9; color: #334155; font-size: 11px; margin-bottom: 2px;">
                                    {{ Str::limit($modNom, 22) }}
                                </span>
                            @empty
                                <span class="text-muted small">Sin asignar</span>
                            @endforelse
                        </td>
                        <td>
                            <div style="font-size: 12.5px; font-weight: 600;">
                                <span class="text-success">{{ $f['cumplidos'] }} C</span> / 
                                <span class="text-danger">{{ $f['incumplidos'] }} I</span> / 
                                <span class="text-muted">{{ $f['pendientes'] }} P</span>
                            </div>
                            <small class="text-muted" style="font-size: 11px;">Total: {{ $f['total_turnos'] }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <strong style="font-size: 13.5px; color: #0f172a;">{{ $f['tasa'] }}%</strong>
                                <div class="progress flex-grow-1" style="height: 6px; border-radius: 3px; background: #e2e8f0; min-width: 60px;">
                                    <div class="progress-bar {{ $f['tasa'] >= 80 ? 'bg-success' : ($f['tasa'] >= 50 ? 'bg-warning' : 'bg-danger') }}" role="progressbar" style="width: {{ $f['tasa'] }}%;"></div>
                                </div>
                            </div>
                        </td>
                        <td style="text-align: right;">
                            <span class="badge {{ $f['estado_color'] === 'success' ? 'bg-success' : ($f['estado_color'] === 'warning' ? 'bg-warning text-dark' : ($f['estado_color'] === 'danger' ? 'bg-danger' : 'bg-secondary')) }}" style="font-size: 11px; font-weight: 700; padding: 5px 10px; border-radius: 6px;">
                                {{ $f['estado'] }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-5 text-center text-muted">
                            <i class="fas fa-folder-open fa-3x mb-2" style="color: #cbd5e1;"></i>
                            <div>No se registraron turnos para las fichas en este trimestre</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- TABLA DE DESGLOSE POR MÓDULO DE LIMPIEZA -->
<div class="card-custom mb-4">
    <div class="card-custom-header">
        <div class="card-custom-title">
            <i class="fas fa-broom text-success"></i>
            <span>Cumplimiento por Módulo de Limpieza ({{ count($reporte['desglose_modulos']) }})</span>
        </div>
        <small class="text-muted">Estado del aseo y desinfección por ambiente físico</small>
    </div>

    <div class="table-responsive">
        <table class="table-clean">
            <thead>
                <tr>
                    <th style="width: 25%;">MÓDULO</th>
                    <th style="width: 25%;">UBICACIÓN</th>
                    <th style="width: 15%;">CAPACIDAD</th>
                    <th style="width: 18%;">TURNOS PROGRAMADOS</th>
                    <th style="width: 17%; text-align: right;">% CUMPLIMIENTO</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reporte['desglose_modulos'] as $m)
                    <tr>
                        <td>
                            <strong style="color: #0f172a; font-size: 14px;">{{ $m['nombre'] }}</strong>
                        </td>
                        <td>
                            <span style="color: #475569; font-size: 13px;">
                                <i class="fas fa-map-marker-alt text-success me-1"></i> {{ $m['ubicacion'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 600;">
                                {{ $m['capacidad'] }} personas
                            </span>
                        </td>
                        <td>
                            <div style="font-size: 12.5px; font-weight: 600;">
                                <span class="text-success">{{ $m['cumplidos'] }} cumplidos</span> &bull; 
                                <span class="text-danger">{{ $m['incumplidos'] }} vencidos</span>
                            </div>
                            <small class="text-muted" style="font-size: 11px;">Total periodo: {{ $m['total_turnos'] }}</small>
                        </td>
                        <td style="text-align: right;">
                            <div class="d-inline-flex align-items-center gap-2 justify-content-end">
                                <strong style="font-size: 14px; color: #0f172a;">{{ $m['tasa'] }}%</strong>
                                <span class="badge {{ $m['tasa'] >= 80 ? 'bg-success' : ($m['tasa'] >= 50 ? 'bg-warning text-dark' : 'bg-danger') }}" style="font-size: 11px; padding: 4px 8px;">
                                    {{ $m['tasa'] >= 80 ? 'Óptimo' : ($m['tasa'] >= 50 ? 'Regular' : 'Bajo') }}
                                </span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-5 text-center text-muted">
                            <i class="fas fa-broom fa-3x mb-2" style="color: #cbd5e1;"></i>
                            <div>No hay información de módulos para este trimestre</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- BLOQUE DE FIRMAS Y VALIDEZ (SOLO VISIBLE AL IMPRIMIR) -->
<div class="d-none d-print-block mt-5 pt-4">
    <div class="row text-center mt-5">
        <div class="col-6">
            <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 8px;">
                <strong>ADMINISTRADOR DEL SISTEMA</strong><br>
                <small style="color: #475569;">GestiLimpieza - SENA</small>
            </div>
        </div>
        <div class="col-6">
            <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 8px;">
                <strong>COORDINACIÓN ACADÉMICA / ASEO</strong><br>
                <small style="color: #475569;">Centro de Formación SENA</small>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    @media print {
        body {
            background: #ffffff !important;
            font-size: 12px !important;
        }
        .main-header, .sidebar, .btn, .d-print-none {
            display: none !important;
        }
        .content-container {
            padding: 0 !important;
            margin: 0 !important;
        }
        .card-custom {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
            break-inside: avoid;
        }
        .card-stat {
            box-shadow: none !important;
            border: 1px solid #cbd5e1 !important;
        }
    }
</style>
@endsection

