<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Rol::create([
            'nombre' => 'Admin',
            'descripcion' => 'Administrador del sistema'
        ]);

        Rol::create([
            'nombre' => 'Vocero',
            'descripcion' => 'Aprendiz líder con permisos de gestión de grupos'
        ]);
    }
}
