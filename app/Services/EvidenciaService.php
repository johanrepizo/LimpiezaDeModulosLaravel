<?php

namespace App\Services;

use App\Models\Evidencia;
use App\Models\EvidenciaIntegrante;
use App\Models\Turno;
use Illuminate\Http\UploadedFile;
use Exception;

class EvidenciaService
{
    public function procesarUpload(UploadedFile $archivo, int $idTurno, string $tipo): string
    {
        // Validaciones
        $extensionesPermitidas = ['jpg', 'jpeg', 'png'];
        $ext = strtolower($archivo->getClientOriginalExtension());
        
        if (!in_array($ext, $extensionesPermitidas)) {
            throw new Exception('Extensión de archivo no permitida. Use JPG, JPEG o PNG');
        }

        if ($archivo->getSize() > 10 * 1024 * 1024) { // 10 MB
            throw new Exception('El archivo no puede exceder 10 MB');
        }

        // Generar nombre único
        $nombre = 'ev_t' . $idTurno . '_' . $tipo . '_' . uniqid() . '.' . $ext;

        // Almacenar archivo
        $ruta = $archivo->storeAs('evidencias', $nombre, 'public');

        return $ruta;
    }

    public function guardarEvidencia(int $idTurno, string $tipo, string $ruta, ?string $observaciones = null): Evidencia
    {
        return Evidencia::create([
            'turno_id' => $idTurno,
            'tipo' => $tipo,
            'ruta_archivo' => $ruta,
            'observaciones' => $observaciones
        ]);
    }

    public function guardarSnapshotIntegrantes(int $idEvidencia, int $idTurno): void
    {
        $turno = Turno::find($idTurno);
        if (!$turno || !$turno->grupo) return;

        $integrantes = $turno->grupo->integrantes()->get();

        foreach ($integrantes as $integrante) {
            EvidenciaIntegrante::create([
                'evidencia_id' => $idEvidencia,
                'aprendiz_id' => $integrante->id,
                'nombre_snapshot' => $integrante->nombres . ' ' . $integrante->apellidos
            ]);
        }
    }
}
