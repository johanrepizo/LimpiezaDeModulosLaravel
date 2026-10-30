@extends('layouts.app')

@section('title', 'Mis Evidencias - Calendario y Galería')

@section('content')
<!-- ENCABEZADO -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="fas fa-images" style="color: #109116; font-size: 24px;"></i>
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Mis Evidencias Fotográficas</h1>
        </div>
        <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
            Consulta el calendario de fechas de limpieza de tu ficha y el historial de registros fotográficos
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="/vocero/subir-evidencia" class="btn-brand-solid">
            <i class="fas fa-camera me-1"></i> Subir Nueva Evidencia
        </a>
    </div>
</div>

<!-- NAVEGACIÓN ENTRE CALENDARIO Y GALERÍA -->
<ul class="nav nav-pills gap-2 mb-4" id="pills-tab-vocero-evidencias" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active d-flex align-items-center gap-2" id="tab-voc-cal-btn" data-bs-toggle="pill" data-bs-target="#tab-voc-cal" type="button" role="tab" style="border-radius: 10px; font-weight: 700; padding: 10px 22px;">
            <i class="fas fa-calendar-alt"></i> Calendario de Limpiezas
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link d-flex align-items-center gap-2" id="tab-voc-gal-btn" data-bs-toggle="pill" data-bs-target="#tab-voc-gal" type="button" role="tab" style="border-radius: 10px; font-weight: 700; padding: 10px 22px;">
            <i class="fas fa-images"></i> Galería de Fotos ({{ $evidencias->total() }})
        </button>
    </li>
</ul>

<!-- CONTENIDO DE PESTAÑAS -->
<div class="tab-content" id="pills-tabContentVocero">

    {{-- ===================================================================== --}}
    {{-- PESTAÑA 1: CALENDARIO DE CUMPLIMIENTO                                 --}}
    {{-- ===================================================================== --}}
    <div class="tab-pane fade show active" id="tab-voc-cal" role="tabpanel">

        <!-- LEYENDA DEL CALENDARIO -->
        <div class="d-flex align-items-center gap-4 flex-wrap mb-3 px-3 py-2 rounded" style="font-size: 13.5px; font-weight: 600; color: #334155; background: #ffffff; border: 1px solid #e2e8f0;">
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

        <!-- TARJETA DEL CALENDARIO -->
        <div class="card-custom p-4 mb-4">
            <!-- CONTROLES < > Hoy / Mes / Año -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-success px-3" onclick="cambiarMesVoc(-1)" title="Mes anterior" style="background: #109116; border-color: #109116; border-radius: 8px 0 0 8px;">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-success px-3" onclick="cambiarMesVoc(1)" title="Mes siguiente" style="background: #109116; border-color: #109116; border-radius: 0 8px 8px 0;">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <button type="button" class="btn btn-sm btn-success px-3" onclick="irAHoyVoc()" style="background: #109116; border-color: #109116; border-radius: 8px; font-weight: 600;">
                        Hoy
                    </button>
                </div>

                <div class="text-center flex-grow-1">
                    <h3 id="calVocTituloMes" class="mb-0 fw-bold" style="color: #0f172a; font-size: 22px; text-transform: lowercase;">
                        noviembre de 2026
                    </h3>
                </div>

                <div class="btn-group" role="group">
                    <button type="button" id="btnVocModoMes" class="btn btn-sm btn-success px-3" onclick="setModoVistaVoc('mes')" style="font-weight: 600; border-radius: 8px 0 0 8px;">
                        Mes
                    </button>
                    <button type="button" id="btnVocModoAno" class="btn btn-sm btn-outline-secondary px-3" onclick="setModoVistaVoc('ano')" style="font-weight: 600; border-radius: 0 8px 8px 0;">
                        Año
                    </button>
                </div>
            </div>

            <!-- VISTA MES -->
            <div id="vistaVocMesContenedor">
                <div class="row g-0 text-center mb-1" style="border-bottom: 2px solid #e2e8f0; padding-bottom: 8px;">
                    <div class="col cal-header-day text-primary fw-bold">dom</div>
                    <div class="col cal-header-day text-primary fw-bold">lun</div>
                    <div class="col cal-header-day text-primary fw-bold">mar</div>
                    <div class="col cal-header-day text-primary fw-bold">mié</div>
                    <div class="col cal-header-day text-primary fw-bold">jue</div>
                    <div class="col cal-header-day text-primary fw-bold">vie</div>
                    <div class="col cal-header-day text-primary fw-bold">sáb</div>
                </div>

                <div id="calVocGridDias" class="cal-grid">
                    <!-- Inyectado por JS -->
                </div>
            </div>

            <!-- VISTA AÑO -->
            <div id="vistaVocAnoContenedor" style="display: none;">
                <div class="row g-3" id="calVocGridAno">
                    <!-- 12 mini calendarios -->
                </div>
            </div>
        </div>
    </div>

    {{-- ===================================================================== --}}
    {{-- PESTAÑA 2: GALERÍA DE FOTOS SUBIDAS                                   --}}
    {{-- ===================================================================== --}}
    <div class="tab-pane fade" id="tab-voc-gal" role="tabpanel">
        <div class="row g-4">
            @forelse($evidencias as $evidencia)
                @php
                    $fechaFormat = $evidencia->created_at 
                        ? $evidencia->created_at->format('d/m/Y H:i') 
                        : ($evidencia->fecha_creacion ? \Carbon\Carbon::parse($evidencia->fecha_creacion)->format('d/m/Y H:i') : 'N/A');
                    $esAntes = strtolower($evidencia->tipo) === 'antes';
                    $moduloNom = $evidencia->turno?->asignacion?->modulo?->nombre ?? 'Módulo General';
                    $grupoNom = $evidencia->turno?->grupo?->nombre ?? 'Grupo';
                @endphp
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card-custom h-100 d-flex flex-column" style="overflow: hidden; border: 1px solid #e2e8f0;">
                        <div style="height: 200px; background: #0f172a; overflow: hidden; position: relative; cursor: pointer;" onclick="abrirModalPreviewVoc('{{ asset('storage/' . $evidencia->ruta_archivo) }}', '{{ $esAntes ? 'Foto Antes de Limpieza' : 'Foto Después de Limpieza' }}', '{{ addslashes($moduloNom) }}', 'Turno #{{ $evidencia->turno_id }}', '{{ $fechaFormat }}', '{{ addslashes($grupoNom) }}', '{{ addslashes($evidencia->observaciones ?? '') }}')">
                            <img src="{{ asset('storage/' . $evidencia->ruta_archivo) }}" alt="Evidencia" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.25s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" onerror="this.src='https://placehold.co/400x300/e2e8f0/475569?text=Sin+Foto';">
                            <div style="position: absolute; top: 12px; right: 12px;">
                                @if($esAntes)
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.95); color: #ffffff; font-weight: 700; padding: 5px 10px; border-radius: 6px; font-size: 11px;">
                                        <i class="fas fa-camera me-1"></i> Foto Antes
                                    </span>
                                @else
                                    <span class="badge" style="background: rgba(16, 145, 22, 0.95); color: #ffffff; font-weight: 700; padding: 5px 10px; border-radius: 6px; font-size: 11px;">
                                        <i class="fas fa-check-circle me-1"></i> Foto Después
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="card-custom-body flex-grow-1 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold mb-0" style="color: #0f172a;">Turno #{{ $evidencia->turno_id }}</h6>
                                <small class="text-muted" style="font-size: 11.5px;">{{ $fechaFormat }}</small>
                            </div>
                            <div class="text-success small fw-bold mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $moduloNom }} &bull; {{ $grupoNom }}
                            </div>
                            @if($evidencia->observaciones)
                                <p class="small text-secondary mb-0 p-2 rounded" style="background: #f8fafc; font-size: 12px; border-left: 2px solid #cbd5e1;">
                                    "{{ $evidencia->observaciones }}"
                                </p>
                            @endif
                        </div>
                        <div class="p-3 border-top bg-light text-center">
                            <button type="button" class="btn-brand-outline w-100 justify-content-center" onclick="abrirModalPreviewVoc('{{ asset('storage/' . $evidencia->ruta_archivo) }}', '{{ $esAntes ? 'Foto Antes de Limpieza' : 'Foto Después de Limpieza' }}', '{{ addslashes($moduloNom) }}', 'Turno #{{ $evidencia->turno_id }}', '{{ $fechaFormat }}', '{{ addslashes($grupoNom) }}', '{{ addslashes($evidencia->observaciones ?? '') }}')">
                                <i class="fas fa-expand me-1"></i> Ver Imagen Ampliada
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center text-muted">
                    <i class="fas fa-images fa-3x mb-2" style="color: #cbd5e1;"></i>
                    <div>Aún no se han subido evidencias para los turnos de tu ficha</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $evidencias->links() }}
        </div>
    </div>
</div>

<!-- MODAL DE DETALLE DEL DÍA DE LIMPIEZA (INTERACTIVO AL CLIC EN EL CALENDARIO) -->
<div class="modal fade" id="modalDetalleDiaVocero" tabindex="-1" aria-labelledby="modalDetalleDiaVoceroLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 18px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div class="modal-header" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; border-bottom: none; padding: 20px 26px;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 145, 22, 0.2); color: #4ade80; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalVocDiaTitulo" style="font-size: 18px;">Detalle de la Fecha de Limpieza</h5>
                        <small id="modalVocDiaSubtitulo" style="color: #94a3b8; font-size: 13px;">Mi Ficha Académica</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background: #f8fafc; max-height: 75vh;">
                <div id="modalVocDiaModulosContenedor" class="d-flex flex-column gap-4">
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

<!-- MODAL PREVIEW DE EVIDENCIA (LIGHTBOX INDIVIDUAL VOCERO) -->
<div class="modal fade" id="modalPreviewEvidenciaVocero" tabindex="-1" aria-labelledby="modalPreviewEvidenciaVoceroLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
            <div class="modal-header" style="background: #0f172a; color: #ffffff; border-bottom: none; padding: 16px 24px;">
                <div>
                    <h5 class="modal-title fw-bold" id="modalPreviewVocTitle" style="font-size: 17px; margin: 0;">Detalle de Evidencia</h5>
                    <small id="modalPreviewVocSubtitle" style="color: #94a3b8; font-size: 12.5px;">Turno de Limpieza</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="background: #090d16; text-align: center;">
                <img id="modalPreviewVocImg" src="" alt="Evidencia en alta resolución" style="max-height: 520px; width: 100%; object-fit: contain; background: #020617;">
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center p-3" style="background: #ffffff; border-top: 1px solid #e2e8f0;">
                <div class="text-start" style="font-size: 12.5px; color: #475569;">
                    <div><i class="fas fa-map-marker-alt text-success me-1"></i> <strong id="modalPreviewVocModulo">-</strong> &bull; <span id="modalPreviewVocFecha">-</span></div>
                    <div><i class="fas fa-users text-primary me-1"></i> <span id="modalPreviewVocGrupo">-</span> &bull; <em><span id="modalPreviewVocObs"></span></em></div>
                </div>
                <div class="d-flex gap-2">
                    <a id="modalPreviewVocDownload" href="#" download class="btn btn-sm btn-outline-success" style="border-radius: 8px; font-weight: 600;">
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
    /* Lightbox Modal de Preview de Evidencia Vocero: siempre por encima de cualquier otro modal */
    #modalPreviewEvidenciaVocero {
        z-index: 1070 !important;
    }
    #modalPreviewEvidenciaVocero .modal-dialog {
        z-index: 1071 !important;
    }

    /* Píldoras activas en verde SENA */
    #pills-tab-vocero-evidencias .nav-link {
        color: #475569;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    #pills-tab-vocero-evidencias .nav-link.active {
        background: #109116 !important;
        color: #ffffff !important;
        border-color: #109116 !important;
        box-shadow: 0 4px 12px rgba(16, 145, 22, 0.25);
    }

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
    }
    .cal-badge-evento:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.12);
    }

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
    // Modal de Preview de Foto Individual Vocero (Lightbox)
    function abrirModalPreviewVoc(imgSrc, titulo, modulo, turno, fecha, grupo, observaciones) {
        const imgEl = document.getElementById('modalPreviewVocImg');
        const titEl = document.getElementById('modalPreviewVocTitle');
        const subEl = document.getElementById('modalPreviewVocSubtitle');
        const modEl = document.getElementById('modalPreviewVocModulo');
        const fecEl = document.getElementById('modalPreviewVocFecha');
        const grpEl = document.getElementById('modalPreviewVocGrupo');
        const obsEl = document.getElementById('modalPreviewVocObs');
        const dlBtn = document.getElementById('modalPreviewVocDownload');

        if (imgEl) imgEl.src = imgSrc;
        if (titEl) titEl.innerText = titulo;
        if (subEl) subEl.innerText = (turno || '') + (modulo ? ' - ' + modulo : '');
        if (modEl) modEl.innerText = modulo || '-';
        if (fecEl) fecEl.innerText = fecha || '-';
        if (grpEl) grpEl.innerText = grupo || '-';
        if (obsEl) obsEl.innerText = observaciones ? '"' + observaciones + '"' : 'Sin observaciones adicionales';
        if (dlBtn) dlBtn.href = imgSrc;

        const modalEl = document.getElementById('modalPreviewEvidenciaVocero');
        if (modalEl) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();

            // Elevar el backdrop para que quede por encima del modal de detalle del calendario
            setTimeout(() => {
                const backdrops = document.querySelectorAll('.modal-backdrop');
                if (backdrops.length > 1) {
                    backdrops[backdrops.length - 1].style.zIndex = '1065';
                }
            }, 50);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const previewVocEl = document.getElementById('modalPreviewEvidenciaVocero');
        if (previewVocEl) {
            previewVocEl.addEventListener('hidden.bs.modal', function () {
                const detalleVocEl = document.getElementById('modalDetalleDiaVocero');
                if (detalleVocEl && detalleVocEl.classList.contains('show')) {
                    document.body.classList.add('modal-open');
                    document.body.style.overflow = 'hidden';
                }
            });
        }
    });

    @if(isset($eventosCalendario))
        const eventosDataVoc = @json($eventosCalendario);
        const nombresMesesVoc = [
            'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
            'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
        ];

        const fechaHoyVoc = new Date();
        let calAnoVoc = fechaHoyVoc.getFullYear();
        let calMesVoc = fechaHoyVoc.getMonth();
        let modoVistaVoc = 'mes';

        const eventosPorFechaVoc = {};
        eventosDataVoc.forEach(ev => {
            if (!eventosPorFechaVoc[ev.fecha]) {
                eventosPorFechaVoc[ev.fecha] = [];
            }
            eventosPorFechaVoc[ev.fecha].push(ev);
        });

        function renderCalendarioVoc() {
            if (modoVistaVoc === 'mes') {
                renderVistaMesVoc();
            } else {
                renderVistaAnoVoc();
            }
        }

        function cambiarMesVoc(delta) {
            calMesVoc += delta;
            if (calMesVoc > 11) {
                calMesVoc = 0;
                calAnoVoc++;
            } else if (calMesVoc < 0) {
                calMesVoc = 11;
                calAnoVoc--;
            }
            renderCalendarioVoc();
        }

        function irAHoyVoc() {
            calAnoVoc = fechaHoyVoc.getFullYear();
            calMesVoc = fechaHoyVoc.getMonth();
            modoVistaVoc = 'mes';
            actualizarBotonesModoVoc();
            renderCalendarioVoc();
        }

        function setModoVistaVoc(nuevoModo) {
            modoVistaVoc = nuevoModo;
            actualizarBotonesModoVoc();
            renderCalendarioVoc();
        }

        function actualizarBotonesModoVoc() {
            const btnMes = document.getElementById('btnVocModoMes');
            const btnAno = document.getElementById('btnVocModoAno');
            const contMes = document.getElementById('vistaVocMesContenedor');
            const contAno = document.getElementById('vistaVocAnoContenedor');

            if (modoVistaVoc === 'mes') {
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

        function formatFechaIsoVoc(ano, mes, dia) {
            const m = String(mes + 1).padStart(2, '0');
            const d = String(dia).padStart(2, '0');
            return `${ano}-${m}-${d}`;
        }

        function renderVistaMesVoc() {
            const tituloEl = document.getElementById('calVocTituloMes');
            tituloEl.innerText = `${nombresMesesVoc[calMesVoc]} de ${calAnoVoc}`;

            const gridEl = document.getElementById('calVocGridDias');
            gridEl.innerHTML = '';

            const primerDiaSemana = new Date(calAnoVoc, calMesVoc, 1).getDay();
            const totalDiasMes = new Date(calAnoVoc, calMesVoc + 1, 0).getDate();
            const diasMesAnterior = new Date(calAnoVoc, calMesVoc, 0).getDate();
            const hoyIso = formatFechaIsoVoc(fechaHoyVoc.getFullYear(), fechaHoyVoc.getMonth(), fechaHoyVoc.getDate());

            for (let i = primerDiaSemana - 1; i >= 0; i--) {
                const diaNum = diasMesAnterior - i;
                const prevMes = calMesVoc === 0 ? 11 : calMesVoc - 1;
                const prevAno = calMesVoc === 0 ? calAnoVoc - 1 : calAnoVoc;
                const fechaIso = formatFechaIsoVoc(prevAno, prevMes, diaNum);
                gridEl.appendChild(crearCeldaDiaVoc(diaNum, fechaIso, true, false));
            }

            for (let dia = 1; dia <= totalDiasMes; dia++) {
                const fechaIso = formatFechaIsoVoc(calAnoVoc, calMesVoc, dia);
                const esHoy = fechaIso === hoyIso;
                gridEl.appendChild(crearCeldaDiaVoc(dia, fechaIso, false, esHoy));
            }

            const totalCeldasActuales = primerDiaSemana + totalDiasMes;
            const celdasRestantes = (7 - (totalCeldasActuales % 7)) % 7;
            for (let dia = 1; dia <= celdasRestantes; dia++) {
                const nextMes = calMesVoc === 11 ? 0 : calMesVoc + 1;
                const nextAno = calMesVoc === 11 ? calAnoVoc + 1 : calAnoVoc;
                const fechaIso = formatFechaIsoVoc(nextAno, nextMes, dia);
                gridEl.appendChild(crearCeldaDiaVoc(dia, fechaIso, true, false));
            }
        }

        function crearCeldaDiaVoc(diaNum, fechaIso, esOtroMes, esHoy) {
            const celda = document.createElement('div');
            celda.className = 'cal-day-cell' + (esOtroMes ? ' cal-day-otro-mes' : '') + (esHoy ? ' cal-day-hoy' : '');
            
            const numSpan = document.createElement('span');
            numSpan.className = 'cal-day-num';
            numSpan.innerText = diaNum;
            celda.appendChild(numSpan);

            const eventosCont = document.createElement('div');
            eventosCont.className = 'd-flex flex-column gap-1 w-100 flex-grow-1';

            const turnosDia = eventosPorFechaVoc[fechaIso] || [];
            if (turnosDia.length > 0) {
                celda.style.cursor = 'pointer';
                celda.onclick = () => abrirDetalleDiaVoc(fechaIso);

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
                celda.onclick = () => abrirDetalleDiaVoc(fechaIso);
            }

            celda.appendChild(eventosCont);
            return celda;
        }

        function renderVistaAnoVoc() {
            const tituloEl = document.getElementById('calVocTituloMes');
            tituloEl.innerText = `Año ${calAnoVoc}`;

            const gridAnoEl = document.getElementById('calVocGridAno');
            gridAnoEl.innerHTML = '';
            const hoyIso = formatFechaIsoVoc(fechaHoyVoc.getFullYear(), fechaHoyVoc.getMonth(), fechaHoyVoc.getDate());

            for (let m = 0; m < 12; m++) {
                const col = document.createElement('div');
                col.className = 'col-md-4 col-sm-6';

                const card = document.createElement('div');
                card.className = 'mini-cal-card h-100';
                card.onclick = () => {
                    calMesVoc = m;
                    setModoVistaVoc('mes');
                };

                const title = document.createElement('div');
                title.className = 'mini-cal-title';
                title.innerHTML = `<span>${nombresMesesVoc[m]}</span> <i class="fas fa-arrow-right text-muted small"></i>`;
                card.appendChild(title);

                const miniGrid = document.createElement('div');
                miniGrid.className = 'mini-cal-grid';

                ['D','L','M','M','J','V','S'].forEach(d => {
                    const dh = document.createElement('div');
                    dh.style.color = '#94a3b8';
                    dh.style.fontWeight = '700';
                    dh.innerText = d;
                    miniGrid.appendChild(dh);
                });

                const primerDia = new Date(calAnoVoc, m, 1).getDay();
                const totalDias = new Date(calAnoVoc, m + 1, 0).getDate();

                for (let b = 0; b < primerDia; b++) {
                    const empty = document.createElement('div');
                    miniGrid.appendChild(empty);
                }

                for (let d = 1; d <= totalDias; d++) {
                    const fechaIso = formatFechaIsoVoc(calAnoVoc, m, d);
                    const turnosDia = eventosPorFechaVoc[fechaIso] || [];

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

        function abrirDetalleDiaVoc(fechaIso) {
            const turnosDia = eventosPorFechaVoc[fechaIso] || [];
            const partes = fechaIso.split('-');
            const fechaObj = new Date(partes[0], partes[1] - 1, partes[2]);
            const opcionesFecha = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const fechaTextoLargo = fechaObj.toLocaleDateString('es-ES', opcionesFecha);

            document.getElementById('modalVocDiaTitulo').innerText = `Limpieza: ${fechaTextoLargo}`;
            document.getElementById('modalVocDiaSubtitulo').innerText = `Ficha {{ $ficha->numero_ficha ?? '' }}`;

            const contModulos = document.getElementById('modalVocDiaModulosContenedor');
            contModulos.innerHTML = '';

            if (turnosDia.length === 0) {
                contModulos.innerHTML = `
                    <div class="card p-4 text-center text-muted" style="border: 2px dashed #cbd5e1; border-radius: 12px; background: #ffffff;">
                        <i class="fas fa-calendar-xmark fa-3x mb-2" style="color: #94a3b8;"></i>
                        <h6 class="fw-bold mb-1" style="color: #334155;">Sin jornada de limpieza</h6>
                        <small>Esta fecha no tiene turnos programados para los módulos de tu ficha.</small>
                    </div>
                `;
            } else {
                turnosDia.forEach(t => {
                    const cardModulo = document.createElement('div');
                    cardModulo.className = 'card p-4 shadow-sm';
                    cardModulo.style.borderRadius = '14px';
                    cardModulo.style.background = '#ffffff';
                    cardModulo.style.border = '1px solid #e2e8f0';

                    let bannerHtml = '';
                    let evidenciasHtml = '';

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
                                            <div style="height: 170px; border-radius: 8px; overflow: hidden; background: #0f172a; cursor: pointer;" onclick="abrirModalPreviewVoc('${ev.ruta_archivo}', '${badgeText} - ${t.modulo_nombre}', '${t.modulo_nombre}', 'Turno #${t.id}', '${ev.fecha_subida || ''}', '${t.grupo_nombre}', '${(ev.observaciones || '').replace(/'/g, "\\'")}')">
                                                <img src="${ev.ruta_archivo}" alt="Evidencia" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.25s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" onerror="this.src='https://placehold.co/400x300/e2e8f0/475569?text=Sin+Foto';">
                                            </div>
                                            ${ev.observaciones ? `<p class="small text-muted mt-2 mb-0" style="font-size: 11.5px;"><em>"${ev.observaciones}"</em></p>` : ''}
                                        </div>
                                    </div>
                                `;
                            });
                            fotosGrid += '</div>';
                            evidenciasHtml = fotosGrid;
                        }

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
                    } else {
                        const mensajeGris = t.es_hoy 
                            ? 'La jornada de limpieza corresponde al día de hoy. Tienes habilitada la plataforma para registrar las evidencias fotográficas antes de que finalice la jornada.'
                            : 'Turno de limpieza programado para una fecha futura. La subida de evidencias se habilitará una vez inicie la jornada de limpieza.';

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

                    let integrantesList = '';
                    if (t.integrantes && t.integrantes.length > 0) {
                        integrantesList = t.integrantes.map(nombre => `<span class="badge bg-light text-dark border me-1 mb-1" style="font-size: 11px;"><i class="fas fa-user me-1 text-success"></i> ${nombre}</span>`).join('');
                    } else {
                        integrantesList = '<span class="text-muted small">Sin integrantes asignados</span>';
                    }

                    cardModulo.innerHTML = `
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

                        ${bannerHtml}

                        <div class="row g-2 mb-3 p-3 rounded" style="background: #f8fafc; font-size: 12.5px; border: 1px solid #e2e8f0;">
                            <div class="col-sm-6">
                                <span class="text-muted d-block" style="font-size: 11px; font-weight: 700; text-transform: uppercase;">Grupo Responsable:</span>
                                <strong style="color: #0f172a;"><i class="fas fa-users text-primary me-1"></i> ${t.grupo_nombre}</strong>
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

                        ${evidenciasHtml}

                        ${t.es_hoy && t.color !== 'verde' ? `
                            <div class="mt-3 text-end">
                                <a href="/vocero/subir-evidencia" class="btn btn-sm btn-success px-3" style="border-radius: 8px; font-weight: 600;">
                                    <i class="fas fa-camera me-1"></i> Subir Evidencias Ahora
                                </a>
                            </div>
                        ` : ''}
                    `;

                    contModulos.appendChild(cardModulo);
                });
            }

            const modalDiaEl = document.getElementById('modalDetalleDiaVocero');
            const modalDia = bootstrap.Modal.getOrCreateInstance(modalDiaEl);
            modalDia.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderCalendarioVoc();
        });
    @endif
</script>
@endsection
