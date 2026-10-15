<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogSincronizacion extends Model
{
    protected $table = 'log_sincronizacion';
    protected $fillable = ['tipo', 'estado', 'mensajes', 'fecha_ejecucion'];
    public $timestamps = true;
}
