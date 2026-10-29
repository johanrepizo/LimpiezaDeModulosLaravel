@extends('layouts.app')

@section('title', 'Evidencias Fotográficas por Carpeta')

@section('content')

{{-- ========================================================================= --}}
{{-- NIVEL 1: CARPETAS DE PROGRAMAS (VISTA POR DEFECTO)                        --}}
{{-- ========================================================================= --}}
@if($nivel === 'programas')
    <!-- BREADCRUMB -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item active">Evidencias Fotográficas por Programa</li>
            </ol>
        </nav>
    </div>

    <!-- HEADER -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-images" style="color: #109116; font-size: 24px;"></i>
                <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Evidencias Fotográficas por Programa</h1>
            </div>
            <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
                Navega por las carpetas de programas para consultar los registros fotográficos de limpieza organizados por ficha
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/evidencias?view=all" class="btn-brand-outline" style="background: #ffffff;">
                <i class="fas fa-list me-1"></i> Ver Listado Global
            </a>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4 col-12">
            <div class="card-stat">
                <div class="stat-icon-wrapper stat-green">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $programas->count() }}</div>
                    <div class="stat-label">Programas Activos</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card-stat">
                <div class="stat-icon-wrapper stat-blue">
                    <i class="fas fa-folder"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalFichas }}</div>
                    <div class="stat-label">Fichas Registradas</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card-stat">
                <div class="stat-icon-wrapper" style="background: #fef3c7; color: #d97706;">
                    <i class="fas fa-camera"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalEvidenciasSistema }}</div>
                    <div class="stat-label">Total Evidencias Subidas</div>
                </div>
            </div>
        </div>
    </div>

    <!-- BUSCADOR -->
    <div class="mb-4">
        <div class="position-relative">
            <i class="fas fa-search position-absolute" style="left: 16px; top: 14px; color: #94a3b8;"></i>
            <input type="text" class="form-control ps-5" id="buscarPrograma" placeholder="Buscar programa por nombre..." onkeyup="filtrarProgramas()" style="padding-top: 10px; padding-bottom: 10px; border-radius: 10px;">
        </div>
    </div>

    <!-- GRID DE PROGRAMAS -->
    <div class="row g-4" id="contenedorProgramas">
        @forelse($programas as $prog)
            @php
                $totalEvidenciasProg = $prog->contarEvidencias();
                $totalFichasProg = $prog->fichas->count();
            @endphp
            <div class="col-md-6 col-lg-4 programa-card">
                <div class="card-custom h-100 folder-card" onclick="window.location.href='/admin/evidencias?programa_id={{ $prog->id }}'" title="Clic para ver las fichas de este programa">
                    <div class="card-custom-body d-flex flex-column h-100 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="folder-icon-box">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 6px;">
                                {{ $prog->nivel ?? 'Programa' }}
                            </span>
                        </div>

                        <h5 class="fw-bold mb-1 programa-nombre" style="color: #0f172a; font-size: 16px;">{{ $prog->nombre }}</h5>
                        <p class="text-secondary small mb-3 flex-grow-1" style="line-height: 1.45;">
                            {{ Str::limit($prog->descripcion ?? 'Programa de formación técnica del Centro de Formación', 95) }}
                        </p>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge" style="background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-size: 12px; padding: 5px 10px;">
                                <i class="fas fa-layer-group me-1"></i> {{ $totalFichasProg }} Fichas
                            </span>
                            <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 12px; padding: 5px 10px;">
                                <i class="fas fa-camera text-warning me-1"></i> {{ $totalEvidenciasProg }} Fotos
                            </span>
                        </div>

                        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                            <span class="text-muted small">Explorar carpetas</span>
                            <span class="btn-brand-outline" style="padding: 4px 12px; font-size: 12px;">
                                Ver Fichas <i class="fas fa-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card-custom py-5 text-center text-muted">
                    <i class="fas fa-folder-open fa-3x mb-2" style="color: #cbd5e1;"></i>
                    <div>No hay programas registrados en el sistema</div>
                </div>
            </div>
        @endforelse
    </div>

{{-- ========================================================================= --}}
{{-- NIVEL 2: FICHAS DE UN PROGRAMA ESPECÍFICO                                 --}}
{{-- ========================================================================= --}}
@elseif($nivel === 'fichas')
    <!-- BREADCRUMB -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/admin/evidencias" class="text-decoration-none text-success"><i class="fas fa-images"></i> Evidencias por Programa</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($programa->nombre, 35) }}</li>
            </ol>
        </nav>
    </div>

    <!-- PROGRAMA HERO BANNER -->
    <div class="card-custom mb-4" style="border-left: 4px solid #109116;">
        <div class="card-custom-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge" style="background: #dcfce7; color: #084d0b; font-size: 11px; font-weight: 700;">Programa Académiéco</span>
                    <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 700;"><i class="fas fa-camera me-1"></i> {{ $programa->contarEvidencias() }} Fotos Totales</span>
                </div>
                <h2 class="h4 fw-bold mb-1" style="color: #0f172a;">{{ $programa->nombre }}</h2>
                <p class="text-muted small mb-0">{{ $programa->descripcion ?? 'Selecciona una ficha para consultar su calendario de limpiezas y galería completa de evidencias' }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="/admin/evidencias" class="btn-brand-outline">
                    <i class="fas fa-arrow-left"></i> Volver a Programas
                </a>
                <a href="/admin/evidencias?view=all" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
                    <i class="fas fa-list"></i> Global
                </a>
            </div>
        </div>
    </div>

    <!-- SECTION TITLE -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold m-0" style="color: #0f172a;">
            <i class="fas fa-folder-open text-success me-2"></i> Carpetas de Fichas ({{ $fichas->count() }})
        </h5>
        <small class="text-muted">Haz clic en una ficha para consultar su calendario y galería de evidencias</small>
    </div>

    @if($fichas->isEmpty())
        <div class="card-custom py-5 text-center text-muted">
            <i class="fas fa-folder-open fa-3x mb-2" style="color: #cbd5e1;"></i>
            <div>No hay fichas registradas para este programa</div>
        </div>
    @else
        <div class="row g-4">
            @foreach($fichas as $f)
                @php
                    $numFotosFicha = $f->contarEvidencias();
                    $modulosActivos = $f->asignaciones->where('activo', true);
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card-custom h-100 folder-card" onclick="window.location.href='/admin/evidencias?ficha_id={{ $f->id }}'" title="Clic para ver evidencias de la ficha {{ $f->numero_ficha }}">
                        <div class="card-custom-body p-4 d-flex flex-column h-100">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div style="width: 44px; height: 44px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                    <i class="fas fa-images"></i>
                                </div>
                                <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 6px;">
                                    {{ $f->jornada ?? 'Diurna' }}
                                </span>
                            </div>

                            <h5 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">
                                Ficha {{ $f->numero_ficha ?? $f->nombre }}
                            </h5>
                            <div class="text-muted small mb-2">
                                {{ Str::limit($f->nombre, 40) }}
                            </div>

                            <!-- MÓDULOS ASIGNADOS -->
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">Módulos Asignados ({{ $modulosActivos->count() }}/2):</small>
                                @forelse($modulosActivos as $asig)
                                    <span class="badge" style="background: #f1f5f9; color: #334155; font-size: 11px; margin-right: 4px; margin-bottom: 4px;">
                                        <i class="fas fa-broom text-success me-1"></i> {{ Str::limit($asig->modulo->nombre, 24) }}
                                    </span>
                                @empty
                                    <span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 11px;">Sin módulo asignado</span>
                                @endforelse
                            </div>

                            <!-- MÉTRICAS -->
                            <div class="p-3 rounded mb-3 mt-auto" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span style="font-size: 12.5px; color: #475569; font-weight: 600;">
                                        <i class="fas fa-calendar-alt text-success me-1"></i> Evidencias Registradas:
                                    </span>
                                    <span class="badge {{ $numFotosFicha > 0 ? 'bg-success' : 'bg-secondary' }}" style="font-size: 12px; font-weight: 700; padding: 5px 10px; border-radius: 6px;">
                                        {{ $numFotosFicha }} fotos
                                    </span>
                                </div>
                            </div>

                            <div class="pt-3 border-top">
                                <a href="/admin/evidencias?ficha_id={{ $f->id }}" class="btn-brand-outline w-100 justify-content-center">
                                    <i class="fas fa-calendar-days me-1"></i> Ver Calendario y Evidencias <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

{{-- ========================================================================= --}}
{{-- NIVEL 3: DETALLE DE LA FICHA (CALENDARIO DE LIMPIEZAS Y GALERÍA)          --}}
{{-- ========================================================================= --}}
@elseif($nivel === 'ficha')
    <!-- BREADCRUMB -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/admin/evidencias" class="text-decoration-none text-success"><i class="fas fa-images"></i> Evidencias por Programa</a></li>
                <li class="breadcrumb-item"><a href="/admin/evidencias?programa_id={{ $programa->id }}" class="text-decoration-none text-success">{{ Str::limit($programa->nombre, 25) }}</a></li>
                <li class="breadcrumb-item active">Ficha {{ $ficha->numero_ficha ?? $ficha->nombre }}</li>
            </ol>
        </nav>
    </div>

    <!-- FICHA HERO BANNER -->
    <div class="card-custom mb-4" style="border-left: 4px solid #109116;">
        <div class="card-custom-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="badge" style="background: #dcfce7; color: #084d0b; font-size: 11px; font-weight: 700;">
                        Ficha {{ $ficha->numero_ficha ?? $ficha->nombre }}
                    </span>
                    <span class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 600;">
                        Jornada: {{ $ficha->jornada ?? 'Diurna' }}
                    </span>
                    <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 700;">
                        <i class="fas fa-camera text-warning me-1"></i> {{ $totalEvidenciasFicha }} Evidencias en total
                    </span>
                </div>
                <h2 class="h4 fw-bold mb-1" style="color: #0f172a;">{{ $ficha->nombre }}</h2>
                <div class="text-muted small">
                    <strong>Programa:</strong> {{ $programa->nombre }}
                    @if($ficha->asignaciones && $ficha->asignaciones->where('activo', true)->count() > 0)
                        &bull; <strong>Módulos Asignados:</strong> 
                        @foreach($ficha->asignaciones->where('activo', true) as $asig)
                            <span class="badge bg-light text-dark border ms-1">{{ $asig->modulo->nombre }}</span>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="/admin/evidencias?programa_id={{ $programa->id }}" class="btn-brand-outline">
                    <i class="fas fa-arrow-left"></i> Volver a Fichas
                </a>
            </div>
        </div>
    </div>

    <!-- NAVEGACIÓN ENTRE VISTAS: CALENDARIO Y GALERÍA -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <ul class="nav nav-pills gap-2" id="pills-tab-evidencias" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active d-flex align-items-center gap-2" id="tab-calendario-btn" data-bs-toggle="pill" data-bs-target="#tab-calendario" type="button" role="tab" style="border-radius: 10px; font-weight: 700; padding: 10px 22px;">
                    <i class="fas fa-calendar-alt"></i> Calendario de Limpiezas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link d-flex align-items-center gap-2" id="tab-galeria-btn" data-bs-toggle="pill" data-bs-target="#tab-galeria" type="button" role="tab" style="border-radius: 10px; font-weight: 700; padding: 10px 22px;">
                    <i class="fas fa-images"></i> Galería de Fotos ({{ $totalEvidenciasFicha }})
                </button>
            </li>
        </ul>

        <!-- SELECTOR RÁPIDO PARA CAMBIAR DE FICHA -->
        <div class="d-flex align-items-center gap-2" style="min-width: 280px;">
            <label for="cambiarFicha" class="small text-muted fw-bold mb-0 text-nowrap"><i class="fas fa-folder me-1 text-success"></i> Cambiar Ficha:</label>
            <select id="cambiarFicha" class="form-select form-select-sm" onchange="if(this.value) window.location.href='/admin/evidencias?ficha_id=' + this.value;">
                @foreach($fichas as $f)
                    <option value="{{ $f->id }}" {{ $f->id == $ficha->id ? 'selected' : '' }}>
                        Ficha {{ $f->numero_ficha ?? $f->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- CONTENEDOR DE PESTAÑAS -->
    <div class="tab-content" id="pills-tabContent">

        {{-- ================================================================= --}}
        {{-- PESTAÑA 1: CALENDARIO DE CUMPLIMIENTO Y EVIDENCIAS                --}}
        {{-- ================================================================= --}}
        <div class="tab-pane fade show active" id="tab-calendario" role="tabpanel">

            <!-- LEYENDA DEL CALENDARIO -->
            <div class="d-flex align-items-center gap-4 flex-wrap mb-3 px-2 py-2 rounded" style="font-size: 13.5px; font-weight: 600; color: #334155; background: #ffffff; border: 1px solid #e2e8f0;">
                <div class="d-flex align-items-center gap-2">
                    <span style="width: 14px; height: 14px; border-radius: 4px; background: #109116; display: inline-block;"></span>
                    <span>Evidencia entregada</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span style="width: 14px; height: 14px; border-radius: 4px; background: #dc2626; display: inline-block;"></span>
                    <span>Vencida sin evidencia</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span style="width: 14px; height: 14px; border-radius: 4px; background: #94a3b8; display: inline-block;"></span>
                    <span>Próxima limpieza (En espera)</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span style="width: 14px; height: 14px; border-radius: 4px; background: #64748b; display: inline-block;"></span>
                    <span>Sin grupo asignado</span>
                </div>
            </div>

            <!-- TARJETA PRINCIPAL DEL CALENDARIO -->
            <div class="card-custom p-4 mb-4">
                <!-- CONTROLES SUPERIORES (MATCH SCREENSHOT) -->
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                    <!-- Botones Izquierda: < > Hoy -->
                    <div class="d-flex align-items-center gap-2">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-success px-3" onclick="cambiarMes(-1)" title="Mes anterior" style="background: #109116; border-color: #109116; border-radius: 8px 0 0 8px;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-success px-3" onclick="cambiarMes(1)" title="Mes siguiente" style="background: #109116; border-color: #109116; border-radius: 0 8px 8px 0;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <button type="button" class="btn btn-sm btn-success px-3" onclick="irAHoy()" style="background: #109116; border-color: #109116; border-radius: 8px; font-weight: 600;">
                            Hoy
                        </button>
                    </div>

                    <!-- Título Central: e.g. noviembre de 2026 -->
                    <div class="text-center flex-grow-1">
                        <h3 id="calTituloMes" class="mb-0 fw-bold" style="color: #0f172a; font-size: 22px; text-transform: lowercase;">
                            noviembre de 2026
                        </h3>
                    </div>

                    <!-- Botones Derecha: Mes | Año -->
                    <div class="btn-group" role="group">
                        <button type="button" id="btnModoMes" class="btn btn-sm btn-success px-3" onclick="setModoVista('mes')" style="font-weight: 600; border-radius: 8px 0 0 8px;">
                            Mes
                        </button>
                        <button type="button" id="btnModoAno" class="btn btn-sm btn-outline-secondary px-3" onclick="setModoVista('ano')" style="font-weight: 600; border-radius: 0 8px 8px 0;">
                            Año
                        </button>
                    </div>
                </div>

                <!-- CONTENEDOR VISTA MES -->
                <div id="vistaMesContenedor">
                    <!-- ENCABEZADO DÍAS DE LA SEMANA (dom, lun, mar, mié, jue, vie, sáb) -->
                    <div class="row g-0 text-center mb-1" style="border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
                        <div class="col cal-header-day text-primary fw-bold">dom</div>
                        <div class="col cal-header-day text-primary fw-bold">lun</div>
                        <div class="col cal-header-day text-primary fw-bold">mar</div>
                        <div class="col cal-header-day text-primary fw-bold">mié</div>
                        <div class="col cal-header-day text-primary fw-bold">jue</div>
                        <div class="col cal-header-day text-primary fw-bold">vie</div>
                        <div class="col cal-header-day text-primary fw-bold">sáb</div>
                    </div>

                    <!-- REJILLA DE DÍAS (GENERADA POR JAVASCRIPT) -->
                    <div id="calGridDias" class="cal-grid">
                        <!-- Las celdas se inyectan dinámicamente con JS -->
                    </div>
                </div>

                <!-- CONTENEDOR VISTA AÑO (12 MESES) -->
                <div id="vistaAnoContenedor" style="display: none;">
                    <div class="row g-3" id="calGridAno">
                        <!-- 12 mini calendarios inyectados con JS -->
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- PESTAÑA 2: GALERÍA FOTOGRÁFICA DE EVIDENCIAS                      --}}
        {{-- ================================================================= --}}
        <div class="tab-pane fade" id="tab-galeria" role="tabpanel">
            <!-- FILTROS POR TIPO (Todas, Antes, Después) -->
            <div class="card-custom mb-4 p-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-muted fw-bold me-2">Filtrar Galería:</span>
                        <a href="/admin/evidencias?ficha_id={{ $ficha->id }}" class="btn btn-sm {{ !request('tipo') ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 8px; font-weight: 600;">
                            Todas ({{ $totalEvidenciasFicha }})
                        </a>
                        <a href="/admin/evidencias?ficha_id={{ $ficha->id }}&tipo=antes" class="btn btn-sm {{ request('tipo') === 'antes' ? 'btn-warning text-dark' : 'btn-outline-secondary' }}" style="border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-camera me-1"></i> Fotos Antes
                        </a>
                        <a href="/admin/evidencias?ficha_id={{ $ficha->id }}&tipo=despues" class="btn btn-sm {{ request('tipo') === 'despues' ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-check-circle me-1"></i> Fotos Después
                        </a>
                    </div>
                </div>
            </div>

            <!-- TARJETAS DE FOTOS -->
            @if($evidencias->isEmpty())
                <div class="card-custom py-5 text-center text-muted">
                    <i class="fas fa-camera-retro fa-3x mb-2" style="color: #cbd5e1;"></i>
                    <div class="fw-bold mb-1">No se encontraron evidencias fotográficas</div>
                    <small>Esta ficha aún no tiene fotos subidas para el filtro seleccionado.</small>
                </div>
            @else
                <div class="row g-4">
                    @foreach($evidencias as $evidencia)
                        @php
                            $fechaFormateada = $evidencia->created_at 
                                ? $evidencia->created_at->format('d/m/Y H:i') 
                                : ($evidencia->fecha_creacion ? \Carbon\Carbon::parse($evidencia->fecha_creacion)->format('d/m/Y H:i') : 'N/A');
                            $esAntes = strtolower($evidencia->tipo) === 'antes';
                            $moduloNom = $evidencia->turno?->asignacion?->modulo?->nombre ?? 'Módulo General';
                            $grupoNom = $evidencia->turno?->grupo?->nombre ?? 'Grupo #' . ($evidencia->turno?->grupo_id ?? 'N/A');
                            $voceroNom = $evidencia->turno?->grupo?->vocero?->aprendiz 
                                ? ($evidencia->turno->grupo->vocero->aprendiz->nombres . ' ' . $evidencia->turno->grupo->vocero->aprendiz->apellidos) 
                                : 'Vocero General';
                            $rutaImg = $evidencia->ruta_archivo ? asset('storage/' . $evidencia->ruta_archivo) : asset('img/no-image.png');
                        @endphp
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="card-custom h-100 d-flex flex-column" style="overflow: hidden; border: 1px solid #e2e8f0; transition: transform 0.2s, box-shadow 0.2s;">
                                <!-- MINIATURA -->
                                <div class="position-relative" style="height: 190px; background: #0f172a; overflow: hidden; cursor: pointer;" onclick="abrirModalPreview('{{ $rutaImg }}', '{{ $esAntes ? 'Foto Antes de Limpieza' : 'Foto Después de Limpieza' }}', '{{ $moduloNom }}', 'Turno #{{ $evidencia->turno_id }}', '{{ $fechaFormateada }}', '{{ $grupoNom }}', '{{ addslashes($evidencia->observaciones ?? 'Sin observaciones') }}')">
                                    <img src="{{ $rutaImg }}" alt="Evidencia Turno {{ $evidencia->turno_id }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" onerror="this.src='https://placehold.co/600x400/e2e8f0/475569?text=Sin+Vista+Previa';">
                                    <div class="position-absolute top-0 start-0 m-2">
                                        @if($esAntes)
                                            <span class="badge" style="background: rgba(245, 158, 11, 0.95); color: #ffffff; font-weight: 700; padding: 5px 10px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.25);">
                                                <i class="fas fa-camera me-1"></i> Foto Antes
                                            </span>
                                        @else
                                            <span class="badge" style="background: rgba(16, 145, 22, 0.95); color: #ffffff; font-weight: 700; padding: 5px 10px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.25);">
                                                <i class="fas fa-check-circle me-1"></i> Foto Después
                                            </span>
                                        @endif
                                    </div>
                                    <div class="position-absolute bottom-0 end-0 m-2">
                                        <span class="badge" style="background: rgba(15, 23, 42, 0.85); color: #ffffff; font-size: 11px;">
                                            <i class="fas fa-expand me-1"></i> Ampliar
                                        </span>
                                    </div>
                                </div>

                                <!-- CONTENIDO -->
                                <div class="p-3 d-flex flex-column flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold" style="font-size: 13px; color: #0f172a;">
                                            <i class="fas fa-calendar-check text-success me-1"></i> Turno #{{ $evidencia->turno_id }}
                                        </span>
                                        <small class="text-muted" style="font-size: 11px;">{{ $fechaFormateada }}</small>
                                    </div>

                                    <div class="mb-2">
                                        <div class="fw-bold text-success small" style="line-height: 1.3;">
                                            <i class="fas fa-map-marker-alt me-1"></i> {{ $moduloNom }}
                                        </div>
                                        <small class="text-muted d-block" style="font-size: 11.5px;">
                                            <i class="fas fa-users me-1"></i> {{ $grupoNom }} &bull; {{ $voceroNom }}
                                        </small>
                                    </div>

                                    @if($evidencia->observaciones)
                                        <p class="text-muted small mb-3 p-2 rounded" style="background: #f8fafc; font-size: 11.5px; border-left: 3px solid #cbd5e1; margin-top: auto;">
                                            <em>"{{ Str::limit($evidencia->observaciones, 65) }}"</em>
                                        </p>
                                    @else
                                        <div class="mt-auto"></div>
                                    @endif

                                    <div class="pt-2 border-top d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-success w-100" style="font-size: 12px; font-weight: 600; border-radius: 6px;" onclick="abrirModalPreview('{{ $rutaImg }}', '{{ $esAntes ? 'Foto Antes de Limpieza' : 'Foto Después de Limpieza' }}', '{{ $moduloNom }}', 'Turno #{{ $evidencia->turno_id }}', '{{ $fechaFormateada }}', '{{ $grupoNom }}', '{{ addslashes($evidencia->observaciones ?? 'Sin observaciones') }}')">
                                            <i class="fas fa-eye me-1"></i> Ver Detalle
                                        </button>
                                        @if($evidencia->ruta_archivo)
                                            <a href="{{ $rutaImg }}" download class="btn btn-sm btn-outline-secondary" style="border-radius: 6px; padding: 4px 8px;" title="Descargar foto">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $evidencias->links() }}
                </div>
            @endif
        </div>
    </div>

{{-- ========================================================================= --}}
{{-- NIVEL 4: MODO LISTADO GLOBAL (TABLA COMPLETA)                             --}}
{{-- ========================================================================= --}}
@elseif($nivel === 'todos')
    <!-- BREADCRUMB -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/admin/evidencias" class="text-decoration-none text-success">Evidencias</a></li>
                <li class="breadcrumb-item active">Listado Global</li>
            </ol>
        </nav>
    </div>

    <!-- HEADER -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-list-check" style="color: #109116; font-size: 24px;"></i>
                <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Listado Global de Evidencias Fotográficas</h1>
            </div>
            <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
                Historial cronológico general de todas las fotos de limpieza subidas en el sistema
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/evidencias" class="btn-brand-outline">
                <i class="fas fa-folder-tree me-1"></i> Ver por Carpetas de Programas
            </a>
        </div>
    </div>

    <!-- FILTROS DE BÚSQUEDA GLOBAL -->
    <div class="card-custom mb-4 p-3">
        <form method="GET" action="/admin/evidencias" class="row g-2 align-items-center">
            <input type="hidden" name="view" value="all">
            <div class="col-md-5">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute" style="left: 12px; top: 10px; color: #94a3b8;"></i>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control form-control-sm ps-5" placeholder="Buscar por número de ficha, módulo..." style="border-radius: 8px;">
                </div>
            </div>
            <div class="col-md-3">
                <select name="tipo" class="form-select form-select-sm" style="border-radius: 8px;">
                    <option value="">Todos los Tipos (Antes y Después)</option>
                    <option value="antes" {{ request('tipo') === 'antes' ? 'selected' : '' }}>Solo Fotos Antes</option>
                    <option value="despues" {{ request('tipo') === 'despues' ? 'selected' : '' }}>Solo Fotos Después</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-success px-3" style="border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-filter me-1"></i> Filtrar
                </button>
                @if(request('buscar') || request('tipo'))
                    <a href="/admin/evidencias?view=all" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;">
                        <i class="fas fa-times me-1"></i> Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABLA DE EVIDENCIAS -->
    <div class="card-custom">
        <div class="table-responsive">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th style="width: 12%;">TURNO</th>
                        <th style="width: 15%;">TIPO</th>
                        <th style="width: 26%;">FICHA &bull; MÓDULO</th>
                        <th style="width: 18%;">FECHA Y HORA</th>
                        <th style="width: 14%;">VISTA PREVIA</th>
                        <th style="width: 15%; text-align: right;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evidencias as $evidencia)
                        @php
                            $fechaFormateada = $evidencia->created_at 
                                ? $evidencia->created_at->format('d/m/Y H:i') 
                                : ($evidencia->fecha_creacion ? \Carbon\Carbon::parse($evidencia->fecha_creacion)->format('d/m/Y H:i') : 'N/A');
                            $esAntes = strtolower($evidencia->tipo) === 'antes';
                            $moduloNom = $evidencia->turno?->asignacion?->modulo?->nombre ?? 'Módulo General';
                            $fichaCod = $evidencia->turno?->asignacion?->ficha?->numero_ficha 
                                ?? $evidencia->turno?->asignacion?->ficha?->nombre 
                                ?? 'N/A';
                            $fichaId = $evidencia->turno?->asignacion?->ficha_id;
                            $grupoNom = $evidencia->turno?->grupo?->nombre ?? 'Grupo';
                            $rutaImg = $evidencia->ruta_archivo ? asset('storage/' . $evidencia->ruta_archivo) : asset('img/no-image.png');
                        @endphp
                        <tr>
                            <td>
                                <strong style="color: #0f172a;">#Turno {{ $evidencia->turno_id }}</strong>
                            </td>
                            <td>
                                @if($esAntes)
                                    <span class="badge" style="background: #fef3c7; color: #d97706; font-weight: 700; padding: 5px 12px; border-radius: 6px; font-size: 12px;">
                                        <i class="fas fa-camera me-1"></i> Foto Antes
                                    </span>
                                @else
                                    <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; padding: 5px 12px; border-radius: 6px; font-size: 12px;">
                                        <i class="fas fa-check-circle me-1"></i> Foto Después
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($fichaId)
                                    <a href="/admin/evidencias?ficha_id={{ $fichaId }}" class="text-decoration-none fw-bold" style="color: #109116;" title="Ver todas las evidencias de esta ficha">
                                        Ficha {{ $fichaCod }} <i class="fas fa-arrow-up-right-from-square small ms-1" style="font-size: 10px;"></i>
                                    </a>
                                @else
                                    <strong>Ficha {{ $fichaCod }}</strong>
                                @endif
                                <div class="text-muted small" style="font-size: 11.5px;">
                                    <i class="fas fa-layer-group me-1"></i> {{ $moduloNom }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 12.5px; font-weight: 600; color: #334155;">{{ $fechaFormateada }}</div>
                                <small class="text-muted" style="font-size: 11px;">{{ $grupoNom }}</small>
                            </td>
                            <td>
                                <div style="width: 50px; height: 38px; border-radius: 6px; overflow: hidden; background: #0f172a; cursor: pointer;" onclick="abrirModalPreview('{{ $rutaImg }}', '{{ $esAntes ? 'Foto Antes de Limpieza' : 'Foto Después de Limpieza' }}', '{{ $moduloNom }}', 'Turno #{{ $evidencia->turno_id }}', '{{ $fechaFormateada }}', '{{ $grupoNom }}', '{{ addslashes($evidencia->observaciones ?? 'Sin observaciones') }}')">
                                    <img src="{{ $rutaImg }}" alt="Miniatura" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://placehold.co/100x80/e2e8f0/475569?text=Sin+Foto';">
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div class="d-flex align-items-center justify-content-end gap-1">
                                    <button type="button" class="btn btn-sm btn-outline-success" style="padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 6px;" onclick="abrirModalPreview('{{ $rutaImg }}', '{{ $esAntes ? 'Foto Antes de Limpieza' : 'Foto Después de Limpieza' }}', '{{ $moduloNom }}', 'Turno #{{ $evidencia->turno_id }}', '{{ $fechaFormateada }}', '{{ $grupoNom }}', '{{ addslashes($evidencia->observaciones ?? 'Sin observaciones') }}')">
                                        <i class="fas fa-eye me-1"></i> Ver
                                    </button>
                                    @if($evidencia->ruta_archivo)
                                        <a href="{{ $rutaImg }}" download class="btn btn-sm btn-outline-secondary" style="padding: 4px 8px; border-radius: 6px;" title="Descargar">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center text-muted">
                                <i class="fas fa-folder-open fa-3x mb-2" style="color: #cbd5e1;"></i>
                                <div>No hay evidencias fotográficas registradas en el sistema</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $evidencias->links() }}
    </div>
@endif

<!-- MODAL DE DETALLE DEL DÍA DE LIMPIEZA (INTERACTIVO AL CLIC EN EL CALENDARIO) -->
<div class="modal fade" id="modalDetalleDiaLimpieza" tabindex="-1" aria-labelledby="modalDetalleDiaLimpiezaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 18px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div class="modal-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; border-bottom: none; padding: 20px 26px;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 145, 22, 0.2); color: #4ade80; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalDiaTitulo" style="font-size: 18px;">Detalle de la Fecha de Limpieza</h5>
                        <small id="modalDiaSubtitulo" style="color: #94a3b8; font-size: 13px;">Ficha Académica</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background: #f8fafc; max-height: 75vh;">
                <!-- CONTENEDOR DINÁMICO DE MÓDULOS DEL DÍA -->
                <div id="modalDiaModulosContenedor" class="d-flex flex-column gap-4">
                    <!-- Se inyecta con Javascript -->
                </div>
            </div>
            <div class="modal-footer p-3" style="background: #ffffff; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 600;">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PREVIEW DE EVIDENCIA (LIGHTBOX INDIVIDUAL) -->
<div class="modal fade" id="modalPreviewEvidencia" tabindex="-1" aria-labelledby="modalPreviewEvidenciaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
            <div class="modal-header" style="background: #0f172a; color: #ffffff; border-bottom: none; padding: 16px 24px;">
                <div>
                    <h5 class="modal-title fw-bold" id="modalPreviewTitle" style="font-size: 17px; margin: 0;">Detalle de Evidencia</h5>
                    <small id="modalPreviewSubtitle" style="color: #94a3b8; font-size: 12.5px;">Turno de Limpieza</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="background: #090d16; text-align: center;">
                <img id="modalPreviewImg" src="" alt="Evidencia en alta resolución" style="max-height: 520px; width: 100%; object-fit: contain; background: #020617;">
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center p-3" style="background: #ffffff; border-top: 1px solid #e2e8f0;">
                <div class="text-start" style="font-size: 12.5px; color: #475569;">
                    <div><i class="fas fa-map-marker-alt text-success me-1"></i> <strong id="modalPreviewModulo">-</strong> &bull; <span id="modalPreviewFecha">-</span></div>
                    <div><i class="fas fa-users text-primary me-1"></i> <span id="modalPreviewGrupo">-</span> &bull; <em><span id="modalPreviewObs"></span></em></div>
                </div>
                <div class="d-flex gap-2">
                    <a id="modalPreviewDownload" href="#" download class="btn btn-sm btn-outline-success" style="border-radius: 8px; font-weight: 600;">
                        <i class="fas fa-download me-1"></i> Descargar
                    </a>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    /* Lightbox Modal de Preview de Evidencia: siempre por encima de cualquier otro modal */
    #modalPreviewEvidencia {
        z-index: 1070 !important;
    }
    #modalPreviewEvidencia .modal-dialog {
        z-index: 1071 !important;
    }

    .folder-card {
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .folder-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        border-color: #86efac;
    }
    .folder-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #dcfce7;
        color: #109116;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    /* Píldoras activas en verde SENA */
    #pills-tab-evidencias .nav-link {
        color: #475569;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    #pills-tab-evidencias .nav-link.active {
        background: #109116 !important;
        color: #ffffff !important;
        border-color: #109116 !important;
        box-shadow: 0 4px 12px rgba(16, 145, 22, 0.25);
    }

    /* Estilos del Calendario */
    .cal-header-day {
        font-size: 13.5px;
        font-weight: 700;
        color: #0284c7;
        text-transform: lowercase;
        padding: 8px 4px;
    }

    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        border-left: 1px solid #e2e8f0;
        border-top: 1px solid #e2e8f0;
        background: #ffffff;
    }

    .cal-day-cell {
        min-height: 105px;
        border-right: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        padding: 8px;
        position: relative;
        display: flex;
        flex-column: column;
        transition: background-color 0.15s ease;
    }

    .cal-day-cell.cal-day-otro-mes {
        background-color: #fbfcfd;
    }
    .cal-day-cell.cal-day-otro-mes .cal-day-num {
        color: #cbd5e1 !important;
    }

    .cal-day-cell.cal-day-hoy {
        background-color: #f0fdf4;
    }
    .cal-day-cell.cal-day-hoy .cal-day-num {
        color: #109116 !important;
        font-weight: 800 !important;
    }

    .cal-day-num {
        font-size: 13.5px;
        font-weight: 600;
        color: #334155;
        text-align: right;
        margin-bottom: 4px;
        display: block;
    }

    .cal-badge-evento {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 6px;
        border-radius: 6px;
        margin-bottom: 3px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        line-height: 1.25;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        text-decoration: none;
    }
    .cal-badge-evento:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.12);
    }

    /* Colores del Calendario solicitados */
    .cal-badge-verde {
        background: #dcfce7;
        color: #084d0b;
        border-left: 3px solid #109116;
    }
    .cal-badge-rojo {
        background: #fee2e2;
        color: #991b1b;
        border-left: 3px solid #dc2626;
    }
    .cal-badge-gris {
        background: #f1f5f9;
        color: #475569;
        border-left: 3px solid #94a3b8;
    }

    /* Mini Calendario Año */
    .mini-cal-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .mini-cal-card:hover {
        border-color: #86efac;
        box-shadow: 0 6px 18px rgba(16, 145, 22, 0.12);
        transform: translateY(-2px);
    }
    .mini-cal-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
        text-transform: capitalize;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .mini-cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 3px;
        text-align: center;
        font-size: 10px;
    }
    .mini-cal-dot {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-weight: 600;
    }
    .mini-cal-dot.has-verde { background: #dcfce7; color: #084d0b; font-weight: 800; }
    .mini-cal-dot.has-rojo { background: #fee2e2; color: #991b1b; font-weight: 800; }
    .mini-cal-dot.has-gris { background: #f1f5f9; color: #475569; font-weight: 800; }
</style>
@endsection

@section('scripts')
<script>
    // Buscador de Programas (Nivel 1)
    function filtrarProgramas() {
        const input = document.getElementById('buscarPrograma');
        if (!input) return;
        const filter = input.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.programa-card');
        
        cards.forEach(card => {
            const nombre = card.querySelector('.programa-nombre')?.innerText.toLowerCase() || '';
            if (nombre.includes(filter)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Modal de Preview de Foto Individual (Lightbox)
    function abrirModalPreview(imgSrc, titulo, modulo, turno, fecha, grupo, observaciones) {
        document.getElementById('modalPreviewImg').src = imgSrc;
        document.getElementById('modalPreviewTitle').innerText = titulo;
        document.getElementById('modalPreviewSubtitle').innerText = (turno || '') + (modulo ? ' - ' + modulo : '');
        document.getElementById('modalPreviewModulo').innerText = modulo || '-';
        document.getElementById('modalPreviewFecha').innerText = fecha || '-';
        document.getElementById('modalPreviewGrupo').innerText = grupo || '-';
        document.getElementById('modalPreviewObs').innerText = observaciones ? '"' + observaciones + '"' : 'Sin observaciones adicionales';
        
        const dlBtn = document.getElementById('modalPreviewDownload');
        if (dlBtn) dlBtn.href = imgSrc;

        const modalEl = document.getElementById('modalPreviewEvidencia');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();

        // Elevar el backdrop para que quede por encima de otros modales abiertos
        setTimeout(() => {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            if (backdrops.length > 1) {
                backdrops[backdrops.length - 1].style.zIndex = '1065';
            }
        }, 50);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const previewEl = document.getElementById('modalPreviewEvidencia');
        if (previewEl) {
            previewEl.addEventListener('hidden.bs.modal', function () {
                const detalleEl = document.getElementById('modalDetalleDiaLimpieza');
                if (detalleEl && detalleEl.classList.contains('show')) {
                    document.body.classList.add('modal-open');
                    document.body.style.overflow = 'hidden';
                }
            });
        }
    });

    // =========================================================================
    // MOTOR DEL CALENDARIO INTERACTIVO (NIVEL 3: FICHA)
    // =========================================================================
    @if(isset($eventosCalendario))
        const eventosData = @json($eventosCalendario);
        const fichaActualInfo = {
            id: {{ $ficha->id ?? 'null' }},
            numero: "{{ $ficha->numero_ficha ?? ($ficha->nombre ?? 'N/A') }}",
            nombre: "{{ $ficha->nombre ?? '' }}",
            programa: "{{ $programa->nombre ?? '' }}"
        };

        // Estado del calendario
        const nombresMeses = [
            'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
            'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
        ];

        // Inicializar en la fecha actual (o fecha del primer evento si aplica)
        const fechaHoy = new Date();
        let calAno = fechaHoy.getFullYear();
        let calMes = fechaHoy.getMonth(); // 0-11
        let modoVista = 'mes'; // 'mes' o 'ano'

        // Mapear eventos por fecha (YYYY-MM-DD) para búsquedía instantánea
        const eventosPorFecha = {};
        eventosData.forEach(ev => {
            if (!eventosPorFecha[ev.fecha]) {
                eventosPorFecha[ev.fecha] = [];
            }
            eventosPorFecha[ev.fecha].push(ev);
        });

        function renderCalendario() {
            if (modoVista === 'mes') {
                renderVistaMes();
            } else {
                renderVistaAno();
            }
        }

        function cambiarMes(delta) {
            calMes += delta;
            if (calMes > 11) {
                calMes = 0;
                calAno++;
            } else if (calMes < 0) {
                calMes = 11;
                calAno--;
            }
            renderCalendario();
        }

        function irAHoy() {
            calAno = fechaHoy.getFullYear();
            calMes = fechaHoy.getMonth();
            modoVista = 'mes';
            actualizarBotonesModo();
            renderCalendario();
        }

        function setModoVista(nuevoModo) {
            modoVista = nuevoModo;
            actualizarBotonesModo();
            renderCalendario();
        }

        function actualizarBotonesModo() {
            const btnMes = document.getElementById('btnModoMes');
            const btnAno = document.getElementById('btnModoAno');
            const contMes = document.getElementById('vistaMesContenedor');
            const contAno = document.getElementById('vistaAnoContenedor');

            if (modoVista === 'mes') {
                btnMes.className = 'btn btn-sm btn-success px-3';
                btnMes.style.background = '#109116';
                btnMes.style.color = '#ffffff';
                btnAno.className = 'btn btn-sm btn-outline-secondary px-3';
                btnAno.style.background = '';
                btnAno.style.color = '';
                contMes.style.display = 'block';
                contAno.style.display = 'none';
            } else {
                btnAno.className = 'btn btn-sm btn-success px-3';
                btnAno.style.background = '#109116';
                btnAno.style.color = '#ffffff';
                btnMes.className = 'btn btn-sm btn-outline-secondary px-3';
                btnMes.style.background = '';
                btnMes.style.color = '';
                contMes.style.display = 'none';
                contAno.style.display = 'block';
            }
        }

        function formatFechaIso(ano, mes, dia) {
            const m = String(mes + 1).padStart(2, '0');
            const d = String(dia).padStart(2, '0');
            return `${ano}-${m}-${d}`;
        }

        function renderVistaMes() {
            const tituloEl = document.getElementById('calTituloMes');
            tituloEl.innerText = `${nombresMeses[calMes]} de ${calAno}`;

            const gridEl = document.getElementById('calGridDias');
            gridEl.innerHTML = '';

            const primerDiaSemana = new Date(calAno, calMes, 1).getDay(); // 0 = Domingo
            const totalDiasMes = new Date(calAno, calMes + 1, 0).getDate();
            const diasMesAnterior = new Date(calAno, calMes, 0).getDate();

            const hoyIso = formatFechaIso(fechaHoy.getFullYear(), fechaHoy.getMonth(), fechaHoy.getDate());

            // 1. Días del mes anterior para completar la primera semana
            for (let i = primerDiaSemana - 1; i >= 0; i--) {
                const diaNum = diasMesAnterior - i;
                const prevMes = calMes === 0 ? 11 : calMes - 1;
                const prevAno = calMes === 0 ? calAno - 1 : calAno;
                const fechaIso = formatFechaIso(prevAno, prevMes, diaNum);

                const celda = crearCeldaDia(diaNum, fechaIso, true, false);
                gridEl.appendChild(celda);
            }

            // 2. Días del mes actual
            for (let dia = 1; dia <= totalDiasMes; dia++) {
                const fechaIso = formatFechaIso(calAno, calMes, dia);
                const esHoy = fechaIso === hoyIso;

                const celda = crearCeldaDia(dia, fechaIso, false, esHoy);
                gridEl.appendChild(celda);
            }

            // 3. Días del mes siguiente para completar la cuadrícula de 7 columnas
            const totalCeldasActuales = primerDiaSemana + totalDiasMes;
            const celdasRestantes = (7 - (totalCeldasActuales % 7)) % 7;
            for (let dia = 1; dia <= celdasRestantes; dia++) {
                const nextMes = calMes === 11 ? 0 : calMes + 1;
                const nextAno = calMes === 11 ? calAno + 1 : calAno;
                const fechaIso = formatFechaIso(nextAno, nextMes, dia);

                const celda = crearCeldaDia(dia, fechaIso, true, false);
                gridEl.appendChild(celda);
            }
        }

        function crearCeldaDia(diaNum, fechaIso, esOtroMes, esHoy) {
            const celda = document.createElement('div');
            celda.className = 'cal-day-cell' + (esOtroMes ? ' cal-day-otro-mes' : '') + (esHoy ? ' cal-day-hoy' : '');
            
            // Número del día
            const numSpan = document.createElement('span');
            numSpan.className = 'cal-day-num';
            numSpan.innerText = diaNum;
            celda.appendChild(numSpan);

            // Contenedor de eventos
            const eventosCont = document.createElement('div');
            eventosCont.className = 'd-flex flex-column gap-1 w-100 flex-grow-1';

            const turnosDia = eventosPorFecha[fechaIso] || [];
            if (turnosDia.length > 0) {
                // Hacer la celda interactiva
                celda.style.cursor = 'pointer';
                celda.onclick = () => abrirDetalleDia(fechaIso);

                turnosDia.forEach(turno => {
                    const badge = document.createElement('div');
                    let claseColor = 'cal-badge-gris';
                    let icono = 'fa-clock';

                    if (turno.color === 'verde') {
                        claseColor = 'cal-badge-verde';
                        icono = 'fa-check-circle';
                    } else if (turno.color === 'rojo') {
                        claseColor = 'cal-badge-rojo';
                        icono = 'fa-times-circle';
                    }

                    badge.className = `cal-badge-evento ${claseColor}`;
                    badge.title = `${turno.modulo_nombre}: ${turno.estado_texto}`;
                    
                    const iconoEl = document.createElement('i');
                    iconoEl.className = `fas ${icono} me-1`;
                    badge.appendChild(iconoEl);

                    const texto = document.createElement('span');
                    texto.style.whiteSpace = 'nowrap';
                    texto.style.overflow = 'hidden';
                    texto.style.textOverflow = 'ellipsis';
                    texto.innerText = turno.modulo_nombre;
                    badge.appendChild(texto);

                    eventosCont.appendChild(badge);
                });
            } else {
                // Si hace clic en un día sin turnos, opcionalmente informar
                celda.onclick = () => abrirDetalleDia(fechaIso);
            }

            celda.appendChild(eventosCont);
            return celda;
        }

        function renderVistaAno() {
            const tituloEl = document.getElementById('calTituloMes');
            tituloEl.innerText = `Año ${calAno}`;

            const gridAnoEl = document.getElementById('calGridAno');
            gridAnoEl.innerHTML = '';

            const hoyIso = formatFechaIso(fechaHoy.getFullYear(), fechaHoy.getMonth(), fechaHoy.getDate());

            for (let m = 0; m < 12; m++) {
                const col = document.createElement('div');
                col.className = 'col-md-4 col-sm-6';

                const card = document.createElement('div');
                card.className = 'mini-cal-card h-100';
                card.onclick = () => {
                    calMes = m;
                    setModoVista('mes');
                };

                const title = document.createElement('div');
                title.className = 'mini-cal-title';
                title.innerHTML = `<span>${nombresMeses[m]}</span> <i class="fas fa-arrow-right text-muted small"></i>`;
                card.appendChild(title);

                const miniGrid = document.createElement('div');
                miniGrid.className = 'mini-cal-grid';

                // Headers mini
                ['D','L','M','M','J','V','S'].forEach(d => {
                    const dh = document.createElement('div');
                    dh.style.color = '#94a3b8';
                    dh.style.fontWeight = '700';
                    dh.innerText = d;
                    miniGrid.appendChild(dh);
                });

                const primerDia = new Date(calAno, m, 1).getDay();
                const totalDias = new Date(calAno, m + 1, 0).getDate();

                for (let b = 0; b < primerDia; b++) {
                    const empty = document.createElement('div');
                    miniGrid.appendChild(empty);
                }

                for (let d = 1; d <= totalDias; d++) {
                    const fechaIso = formatFechaIso(calAno, m, d);
                    const turnosDia = eventosPorFecha[fechaIso] || [];

                    const dot = document.createElement('div');
                    dot.className = 'mini-cal-dot';
                    dot.innerText = d;

                    if (turnosDia.length > 0) {
                        const hayVerde = turnosDia.some(t => t.color === 'verde');
                        const hayRojo = turnosDia.some(t => t.color === 'rojo');
                        if (hayVerde) {
                            dot.classList.add('has-verde');
                        } else if (hayRojo) {
                            dot.classList.add('has-rojo');
                        } else {
                            dot.classList.add('has-gris');
                        }
                    }

                    if (fechaIso === hoyIso) {
                        dot.style.border = '1px solid #109116';
                    }

                    miniGrid.appendChild(dot);
                }

                card.appendChild(miniGrid);
                col.appendChild(card);
                gridAnoEl.appendChild(col);
            }
        }

        // =====================================================================
        // DETALLE DEL DÍA DE LIMPIEZA (MODAL INTERACTIVO)
        // =====================================================================
        function abrirDetalleDia(fechaIso) {
            const turnosDia = eventosPorFecha[fechaIso] || [];
            
            // Formatear fecha para el encabezado
            const partes = fechaIso.split('-');
            const fechaObj = new Date(partes[0], partes[1] - 1, partes[2]);
            const opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const fechaTextoLargo = fechaObj.toLocaleDateString('es-ES', opcionesFecha);

            document.getElementById('modalDiaTitulo').innerText = `Limpieza: ${fechaTextoLargo}`;
            document.getElementById('modalDiaSubtitulo').innerText = `Ficha ${fichaActualInfo.numero} • ${fichaActualInfo.programa}`;

            const contModulos = document.getElementById('modalDiaModulosContenedor');
            contModulos.innerHTML = '';

            if (turnosDia.length === 0) {
                contModulos.innerHTML = `
                    <div class="card p-4 text-center text-muted" style="border: 2px dashed #cbd5e1; border-radius: 12px; background: #ffffff;">
                        <i class="fas fa-calendar-xmark fa-3x mb-2" style="color: #94a3b8;"></i>
                        <h6 class="fw-bold mb-1" style="color: #334155;">Sin jornada de limpieza</h6>
                        <small>Esta fecha no tiene turnos programados para los módulos de esta ficha.</small>
                    </div>
                `;
            } else {
                turnosDia.forEach((t, idx) => {
                    const cardModulo = document.createElement('div');
                    cardModulo.className = 'card p-4 shadow-sm';
                    cardModulo.style.borderRadius = '14px';
                    cardModulo.style.background = '#ffffff';
                    cardModulo.style.border = '1px solid #e2e8f0';

                    let bannerHtml = '';
                    let evidenciasHtml = '';

                    // 1. CASO VERDE: EVIDENCIA ENTREGADA
                    if (t.color === 'verde') {
                        bannerHtml = `
                            <div class="p-3 rounded mb-3 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #dcfce7; border: 1px solid #86efac; color: #084d0b;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-check-circle fa-xl text-success"></i>
                                    <div>
                                        <strong style="font-size: 15px;">Evidencias entregadas correctamente</strong>
                                        <div style="font-size: 12px; color: #0d7712;">Turno completado y verificado en el sistema.</div>
                                    </div>
                                </div>
                                <span class="badge" style="background: #109116; color: #ffffff; padding: 6px 12px; border-radius: 6px; font-size: 12px;">
                                    <i class="fas fa-camera me-1"></i> ${t.num_evidencias} fotos registradas
                                </span>
                            </div>
                        `;

                        // Render de fotos (Antes y Después)
                        if (t.evidencias && t.evidencias.length > 0) {
                            let fotosGrid = '<div class="row g-3 mb-2">';
                            t.evidencias.forEach(ev => {
                                const esAntes = ev.tipo === 'antes';
                                const badgeColor = esAntes ? '#fef3c7; color: #92400e;' : '#dcfce7; color: #084d0b;';
                                const badgeText = esAntes ? 'Foto Antes' : 'Foto Después';

                                fotosGrid += `
                                    <div class="col-md-6">
                                        <div class="rounded border p-2" style="background: #f8fafc;">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge" style="background: ${badgeColor}; font-weight: 700; font-size: 11px;">
                                                    <i class="fas ${esAntes ? 'fa-camera' : 'fa-check'} me-1"></i> ${badgeText}
                                                </span>
                                                <small class="text-muted" style="font-size: 11px;">${ev.fecha_subida || ''}</small>
                                            </div>
                                            <div style="height: 170px; border-radius: 8px; overflow: hidden; background: #0f172a; cursor: pointer;" onclick="abrirModalPreview('${ev.ruta_archivo}', '${badgeText} - ${t.modulo_nombre}', '${t.modulo_nombre}', 'Turno #${t.id}', '${ev.fecha_subida || ''}', '${t.grupo_nombre}', '${(ev.observaciones || '').replace(/'/g, "\\'")}')">
                                                <img src="${ev.ruta_archivo}" alt="Evidencia" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://placehold.co/400x300/e2e8f0/475569?text=Sin+Foto';">
                                            </div>
                                            ${ev.observaciones ? `<p class="small text-muted mt-2 mb-0" style="font-size: 11.5px;"><em>"${ev.observaciones}"</em></p>` : ''}
                                        </div>
                                    </div>
                                `;
                            });
                            fotosGrid += '</div>';
                            evidenciasHtml = fotosGrid;
                        }

                    // 2. CASO ROJO: FECHA ANTERIOR Y NO SUBIERON EVIDENCIAS
                    } else if (t.color === 'rojo') {
                        bannerHtml = `
                            <div class="p-3 rounded mb-3" style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b;">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(220, 38, 38, 0.15); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                                        <i class="fas fa-times-circle text-danger"></i>
                                    </div>
                                    <div>
                                        <strong style="font-size: 15.5px; display: block; color: #991b1b; margin-bottom: 2px;">
                                            No subieron evidencias
                                        </strong>
                                        <div style="font-size: 13px; color: #7f1d1d; line-height: 1.4;">
                                            La fecha límite de limpieza para este módulo venció y la ficha <strong>no registró las evidencias fotográficas</strong> correspondientes.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                    // 3. CASO GRIS: FUTURA LIMPIEZA O LIMPIEZA DE HOY EN ESPERA
                    } else {
                        const mensajeGris = t.es_hoy 
                            ? 'La jornada de limpieza corresponde al día de hoy. El vocero y los aprendices asignados tienen habilitada la plataforma para registrar las evidencias fotográficas.'
                            : 'Turno de limpieza programado para una fecha futura. La subida de evidencias se habilitarááá una vez inicie la jornada de limpieza.';

                        bannerHtml = `
                            <div class="p-3 rounded mb-3" style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #334155;">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 44px; height: 44px; border-radius: 10px; background: rgba(148, 163, 184, 0.2); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                                        <i class="fas fa-hourglass-half text-secondary"></i>
                                    </div>
                                    <div>
                                        <strong style="font-size: 15.5px; display: block; color: #1e293b; margin-bottom: 2px;">
                                            En espera de subida de evidencias
                                        </strong>
                                        <div style="font-size: 13px; color: #475569; line-height: 1.4;">
                                            ${mensajeGris}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }

                    // Render integrantes del grupo
                    let integrantesList = '';
                    if (t.integrantes && t.integrantes.length > 0) {
                        integrantesList = t.integrantes.map(nombre => `<span class="badge bg-light text-dark border me-1 mb-1" style="font-size: 11px;"><i class="fas fa-user me-1 text-success"></i> ${nombre}</span>`).join('');
                    } else {
                        integrantesList = '<span class="text-muted small">Sin integrantes asignados</span>';
                    }

                    cardModulo.innerHTML = `
                        <!-- CABECERA DEL MÓDULO -->
                        <div class="d-flex justify-content-between align-items-start mb-3 pb-2 border-bottom">
                            <div>
                                <span class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 700; margin-bottom: 4px;">
                                    Módulo Asignado
                                </span>
                                <h5 class="fw-bold mb-1" style="color: #0f172a; font-size: 17px;">
                                    <i class="fas fa-broom text-success me-2"></i> ${t.modulo_nombre}
                                </h5>
                                <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i> ${t.modulo_ubicacion}</small>
                            </div>
                            <span class="badge" style="background: ${t.color === 'verde' ? '#dcfce7; color: #084d0b;' : (t.color === 'rojo' ? '#fee2e2; color: #991b1b;' : '#f1f5f9; color: #475569;')}; font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 8px;">
                                ${t.estado_texto}
                            </span>
                        </div>

                        <!-- BANNER DEL ESTADO SOLICITADO -->
                        ${bannerHtml}

                        <!-- INFORMACIÓN DE TURNO Y GRUPO -->
                        <div class="row g-2 mb-3 p-3 rounded" style="background: #f8fafc; font-size: 12.5px; border: 1px solid #e2e8f0;">
                            <div class="col-sm-6">
                                <span class="text-muted d-block" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">Grupo Responsable:</span>
                                <strong style="color: #0f172a;"><i class="fas fa-users text-primary me-1"></i> ${t.grupo_nombre}</strong>
                                ${t.vocero_nombre ? `<div class="text-muted small mt-1"><i class="fas fa-crown text-warning me-1"></i> Vocero: ${t.vocero_nombre}</div>` : ''}
                            </div>
                            <div class="col-sm-6">
                                <span class="text-muted d-block" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">Estado del Turno:</span>
                                <div><i class="fas fa-circle me-1" style="color: ${t.color === 'verde' ? '#109116' : (t.color === 'rojo' ? '#dc2626' : '#94a3b8')}; font-size: 10px;"></i> <strong>${t.estado_bd}</strong></div>
                            </div>
                            <div class="col-12 mt-2 pt-2 border-top">
                                <span class="text-muted d-block mb-1" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">Integrantes del Grupo:</span>
                                <div class="d-flex flex-wrap">${integrantesList}</div>
                            </div>
                        </div>

                        <!-- EVIDENCIAS SI FUERON SUBIDÍAS -->
                        ${evidenciasHtml}
                    `;

                    contModulos.appendChild(cardModulo);
                });
            }

            const modalDiaEl = document.getElementById('modalDetalleDiaLimpieza');
            const modalDia = new bootstrap.Modal(modalDiaEl);
            modalDia.show();
        }

        // Inicializar renderizado del calendario al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            renderCalendario();
        });
    @endif
</script>
@endsection
