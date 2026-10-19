<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarProgramaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->rol_id == 1;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255|unique:programas,nombre,' . ($this->input('id') ?? 'NULL'),
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del programa es requerido',
            'nombre.unique' => 'Ya existe un programa con ese nombre'
        ];
    }
}
