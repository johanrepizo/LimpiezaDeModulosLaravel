<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    protected $table = 'fichas';
    protected $fillable = ['programa_id', 'nombre', 'numero_ficha', 'jornada', 'activo', 'fecha_inicio', 'fecha_finalizacion'];
    protected $dates = ['fecha_inicio', 'fecha_finalizacion'];
    public $timestamps = true;

    public function programa()
    {
        return $this->belongsTo(Programa::class, 'programa_id');
    }

    public function aprendices()
    {
        return $this->hasMany(Aprendiz::class, 'ficha_id');
    }

    public function voceros()
    {
        return $this->hasMany(Vocero::class, 'ficha_id');
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'ficha_id');
    }

    public function obtenerAprendices()
    {
        return $this->aprendices()->where('activo', true)->get();
    }

    public function tieneAsignacionesActivas(): bool
    {
        return $this->asignaciones()->where('activo', true)->exists();
    }

    public function obtenerVocerosActivos()
    {
        return $this->voceros()->where('activo', true)->get();
    }

    public function contarEvidencias(): int
    {
        return Evidencia::whereHas('turno.asignacion', function ($q) {
            $q->where('ficha_id', $this->id);
        })->count();
    }

    public function contarGruposActivos(): int
    {
        return Grupo::where('activo', true)
            ->where(function ($q) {
                $q->whereHas('vocero', fn($sq) => $sq->where('ficha_id', $this->id))
                  ->orWhereHas('asignacion', fn($sq) => $sq->where('ficha_id', $this->id));
            })->count();
    }
}
