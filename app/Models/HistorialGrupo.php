<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialGrupo extends Model
{
    protected $table = 'historial_grupos';
    protected $fillable = ['grupo_id', 'descripcion', 'usuario_id', 'tipo'];
    public $timestamps = false;

    const TIPO_CREACION = 'Creación';
    const TIPO_EDICION = 'Edición';
    const TIPO_ELIMINACION = 'Eliminación';

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
