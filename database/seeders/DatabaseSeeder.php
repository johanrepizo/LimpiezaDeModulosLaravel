<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamar al seeder de roles primero
        $this->call(RolesSeeder::class);

        // Crear usuarios administrativos
        Usuario::create([
            'correo' => 'admin@gestion.co',
            'nombres' => 'Administrador',
            'apellidos' => 'Sistema',
            'password' => Hash::make('password123'),
            'rol_id' => 1, // Admin
            'activo' => true,
            'primer_acceso' => false,
        ]);

        Usuario::create([
            'correo' => 'test@example.com',
            'nombres' => 'Usuario',
            'apellidos' => 'Prueba',
            'password' => Hash::make('password123'),
            'rol_id' => 2, // Vocero
            'activo' => true,
            'primer_acceso' => true,
        ]);

        // Cargar programas, fichas y aprendices
        $this->call(ProgramasSeeder::class);
        $this->call(FichasSeeder::class);
        $this->call(VocerosSeeder::class);
    }
}
