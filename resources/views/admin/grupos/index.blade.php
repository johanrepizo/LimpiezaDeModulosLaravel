@extends('layouts.app')

@section('title', 'Grupos de Limpieza por Carpeta')

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
                <li class="breadcrumb-item active">Grupos de Limpieza por Programa</li>
            </ol>
        </nav>
    </div>

    <!-- HEADER -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-users-gear" style="color: #109116; font-size: 24px;"></i>
                <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Grupos de Limpieza por Programa</h1>
            </div>
            <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
                Navega por las carpetas de programas para supervisar los equipos de aprendices conformados en cada ficha
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/grupos?view=all" class="btn-brand-outline" style="background: #ffffff;">
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
                <div class="stat-icon-wrapper" style="background: #e0f2fe; color: #0369a1;">
                    <i class="fas fa-users-gear"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $totalGruposSistema }}</div>
                    <div class="stat-label">Total Grupos Conformados</div>
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
                $totalGruposProg = $prog->contarGrupos();
                $totalFichasProg = $prog->fichas->count();
            @endphp
            <div class="col-md-6 col-lg-4 programa-card">
                <div class="card-custom h-100 folder-card" onclick="window.location.href='/admin/grupos?programa_id={{ $prog->id }}'" title="Clic para explorar las fichas de este programa">
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
                            <span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; font-size: 12px; padding: 5px 10px;">
                                <i class="fas fa-users-gear text-primary me-1"></i> {{ $totalGruposProg }} Grupos
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
                    <div>No hay programas registróóados en el sistema</div>
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
                <li class="breadcrumb-item"><a href="/admin/grupos" class="text-decoration-none text-success"><i class="fas fa-users-gear"></i> Grupos por Programa</a></li>
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
                    <span class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 700;"><i class="fas fa-users-gear me-1"></i> {{ $programa->contarGrupos() }} Grupos Totales</span>
                </div>
                <h2 class="h4 fw-bold mb-1" style="color: #0f172a;">{{ $programa->nombre }}</h2>
                <p class="text-muted small mb-0">{{ $programa->descripcion ?? 'Selecciona una ficha para consultar sus grupos de limpieza conformados, aprendices asignados y turnos' }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="/admin/grupos" class="btn-brand-outline">
                    <i class="fas fa-arrow-left"></i> Volver a Programas
                </a>
                <a href="/admin/grupos?view=all" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1" style="border-radius: 8px;">
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
        <small class="text-muted">Haz clic en una ficha para consultar y supervisar sus grupos de limpieza</small>
    </div>

    @if($fichas->isEmpty())
        <div class="card-custom py-5 text-center text-muted">
            <i class="fas fa-folder-open fa-3x mb-2" style="color: #cbd5e1;"></i>
            <div>No hay fichas registróóadías para este programa</div>
        </div>
    @else
        <div class="row g-4">
            @foreach($fichas as $f)
                @php
                    $numGruposFicha = $f->contarGruposActivos();
                    $modulosActivos = $f->asignaciones->where('activo', true);
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card-custom h-100 folder-card" onclick="window.location.href='/admin/grupos?ficha_id={{ $f->id }}'" title="Clic para ver los grupos de la ficha {{ $f->numero_ficha }}">
                        <div class="card-custom-body p-4 d-flex flex-column h-100">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div style="width: 44px; height: 44px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                    <i class="fas fa-users-gear"></i>
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
                                        <i class="fas fa-users text-primary me-1"></i> Grupos Conformados:
                                    </span>
                                    <span class="badge {{ $numGruposFicha > 0 ? 'bg-primary' : 'bg-secondary' }}" style="font-size: 12px; font-weight: 700; padding: 5px 10px; border-radius: 6px;">
                                        {{ $numGruposFicha }} grupos
                                    </span>
                                </div>
                            </div>

                            <div class="pt-3 border-top">
                                <a href="/admin/grupos?ficha_id={{ $f->id }}" class="btn-brand-outline w-100 justify-content-center">
                                    <i class="fas fa-users-viewfinder me-1"></i> Ver Grupos de Limpieza <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

{{-- ========================================================================= --}}
{{-- NIVEL 3: DETALLE DE GRUPOS DE LA FICHA                                     --}}
{{-- ========================================================================= --}}
@elseif($nivel === 'ficha')
    <!-- BREADCRUMB -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/admin/grupos" class="text-decoration-none text-success"><i class="fas fa-users-gear"></i> Grupos por Programa</a></li>
                <li class="breadcrumb-item"><a href="/admin/grupos?programa_id={{ $programa->id }}" class="text-decoration-none text-success">{{ Str::limit($programa->nombre, 25) }}</a></li>
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
                    <span class="badge" style="background: #f1f5f9; color: #334155; font-size: 11px; font-weight: 700;">
                        <i class="fas fa-users-gear text-primary me-1"></i> {{ $totalGruposFicha }} Grupos Activos
                    </span>
                </div>
                <h2 class="h4 fw-bold mb-1" style="color: #0f172a;">{{ $ficha->nombre }}</h2>
                <div class="text-muted small">
                    <strong>Programa:</strong> {{ $programa->nombre }}
                    @if($ficha->asignaciones && $ficha->asignaciones->where('activo', true)->count() > 0)
                        &bull; <strong>Módulos Asignados:</strong> 
                        @foreach($ficha->asignaciones->where('activo', true) as $asig)
                            <span class="badge bg-light text-dark border ms-1">{{ $asig->modulo->nombre }} (Cap: {{ $asig->modulo->capacidad ?? $asig->modulo->capacidad_maxima ?? 'N/A' }})</span>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="/admin/grupos?programa_id={{ $programa->id }}" class="btn-brand-outline">
                    <i class="fas fa-arrow-left"></i> Volver a Fichas
                </a>
            </div>
        </div>
    </div>

    <!-- FILTRO Y SELECTOR RÁPIDO DE FICHA -->
    <div class="card-custom mb-4 p-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-filter text-success"></i>
                <span class="fw-bold" style="font-size: 14px; color: #0f172a;">Grupos de Limpieza Registrados:</span>
                <span class="badge bg-success" style="font-size: 12px;">{{ $totalGruposFicha }} Activos</span>
            </div>

            <!-- Selector para cambiar de ficha rápidíamente -->
            <div class="d-flex align-items-center gap-2" style="min-width: 280px;">
                <label for="cambiarFicha" class="small text-muted fw-bold mb-0 text-nowrap"><i class="fas fa-folder me-1 text-success"></i> Cambiar Ficha:</label>
                <select id="cambiarFicha" class="form-select form-select-sm" onchange="if(this.value) window.location.href='/admin/grupos?ficha_id=' + this.value;">
                    @foreach($fichas as $f)
                        <option value="{{ $f->id }}" {{ $f->id == $ficha->id ? 'selected' : '' }}>
                            Ficha {{ $f->numero_ficha ?? $f->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- GRID DE TARJETAS DE GRUPOS -->
    @if($grupos->isEmpty())
        <div class="card-custom py-5 text-center text-muted">
            <i class="fas fa-users-slash fa-3x mb-2" style="color: #cbd5e1;"></i>
            <div class="fw-bold mb-1">No hay grupos de limpieza conformados</div>
            <small>El vocero de esta ficha aún no ha creado grupos de aprendices para la rotación de turnos.</small>
        </div>
    @else
        <div class="row g-4">
            @foreach($grupos as $grupo)
                @php
                    $fechaProxima = $grupo->fecha_limpieza ? \Carbon\Carbon::parse($grupo->fecha_limpieza)->format('d/m/Y') : 'Por programar';
                    $moduloNom = $grupo->asignacion?->modulo?->nombre ?? 'Módulo General';
                    $voceroNom = $grupo->vocero?->aprendiz 
                        ? ($grupo->vocero->aprendiz->nombres . ' ' . $grupo->vocero->aprendiz->apellidos) 
                        : 'Vocero de la Ficha';
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="card-custom h-100 d-flex flex-column" style="border: 1px solid #e2e8f0; overflow: hidden;">
                        <!-- HEADER DE LA TARJETA DEL GRUPO (SENA STYLE) -->
                        <div class="p-3 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #109116 0%, #0a5c0e 100%); color: #ffffff;">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-users" style="color: #4ade80; font-size: 18px;"></i>
                                <h5 class="fw-bold mb-0" style="font-size: 16px;">{{ $grupo->nombre }}</h5>
                            </div>
                            <span class="badge" style="background: rgba(255, 255, 255, 0.2); color: #ffffff; font-weight: 700; font-size: 11px;">
                                Grupo #{{ $grupo->numero_grupo ?? '1' }}
                            </span>
                        </div>

                        <!-- CUERPO DE LA TARJETA -->
                        <div class="p-4 d-flex flex-column flex-grow-1">
                            <!-- MÓDULO ASIGNADO -->
                            <div class="mb-3">
                                <small class="text-muted d-block" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">Módulo Asignado:</small>
                                <strong style="color: #0f172a; font-size: 14px;">
                                    <i class="fas fa-broom text-success me-1"></i> {{ $moduloNom }}
                                </strong>
                            </div>

                            <!-- PRÓXIMA FECHA DE LIMPIEZA -->
                            <div class="mb-3">
                                <small class="text-muted d-block" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">Próxima Fecha de Limpieza:</small>
                                <span class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 6px;">
                                    <i class="fas fa-calendar-day me-1"></i> {{ $fechaProxima }}
                                </span>
                            </div>

                            <!-- VOCERO -->
                            <div class="mb-3">
                                <small class="text-muted d-block" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">Vocero a Cargo:</small>
                                <span style="color: #334155; font-size: 13px;">
                                    <i class="fas fa-crown text-warning me-1"></i> {{ $voceroNom }}
                                </span>
                            </div>

                            <!-- INTEGRANTES DEL GRUPO -->
                            <div class="mt-auto pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">
                                        Integrantes ({{ $grupo->integrantes->count() }}):
                                    </small>
                                    <span class="badge" style="background: #dcfce7; color: #084d0b; font-size: 11px; font-weight: 700;">
                                        Activo
                                    </span>
                                </div>
                                <div class="d-flex flex-column gap-1" style="max-height: 180px; overflow-y: auto;">
                                    @forelse($grupo->integrantes as $integ)
                                        <div class="d-flex align-items-center gap-2 p-1 rounded" style="background: #f8fafc; font-size: 12.5px;">
                                            <i class="fas fa-user text-success" style="font-size: 11px;"></i>
                                            <span style="color: #1e293b; font-weight: 500;">{{ $integ->nombres }} {{ $integ->apellidos }}</span>
                                        </div>
                                    @empty
                                        <div class="text-muted small">Sin integrantes vinculados</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $grupos->links() }}
        </div>
    @endif

{{-- ========================================================================= --}}
{{-- NIVEL 4: MODO LISTADO GLOBAL (TABLA COMPLETA)                             --}}
{{-- ========================================================================= --}}
@elseif($nivel === 'todos')
    <!-- BREADCRUMB -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/admin/grupos" class="text-decoration-none text-success">Grupos</a></li>
                <li class="breadcrumb-item active">Listado Global</li>
            </ol>
        </nav>
    </div>

    <!-- HEADER -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-list-check" style="color: #109116; font-size: 24px;"></i>
                <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Listado Global de Grupos de Limpieza</h1>
            </div>
            <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
                Vista general consolidíadía de todos los equipos de limpieza registróóados en el centro
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/grupos" class="btn-brand-outline">
                <i class="fas fa-folder-tree me-1"></i> Ver por Carpetas de Programas
            </a>
        </div>
    </div>

    <!-- FILTROS DE BÚSQUEDA GLOBAL -->
    <div class="card-custom mb-4 p-3">
        <form method="GET" action="/admin/grupos" class="row g-2 align-items-center">
            <input type="hidden" name="view" value="all">
            <div class="col-md-8">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute" style="left: 12px; top: 10px; color: #94a3b8;"></i>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control form-control-sm ps-5" placeholder="Buscar por nombre de grupo, ficha, vocero o módulo..." style="border-radius: 8px;">
                </div>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-success px-3" style="border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-filter me-1"></i> Filtrar
                </button>
                @if(request('buscar'))
                    <a href="/admin/grupos?view=all" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;">
                        <i class="fas fa-times me-1"></i> Limpiar
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABLA DE GRUPOS GLOBAL -->
    <div class="card-custom">
        <div class="table-responsive">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th style="width: 22%;">GRUPO</th>
                        <th style="width: 22%;">VOCERO A CARGO</th>
                        <th style="width: 26%;">FICHA ACADÉMICA &bull; PROGRAMA</th>
                        <th style="width: 18%;">INTEGRANTES</th>
                        <th style="width: 12%; text-align: right;">ESTADO</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grupos as $grupo)
                        @php
                            $fichaRef = $grupo->vocero?->ficha;
                        @endphp
                        <tr>
                            <td>
                                <strong style="color: #0f172a; font-size: 14.5px;">{{ $grupo->nombre }}</strong>
                                @if($grupo->fecha_limpieza)
                                    <small class="text-muted d-block"><i class="fas fa-calendar-alt me-1"></i> Turno: {{ \Carbon\Carbon::parse($grupo->fecha_limpieza)->format('d/m/Y') }}</small>
                                @endif
                            </td>
                            <td>
                                <span style="font-weight: 500; color: #1e293b;">
                                    <i class="fas fa-crown text-warning me-1"></i>
                                    {{ $grupo->vocero->aprendiz->nombres ?? 'No asignado' }} {{ $grupo->vocero->aprendiz->apellidos ?? '' }}
                                </span>
                            </td>
                            <td>
                                @if($fichaRef)
                                    <a href="/admin/grupos?ficha_id={{ $fichaRef->id }}" class="text-decoration-none fw-bold" style="color: #109116;" title="Ver todos los grupos de esta ficha">
                                        Ficha {{ $fichaRef->numero_ficha ?? $fichaRef->nombre }} <i class="fas fa-arrow-up-right-from-square small ms-1" style="font-size: 10px;"></i>
                                    </a>
                                    <small class="text-muted d-block" style="font-size: 11.5px;">{{ Str::limit($fichaRef->programa->nombre ?? '', 32) }}</small>
                                @else
                                    <span class="text-muted">Ficha General</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge" style="background: #e0f2fe; color: #0284c7; font-weight: 600; font-size: 12px;">
                                    <i class="fas fa-users me-1"></i> {{ $grupo->integrantes ? $grupo->integrantes->count() : 0 }} aprendices
                                </span>
                            </td>
                            <td style="text-align: right;">
                                @if($grupo->activo)
                                    <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px;">
                                        Activo
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center text-muted">
                                <i class="fas fa-users-slash fa-3x mb-2" style="color: #cbd5e1;"></i>
                                <div>No hay grupos de limpieza registróóados en el sistema</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $grupos->links() }}
    </div>
@endif

@endsection

@section('styles')
<style>
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
</style>
@endsection

@section('scripts')
<script>
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
</script>
@endsection
