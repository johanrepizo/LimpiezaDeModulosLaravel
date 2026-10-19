<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsignarModuloRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->rol_id == 1;
    }

    public function rules(): array
    {
        return [
            'modulo_id' => 'required|exists:modulos,id',
            'ficha_id' => 'required|exists:fichas,id',
            'fecha_inicio' => 'required|date',
            'dia_semana' => 'required|integer|between:0,6'
        ];
    }

    public function messages(): array
    {
        return [
            'modulo_id.required' => 'El módulo es requerido',
            'ficha_id.required' => 'La ficha es requerida',
            'fecha_inicio.required' => 'La fecha de inicio es requerida',
            'dia_semana.required' => 'El día de la semana es requerido'
        ];
    }
}
