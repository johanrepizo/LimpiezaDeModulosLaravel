<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aprendiz extends Model
{
    protected $table = 'aprendices';
    protected $fillable = ['ficha_id', 'id_sicefa', 'nombres', 'apellidos', 'documento', 'correo', 'telefono', 'activo'];
    public $timestamps = true;

    public function ficha()
    {
        return $this->belongsTo(Ficha::class, 'ficha_id');
    }

    public function vocero()
    {
        return $this->hasOne(Vocero::class, 'aprendiz_id');
    }

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_integrantes', 'aprendiz_id', 'grupo_id');
    }
}
