<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $table = 'programas';
    protected $fillable = ['nombre', 'descripcion', 'nivel', 'activo'];
    public $timestamps = true;

    public function fichas()
    {
        return $this->hasMany(Ficha::class, 'programa_id');
    }

    public function tieneFichasActivas(): bool
    {
        return $this->fichas()->where('activo', true)->exists();
    }

    public function obtenerFichas()
    {
        return $this->fichas()->where('activo', true)->get();
    }

    public function contarEvidencias(): int
    {
        return Evidencia::whereHas('turno.asignacion.ficha', function ($q) {
            $q->where('programa_id', $this->id);
        })->count();
    }

    public function contarGrupos(): int
    {
        return Grupo::where('activo', true)
            ->whereHas('vocero.ficha', function ($q) {
                $q->where('programa_id', $this->id);
            })->count();
    }
}
