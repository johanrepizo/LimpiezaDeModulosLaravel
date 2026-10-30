@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content')
<h1><i class="fas fa-bell"></i> Mis Notificaciones</h1>
<hr>

<div class="card">
    <div class="list-group list-group-flush">
        @foreach($notificaciones as $notif)
        <div class="list-group-item">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h6 class="mb-1">{{ $notif->titulo }}</h6>
                    <p class="mb-1">{{ $notif->descripcion }}</p>
                    <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                </div>
                @if(!$notif->leida)
                    <span class="badge bg-primary">Nuevo</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
