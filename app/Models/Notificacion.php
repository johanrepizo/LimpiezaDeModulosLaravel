<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    protected $fillable = ['usuario_id', 'titulo', 'descripcion', 'tipo', 'datos_json', 'leida', 'fecha_lectura'];
    protected $casts = ['datos_json' => 'array', 'leida' => 'boolean'];
    public $timestamps = true;

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function marcarComoLeida(): void
    {
        $this->update(['leida' => true, 'fecha_lectura' => now()]);
    }
}
