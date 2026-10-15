<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulos';
    protected $fillable = ['nombre', 'ubicacion', 'capacidad', 'descripcion', 'activo'];
    public $timestamps = true;

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'modulo_id');
    }

    public function getCapacidadMaximaAttribute()
    {
        return $this->capacidad;
    }
}
