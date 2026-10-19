<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarGrupoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->rol_id == 2;
    }

    public function prepareForValidation()
    {
        if (!$this->has('asignacion_id') && auth()->check() && auth()->user()->vocero) {
            $primeraAsig = \App\Models\Asignacion::where('ficha_id', auth()->user()->vocero->ficha_id)
                ->where('activo', true)
                ->first();
            if ($primeraAsig) {
                $this->merge(['asignacion_id' => $primeraAsig->id]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'aprendices' => 'required|array|min:1',
            'aprendices.*' => 'exists:aprendices,id',
            'asignacion_id' => 'required|exists:asignaciones,id'
        ];
    }

    public function messages(): array
    {
        return [
            'aprendices.required' => 'Debe seleccionar al menos un aprendiz',
            'aprendices.min' => 'Debe seleccionar al menos un aprendiz'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('asignacion_id') && is_array($this->aprendices)) {
                $asignacion = \App\Models\Asignacion::with('modulo')->find($this->asignacion_id);
                if ($asignacion && $asignacion->modulo) {
                    $capacidad = $asignacion->modulo->capacidad;
                    
                    // Si la ficha tiene múltiples asignaciones activas, respetar la menor capacidad
                    $minCapacidad = \App\Models\Asignacion::where('ficha_id', $asignacion->ficha_id)
                        ->where('activo', true)
                        ->with('modulo')
                        ->get()
                        ->min('modulo.capacidad') ?? $capacidad;

                    $limite = min($capacidad, $minCapacidad);

                    if (count($this->aprendices) > $limite) {
                        $validator->errors()->add('aprendices', "El módulo '{$asignacion->modulo->nombre}' tiene una capacidad máxima de {$limite} persona(s). No puedes crear un grupo con " . count($this->aprendices) . " aprendices.");
                    }
                }
            }

            // Validar que ningún aprendiz seleccionado pertenezca ya a un grupo activo
            if (is_array($this->aprendices)) {
                foreach ($this->aprendices as $idAprendiz) {
                    $aprendiz = \App\Models\Aprendiz::find($idAprendiz);
                    if ($aprendiz && $aprendiz->grupos()->where('grupos.activo', true)->exists()) {
                        $grupoActivo = $aprendiz->grupos()->where('grupos.activo', true)->first();
                        $nombreG = $grupoActivo ? ($grupoActivo->nombre ?? ('Grupo #' . $grupoActivo->numero_grupo)) : 'otro grupo';
                        $validator->errors()->add('aprendices', "El aprendiz {$aprendiz->nombres} {$aprendiz->apellidos} ya pertenece a {$nombreG}.");
                        break;
                    }
                }
            }
        });
    }
}
