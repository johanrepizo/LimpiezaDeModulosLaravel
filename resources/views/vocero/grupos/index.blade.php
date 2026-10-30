@extends('layouts.app')

@section('title', 'Mis Grupos de Limpieza')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="fas fa-users-gear" style="color: #109116; font-size: 22px;"></i>
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Mis Grupos de Limpieza</h1>
        </div>
        <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
            Organiza los aprendices de tu ficha en grupos para turnos de aseo
        </p>
    </div>
    <button class="btn-brand-solid" data-bs-toggle="modal" data-bs-target="#modalCrearGrupo">
        <i class="fas fa-plus"></i> Crear Nuevo Grupo
    </button>
</div>

<div class="row g-4">
    @forelse($grupos as $grupo)
    <div class="col-lg-4 col-md-6 col-12">
        <div class="card-custom h-100 d-flex flex-column">
            <div class="card-custom-header" style="background: linear-gradient(135deg, #109116 0%, #0a5c0e 100%); color: #fff; padding: 14px 18px; border-top-left-radius: 14px; border-top-right-radius: 14px;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0" style="font-size: 16px;">
                        <i class="fas fa-user-group text-success me-2"></i>{{ $grupo->nombre }}
                    </h5>
                    <span class="badge" style="background: rgba(16, 145, 22,0.2); color: #86efac; font-size: 11px;">
                        Grupo #{{ $grupo->numero_grupo }}
                    </span>
                </div>
            </div>
            @php
                $asignacionesActivas = $grupo->vocero?->ficha?->asignaciones()->where('activo', true)->with('modulo')->get() 
                    ?? collect();
                $capacidadLimiteGrupo = $asignacionesActivas->isNotEmpty() 
                    ? $asignacionesActivas->min('modulo.capacidad') 
                    : ($grupo->asignacion->modulo->capacidad ?? 25);
            @endphp
            <div class="card-custom-body p-3 flex-grow-1">
                <div class="mb-2">
                    @if($asignacionesActivas->count() > 1)
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #084d0b !important;">
                                <i class="fas fa-layer-group text-success me-1"></i> Módulos Asignados (2)
                            </small>
                            <span class="badge" style="background: #e2e8f0; color: #334155; font-size: 10px;">
                                Capacidad: {{ $capacidadLimiteGrupo }} pers.
                            </span>
                        </div>
                        <div class="p-2 rounded bg-light border">
                            @foreach($asignacionesActivas as $asigAct)
                                <div class="small fw-semibold text-dark d-flex justify-content-between">
                                    <span><i class="fas fa-door-open text-success me-1"></i> {{ $asigAct->modulo->nombre }}</span>
                                    <span class="text-muted">({{ $asigAct->modulo->capacidad }} p.)</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <small class="text-muted d-block" style="font-size: 11.5px; text-transform: uppercase; font-weight: 600;">Módulo Asignado</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold" style="color: #1e293b; font-size: 13.5px;">
                                {{ $grupo->asignacion->modulo->nombre ?? 'N/A' }}
                            </span>
                            <span class="badge" style="background: #e2e8f0; color: #334155; font-size: 10.5px;">
                                Capacidad: {{ $grupo->asignacion->modulo->capacidad ?? 0 }} pers.
                            </span>
                        </div>
                    @endif
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block" style="font-size: 11.5px; text-transform: uppercase; font-weight: 600;">Próxima Fecha de Limpieza</small>
                    <span class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 12px; font-weight: 600;">
                        <i class="fas fa-calendar-day me-1"></i>
                        {{ $grupo->fecha_limpieza ? \Carbon\Carbon::parse($grupo->fecha_limpieza)->format('d/m/Y') : 'Por programar' }}
                    </span>
                </div>
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted" style="font-size: 11.5px; text-transform: uppercase; font-weight: 600;">
                            Integrantes ({{ $grupo->integrantes->count() }} / Máx {{ $capacidadLimiteGrupo }})
                        </small>
                    </div>
                    <ul class="list-unstyled mb-0" style="max-height: 140px; overflow-y: auto;">
                        @foreach($grupo->integrantes as $integrante)
                        <li class="py-1 border-bottom" style="font-size: 12.5px; color: #334155;">
                            <i class="fas fa-user-check text-success me-1" style="font-size: 10px;"></i>
                            {{ $integrante->nombres }} {{ $integrante->apellidos }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="p-3 border-top bg-light d-flex justify-content-end gap-2" style="border-bottom-left-radius: 14px; border-bottom-right-radius: 14px;">
                <button type="button" class="btn btn-sm btn-outline-warning" 
                    title="Editar integrantes del grupo"
                    onclick="abrirModalEditar({{ $grupo->id }}, '{{ addslashes($grupo->nombre) }}', {{ json_encode($grupo->integrantes->pluck('id')) }}, {{ $capacidadLimiteGrupo }})">
                    <i class="fas fa-edit me-1"></i> Editar
                </button>
                <form method="POST" action="/vocero/grupo/eliminar" class="d-inline"
                      data-confirm="¿Seguro que deseas eliminar el <strong>{{ $grupo->nombre }}</strong>?<br><br><small class='text-muted'>Los aprendices quedarán disponibles y los grupos restantes se renumerarán automáticamente.</small>"
                      data-confirm-title="Eliminar Grupo de Limpieza"
                      data-confirm-icon="warning"
                      data-confirm-btn="Sí, Eliminar Grupo"
                      data-confirm-color="#dc2626">
                    @csrf
                    <input type="hidden" name="grupo_id" value="{{ $grupo->id }}">
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar Grupo">
                        <i class="fas fa-trash me-1"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 py-5 text-center text-muted">
        <i class="fas fa-users-slash fa-3x mb-3" style="color: #cbd5e1;"></i>
        <h5 class="fw-bold" style="color: #64748b;">No tienes grupos creados aún</h5>
        <p class="mb-3" style="font-size: 13.5px;">Crea grupos para asignar aprendices a los turnos de limpieza de tu ficha.</p>
        <button class="btn-brand-solid" data-bs-toggle="modal" data-bs-target="#modalCrearGrupo">
            <i class="fas fa-plus"></i> Crear Primer Grupo
        </button>
    </div>
    @endforelse
</div>

<!-- MODAL CREAR GRUPO -->
<div class="modal fade" id="modalCrearGrupo" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: linear-gradient(135deg, #109116 0%, #0a5c0e 100%); color: #fff; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-plus-circle text-success me-2"></i>Crear Nuevo Grupo de Limpieza
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            @php
                $asignacionesFicha = $asignacionesFicha ?? (Auth::user()->vocero 
                    ? \App\Models\Asignacion::where('activo', true)->where('ficha_id', Auth::user()->vocero->ficha_id)->with('modulo')->get() 
                    : collect());
                $capacidadMinima = $asignacionesFicha->min('modulo.capacidad') ?? 25;
                $primeraAsigId = $asignacionesFicha->first()?->id ?? '';
            @endphp
            <form method="POST" action="/vocero/grupo/guardar">
                @csrf
                <div class="modal-body p-4">
                    @if($asignacionesFicha->isEmpty())
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Atención:</strong> Tu ficha aún no tiene módulos asignados por el administrador. Para poder crear un grupo de limpieza, la administración debe asignar al menos un módulo a tu ficha.
                        </div>
                    @elseif($asignacionesFicha->count() === 1)
                        @php $asig = $asignacionesFicha->first(); @endphp
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-2">
                                <i class="fas fa-door-open text-success me-1"></i> Módulo Asignado
                            </label>
                            <div class="p-3 rounded border d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #f8fafc; border-color: #cbd5e1 !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #dcfce7; color: #109116; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                                        <i class="fas fa-door-open"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 15px;">{{ $asig->modulo->nombre }}</div>
                                        <small class="text-muted">
                                            <i class="fas fa-location-dot text-danger me-1"></i>{{ $asig->modulo->ubicacion ?? 'Centro de Formación' }}
                                        </small>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 12px; font-weight: 700; padding: 6px 12px; border-radius: 8px;">
                                        <i class="fas fa-users me-1"></i> Capacidad: {{ $asig->modulo->capacidad }} personas
                                    </span>
                                </div>
                            </div>
                            <input type="hidden" name="asignacion_id" id="asignacion_id" value="{{ $asig->id }}">
                        </div>
                    @else
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark mb-2">
                                <i class="fas fa-door-open text-success me-1"></i> Módulos Asignados ({{ $asignacionesFicha->count() }})
                            </label>
                            <div class="p-3 rounded border" style="background: #f8fafc; border-color: #cbd5e1 !important;">
                                <div class="row g-2 mb-2">
                                    @foreach($asignacionesFicha as $asig)
                                        <div class="col-md-6 col-12">
                                            <div class="p-2 rounded bg-white border d-flex align-items-center justify-content-between h-100">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #dcfce7; color: #109116; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">
                                                        <i class="fas fa-door-open"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-dark" style="font-size: 13px;">{{ $asig->modulo->nombre }}</div>
                                                        <small class="text-muted" style="font-size: 11px;">{{ $asig->modulo->ubicacion ?? 'Centro de Formación' }}</small>
                                                    </div>
                                                </div>
                                                <span class="badge bg-light text-dark border" style="font-size: 11px;">
                                                    {{ $asig->modulo->capacidad }} pers.
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 border-top">
                                    <small class="text-muted" style="font-size: 11.5px;">
                                        <i class="fas fa-info-circle text-primary me-1"></i> El grupo limpiará ambos módulos asignados en cada jornada.
                                    </small>
                                    <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 11.5px; font-weight: 700;">
                                        Capacidad máxima: {{ $capacidadMinima }} personas
                                    </span>
                                </div>
                            </div>
                            <input type="hidden" name="asignacion_id" id="asignacion_id" value="{{ $primeraAsigId }}">
                        </div>
                    @endif

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-semibold mb-0">Seleccionar Aprendices para este Grupo</label>
                            <span class="badge" id="badge-limite-crear" style="background: #e2e8f0; color: #334155; font-size: 11.5px;">
                                Seleccionados: <strong id="contador-crear">0</strong> / <span id="span-capacidad-crear">{{ $capacidadMinima }}</span>
                            </span>
                        </div>
                        <div id="alerta-limite-crear" class="alert alert-warning py-1 px-2 mb-2 d-none" style="font-size: 12px; border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle me-1"></i> Capacidad máxima alcanzada para este módulo. No puedes seleccionar más aprendices.
                        </div>
                        <div id="aprendices-list-crear" class="border p-2 rounded" style="max-height: 280px; overflow-y: auto; background: #fafafa;">
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-spinner fa-spin me-1"></i> Cargando aprendices...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-brand-solid" {{ $asignacionesFicha->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-check me-1"></i> Crear Grupo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDITAR GRUPO -->
<div class="modal fade" id="modalEditarGrupo" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: linear-gradient(135deg, #109116 0%, #0a5c0e 100%); color: #fff; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-edit text-warning me-2"></i>Editar Integrantes del Grupo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/vocero/grupo/editar">
                @csrf
                <input type="hidden" name="grupo_id" id="edit_grupo_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nombre del Grupo</label>
                        <input type="text" id="edit_grupo_nombre" class="form-control" readonly style="background: #f1f5f9;">
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-semibold mb-0">Seleccionar Aprendices</label>
                            <span class="badge" id="badge-limite-editar" style="background: #e2e8f0; color: #334155; font-size: 11.5px;">
                                Seleccionados: <strong id="contador-editar">0</strong> / <span id="span-capacidad-editar">--</span>
                            </span>
                        </div>
                        <div id="alerta-limite-editar" class="alert alert-warning py-1 px-2 mb-2 d-none" style="font-size: 12px; border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle me-1"></i> Capacidad máxima del módulo alcanzada. Desmarca un aprendiz para seleccionar otro.
                        </div>
                        <div id="aprendices-list-editar" class="border p-3 rounded" style="max-height: 260px; overflow-y: auto; background: #fafafa;">
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-spinner fa-spin me-1"></i> Cargando aprendices...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn-brand-solid">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let aprendicesCache = [];
let capacidadCrearActual = {{ $capacidadMinima ?? 25 }};
let capacidadEditarActual = 25;

document.addEventListener('DOMContentLoaded', function() {
    const spanCrear = document.getElementById('span-capacidad-crear');
    if (spanCrear) spanCrear.innerText = capacidadCrearActual;

    fetch('/vocero/integrantes/ids')
        .then(r => r.json())
        .then(data => {
            aprendicesCache = data;
            renderAprendicesCrear();
        })
        .catch(err => {
            console.error('Error cargando aprendices:', err);
        });
});

function actualizarRestriccionesCrear() {
    const container = document.getElementById('aprendices-list-crear');
    if (!container) return;
    const checkboxesDisponibles = container.querySelectorAll('input.check-crear');
    const checked = container.querySelectorAll('input.check-crear:checked');
    const contador = document.getElementById('contador-crear');
    const alerta = document.getElementById('alerta-limite-crear');

    if (contador) contador.innerText = checked.length;

    if (checked.length >= capacidadCrearActual) {
        checkboxesDisponibles.forEach(cb => {
            if (!cb.checked) {
                cb.disabled = true;
                const row = document.getElementById(`row_ap_crear_${cb.value}`);
                if (row) row.style.opacity = '0.5';
            }
        });
        if (alerta) {
            alerta.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i> Capacidad máxima alcanzada (${capacidadCrearActual} personas). Desmarca un aprendiz para seleccionar otro.`;
            alerta.classList.remove('d-none');
        }
    } else {
        checkboxesDisponibles.forEach(cb => {
            cb.disabled = false;
            const row = document.getElementById(`row_ap_crear_${cb.value}`);
            if (row) row.style.opacity = '1';
        });
        if (alerta) alerta.classList.add('d-none');
    }
}

function renderAprendicesCrear() {
    const container = document.getElementById('aprendices-list-crear');
    if (!container) return;
    if (aprendicesCache.length === 0) {
        container.innerHTML = '<div class="text-muted text-center py-2">No hay aprendices registrados en esta ficha.</div>';
        return;
    }
    let html = '';
    aprendicesCache.forEach(ap => {
        if (ap.tiene_grupo) {
            // APRENDIZ YA EN OTRO GRUPO: en gris, deshabilitado y muestra a la derecha en qué grupo está
            html += `
            <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded border" style="background: #f1f5f9; border-color: #e2e8f0; opacity: 0.7; cursor: not-allowed;">
                <div class="form-check m-0 d-flex align-items-center gap-2">
                    <input class="form-check-input m-0" type="checkbox" disabled style="cursor: not-allowed;">
                    <label class="form-check-label text-muted mb-0" style="cursor: not-allowed; font-size: 13.5px;">
                        ${ap.nombres} ${ap.apellidos}
                    </label>
                </div>
                <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 11.5px; font-weight: 600; padding: 5px 10px; border-radius: 6px;">
                    <i class="fas fa-users text-secondary me-1"></i> ${ap.grupo_nombre}
                </span>
            </div>`;
        } else {
            // APRENDIZ DISPONIBLE: seleccionable normalmente
            html += `
            <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded border" id="row_ap_crear_${ap.id}" style="background: #ffffff; border-color: #e2e8f0; transition: all 0.15s ease;">
                <div class="form-check m-0 d-flex align-items-center gap-2 flex-grow-1">
                    <input class="form-check-input check-crear m-0" type="checkbox" name="aprendices[]" value="${ap.id}" id="ap_crear_${ap.id}" onchange="actualizarRestriccionesCrear()">
                    <label class="form-check-label text-dark mb-0 flex-grow-1" for="ap_crear_${ap.id}" style="cursor: pointer; font-size: 13.5px; font-weight: 500;">
                        ${ap.nombres} ${ap.apellidos}
                    </label>
                </div>
                <span class="badge" style="background: #dcfce7; color: #084d0b; font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 6px;">
                    <i class="fas fa-check-circle me-1"></i> Disponible
                </span>
            </div>`;
        }
    });
    container.innerHTML = html;
    actualizarRestriccionesCrear();
}

function actualizarRestriccionesEditar() {
    const container = document.getElementById('aprendices-list-editar');
    if (!container) return;
    const checkboxesDisponibles = container.querySelectorAll('input.check-editar');
    const checked = container.querySelectorAll('input.check-editar:checked');
    const contador = document.getElementById('contador-editar');
    const alerta = document.getElementById('alerta-limite-editar');

    if (contador) contador.innerText = checked.length;

    if (checked.length >= capacidadEditarActual) {
        checkboxesDisponibles.forEach(cb => {
            if (!cb.checked) {
                cb.disabled = true;
                const row = document.getElementById(`row_ap_edit_${cb.value}`);
                if (row) row.style.opacity = '0.5';
            }
        });
        if (alerta) {
            alerta.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i> Capacidad máxima alcanzada (${capacidadEditarActual} personas). Desmarca un aprendiz para seleccionar otro.`;
            alerta.classList.remove('d-none');
        }
    } else {
        checkboxesDisponibles.forEach(cb => {
            cb.disabled = false;
            const row = document.getElementById(`row_ap_edit_${cb.value}`);
            if (row) row.style.opacity = '1';
        });
        if (alerta) alerta.classList.add('d-none');
    }
}

function abrirModalEditar(grupoId, grupoNombre, integrantesActualesIds, capacidad = 25) {
    document.getElementById('edit_grupo_id').value = grupoId;
    document.getElementById('edit_grupo_nombre').value = grupoNombre;
    capacidadEditarActual = parseInt(capacidad) || 25;

    const spanCap = document.getElementById('span-capacidad-editar');
    if (spanCap) spanCap.innerText = capacidadEditarActual;

    const container = document.getElementById('aprendices-list-editar');
    let html = '';
    const currentSet = new Set(integrantesActualesIds);

    aprendicesCache.forEach(ap => {
        const perteneceAEsteGrupo = currentSet.has(ap.id) || (ap.tiene_grupo && ap.grupo_id === grupoId);
        const estaEnOtroGrupo = ap.tiene_grupo && ap.grupo_id !== grupoId && !currentSet.has(ap.id);

        if (estaEnOtroGrupo) {
            // EN OTRO GRUPO: Deshabilitado en gris con badge a la derecha
            html += `
            <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded border" style="background: #f1f5f9; border-color: #e2e8f0; opacity: 0.7; cursor: not-allowed;">
                <div class="form-check m-0 d-flex align-items-center gap-2">
                    <input class="form-check-input m-0" type="checkbox" disabled style="cursor: not-allowed;">
                    <label class="form-check-label text-muted mb-0" style="cursor: not-allowed; font-size: 13.5px;">
                        ${ap.nombres} ${ap.apellidos}
                    </label>
                </div>
                <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 11.5px; font-weight: 600; padding: 5px 10px; border-radius: 6px;">
                    <i class="fas fa-users text-secondary me-1"></i> ${ap.grupo_nombre}
                </span>
            </div>`;
        } else {
            // DISPONIBLE O INTEGRANTE DE ESTE GRUPO
            const isChecked = currentSet.has(ap.id) ? 'checked' : '';
            html += `
            <div class="d-flex align-items-center justify-content-between p-2 mb-2 rounded border" id="row_ap_edit_${ap.id}" style="background: #ffffff; border-color: #e2e8f0; transition: all 0.15s ease;">
                <div class="form-check m-0 d-flex align-items-center gap-2 flex-grow-1">
                    <input class="form-check-input check-editar m-0" type="checkbox" name="aprendices[]" value="${ap.id}" id="ap_edit_${ap.id}" ${isChecked} onchange="actualizarRestriccionesEditar()">
                    <label class="form-check-label text-dark mb-0 flex-grow-1" for="ap_edit_${ap.id}" style="cursor: pointer; font-size: 13.5px; font-weight: 500;">
                        ${ap.nombres} ${ap.apellidos}
                    </label>
                </div>
                <span class="badge" style="background: ${isChecked ? '#dcfce7; color: #084d0b;' : '#f1f5f9; color: #475569;'} font-size: 11px; font-weight: 600; padding: 4px 8px; border-radius: 6px;">
                    ${isChecked ? '<i class="fas fa-check me-1"></i> Integrante actual' : '<i class="fas fa-check-circle me-1"></i> Disponible'}
                </span>
            </div>`;
        }
    });

    container.innerHTML = html;
    actualizarRestriccionesEditar();

    new bootstrap.Modal(document.getElementById('modalEditarGrupo')).show();
}
</script>
@endsection
