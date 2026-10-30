@extends('layouts.app')

@section('title', 'Aprendices')

@section('content')
<h1><i class="fas fa-users"></i> Aprendices de Mi Ficha</h1>
<hr>

<div class="card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aprendices as $aprendiz)
                <tr>
                    <td>{{ $aprendiz->nombres }} {{ $aprendiz->apellidos }}</td>
                    <td>{{ $aprendiz->documento }}</td>
                    <td>{{ $aprendiz->correo }}</td>
                    <td>{{ $aprendiz->telefono }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
