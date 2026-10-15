<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Grupo extends Model
{
    protected $table = 'grupos';
    protected $fillable = ['vocero_id', 'asignacion_id', 'nombre', 'fecha_limpieza', 'numero_grupo', 'activo'];
    protected $casts = [
        'fecha_limpieza' => 'date',
        'activo' => 'boolean',
    ];
    public $timestamps = true;

    public function vocero()
    {
        return $this->belongsTo(Vocero::class, 'vocero_id');
    }

    public function asignacion()
    {
        return $this->belongsTo(Asignacion::class, 'asignacion_id');
    }

    public function integrantes()
    {
        return $this->belongsToMany(Aprendiz::class, 'grupo_integrantes', 'grupo_id', 'aprendiz_id');
    }

    public function turnos()
    {
        return $this->hasMany(Turno::class, 'grupo_id');
    }

    public function historial()
    {
        return $this->hasMany(HistorialGrupo::class, 'grupo_id');
    }

    public static function proximoNombreGrupo(int $idVocero): string
    {
        $ultimoGrupo = self::where('vocero_id', $idVocero)
                           ->where('activo', true)
                           ->orderBy('numero_grupo', 'desc')
                           ->first();
        
        $numeroProximo = ($ultimoGrupo ? $ultimoGrupo->numero_grupo : 0) + 1;
        return "Grupo $numeroProximo";
    }

    public function renumerarGrupos(): void
    {
        $gruposActivos = self::where('vocero_id', $this->vocero_id)
                             ->where('activo', true)
                             ->orderBy('numero_grupo', 'asc')
                             ->get();

        foreach ($gruposActivos as $index => $grupo) {
            $grupo->update(['numero_grupo' => $index + 1]);
        }
    }

    public function registrarHistorial(string $desc, int $idUsuario, string $tipo): void
    {
        HistorialGrupo::create([
            'grupo_id' => $this->id,
            'descripcion' => $desc,
            'usuario_id' => $idUsuario,
            'tipo' => $tipo
        ]);
    }

    public function obtenerIntegrantes(): Collection
    {
        return $this->integrantes()->get();
    }

    public static function aprendicesOcupadosEnFicha(int $idFicha): Collection
    {
        return Aprendiz::whereIn('id', function ($query) use ($idFicha) {
            $query->select('aprendiz_id')
                  ->from('grupo_integrantes')
                  ->whereIn('grupo_id', function ($q) use ($idFicha) {
                      $q->select('id')
                        ->from('grupos')
                        ->where('activo', true);
                  });
        })->where('ficha_id', $idFicha)->get();
    }
}
