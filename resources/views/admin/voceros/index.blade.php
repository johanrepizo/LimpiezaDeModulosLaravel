@extends('layouts.app')

@section('title', 'Voceros - Estructura de Formación')

@section('content')

@if($nivel === 'programas')
    <!-- ================= NIVEL 1: PROGRAMAS DE FORMACIÓN ================= -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item active">Voceros por Programa</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="fas fa-id-badge" style="color: #109116; font-size: 22px;"></i>
                <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Voceros por Programa</h1>
            </div>
            <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
                Selecciona un programa de formación para navegar hacia sus fichas y voceros designados
            </p>
        </div>
        <div>
            <a href="/admin/voceros?view=all" class="btn-brand-outline">
                <i class="fas fa-list me-1"></i> Ver Listado Global
            </a>
        </div>
    </div>

    <!-- GRID DE PROGRAMAS (CARPETAS) -->
    <div class="row g-4">
        @forelse($programas as $prog)
            @php
                $totalVocerosProg = $prog->fichas->sum(fn($f) => $f->voceros->count());
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card-custom h-100 folder-card" onclick="window.location.href='/admin/voceros?programa_id={{ $prog->id }}'">
                    <div class="card-custom-body d-flex flex-column h-100 p-4">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <div class="folder-icon-box">
                                <i class="fas fa-folder"></i>
                            </div>
                            <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 6px;">
                                Activo
                            </span>
                        </div>

                        <h5 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">{{ $prog->nombre }}</h5>
                        <small class="text-muted mb-2 d-block">{{ $prog->nivel ?? 'Técnico' }}</small>
                        <p class="text-secondary small mb-3 flex-grow-1" style="line-height: 1.45;">
                            {{ Str::limit($prog->descripcion ?? 'Programa de formación técnica del Centro de Formación', 90) }}
                        </p>

                        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-user-tie text-success"></i>
                                <span class="fw-bold text-dark">{{ $totalVocerosProg }}</span>
                                <span class="text-muted small">Voceros en {{ $prog->fichas->count() }} fichas</span>
                            </div>
                            <a href="/admin/voceros?programa_id={{ $prog->id }}" class="btn-brand-outline" style="padding: 4px 12px; font-size: 12px;">
                                Ver Fichas <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center text-muted">
                <i class="fas fa-folder-open fa-3x mb-2" style="color: #cbd5e1;"></i>
                <div>No hay programas registrados</div>
            </div>
        @endforelse
    </div>

@elseif($nivel === 'fichas')
    <!-- ================= NIVEL 2: FICHAS DEL PROGRAMA ================= -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/admin/voceros" class="text-decoration-none text-success">Programas</a></li>
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
                <p class="text-muted small mb-0">Selecciona una ficha para consultar o activar sus voceros líderes</p>
            </div>
            <div>
                <a href="/admin/voceros" class="btn-brand-outline">
                    <i class="fas fa-arrow-left"></i> Volver a Programas
                </a>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold m-0" style="color: #0f172a;">
            <i class="fas fa-folder-open text-success me-2"></i> Fichas de este Programa ({{ $fichas->count() }})
        </h5>
        <small class="text-muted">Haz clic en una ficha para gestionar sus voceros</small>
    </div>

    <!-- GRID DE FICHAS -->
    <div class="row g-4">
        @forelse($fichas as $f)
            <div class="col-md-6 col-lg-4">
                <div class="card-custom h-100 folder-card" onclick="window.location.href='/admin/voceros?ficha_id={{ $f->id }}'">
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

                        <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-id-badge text-success"></i>
                                <span class="fw-bold text-dark">{{ $f->voceros->count() }}</span>
                                <span class="text-muted small">Voceros Activos</span>
                            </div>
                            <a href="/admin/voceros?ficha_id={{ $f->id }}" class="btn-brand-outline" style="padding: 4px 12px; font-size: 12px;">
                                Ver Voceros <i class="fas fa-arrow-right ms-1"></i>
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
    <!-- ================= NIVEL 3: VOCEROS DE LA FICHA ================= -->
    <div class="mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
                <li class="breadcrumb-item"><a href="/admin/voceros" class="text-decoration-none text-success">Programas</a></li>
                <li class="breadcrumb-item"><a href="/admin/voceros?programa_id={{ $programa->id }}" class="text-decoration-none text-success">{{ Str::limit($programa->nombre, 25) }}</a></li>
                <li class="breadcrumb-item active">{{ $ficha->numero_ficha ?? $ficha->nombre }}</li>
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
            <div class="d-flex align-items-center gap-2">
                @if($voceros->count() < 2)
                    <button class="btn-brand-solid" data-bs-toggle="modal" data-bs-target="#modalActivarVocero">
                        <i class="fas fa-user-plus me-1"></i> Activar Nuevo Vocero
                    </button>
                @endif
                <a href="/admin/voceros?programa_id={{ $programa->id }}" class="btn-brand-outline">
                    <i class="fas fa-arrow-left"></i> Volver a Fichas
                </a>
            </div>
        </div>
    </div>

    <!-- TABLA DE VOCEROS -->
    <div class="card-custom">
        <div class="card-custom-header">
            <div class="card-custom-title">
                <i class="fas fa-id-badge" style="color: #109116; font-size: 18px;"></i>
                <span>Voceros Activos ({{ $voceros->count() }} de 2 permitidos)</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th style="width: 32%;">VOCERO (APRENDIZ)</th>
                        <th style="width: 30%;">CORREO DE ACCESO</th>
                        <th style="width: 18%;">ESTADO</th>
                        <th style="width: 20%; text-align: right;">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($voceros as $v)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 38px; height: 38px; border-radius: 50%; background: #dcfce7; color: #109116; display: flex; align-items: center; justify-content: center; font-size: 15px;">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <strong style="color: #0f172a;">{{ $v->aprendiz->nombres }} {{ $v->aprendiz->apellidos }}</strong>
                                    <small class="text-muted d-block">Doc: {{ $v->aprendiz->documento }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="color: #475569; font-size: 13.5px;"><i class="fas fa-envelope text-muted me-1"></i> {{ $v->usuario->correo }}</span>
                        </td>
                        <td>
                            <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px;">
                                Vocero Activo
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <form method="POST" action="/admin/vocero/reenviar-credenciales" class="d-inline" title="Reenviar contraseña">
                                @csrf
                                <input type="hidden" name="id" value="{{ $v->id }}">
                                <button type="submit" class="btn-brand-outline" style="padding: 5px 12px; font-size: 12px;">
                                    <i class="fas fa-key"></i> Credenciales
                                </button>
                            </form>
                            <form method="POST" action="/admin/vocero/desactivar" class="d-inline"
                                  data-confirm="¿Estás seguro de que deseas desactivar a este vocero? Perderá acceso al sistema y sus funciones."
                                  data-confirm-title="Desactivar Vocero"
                                  data-confirm-icon="warning"
                                  data-confirm-btn="Sí, Desactivar"
                                  data-confirm-color="#dc2626">
                                @csrf
                                <input type="hidden" name="id" value="{{ $v->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Desactivar" style="padding: 5px 10px; border-radius: 8px;">
                                    <i class="fas fa-ban"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-5 text-center text-muted">
                            <i class="fas fa-user-slash fa-3x mb-2" style="color: #cbd5e1;"></i>
                            <div>Esta ficha no tiene voceros asignados aún</div>
                            <button class="btn-brand-solid btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#modalActivarVocero">
                                <i class="fas fa-user-plus me-1"></i> Designar Vocero Ahora
                            </button>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL ACTIVAR VOCERO -->
    <div class="modal fade" id="modalActivarVocero" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background: linear-gradient(135deg, #109116 0%, #0a5c0e 100%); color: #ffffff; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="modal-title fw-bold m-0"><i class="fas fa-user-plus text-success me-2"></i> Designar Vocero de Ficha</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="/admin/vocero/activar">
                    @csrf
                    <input type="hidden" name="ficha_id" value="{{ $ficha->id }}">
                    <div class="modal-body p-4">
                        <p class="small text-muted mb-3">
                            Selecciona un aprendiz de la <strong>Ficha {{ $ficha->numero_ficha }}</strong> para asignarlo como Vocero. Se creará su cuenta de usuario y contraseña automáticamente.
                        </p>
                        <div class="mb-3">
                            <label for="aprendiz_id" class="form-label fw-semibold">Seleccionar Aprendiz</label>
                            <select name="aprendiz_id" id="aprendiz_id" class="form-select" required>
                                <option value="">Selecciona un aprendiz de esta ficha...</option>
                                @foreach($aprendicesFicha as $ap)
                                    <option value="{{ $ap->id }}">{{ $ap->nombres }} {{ $ap->apellidos }} (Doc: {{ $ap->documento }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-brand-solid">Activar Vocero</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@else
    <!-- ================= MODO LISTADO GLOBAL ================= -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color: #0f172a;">Listado Global de Voceros</h1>
            <p class="text-muted small mb-0">Vista general de todos los voceros registrados en el sistema</p>
        </div>
        <a href="/admin/voceros" class="btn-brand-outline">
            <i class="fas fa-folder-tree me-1"></i> Ver por Carpetas de Programas
        </a>
    </div>

    <div class="card-custom">
        <div class="table-responsive">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th>VOCERO</th>
                        <th>CORREO DE ACCESO</th>
                        <th>FICHA Y PROGRAMA</th>
                        <th>ESTADO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($voceros as $v)
                    <tr>
                        <td><strong>{{ $v->aprendiz->nombres }} {{ $v->aprendiz->apellidos }}</strong></td>
                        <td>{{ $v->usuario->correo }}</td>
                        <td>
                            <a href="/admin/voceros?ficha_id={{ $v->ficha_id }}" class="text-success text-decoration-none fw-semibold">
                                <i class="fas fa-folder me-1"></i> Ficha {{ $v->ficha->numero_ficha ?? $v->ficha->nombre ?? '' }}
                            </a>
                        </td>
                        <td><span class="badge bg-success">Activo</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        {{ $voceros->links() }}
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
