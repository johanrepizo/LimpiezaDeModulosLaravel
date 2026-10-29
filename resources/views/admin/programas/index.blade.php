@extends('layouts.app')

@section('title', 'Programas de Formación')

@section('content')
<!-- BREADCRUMB -->
<div class="mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/admin" class="text-decoration-none text-success"><i class="fas fa-home"></i> Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Programas de Formación</li>
        </ol>
    </nav>
</div>

<!-- HEADER -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="fas fa-graduation-cap" style="color: #109116; font-size: 22px;"></i>
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Programas de Formación</h1>
        </div>
        <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
            Estructura académica sincronizada desde el sistema institucional SICEFA
        </p>
    </div>
    <div>
        <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 12px; font-weight: 600; padding: 7px 14px; border-radius: 8px;">
            <i class="fas fa-lock me-1"></i> Solo Lectura (SICEFA)
        </span>
    </div>
</div>

<!-- STATS CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-green">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $programas->count() }}</div>
                <div class="stat-label">Programas Activos</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="card-stat">
            <div class="stat-icon-wrapper stat-blue">
                <i class="fas fa-folder"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $programas->sum(fn($p) => $p->fichas->count()) }}</div>
                <div class="stat-label">Fichas Registradas</div>
            </div>
        </div>
    </div>
</div>

<!-- BUSCADOR -->
<div class="mb-4">
    <div class="position-relative">
        <i class="fas fa-search position-absolute" style="left: 16px; top: 14px; color: #94a3b8;"></i>
        <input type="text" class="form-control ps-5" id="buscarPrograma" placeholder="Buscar programa por nombre o código..." onkeyup="filtrarProgramas()" style="padding-top: 10px; padding-bottom: 10px; border-radius: 10px;">
    </div>
</div>

<!-- GRID DE PROGRAMAS -->
<div class="row g-4" id="contenedorProgramas">
    @forelse($programas as $programa)
    <div class="col-md-6 col-lg-4 programa-card">
        <div class="card-custom h-100 folder-card" onclick="verFichas({{ $programa->id }})" title="Clic para explorar fichas de este programa">
            <div class="card-custom-body d-flex flex-column h-100 p-4">
                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div style="width: 44px; height: 44px; border-radius: 10px; background: #dcfce7; color: #109116; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <span class="badge" style="background: #dcfce7; color: #084d0b; font-weight: 700; font-size: 11px; padding: 4px 10px; border-radius: 6px;">
                        Activo
                    </span>
                </div>

                <h5 class="fw-bold mb-1 programa-nombre" style="color: #0f172a; font-size: 16px;">{{ $programa->nombre }}</h5>
                @if($programa->nivel)
                    <small class="text-muted mb-2 d-block">{{ $programa->nivel }}</small>
                @endif
                <p class="text-secondary small mb-3 flex-grow-1" style="line-height: 1.45;">
                    {{ Str::limit($programa->descripcion ?? 'Programa de formación técnica del Centro de Formación', 95) }}
                </p>

                <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-layer-group text-success"></i>
                        <span class="fw-bold text-dark">{{ $programa->fichas->count() }}</span>
                        <span class="text-muted small">Fichas vinculadas</span>
                    </div>
                    <span class="btn-brand-outline" style="padding: 4px 12px; font-size: 12px;">
                        Ver Fichas <i class="fas fa-arrow-right ms-1"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 py-5 text-center text-muted">
        <i class="fas fa-book-open fa-3x mb-2" style="color: #cbd5e1;"></i>
        <div>No hay programas registrados en el sistema</div>
    </div>
    @endforelse
</div>
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
function verFichas(programaId) {
    window.location.href = '/admin/programa/' + programaId + '/fichas';
}

function filtrarProgramas() {
    const input = document.getElementById('buscarPrograma').value.toLowerCase();
    const cards = document.querySelectorAll('.programa-card');
    cards.forEach(card => {
        const nombre = card.querySelector('.programa-nombre').textContent.toLowerCase();
        if (nombre.includes(input)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection
