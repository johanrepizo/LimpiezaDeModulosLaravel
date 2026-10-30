@extends('layouts.app')

@section('title', 'Subir Evidencia Fotográfica')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="fas fa-camera" style="color: #109116; font-size: 22px;"></i>
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Subir Evidencia Fotográfica</h1>
        </div>
        <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
            Carga las fotos de antes y después del aseo para certificar el turno de limpieza
        </p>
    </div>
    <a href="/vocero/evidencias" class="btn-brand-outline">
        <i class="fas fa-images"></i> Ver Todas Mis Evidencias
    </a>
</div>

@if(!$turnosHoy || $turnosHoy->isEmpty())
<div class="card-custom p-4 text-center mb-4" style="background: #fefce8; border: 1px solid #fef08a;">
    <div style="width: 50px; height: 50px; border-radius: 50%; background: #fef08a; color: #854d0e; display: inline-flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 12px;">
        <i class="fas fa-calendar-check"></i>
    </div>
    <h5 class="fw-bold" style="color: #713f12;">No tienes turno de limpieza abierto para el día de hoy</h5>
    <p class="text-muted mx-auto mb-3" style="max-width: 540px; font-size: 13.5px;">
        Los turnos se habilitan automáticamente los días programados para tu ficha según la asignación de módulos activa.
    </p>
    <div>
        <a href="/vocero" class="btn-brand-solid">
            <i class="fas fa-arrow-left me-1"></i> Ir al Dashboard
        </a>
    </div>
</div>
@elseif($esDobleModulo)
<!-- ===== CASO: 2 MÓDULOS ASIGNADOS (4 FOTOS) ===== -->
<div class="card-custom mb-4" style="background: linear-gradient(135deg, rgba(16, 145, 22,0.08), rgba(16, 145, 22,0.02)); border: 1.5px solid rgba(16, 145, 22,0.3);">
    <div class="card-custom-body p-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <div>
                <span class="badge mb-2" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11.5px;">
                    <i class="fas fa-circle text-success me-1"></i> 2 MÓDULOS ACTIVOS HOY
                </span>
                <h4 class="fw-bold m-0" style="color: #0f172a;">
                    Jornada de Limpieza: 2 Módulos Asignados
                </h4>
                <div class="text-muted small mt-1">
                    <i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($turnosHoy[0]->fecha_turno)->format('d/m/Y') }}
                </div>
            </div>
            @php $grupoResponsable = $turnosHoy[0]->grupo ?? $turnosHoy[1]->grupo; @endphp
            @if($grupoResponsable)
                <div class="text-end">
                    <small class="text-muted d-block" style="font-size: 11.5px;">Grupo Responsable de Ambos Módulos</small>
                    <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 700; font-size: 13px;">
                        <i class="fas fa-users me-1"></i> {{ $grupoResponsable->nombre }}
                    </span>
                </div>
            @endif
        </div>

        <div class="alert alert-info py-2 px-3 m-0" style="font-size: 13px; border-radius: 10px;">
            <i class="fas fa-info-circle me-1"></i>
            Tu ficha tiene <strong>2 módulos asignados</strong>. El mismo grupo de limpieza es el encargado de asear ambos módulos el día de hoy, por lo que debes certificar la labor subiendo <strong>4 fotos en total</strong> (2 fotos de antes y después por cada módulo).
        </div>
    </div>
</div>

<form method="POST" action="/vocero/evidencia/subir" enctype="multipart/form-data">
    @csrf

    <!-- BLOQUE MÓDULO 1 -->
    <div class="card-custom mb-4">
        <div class="card-custom-header d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #109116 0%, #0a5c0e 100%); color: #fff; padding: 14px 20px; border-top-left-radius: 14px; border-top-right-radius: 14px;">
            <h5 class="fw-bold m-0" style="font-size: 15px;">
                <span class="badge bg-success me-2">1</span> Módulo: {{ $turnosHoy[0]->asignacion->modulo->nombre }}
            </h5>
            <small style="color: #86efac; font-size: 12px;">
                <i class="fas fa-map-marker-alt me-1"></i> {{ $turnosHoy[0]->asignacion->modulo->ubicacion }}
            </small>
        </div>
        <div class="card-custom-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="p-3 rounded h-100" style="background: #fffbeb; border: 1.5px dashed #f59e0b;">
                        <label for="foto_antes_1" class="form-label fw-bold" style="color: #92400e;">
                            <i class="fas fa-camera me-1"></i> 1. Foto ANTES — {{ $turnosHoy[0]->asignacion->modulo->nombre }} <span class="text-danger">*</span>
                        </label>
                        <input type="file" id="foto_antes_1" name="foto_antes_1" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                        <small class="form-text text-muted d-block mt-1" style="font-size: 11.5px;">
                            Estado del módulo previo al aseo (JPG, PNG. Máx 10 MB)
                        </small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 rounded h-100" style="background: #f0fdf4; border: 1.5px dashed #109116;">
                        <label for="foto_despues_1" class="form-label fw-bold" style="color: #084d0b;">
                            <i class="fas fa-check-circle me-1"></i> 2. Foto DESPUÉS — {{ $turnosHoy[0]->asignacion->modulo->nombre }} <span class="text-danger">*</span>
                        </label>
                        <input type="file" id="foto_despues_1" name="foto_despues_1" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                        <small class="form-text text-muted d-block mt-1" style="font-size: 11.5px;">
                            Resultado final tras completar la labor (JPG, PNG. Máx 10 MB)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BLOQUE MÓDULO 2 -->
    <div class="card-custom mb-4">
        <div class="card-custom-header d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #109116 0%, #0a5c0e 100%); color: #fff; padding: 14px 20px; border-top-left-radius: 14px; border-top-right-radius: 14px;">
            <h5 class="fw-bold m-0" style="font-size: 15px;">
                <span class="badge bg-success me-2">2</span> Módulo: {{ $turnosHoy[1]->asignacion->modulo->nombre }}
            </h5>
            <small style="color: #86efac; font-size: 12px;">
                <i class="fas fa-map-marker-alt me-1"></i> {{ $turnosHoy[1]->asignacion->modulo->ubicacion }}
            </small>
        </div>
        <div class="card-custom-body p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="p-3 rounded h-100" style="background: #fffbeb; border: 1.5px dashed #f59e0b;">
                        <label for="foto_antes_2" class="form-label fw-bold" style="color: #92400e;">
                            <i class="fas fa-camera me-1"></i> 3. Foto ANTES — {{ $turnosHoy[1]->asignacion->modulo->nombre }} <span class="text-danger">*</span>
                        </label>
                        <input type="file" id="foto_antes_2" name="foto_antes_2" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                        <small class="form-text text-muted d-block mt-1" style="font-size: 11.5px;">
                            Estado del segundo módulo previo al aseo (JPG, PNG. Máx 10 MB)
                        </small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 rounded h-100" style="background: #f0fdf4; border: 1.5px dashed #109116;">
                        <label for="foto_despues_2" class="form-label fw-bold" style="color: #084d0b;">
                            <i class="fas fa-check-circle me-1"></i> 4. Foto DESPUÉS — {{ $turnosHoy[1]->asignacion->modulo->nombre }} <span class="text-danger">*</span>
                        </label>
                        <input type="file" id="foto_despues_2" name="foto_despues_2" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                        <small class="form-text text-muted d-block mt-1" style="font-size: 11.5px;">
                            Resultado final tras completar el segundo módulo (JPG, PNG. Máx 10 MB)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- OBSERVACIONES Y ENVÍO -->
    <div class="card-custom mb-4 p-4">
        <div class="mb-4">
            <label for="observaciones" class="form-label fw-semibold">Observaciones / Comentarios de la Jornada (Opcional)</label>
            <textarea id="observaciones" name="observaciones" class="form-control" rows="3" placeholder="Describe cualquier novedad, implementos utilizados o detalles de ambos módulos..."></textarea>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn-brand-solid" style="padding: 12px 30px; font-size: 15px;">
                <i class="fas fa-paper-plane me-1"></i> Subir las 4 Fotos y Certificar Ambos Módulos
            </button>
        </div>
    </div>
</form>

@else
<!-- ===== CASO: 1 MÓDULO ASIGNADO (2 FOTOS) ===== -->
<div class="card-custom mb-4" style="background: linear-gradient(135deg, rgba(16, 145, 22,0.06), rgba(16, 145, 22,0.02)); border: 1.5px solid rgba(16, 145, 22,0.25);">
    <div class="card-custom-body p-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <span class="badge mb-2" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px;">
                    <i class="fas fa-circle text-success me-1"></i> TURNO DE HOY ACTIVO
                </span>
                <h4 class="fw-bold m-0" style="color: #0f172a;">
                    {{ $turnoHoy->asignacion->modulo->nombre ?? 'Módulo Asignado' }}
                </h4>
                <div class="text-muted small mt-1">
                    <i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $turnoHoy->asignacion->modulo->ubicacion ?? 'Instalaciones SENA' }}
                    <span class="mx-2">•</span>
                    <i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($turnoHoy->fecha_turno)->format('d/m/Y') }}
                </div>
            </div>
            @if($turnoHoy->grupo)
                <div class="text-end">
                    <small class="text-muted d-block" style="font-size: 11.5px;">Grupo Responsable</small>
                    <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 700; font-size: 13px;">
                        <i class="fas fa-users me-1"></i> {{ $turnoHoy->grupo->nombre }}
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="card-custom mb-4">
    <div class="card-custom-header" style="background: linear-gradient(135deg, #109116 0%, #0a5c0e 100%); color: #fff; padding: 14px 20px; border-top-left-radius: 14px; border-top-right-radius: 14px;">
        <h5 class="fw-bold m-0" style="font-size: 16px;">
            <i class="fas fa-upload text-success me-2"></i>Formulario de Carga de Evidencias
        </h5>
    </div>
    <div class="card-custom-body p-4">
        <form method="POST" action="/vocero/evidencia/subir" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="turno_id" value="{{ $turnoHoy->id }}">

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="p-3 rounded h-100" style="background: #fffbeb; border: 1.5px dashed #f59e0b;">
                        <label for="foto_antes" class="form-label fw-bold" style="color: #92400e;">
                            <i class="fas fa-image me-1"></i> Foto ANTES de Limpiar <span class="text-danger">*</span>
                        </label>
                        <input type="file" id="foto_antes" name="foto_antes" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                        <small class="form-text text-muted d-block mt-1" style="font-size: 11.5px;">
                            Estado del módulo previo a la labor (JPG, PNG. Máx 10 MB)
                        </small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 rounded h-100" style="background: #f0fdf4; border: 1.5px dashed #109116;">
                        <label for="foto_despues" class="form-label fw-bold" style="color: #084d0b;">
                            <i class="fas fa-check-circle me-1"></i> Foto DESPUÉS de Limpiar <span class="text-danger">*</span>
                        </label>
                        <input type="file" id="foto_despues" name="foto_despues" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                        <small class="form-text text-muted d-block mt-1" style="font-size: 11.5px;">
                            Resultado final tras completar el aseo (JPG, PNG. Máx 10 MB)
                        </small>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label for="observaciones" class="form-label fw-semibold">Observaciones / Comentarios (Opcional)</label>
                <textarea id="observaciones" name="observaciones" class="form-control" rows="3" placeholder="Describe cualquier novedad, implementos utilizados o detalles del módulo..."></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn-brand-solid" style="padding: 12px 28px; font-size: 15px;">
                    <i class="fas fa-paper-plane me-1"></i> Subir y Certificar Turno
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<!-- RECIENTES EVIDENCIAS -->
<div class="mt-5">
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="fas fa-history" style="color: #109116;"></i>
        <h4 class="fw-bold m-0" style="font-size: 18px; color: #0f172a;">Evidencias Recientemente Reportadas</h4>
    </div>

    <div class="row g-3">
        @forelse($evidenciasRecientes as $ev)
        <div class="col-lg-2 col-md-4 col-6">
            <div class="card-custom h-100 overflow-hidden">
                <div style="height: 120px; background: #e2e8f0; position: relative;">
                    <img src="/storage/{{ $ev->ruta_archivo }}" alt="Evidencia" style="width: 100%; height: 100%; object-fit: cover;">
                    <span class="badge" style="position: absolute; top: 6px; right: 6px; font-size: 10px; background: {{ strtolower($ev->tipo) === 'antes' ? '#f59e0b' : '#109116' }}; color: #fff;">
                        {{ ucfirst($ev->tipo) }}
                    </span>
                </div>
                <div class="p-2 text-center">
                    <small class="text-muted d-block" style="font-size: 11px;">
                        {{ $ev->created_at ? $ev->created_at->format('d/m/Y') : '' }}
                    </small>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-muted py-3">
            <small>Aún no hay evidencias subidas recientemente.</small>
        </div>
        @endforelse
    </div>
</div>
@endsection
