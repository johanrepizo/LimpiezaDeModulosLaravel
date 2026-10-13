<?php

namespace Database\Seeders;

use App\Models\Programa;
use Illuminate\Database\Seeder;

class ProgramasSeeder extends Seeder
{
    public function run(): void
    {
        Programa::create([
            'nombre' => 'Técnico en Servicios de Limpieza Industrial',
            'descripcion' => 'Programa de formación especializado en servicios de limpieza industrial con estándares internacionales',
            'nivel' => 'Técnico',
            'activo' => true,
        ]);

        Programa::create([
            'nombre' => 'Técnico en Gestión Ambiental y Saneamiento',
            'descripcion' => 'Programa enfocado en gestión ambiental, saneamiento e higiene en espacios públicos y privados',
            'nivel' => 'Técnico',
            'activo' => true,
        ]);
    }
}
