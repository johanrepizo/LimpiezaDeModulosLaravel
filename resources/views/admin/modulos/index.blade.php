@extends('layouts.app')

@section('title', 'Módulos y Asignaciones')

@section('content')
<!-- HEADER -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="fas fa-door-open" style="color: #109116; font-size: 22px;"></i>
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Módulos y Asignaciones</h1>
        </div>
        <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
            Gestión completa de ambientes de aprendizaje y programación de turnos de limpieza por ficha
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button class="btn-brand-solid" data-bs-toggle="modal" data-bs-target="#modalAsignar">
            <i class="fas fa-calendar-plus"></i> Asignar Módulo a Ficha
        </button>
        <button class="btn-brand-outline" data-bs-toggle="modal" data-bs-target="#modalModulo">
            <i class="fas fa-plus"></i> Nuevo Módulo
        </button>
    </div>
</div>

@if(isset($fichaSeleccionada) && $fichaSeleccionada)
<div class="alert alert-info d-flex align-items-center justify-content-between py-2 px-3 mb-4" style="border-radius: 10px;">
    <div>
        <i class="fas fa-filter me-1"></i> Filtrando asignaciones para la ficha: <strong>{{ $fichaSeleccionada->nombre }}</strong> ({{ $fichaSeleccionada->numero_ficha }})
    </div>
    <a href="/admin/modulos" class="btn btn-sm btn-outline-secondary" style="font-size: 12px;">Quitar Filtro</a>
</div>
@endif

<!-- TABS DE NAVEGACIÓN -->
<ul class="nav nav-pills mb-4 gap-2" id="modulosTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active custom-tab-btn" id="asignaciones-tab" data-bs-toggle="pill" data-bs-target="#tab-asignaciones" type="button" role="tab">
            <i class="fas fa-calendar-check me-1"></i> Asignaciones Activas ({{ $asignaciones->count() }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link custom-tab-btn" id="catalogo-tab" data-bs-toggle="pill" data-bs-target="#tab-catalogo" type="button" role="tab">
            <i class="fas fa-building me-1"></i> Catálogo de Módulos ({{ $modulos->count() }})
        </button>
    </li>
</ul>

<div class="tab-content" id="modulosTabContent">
    <!-- TAB 1: ASIGNACIONES -->
    <div class="tab-pane fade show active" id="tab-asignaciones" role="tabpanel">
        <div class="card-custom">
            <div class="table-responsive">
                <table class="table-clean">
                    <thead>
                        <tr>
                            <th style="width: 25%;">MÓDULO ASIGNADO</th>
                            <th style="width: 25%;">FICHA Y PROGRAMA</th>
                            <th style="width: 15%;">DÍA DE LIMPIEZA</th>
                            <th style="width: 15%;">FECHA INICIO</th>
                            <th style="width: 10%;">ESTADO</th>
                            <th style="width: 10%; text-align: right;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asignaciones as $asignacion)
                            @php
                                $dias = [
                                    0 => 'Domingo',
                                    1 => 'Lunes',
                                    2 => 'Martes',
                                    3 => 'Miércoles',
                                    4 => 'Jueves',
                                    5 => 'Viernes',
                                    6 => 'Sábado'
                                ];
                                $diaNombre = $dias[$asignacion->dia_semana] ?? 'Lunes';
                            @endphp
                            <tr>
                                <td>
                                    <strong style="color: #0f172a;">{{ $asignacion->modulo->nombre }}</strong>
                                    <small class="text-muted d-block">{{ $asignacion->modulo->ubicacion }}</small>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: #1e293b;">{{ $asignacion->ficha->nombre }}</span>
                                    <small class="text-muted d-block">{{ $asignacion->ficha->programa->nombre ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge" style="background: #e0f2fe; color: #0284c7; font-weight: 600; font-size: 12px;">
                                        <i class="fas fa-calendar-day me-1"></i> {{ $diaNombre }}
                                    </span>
                                </td>
                                <td>
                                    <span style="color: #64748b; font-size: 13px;">
                                        {{ \Carbon\Carbon::parse($asignacion->fecha_inicio)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($asignacion->activo)
                                        <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px;">
                                            Activa
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Cancelada</span>
                                    @endif
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <a href="/admin/evidencias?ficha_id={{ $asignacion->ficha_id }}" class="btn btn-sm btn-outline-success" title="Ver evidencias fotográficas" style="padding: 4px 8px; border-radius: 6px;">
                                        <i class="fas fa-camera"></i>
                                    </a>
                                    @if($asignacion->activo)
                                        <form method="POST" action="/admin/asignacion/cancelar" class="d-inline"
                                              data-confirm="¿Seguro que deseas cancelar esta asignación?<br><br><small class='text-muted'>Los turnos futuros sin evidencias se cancelarán, pero <strong>todas las fotos y evidencias ya subidas se conservarán intactas</strong>.</small>"
                                              data-confirm-title="Cancelar Asignación de Módulo"
                                              data-confirm-icon="warning"
                                              data-confirm-btn="Sí, Cancelar Asignación"
                                              data-confirm-color="#dc2626">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $asignacion->id }}">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancelar Asignación" style="padding: 4px 8px; border-radius: 6px;">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-5 text-center text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-2" style="color: #cbd5e1;"></i>
                                    <div>No hay asignaciones de módulos registradas aún</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- TAB 2: CATÁLOGO DE MÓDULOS -->
    <div class="tab-pane fade" id="tab-catalogo" role="tabpanel">
        <div class="card-custom">
            <div class="table-responsive">
                <table class="table-clean">
                    <thead>
                        <tr>
                            <th style="width: 30%;">MÓDULO / AMBIENTE</th>
                            <th style="width: 30%;">UBICACIÓN</th>
                            <th style="width: 20%;">CAPACIDAD</th>
                            <th style="width: 20%; text-align: right;">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modulos as $modulo)
                        <tr>
                            <td>
                                <strong style="color: #0f172a;">{{ $modulo->nombre }}</strong>
                                @if($modulo->descripcion)
                                    <small class="text-muted d-block">{{ Str::limit($modulo->descripcion, 60) }}</small>
                                @endif
                            </td>
                            <td>
                                <span style="color: #475569;"><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $modulo->ubicacion }}</span>
                            </td>
                            <td>
                                <span class="badge" style="background: #f1f5f9; color: #334155; font-size: 12px; font-weight: 600;">
                                    {{ $modulo->capacidad }} personas
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <button class="btn-brand-outline" style="padding: 4px 10px; font-size: 12px;" onclick="editarModulo({{ json_encode($modulo) }})">
                                    <i class="fas fa-edit"></i> Editar
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-5 text-center text-muted">
                                <i class="fas fa-door-closed fa-3x mb-2" style="color: #cbd5e1;"></i>
                                <div>No hay módulos registrados en el catálogo</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ASIGNAR MÓDULO A FICHA -->
<div class="modal fade" id="modalAsignar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: linear-gradient(135deg, #109116 0%, #0a5c0e 100%); color: #ffffff; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #14b51c; color: #fff; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <h5 class="modal-title fw-bold m-0">Asignar Módulo a Ficha</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/admin/asignacion/crear">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="modulo_id" class="form-label fw-semibold">Seleccionar Módulo</label>
                        <select name="modulo_id" id="modulo_id" class="form-select" required>
                            <option value="">Selecciona un módulo...</option>
                            @foreach($modulos as $mod)
                                <option value="{{ $mod->id }}">{{ $mod->nombre }} ({{ $mod->ubicacion }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="ficha_id" class="form-label fw-semibold mb-0">Seleccionar Ficha de Formación</label>
                            <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 11px;">Máx. 2 módulos por ficha</span>
                        </div>
                        <select name="ficha_id" id="ficha_id" class="form-select" required>
                            <option value="">Selecciona una ficha...</option>
                            @foreach($fichas as $f)
                                @php
                                    $modulosAsignados = $f->asignaciones_count ?? 0;
                                    $estaLlena = $modulosAsignados >= 2;
                                @endphp
                                <option value="{{ $f->id }}" 
                                    {{ (isset($fichaSeleccionada) && $fichaSeleccionada && $fichaSeleccionada->id == $f->id) ? 'selected' : '' }}
                                    {{ $estaLlena ? 'disabled' : '' }}>
                                    {{ $f->nombre }} ({{ $f->numero_ficha }}) — [{{ $modulosAsignados }}/2 módulos {{ $estaLlena ? '• Límite alcanzado' : 'activos' }}]
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1" style="font-size: 11.5px;">Cada ficha puede tener asignados 1 o como máximo 2 módulos activos simultáneamente.</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label fw-semibold">Fecha de Inicio</label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="dia_semana" class="form-label fw-semibold">Día de Limpieza</label>
                            <select name="dia_semana" id="dia_semana" class="form-select" required>
                                <option value="1">Lunes</option>
                                <option value="2">Martes</option>
                                <option value="3">Miércoles</option>
                                <option value="4">Jueves</option>
                                <option value="5">Viernes</option>
                                <option value="6">Sábado</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3 p-3 rounded" style="background: #f0fdf4; border: 1px solid #bbf7d0; font-size: 12.5px; color: #084d0b;">
                        <i class="fas fa-info-circle me-1"></i> Al crear la asignación se generarán automáticamente los turnos periódicos correspondientes y se notificará a los voceros.
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-brand-solid">Confirmar Asignación</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL CREAR / EDITAR MÓDULO -->
<div class="modal fade" id="modalModulo" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: linear-gradient(135deg, #109116 0%, #0a5c0e 100%); color: #ffffff; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #14b51c; color: #fff; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-building"></i>
                    </div>
                    <h5 class="modal-title fw-bold m-0" id="tituloModalModulo">Gestionar Módulo</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/admin/modulo/guardar">
                @csrf
                <input type="hidden" name="id" id="modulo_edit_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="mod_nombre" class="form-label fw-semibold">Nombre del Módulo</label>
                        <input type="text" name="nombre" id="mod_nombre" class="form-control" required placeholder="Ej: Laboratorio de Limpieza 2">
                    </div>

                    <div class="mb-3">
                        <label for="mod_ubicacion" class="form-label fw-semibold">Ubicación</label>
                        <input type="text" name="ubicacion" id="mod_ubicacion" class="form-control" required placeholder="Ej: Bloque A - Piso 2">
                    </div>

                    <div class="mb-3">
                        <label for="mod_capacidad" class="form-label fw-semibold">Capacidad (Personas)</label>
                        <input type="number" name="capacidad" id="mod_capacidad" class="form-control" required min="1" placeholder="Ej: 25">
                    </div>

                    <div class="mb-3">
                        <label for="mod_descripcion" class="form-label fw-semibold">Descripción (Opcional)</label>
                        <textarea name="descripcion" id="mod_descripcion" class="form-control" rows="2" placeholder="Detalles de insumos o características..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-brand-solid">Guardar Módulo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .custom-tab-btn {
        color: #475569;
        font-weight: 600;
        border-radius: 10px;
        padding: 9px 18px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        transition: all 0.2s ease;
    }
    .custom-tab-btn:hover {
        background: #f8fafc;
        color: #0f172a;
    }
    .custom-tab-btn.active {
        background: #109116 !important;
        color: #ffffff !important;
        border-color: #109116 !important;
        box-shadow: 0 4px 12px rgba(16, 145, 22, 0.25);
    }
</style>
@endsection

@section('scripts')
<script>
function editarModulo(modulo) {
    document.getElementById('modulo_edit_id').value = modulo.id;
    document.getElementById('mod_nombre').value = modulo.nombre;
    document.getElementById('mod_ubicacion').value = modulo.ubicacion;
    document.getElementById('mod_capacidad').value = modulo.capacidad;
    document.getElementById('mod_descripcion').value = modulo.descripcion || '';
    document.getElementById('tituloModalModulo').innerText = 'Editar Módulo';
    new bootstrap.Modal(document.getElementById('modalModulo')).show();
}

document.getElementById('modalModulo').addEventListener('hidden.bs.modal', function () {
    document.getElementById('modulo_edit_id').value = '';
    document.getElementById('mod_nombre').value = '';
    document.getElementById('mod_ubicacion').value = '';
    document.getElementById('mod_capacidad').value = '';
    document.getElementById('mod_descripcion').value = '';
    document.getElementById('tituloModalModulo').innerText = 'Gestionar Módulo';
});
</script>
@endsection
