@extends('layouts.app')

@section('title', 'Fichas de Formación')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="fas fa-folder-open" style="color: #109116; font-size: 22px;"></i>
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.3px;">Fichas de Formación</h1>
        </div>
        <p style="font-size: 13.5px; color: #64748b; font-weight: 500; margin: 0;">
            Listado general de fichas sincronizadas desde SICEFA
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge" style="background: #e2e8f0; color: #475569; font-size: 12px; font-weight: 600; padding: 7px 14px; border-radius: 8px;">
            <i class="fas fa-lock me-1"></i> Solo Lectura (SICEFA)
        </span>
        <a href="/admin/programas" class="btn-brand-outline">
            <i class="fas fa-graduation-cap"></i> Ver por Programa
        </a>
    </div>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table-clean">
            <thead>
                <tr>
                    <th style="width: 25%;">NOMBRE DE LA FICHA</th>
                    <th style="width: 15%;">NÚMERO</th>
                    <th style="width: 28%;">PROGRAMA ASOCIADO</th>
                    <th style="width: 12%;">JORNADA</th>
                    <th style="width: 20%; text-align: right;">ACCIONES</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fichas as $ficha)
                <tr>
                    <td>
                        <strong style="color: #0f172a;">{{ $ficha->nombre }}</strong>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $ficha->numero_ficha ?? 'S/N' }}</span>
                    </td>
                    <td>
                        <span style="color: #475569;">{{ $ficha->programa->nombre ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="badge" style="background: #e0f2fe; color: #0284c7; font-weight: 600; font-size: 11px;">
                            {{ $ficha->jornada ?? 'Diurna' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <a href="/admin/aprendices?ficha_id={{ $ficha->id }}" class="btn-brand-outline" style="padding: 4px 10px; font-size: 12px;" title="Ver Aprendices y Voceros">
                            <i class="fas fa-user-graduate"></i>
                        </a>
                        <a href="/admin/modulos?ficha_id={{ $ficha->id }}" class="btn-brand-outline" style="padding: 4px 10px; font-size: 12px;" title="Asignaciones">
                            <i class="fas fa-door-open"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-5 text-center text-muted">
                        <i class="fas fa-folder fa-3x mb-2" style="color: #cbd5e1;"></i>
                        <div>No hay fichas registradas</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
