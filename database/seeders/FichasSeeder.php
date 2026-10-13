<?php

namespace Database\Seeders;

use App\Models\Ficha;
use App\Models\Aprendiz;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FichasSeeder extends Seeder
{
    public function run(): void
    {
        // Ficha 1
        $ficha1 = Ficha::create([
            'programa_id' => 1,
            'nombre' => 'Ficha 2605741 - Servicios Limpieza Industrial',
            'numero_ficha' => '2605741',
            'jornada' => 'Diurna',
            'activo' => true,
            'fecha_inicio' => Carbon::now()->subMonths(3),
            'fecha_finalizacion' => Carbon::now()->addMonths(9),
        ]);

        // Aprendices para Ficha 1
        $aprendices_ficha1 = [
            [
                'id_sicefa' => 'AP-0001-001',
                'nombres' => 'Juan',
                'apellidos' => 'García López',
                'documento' => '1023456789',
                'correo' => 'juan.garcia@sena.edu.co',
                'telefono' => '3101234567',
            ],
            [
                'id_sicefa' => 'AP-0001-002',
                'nombres' => 'María',
                'apellidos' => 'Rodríguez Martínez',
                'documento' => '1023456790',
                'correo' => 'maria.rodriguez@sena.edu.co',
                'telefono' => '3102345678',
            ],
            [
                'id_sicefa' => 'AP-0001-003',
                'nombres' => 'Carlos',
                'apellidos' => 'Pérez Sánchez',
                'documento' => '1023456791',
                'correo' => 'carlos.perez@sena.edu.co',
                'telefono' => '3103456789',
            ],
            [
                'id_sicefa' => 'AP-0001-004',
                'nombres' => 'Ana',
                'apellidos' => 'Martínez González',
                'documento' => '1023456792',
                'correo' => 'ana.martinez@sena.edu.co',
                'telefono' => '3104567890',
            ],
            [
                'id_sicefa' => 'AP-0001-005',
                'nombres' => 'Luis',
                'apellidos' => 'López Ramírez',
                'documento' => '1023456793',
                'correo' => 'luis.lopez@sena.edu.co',
                'telefono' => '3105678901',
            ],
            [
                'id_sicefa' => 'AP-0001-006',
                'nombres' => 'Sofia',
                'apellidos' => 'González Flores',
                'documento' => '1023456794',
                'correo' => 'sofia.gonzalez@sena.edu.co',
                'telefono' => '3106789012',
            ],
        ];

        foreach ($aprendices_ficha1 as $data) {
            Aprendiz::create(array_merge($data, ['ficha_id' => $ficha1->id, 'activo' => true]));
        }

        // Ficha 2
        $ficha2 = Ficha::create([
            'programa_id' => 1,
            'nombre' => 'Ficha 2605742 - Servicios Limpieza Industrial',
            'numero_ficha' => '2605742',
            'jornada' => 'Nocturna',
            'activo' => true,
            'fecha_inicio' => Carbon::now()->subMonths(2),
            'fecha_finalizacion' => Carbon::now()->addMonths(10),
        ]);

        // Aprendices para Ficha 2
        $aprendices_ficha2 = [
            [
                'id_sicefa' => 'AP-0002-001',
                'nombres' => 'Pedro',
                'apellidos' => 'Hernández Castro',
                'documento' => '1023456795',
                'correo' => 'pedro.hernandez@sena.edu.co',
                'telefono' => '3107890123',
            ],
            [
                'id_sicefa' => 'AP-0002-002',
                'nombres' => 'Paola',
                'apellidos' => 'Vargas Acosta',
                'documento' => '1023456796',
                'correo' => 'paola.vargas@sena.edu.co',
                'telefono' => '3108901234',
            ],
            [
                'id_sicefa' => 'AP-0002-003',
                'nombres' => 'Diego',
                'apellidos' => 'Moreno Silva',
                'documento' => '1023456797',
                'correo' => 'diego.moreno@sena.edu.co',
                'telefono' => '3109012345',
            ],
            [
                'id_sicefa' => 'AP-0002-004',
                'nombres' => 'Jessica',
                'apellidos' => 'Torres Romero',
                'documento' => '1023456798',
                'correo' => 'jessica.torres@sena.edu.co',
                'telefono' => '3110123456',
            ],
            [
                'id_sicefa' => 'AP-0002-005',
                'nombres' => 'Andrés',
                'apellidos' => 'Ruiz Mendoza',
                'documento' => '1023456799',
                'correo' => 'andres.ruiz@sena.edu.co',
                'telefono' => '3111234567',
            ],
        ];

        foreach ($aprendices_ficha2 as $data) {
            Aprendiz::create(array_merge($data, ['ficha_id' => $ficha2->id, 'activo' => true]));
        }

        // Ficha 3
        $ficha3 = Ficha::create([
            'programa_id' => 2,
            'nombre' => 'Ficha 2605743 - Gestión Ambiental',
            'numero_ficha' => '2605743',
            'jornada' => 'Diurna',
            'activo' => true,
            'fecha_inicio' => Carbon::now()->subMonths(1),
            'fecha_finalizacion' => Carbon::now()->addMonths(11),
        ]);

        // Aprendices para Ficha 3
        $aprendices_ficha3 = [
            [
                'id_sicefa' => 'AP-0003-001',
                'nombres' => 'Valentina',
                'apellidos' => 'Gómez Díaz',
                'documento' => '1023456800',
                'correo' => 'valentina.gomez@sena.edu.co',
                'telefono' => '3112345678',
            ],
            [
                'id_sicefa' => 'AP-0003-002',
                'nombres' => 'Roberto',
                'apellidos' => 'Fuentes Quispe',
                'documento' => '1023456801',
                'correo' => 'roberto.fuentes@sena.edu.co',
                'telefono' => '3113456789',
            ],
            [
                'id_sicefa' => 'AP-0003-003',
                'nombres' => 'Camila',
                'apellidos' => 'Núñez Ochoa',
                'documento' => '1023456802',
                'correo' => 'camila.nunez@sena.edu.co',
                'telefono' => '3114567890',
            ],
            [
                'id_sicefa' => 'AP-0003-004',
                'nombres' => 'Fernando',
                'apellidos' => 'Jiménez Parra',
                'documento' => '1023456803',
                'correo' => 'fernando.jimenez@sena.edu.co',
                'telefono' => '3115678901',
            ],
            [
                'id_sicefa' => 'AP-0003-005',
                'nombres' => 'Isabela',
                'apellidos' => 'Reyes Castaño',
                'documento' => '1023456804',
                'correo' => 'isabela.reyes@sena.edu.co',
                'telefono' => '3116789012',
            ],
            [
                'id_sicefa' => 'AP-0003-006',
                'nombres' => 'Mateo',
                'apellidos' => 'Salazar Vega',
                'documento' => '1023456805',
                'correo' => 'mateo.salazar@sena.edu.co',
                'telefono' => '3117890123',
            ],
        ];

        foreach ($aprendices_ficha3 as $data) {
            Aprendiz::create(array_merge($data, ['ficha_id' => $ficha3->id, 'activo' => true]));
        }
    }
}
