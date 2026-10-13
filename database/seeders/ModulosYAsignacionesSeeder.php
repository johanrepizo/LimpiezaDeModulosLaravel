<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\Ficha;
use App\Models\Asignacion;
use App\Models\Turno;
use App\Models\Grupo;
use App\Models\Evidencia;
use App\Models\Vocero;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class ModulosYAsignacionesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear o asegurar Módulos
        $modulosData = [
            [
                'nombre' => 'Laboratorio de Limpieza',
                'ubicacion' => 'Edificio Central - Piso 1',
                'capacidad' => 25,
                'descripcion' => 'Laboratorio principal de prácticas de aseo y desinfección',
                'activo' => true
            ],
            [
                'nombre' => 'Área de Almacenamiento',
                'ubicacion' => 'Bloque Talleres - Módulo A',
                'capacidad' => 15,
                'descripcion' => 'Bodega de insumos y equipos de mantenimiento',
                'activo' => true
            ],
            [
                'nombre' => 'Aula de Teoría 2',
                'ubicacion' => 'Edificio B - Piso 2',
                'capacidad' => 30,
                'descripcion' => 'Aula para capacitaciones teóricas y conferencias',
                'activo' => true
            ],
            [
                'nombre' => 'Ambiente de Cómputo 1',
                'ubicacion' => 'Bloque Tecnológico - Piso 1',
                'capacidad' => 35,
                'descripcion' => 'Sala de sistemas e informática',
                'activo' => true
            ],
        ];

        $modulos = [];
        foreach ($modulosData as $mData) {
            $modulos[] = Modulo::firstOrCreate(['nombre' => $mData['nombre']], $mData);
        }

        // 2. Fichas existentes
        $fichas = Ficha::all();
        if ($fichas->isEmpty()) {
            return;
        }

        // Asegurar que las fichas tengan número de ficha
        foreach ($fichas as $i => $ficha) {
            if (!$ficha->numero_ficha) {
                $ficha->update(['numero_ficha' => '202400' . ($i + 1)]);
            }
        }

        // 3. Crear asignaciones
        $asignaciones = [];
        foreach ($fichas as $ficha) {
            foreach ($modulos as $mod) {
                $asig = Asignacion::firstOrCreate(
                    [
                        'modulo_id' => $mod->id,
                        'ficha_id' => $ficha->id,
                    ],
                    [
                        'fecha_inicio' => Carbon::now()->subMonths(2)->toDateString(),
                        'dia_semana' => 1, // Lunes
                        'activo' => true
                    ]
                );
                $asignaciones[] = $asig;
            }
        }

        // 4. Crear Grupos para voceros si no existen
        $voceros = Vocero::with('aprendiz', 'ficha')->where('activo', true)->get();
        $grupos = [];
        foreach ($voceros as $idx => $vocero) {
            $asig = Asignacion::where('ficha_id', $vocero->ficha_id)->first();
            if ($asig) {
                $grupo = Grupo::firstOrCreate(
                    [
                        'vocero_id' => $vocero->id,
                        'asignacion_id' => $asig->id,
                    ],
                    [
                        'nombre' => 'Grupo ' . ($idx + 1),
                        'numero_grupo' => $idx + 1,
                        'activo' => true
                    ]
                );
                $grupos[] = $grupo;
            }
        }

        // 5. Crear Turnos (Cumplidos con Evidencias, Incumplidos, y Abierto hoy)
        $storageEvidenciasPath = storage_path('app/public/evidencias');
        if (!File::exists($storageEvidenciasPath)) {
            File::makeDirectory($storageEvidenciasPath, 0755, true);
        }

        // Crear dummy evidence image si no existe
        $dummyImagePath = $storageEvidenciasPath . '/demo_antes.jpg';
        if (!File::exists($dummyImagePath)) {
            // Generar un archivo mínimo de imagen
            File::put($dummyImagePath, base64_decode('/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA='));
        }
        $dummyImageDespues = $storageEvidenciasPath . '/demo_despues.jpg';
        if (!File::exists($dummyImageDespues)) {
            File::put($dummyImageDespues, base64_decode('/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wgALCAABAAEBAREA/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxA='));
        }

        foreach ($asignaciones as $index => $asig) {
            $grupo = Grupo::where('asignacion_id', $asig->id)->first() ?? Grupo::first();

            // A) Turnos cumplidos en el pasado (con evidencia)
            $turnoCumplido = Turno::firstOrCreate(
                [
                    'asignacion_id' => $asig->id,
                    'fecha_turno' => Carbon::now()->subDays(2)->toDateString(),
                ],
                [
                    'grupo_id' => $grupo ? $grupo->id : null,
                    'estado' => Turno::ESTADO_CUMPLIDO,
                ]
            );

            if ($turnoCumplido->wasRecentlyCreated || Evidencia::where('turno_id', $turnoCumplido->id)->count() == 0) {
                Evidencia::create([
                    'turno_id' => $turnoCumplido->id,
                    'tipo' => 'antes',
                    'ruta_archivo' => 'evidencias/demo_antes.jpg',
                    'observaciones' => 'Limpieza de laboratorio completada'
                ]);
                Evidencia::create([
                    'turno_id' => $turnoCumplido->id,
                    'tipo' => 'despues',
                    'ruta_archivo' => 'evidencias/demo_despues.jpg',
                    'observaciones' => 'Laboratorio desinfectado'
                ]);
            }

            // B) Turnos Incumplidos / Vencidos
            if ($index < 5) {
                Turno::firstOrCreate(
                    [
                        'asignacion_id' => $asig->id,
                        'fecha_turno' => Carbon::now()->subDays(7 + $index * 3)->toDateString(),
                    ],
                    [
                        'grupo_id' => $grupo ? $grupo->id : null,
                        'estado' => Turno::ESTADO_INCUMPLIDO,
                    ]
                );
            }

            // C) Turno para hoy
            if ($index === 0) {
                Turno::firstOrCreate(
                    [
                        'asignacion_id' => $asig->id,
                        'fecha_turno' => Carbon::now()->toDateString(),
                    ],
                    [
                        'grupo_id' => $grupo ? $grupo->id : null,
                        'estado' => Turno::ESTADO_ABIERTO,
                    ]
                );
            }

            // D) Turnos futuros
            Turno::firstOrCreate(
                [
                    'asignacion_id' => $asig->id,
                    'fecha_turno' => Carbon::now()->addDays(5 + $index)->toDateString(),
                ],
                [
                    'grupo_id' => $grupo ? $grupo->id : null,
                    'estado' => Turno::ESTADO_PENDIENTE,
                ]
            );
        }
    }
}
