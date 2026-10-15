<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvidenciaIntegrante extends Model
{
    protected $table = 'evidencia_integrantes';
    protected $fillable = ['evidencia_id', 'aprendiz_id', 'nombre_snapshot'];
    public $timestamps = false;

    public function evidencia()
    {
        return $this->belongsTo(Evidencia::class, 'evidencia_id');
    }

    public function aprendiz()
    {
        return $this->belongsTo(Aprendiz::class, 'aprendiz_id');
    }
}
