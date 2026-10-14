<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vocero extends Model
{
    protected $table = 'voceros';
    protected $fillable = ['aprendiz_id', 'usuario_id', 'ficha_id', 'activo', 'fecha_activacion', 'fecha_desactivacion'];
    protected $dates = ['fecha_activacion', 'fecha_desactivacion'];
    public $timestamps = true;

    public function aprendiz()
    {
        return $this->belongsTo(Aprendiz::class, 'aprendiz_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function ficha()
    {
        return $this->belongsTo(Ficha::class, 'ficha_id');
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'vocero_id');
    }

    public function groups()
    {
        return $this->grupos();
    }
}
