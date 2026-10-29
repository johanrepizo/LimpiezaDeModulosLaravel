@extends('layouts.app')

@section('title', 'Sincronización SICEFA')

@section('content')
<h1><i class="fas fa-sync"></i> Sincronización SICEFA</h1>
<hr>

<div class="alert alert-info">
    <i class="fas fa-info-circle"></i> Módulo de sincronización con sistema SICEFA
</div>

<div class="card">
    <div class="card-header">
        <h5>Historial de Sincronizaciones</h5>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Mensaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>{{ $log->tipo }}</td>
                    <td>
                        @if($log->estado === 'éxito')
                            <span class="badge bg-success">Éxito</span>
                        @else
                            <span class="badge bg-danger">Error</span>
                        @endif
                    </td>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ Str::limit($log->mensajes, 50) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
