<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubirEvidenciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->rol_id == 2;
    }

    public function rules(): array
    {
        return [
            'turno_id' => 'required|exists:turnos,id',
            'foto_antes' => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'foto_despues' => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'observaciones' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'turno_id.required' => 'El turno es requerido',
            'foto_antes.required' => 'La foto antes es requerida',
            'foto_despues.required' => 'La foto después es requerida',
            'foto_antes.image' => 'El archivo antes debe ser una imagen',
            'foto_despues.image' => 'El archivo después debe ser una imagen',
            'foto_antes.max' => 'La foto antes no puede exceder 10 MB',
            'foto_despues.max' => 'La foto después no puede exceder 10 MB'
        ];
    }
}
