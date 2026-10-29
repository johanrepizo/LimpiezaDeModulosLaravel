@extends('layouts.app')

@section('title', 'Fichas - ' . $programa->nombre)

@section('content')
<!-- BREADCRUMB -->
<div class="mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
            <li class="breadcrumb-item"><a href="/admin/programas" class="text-decoration-none text-success"><i class="fas fa-graduation-cap"></i> Programas</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($programa->nombre, 35) }}</li>
        </ol>
    </nav>
</div>

<!-- PROGRAMA HERO BANNER -->
<div class="card-custom mb-4" style="border-left: 4px solid #109116;">
    <div class="card-custom-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge" style="background: #dcfce7; color: #084d0b; font-size: 11px; font-weight: 700;">Programa Activo</span>
                <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 11px; font-weight: 600;"><i class="fas fa-lock me-1"></i> Solo Lectura</span>
            </div>
            <h2 class="h4 fw-bold mb-1" style="color: #0f172a;">{{ $programa->nombre }}</h2>
            <p class="text-muted small mb-0">{{ $programa->descripcion ?? 'Programa de formación técnica sincronizado desde SICEFA' }}</p>
        </div>
        <div>
            <a href="/admin/programas" class="btn-brand-outline">
                <i class="fas fa-arrow-left"></i> Volver a Programas
            </a>
        </div>
    </div>
</div>

<!-- STATS -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-green">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $fichas->count() }}</div>
                <div class="stat-label">Fichas en este Programa</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-blue">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $fichas->sum(fn($f) => $f->aprendices->count()) }}</div>
                <div class="stat-label">Aprendices Vinculados</div>
            </div>
        </div>
    </div>
</div>

<!-- SECTION TITLE -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold m-0" style="color: #0f172a;">
        <i class="fas fa-folder-open text-success me-2"></i> Carpetas de Fichas ({{ $fichas->count() }})
    </h5>
    <small class="text-muted">Haz clic en cada sección para explorar aprendices, voceros y asignaciones</small>
</div>

@if($fichas->isEmpty())
    <div class="card-custom py-5 text-center text-muted">
        <i class="fas fa-folder fa-3x mb-2" style="color: #cbd5e1;"></i>
        <div>No hay fichas registradas para este programa en el sistema</div>
    </div>
@else
    <!-- GRID DE FICHAS -->
    <div class="row g-4">
        @foreach($fichas as $ficha)
        <div class="col-md-6 col-lg-4">
            <div class="card-custom h-100 folder-card">
                <div class="card-custom-body p-4 d-flex flex-column h-100">
                    <div class="d-flex align-items-start justify-content-between mb-3">
                        <div style="width: 44px; height: 44px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 6px;">
                            {{ $ficha->jornada ?? 'Diurna' }}
                        </span>
                    </div>

                    <h5 class="fw-bold mb-1" style="color: #0f172a; font-size: 16px;">
                        {{ $ficha->nombre }}
                    </h5>
                    <div class="text-muted small mb-3">
                        <i class="fas fa-barcode me-1"></i> Ficha: <strong>{{ $ficha->numero_ficha ?? 'S/N' }}</strong>
                    </div>

                    <!-- METRICS CLICKABLES -->
                    <div class="row text-center g-2 mb-3 mt-auto">
                        <div class="col-4">
                            <a href="/admin/aprendices?ficha_id={{ $ficha->id }}" class="metric-box d-block text-decoration-none" title="Ver aprendices de esta ficha">
                                <div class="fw-bold text-dark fs-5">{{ $ficha->aprendices->count() }}</div>
                                <small class="text-muted d-block" style="font-size: 11px;">Aprendices</small>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="/admin/aprendices?ficha_id={{ $ficha->id }}&rol=vocero" class="metric-box d-block text-decoration-none" title="Ver voceros de esta ficha (máx. 2)">
                                <div class="fw-bold text-success fs-5">{{ $ficha->voceros->count() }} <span style="font-size: 12px; font-weight: 500; color: #64748b;">/ 2</span></div>
                                <small class="text-muted d-block" style="font-size: 11px;">Voceros (máx. 2)</small>
                            </a>
                        </div>
                        <div class="col-4">
                            <a href="/admin/modulos?ficha_id={{ $ficha->id }}" class="metric-box d-block text-decoration-none" title="Ver asignaciones de módulos (máx. 2)">
                                <div class="fw-bold text-warning fs-5">{{ $ficha->asignaciones ? $ficha->asignaciones->where('activo', true)->count() : 0 }} <span style="font-size: 12px; font-weight: 500; color: #64748b;">/ 2</span></div>
                                <small class="text-muted d-block" style="font-size: 11px;">Módulos (máx. 2)</small>
                            </a>
                        </div>
                    </div>

                    <div class="pt-3 border-top">
                        <button class="btn-brand-outline w-100 justify-content-center" onclick="verDetalleFicha({{ json_encode($ficha) }}, '{{ $programa->nombre }}')">
                            <i class="fas fa-info-circle"></i> Ver Ficha Completa
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

<!-- MODAL DETALLE DE FICHA -->
<div class="modal fade" id="modalDetalleFicha" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: linear-gradient(135deg, #109116 0%, #0a5c0e 100%); color: #ffffff; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #14b51c; color: #fff; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h5 class="modal-title fw-bold m-0" id="detalleTituloFicha">Detalle de la Ficha</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="detalleContenidoFicha">
                <!-- Se llena dinámicamente -->
            </div>
            <div class="modal-footer bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                <button type="button" class="btn-brand-outline" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .folder-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .folder-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        border-color: #93c5fd;
    }
    .metric-box {
        background: #f8fafc;
        border: 1px solid #eef2ed;
        border-radius: 10px;
        padding: 8px 4px;
        transition: all 0.15s ease;
    }
    .metric-box:hover {
        background: #ecfdf5;
        border-color: #86efac;
        transform: scale(1.03);
    }
    .card-stat {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8e0;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .stat-icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
    }
    .stat-green { background: #dcfce7; color: #109116; }
    .stat-blue { background: #e0f2fe; color: #0284c7; }
    .stat-number { font-size: 24px; font-weight: 800; color: #0f172a; line-height: 1.1; }
    .stat-label { font-size: 13px; font-weight: 500; color: #64748b; margin-top: 3px; }
</style>
@endsection

@section('scripts')
<script>
function verDetalleFicha(ficha, programaNombre) {
    document.getElementById('detalleTituloFicha').innerText = ficha.nombre;
    const html = `
        <div class="mb-3 p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0;">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Programa:</span>
                <strong class="text-dark">${programaNombre}</strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Número de Ficha:</span>
                <strong class="text-dark">${ficha.numero_ficha || 'S/N'}</strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Jornada:</span>
                <strong class="text-dark">${ficha.jornada || 'Diurna'}</strong>
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Origen de Datos:</span>
                <span class="badge bg-light text-secondary border"><i class="fas fa-lock me-1"></i> SICEFA</span>
            </div>
        </div>

        <div class="d-flex flex-column gap-2 mt-3">
            <a href="/admin/aprendices?ficha_id=${ficha.id}" class="btn-brand-outline justify-content-center">
                <i class="fas fa-user-graduate me-1"></i> Ver Aprendices de esta Ficha
            </a>
            <a href="/admin/voceros?ficha_id=${ficha.id}" class="btn-brand-outline justify-content-center">
                <i class="fas fa-id-badge me-1"></i> Ver Voceros de esta Ficha
            </a>
            <a href="/admin/modulos?ficha_id=${ficha.id}" class="btn-brand-outline justify-content-center">
                <i class="fas fa-door-open me-1"></i> Ver Módulos Asignados
            </a>
            <a href="/admin/grupos?ficha_id=${ficha.id}" class="btn-brand-outline justify-content-center">
                <i class="fas fa-users-gear me-1"></i> Ver Grupos de Limpieza
            </a>
        </div>
    `;
    document.getElementById('detalleContenidoFicha').innerHTML = html;
    new bootstrap.Modal(document.getElementById('modalDetalleFicha')).show();
}
</script>
@endsection
