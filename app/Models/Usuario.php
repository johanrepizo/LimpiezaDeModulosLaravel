<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $fillable = ['correo', 'nombres', 'password', 'remember_token', 'rol_id', 'activo', 'primer_acceso', 'intentos_fallidos', 'fecha_bloqueo'];
    protected $hidden = ['password', 'remember_token'];
    public $timestamps = true;

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function vocero()
    {
        return $this->hasOne(Vocero::class, 'usuario_id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'usuario_id');
    }

    public static function obtenerPorEmail(string $correo): ?self
    {
        return self::where('correo', trim(strtolower($correo)))->first();
    }

    public function marcarPrimerAccesoCompletado(): bool
    {
        return $this->update(['primer_acceso' => false]);
    }

    public function registrarIntentoFallido(): void
    {
        $intentos = $this->intentos_fallidos + 1;
        if ($intentos >= 3) {
            $this->update([
                'intentos_fallidos' => $intentos,
                'fecha_bloqueo' => now()->addMinutes(15)
            ]);
        } else {
            $this->update(['intentos_fallidos' => $intentos]);
        }
    }

    public function resetearIntentos(): void
    {
        $this->update(['intentos_fallidos' => 0, 'fecha_bloqueo' => null]);
    }

    public function actualizarPassword(string $hashPassword): bool
    {
        return $this->update(['password' => $hashPassword]);
    }

    public function estaVocero(): bool
    {
        return $this->rol_id == 2 && $this->vocero !== null;
    }
}
