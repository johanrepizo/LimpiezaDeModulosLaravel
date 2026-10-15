<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $table = 'asignaciones';
    protected $fillable = ['modulo_id', 'ficha_id', 'fecha_inicio', 'fecha_fin', 'dia_semana', 'activo'];
    protected $dates = ['fecha_inicio', 'fecha_fin'];
    public $timestamps = true;

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function ficha()
    {
        return $this->belongsTo(Ficha::class, 'ficha_id');
    }

    public function turnos()
    {
        return $this->hasMany(Turno::class, 'asignacion_id');
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'asignacion_id');
    }
}
