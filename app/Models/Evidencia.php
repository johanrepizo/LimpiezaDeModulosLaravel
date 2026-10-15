<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    protected $table = 'evidencias';
    protected $fillable = [
        'turno_id', 'grupo_id', 'vocero_id', 'tipo', 
        'nombre_archivo', 'ruta_archivo', 'observaciones', 
        'fecha_subida', 'cumplimiento', 'fecha_creacion', 
        'created_at', 'updated_at'
    ];
    public $timestamps = true;

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'fecha_subida' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getCreatedAtAttribute($value)
    {
        if ($value) {
            return \Carbon\Carbon::parse($value);
        }
        if (!empty($this->attributes['fecha_creacion'])) {
            return \Carbon\Carbon::parse($this->attributes['fecha_creacion']);
        }
        if (!empty($this->attributes['fecha_subida'])) {
            return \Carbon\Carbon::parse($this->attributes['fecha_subida']);
        }
        return now();
    }

    const TIPO_ANTES = 'antes';
    const TIPO_DESPUES = 'despues';

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }

    public function integrantes()
    {
        return $this->hasMany(EvidenciaIntegrante::class, 'evidencia_id');
    }

    public static function obtenerParTurno(int $idTurno): array
    {
        $evidencias = self::where('turno_id', $idTurno)->get();
        return [
            'antes' => $evidencias->firstWhere('tipo', self::TIPO_ANTES),
            'despues' => $evidencias->firstWhere('tipo', self::TIPO_DESPUES)
        ];
    }

    public static function contarPorTurno(int $idTurno): int
    {
        return self::where('turno_id', $idTurno)->count();
    }

    public static function turnoCompleto(int $idTurno): bool
    {
        return self::where('turno_id', $idTurno)
                   ->where('tipo', self::TIPO_ANTES)
                   ->exists() && 
               self::where('turno_id', $idTurno)
                   ->where('tipo', self::TIPO_DESPUES)
                   ->exists();
    }

    public static function resumenPorAsignacion(int $idAsignacion): array
    {
        return self::whereHas('turno', function ($q) use ($idAsignacion) {
                    $q->where('asignacion_id', $idAsignacion);
                })
                ->selectRaw('COUNT(*) as total, SUM(CASE WHEN tipo = ? THEN 1 ELSE 0 END) as antes', [self::TIPO_ANTES])
                ->first()
                ->toArray();
    }
}
