@extends('layouts.app')

@section('title', 'Aprendices y Voceros - Estructura de Formación')

@section('content')

@if($nivel === 'programas')
    <!-- ================= NIVEL 1: PROGRAMAS DE FORMACIÓN ================= -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item active">Aprendices y Voceros por Programa</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-user-graduate" style="color: #109116; font-size: 24px;"></i>
                <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Aprendices y Voceros por Programa</h1>
            </div>
            <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
                Navega por las carpetas de programas para consultar los aprendices y gestionar la vocería de cada ficha (máx. 2 voceros por ficha).
            </p>
        </div>
        <div>
            <a href="/admin/aprendices?view=all" class="btn-brand-outline">
                <i class="fas fa-list me-1"></i> Ver Listado Global
            </a>
        </div>
    </div>

    <!-- GRID DE PROGRAMAS (CARPETAS) -->
    <div class="row g-4">
        @forelse($programas as $prog)
            @php
                $totalAprendicesProg = $prog->fichas->sum(fn($f) => $f->aprendices->count());
                $totalVocerosProg = $prog->fichas->sum(fn($f) => $f->voceros->count());
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card-custom h-100 folder-card" onclick="window.location.href='/admin/aprendices?programa_id={{ $prog->id }}'">
                    <div class="card-custom-body d-flex flex-column h-100 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="folder-icon-box">
                                <i class="fas fa-folder"></i>
                            </div>
                            <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 6px;">
                                {{ $prog->nivel ?? 'Programa Activo' }}
                            </span>
                        </div>

                        <h5 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">{{ $prog->nombre }}</h5>
                        <p class="text-secondary small mb-3 flex-grow-1" style="line-height: 1.45;">
                            {{ Str::limit($prog->descripcion ?? 'Programa de formación técnica del Centro de Formación', 85) }}
                        </p>

                        <!-- Indicadores de la carpeta -->
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            <span class="badge bg-light text-dark border">
                                <i class="fas fa-layer-group text-success me-1"></i> {{ $prog->fichas->count() }} Fichas
                            </span>
                            <span class="badge bg-light text-dark border">
                                <i class="fas fa-user-graduate text-primary me-1"></i> {{ $totalAprendicesProg }} Aprendices
                            </span>
                            <span class="badge" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                <i class="fas fa-crown text-warning me-1"></i> {{ $totalVocerosProg }} Voceros
                            </span>
                        </div>

                        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                            <span class="text-muted small">Abrir programa</span>
                            <a href="/admin/aprendices?programa_id={{ $prog->id }}" class="btn-brand-outline" style="padding: 4px 12px; font-size: 12px;">
                                Ver Fichas <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center text-muted">
                <i class="fas fa-folder-open fa-3x mb-2" style="color: #cbd5e1;"></i>
                <div>No hay programas registróóados</div>
            </div>
        @endforelse
    </div>

@elseif($nivel === 'fichas')
    <!-- ================= NIVEL 2: FICHAS DEL PROGRAMA ================= -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/admin/aprendices" class="text-decoration-none text-success">Programas</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($programa->nombre, 35) }}</li>
            </ol>
        </nav>
    </div>

    <!-- BANNER DEL PROGRAMA -->
    <div class="card-custom mb-4" style="border-left: 4px solid #109116;">
        <div class="card-custom-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge" style="background: #dcfce7; color: #084d0b; font-size: 11px; font-weight: 700; margin-bottom: 6px;">Programa de Formación</span>
                <h2 class="h4 fw-bold mb-1" style="color: #0f172a;">{{ $programa->nombre }}</h2>
                <p class="text-muted small mb-0">{{ $programa->descripcion ?? 'Selecciona una ficha para consultar sus aprendices y gestionar hasta 2 voceros' }}</p>
            </div>
            <div>
                <a href="/admin/aprendices" class="btn-brand-outline">
                    <i class="fas fa-arrow-left"></i> Volver a Programas
                </a>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold m-0" style="color: #0f172a;">
            <i class="fas fa-folder-open text-success me-2"></i> Fichas de este Programa ({{ $fichas->count() }})
        </h5>
        <small class="text-muted">Haz clic en una ficha para gestionar sus aprendices y voceros</small>
    </div>

    <!-- GRID DE FICHAS -->
    <div class="row g-4">
        @forelse($fichas as $f)
            @php
                $numVocerosFicha = $f->voceros->count();
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card-custom h-100 folder-card" onclick="window.location.href='/admin/aprendices?ficha_id={{ $f->id }}'">
                    <div class="card-custom-body p-4 d-flex flex-column h-100">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="folder-icon-box" style="background: #e0f2fe; color: #0284c7;">
                                <i class="fas fa-folder-open"></i>
                            </div>
                            <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 6px;">
                                {{ $f->jornada ?? 'Diurna' }}
                            </span>
                        </div>

                        <h5 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">{{ $f->nombre }}</h5>
                        <div class="text-muted small mb-3">
                            <i class="fas fa-barcode me-1"></i> Ficha: <strong>{{ $f->numero_ficha ?? 'S/N' }}</strong>
                        </div>

                        <!-- Métricas de la ficha -->
                        <div class="d-flex justify-content-between align-items-center p-2 rounded mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div>
                                <small class="text-muted d-block" style="font-size: 11px;">Aprendices</small>
                                <span class="fw-bold text-dark"><i class="fas fa-user-graduate text-primary me-1"></i> {{ $f->aprendices->count() }}</span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted d-block" style="font-size: 11px;">Voceros (Máx. 2)</small>
                                @if($numVocerosFicha >= 2)
                                    <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700;">
                                        <i class="fas fa-check-circle me-1"></i> 2 de 2 Asignados
                                    </span>
                                @elseif($numVocerosFicha == 1)
                                    <span class="badge" style="background: #fef3c7; color: #92400e; font-weight: 700;">
                                        <i class="fas fa-info-circle me-1"></i> 1 de 2 Asignado
                                    </span>
                                @else
                                    <span class="badge" style="background: #fee2e2; color: #991b1b; font-weight: 700;">
                                        <i class="fas fa-exclamation-triangle me-1"></i> 0 de 2 Asignados
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                            <span class="text-muted small">Gestionar ficha</span>
                            <a href="/admin/aprendices?ficha_id={{ $f->id }}" class="btn-brand-outline" style="padding: 4px 12px; font-size: 12px;">
                                Ver Aprendices <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center text-muted">
                <i class="fas fa-folder fa-3x mb-2" style="color: #cbd5e1;"></i>
                <div>No hay fichas vinculadas a este programa</div>
            </div>
        @endforelse
    </div>

@elseif($nivel === 'ficha')
    <!-- ================= NIVEL 3: DETALLE DE LA FICHA (APRENDICES Y VOCEROS) ================= -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/admin/aprendices" class="text-decoration-none text-success">Programas</a></li>
                <li class="breadcrumb-item"><a href="/admin/aprendices?programa_id={{ $programa->id }}" class="text-decoration-none text-success">{{ Str::limit($programa->nombre, 25) }}</a></li>
                <li class="breadcrumb-item active">Ficha {{ $ficha->numero_ficha ?? $ficha->nombre }}</li>
            </ol>
        </nav>
    </div>

    <!-- BANNER DE LA FICHA -->
    <div class="card-custom mb-4" style="border-left: 4px solid #109116;">
        <div class="card-custom-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px;">Ficha {{ $ficha->numero_ficha }}</span>
                    <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 11px;">{{ $ficha->jornada ?? 'Diurna' }}</span>
                </div>
                <h2 class="h4 fw-bold mb-1" style="color: #0f172a;">{{ $ficha->nombre }}</h2>
                <small class="text-muted"><i class="fas fa-graduation-cap me-1"></i> {{ $programa->nombre }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="/admin/modulos?ficha_id={{ $ficha->id }}" class="btn-brand-outline" title="Ver Módulos de Limpieza asignados">
                    <i class="fas fa-door-open me-1"></i> Módulos
                </a>
                <a href="/admin/aprendices?programa_id={{ $programa->id }}" class="btn-brand-outline">
                    <i class="fas fa-arrow-left"></i> Volver a Fichas
                </a>
            </div>
        </div>
    </div>

    <!-- PANEL DE ESTADO DE VOCERÍA (MÁXIMO 2 VOCEROS) -->
    <div class="card-custom mb-4" style="background: #ffffff; border: 1px solid #e2e8f0;">
        <div class="card-custom-header d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: #fafcfa; border-bottom: 1px solid #edf2ed;">
            <div class="d-flex align-items-center gap-2">
                <div style="width: 32px; height: 32px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                    <i class="fas fa-crown"></i>
                </div>
                <div>
                    <h5 class="fw-bold m-0" style="color: #0f172a; font-size: 15px;">Voceros Designados de la Ficha</h5>
                    <small class="text-muted">Regla: Cada ficha debe tener <strong>máximo 2 voceros activos</strong></small>
                </div>
            </div>
            <div>
                @if($vocerosCount >= 2)
                    <span class="badge" style="background: #dcfce7; color: #084d0b; font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 8px;">
                        <i class="fas fa-lock me-1"></i> 2 de 2 Voceros (Cupo Completo)
                    </span>
                @elseif($vocerosCount == 1)
                    <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 8px;">
                        <i class="fas fa-user-plus me-1"></i> 1 de 2 Voceros (1 Cupo Disponible)
                    </span>
                @else
                    <span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 8px;">
                        <i class="fas fa-exclamation-triangle me-1"></i> 0 de 2 Voceros (2 Cupos Disponibles)
                    </span>
                @endif
            </div>
        </div>

        <div class="p-3">
            <div class="row g-3">
                <!-- TARJETAS DE VOCEROS ACTUALES -->
                @foreach($vocerosActivos as $v)
                    <div class="col-md-6">
                        <div class="p-3 rounded border h-100 d-flex flex-column justify-content-between" style="background: #fbfdfb; border-color: #bbf7d0 !important;">
                            <div class="d-flex align-items-start justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 42px; height: 42px; border-radius: 50%; background: #dcfce7; color: #109116; display: flex; align-items: center; justify-content: center; font-size: 17px; border: 2px solid #86efac;">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 15px;">{{ $v->aprendiz->nombres }} {{ $v->aprendiz->apellidos }}</div>
                                        <small class="text-muted d-block">Doc: {{ $v->aprendiz->documento }}</small>
                                    </div>
                                </div>
                                <span class="badge" style="background: #109116; color: #ffffff; font-size: 10.5px; font-weight: 700;">
                                    <i class="fas fa-crown me-1"></i> Vocero
                                </span>
                            </div>

                            <div class="py-2 px-3 rounded mb-3" style="background: #ffffff; border: 1px solid #e2e8f0; font-size: 12.5px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted"><i class="fas fa-envelope me-1"></i> Acceso:</span>
                                    <strong class="text-dark">{{ $v->usuario->correo ?? 'Sin usuario' }}</strong>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <!-- Reenviar credenciales -->
                                <form method="POST" action="/admin/vocero/reenviar-credenciales" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $v->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary" style="font-size: 12px; border-radius: 6px;">
                                        <i class="fas fa-key me-1"></i> Reenviar Contraseña
                                    </button>
                                </form>

                                <!-- Cambiar a Aprendiz -->
                                <form method="POST" action="/admin/aprendiz/cambiar-rol" class="d-inline"
                                      data-confirm="¿¿¿Confirmas que deseas pasar a <strong>{{ $v->aprendiz->nombres }} {{ $v->aprendiz->apellidos }}</strong> al rol de Aprendiz regular?<br><br><small class='text-muted'>Perderáá los permiésos de vocero y se liberaráá un cupo en la ficha.</small>"
                                      data-confirm-title="Cambiar a Aprendiz Regular"
                                      data-confirm-icon="warning"
                                      data-confirm-btn="Sí, Cambiar a Aprendiz"
                                      data-confirm-color="#d97706">
                                    @csrf
                                    <input type="hidden" name="aprendiz_id" value="{{ $v->aprendiz->id }}">
                                    <input type="hidden" name="nuevo_rol" value="aprendiz">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="font-size: 12px; border-radius: 6px;">
                                        <i class="fas fa-user-minus me-1"></i> Cambiar a Aprendiz
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- ESPACIOS DISPONIBLES (SI HAY MENOS DE 2) -->
                @for($i = $vocerosCount; $i < 2; $i++)
                    <div class="col-md-6">
                        <div class="p-3 rounded border h-100 d-flex flex-column align-items-center justify-content-center text-center" style="border: 2px dashed #cbd5e1 !important; background: #f8fafc; min-height: 140px;">
                            <div style="width: 38px; height: 38px; border-radius: 50%; background: #e2e8f0; color: #94a3b8; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 8px;">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h6 class="fw-bold mb-1" style="color: #475569; font-size: 13.5px;">Cupo de Vocero Disponible</h6>
                            <p class="text-muted small mb-0" style="max-width: 280px; font-size: 11.5px;">
                                Puedes promover a cualquier aprendiz de la tabla inferior haciendo clic en <strong>"Asignar como Vocero"</strong>.
                            </p>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- TABLA DE APRENDICES Y VOCEROS DE LA FICHA -->
    <div class="card-custom">
        <div class="card-custom-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="card-custom-title">
                <i class="fas fa-users" style="color: #109116; font-size: 18px;"></i>
                <span>Aprendices de la Ficha ({{ $aprendices->total() }})</span>
            </div>

            <!-- Filtros y Búsquedía -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Filtros por Rol -->
                <div class="btn-group btn-group-sm" role="group">
                    <a href="/admin/aprendices?ficha_id={{ $ficha->id }}" class="btn {{ !request('rol') ? 'btn-success' : 'btn-outline-secondary' }}">
                        Todos ({{ $totalAprendicesFicha }})
                    </a>
                    <a href="/admin/aprendices?ficha_id={{ $ficha->id }}&rol=vocero" class="btn {{ request('rol') === 'vocero' ? 'btn-success' : 'btn-outline-secondary' }}">
                        <i class="fas fa-crown me-1 text-warning"></i> Voceros ({{ $vocerosCount }})
                    </a>
                    <a href="/admin/aprendices?ficha_id={{ $ficha->id }}&rol=aprendiz" class="btn {{ request('rol') === 'aprendiz' ? 'btn-success' : 'btn-outline-secondary' }}">
                        Aprendices ({{ $totalAprendicesFicha - $vocerosCount }})
                    </a>
                </div>

                <!-- Buscador -->
                <form method="GET" action="/admin/aprendices" class="d-flex gap-1">
                    <input type="hidden" name="ficha_id" value="{{ $ficha->id }}">
                    @if(request('rol'))
                        <input type="hidden" name="rol" value="{{ request('rol') }}">
                    @endif
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar aprendiz..." class="form-control form-control-sm" style="width: 200px;">
                    <button type="submit" class="btn btn-sm btn-outline-success"><i class="fas fa-search"></i></button>
                    @if(request('buscar'))
                        <a href="/admin/aprendices?ficha_id={{ $ficha->id }}{{ request('rol') ? '&rol='.request('rol') : '' }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times"></i></a>
                    @endif
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th style="width: 30%;">APRENDIZ</th>
                        <th style="width: 18%;">DOCUMENTO</th>
                        <th style="width: 24%;">CORREO INSTITUCIONAL</th>
                        <th style="width: 14%;">ROL ACTUAL</th>
                        <th style="width: 14%; text-align: right;">CAMBIAR ROL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aprendices as $ap)
                        @php
                            $esVoceroActivo = $ap->vocero && $ap->vocero->activo;
                        @endphp
                        <tr style="{{ $esVoceroActivo ? 'background-color: #f7fdf8;' : '' }}">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $esVoceroActivo ? '#dcfce7' : '#f1f5f9' }}; color: {{ $esVoceroActivo ? '#109116' : '#64748b' }}; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                                        <i class="fas {{ $esVoceroActivo ? 'fa-crown' : 'fa-user-graduate' }}"></i>
                                    </div>
                                    <div>
                                        <strong style="color: #0f172a; font-size: 13.5px;">{{ $ap->nombres }} {{ $ap->apellidos }}</strong>
                                        @if($ap->telefono)
                                            <small class="text-muted d-block" style="font-size: 11px;"><i class="fas fa-phone-alt me-1"></i> {{ $ap->telefono }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $ap->documento }}</span>
                            </td>
                            <td>
                                <span style="color: #475569; font-size: 13px;">{{ $ap->correo ?? 'Sin correo' }}</span>
                                @if($esVoceroActivo && $ap->vocero->usuario)
                                    <small class="text-muted d-block" style="font-size: 11px;"><i class="fas fa-key text-success me-1"></i> Acceso: {{ $ap->vocero->usuario->correo }}</small>
                                @endif
                            </td>
                            <td>
                                @if($esVoceroActivo)
                                    <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px; padding: 5px 10px; border-radius: 6px; border: 1px solid #86efac;">
                                        <i class="fas fa-crown text-warning me-1"></i> Vocero Activo
                                    </span>
                                @else
                                    <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 600; font-size: 11px; padding: 5px 10px; border-radius: 6px;">
                                        <i class="fas fa-user-graduate me-1 text-primary"></i> Aprendiz
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($esVoceroActivo)
                                    <!-- Botón para Cambiar de Vocero a Aprendiz -->
                                    <form method="POST" action="/admin/aprendiz/cambiar-rol" class="d-inline"
                                          data-confirm="¿¿¿Confirmas cambiar a <strong>{{ $ap->nombres }} {{ $ap->apellidos }}</strong> a Aprendiz regular?<br><br><small class='text-muted'>Se revocarááá su acceso como vocero y quedíaráá un cupo libre en la ficha.</small>"
                                          data-confirm-title="Revocar Rol de Vocero"
                                          data-confirm-icon="warning"
                                          data-confirm-btn="Sí, Cambiar a Aprendiz"
                                          data-confirm-color="#d97706">
                                        @csrf
                                        <input type="hidden" name="aprendiz_id" value="{{ $ap->id }}">
                                        <input type="hidden" name="nuevo_rol" value="aprendiz">
                                        <button type="submit" class="btn btn-sm btn-outline-warning text-dark" style="padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 6px;" title="Cambiar a Aprendiz regular">
                                            <i class="fas fa-arrow-down me-1"></i> Hacer Aprendiz
                                        </button>
                                    </form>
                                @else
                                    <!-- Botón para Cambiar de Aprendiz a Vocero -->
                                    @if($vocerosCount < 2)
                                        <form method="POST" action="/admin/aprendiz/cambiar-rol" class="d-inline"
                                              data-confirm="¿¿¿Deseas asignar a <strong>{{ $ap->nombres }} {{ $ap->apellidos }}</strong> como Vocero de la Ficha?<br><br><small class='text-muted'>Se generarááá su usuario de acceso al sistema con su correo institucional.</small>"
                                              data-confirm-title="Designar como Vocero"
                                              data-confirm-icon="question"
                                              data-confirm-btn="Sí, Asignar Vocero"
                                              data-confirm-color="#109116">
                                            @csrf
                                            <input type="hidden" name="aprendiz_id" value="{{ $ap->id }}">
                                            <input type="hidden" name="nuevo_rol" value="vocero">
                                            <button type="submit" class="btn btn-sm btn-outline-success" style="padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 6px;" title="Asignar como Vocero líder">
                                                <i class="fas fa-crown me-1 text-warning"></i> Hacer Vocero
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Esta ficha ya cuenta con el número máximo de 2 voceros activos. Cambia a uno de los voceros actuales a aprendiz para habilitaráá un cupo." style="padding: 4px 10px; font-size: 12px; border-radius: 6px; opacity: 0.6;">
                                            <i class="fas fa-lock me-1"></i> Máx. 2 Voceros
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center text-muted">
                                <i class="fas fa-users-slash fa-3x mb-2" style="color: #cbd5e1;"></i>
                                <div>No se encontraron registróóos con los criterios seleccionados</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $aprendices->links() }}
    </div>

@else
    <!-- ================= MODO LISTADO GLOBAL ================= -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-users" style="color: #109116; font-size: 24px;"></i>
                <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Listado Global de Aprendices y Voceros</h1>
            </div>
            <p class="text-muted small mb-0">Vista general de todos los aprendices y voceros activos en el sistema</p>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/aprendices" class="btn-brand-outline">
                <i class="fas fa-folder-tree me-1"></i> Ver por Carpetas de Programas
            </a>
        </div>
    </div>

    <!-- Barra de búsquedía y filtros para listado global -->
    <div class="card-custom mb-4">
        <div class="card-custom-body p-3">
            <form method="GET" action="/admin/aprendices" class="row g-2 align-items-center">
                <input type="hidden" name="view" value="all">
                <div class="col-md-5">
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar por nombre o documento..." class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
                    <select name="rol" class="form-select form-select-sm">
                        <option value="">Todos los Roles</option>
                        <option value="vocero" {{ request('rol') === 'vocero' ? 'selected' : '' }}>Solo Voceros</option>
                        <option value="aprendiz" {{ request('rol') === 'aprendiz' ? 'selected' : '' }}>Solo Aprendices</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn-brand-solid btn-sm">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    @if(request('buscar') || request('rol'))
                        <a href="/admin/aprendices?view=all" class="btn-brand-outline btn-sm">
                            <i class="fas fa-times me-1"></i> Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card-custom">
        <div class="table-responsive">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th>APRENDIZ</th>
                        <th>DOCUMENTO</th>
                        <th>CORREO</th>
                        <th>FICHA Y PROGRAMA</th>
                        <th>ROL ACTUAL</th>
                        <th style="text-align: right;">GESTIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aprendices as $ap)
                        @php
                            $esVoceroActivo = $ap->vocero && $ap->vocero->activo;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $ap->nombres }} {{ $ap->apellidos }}</strong>
                                @if($ap->telefono)
                                    <small class="text-muted d-block"><i class="fas fa-phone-alt me-1"></i> {{ $ap->telefono }}</small>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $ap->documento }}</span></td>
                            <td>{{ $ap->correo ?? 'Sin correo' }}</td>
                            <td>
                                <a href="/admin/aprendices?ficha_id={{ $ap->ficha_id }}" class="text-success text-decoration-none fw-semiébold">
                                    <i class="fas fa-folder me-1"></i> Ficha {{ $ap->ficha->numero_ficha ?? $ap->ficha->nombre ?? '' }}
                                </a>
                                <small class="text-muted d-block">{{ $ap->ficha->programa->nombre ?? '' }}</small>
                            </td>
                            <td>
                                @if($esVoceroActivo)
                                    <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px;">
                                        <i class="fas fa-crown text-warning me-1"></i> Vocero
                                    </span>
                                @else
                                    <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 600; font-size: 11px;">
                                        <i class="fas fa-user-graduate text-primary me-1"></i> Aprendiz
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <a href="/admin/aprendices?ficha_id={{ $ap->ficha_id }}" class="btn-brand-outline" style="padding: 4px 10px; font-size: 12px;" title="Abrir carpeta de la Ficha para gestionar rol">
                                    <i class="fas fa-folder-open me-1"></i> Abrir Ficha
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-5 text-center text-muted">
                                <i class="fas fa-users-slash fa-3x mb-2" style="color: #cbd5e1;"></i>
                                <div>No se encontraron registróóos</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        {{ $aprendices->links() }}
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
