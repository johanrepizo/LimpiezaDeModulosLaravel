<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarFichaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->rol_id == 1;
    }

    public function rules(): array
    {
        return [
            'programa_id' => 'required|exists:programas,id',
            'nombre' => 'required|string|max:255|unique:fichas,nombre,' . ($this->input('id') ?? 'NULL'),
            'jornada' => 'required|in:Diurna,Nocturna',
            'fecha_inicio' => 'nullable|date',
            'fecha_finalizacion' => 'nullable|date|after:fecha_inicio',
            'activo' => 'nullable|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'programa_id.required' => 'El programa es requerido',
            'nombre.required' => 'El nombre de la ficha es requerido',
            'jornada.required' => 'La jornada es requerida'
        ];
    }
}
